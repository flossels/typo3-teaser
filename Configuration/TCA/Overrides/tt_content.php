<?php
defined('TYPO3') or die;

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'Teaser',
        'tx_teaser_teaser',
        'tx-teaser-teaser',
    ],
    'textmedia',
    'after'
);

$palettes = [
    'teaserHeader' => [
        'label' => 'LLL:EXT:teaser/Resources/Private/Language/Database.xlf:tt_content.palette.teaser_header',
        'showitem' => '
                header;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_formlabel,
                --linebreak--, 
                header_layout;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_layout_formlabel, 
                date;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:date_formlabel, 
                --linebreak--, 
                subheader;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:subheader_formlabel,
            ',
    ],
    'teaserLink' => [
        'label' => 'LLL:EXT:teaser/Resources/Private/Language/Database.xlf:tt_content.palette.teaser_link',
        'showitem' => '
            header_link,
            --linebreak--,
            table_caption;LLL:EXT:teaser/Resources/Private/Language/Database.xlf:tt_content.table_caption,
            layout,
        ',
    ]
];

\TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($GLOBALS['TCA']['tt_content']['palettes'], $palettes);

$GLOBALS['TCA']['tt_content']['types']['tx_teaser_teaser'] = [
    'showitem' => '
         --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            --palette--;;teaserLink,
        --div--;LLL:EXT:teaser/Resources/Private/Language/Database.xlf:tt_content.tabs.override,
            --palette--;;teaserHeader,
            assets;LLL:EXT:teaser/Resources/Private/Language/Database.xlf:tt_content.assets,
            bodytext;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:bodytext_formlabel,
         --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
         --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
         --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes, rowDescription,
    ',
    'columnsOverrides' => [
        'header_link' => [
            'description' => 'LLL:EXT:teaser/Resources/Private/Language/Database.xlf:tt_content.header_link.description',
        ],
        'bodytext' => [
            'config' => [
                'enableRichtext' => true,
            ],
        ],
        'assets' => [
            'config' => [
                'maxitems' => 1,
            ],
        ],
        'layout' => [
            'config' => [
                'items' => \Flossels\Teaser\Registry\LayoutTypeRegistry::getConfiguration(),
            ],
        ],
    ],
];
