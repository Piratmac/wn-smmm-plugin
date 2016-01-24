<?php namespace Piratmac\Smmm\Models;

use Model;
use Lang;
use \October\Rain\Database\Traits\SoftDeleting;
use October\Rain\Exception\ApplicationException;



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
  protected $fillable = ['id', 'title', 'source', 'type'];


  protected $rules = [
    'id' => 'required|unique:piratmac_smmm_assets|regex:#^[0-9A-Za-z.]*$#',
    'type' => 'required',
  ];
  /**
   * @var array Whether the ID is determined by the database or not
   */
  public $incrementing = false;


  /**
   * @var array Relations
   */
  public $hasOne = [];
  public $hasMany = ['value' => 'Piratmac\Smmm\Models\AssetValue'];
  public $belongsTo = [];
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

  public function getDropdownOptions($fieldName = null, $keyValue = null)
  {
    if (in_array($fieldName, ['type', 'source'])) {
      $dropdown = [NULL => ''] + Lang::get('piratmac.smmm::lang.dropdowns.asset.'.$fieldName);
      if ($fieldName == 'type') unset ($dropdown['cash']);
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
   * @param string $dateRouding How is the date interpreted if the value is missing: 'exact' will return NULL, 'rounded' will seek the closest existing value (only possible when both dates are equal)
   */
  public function getValues($dateFrom = '', $dateTo = '', $dateRounding = 'exact')
  {
    if ($dateFrom === '') $dateFrom = date('Y-m-d');
    if ($dateTo === '')   $dateTo = date('Y-m-d');

    $query = $this->value();

    //Applying the dateFrom restriction
    if ($dateFrom != NULL) $query = $query->where('date', '>=', $dateFrom);
    if ($dateTo != NULL)   $query = $query->where('date', '<=', $dateTo);

    $this->valueHistory = $query->get();
  }


/**********************************************************************
                       User actions
**********************************************************************/

  /**
  * Deletes a asset
  * @return 0 if no error occurred
  */
  public function beforeDelete () {
    if ($this->portfolios()->count()>0)
      throw new ApplicationException(trans('piratmac.smmm::lang.messages.asset_in_use'));
  }



}