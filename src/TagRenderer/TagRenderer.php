<?php

declare(strict_types=1);

/*
 * This file is part of the WebpackEncore plugin for Micro Framework.
 * (c) Oleksii Bulba <oleksii.bulba@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boo\WebpackEncorePlugin\TagRenderer;

use Boo\WebpackEncorePlugin\Asset\EntrypointLookupInterface;

class TagRenderer implements TagRendererInterface
{
    private array $defaultAttributes = [];
    private array $defaultScriptAttributes = [
        'type' => 'application/javascript',
    ];
    private array $defaultLinkAttributes = [];

    public function __construct(private readonly EntrypointLookupInterface $entrypointLookup)
    {
    }

    public function renderWebpackScriptTags(string $entryName, array $extraAttributes = []): string
    {
        $scriptTags = [];

        foreach ($this->entrypointLookup->getJavaScriptFiles($entryName) as $filename) {
            $attributes = [];
            $attributes['src'] = $filename;
            $attributes = array_merge($attributes, $this->defaultAttributes, $this->defaultScriptAttributes, $extraAttributes);

            $scriptTags[] = sprintf(
                /* @lang text */ '<script %s></script>',
                $this->convertArrayToAttributes($attributes)
            );
        }

        return implode('', $scriptTags);
    }

    public function renderWebpackLinkTags(string $entryName, array $extraAttributes = []): string
    {
        $scriptTags = [];

        foreach ($this->entrypointLookup->getCssFiles($entryName) as $filename) {
            $attributes = [];
            $attributes['rel'] = 'stylesheet';
            $attributes['href'] = $filename;
            $attributes = array_merge($attributes, $this->defaultAttributes, $this->defaultLinkAttributes, $extraAttributes);

            $scriptTags[] = sprintf(
                /* @lang text */ '<link %s>',
                $this->convertArrayToAttributes($attributes)
            );
        }

        return implode('', $scriptTags);
    }

    private function convertArrayToAttributes(array $attributesMap): string
    {
        // remove attributes set specifically to false
        $attributesMap = array_filter($attributesMap, static function ($value) {
            return false !== $value;
        });

        return implode(' ', array_map(
            static function ($key, $value) {
                // allows for things like defer: true to only render "defer"
                if (true === $value || null === $value) {
                    return $key;
                }

                return sprintf('%s="%s"', $key, htmlentities($value));
            },
            array_keys($attributesMap),
            $attributesMap
        ));
    }
}
