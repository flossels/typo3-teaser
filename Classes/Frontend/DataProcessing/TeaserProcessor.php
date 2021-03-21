<?php

/*
 * This file is part of the "Teaser" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Florian Wessels <hey@flossels.de>
 */

namespace Flossels\Teaser\Frontend\DataProcessing;

use Flossels\Teaser\Factory\TeaserFactory;
use Flossels\Teaser\Registry\LayoutTypeRegistry;
use Flossels\Teaser\Service\ImageService;
use TYPO3\CMS\Core\LinkHandling\LinkService;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class TeaserProcessor implements DataProcessorInterface
{
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        $data = $processedData['data'];
        $defaultTeaser = $this->getDefaultTeaser($data);

        if (!empty($data['header_link'])) {
            [$link, $_] = GeneralUtility::trimExplode(' ', $data['header_link'], true, 2);
            $linkData = GeneralUtility::makeInstance(LinkService::class)->resolveByStringRepresentation($link);
            $teaserFactory = GeneralUtility::makeInstance(TeaserFactory::class);
            $teaser = $teaserFactory->buildFromSource($linkData, $contentObjectConfiguration['settings.']['tx_teaser_teaser.']);

            if ($data['assets'] === 0) {
                $teaser['image'] = (new ImageService($teaserFactory->getSettings()['image.']))->forType($teaserFactory->getType())->get();
            }

            ArrayUtility::mergeRecursiveWithOverrule($teaser, $defaultTeaser, true, false);
        }

        $teaser = $teaser ?? $defaultTeaser;
        $teaser['headerLayout'] = $data['header_layout'];
        $teaser['link'] = $data['header_link'];
        $teaser['available'] = $teaser['available'] ?? true;

        // set the files into a variable, default "files"
        $targetVariableName = $cObj->stdWrapValue('as', $processorConfiguration, 'teaser');
        $processedData[$targetVariableName] = $teaser;

        return $processedData;
    }

    protected function getDefaultTeaser(array $data): array
    {
        return [
            'header' => $data['header'],
            'subheader' => $data['subheader'],
            'bodytext' => $data['bodytext'],
            'date' => $data['date'],
            'layout' => LayoutTypeRegistry::get($data['layout']),
            'linkLabel' => $this->getLinkLabel($data['table_caption'] ?? ''),
        ];
    }

    protected function getLinkLabel(string $linkLabel): string
    {
        if (empty($linkLabel)) {
            $linkLabel = $this->getLanguageService()->sL('LLL:EXT:teaser/Resources/Private/Language/locallang.xlf:read_more');
        }

        return $linkLabel;
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
