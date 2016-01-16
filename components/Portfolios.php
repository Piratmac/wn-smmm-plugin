<?php namespace Piratmac\Smmm\Components;

use Cms\Classes\ComponentBase;
use Auth;
use Flash;
use Lang;
use Cms\Classes\Page;
use RainLab\User\Components\Account;
use Piratmac\Smmm\Models\Portfolio as PortfolioModel;

class Portfolios extends ComponentBase
{



  /**
   * List of portfolios
   * @var array
  */
  public $portfolioList = array();


  /**
   * Page for portfolio details page
   * @var array
  */
  public $portfolioDetails = '';


  /**
   * Default options for the portfolio list
   * @var array
  */
  private $listOptionsDefault = array(
    'include_closed' => false,
  );

  /*
   * Folder containing the images related to this plugin
   */
  public $imageFolder = 'plugins/piratmac/smmm/assets/images';


  public function componentDetails()
  {
    return [
      'name'    => 'piratmac.smmm::lang.components.portfolios_name',
      'description' => 'piratmac.smmm::lang.components.portfolios_description'
    ];
  }

  public function defineProperties()
  {
    return [
      'portfolioDetails' => [
        'title'     => 'piratmac.smmm::lang.settings.portfolio_page',
        'description' => 'piratmac.smmm::lang.settings.portfolio_description',
        'type'    => 'dropdown',
        'default'   => '',
      ],
    ];
  }

  public function getportfolioDetailsOptions()
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
  private function updatePortfolioList ($options = array())
  {
    // Defaulting options
    $options = array_merge ($this->listOptionsDefault, $options);

    // Filter list according to connected user
    if (Auth::check()) {
      $this->portfolioList = PortfolioModel::where('user_id', '=', Auth::getUser()->id);
    }
    else {
      $this->portfolioList = PortfolioModel::where('user_id', '=', NULL);
    }

    // Applying options
    if ($options['include_closed'] == false) {
      $this->portfolioList = $this->portfolioList->where(function ($query) { $query->where('close_date', '>=', date('Y-m-d'))->orWherenull('close_date');});
    }

    // Getting filtered list
    $this->portfolioList = $this->portfolioList->orderBy('description', 'ASC');
    $this->portfolioList = $this->portfolioList->get();


    if ($this->portfolioList && $this->portfolioList->count()) {
      $this->portfolioList->each(function($portfolio) {
        $portfolio->setUrl($this->controller, $this->portfolioDetails);
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
    $this->portfolioDetails = $this->property('portfolioDetails');
    $this->updatePortfolioList();
  }


  /**
   * Updates the portfolio list from AJAX calls with specific options
   *
   * Possible options include:
   - - include_closed: if set to 'true', closed portfolios will be displayed
   * @param options An array of options
   */
  public function onRefresh () {
    $this->portfolioDetails = $this->property('portfolioDetails');
    $options['include_closed'] = (post('include_closed') == 'true');
    $this->updatePortfolioList($options);
  }
}