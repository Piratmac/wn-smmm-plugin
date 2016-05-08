<?php

return [

  // Custom error messages
  'custom' => [
    'asset_id' => [
      'required_if' => 'For this type of operation, please choose an asset',
      'not_in' => 'Please don\'t use Cash as an asset (it\'s calculated for you.)',
    ],
  ],
];