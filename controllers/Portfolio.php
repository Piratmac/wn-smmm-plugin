<?php namespace Piratmac\Smmm\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Portfolio Back-end Controller
 */
class Portfolio extends Controller
{
  public $implement = [
    'Backend.Behaviors.FormController',
  ];

  public $formConfig = 'config_form.yaml';
}