<?php namespace Piratmac\Smmm\Models;

use Model;
use Lang;
use Flash;
use \October\Rain\Database\Traits\SoftDeleting;
use October\Rain\Exception\ApplicationException;
use October\Rain\Exception\ValidationException;


/**
 * Asset Model
 */
class Asset extends Model
{
  use \October\Rain\Database\Traits\Validation;
  use SoftDeleting;

  /**
   * @var string The database table used by the model.
   */
  public $table = 'piratmac_smmm_assets';

  /**
   * @var array Guarded fields
   */
  protected $guarded = [];

  /**
   * @var array Fillable fields
   */
  protected $fillable = ['id', 'title', 'source', 'type', 'synced', 'base_currency_id'];


  protected $rules = [
    'id' => 'required|unique:piratmac_smmm_assets|regex:#^[0-9A-Za-z.^-]*$#',
    'type' => 'required',
  ];
  /**
   * @var array Whether the ID is determined by the database or not
   */
  public $incrementing = false;


  /**
   * @var array Relations
   */
  public $hasOne = [
  ];
  public $hasMany = ['value' => 'Piratmac\Smmm\Models\AssetValue'];
  public $belongsTo = [
    'baseCurrency' => ['Piratmac\Smmm\Models\Asset', 'order' => 'title ASC', 'conditions' => 'type = \'cash\''],
  ];
  public $belongsToMany = [
      'portfolios' => [
        'Piratmac\Smmm\Models\Portfolio',
        'table'    => 'piratmac_smmm_portfolio_contents',
        'pivot' => ['date_from', 'date_to', 'asset_count', 'average_price_tag']
    ]];
  public $morphTo = [];
  public $morphOne = [];
  public $morphMany = [];
  public $attachOne = [];
  public $attachMany = [];


  /**
   * @var array Value history of this asset
   */
  public $valueHistory = [];

  /**
   * @var array Indicators on the evolution of the value
   */
  public $performanceHistory = [];

  public function getDropdownOptions($fieldName = null, $keyValue = null)
  {
    if (in_array($fieldName, ['type', 'source'])) {
      $dropdown = [NULL => ''] + Lang::get('piratmac.smmm::lang.dropdowns.asset.'.$fieldName);
      return $dropdown;
    }
    else
      return ['' => '-- none --'];
  }


/**********************************************************************
                       Various
**********************************************************************/

  /**
   * Sets the "url" attribute with a URL to this object
   * @param string $pageName
   * @param Cms\Classes\Controller $controller
   */
  public function setUrl($controller, $page)
  {
      $params_view = [
          'asset_id' => $this->id,
          'action'       => 'view',
      ];
      $params_update = [
          'asset_id' => $this->id,
          'action'       => 'update',
      ];

      $this->url_view   = $controller->pageUrl($page, $params_view);
      $this->url_update = $controller->pageUrl($page, $params_update);
  }

  /**
   * Fetches the values of the asset during a given timeframe
   * @param string $dateFrom the earliest date (NULL for all values)
   * @param string $dateTo the last date (NULL for all values)
   * @param string $dateRounding How is the date interpreted if the value is missing: 'exact' will return NULL, 'rounded' will seek the closest existing value (only possible when both dates are equal)
   */
  public function getValues($dateFrom = '', $dateTo = '', $dateRounding = 'exact')
  {
    if ($dateFrom === '') $dateFrom = date('Y-m-d');
    if ($dateTo === '')   $dateTo = date('Y-m-d');

    $query = $this->value();

    //Applying the dateFrom restriction
    if ($dateFrom != NULL) $query = $query->where('date', '>=', $dateFrom);
    if ($dateTo != NULL)   $query = $query->where('date', '<=', $dateTo);

    $this->valueHistory = $query->orderBy('date', 'desc')->get();
  }


  /**
   * Calculates past performance of the asset
   * @param string $dateList A list of dates upon which to calculate the value
   */
  public function getPastPerformance($dateList)
  {
    if (!is_array($dateList)) return;

    $valueToday = $this->getValueAsOfDate(date('Y-m-d'))['value'];

    foreach ($dateList as $dateCode => $date) {
      if ($dateCode == 'today')
        $this->performanceHistory[$dateCode] = ['value' => $valueToday, 'evolution' => 0];
      else {
        $value = $this->getValueAsOfDate($date);
        if (is_null($value))
          $this->performanceHistory[$dateCode] = ['value' => 'N/A', 'evolution' => 'N/A'];
        else
          $this->performanceHistory[$dateCode] = ['value' => $value['value'], 'evolution' => ($valueToday - $value['value'])/$value['value']*100];
      }
    }
  }

  /**
   * Gets the latest value before the provided date
   * @param string $date A date
   * @param string $unit The unit in which to return the value (another asset ID)
   */
  public function getValueAsOfDate ($date, $unit = '')
  {
    // If no unit is provided or there is no base currency
    if ($unit == '' || $this->baseCurrency == NULL)
      return new AssetValue(['date' => $date, 'value' => 1]);

    // Get value in base currency
    $value = $this->value()->where('date', '<=', $date)->orderBy('date', 'DESC')->first();

    //  If the base currency is not the expected unit, convert it
    if ($this->baseCurrency->id != $unit->id) {
      $BaseValueInUnit = $this->baseCurrency->value()->where('date', '<=', $date)->orderBy('date', 'DESC')->first();
      $value->value = $value->value * $BaseValueInUnit->value;
    }
    return $value;
  }


/**********************************************************************
                       User actions
**********************************************************************/

  /**
  * Deletes a asset
  */
  public function beforeDelete () {
    if ($this->portfolios()->count()>0)
      throw new ApplicationException(trans('piratmac.smmm::lang.messages.asset_in_use'));
  }

  /**
  * After update of the asset
  */
  public function beforeSave () {
    if ($this->portfolios()->wherePivot('date_to', '>=', date('Y-m-d'))->count() > 0 && !$this->synced)
      Flash::warning(trans('piratmac.smmm::lang.messages.used_and_not_synced'));

    $this->base_currency_id = post('Asset[baseCurrency]');
  }

  /**
  * Adding a new value
  */
  public function onAddValue ($userData) {
    $value = new AssetValue($userData);
    $this->value()->save($value);
  }

  /**
  * Deleting an existing value
  */
  public function onDeleteValue ($date) {
    $this->value()->where('date', $date)->delete();
  }

}