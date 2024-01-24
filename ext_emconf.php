<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Interface for Service BW',
    'description' => 'With this extension you can access interface of service BW',
    'category' => 'plugin',
    'author' => 'Stefan Froemken, Hoja Mustaffa Abdul Latheef',
    'author_email' => 'projects@jweiland.net',
    'author_company' => 'jweiland.net',
    'state' => 'stable',
    'version' => '7.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.9-12.4.99',
            'scheduler' => '12.4.0-12.4.99',
            'maps2' => '11.0.0-0.0.0',
        ],
        'conflicts' => [
            'fal_dropbox' => '',
        ],
        'suggests' => [
            'solr' => '12.0.0-0.0.0',
        ],
    ],
];
