<?php namespace Piratmac\Smmm\Models;

use Model;
use \October\Rain\Database\Traits\SoftDeleting;
use RainLab\User\Components\Account;
use Auth;
use October\Rain\Exception\ValidationException;

/**
 * Portfolio Model
 */
class Portfolio extends Model
{

  use \October\Rain\Database\Traits\Validation;
  use SoftDeleting;

  /**
   * @var string The database table used by the model.
   */
  public $table = 'piratmac_smmm_portfolios';

  /**
   * @var array Guarded fields
   */
  protected $guarded = [];

  /**
   * @var array Fillable fields
   */
  protected $fillable = ['description', 'open_date', 'close_date', 'broker', 'number'];


  protected $rules = [
    'description' => 'required',
    'open_date' => 'required|date',
    'close_date' => 'sometimes|date|after:open_date',

  ];

  /**
   * @var array Relations
   */
  public $hasOne = [];
  public $hasMany = ['movements' => 'Piratmac\Smmm\Models\PortfolioMovement'];
  public $belongsTo = ['User', 'foreignKey' => 'user_id'];
  public $belongsToMany = [
      'heldAssets' => [
        'Piratmac\Smmm\Models\Asset',
        'table'    => 'piratmac_smmm_portfolio_contents',
        'pivot' => ['date_from', 'date_to', 'asset_count', 'average_price_tag']
    ]];
  public $morphTo = [];
  public $morphOne = [];
  public $morphMany = [];
  public $attachOne = [];
  public $attachMany = [];


  /**
   * @var array Contents of the portfolio
   */
  public $contents = [];

  /**
   * @var array Movements related to the portfolio
   */
  public $movements = [];

  /**
   * @var array Total value of the portfolio
   */
  public $balance = [];


/**********************************************************************
                       Authorization checks
**********************************************************************/

  /**
    * Get current user
    * @return The current user ID
    */
  public function getUser() {
    if (Auth::check()) return Auth::getUser()->id;
    else return NULL;
  }

  /**
    * Checks user access before updating
    */
  public function checkUser($creation = false) {
    $user_id = $this->getUser();

    //In creation mode
    if ($creation) return true;

    //In modification mode
    if ($this->user_id == $user_id) return true;
    else return false;
  }


/**********************************************************************
                       Navigation
**********************************************************************/

  /**
   * Sets the "url" attribute with a URL to this object
   * @param string $pageName
   * @param Cms\Classes\Controller $controller
   */
  public function setUrl($controller, $page)
  {
      $params_view = [
          'portfolio_id' => $this->id,
          'action'       => 'view',
      ];
      $params_update = [
          'portfolio_id' => $this->id,
          'action'       => 'update',
      ];

      $this->url_view   = $controller->pageUrl($page, $params_view);
      $this->url_update = $controller->pageUrl($page, $params_update);
  }


/**********************************************************************
                       Portfolio valuation
**********************************************************************/
  /**
  * Determines the contents of the portfolio
  * @param date The date at which to determine the contents
  */
  public function getHeldAssets ($date = 0) {
    if ($date = 0 || !strtotime($date) || !isset($date))
      $date = date('Y-m-d');
    $this->contents = $this->heldAssets()
                           ->where('date_from', '<=', $date)
                           ->where(function ($query) use($date) {
                              $query->where('date_to', '>=', $date)
                                    ->orWherenull('date_to');})
                           ->get();
    return $this->contents;
  }
  /**
  * Calculates various amounts related to the portfolio
  */
  public function calculateValuation () {
    if ($this->contents->count() == 0) return;

    $this->contents->each(function ($heldAsset) {
      $heldAsset->pivot->totalBuyPrice = $heldAsset->pivot->average_price_tag * $heldAsset->pivot->asset_count;

      if (!isset($this->balance[$heldAsset->type])) $this->balance[$heldAsset->type] = 0;
      $this->balance[$heldAsset->type] += $heldAsset->pivot->totalBuyPrice;

      if (!isset($this->balance['total'])) $this->balance['total'] = 0;
      $this->balance['total'] += $heldAsset->pivot->totalBuyPrice;
    });

    return $this->contents;
  }


  /**
  * Sets all URLs for the assets
  * @return 0 if no error occurred
  */
  public function setHeldAssetsLinks ($controller, $page) {
    if ($this->contents->count() > 0) {
      $this->contents->each(function($heldAsset) use($controller, $page) {
        $heldAsset->setUrl($controller, $page);
      });
    }
    return 0;
  }

/**********************************************************************
                       Portfolio movements
**********************************************************************/
  /**
  * Gets the movements related to the account
  * @param dateFrom The earliest date - default to 01/01/1970
  * @param dateTo The last date - defaults to maximum date
  */
  public function getMovements ($dateFrom = 0, $dateTo = 0) {
    $query = $this->movements()->with(array('asset' => function($query) {
      $query->withTrashed();
    }));



    if ($dateFrom != 0 && strtotime($dateFrom) && isset($dateFrom))
      $query->where('date_from', '>=', $dateFrom);
    if ($dateTo != 0 && strtotime($dateTo) && isset($dateTo))
      $query->where('date_to', '<=', $dateTo);

    $this->movements = $query->get();
  }

  /**
  * Sets all URLs for the assets linked to movements
  * @return 0 if no error occurred
  */
  public function setMovementsLinks ($controller, $page) {
    if ($this->movements->count() > 0) {
      $this->movements->each(function($movement) use($controller, $page) {
        if (!is_null($movement->asset) && !$movement->asset->trashed())
          $movement->asset->setUrl($controller, $page);
      });
    }
    return 0;
  }

/**********************************************************************
                       User actions
**********************************************************************/

  /**
  * Modifies a portfolio
  */
  public function beforeValidate () {
    if ($this->close_date == '0000-00-00' || $this->close_date == '')
      $this->close_date = null;
  }

  /**
  * Checks the user in case of update
  */
  public function beforeUpdate () {
    // Check user
    if (!$this->checkUser())
      throw new ValidationException(trans('piratmac.smmm::lang.messages.fatal_error'));
  }

  /**
  * Checks the user in case of deletion
  */
  public function beforeDelete () {
    // Check user
    if (!$this->checkUser())
      throw new ValidationException(trans('piratmac.smmm::lang.messages.fatal_error'));
  }


  /**
  * Adds the user ID before creation
  */
  public function beforeCreate () {
    $this->user_id = $this->user_id?$this->user_id:$this->getUser();
  }

}