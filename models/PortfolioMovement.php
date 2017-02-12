<?php namespace Piratmac\Smmm\Models;

use Model;
use Lang;
use Flash;
use October\Rain\Exception\ValidationException;
use Barryvdh\DebugBar;
use Db;

/**
 * PortfolioMovement Model
 */
class PortfolioMovement extends Model
{
  use \October\Rain\Database\Traits\Validation;

  /**
   * @var string The database table used by the model.
   */
  public $table = 'piratmac_smmm_portfolio_movements';

  /**
   * @var array Guarded fields
   */
  protected $guarded = [];

  /**
   * @var array Fillable fields
   */
  protected $fillable = ['date', 'type', 'asset_count', 'unit_value', 'fee', 'portfolio_id', 'asset_id', 'asset', 'portfolio'];

  /**
   * @var boolean Don't use timestamps
   */
  public $timestamps = false;

  protected $rules = [
    'asset_id' => 'required_if:type,asset_buy|required_if:type,asset_sell|not_in:cash',
    'portfolio' => 'required',
    'date' => 'required|date',
    'type' => 'required',
    'asset_count' => 'numeric|required_if:type,asset_buy|required_if:type,asset_sell',
    'unit_value' => [
      'numeric',
      'required_if:type,asset_buy', // The only case when it's not required is "fee"
      'required_if:type,asset_sell',
      'required_if:type,cash_deposit',
      'required_if:type,cash_withdraw',
    ],
    'fee' => 'numeric',

  ];


  /**
   * @var array Relations
   */
  public $hasOne = [];
  public $hasMany = [];
  public $belongsTo = [
    'portfolio' => ['Piratmac\Smmm\Models\Portfolio', 'key' => 'portfolio_id'],
    'asset' => ['Piratmac\Smmm\Models\Asset', 'key' => 'asset_id', 'order' => 'title ASC'],
  ];
  public $belongsToMany = [];
  public $morphTo = [];
  public $morphOne = [];
  public $morphMany = [];
  public $attachOne = [];
  public $attachMany = [];

  public function getDropdownOptions($fieldName = null, $keyValue = null)
  {
    if (in_array($fieldName, ['type', 'source']))
      return array(NULL => '') + Lang::get('piratmac.smmm::lang.dropdowns.movement.'.$fieldName);
    else
      return ['' => '-- none --'];
  }


/**********************************************************************
                       User actions
**********************************************************************/

  public function beforeSave () {
    $this->date = substr($this->date, 0, 10);
    // Checking asset balance
    if ($this->type == 'asset_sell') {
      $heldAssets = $this->portfolio->getHeldAssets($this->date, $this->asset_id)->first();
      if (is_null($heldAssets) || (int)$heldAssets->pivot->asset_count < (int)$this->asset_count)
        throw new ValidationException (['asset_count' => trans('piratmac.smmm::lang.messages.negative_asset_count')]);
    }

    // Checking cash balance when withdrawing
    if ($this->type == 'cash_exit') {
      $heldAssets = $this->portfolio->getHeldAssets($this->date, 'cash')->first();
      if (is_null($heldAssets) || $heldAssets->pivot->asset_count < ($this->unit_value + $this->fee))
        \Flash::warning(trans('piratmac.smmm::lang.messages.negative_cash_balance'));
    }

    // Checking cash balance for fees and when selling assets (the fee may use all the cash)
    if (in_array($this->type, ['fee', 'asset_sell'])) {
      $heldAssets = $this->portfolio->getHeldAssets($this->date, 'cash')->first();
      if (is_null($heldAssets) || $heldAssets->pivot->asset_count < $this->fee)
        \Flash::warning(trans('piratmac.smmm::lang.messages.negative_cash_balance'));
    }

    // Checking cash balance for cash entry (not likely, but fee may be above what is entering the portfolio)
    if ($this->type == 'cash_entry') {
      $heldAssets = $this->portfolio->getHeldAssets($this->date, 'cash')->first();
      if (is_null($heldAssets) || ( $heldAssets->pivot->asset_count + $this->unit_value) < $this->fee)
        \Flash::warning(trans('piratmac.smmm::lang.messages.negative_cash_balance'));
    }

    // Checking cash balance for assets buy
    if ($this->type == 'asset_buy') {
      $heldAssets = $this->portfolio->getHeldAssets($this->date, 'cash')->first();
      if (is_null($heldAssets) || $heldAssets->pivot->asset_count < ($this->asset_count * $this->unit_value + $this->fee))
        \Flash::warning(trans('piratmac.smmm::lang.messages.negative_cash_balance'));
    }

    // Checking date of movement against date of portfolio
    if ( (!is_null($this->portfolio->close_date) && $this->portfolio->close_date < $this->date)
        || $this->portfolio->open_date > $this->date)
      throw new ValidationException (['date' => trans('piratmac.smmm::lang.messages.movement_outside_portfolio_dates')]);


    // Adding the gain/loss upon selling the asset
    if ($this->type == 'asset_sell') {
      $heldAssets = $this->portfolio->getHeldAssets($this->date, $this->asset_id)->first();
      //dd($heldAssets->pivot->average_price_tag);
      $this->unit_gain_upon_sell = $this->unit_value - $heldAssets->pivot->average_price_tag;
    }
  }

