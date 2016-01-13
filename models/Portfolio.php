<?php namespace Piratmac\Smmm\Models;

use Model;
use \October\Rain\Database\Traits\SoftDeleting;
use RainLab\User\Components\Account;
use Auth;
use October\Rain\Exception\ApplicationException;
use October\Rain\Exception\SystemException;
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
      'heldStocks' => [
        'Piratmac\Smmm\Models\Stock',
        'table'    => 'piratmac_smmm_portfolio_contents',
        'pivot' => ['date_from', 'date_to', 'stock_count', 'average_price_tag']
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
  public $balance = ['stock' => 0, 'cash' => 0, 'bond' => 0, 'total' => 0, 'mixed' => 0];


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
  public function getHeldStocks ($date = 0) {
    if ($date = 0 || !strtotime($date) || !isset($date))
      $date = date('Y-m-d');
    $this->contents = $this->heldStocks()
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

    $this->contents->each(function ($heldStock) {
      $heldStock->pivot->totalBuyPrice = $heldStock->pivot->average_price_tag * $heldStock->pivot->stock_count;
      $this->balance[$heldStock->type] += $heldStock->pivot->totalBuyPrice;
      $this->balance['total'] += $heldStock->pivot->totalBuyPrice;
    });

    return $this->contents;
  }


  /**
  * Sets all URLs for the stocks
  * @return 0 if no error occurred
  */
  public function setHeldStocksLinks ($controller, $page) {
    if ($this->contents->count() > 0) {
      $this->contents->each(function($heldStock) use($controller, $page) {
        $heldStock->setUrl($controller, $page);
      });
    }
    return 0;
  }

  /**
  * Sets all URLs for the stocks
  * @return 0 if no error occurred
  */
  public function setMovementsLinks ($controller, $page) {
    if ($this->movements->count() > 0) {
      $this->movements->each(function($movement) use($controller, $page) {
        if ($movement->stock != NULL)
          $movement->stock->setUrl($controller, $page);
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
    $query = $this->movements();

    if ($dateFrom != 0 && strtotime($dateFrom) && isset($dateFrom))
      $query->where('date_from', '>=', $dateFrom);
    if ($dateTo != 0 && strtotime($dateTo) && isset($dateTo))
      $query->where('date_to', '<=', $dateTo);

    $this->movements = $query->get();
  }


/**********************************************************************
                       User actions
**********************************************************************/

  /**
  * Modifies a portfolio
  * @return 0 if no error occurred
  */
  public function onUpdate ($userData) {
    // Check user
    if (!$this->checkUser())
      throw new ValidationException(trans('piratmac.smmm::lang.messages.fatal_error'));

    if ($userData['close_date'] == '0000-00-00' || $userData['close_date'] == '')
      $userData['close_date'] = null;

    $this->update($userData);
    return 0;
  }


  /**
  * Creates a portfolio
  * @return 0 if no error occurred
  */
  public function onCreate () {
    // Check user
    if (!$this->user_id = $this->getUser())
      throw new ValidationException(trans('piratmac.smmm::lang.messages.fatal_error'));

    if ($this->close_date == '0000-00-00' || $this->close_date == '')
      $this->close_date = null;

    $this->save();
    return 0;
  }

  /**
  * Deletes a portfolio
  * @return 0 if no error occurred
  */
  public function onDelete () {
    // Check user
    if (!$this->user_id = $this->getUser())
      throw new ValidationException(trans('piratmac.smmm::lang.messages.fatal_error'));

    $this->delete();
    return 0;
  }
}