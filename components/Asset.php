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
                     User actions
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
        $this->asset->getValues(NULL, NULL);
        break;
    }
  }

  public function init () {
    $this->assetListPage = $this->property('assetList');
  }

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



}