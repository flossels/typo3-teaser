<?php

/*
 * This file is part of the "Teaser" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Florian Wessels <hey@flossels.de>
 */

namespace Flossels\Teaser\Service;

use TYPO3\CMS\Core\LinkHandling\LinkService;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ImageService
{
    protected $settings;

    protected $fileRepository;

    protected $type = '';

    public function __construct(array $settings)
    {
        $this->settings = $settings;
        $this->fileRepository = GeneralUtility::makeInstance(FileRepository::class);
    }

    public function forType(string $type): self
    {
        if ($type !== LinkService::TYPE_PAGE && $type !== LinkService::TYPE_FILE) {
            // TODO
            die;
        }

        $clonedObject = clone $this;
        $clonedObject->type = $type;

        return $clonedObject;
    }

    public function get(): string
    {
        switch ($this->type) {
            case LinkService::TYPE_FILE:
                return $this->settings['identifier'] ?? $this->settings['default'];

            case LinkService::TYPE_PAGE:
                return $this->getPageImage();
        }
    }

    protected function getPageImage(): string
    {
        if (!empty($this->settings['identifier'])) {
            $fields = GeneralUtility::trimExplode(',', $this->settings['fields'] ?? 'media');

            foreach ($fields as $field) {
                $files = $this->fileRepository->findByRelation('pages', $field, $this->settings['identifier']);

                if (!empty($files)) {
                    $fileReference = array_shift($files);

                    if ($fileReference instanceof FileReference) {
                        return $fileReference->getCombinedIdentifier();
                    }
                }
            }
        }

        return $this->settings['default'];
    }
}
