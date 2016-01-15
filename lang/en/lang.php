<?php

return [
  'plugin' => [
    'name'           => 'Show me my money',
    'description'    => 'A plugin to manage portfolios',
  ],
  'components' => [
    'portfolios_name'           => 'Portfolios list',
    'portfolios_description'    => 'Displays a list of all portfolios',
    'portfolio_name'           => 'Portfolio details',
    'portfolio_description'    => 'Details about a portfolio',
    'assets_name'           => 'Assets list',
    'assets_description'    => 'Displays a list of all assets',
    'asset_name'           => 'Asset details',
    'asset_description'    => 'Details about a asset',
  ],
  'settings' => [
    'portfolio_page'           => 'Portfolio details',
    'portfolio_description'    => 'Name of the page to view details on a portfolio',
    'portfoliolist_page'           => 'List of portfolios',
    'portfoliolist_description'    => 'Name of the page to list portfolios',
    'asset_page'           => 'Asset details',
    'asset_description'    => 'Name of the page to view details on a asset',
    'assetlist_page'           => 'List of assets',
    'assetlist_description'    => 'Name of the page to list assets',

    'action'                => 'Action',
    'action_description'    => 'Action performed, such as Create, Update, View',


    'portfolio_id'                => 'Portfolio ID',
    'portfolio_id_description'    => 'Portfolio\'s unique identifier',
    'asset_id'                => 'Asset ID',
    'asset_id_description'    => 'Asset\'s unique identifier',

  ],
  'properties' => [
    'description'              => 'Description',
    'opened_on'                => 'Opened on',
    'closed_on'                => 'Closed on',



  ],
  'messages' => [
    'error_no_id'              => 'There is no portfolio here.',
    'fatal_error'              => 'Fatal error. Please try again',
    'error_wrong_user'         => 'Wrong user. Try again.',
    'asset_in_use'             => 'This asset is used in a portfolio or movement. It can\'t be deleted.',

    'success_modification'    => 'Modification successful',
    'success_creation'        => 'Creation successful',
    'success_deletion'        => 'Deletion successful',

    'confirm_deletion'        => 'This will be deleted. Please confirm.',
  ],
  'labels' => [
    'asset'       => 'Asset',
    'title'       => 'Title',
    'code'        => 'Code',
    'type'        => 'Type',
    'source'      => 'Source',

    'portfolio'      => 'Portfolio',
    'description'    => 'Description',
    'opened_on'      => 'Opened on',
    'closed_on'      => 'Closed on',
    'number'      => 'Number',
    'broker'      => 'Broker',

    'date'        => 'Date',
    'asset_count' => 'Asset count',
    'unit_value'  => 'Unit value / Amount',
    'fee'         => 'Fee',

    'manage'      => 'Manage',
    'save'        => 'Save',
    'cancel'      => 'Cancel',
    'delete'      => 'Delete',


  ],
  'dropdowns' => [
    'asset' => [
      'type' => [
        'stock' => 'Stock',
        'bond' => 'Bond',
        'cash' => 'Cash',
        'mixed' => 'Mixed',
      ],
      'source' => [
        'yahoo' => 'Yahoo! Finance',
        'bourso' => 'Boursorama',
      ],
    ],
    'movement' => [
      'type' => [
        'cash_entry' => 'Cash deposit',
        'asset_buy' => 'Asset buy / subscription',
        'asset_sell' => 'Asset sell',
        'fee' => 'Fee',
        'cash_exit' => 'Cash withdrawal',
      ],
    ],
  ],

  // Custom error messages
  'custom' => [
    'asset_id' => [
      'required_if' => 'For this type of operation, please choose an asset',
    ],
  ],

];


$OLD = [
  'settings' => [
    'portfolioview_page'           => 'View a portfolio',
    'portfolioview_description'    => 'Name of the portfolio page to view details on a portfolio',
    'portfoliomanage_page'           => 'Manage a portfolio',
    'portfoliomanage_description'    => 'Name of the portfolio page to manage portfolios',
    'portfoliocreate_page'           => 'Create a portfolio',
    'portfoliocreate_description'    => 'Name of the portfolio page to create portfolios',

    'display_mode'               => 'Display mode',
    'display_mode_description'   => 'Choose whether the page will allow modification or not',
    'display_mode_view'             => 'Read-only',
    'display_mode_manage'           => 'Modification',
    'display_mode_create'           => 'Creation',
  ],

];