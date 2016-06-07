<?php

return [
    'github_username' => env('GITHUB_USERNAME', ''),
    'github_token'    => env('GITHUB_TOKEN', ''),
    'root_location'   => 'storage/repos',
    'images'          => [
        'nginx'      => [
            'parent_repository' => 'docker-library/official-images',
            'location'          => 'library/nginx',
            'repository'        => 'realpage/nginx',
        ],
        'php'        => [
            'parent_repository' => 'docker-library/official-images',
            'location'          => 'library/php',
            'repository'        => 'realpage/php',
        ],
    ],
];
