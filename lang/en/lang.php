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
  'messages' => [
    'error_no_id'              => 'There is no portfolio here.',
    'fatal_error'              => 'Fatal error. Please try again',
    'error_wrong_user'         => 'Wrong user. Try again.',
    'asset_in_use'             => 'This asset is used in a portfolio or movement. It can\'t be deleted.',

    'success_modification'    => 'Modification successful',
    'success_creation'        => 'Creation successful',
    'success_deletion'        => 'Deletion successful',

    'confirm_deletion'        => 'This will be deleted. Please confirm.',

    'old_assets_hidden'       => 'Old assets have been hidden',
    'old_assets_displayed'    => 'Old assets are now displayed',

    'closed_portfolios_hidden'       => 'Closed portfolios have been hidden',
    'closed_portfolios_displayed'    => 'Closed portfolios are now displayed',

    'used_and_not_synced'    => 'Warning: This asset is not synced, but belongs to a portfolio',

    'negative_asset_count'    => 'You don\'t have that many assets to sell.',
    'negative_cash_balance'    => 'You are in the red.',
    'movement_outside_portfolio_dates'    => 'The movement\'s date doesn\'t match with the portfolio\'s ones.',
  ],
  'labels' => [
    'available_assets' => 'Available assets',
    'display_old_assets'         => 'Display old assets',
    'hide_old_assets'            => 'Hide old assets',

    'asset'       => 'Asset',
    'title'       => 'Title',
    'code'        => 'Code',
    'type'        => 'Type',
    'source'      => 'Source',
    'synced'      => 'Sync values',

    'value_history'    => 'Value history',
    'value'            => 'Value',



    'portfolios' => 'Portfolios',
    'display_closed_portfolios'         => 'Display closed portfolios',
    'hide_closed_portfolios'            => 'Hide closed portfolios',

    'portfolio'        => 'Portfolio',
    'description'      => 'Description',
    'opened_on'        => 'Opened on',
    'closed_on'        => 'Closed on',
    'number'           => 'Number',
    'broker'           => 'Broker',
    'contents'         => 'Contenu',
    'average_buy_price'       => 'Average buy price',
    'total_buy_price'         => 'Total cost',
    'stocks'           => 'Stocks',
    'bonds'            => 'Bonds',
    'mixed'            => 'Mixed',
    'cash'             => 'Cash',
    'total'            => 'Total',
    'arbitration'      => 'Arbitration',


    'movements'   => 'Movements',
    'date'        => 'Date',
    'asset_count' => 'Asset count',
    'unit_value'  => 'Unit value / Amount',
    'fee'         => 'Fee',

    'details'     => 'Details',
    'manage'      => 'Manage',
    'save'        => 'Save',
    'cancel'      => 'Cancel',
    'delete'      => 'Delete',
    'new'         => 'New',


    'yes'         => 'Yes',
    'no'          => 'No',


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

];