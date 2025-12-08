<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Interface Translations
    |--------------------------------------------------------------------------
    |
    | Translations for the entire administration interface
    |
    */

    'navigation' => [
        'dashboard' => 'Dashboard',
        'projects' => 'Projects',
        'content' => 'Content',
        'settings' => 'Settings',
        'section_settings' => 'Section Visibility',
        'profile' => 'Profile',
        'messages' => 'Messages',
    ],

    'dashboard' => [
        'title' => 'Dashboard',
        'subtitle' => 'Monitor your content and system activity',
        'welcome' => 'Welcome',
        'overview' => 'Overview',

        'stats' => [
            'total_projects' => 'Total projects',
            'published_projects' => 'Published projects',
            'draft_projects' => 'Draft projects',
            'total_content' => 'Total content',
            'content_pieces' => 'Content pieces',
            'unread_messages' => 'Unread messages',
            'last_update' => 'Last update',
            'content_modified' => 'Content modified',
            'never' => 'Never',
        ],

        'recent_messages' => [
            'title' => 'Recent messages',
            'empty' => 'No messages yet',
            'empty_state' => 'No recent messages',
            'empty_description' => 'Contact messages will appear here when received',
            'no_subject' => 'No subject',
            'view_all' => 'View all messages',
            'unread' => 'Unread',
            'read' => 'Read',
        ],

        'quick_actions' => [
            'title' => 'Quick actions',
            'create_project' => 'Create project',
            'manage_content' => 'Manage content',
            'view_messages' => 'View messages',
        ],
    ],

    'projects' => [
        'title' => 'Projects',
        'management_title' => 'Project management',
        'subtitle' => 'Manage your portfolio projects',
        'list' => 'Projects list',
        'create' => 'Create project',
        'edit' => 'Edit project',
        'delete' => 'Delete project',
        'view' => 'View project',

        'empty_state' => [
            'title' => 'No projects',
            'description' => 'Start by creating your first project',
            'create_first' => 'Create your first project',
        ],

        'count' => ':count project|:count projects',

        'created_successfully' => 'Project created successfully.',
        'updated_successfully' => 'Project updated successfully.',
        'deleted_successfully' => 'Project deleted successfully.',
        'published_successfully' => 'Project published successfully.',
        'archived_successfully' => 'Project archived successfully.',
        'drafted_successfully' => 'Project reverted to draft successfully.',

        'actions' => [
            'publish' => 'Publish project',
            'publishing' => 'Publishing...',
            'archive' => 'Archive project',
            'set_to_draft' => 'Revert to draft',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'view_public' => 'View on site',
        ],

        'form' => [
            'create_title' => 'Create new project',
            'create_subtitle' => 'Create a new project for your portfolio',
            'edit_title' => 'Edit project',
            'edit_title_with_name' => 'Edit project :name',
            'edit_subtitle' => 'Edit project information',
            'form_description' => 'Fill in the project information',

            'section' => [
                'basic' => 'Basic information',
                'details' => 'Details',
                'media' => 'Media',
                'audio' => 'Audio',
            ],

            'title' => [
                'label' => 'Project title',
                'placeholder' => 'e.g. Album for Artist X',
                'help' => 'The main project title',
            ],

            'slug' => [
                'label' => 'Slug',
                'help' => 'Project URL (auto-generated)',
                'copy' => 'Copy link',
                'copied' => 'Link copied!',
            ],

            'status' => [
                'label' => 'Status',
                'current' => 'Current status',
            ],

            'description' => [
                'label' => 'Description',
                'placeholder' => 'Detailed project description (Markdown supported)',
                'help' => 'Full project description. You can use Markdown.',
            ],

            'short_description' => [
                'label' => 'Short description',
                'placeholder' => 'Brief project summary',
                'help' => 'Summary displayed in project list (max 500 characters)',
            ],

            'client_name' => [
                'label' => 'Client name',
                'placeholder' => 'e.g. Artist X',
                'help' => 'Artist or client name',
            ],

            'project_date' => [
                'label' => 'Project date',
                'placeholder' => 'YYYY-MM-DD',
                'help' => 'Completion or release date',
            ],

            'bandcamp_player' => [
                'label' => 'Bandcamp player',
                'placeholder' => 'Paste Bandcamp player embed code here',
                'help' => 'Bandcamp player iframe code (optional)',
            ],

            'featured_image' => [
                'label' => 'Featured image',
                'help' => 'Project cover image (min 800x600px)',
                'alt' => 'Project featured image',
                'upload' => 'Click to upload featured image',
                'upload_button' => 'Upload featured image',
                'change' => 'Change image',
                'remove' => 'Remove featured image',
                'uploaded_successfully' => 'Featured image uploaded successfully.',
                'deleted_successfully' => 'Featured image deleted successfully.',
                'uploading' => 'Uploading...',
            ],

            'gallery_images' => [
                'label' => 'Image gallery',
                'help' => 'Additional images for project gallery (max 10 images). Accepted formats: JPEG, PNG, GIF, WebP. Minimum size: 800x600px. Maximum size: 10MB per image.',
                'alt' => 'Gallery image',
                'preview_alt' => 'Gallery image preview',
                'upload' => 'Click to upload gallery images (multiple allowed)',
                'upload_button' => 'Upload gallery images',
                'remove' => 'Remove gallery image',
                'preview_count' => ':count image|:count images',
                'uploaded_successfully' => 'Images added to gallery successfully.',
                'deleted_successfully' => 'Image removed from gallery successfully.',
                'uploading' => 'Uploading...',
                'empty' => 'No images in gallery',
                'count' => ':count image|:count images',
            ],
        ],

        'errors' => [
            'not_found' => 'Project not found.',
            'cannot_delete' => 'Cannot delete project.',
            'cannot_publish' => 'Cannot publish project.',
            'cannot_archive' => 'Cannot archive project.',
            'invalid_status' => 'Invalid status.',
            'file_too_large' => 'File is too large.',
            'file_not_found' => 'File not found.',
            'invalid_format' => 'Invalid file format.',
            'upload_failed' => 'Upload failed.',
            'delete_failed' => 'Deletion failed.',
        ],

        'confirm' => [
            'delete' => 'Are you sure you want to delete this project?',
            'delete_message' => 'This action is irreversible. The project and all its media will be permanently deleted.',
            'publish' => 'Do you want to publish this project?',
            'publish_message' => 'The project will be visible on the public site.',
            'archive' => 'Do you want to archive this project?',
            'archive_message' => 'The project will no longer be visible on the public site.',
        ],

        'modals' => [
            'publish' => [
                'title' => 'Publish this project?',
                'message' => 'This project will become visible on your public portfolio. Make sure all content is finalized and a description is provided.',
                'warning' => 'Warning: No description is currently set. Publishing will fail without a description.',
            ],

            'archive' => [
                'title' => 'Archive this project?',
                'message' => 'This project will no longer be visible on your public portfolio. You can restore it later by reverting to draft or publishing it again.',
            ],

            'draft' => [
                'title' => 'Revert to draft?',
                'message' => 'This project will be reverted to draft and will no longer be publicly visible',
                'from_published' => 'on your portfolio.',
                'from_archived' => 'if it was archived.',
                'can_publish_again' => 'You can publish it again at any time.',
            ],
        ],
    ],

    'content' => [
        'title' => 'Content management',
        'subtitle' => 'Manage your site content and pages',
        'list' => 'Content list',
        'create' => 'Create content',
        'edit' => 'Edit content',
        'delete' => 'Delete content',
        'editing' => 'Editing',
        'on' => 'on',
        'page' => 'page',
        'create_new' => 'Create new content',
        'create_description' => 'Add new content to your site',

        'created_successfully' => 'Content created successfully.',
        'updated_successfully' => 'Content updated successfully.',
        'deleted_successfully' => 'Content deleted successfully.',

        'no_title' => 'No title set',
        'empty_content' => 'Empty content',

        'empty_state' => [
            'title' => 'No content found',
            'description' => 'Try adjusting your filters or search',
        ],

        'missing_keys' => [
            'title' => 'Missing keys',
            'description' => 'These keys don\'t have content yet',
            'create' => 'Create content',
        ],

        'filter' => [
            'page_label' => 'Filter by page',
            'all_pages' => 'All pages',
            'search_label' => 'Search content',
            'search_placeholder' => 'Search by key, title or content...',
        ],

        'results' => [
            'showing' => 'Showing',
            'content' => 'content',
            'contents' => 'contents',
            'for' => 'for',
            'use_ctrlf' => 'Use Ctrl+F to search within results',
        ],

        'status' => [
            'has_content' => 'Has content',
            'empty' => 'Empty',
            'has_title' => 'Has title',
        ],

        'form' => [
            'title' => 'Edit content',
            'create_title' => 'Create new content',

            'page' => [
                'label' => 'Page',
                'placeholder' => 'Select a page',
                'help' => 'Page this content belongs to',
            ],

            'key' => [
                'label' => 'Key',
                'placeholder' => 'Select a key',
                'help' => 'Unique content identifier (used in code)',
                'no_keys' => 'No keys available for this page',
            ],

            'title' => [
                'label' => 'Title',
                'placeholder' => 'Content title',
                'help' => 'Descriptive title (optional)',
            ],

            'content' => [
                'label' => 'Content',
                'placeholder' => 'Text content (Markdown supported)',
                'help' => 'Content that will be displayed on the site',
                'characters' => 'characters',
                'long' => 'Long content',
                'good_length' => 'Good length',
                'min_read' => 'min read',
            ],

            'preview' => 'Preview',
            'preview_empty' => 'Content preview will appear here once you start writing',

            'create_button' => 'Create content',
            'update_button' => 'Update',
            'copy_content' => 'Copy content',
            'delete_confirm' => 'Are you sure you want to delete this content?',

            'press' => 'Press',
            'to_save' => 'to save',
        ],

        'table' => [
            'page' => 'Page',
            'key' => 'Key',
            'title' => 'Title',
            'content' => 'Content',
            'status' => 'Status',
            'actions' => 'Actions',
        ],

        'errors' => [
            'not_found' => 'Content not found.',
            'duplicate_key' => 'This key already exists for this page.',
            'invalid_key' => 'Invalid key for this page.',
        ],
    ],

    'settings' => [
        'title' => 'Settings',
        'section_visibility' => 'Section visibility',
        'section_visibility_description' => 'Manage visible sections on your site pages. Hero sections and contact page sections cannot be disabled.',

        'sections' => [
            'title' => 'Page sections',
            'description' => 'Manage section visibility on public pages',
            'all_enabled' => 'All sections on this page are always enabled and cannot be disabled.',

            'home' => [
                'title' => 'Home page',
                'features' => [
                    'title' => 'Features section',
                    'description' => 'Controls the display of main features on the home page',
                ],
                'cta' => [
                    'title' => 'Call to action section',
                    'description' => 'Controls the display of the call to action section at the bottom of the home page',
                ],
            ],

            'about' => [
                'title' => 'About page',
                'experience' => [
                    'title' => 'Experience section',
                    'description' => 'Controls the display of the statistics section showing years of experience, projects and clients',
                ],
                'services' => [
                    'title' => 'Services section',
                    'description' => 'Controls the display of the services list',
                ],
                'philosophy' => [
                    'title' => 'Philosophy section',
                    'description' => 'Controls the display of the philosophy section with company values',
                ],
            ],
        ],

        'important_notes' => 'Important notes',
        'notes' => [
            'hero_always_enabled' => 'Hero sections are always displayed and cannot be disabled',
            'contact_always_enabled' => 'All contact page sections are always enabled',
            'immediate_effect' => 'Changes take effect immediately on the site',
            'disabled_not_shown' => 'Disabled sections will not appear in site navigation or content',
        ],

        'updated_successfully' => 'Settings updated successfully.',
        'saved_successfully' => 'Settings saved successfully.',
    ],
];
