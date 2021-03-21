<?php
defined('TYPO3') or die;

call_user_func(
    function($extensionKey) {
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $iconRegistry->registerIcon(
            'tx-teaser-teaser',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            [
                'source' => 'EXT:teaser/Resources/Public/Icons/Plugin.svg'
            ]
        );

        // Register default Layouts
        \Flossels\Teaser\Registry\LayoutTypeRegistry::add('LLL:EXT:teaser/Resources/Private/Language/Database.xlf:tt_content.layout.default', \Flossels\Teaser\Registry\LayoutTypeRegistry::DEFAULT_LAYOUT, 0, 'Default');
        \Flossels\Teaser\Registry\LayoutTypeRegistry::add('LLL:EXT:teaser/Resources/Private/Language/Database.xlf:tt_content.layout.overlay', 1616340092, 10, 'Overlay');
    },
    'teaser'
);
