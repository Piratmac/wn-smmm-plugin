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
    'graphs_name'           => 'Graphs',
    'graphs_description'    => 'Displays various graphs',
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
    'base_currency_must_be_cash'    => 'The base currency must be a currency',

    'existing_value_replaced'   => 'There was an existing value at that date. It has been replaced.',

    'negative_asset_count'    => 'You don\'t have that many assets to sell.',
    'negative_cash_balance'    => 'You are in the red.',
    'movement_outside_portfolio_dates'    => 'The movement\'s date doesn\'t match with the portfolio\'s ones.',

    'no_movement'         => 'No mouvement found.',
    'no_value_history'    => 'No value history found.',
    'no_asset'            => 'No asset found.',
    'no_portfolio'        => 'No portfolio found.',



    'basis_date_outside_scope'        => 'The basis date must be between the start and end dates.',
    'no_element_selected'             => 'No element selected.',
    'no_value_found_for_on'           => 'No value found for %s on %s.',
  ],
  'labels' => [
    'available_assets' => 'Available assets',
    'display_old_assets'         => 'Display old assets',
    'hide_old_assets'            => 'Hide old assets',

    'asset'         => 'Asset',
    'title'         => 'Title',
    'code'          => 'Code',
    'type'          => 'Type',
    'source'        => 'Source',
    'synced'        => 'Sync values',
    'base_currency' => 'Base currency',

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
    'contents'         => 'Contents',
    'average_buy_price'       => 'Average buy price',
    'total_buy_price'         => 'Total cost',


    'assets'           => 'Assets',
    'stock'            => 'Stock',
    'bond'             => 'Bond',
    'mixed'            => 'Mixed',
    'cash'             => 'Cash',
    'total'            => 'Total',
    'subtotal'         => 'Sub-total',
    'arbitration'      => 'Arbitration',
    'cash_balance'     => 'Cash balance',


    'movements'   => 'Movements',
    'date'        => 'Date',
    'asset_count' => 'Asset count / Amount',
    'unit_value'  => 'Unit value',

    'details'     => 'Details',
    'manage'      => 'Manage',
    'save'        => 'Save',
    'cancel'      => 'Cancel',
    'delete'      => 'Delete',
    'new'         => 'New',
    'display'     => 'Display',


    'yes'         => 'Yes',
    'no'          => 'No',
    'none'        => 'None',


    'basic_information'        => 'Basic information',
    'value_evolution'          => 'Evolution',

    'summary'                  => 'Summary',
    'total_deposits'           => 'Total of deposits',
    'total_withdrawals'        => 'Total of withdrawals',
    'expected_gain'            => 'Expected gain',
    'actual_gain'              => 'Actual gain',


    'today'            => 'Today',
    'jan_1st'          => 'January 1st',
    '3_months'         => '3 months',
    '1_year'           => '1 year',
    '3_years'          => '3 years',
    '5_years'          => '5 years',


    'date_from'          => 'From',
    'date_to'            => 'To',
    'value_as_of'        => 'Value as of',



    'graphs'        => 'Graphs',
    'elements_to_display'        => 'Elements to display',
    'parameters'        => 'Parameters',
    'start_date'        => 'Start date',
    'end_date'          => 'End date',
    'date_basis'        => 'Basis',
    'display_forex_in'  => 'Currency for foreign exchange',


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
        'arbitrage_buy' => 'Arbitrage - Buy',
        'arbitrage_sell' => 'Arbitrage - Sell',
        'asset_buy' => 'Asset buy / subscription',
        'asset_refund' => 'Asset refund',
        'asset_sell' => 'Asset sell',
        'cash_entry' => 'Cash deposit',
        'cash_exit' => 'Cash withdrawal',
        'company_funding' => 'Company funding',
        'dividends' => 'Dividends',
        'fee_asset' => 'Management fee - in units',
        'fee_cash' => 'Management fee - in cash',
        'forex' => 'Foreign exchange',
        'interest' => 'Interests',
        'movement_fee' => 'Movement fee',
        'profit_asset' => 'Profit - in units',
        'profit_cash' => 'Profit - in cash',
        'split_source' => 'Split & merge - Source',
        'split_target' => 'Split & merge - Target',
        'taxes_cash' => 'Taxes - in cash',
        'taxes_asset' => 'Taxes - in units',
      ],
    ],
  ],

];