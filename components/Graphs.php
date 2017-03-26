<?php namespace Piratmac\Smmm\Components;

use Cms\Classes\ComponentBase;
use Auth;
use Lang;
use Piratmac\Smmm\Models\Portfolio as PortfolioModel;
use Piratmac\Smmm\Models\PortfolioMovement;
use Piratmac\Smmm\Models\Asset as AssetModel;
use Piratmac\Smmm\Models\AssetValue;
use October\Rain\Exception\ApplicationException;

class Graphs extends ComponentBase
{
  public function componentDetails()
  {
    return [
      'name'    => 'piratmac.smmm::lang.components.graphs_name',
      'description' => 'piratmac.smmm::lang.components.graphs_description'
    ];
  }

  public function defineProperties()
  {
    return [];
  }


  /*
   * The list of all portfolios
   */
  public $portfolios = [];

  /*
   * The list of all assets
   */
  public $assets = [];

  /*
   * The dots on the graph, in the right format for the JS library
   */
  public $timeDots_rendering = [];


/**********************************************************************
                     Helper functions
**********************************************************************/



/**********************************************************************
                     User actions
**********************************************************************/
  public function onRun()
  {
  }

  public function init () {
    $this->addJs('/plugins/piratmac/smmm/assets/js/smmm.js');
    $this->addJs('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.0/Chart.min.js');
    $this->addJs('/modules/system/assets/ui/js/foundation.baseclass.js');
    $this->addJs('/modules/system/assets/ui/js/foundation.controlutils.js');
    $this->addJs('/modules/system/assets/ui/vendor/moment/moment.js');
    $this->addJs('/modules/system/assets/ui/vendor/moment/moment-timezone-with-data.js');
    $this->addJs('/modules/system/assets/ui/vendor/pikaday/js/pikaday.js');
    $this->addJs('/modules/system/assets/ui/vendor/pikaday/js/pikaday.jquery.js');
    $this->addJs('/modules/system/assets/ui/js/datepicker.js');
    $this->addCss('/modules/system/assets/ui/vendor/pikaday/css/pikaday.css');

    $this->portfolios = PortfolioModel::get();
    $this->assets = AssetModel::all()->sortBy('title');

    $this->portfolios->each(function ($portfolio) {
      $portfolio->heldAssetsList = $portfolio->heldAssets->sortBy('title')->unique();
    });
  }

