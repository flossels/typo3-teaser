<?php

/*
 * This file is part of the "Teaser" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * Florian Wessels <hey@flossels.de>
 */

namespace Flossels\Teaser\Registry;

class LayoutTypeRegistry
{
    const DEFAULT_LAYOUT = 1616340091;

    protected static $layouts = [];

    public static function add(string $label, int $value, int $priority = 0, ?string $partialName = null)
    {
        self::$layouts[$value] = [
            'label' => $label,
            'value' => $value,
            'priority' => $priority,
            'partial' => $partialName ?? $label,
        ];
    }

    public static function remove(int $value): void
    {
        if (isset(self::$layouts[$value])) {
            unset(self::$layouts[$value]);
        }
    }

    public static function get(int $value)
    {
        return self::$layouts[$value] ?? self::$layouts[self::DEFAULT_LAYOUT];
    }

    public static function getConfiguration(): array
    {
        $layouts = self::$layouts;
        $configuration = [];

        usort($layouts, function (array $a, array $b) {
            if ($a['priority'] === $b['priority']) {
                return 0;
            }

            return ($a['priority'] < $b['priority']) ? -1 : 1;
        });

        foreach ($layouts as $layout) {
            $configuration[] = [$layout['label'], $layout['value']];
        }

        return $configuration;
    }
}
