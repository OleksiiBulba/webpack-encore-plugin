<?php

declare(strict_types=1);

namespace Boo\WebpackEncorePlugin\Twig\Extension;

use Boo\WebpackEncorePlugin\Asset\EntrypointLookupInterface;
use Boo\WebpackEncorePlugin\TagRenderer\TagRendererInterface;
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
