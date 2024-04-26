<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Interface for Service BW',
    'description' => 'With this extension you can access interface of service BW',
    'category' => 'plugin',
    'author' => 'Stefan Froemken, Markus Kugler',
    'author_email' => 'projects@jweiland.net',
    'author_company' => 'jweiland.net',
    'state' => 'stable',
    'version' => '6.0.6',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.32-11.5.99',
            'scheduler' => '10.4.32-11.5.99',
            'maps2' => '8.0.0-0.0.0',
        ],
        'conflicts' => [
            'fal_dropbox' => '',
        ],
        'suggests' => [
            'solr' => '10.2.0-0.0.0',
        ],
    ],
];
