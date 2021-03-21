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

use Flossels\Teaser\Factory\TeaserFactory;

final class AfterTeaserBuildEvent
{
    private $teaser;

    private $teaserFactory;

    public function __construct(array $teaser, TeaserFactory $teaserFactory)
    {
        $this->teaser = $teaser;
        $this->teaserFactory = $teaserFactory;
    }

    public function getTeaser(): array
    {
        return $this->teaser;
    }

    public function setTeaser(array $teaser): void
    {
        $this->teaser = $teaser;
    }

    public function getTeaserFactory(): TaserFactory
    {
        return $this->teaserFactory;
    }
}
