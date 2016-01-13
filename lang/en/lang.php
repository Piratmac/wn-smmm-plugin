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
    'stocks_name'           => 'Stocks list',
    'stocks_description'    => 'Displays a list of all stocks',
    'stock_name'           => 'Stock details',
    'stock_description'    => 'Details about a stock',
  ],
  'settings' => [
    'portfolio_page'           => 'Portfolio details',
    'portfolio_description'    => 'Name of the page to view details on a portfolio',
    'portfoliolist_page'           => 'List of portfolios',
    'portfoliolist_description'    => 'Name of the page to list portfolios',
    'stock_page'           => 'Stock details',
    'stock_description'    => 'Name of the page to view details on a stock',
    'stocklist_page'           => 'List of stocks',
    'stocklist_description'    => 'Name of the page to list stocks',

    'action'                => 'Action',
    'action_description'    => 'Action performed, such as Create, Manage, View',


    'portfolio_id'                => 'Portfolio ID',
    'portfolio_id_description'    => 'Portfolio\'s unique identifier',
    'stock_id'                => 'Stock ID',
    'stock_id_description'    => 'Stock\'s unique identifier',

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
    'stock_in_use'             => 'This stock is used in a portfolio or movement. It can\'t be deleted.',

    'success_modification'    => 'Modification successful',
    'success_creation'        => 'Creation successful',
    'success_deletion'        => 'Deletion successful',

  ],
  'labels' => [
    'confirm_deletion'              => 'Confirm deletion.',
    'title'       => 'Title',
    'code'        => 'Code',
    'type'        => 'Type',
    'source'      => 'Source',
    'manage'      => 'Manage',


  ],
  'dropdowns' => [
    'stock' => [
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
        'stock_buy' => 'Stock buy / subscription',
        'stock_sell' => 'Stock sell',
        'fee' => 'Fee',
        'cash_exit' => 'Cash withdrawal',
      ],
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