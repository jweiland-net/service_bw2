<?php

/*
 * This file is part of the package jweiland/sponsoring.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

return [
    'ctrl' => [
        'title' => 'Requests',
        'label' => 'id',
        'crdate' => 'crdate',
        'hideTable' => true,
    ],
    'types' => [
        '1' => [
            'showitem' => 'id, name, type, language',
        ],
    ],
    'palettes' => [],
    'columns' => [
        'id' => [
            'label' => 'ID',
            'config' => [
                'type' => 'number',
                'format' => 'integer',
                'size' => 30,
            ],
        ],
        'name' => [
            'label' => 'Name',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'type' => [
            'label' => 'Type',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'language' => [
            'label' => 'Language',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'data' => [
            'label' => 'Record Response',
            'config' => [
                'type' => 'json',
                'default' => '{}',
            ],
        ],
    ],
];
