<?php

########################################################################
# Extension Manager/Repository config file for ext: "service_bw2"
#
# Auto generated by Extension Builder 2017-05-05
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
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
    'version' => '1.0.0',
    'constraints' => array(
        'depends' => array(
            'typo3' => '8.7.0-8.7.99',
            'maps2' => '3.0.0-3.99.99'
        ),
        'conflicts' => array(
            'fal_dropbox' => ''
        ),
        'suggests' => array(
        ),
    ),
);
