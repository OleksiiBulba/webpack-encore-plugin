<?php

declare(strict_types=1);

/*
 * This file is part of the WebpackEncore plugin for Micro Framework.
 * (c) Oleksii Bulba <oleksii.bulba@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OleksiiBulba\WebpackEncorePlugin\Twig\Extension;

use OleksiiBulba\WebpackEncorePlugin\Asset\EntrypointLookupInterface;
use OleksiiBulba\WebpackEncorePlugin\TagRenderer\TagRendererInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @codeCoverageIgnore
 */
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
