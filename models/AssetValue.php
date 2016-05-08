<?php namespace Piratmac\Smmm\Models;

use Model;
use Flash;

/**
 * AssetValue Model
 */
class AssetValue extends Model
{
  use \October\Rain\Database\Traits\Validation;

  /**
   * @var string The database table used by the model.
   */
  public $table = 'piratmac_smmm_asset_values';

  /**
   * @var array Guarded fields
   */
  protected $guarded = [];

  /**
   * @var array Fillable fields
   */
  protected $fillable = ['asset_id', 'date', 'value'];

  /**
   * @var array Rules
   */
  protected $rules = [
    'asset_id' => 'required',
    'date' => 'required|date|after:1800-01-01',
    'value' => 'required|numeric'
  ];

  /**
   * @var array Relations
   */
  public $hasOne = [];
  public $hasMany = [];
  public $belongsTo = ['asset' => 'Piratmac\Smmm\Models\Asset'];
  public $belongsToMany = [];
  public $morphTo = [];
  public $morphOne = [];
  public $morphMany = [];
  public $attachOne = [];
  public $attachMany = [];

  public $timestamps = false;


/**********************************************************************
                       User actions
**********************************************************************/

  public function beforeSave () {
    if ($this->asset->value()->where('date', $this->date)->count() > 0) {
      $this->asset->value()->where('date', $this->date)->delete();
      Flash::warning(trans('piratmac.smmm::lang.messages.existing_value_replaced'));
    }
  }
}