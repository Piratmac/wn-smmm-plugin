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
    'assets_name'           => 'Liste des actions',
    'assets_description'    => 'Affiche la liste des actions',
    'asset_name'           => 'Détail d\'une action',
    'asset_description'    => 'Modifie une action',
    'graphs_name'           => 'Graphiques',
    'graphs_description'    => 'Graphiques sur les portefeuilles',
  ],
  'settings' => [
    'portfolio_page'           => 'Détails du portefeuille',
    'portfolio_description'    => 'Nom de la page de détails d\'un portefeuille',
    'portfoliolist_page'           => 'Liste des portefeuilles',
    'portfoliolist_description'    => 'Page affichant la liste des portefeuilles',
    'asset_page'           => 'Détails de l\'action',
    'asset_description'    => 'Nom de la page de détails d\'une action',
    'assetlist_page'           => 'Liste des actions',
    'assetlist_description'    => 'Page affichant la liste des actions',

    'action'                => 'Action',
    'action_description'    => 'Action réalisée, par exemple voir, créer, modifier',


    'portfolio_id'                   => 'ID du portefeuille',
    'portfolio_id_description'       => 'Identifiant unique du portefeuille',
    'asset_id'                   => 'ID de l\'action',
    'asset_id_description'       => 'Identifiant unique de l\'action',

  ],
  'messages' => [
    'error_no_id'              => 'Il n\'y a pas de portefeuille ici.',
    'fatal_error'              => 'Erreur fatale. Veuillez réessayer.',
    'asset_in_use'             => 'Cette action est utilisée dans un portefeuille ou un mouvement. Vous ne pouvez la supprimer.',

    'success_modification'    => 'Modification réussie',
    'success_creation'        => 'Création réussie',
    'success_deletion'        => 'Suppression réussie',

    'confirm_deletion'        => 'Êtes-vous sûr de vouloir supprimer?',

    'old_assets_hidden'       => 'Les anciennes valeurs sont masquées',
    'old_assets_displayed'    => 'Les anciennes valeurs sont affichées',

    'closed_portfolios_hidden'       => 'Les portefeuilles clos sont masqués',
    'closed_portfolios_displayed'    => 'Les portefeuilles clos sont affichés',

    'used_and_not_synced'    => 'Attention: Cette valeur n\{est pas synchronisé et apparait dans un portefeuille.',

    'existing_value_replaced'   => 'La valeur existante à cette date a été remplacée.',

    'negative_asset_count'    => 'Vous n\'avez pas autant d\'actifs à vendre.',
    'negative_cash_balance'    => 'Le solde de votre compte est négatif.',
    'movement_outside_portfolio_dates'    => 'La date du mouvement n\'est pas cohérente avec celles du portefeuille.',

    'no_movement'         => 'Aucun mouvement trouvé.',
    'no_value_history'    => 'Aucun historique de valeur trouvé.',
    'no_asset'            => 'Aucun actif trouvé.',
    'no_portfolio'        => 'Aucun portefeuille trouvé.',



    'basis_date_outside_scope'        => 'La date de base doit être entre la date de début et celle de fin.',
    'no_element_selected'             => 'Aucun élément choisi.',
    'no_value_found_for_on'           => 'Aucune valeur trouvée pour %s le %s.',
  ],
  'labels' => [
    'available_assets' => 'Valeurs disponibles',
    'display_old_assets'         => 'Afficher les anciennes valeurs',
    'hide_old_assets'            => 'Masquer les anciennes valeurs',

    'asset'       => 'Actif',
    'title'       => 'Titre',
    'code'        => 'Code',
    'type'        => 'Type',
    'source'      => 'Source',
    'synced'      => 'Télécharger les valeurs',

    'value_history'    => 'Historique de la valeur',
    'value'            => 'Valeur',



    'portfolios' => 'Portefeuilles',
    'display_closed_portfolios'         => 'Afficher les portefeuilles clos',
    'hide_closed_portfolios'            => 'Masquer les portefeuilles clos',

    'portfolio'        => 'Portefeuille',
    'description'      => 'Description',
    'opened_on'        => 'Ouvert le',
    'closed_on'        => 'Clos le',
    'number'           => 'Numéro',
    'broker'           => 'Banque',
    'contents'         => 'Contenu',
    'average_buy_price'       => 'Prix moyen d\'achat',
    'total_buy_price'         => 'Prix total',



    'assets'           => 'Actifs',
    'stock'            => 'Action',
    'bond'             => 'Obligation',
    'mixed'            => 'Mixte',
    'cash'             => 'Espèces',
    'total'            => 'Total',
    'subtotal'         => 'Sous-total',
    'arbitration'      => 'Arbitrage',
    'cash_balance'     => 'Solde espèces',


    'movements'   => 'Mouvements',
    'date'        => 'Date',
    'asset_count' => 'Nombre d\'actions',
    'unit_value'  => 'Valeur unitaire / Montant',
    'fee'         => 'Frais',

    'details'     => 'Détails',
    'manage'      => 'Gérer',
    'save'        => 'Sauvegarder',
    'cancel'      => 'Annuler',
    'delete'      => 'Supprimer',
    'new'         => 'Nouveau',
    'display'     => 'Afficher',


    'yes'         => 'Oui',
    'no'          => 'Non',


    'basic_information'        => 'Informations',
    'value_evolution'          => 'Evolution',

    'summary'                  => 'Résumé',
    'total_deposits'           => 'Total des versements',
    'total_withdrawals'        => 'Total des retraits',
    'total_fees'               => 'Total des frais',
    'expected_gain'            => 'Plus-value(s) attendue(s)',
    'actual_gain'              => 'Plus-value(s) réalisée(s)',


    'today'          => 'Aujourd\'hui',
    'jan_1st'          => '1er janvier',
    '3_months'         => '3 mois',
    '1_year'           => '1 an',
    '3_years'          => '3 ans',
    '5_years'          => '5 ans',


    'date_from'          => 'De',
    'date_to'            => 'A',
    'value_as_of'        => 'Date',



    'graphs'        => 'Graphiques',
    'elements_to_display'        => 'Valeurs à afficher',
    'parameters'        => 'Paramètres',
    'start_date'        => 'Début',
    'end_date'          => 'Fin',
    'date_basis'        => 'Base',

  ],
  'dropdowns' => [
    'asset' => [
      'type' => [
        'stock' => 'Action',
        'bond'  => 'Obligation',
        'cash'  => 'Espèces',
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
        'asset_buy' => 'Achat',
        'asset_sell' => 'Vente',
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