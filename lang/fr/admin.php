<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Interface Translations
    |--------------------------------------------------------------------------
    |
    | Traductions pour toute l'interface d'administration
    |
    */

    'navigation' => [
        'dashboard' => 'Tableau de bord',
        'projects' => 'Projets',
        'content' => 'Contenu',
        'settings' => 'Paramètres',
        'section_settings' => 'Visibilité des sections',
        'profile' => 'Profil',
        'messages' => 'Messages',
    ],

    'dashboard' => [
        'title' => 'Tableau de bord',
        'welcome' => 'Bienvenue',
        'overview' => 'Vue d\'ensemble',

        'stats' => [
            'total_projects' => 'Projets totaux',
            'published_projects' => 'Projets publiés',
            'draft_projects' => 'Projets en brouillon',
            'total_content' => 'Contenus totaux',
            'last_update' => 'Dernière mise à jour',
            'never' => 'Jamais',
        ],

        'recent_messages' => [
            'title' => 'Messages récents',
            'empty' => 'Aucun message pour le moment',
            'view_all' => 'Voir tous les messages',
            'unread' => 'Non lu',
            'read' => 'Lu',
        ],

        'quick_actions' => [
            'title' => 'Actions rapides',
            'create_project' => 'Créer un projet',
            'manage_content' => 'Gérer le contenu',
            'view_messages' => 'Voir les messages',
        ],
    ],

    'projects' => [
        'title' => 'Projets',
        'list' => 'Liste des projets',
        'create' => 'Créer un projet',
        'edit' => 'Modifier le projet',
        'delete' => 'Supprimer le projet',
        'view' => 'Voir le projet',

        'empty_state' => [
            'title' => 'Aucun projet',
            'description' => 'Commencez par créer votre premier projet',
            'create_first' => 'Créer votre premier projet',
        ],

        'count' => ':count projet|:count projets',

        'created_successfully' => 'Projet créé avec succès.',
        'updated_successfully' => 'Projet mis à jour avec succès.',
        'deleted_successfully' => 'Projet supprimé avec succès.',
        'published_successfully' => 'Projet publié avec succès.',
        'archived_successfully' => 'Projet archivé avec succès.',
        'drafted_successfully' => 'Projet remis en brouillon avec succès.',

        'actions' => [
            'publish' => 'Publier',
            'archive' => 'Archiver',
            'draft' => 'Remettre en brouillon',
            'edit' => 'Modifier',
            'delete' => 'Supprimer',
            'view_public' => 'Voir sur le site',
        ],

        'form' => [
            'title' => 'Créer un nouveau projet',
            'edit_title' => 'Modifier le projet',
            'description' => 'Remplissez les informations du projet',

            'section' => [
                'basic' => 'Informations de base',
                'details' => 'Détails',
                'media' => 'Médias',
                'audio' => 'Audio',
            ],

            'title' => [
                'label' => 'Titre du projet',
                'placeholder' => 'Ex: Album pour Artiste X',
                'help' => 'Le titre principal du projet',
            ],

            'slug' => [
                'label' => 'Slug',
                'help' => 'URL du projet (généré automatiquement)',
                'copy' => 'Copier le lien',
                'copied' => 'Lien copié !',
            ],

            'status' => [
                'label' => 'Statut',
                'current' => 'Statut actuel',
            ],

            'description' => [
                'label' => 'Description',
                'placeholder' => 'Description détaillée du projet (Markdown supporté)',
                'help' => 'Description complète du projet. Vous pouvez utiliser Markdown.',
            ],

            'short_description' => [
                'label' => 'Description courte',
                'placeholder' => 'Résumé court du projet',
                'help' => 'Résumé affiché dans la liste des projets (max 500 caractères)',
            ],

            'client_name' => [
                'label' => 'Nom du client',
                'placeholder' => 'Ex: Artiste X',
                'help' => 'Nom de l\'artiste ou du client',
            ],

            'project_date' => [
                'label' => 'Date du projet',
                'placeholder' => 'YYYY-MM-DD',
                'help' => 'Date de réalisation ou de sortie',
            ],

            'bandcamp_player' => [
                'label' => 'Lecteur Bandcamp',
                'placeholder' => 'Collez ici le code embed du lecteur Bandcamp',
                'help' => 'Code iframe du lecteur Bandcamp (optionnel)',
            ],

            'featured_image' => [
                'label' => 'Image principale',
                'help' => 'Image de couverture du projet (min 800x600px)',
                'upload' => 'Télécharger une image',
                'change' => 'Changer l\'image',
                'remove' => 'Retirer l\'image',
                'uploaded_successfully' => 'Image principale téléchargée avec succès.',
                'deleted_successfully' => 'Image principale supprimée avec succès.',
                'uploading' => 'Téléchargement en cours...',
            ],

            'gallery_images' => [
                'label' => 'Galerie d\'images',
                'help' => 'Images additionnelles du projet (max 10 images, min 800x600px chacune)',
                'upload' => 'Ajouter des images',
                'uploaded_successfully' => 'Images ajoutées à la galerie avec succès.',
                'deleted_successfully' => 'Image retirée de la galerie avec succès.',
                'uploading' => 'Téléchargement en cours...',
                'empty' => 'Aucune image dans la galerie',
                'count' => ':count image|:count images',
            ],
        ],

        'errors' => [
            'not_found' => 'Projet introuvable.',
            'cannot_delete' => 'Impossible de supprimer le projet.',
            'cannot_publish' => 'Impossible de publier le projet.',
            'cannot_archive' => 'Impossible d\'archiver le projet.',
            'invalid_status' => 'Statut invalide.',
            'file_too_large' => 'Le fichier est trop volumineux.',
            'file_not_found' => 'Fichier introuvable.',
            'invalid_format' => 'Format de fichier invalide.',
            'upload_failed' => 'Le téléchargement a échoué.',
            'delete_failed' => 'La suppression a échoué.',
        ],

        'confirm' => [
            'delete' => 'Êtes-vous sûr de vouloir supprimer ce projet ?',
            'delete_message' => 'Cette action est irréversible. Le projet et tous ses médias seront supprimés définitivement.',
            'publish' => 'Voulez-vous publier ce projet ?',
            'publish_message' => 'Le projet sera visible sur le site public.',
            'archive' => 'Voulez-vous archiver ce projet ?',
            'archive_message' => 'Le projet ne sera plus visible sur le site public.',
        ],
    ],

    'content' => [
        'title' => 'Gestion du contenu',
        'list' => 'Liste du contenu',
        'create' => 'Créer un contenu',
        'edit' => 'Modifier le contenu',
        'delete' => 'Supprimer le contenu',

        'created_successfully' => 'Contenu créé avec succès.',
        'updated_successfully' => 'Contenu mis à jour avec succès.',
        'deleted_successfully' => 'Contenu supprimé avec succès.',

        'empty_state' => [
            'title' => 'Aucun contenu',
            'description' => 'Aucun contenu trouvé pour les filtres sélectionnés',
        ],

        'missing_keys' => [
            'title' => 'Clés manquantes',
            'description' => 'Ces clés n\'ont pas encore de contenu',
            'create' => 'Créer le contenu',
        ],

        'filter' => [
            'all_pages' => 'Toutes les pages',
            'search_placeholder' => 'Rechercher par clé, titre ou contenu...',
        ],

        'form' => [
            'title' => 'Modifier le contenu',
            'create_title' => 'Créer un nouveau contenu',

            'page' => [
                'label' => 'Page',
                'help' => 'Page à laquelle appartient ce contenu',
            ],

            'key' => [
                'label' => 'Clé',
                'placeholder' => 'Ex: hero.title',
                'help' => 'Identifiant unique du contenu (utilisé dans le code)',
            ],

            'title' => [
                'label' => 'Titre',
                'placeholder' => 'Titre du contenu',
                'help' => 'Titre descriptif (optionnel)',
            ],

            'content' => [
                'label' => 'Contenu',
                'placeholder' => 'Contenu du texte (Markdown supporté)',
                'help' => 'Le contenu qui sera affiché sur le site',
            ],
        ],

        'table' => [
            'page' => 'Page',
            'key' => 'Clé',
            'title' => 'Titre',
            'content' => 'Contenu',
            'status' => 'Statut',
            'actions' => 'Actions',
        ],

        'errors' => [
            'not_found' => 'Contenu introuvable.',
            'duplicate_key' => 'Cette clé existe déjà pour cette page.',
            'invalid_key' => 'Clé invalide pour cette page.',
        ],
    ],

    'settings' => [
        'title' => 'Paramètres',
        'section_visibility' => 'Visibilité des sections',

        'sections' => [
            'title' => 'Sections des pages',
            'description' => 'Gérez la visibilité des sections sur les pages publiques',

            'home' => [
                'title' => 'Page d\'accueil',
                'features' => [
                    'title' => 'Section Fonctionnalités',
                    'description' => 'Afficher la section des fonctionnalités',
                ],
                'cta' => [
                    'title' => 'Section Appel à l\'action',
                    'description' => 'Afficher la section d\'appel à l\'action',
                ],
            ],

            'about' => [
                'title' => 'Page À propos',
                'experience' => [
                    'title' => 'Section Expérience',
                    'description' => 'Afficher la section expérience',
                ],
                'services' => [
                    'title' => 'Section Services',
                    'description' => 'Afficher la section services',
                ],
                'philosophy' => [
                    'title' => 'Section Philosophie',
                    'description' => 'Afficher la section philosophie',
                ],
            ],
        ],

        'updated_successfully' => 'Paramètres mis à jour avec succès.',
        'saved_successfully' => 'Paramètres enregistrés avec succès.',
    ],
];
