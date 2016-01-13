<?php namespace Piratmac\Smmm\Components;

use Cms\Classes\ComponentBase;
use Flash;
use Lang;
use Cms\Classes\Page;
use RainLab\User\Components\Account;
use Piratmac\Smmm\Models\Stock as StockModel;

class Stocks extends ComponentBase
{
  /**
   * List of stocks
   * @var array
  */
  public $stockList = array();

  /**
   * Page for stock details page
   * @var array
  */
  public $stockDetails = '';


  /**
   * Default options for the portfolio list
   * @var array
  */
  private $listOptionsDefault = array(
    'include_old' => false,
  );




  public function componentDetails()
  {
    return [
      'name'    => 'piratmac.smmm::lang.components.stocks_name',
      'description' => 'piratmac.smmm::lang.components.stocks_description'
    ];
  }

  public function defineProperties()
  {
    return [
      'stockDetails' => [
        'title'     => 'piratmac.smmm::lang.settings.stock_page',
        'description' => 'piratmac.smmm::lang.settings.stock_description',
        'type'    => 'dropdown',
        'default'   => '',
      ],
    ];
  }

  public function getstockDetailsOptions()
  {
    return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
  }


/**********************************************************************
             Helper functions
**********************************************************************/
  /**
   * Updates the list from database, in the right format for rendering the partial
   * @param options An array of options
   */
  private function updateStockList ($options = array())
  {
    // Defaulting options
    $options = array_merge ($this->listOptionsDefault, $options);

    //// Filter list according to connected user
    //if (Auth::check()) {
      //$this->stockList = PortfolioModel::where('user_id', '=', Auth::getUser()->id);
    //}
    //else {
      //$this->stockList = PortfolioModel::where('user_id', '=', NULL);
    //}

    $this->stockList = StockModel::where('title', '<>', '');

    // Applying options
    if ($options['include_old'] == false) {
      $this->stockList = $this->stockList->where(function ($query) { $query->where('display_to', '>=', date('Y-m-d'))->orWherenull('display_to');});
    }

    // Getting filtered list
    $this->stockList = $this->stockList->orderBy('title', 'ASC');
    $this->stockList = $this->stockList->get();


    if ($this->stockList && $this->stockList->count()) {
      $this->stockList->each(function($stock) {
        $stock->setUrl($this->controller, $this->stockDetails);
      });
    }
  }

/**********************************************************************
             User actions
**********************************************************************/
  /**
   * Initial display of the page
   * @param None
   */
  public function onRun ()
  {
    // Not executed for AJAX events
    $this->addJs('/plugins/piratmac/smmm/assets/js/smmm.js');
    $this->stockDetails = $this->property('stockDetails');
    $this->updateStockList();
  }


  /**
   * Updates the portfolio list from AJAX calls with specific options
   *
   * Possible options include:
   - - include_old: if set to 'true', old stocks will be displayed
   * @param options An array of options
   */
  public function onRefresh () {
    $this->stockDetails = $this->property('stockDetails');
    $options['include_old'] = (post('include_old') == 'true');
    $this->updateStockList($options);
  }

}