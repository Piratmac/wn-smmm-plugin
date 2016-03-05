<?php namespace Piratmac\Smmm\Models;

use Model;
use Lang;
use Flash;
use October\Rain\Exception\ValidationException;
use \October\Rain\Database\Traits\SoftDeleting;

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
    'asset' => ['Piratmac\Smmm\Models\Asset', 'key' => 'asset_id'],
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
    // Checking asset balance
    if ($this->type == 'asset_sell') {
      $heldAssets = $this->portfolio->getHeldAssets($this->date, $this->asset_id)->first();
      if (is_null($heldAssets) || (int)$heldAssets->pivot->asset_count < (int)$this->asset_count)
        throw new ValidationException (['asset_count' => trans('piratmac.smmm::lang.messages.negative_asset_count').$this->portfolio.'-'.$this->date]);
    }

    // Checking cash balance when withdrawing
    if ($this->type == 'cash_exit') {
      $heldAssets = $this->portfolio->getHeldAssets($this->date, 'cash')->first();
      if (is_null($heldAssets) || $heldAssets->pivot->asset_count < ($this->unit_value + $this->fee))
        Flash::warning(trans('piratmac.smmm::lang.messages.negative_cash_balance'));
    }

    // Checking cash balance for fees and when selling assets (the fee may use all the cash)
    if (in_array($this->type, ['fee', 'asset_sell'])) {
      $heldAssets = $this->portfolio->getHeldAssets($this->date, 'cash')->first();
      if (is_null($heldAssets) || $heldAssets->pivot->asset_count < $this->fee)
        Flash::warning(trans('piratmac.smmm::lang.messages.negative_cash_balance'));
    }

    // Checking cash balance for cash entry (not likely, but fee may be above what is entering the portfolio)
    if ($this->type == 'cash_entry') {
      $heldAssets = $this->portfolio->getHeldAssets($this->date, 'cash')->first();
      if (is_null($heldAssets) || ( $heldAssets->pivot->asset_count + $this->unit_value) < $this->fee)
        Flash::warning(trans('piratmac.smmm::lang.messages.negative_cash_balance'));
    }

    // Checking cash balance for assets buy
    if ($this->type == 'asset_buy') {
      $heldAssets = $this->portfolio->getHeldAssets($this->date, 'cash')->first();
      if (is_null($heldAssets) || $heldAssets->pivot->asset_count < ($this->asset_count * $this->unit_value + $this->fee))
        Flash::warning(trans('piratmac.smmm::lang.messages.negative_cash_balance'));
    }

    // Checking date of movement against date of portfolio
    if ( (!is_null($this->portfolio->close_date) && $this->portfolio->close_date < $this->date)
        || $this->portfolio->open_date > $this->date)
      throw new ValidationException (['asset_count' => trans('piratmac.smmm::lang.messages.movement_outside_portfolio_dates')]);


    // Adding the gain/loss upon selling the asset
    if ($this->type == 'asset_sell') {
      $heldAssets = $this->portfolio->getHeldAssets($this->date, $this->asset_id)->first();
      $this->unit_gain_upon_sell = $this->unit_value - $heldAssets->average_price_tag;
    }
  }

  public function afterSave () {
    $this->portfolio->updateHeldAssets($this);
  }

  public function afterDelete () {
    $this->asset_count = - $this->asset_count;
    $this->portfolio->updateHeldAssets($this);
  }

  public function afterRestore () {
    $this->portfolio->updateHeldAssets($this);
  }


}