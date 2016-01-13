<?php namespace Piratmac\Smmm\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Stock Back-end Controller
 */
class Stock extends Controller
{
  public $implement = [
    'Backend.Behaviors.FormController',
  ];

  public $formConfig = 'config_form.yaml';
}