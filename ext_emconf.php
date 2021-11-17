<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Interface for Service BW',
    'description' => 'With this extension you can access interface of service BW',
    'category' => 'plugin',
    'author' => 'Stefan Froemken, Markus Kugler, Pascal Rinker',
    'author_email' => 'projects@jweiland.net',
    'author_company' => 'jweiland.net',
    'state' => 'stable',
    'version' => '4.0.2',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.29-10.4.99',
            'maps2' => '8.0.0-0.0.0'
        ],
        'conflicts' => [
            'fal_dropbox' => ''
        ],
        'suggests' => [
        ],
    ],
];
