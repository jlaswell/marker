<?php

return [
    'images'        => [
        'nginx' => [
            'parent_repository' => 'docker-library/official-images',
            'location'          => 'library/nginx',
            'repository'        => 'realpage/nginx',
        ],
        'php'   => [
            'parent_repository' => 'docker-library/official-images',
            'location'          => 'library/php',
            'repository'        => 'realpage/php',
        ],
    ],
    'root_location' => 'tests/fixtures/repos',
];