  public function afterSave () {
    $this->updatePortfolioHeldAssets();
  }

  public function afterDelete () {
    if ($this->asset_id == NULL)
      //Cash
      $this->unit_value = - $this->unit_value;
    else
      $this->asset_count = - $this->asset_count;
    $this->updatePortfolioHeldAssets();
  }

  public function afterRestore () {
    $this->updatePortfolioHeldAssets();
  }


  /**
   * Updates the "held asset" table based on the movements
   */
  public function updatePortfolioHeldAssets () {
    $portfolio = $this->portfolio;

    $impactedCashBalance = $portfolio->heldAssets()
                                     ->wherePivot('date_to', '>=', $this->date)
                                     ->wherePivot('asset_id', 'cash');

    switch ($this->type) {
      case 'cash_entry':
      case 'cash_exit':
      case 'fee':
        $changeInCash = ($this->type=='cash_entry'?+1:-1)*$this->unit_value - $this->fee;

        if ($impactedCashBalance->count() == 0) {
          // There is no history for that portfolio in cash ==> setting it up
          $heldCashData = [
            'date_from' => $this->date,
            'date_to'   => '9999-12-31',
            'asset_count' => $changeInCash,
            'average_price_tag' => 1,
          ];

          $portfolio->heldAssets()->attach('cash', $heldCashData);
        }
        else {
          // Update the cash balance
          //dd('second run');
          $portfolio->updateFutureBalance($this, $changeInCash, 'cash');
        }
        break;

      case 'asset_buy':
        //Update the cash balance
        $changeInCash = -1 * $this->asset_count * $this->unit_value - $this->fee;
        $portfolio->updateFutureBalance($this, $changeInCash, 'cash');

        $changeInAsset =     $this->asset_count;
        $portfolio->updateFutureBalance($this, $changeInAsset, $this->asset_id);


        break;
      case 'split_source':
        $changeInAsset = -1 * $this->asset_count;
        $portfolio->updateFutureBalance($this, $changeInAsset, $this->asset_id);

        break;
      case 'split_target':
        $changeInAsset =     $this->asset_count;
        $portfolio->updateFutureBalance($this, $changeInAsset, $this->asset_id);

        break;
      case 'asset_sell':
        // Update the cash balance
        $changeInCash  =   $this->asset_count * $this->unit_value - $this->fee;
        $portfolio->updateFutureBalance($this, $changeInCash, 'cash');

        $changeInAsset = - $this->asset_count;
        $portfolio->updateFutureBalance($this, $changeInAsset, $this->asset_id);
        break;
    }

    // Clean-up of old values
    Db::table('piratmac_smmm_portfolio_contents')->where('asset_count', 0)->delete();
  }


}