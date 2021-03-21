<?php

$EM_CONF['teaser'] = [
    'title' => 'Teaser',
    'description' => 'This extension allows you to create simple teasers to existing pages and files. You also have the possibility to create teasers for other links like external links, phone numbers or email addresses.',
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
