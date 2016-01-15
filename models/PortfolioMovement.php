<?php namespace Piratmac\Smmm\Models;

use Model;
use Lang;
use Validator;
use October\Rain\Exception\ValidationException;

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
    'asset_id' => 'required_if:type,asset_buy|required_if:type,asset_sell',
    'portfolio' => 'required',
    'date' => 'required|date',
    'type' => 'required',
    'asset_count' => 'numeric|required_if:type,asset_buy|required_if:type,asset_sell',
    'unit_value' => 'numeric|required',
    'fee' => 'numeric',

  ];

// Ajouter warning sur le solde du compte
// Ajouter erreur quand on vend plus que ce qu'on a
// Ajouter calculs de mise à jour des soldes


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

  public function onCreate () {
    $this->save();
  }
}