<?php

declare(strict_types=1);

namespace Boo\MicroPlugin\WebpackEncore\Twig\Extension;

use Boo\MicroPlugin\WebpackEncore\Asset\EntrypointLookupInterface;
use Boo\MicroPlugin\WebpackEncore\TagRenderer\TagRendererInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EntryFilesTwigExtension extends AbstractExtension
{
    private TagRendererInterface $tagRenderer;
    private EntrypointLookupInterface $entrypointLookup;

    public function __construct(
        TagRendererInterface $tagRenderer,
        EntrypointLookupInterface $entrypointLookupCollection
    ) {
        $this->tagRenderer = $tagRenderer;
        $this->entrypointLookup = $entrypointLookupCollection;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('encore_entry_js_files', [$this->entrypointLookup, 'getJavaScriptFiles']),
            new TwigFunction('encore_entry_css_files', [$this->entrypointLookup, 'getCssFiles']),
            new TwigFunction('encore_entry_script_tags', [$this->tagRenderer, 'renderWebpackScriptTags'], ['is_safe' => ['html']]),
            new TwigFunction('encore_entry_link_tags', [$this->tagRenderer, 'renderWebpackLinkTags'], ['is_safe' => ['html']]),
            new TwigFunction('encore_entry_exists', [$this->entrypointLookup, 'entryExists']),
        ];
    }
}
