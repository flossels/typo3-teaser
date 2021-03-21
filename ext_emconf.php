<?php

$EM_CONF['teaser'] = [
    'title' => 'Teaser',
    'description' => 'Teaser elements for TYPO3.',
    'version' => '0.1.0-dev',
    'category' => 'plugin',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-10.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'state' => 'alpha',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearCacheOnLoad' => true,
    'author' => 'Florian Wessels',
    'author_email' => 'hey@flossels.de',
    'author_company' => 'Flossels',
    'autoload' => [
        'psr-4' => [
            'Flossels\\Teaser\\' => 'Classes',
        ],
    ],
    'autoload-dev' => [
        'psr-4' => [
            'Flossels\\Teaser\\Tests\\' => 'Classes/Tests',
        ],
    ],
];
