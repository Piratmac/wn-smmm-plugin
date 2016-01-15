<?php namespace Piratmac\Smmm\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Piratmac\Smmm\Models\Portfolio;
use Piratmac\Smmm\Models\PortfolioMovement as MovementModel;
use Piratmac\Smmm\Models\Asset;

/**
 * Portfolio Movements Back-end Controller
 */
class PortfolioMovement extends Controller
{
  public $implement = [
    'Backend.Behaviors.FormController',
  ];

  public $formConfig = 'config_form.yaml';
}