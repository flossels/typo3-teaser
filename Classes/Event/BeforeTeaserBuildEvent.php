<?php

/*
 * This file is part of the "Teaser" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Florian Wessels <hey@flossels.de>
 */

namespace Flossels\Teaser\Event;

final class BeforeTeaserBuildEvent
{
    protected $type;

    protected $settings;

    public function __construct(string $type, array $settings)
    {
        $this->type = $type;
        $this->settings = $settings;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }
}
