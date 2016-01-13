<?php

return [
  'plugin' => [
    'name'           => 'Montre mon argent',
    'description'    => 'Gestionnaire de portefeuilles d\'actions',
  ],
  'components' => [
    'portfolios_name'           => 'Liste des portefeuilles',
    'portfolios_description'    => 'Affiche la liste des portefeuilles',
    'portfolio_name'           => 'Gérer un portefeuille',
    'portfolio_description'    => 'Modifie un portefeuille',
    'stocks_name'           => 'Liste des actions',
    'stocks_description'    => 'Affiche la liste des actions',
    'stock_name'           => 'Détail d\'une action',
    'stock_description'    => 'Modifie une action',
  ],
  'settings' => [
    'portfolio_page'           => 'Détails du portefeuille',
    'portfolio_description'    => 'Nom de la page de détails d\'un portefeuille',
    'portfoliolist_page'           => 'Liste des portefeuilles',
    'portfoliolist_description'    => 'Page affichant la liste des portefeuilles',
    'stock_page'           => 'Détails de l\'action',
    'stock_description'    => 'Nom de la page de détails d\'une action',
    'stocklist_page'           => 'Liste des actions',
    'stocklist_description'    => 'Page affichant la liste des actions',

    'action'                => 'Action',
    'action_description'    => 'Action réalisée, par exemple voir, créer, modifier',


    'portfolio_id'                   => 'ID du portefeuille',
    'portfolio_id_description'       => 'Identifiant unique du portefeuille',
    'stock_id'                   => 'ID de l\'action',
    'stock_id_description'       => 'Identifiant unique de l\'action',

  ],
  'properties' => [
    'description'              => 'Description',
    'opened_on'                => 'Ouvert le',
    'closed_on'                => 'Clos le',



  ],
  'messages' => [
    'error_no_id'              => 'Il n\'y a pas de portefeuille ici.',
    'fatal_error'              => 'Erreur fatale. Veuillez réessayer.',
    'error_wrong_user'         => 'Ce portefeuille n\'est pas le votre.',
    'stock_in_use'             => 'Cette action est utilisée dans un portefeuille ou un mouvement. Vous ne pouvez la supprimer.',

    'success_modification'    => 'Modification réussie',
    'success_creation'        => 'Création réussie',
    'success_deletion'        => 'Suppression réussie',

  ],
  'labels' => [
    'confirm_deletion'              => 'Confirmer la suppression.',
    'title'       => 'Titre',
    'code'        => 'Code',
    'type'        => 'Type',
    'source'      => 'Source',
    'manage'      => 'Gérer',


  ],
  'dropdowns' => [
    'stock' => [
      'type' => [
        'stock' => 'Action',
        'bond' => 'Obligation',
        'cash' => 'Espèces',
        'mixed' => 'Mixte',
      ],
      'source' => [
        'yahoo' => 'Yahoo! Finance',
        'bourso' => 'Boursorama',
      ],
    ],
    'movement' => [
      'type' => [
        'cash_entry' => 'Dépôt',
        'stock_buy' => 'Achat',
        'stock_sell' => 'Vente',
        'fee' => 'Frais',
        'cash_exit' => 'Retrait',
      ],
    ],
  ],
];


$OLD = [
  'settings' => [
    'portfolioview_page'           => 'Page d\'affichage',
    'portfolioview_description'    => 'Nom de la page pour afficher un portefeuille',
    'portfoliomanage_page'           => 'Page de gestion',
    'portfoliomanage_description'    => 'Nom de la page pour modifier un portefeuille',
    'portfoliocreate_page'           => 'Page de création',
    'portfoliocreate_description'    => 'Nom de la page pour créer un portefeuille',

    'display_mode'               => 'Mode d\'affichage',
    'display_mode_description'   => 'Choisissez si la page permet la modification ou non',
    'display_mode_view'             => 'Lecture seule',
    'display_mode_manage'           => 'Modification',
    'display_mode_create'           => 'Création',
  ],

];