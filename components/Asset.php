<?php namespace Piratmac\Smmm\Components;

use Cms\Classes\ComponentBase;
use Flash;
use Lang;
use Cms\Classes\Page;
use Piratmac\Smmm\Models\Asset as AssetModel;
use Piratmac\Smmm\Controllers\Asset as AssetController;

use October\Rain\Exception\ApplicationException;
use Illuminate\Support\Facades\Redirect;

class Asset extends ComponentBase
{
  /*
   * The asset being viewed / edited
   */
  public $asset;
  /*
   * Action: create, view or update
   */
  public $action = 'view';

  /*
   * Page listing the assets
   */
  public $assetListPage = [];

  /*
   * Folder containing the images related to this plugin
   */
  public $imageFolder = '/plugins/piratmac/smmm/assets/images';


  public function componentDetails()
  {
    return [
      'name'        => 'piratmac.smmm::lang.components.asset_name',
      'description' => 'piratmac.smmm::lang.components.asset_description'
    ];
  }

  public function defineProperties()
  {
    return [
      'asset_id' => [
        'title'       => 'piratmac.smmm::lang.settings.asset_id',
        'description' => 'piratmac.smmm::lang.settings.asset_id_description',
        'default'     => '{{ :asset_id }}',
        'type'        => 'string'
      ],
      'action' => [
        'title'       => 'piratmac.smmm::lang.settings.action',
        'description' => 'piratmac.smmm::lang.settings.action_description',
        'default'     => '{{ :action }}',
        'type'        => 'string'
      ],
      'assetList' => [
        'title'       => 'piratmac.smmm::lang.settings.assetlist_page',
        'description' => 'piratmac.smmm::lang.settings.assetlist_description',
        'default'     => '',
        'type'        => 'dropdown'
      ],
    ];
  }
  public function getassetListOptions()
  {
    return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
  }
/**********************************************************************
                     Helper functions
**********************************************************************/

  protected function loadAsset($asset_id)
  {
    if ($asset_id == '')
      throw new ApplicationException ('piratmac.smmm::lang.messages.error_no_id');


    $asset = AssetModel::find($asset_id);
    return $asset;
  }
/**********************************************************************
                     Display of the page
**********************************************************************/
  public function onRun()
  {
    $this->addJs('/plugins/piratmac/smmm/assets/js/smmm.js');
    $this->action = $this->param('action');
    $this->assetListPage = $this->property('assetList');

    switch ($this->action) {
      case 'create':
        $formController = new AssetController();
        $formController->create($this->action);
        $this->page['form'] = $formController;
        break;

      case 'update':
        $formController = new AssetController();
        $formController->update($this->property('asset_id'), $this->action);
        $this->page['form'] = $formController;
        break;

      case 'view':
        $this->asset = $this->loadAsset($this->property('asset_id'));
        $this->asset->getValues (date('Y-m-d', strtotime('1 month ago')), date('Y-m-d'));
        $this->asset->getPastPerformance(
          ['today'    => date('Y-m-d'),
           'jan_1st'  => date('Y-01-01'),
           '3_months' => date('Y-m-d', strtotime('3 months ago')),
           '1_year'   => date('Y-m-d', strtotime('1 year ago')),
           '3_years'  => date('Y-m-d', strtotime('3 years ago')),
           '5_years'  => date('Y-m-d', strtotime('5 years ago')),
          ]);

        # Adding the Pickaday component (for date picker)
        $this->addJs('/modules/backend/formwidgets/datepicker/assets/js/build-min.js');
        $this->addCss('/modules/backend/formwidgets/datepicker/assets/vendor/pikaday/css/pikaday.css');
        break;
    }
  }

  public function init () {
    $this->assetListPage = $this->property('assetList');
  }


/**********************************************************************
                     User actions
**********************************************************************/

  public function onUpdateAsset() {
    $this->asset = $this->loadAsset($this->property('asset_id'));
    $result = $this->asset->update(post('Asset'));

    $url = $this->pageUrl($this->page->baseFileName, ['asset_id' => $this->asset->id, 'action' => 'view']);
    Flash::success(trans('piratmac.smmm::lang.messages.success_modification'));
    return Redirect::to($url);
  }

  public function onCreateAsset() {
    $this->asset = AssetModel::create(post(('Asset')));

    $url = $this->pageUrl($this->page->baseFileName, ['asset_id' => $this->asset->id, 'action' => 'view']);
    Flash::success(trans('piratmac.smmm::lang.messages.success_creation'));
    return Redirect::to($url);
  }


  public function onDeleteAsset() {
    $this->asset = $this->loadAsset($this->property('asset_id'));
    $result = $this->asset->delete();

    $url = $this->pageUrl($this->assetListPage);
    Flash::success(trans('piratmac.smmm::lang.messages.success_deletion'));
    return Redirect::to($url);
  }


  public function onGetAssetValues () {
    $this->asset = $this->loadAsset($this->property('asset_id'));
    \Debugbar::info(post());
    $this->asset->getValues (post('valueHistory_datefrom'), post('valueHistory_dateto'));
    \Debugbar::info($this->asset->valueHistory);
  }

  public function onAddAssetValue () {
    $this->asset = $this->loadAsset($this->property('asset_id'));

    $this->asset->onAddValue (post('newValue'));

    $url = $this->pageUrl($this->page->baseFileName, ['asset_id' => $this->asset->id, 'action' => 'view']);
    Flash::success(trans('piratmac.smmm::lang.messages.success_creation'));
    return Redirect::to($url);
  }


  public function onDeleteAssetValue () {
    $this->asset = $this->loadAsset($this->property('asset_id'));

    $this->asset->onDeleteValue (post('date'));

    $url = $this->pageUrl($this->page->baseFileName, ['asset_id' => $this->asset->id, 'action' => 'view']);
    Flash::success(trans('piratmac.smmm::lang.messages.success_deletion'));
    return Redirect::to($url);
  }



}