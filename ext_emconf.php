<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Interface for Service BW',
    'description' => 'With this extension you can access interface of service BW',
    'category' => 'plugin',
    'author' => 'Stefan Froemken, Hoja Mustaffa Abdul Latheef',
    'author_email' => 'projects@jweiland.net',
    'author_company' => 'jweiland.net',
    'state' => 'stable',
    'version' => '8.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.3-13.4.99',
            'scheduler' => '13.4.0-0.0.0',
            'maps2' => '12.0.0-0.0.0',
        ],
        'conflicts' => [
            'fal_dropbox' => '',
        ],
        'suggests' => [
            'solr' => '13.0.0-0.0.0',
        ],
    ],
];
