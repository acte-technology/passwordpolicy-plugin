<?php return [
    'plugin' => [
        'name' => 'Règles de mot de passes',
        'description' => 'Configurer les règles pour les utilisateurs et les administrateurs.'
    ],
    'settings' => [
      'activate_backend_policy' => 'Activer les règles pour les administrateurs',
      'activate_user_policy' => 'Activer les règles pour les utilisateurs (RainLab.User)',
      'description' => 'Selectionner le nombre minimum pour chaque options:',
      'length' => 'Longueur',
      'lower_case' => 'Minuscules',
      'upper_case' => 'Capitales',
      'special_character' => 'Charactères spéciaux',
      'numbers' => 'Chiffres',
      'pwned' => 'Pwned vérification',
      'pwned_comment' => 'Vérification du mot de passe sha1 dans haveibeenpwned.com',
      
      'tabs' => [
        'backend' => "Administrateurs",
        'user' => "Utilisateurs",
      ]
    ],
];
