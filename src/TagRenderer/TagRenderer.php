<?php

declare(strict_types=1);

namespace Boo\MicroPlugin\WebpackEncore\TagRenderer;

use Boo\MicroPlugin\WebpackEncore\Asset\EntrypointLookupInterface;

class TagRenderer implements TagRendererInterface
{
    private array $renderedFiles = [];
    private array $defaultAttributes = [];
    private array $defaultScriptAttributes = [];
    private array $defaultLinkAttributes = [];

    public function __construct(private readonly EntrypointLookupInterface $entrypointLookup)
    {
    }

    public function renderWebpackScriptTags(string $entryName, string $packageName = null, array $extraAttributes = []): string
    {
        $scriptTags = [];

        foreach ($this->entrypointLookup->getJavaScriptFiles($entryName) as $filename) {
            $attributes = [];
            $attributes['src'] = $filename; // $this->getAssetPath($filename, $packageName);
            $attributes = array_merge($attributes, $this->defaultAttributes, $this->defaultScriptAttributes, $extraAttributes);

            $scriptTags[] = sprintf(
                '<script %s></script>',
                $this->convertArrayToAttributes($attributes)
            );

            $this->renderedFiles['scripts'][] = $attributes['src'];
        }

        return implode('', $scriptTags);
    }

    public function renderWebpackLinkTags(string $entryName, string $packageName = null, array $extraAttributes = []): string
    {
        $scriptTags = [];

        foreach ($this->entrypointLookup->getCssFiles($entryName) as $filename) {
            $attributes = [];
            $attributes['rel'] = 'stylesheet';
            $attributes['href'] = $filename; // $this->getAssetPath($filename, $packageName);
            $attributes = array_merge($attributes, $this->defaultAttributes, $this->defaultLinkAttributes, $extraAttributes);

            $scriptTags[] = sprintf(
                '<link %s>',
                $this->convertArrayToAttributes($attributes)
            );

            $this->renderedFiles['styles'][] = $attributes['href'];
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
