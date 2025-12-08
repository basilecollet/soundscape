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
        'subtitle' => 'Surveillez votre contenu et l\'activité du système',
        'welcome' => 'Bienvenue',
        'overview' => 'Vue d\'ensemble',

        'stats' => [
            'total_projects' => 'Projets totaux',
            'published_projects' => 'Projets publiés',
            'draft_projects' => 'Projets en brouillon',
            'total_content' => 'Contenu total',
            'content_pieces' => 'Éléments de contenu',
            'unread_messages' => 'Messages non lus',
            'last_update' => 'Dernière mise à jour',
            'content_modified' => 'Contenu modifié',
            'never' => 'Jamais',
        ],

        'recent_messages' => [
            'title' => 'Messages récents',
            'empty' => 'Aucun message pour le moment',
            'empty_state' => 'Aucun message récent',
            'empty_description' => 'Les messages de contact apparaîtront ici lorsqu\'ils seront reçus',
            'no_subject' => 'Sans objet',
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
        'management_title' => 'Gestion des projets',
        'subtitle' => 'Gérez les projets de votre portfolio',
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
            'publish' => 'Publier le projet',
            'publishing' => 'Publication en cours...',
            'archive' => 'Archiver le projet',
            'set_to_draft' => 'Remettre en brouillon',
            'edit' => 'Modifier',
            'delete' => 'Supprimer',
            'view_public' => 'Voir sur le site',
        ],

        'form' => [
            'create_title' => 'Créer un nouveau projet',
            'create_subtitle' => 'Créer un nouveau projet pour votre portfolio',
            'edit_title' => 'Modifier le projet',
            'edit_title_with_name' => 'Modifier le projet :name',
            'edit_subtitle' => 'Modifiez les informations du projet',
            'form_description' => 'Remplissez les informations du projet',

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
                'alt' => 'Image principale du projet',
                'upload' => 'Cliquez pour télécharger l\'image principale',
                'upload_button' => 'Télécharger l\'image principale',
                'change' => 'Changer l\'image',
                'remove' => 'Retirer l\'image principale',
                'uploaded_successfully' => 'Image principale téléchargée avec succès.',
                'deleted_successfully' => 'Image principale supprimée avec succès.',
                'uploading' => 'Téléchargement en cours...',
            ],

            'gallery_images' => [
                'label' => 'Galerie d\'images',
                'help' => 'Images additionnelles pour la galerie du projet (max 10 images). Formats acceptés : JPEG, PNG, GIF, WebP. Taille minimale : 800x600px. Taille maximale : 10MB par image.',
                'alt' => 'Image de la galerie',
                'preview_alt' => 'Aperçu de l\'image de galerie',
                'upload' => 'Cliquez pour télécharger des images de galerie (plusieurs autorisées)',
                'upload_button' => 'Télécharger les images de galerie',
                'remove' => 'Retirer l\'image de la galerie',
                'preview_count' => ':count image|:count images',
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

        'modals' => [
            'publish' => [
                'title' => 'Publier ce projet ?',
                'message' => 'Ce projet deviendra visible sur votre portfolio public. Assurez-vous que tout le contenu est finalisé et qu\'une description est fournie.',
                'warning' => 'Attention : Aucune description n\'est actuellement définie. La publication échouera sans description.',
            ],

            'archive' => [
                'title' => 'Archiver ce projet ?',
                'message' => 'Ce projet ne sera plus visible sur votre portfolio public. Vous pourrez le restaurer plus tard en le remettant en brouillon ou en le publiant à nouveau.',
            ],

            'draft' => [
                'title' => 'Remettre en brouillon ?',
                'message' => 'Ce projet sera remis en brouillon et ne sera plus publiquement visible',
                'from_published' => 'sur votre portfolio.',
                'from_archived' => 's\'il était archivé.',
                'can_publish_again' => 'Vous pourrez le publier à nouveau à tout moment.',
            ],
        ],
    ],

    'content' => [
        'title' => 'Gestion du contenu',
        'subtitle' => 'Gérez le contenu et les pages de votre site',
        'list' => 'Liste du contenu',
        'create' => 'Créer un contenu',
        'edit' => 'Modifier le contenu',
        'delete' => 'Supprimer le contenu',
        'editing' => 'Modification',
        'on' => 'sur',
        'page' => 'page',
        'create_new' => 'Créer un nouveau contenu',
        'create_description' => 'Ajouter du nouveau contenu à votre site',

        'created_successfully' => 'Contenu créé avec succès.',
        'updated_successfully' => 'Contenu mis à jour avec succès.',
        'deleted_successfully' => 'Contenu supprimé avec succès.',

        'no_title' => 'Aucun titre défini',
        'empty_content' => 'Contenu vide',

        'empty_state' => [
            'title' => 'Aucun contenu trouvé',
            'description' => 'Essayez d\'ajuster vos filtres ou votre recherche',
        ],

        'missing_keys' => [
            'title' => 'Clés manquantes',
            'description' => 'Ces clés n\'ont pas encore de contenu',
            'create' => 'Créer le contenu',
        ],

        'filter' => [
            'page_label' => 'Filtrer par page',
            'all_pages' => 'Toutes les pages',
            'search_label' => 'Rechercher du contenu',
            'search_placeholder' => 'Rechercher par clé, titre ou contenu...',
        ],

        'results' => [
            'showing' => 'Affichage de',
            'content' => 'contenu',
            'contents' => 'contenus',
            'for' => 'pour',
            'use_ctrlf' => 'Utilisez Ctrl+F pour rechercher dans les résultats',
        ],

        'status' => [
            'has_content' => 'Contenu présent',
            'empty' => 'Vide',
            'has_title' => 'Titre présent',
        ],

        'form' => [
            'title' => 'Modifier le contenu',
            'create_title' => 'Créer un nouveau contenu',

            'page' => [
                'label' => 'Page',
                'placeholder' => 'Sélectionner une page',
                'help' => 'Page à laquelle appartient ce contenu',
            ],

            'key' => [
                'label' => 'Clé',
                'placeholder' => 'Sélectionner une clé',
                'help' => 'Identifiant unique du contenu (utilisé dans le code)',
                'no_keys' => 'Aucune clé disponible pour cette page',
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
                'characters' => 'caractères',
                'long' => 'Contenu long',
                'good_length' => 'Bonne longueur',
                'min_read' => 'min de lecture',
            ],

            'preview' => 'Aperçu',
            'preview_empty' => 'L\'aperçu du contenu apparaîtra ici une fois que vous aurez commencé à écrire',

            'create_button' => 'Créer le contenu',
            'update_button' => 'Mettre à jour',
            'copy_content' => 'Copier le contenu',
            'delete_confirm' => 'Êtes-vous sûr de vouloir supprimer ce contenu ?',

            'press' => 'Appuyez sur',
            'to_save' => 'pour enregistrer',
        ],

        'table' => [
            'page' => 'Page',
            'key' => 'Clé',
            'title' => 'Titre',
            'content' => 'Contenu',
            'status' => 'Statut',
            'actions' => 'Actions',
            'aria_label' => 'Tableau de gestion du contenu',
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
        'section_visibility_description' => 'Gérez les sections visibles sur les pages de votre site. Les sections hero et les sections de la page contact ne peuvent pas être désactivées.',

        'sections' => [
            'title' => 'Sections des pages',
            'description' => 'Gérez la visibilité des sections sur les pages publiques',
            'all_enabled' => 'Toutes les sections de cette page sont toujours activées et ne peuvent pas être désactivées.',

            'home' => [
                'title' => 'Page d\'accueil',
                'features' => [
                    'title' => 'Section Fonctionnalités',
                    'description' => 'Contrôle l\'affichage des fonctionnalités principales sur la page d\'accueil',
                ],
                'cta' => [
                    'title' => 'Section Appel à l\'action',
                    'description' => 'Contrôle l\'affichage de la section d\'appel à l\'action en bas de la page d\'accueil',
                ],
            ],

            'about' => [
                'title' => 'Page À propos',
                'experience' => [
                    'title' => 'Section Expérience',
                    'description' => 'Contrôle l\'affichage de la section statistiques montrant les années d\'expérience, projets et clients',
                ],
                'services' => [
                    'title' => 'Section Services',
                    'description' => 'Contrôle l\'affichage de la liste des services',
                ],
                'philosophy' => [
                    'title' => 'Section Philosophie',
                    'description' => 'Contrôle l\'affichage de la section philosophie avec les valeurs de l\'entreprise',
                ],
            ],
        ],

        'important_notes' => 'Notes importantes',
        'notes' => [
            'hero_always_enabled' => 'Les sections hero sont toujours affichées et ne peuvent pas être désactivées',
            'contact_always_enabled' => 'Toutes les sections de la page contact sont toujours activées',
            'immediate_effect' => 'Les modifications prennent effet immédiatement sur le site',
            'disabled_not_shown' => 'Les sections désactivées n\'apparaîtront pas dans la navigation ou le contenu du site',
        ],

        'updated_successfully' => 'Paramètres mis à jour avec succès.',
        'saved_successfully' => 'Paramètres enregistrés avec succès.',
    ],
];
