<?php

return [
    'uploads' => [
        'cover_image_max_kb' => env('CATALOGUE_COVER_MAX_KB', 10240),
        'file_max_kb' => env('CATALOGUE_FILE_MAX_KB', 51200),
    ],
];
