<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Interface for Service BW',
    'description' => 'With this extension you can access interface of service BW',
    'category' => 'plugin',
    'author' => 'Stefan Froemken, Markus Kugler, Pascal Rinker',
    'author_email' => 'projects@jweiland.net',
    'author_company' => 'jweiland.net',
    'shy' => '',
    'priority' => '',
    'module' => '',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 0,
    'lockType' => '',
    'version' => '1.2.2',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-8.7.99',
            'maps2' => '3.0.0-3.99.99'
        ],
        'conflicts' => [
            'fal_dropbox' => ''
        ],
        'suggests' => [
        ],
    ],
];
