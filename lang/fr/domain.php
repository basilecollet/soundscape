<?php

declare(strict_types=1);

return [
    'project' => [
        'cannot_publish_invalid_status' => 'Impossible de publier le projet avec le statut ":status". Seuls les projets en brouillon peuvent être publiés.',
        'cannot_publish_missing_description' => 'Impossible de publier le projet sans description.',
        'cannot_be_archived' => 'Impossible d\'archiver le projet avec le statut ":status". Seuls les projets publiés peuvent être archivés.',
        'already_draft' => 'Le projet est déjà en brouillon.',
        'duplicate_slug' => 'Un projet avec le slug ":slug" existe déjà.',
        'not_found' => 'Le projet avec le slug ":slug" n\'a pas été trouvé.',

        'title' => [
            'empty' => 'Le titre du projet ne peut pas être vide.',
            'too_long' => 'Le titre du projet ne peut pas dépasser :max caractères (reçu :length).',
        ],

        'description' => [
            'empty' => 'La description ne peut pas être vide.',
        ],

        'short_description' => [
            'empty' => 'La description courte ne peut pas être vide.',
            'too_long' => 'La description courte ne peut pas dépasser :max caractères (reçu :length).',
        ],

        'slug' => [
            'invalid' => 'Format de slug invalide : ":slug". Le slug doit contenir uniquement des lettres minuscules, chiffres et tirets.',
        ],

        'client_name' => [
            'empty' => 'Le nom du client ne peut pas être vide.',
            'too_long' => 'Le nom du client ne peut pas dépasser :max caractères (reçu :length).',
            'invalid' => 'Échec du traitement du nom du client : ":value".',
        ],

        'date' => [
            'invalid' => 'Format de date invalide : ":date". Une date valide est attendue.',
        ],

        'bandcamp_player' => [
            'not_iframe' => 'Le lecteur Bandcamp doit être une balise iframe.',
            'not_bandcamp' => 'Le lecteur Bandcamp doit contenir le domaine bandcamp.com.',
            'too_long' => 'Le code du lecteur Bandcamp ne peut pas dépasser :max caractères (reçu :length).',
        ],

        'image' => [
            'url_empty' => 'L\'URL de l\'image :type ne peut pas être vide.',
            'url_invalid' => 'Format d\'URL invalide : :url.',
        ],
    ],
];
