<?php namespace Piratmac\Smmm\Models;

use Model;
use Lang;
use Flash;
use October\Rain\Exception\ValidationException;
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
  protected $fillable = ['date', 'type', 'asset_count', 'unit_value', 'portfolio_id', 'asset_id', 'asset', 'portfolio'];

  /**
   * @var boolean Don't use timestamps
   */
  public $timestamps = false;

  protected $rules = [
    'asset_id' => [
      'required_if:type,arbitrage_buy',
      'required_if:type,arbitrage_sell',
      'required_if:type,asset_buy',
      'required_if:type,asset_refund',
      'required_if:type,asset_sell',
      'required_if:type,fee_asset',
      'required_if:type,forex',
      'required_if:type,profit_asset',
      'required_if:type,split_source',
      'required_if:type,split_target',
      'not_in:cash',
    ],
    'portfolio' => 'required',
    'date' => 'required|date',
    'type' => 'required',
    'asset_count' => 'numeric',
    'unit_value' => [
      'numeric',
      'required_if:type,arbitrage_buy',
      'required_if:type,arbitrage_sell',
      'required_if:type,asset_buy',
      'required_if:type,asset_refund',
      'required_if:type,asset_sell',
      'required_if:type,fee_asset',
      'required_if:type,forex',
      'required_if:type,profit_asset',
      'required_if:type,split_source',
      'required_if:type,split_target',
    ],

  ];



  // How the movement impacts the cash balance of the account
  private $impactOnBaseCurrency  = [
      'arbitrage_buy'         =>  0,
      'arbitrage_sell'        =>  0,
      'asset_buy'             => -1,
      'asset_refund'          =>  0,
      'asset_sell'            => +1,
      'cash_entry'            => +1,
      'cash_exit'             => -1,
      'company_funding'       => +1,
      'dividends'             => +1,
      'fee_asset'             =>  0,
      'fee_cash'              => -1,
      'forex'                 => -1,
      'interest'              => +1,
      'movement_fee'          => -1,
      'profit_asset'          =>  0,
      'profit_cash'           => +1,
      'split_source'          =>  0,
      'split_target'          =>  0,
      'taxes_cash'            => -1,
      'taxes_asset'           =>  0,
  ];
  // How the movement impacts the asset balance of the account
  private $impactOnAsset = [
      'arbitrage_buy'         => +1,
      'arbitrage_sell'        => -1,
      'asset_buy'             => +1,
      'asset_refund'          => +1,
      'asset_sell'            => -1,
      'cash_entry'            =>  0,
      'cash_exit'             =>  0,
      'company_funding'       =>  0,
      'dividends'             =>  0,
      'fee_asset'             => -1,
      'fee_cash'              =>  0,
      'forex'                 => +1,
      'interest'              =>  0,
      'movement_fee'          =>  0,
      'profit_asset'          => +1,
      'profit_cash'           =>  0,
      'split_source'          => -1,
      'split_target'          => +1,
      'taxes_cash'            =>  0,
      'taxes_asset'           => -1,
  ];

  // If the impact is only on base currency ==> asset count makes no sense
  private $hasAssetCount = [
      'arbitrage_buy'         =>  true,
      'arbitrage_sell'        =>  true,
      'asset_buy'             =>  true,
      'asset_refund'          =>  true,
      'asset_sell'            =>  true,
      'cash_entry'            =>  false,
      'cash_exit'             =>  false,
      'company_funding'       =>  false,
      'dividends'             =>  false,
      'fee_asset'             =>  true,
      'fee_cash'              =>  false,
      'forex'                 =>  true,
      'interest'              =>  false,
      'movement_fee'          =>  false,
      'profit_asset'          =>  true,
      'profit_cash'           =>  false,
      'split_source'          =>  true,
      'split_target'          =>  true,
      'taxes_cash'            =>  false,
      'taxes_asset'           =>  true,
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

    // Base currency (cash)-only impact ==> add this value for calculation
    if ($this->hasAssetCount[$this->type] == false)
      $this->unit_value = 1;


    // Checking asset balance
    if ($this->impactOnAsset[$this->type] < 0) {
      $heldAssets = $this->portfolio->getHeldAssets($this->date, $this->asset_id)->first();
      if (is_null($heldAssets) || (int)$heldAssets->pivot->asset_count < (int)$this->asset_count)
        throw new ValidationException (['asset_count' => trans('piratmac.smmm::lang.messages.negative_asset_count')]);
    }

    // Checking base currency (cash) balance
    if ($this->impactOnBaseCurrency[$this->type] < 0) {
      $cashBalance = $this->portfolio->getHeldAssets($this->date, $this->portfolio->base_currency)->first();
      if (is_null($cashBalance) || $cashBalance->pivot->asset_count < $this->asset_count)
        \Flash::warning(trans('piratmac.smmm::lang.messages.negative_cash_balance'));
    }


    // Checking date of movement against date of portfolio
    if ( (!is_null($this->portfolio->close_date) && $this->portfolio->close_date < $this->date)
        || $this->portfolio->open_date > $this->date)
      throw new ValidationException (['date' => trans('piratmac.smmm::lang.messages.movement_outside_portfolio_dates')]);


    // Adding the gain/loss upon selling the asset
    if ($this->type == 'asset_sell') {
      $heldAssets = $this->portfolio->getHeldAssets($this->date, $this->asset_id)->first();
      $this->unit_gain_upon_sell = $this->unit_value - $heldAssets->pivot->average_price_tag;
    }
  }

  public function afterSave () {
    $this->updatePortfolioHeldAssets();
  }

  public function afterDelete () {
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

    // Change in the cash balance
    $changeInCash  = $this->impactOnBaseCurrency[$this->type] * $this->unit_value * $this->asset_count;
    //dd($this->portfolio->base_currency->id);
    if ($changeInCash != 0)
      $portfolio->updateFutureBalance($this, $changeInCash, $this->portfolio->base_currency->id);

    // Change in the asset balance
    $changeInAsset = $this->impactOnAsset[$this->type] * $this->asset_count;
    if ($changeInAsset != 0)
      $portfolio->updateFutureBalance($this, $changeInAsset, $this->asset_id);

    // Clean-up of old values
    Db::table('piratmac_smmm_portfolio_contents')->where('asset_count', 0)->delete();
  }


}