  public function onGraphRender () {
    if (strtotime(post('basis_date')) < strtotime(post('start_date')) || strtotime(post('basis_date')) > strtotime(post('end_date')) )
      throw new ApplicationException(trans('piratmac.smmm::lang.messages.basis_date_outside_scope'));
    if (!is_array(post('asset')) && !is_array(post('portfolio')))
      throw new ApplicationException(trans('piratmac.smmm::lang.messages.no_element_selected'));

    /****************  Determining dates and X-scale *************/
    $dates['start'] = post('start_date');
    $dates['start_timestamp'] = strtotime($dates['start']);
    $dates['end'] = post('end_date');
    $dates['end_timestamp'] = strtotime($dates['end']);

    /* Determining the scale */
    $nb_days = ($dates['end_timestamp'] - $dates['start_timestamp'])/60/60/24;
    switch (true) {
      case $nb_days <= 210:
        $date_scale = 60*60*24;
        break;

      case $nb_days <= 701:
        $date_scale = 60*60*24*7;
        break;

      case $nb_days <= 1401:
        $date_scale = 60*60*24*7*2;
        break;

      default:
        $date_scale = 60*60*24*30;
        break;
    }
    $timeDots = range($dates['start_timestamp'], $dates['end_timestamp'], $date_scale);

    // Rounding basis date to match one of the points on x-scale
    $dates['basis'] = strtotime(post('basis_date'));
    $dates['basis'] = date('Y-m-d', $dates['start_timestamp']+$date_scale*round(($dates['basis']-$dates['start_timestamp'])/$date_scale));
    $dates['basis_timestamp'] = strtotime($dates['basis']);

    // Getting base currency for foreign exchange
    $graph_forex_currency = post('display_forex_in');

    /****************  Get the data *************/
    // Get selected portfolios
    $portfolios = PortfolioModel::whereIn('id', post('portfolio'))->with(['heldAssets' =>
      function ($query) use ($dates) {
        $query->where('date_from', '<=', $dates['end'])->where('date_to', '>=', $dates['start']);
      }]
    )->get();

    // Get all assets held (directly or not)
    $allAssets = [];
    $portfolios->each(function ($portfolio, $key = '') use (&$allAssets) {
      $allAssets = array_merge($allAssets, explode('#', $portfolio->heldAssets->implode('id', '#')));
    });
    $allAssets = array_merge($allAssets, is_array(post('asset'))?post('asset'):[]);
    $assets = AssetModel::whereIn('id', $allAssets)->with(['value' => function ($query) { $query->orderBy('date'); }])->get();
    unset($allAssets);

    // Assets to display
    $assetsToDisplay = array_merge(is_array(post('asset'))?post('asset'):[], is_array(post('portfolio'))?post('portfolio'):[]);

    /****************  Transform data to match target format + use basis values *************/
    $seriesData = [];
    $assets->each (function ($asset, $key = '') use ($timeDots, &$seriesData, $dates, $graph_forex_currency) {
      $a_ID = $asset->id;
      if ($asset->id == $graph_forex_currency)
        return;

      $seriesData[$a_ID] = [];
      $seriesData[$a_ID]['label'] = $asset->title;


      foreach ($timeDots as $day_time) {
        $value = $asset->value->where('date', date('Y-m-d', $day_time))->last();
        if (is_null($value))
          $value = $asset->value->filter(function ($assetValue) use ($day_time) {
            return (strtotime($assetValue->date) <= $day_time);
          })->last();

        if (is_null($value))
          throw new ApplicationException (sprintf(trans('piratmac.smmm::lang.messages.no_value_found_for_on'), $asset->title, date('Y-m-d', $day_time)));

        $seriesData[$a_ID]['value'][$day_time] = $value->value;
      }
      $seriesData[$a_ID]['basis_value'] = $seriesData[$a_ID]['value'][$dates['basis_timestamp']];
      foreach ($timeDots as $day_time)
        $seriesData[$a_ID]['value_basis'][$day_time] = $seriesData[$a_ID]['value'][$day_time] / $seriesData[$a_ID]['basis_value'];
    });

    $portfolios->each (function ($portfolio, $key = '') use (&$timeDots, $dates, &$seriesData, $assets) {
      $p_ID = $portfolio->id;
      $p_base_currency = $portfolio->base_currency->id;
      $seriesData[$p_ID] = [];
      $seriesData[$p_ID]['label'] = $portfolio->description;
      $seriesData[$p_ID]['value'] = [];
      $seriesData[$p_ID]['value_basis'] = [];

      foreach ($timeDots as $day_time) {
        $seriesData[$p_ID]['value'][$day_time] = 0;

        $heldAssets = $portfolio->heldAssets->filter(function ($heldAsset) use ($day_time) {
          return (strtotime($heldAsset->pivot->date_from) <= $day_time && strtotime($heldAsset->pivot->date_to) > $day_time);
        });
        $heldAssets->each ( function ($heldAsset) use ($day_time, &$seriesData, $p_ID, $p_base_currency, $assets) {
          $hA_ID = $heldAsset->id;
          if ($hA_ID == $p_base_currency)
            $seriesData[$p_ID]['value'][$day_time] += $heldAsset->pivot->asset_count;
          else {
            $seriesData[$hA_ID]['held'][$day_time] = true;
            if ($heldAsset->baseCurrency->id == $p_base_currency)
              $seriesData[$p_ID]['value'][$day_time] += $heldAsset->pivot->asset_count * $seriesData[$hA_ID]['value'][$day_time];
            else {
              $hA_base_currency_id = $heldAsset->baseCurrency->id;
              $hA_base_currency = $assets->filter(
                function($asset) use ($hA_base_currency_id) {
                  return $asset->id == $hA_base_currency_id;
                }
              )->first();

              if ($hA_base_currency->baseCurrency->id == $p_base_currency) {
                $seriesData[$p_ID]['value'][$day_time] +=
                  $heldAsset->pivot->asset_count
                  * $seriesData[$hA_ID]['value'][$day_time]
                  * $seriesData[$hA_base_currency_id]['value'][$day_time];
              }
            }
          }
        });
      }
      $seriesData[$p_ID]['basis_value'] = $seriesData[$p_ID]['value'][$dates['basis_timestamp']];
      foreach ($timeDots as $day_time)
        $seriesData[$p_ID]['value_basis'][$day_time] = round($seriesData[$p_ID]['value'][$day_time] / $seriesData[$p_ID]['basis_value'], 3);
    });


    /****************  Prepare rendering *************/

    $timeDots_rendering = [];
    $timeDots_rendering['labels'] = '"'.implode(array_map(function ($dot_dates) { return date('d-m-Y', $dot_dates); }, $timeDots), '", "').'"';
    $timeDots_rendering['datasets'] = [];
    foreach ($seriesData as $assetID => $assetValue) {
      if (!in_array($assetID, $assetsToDisplay))
        continue;
      $timeDots_rendering['datasets'][$assetID]['label'] = $seriesData[$assetID]['label'];
      $timeDots_rendering['datasets'][$assetID]['data'] = implode(', ', array_map(function ($value) { return sprintf("%+.3f", $value); }, $seriesData[$assetID]['value_basis']));
      $timeDots_rendering['datasets'][$assetID]['data_label'] = [];
      foreach ($seriesData[$assetID]['value_basis'] as $day_time => $value_basis) {
        $timeDots_rendering['datasets'][$assetID]['data_label'][] = '"'.sprintf("%.2f", $seriesData[$assetID]['value'][$day_time]).' ('.sprintf("%+.1f %%", ($value_basis-1)*100).')"';
      }
      $timeDots_rendering['datasets'][$assetID]['data_label'] = implode(', ', $timeDots_rendering['datasets'][$assetID]['data_label']);
    }
    $this->timeDots_rendering = $timeDots_rendering;
  }

}