<?php

/*
 * Language + theme configuration for the user-facing UI.
 *
 * Adding a new language: drop a new file at lang/{code}/ui.php mirroring the
 * existing keys, then register it below. Nothing else needs to change.
 */

return [
    'default' => env('APP_LOCALE', 'bn'),

    'supported' => [
        'bn' => [
            'label'     => 'বাংলা',
            'native'    => 'বাংলা',
            'english'   => 'Bangla',
            'html_lang' => 'bn',
            'dir'       => 'ltr',
        ],
        'en' => [
            'label'     => 'English',
            'native'    => 'English',
            'english'   => 'English',
            'html_lang' => 'en',
            'dir'       => 'ltr',
        ],
    ],

    'themes'        => ['light', 'dark'],
    'default_theme' => env('APP_THEME', 'light'),
];
