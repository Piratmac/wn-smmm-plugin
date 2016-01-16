<?php namespace Piratmac\Smmm\Components;

use Cms\Classes\ComponentBase;
use Flash;
use Lang;
use Cms\Classes\Page;
use RainLab\User\Components\Account;
use Piratmac\Smmm\Models\Asset as AssetModel;

class Assets extends ComponentBase
{
  /**
   * List of assets
   * @var array
  */
  public $assetList = array();

  /**
   * Page for asset details page
   * @var array
  */
  public $assetDetails = '';


  /**
   * Default options for the portfolio list
   * @var array
  */
  private $listOptionsDefault = array(
    'include_old' => false,
  );


  /*
   * Folder containing the images related to this plugin
   */
  public $imageFolder = 'plugins/piratmac/smmm/assets/images';



  public function componentDetails()
  {
    return [
      'name'    => 'piratmac.smmm::lang.components.assets_name',
      'description' => 'piratmac.smmm::lang.components.assets_description'
    ];
  }

  public function defineProperties()
  {
    return [
      'assetDetails' => [
        'title'     => 'piratmac.smmm::lang.settings.asset_page',
        'description' => 'piratmac.smmm::lang.settings.asset_description',
        'type'    => 'dropdown',
        'default'   => '',
      ],
    ];
  }

  public function getassetDetailsOptions()
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
  private function updateAssetList ($options = array())
  {
    // Defaulting options
    $options = array_merge ($this->listOptionsDefault, $options);

    //// Filter list according to connected user
    //if (Auth::check()) {
      //$this->assetList = PortfolioModel::where('user_id', '=', Auth::getUser()->id);
    //}
    //else {
      //$this->assetList = PortfolioModel::where('user_id', '=', NULL);
    //}

    $this->assetList = AssetModel::where('title', '<>', '');

    // Applying options
    if ($options['include_old'] == false) {
      $this->assetList = $this->assetList->where(function ($query) { $query->where('display_to', '>=', date('Y-m-d'))->orWherenull('display_to');});
    }

    // Getting filtered list
    $this->assetList = $this->assetList->orderBy('title', 'ASC');
    $this->assetList = $this->assetList->get();


    if ($this->assetList && $this->assetList->count()) {
      $this->assetList->each(function($asset) {
        $asset->setUrl($this->controller, $this->assetDetails);
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
    $this->assetDetails = $this->property('assetDetails');
    $this->updateAssetList();
  }


  /**
   * Updates the portfolio list from AJAX calls with specific options
   *
   * Possible options include:
   - - include_old: if set to 'true', old assets will be displayed
   * @param options An array of options
   */
  public function onRefresh () {
    $this->assetDetails = $this->property('assetDetails');
    $options['include_old'] = (post('include_old') == 'true');
    $this->updateAssetList($options);
  }

}