<?php namespace Piratmac\Smmm\Controllers;

use Backend\Classes\Controller;

/**
 * Asset Back-end Controller
 */
class Asset extends Controller
{
  public $implement = [
    'Backend.Behaviors.FormController',
    'Backend.Behaviors.ListController',
  ];

  public $formConfig = 'config_form.yaml';
  public $listConfig = 'config_list.yaml';
}