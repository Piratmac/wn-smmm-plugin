<?php namespace Piratmac\Smmm\Components;

use Cms\Classes\ComponentBase;
use Flash;
use Lang;
use Cms\Classes\Page;
use Piratmac\Smmm\Models\Stock as StockModel;
use October\Rain\Exception\ApplicationException;
use October\Rain\Exception\SystemException;
use October\Rain\Exception\ValidationException;
use Illuminate\Support\Facades\Redirect;

class Stock extends ComponentBase
{
  /*
   * The stock being viewed / edited
   */
  public $stock;
  /*
   * Action: create, view or manage
   */
  public $action = 'view';

  /*
   * Page listing the stocks
   */
  public $stockListPage = [];



  public function componentDetails()
  {
    return [
      'name'        => 'piratmac.smmm::lang.components.stock_name',
      'description' => 'piratmac.smmm::lang.components.stock_description'
    ];
  }

  public function defineProperties()
  {
    return [
      'stock_id' => [
        'title'       => 'piratmac.smmm::lang.settings.stock_id',
        'description' => 'piratmac.smmm::lang.settings.stock_id_description',
        'default'     => '{{ :stock_id }}',
        'type'        => 'string'
      ],
      'action' => [
        'title'       => 'piratmac.smmm::lang.settings.action',
        'description' => 'piratmac.smmm::lang.settings.action_description',
        'default'     => '{{ :action }}',
        'type'        => 'string'
      ],
      'stockList' => [
        'title'       => 'piratmac.smmm::lang.settings.stocklist_page',
        'description' => 'piratmac.smmm::lang.settings.stocklist_description',
        'default'     => '',
        'type'        => 'dropdown'
      ],
    ];
  }
  public function getstockListOptions()
  {
    return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
  }
/**********************************************************************
                     Helper functions
**********************************************************************/

  protected function loadStock($stock_id)
  {
    if ($stock_id == '')
      //@TODO: Ajouter redirection vers page d'erreur
      throw new ApplicationException ('piratmac.smmm::lang.messages.error_no_id');


    $stock = StockModel::find($stock_id);
    return $stock;
  }
/**********************************************************************
                     User actions
**********************************************************************/
  public function onRun()
  {
    $this->addJs('/plugins/piratmac/smmm/assets/js/smmm.js');
    $this->action = $this->param('action');
    $this->stockListPage = $this->property('stockList');

    if ($this->action != 'create') {
      $this->stock = $this->loadStock($this->property('stock_id'));
    }

    if ($this->action == 'view') {
      $this->stock->getValues(NULL, NULL);
    }
  }

  public function init () {
    $this->stockListPage = $this->property('stockList');
  }

  public function onModifyStock() {
    $this->stock = $this->loadStock(post('id'));

    $result = $this->stock->onModify(post());
    $url = $this->pageUrl($this->page->baseFileName, ['stock_id' => $this->stock->id, 'action' => 'view']);
    Flash::success(trans('piratmac.smmm::lang.messages.success_modification'));
    return Redirect::to($url);
  }

  public function onCreateStock() {
    $this->stock = new StockModel(post());

    $result = $this->stock->onCreate();
    $url = $this->pageUrl($this->page->baseFileName, ['stock_id' => $this->stock->id, 'action' => 'view']);
    Flash::success(trans('piratmac.smmm::lang.messages.success_creation'));
    return Redirect::to($url);
  }


  public function onDeleteStock() {
    $this->stock = $this->loadStock(post('id'));

    $result = $this->stock->onDelete();
    $url = $this->pageUrl($this->stockListPage);
    Flash::success(trans('piratmac.smmm::lang.messages.success_deletion'));
    return Redirect::to($url);
  }



}