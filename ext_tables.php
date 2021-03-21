<?php
defined('TYPO3') or die;

call_user_func(
    function($extensionKey) {
        // Add content element wizard to PageTSconfig
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            sprintf('@import "EXT:%s/Configuration/TsConfig/Page/Mod/Wizards/NewContentElement.tsconfig"', $extensionKey));
    }, 'teaser'
);
