<?php

/*
 * This file is part of the "Teaser" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Florian Wessels <hey@flossels.de>
 */

namespace Flossels\Teaser\Factory;

use Flossels\Teaser\Event\AfterTeaserBuildEvent;
use Flossels\Teaser\Event\BeforeTeaserBuildEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\LinkHandling\LinkService;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TeaserFactory implements SingletonInterface
{
    protected $eventDispatcher;

    protected $type = '';

    protected $settings = [];

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     *    pageuid => '90' (2 chars)
    type => 'page' (4 chars)
     *
     *    file => TYPO3\CMS\Core\Resource\Fileprototypeobject
    type => 'file' (4 chars)
     *
     * folder => TYPO3\CMS\Core\Resource\Folderprototypeobject
    type => 'folder' (6 chars)
     *
     *    url => 'https://example.com' (19 chars)
    type => 'url' (3 chars)
     *
     *    email => 'flo.wessels@gmail.com' (21 chars)
    type => 'email' (5 chars)
     *
     *    telephone => '01721893' (8 chars)
    type => 'telephone' (9 chars)
     */
    public function buildFromSource(array $linkData, array $settings): array
    {
        $type = $linkData['type'];
        $actualSettings = $settings[$type . '.'] ?? [];

        $this->dispatchBeforeTeaserBuildEvent($type, $actualSettings);
        $teaser = $this->getTeaser($linkData);
        $this->dispatchAfterTeaserBuildEvent($teaser);

        return $teaser;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    protected function dispatchBeforeTeaserBuildEvent(string $type, array $settings): void
    {
        $event = $this->eventDispatcher->dispatch(new BeforeTeaserBuildEvent($type, $settings));
        $this->type = $event->getType();
        $this->settings = $event->getSettings();
    }

    protected function dispatchAfterTeaserBuildEvent(array &$teaser): void
    {
        $teaser = $this->eventDispatcher->dispatch(new AfterTeaserBuildEvent($teaser, $this))->getTeaser();
    }

    public function getTeaser(array $configuration): array
    {
        switch ($this->type) {
            case LinkService::TYPE_EMAIL:
            case LinkService::TYPE_FOLDER:
            case LinkService::TYPE_RECORD:
            case LinkService::TYPE_TELEPHONE:
            case LinkService::TYPE_UNKNOWN:
            case LinkService::TYPE_URL:
                $this->settings['image.']['identifier'] = $this->settings['image.'][$this->type] ?? $this->settings['image.']['default'];
                return [];

            case LinkService::TYPE_FILE:
                return $this->getFileTeaser($configuration);

            case LinkService::TYPE_PAGE:
                return $this->getPageTeaser($configuration);

            default:
                // TODO: Throw Exception?
                return [];
        }
    }

    protected function getFileTeaser(array $configuration): array
    {
        $file = $configuration['file'];

        if ($file instanceof File === true) {
            $metaData = $file->getMetaData()->get();

            $this->settings['image.']['identifier'] = $file->isMediaFile()
                ? $file->getCombinedIdentifier()
                : $this->settings['image.'][$file->getExtension()] ?? $this->settings['image.']['default'];
        } elseif ((bool)$this->settings['showUnavailableFiles'] === false) {
            return ['available' => false];
        }

        return $this->parseSettings($metaData ?? []);
    }

    protected function getPageTeaser(array $configuration): array
    {
        $pageId = (int)($configuration['pageuid'] ?? 0);
        $page = GeneralUtility::makeInstance(PageRepository::class)->getPage($pageId);

        if (empty($page) && (bool)$this->settings['showUnavailablePages'] === false) {
            return ['available' => false];
        }

        $this->settings['image.']['identifier'] = $page['uid'];

        return $this->parseSettings($page);
    }

    protected function parseSettings(array $object): array
    {
        $teaser = [];

        foreach ($this->settings as $key => $fieldConfiguration) {
            $teaserProperty = rtrim($key, '.');

            if ($teaserProperty === 'image') {
                continue;
            }

            $fieldConfiguration = GeneralUtility::trimExplode(',', $fieldConfiguration, true);

            foreach ($fieldConfiguration as $databaseField) {
                if (isset($object[$databaseField]) && !empty($object[$databaseField])) {
                    $teaser[$teaserProperty] = $object[$databaseField];
                    break;
                }
            }
        }

        return $teaser;
    }
}
