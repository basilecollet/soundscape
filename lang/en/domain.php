<?php

declare(strict_types=1);

return [
    'project' => [
        'cannot_publish_invalid_status' => 'Cannot publish project with status ":status". Only draft projects can be published.',
        'cannot_publish_missing_description' => 'Cannot publish project without description.',
        'cannot_be_archived' => 'Cannot archive project with status ":status". Only published projects can be archived.',
        'already_draft' => 'Project is already a draft.',
        'duplicate_slug' => 'A project with slug ":slug" already exists.',
        'not_found' => 'Project with slug ":slug" was not found.',

        'title' => [
            'empty' => 'Project title cannot be empty.',
            'too_long' => 'Project title cannot exceed :max characters (got :length).',
        ],

        'description' => [
            'empty' => 'Description cannot be empty.',
        ],

        'short_description' => [
            'empty' => 'Short description cannot be empty.',
            'too_long' => 'Short description cannot exceed :max characters (got :length).',
        ],

        'slug' => [
            'invalid' => 'Invalid slug format: ":slug". Slug must contain only lowercase letters, numbers and hyphens.',
        ],

        'client_name' => [
            'empty' => 'Client name cannot be empty.',
            'too_long' => 'Client name cannot exceed :max characters (got :length).',
            'invalid' => 'Failed to process client name: ":value".',
        ],

        'date' => [
            'invalid' => 'Invalid date format: ":date". A valid date is expected.',
        ],

        'bandcamp_player' => [
            'not_iframe' => 'Bandcamp player must be an iframe tag.',
            'not_bandcamp' => 'Bandcamp player must contain the bandcamp.com domain.',
            'too_long' => 'Bandcamp player code cannot exceed :max characters (got :length).',
        ],

        'image' => [
            'url_empty' => 'Image URL :type cannot be empty.',
            'url_invalid' => 'Invalid URL format: :url.',
        ],
    ],
];
