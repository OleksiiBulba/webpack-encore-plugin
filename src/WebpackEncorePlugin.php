<?php

declare(strict_types=1);

namespace Boo\MicroPlugin\WebpackEncore;

use Micro\Framework\Kernel\Plugin\ConfigurableInterface;
use Micro\Framework\Kernel\Plugin\PluginConfigurationTrait;
use Micro\Plugin\Twig\Plugin\TwigExtensionPluginInterface;
use Boo\MicroPlugin\WebpackEncore\Asset\EntrypointLookup;
use Boo\MicroPlugin\WebpackEncore\Asset\EntrypointLookupInterface;
use Boo\MicroPlugin\WebpackEncore\TagRenderer\TagRenderer;
use Boo\MicroPlugin\WebpackEncore\TagRenderer\TagRendererInterface;
use Boo\MicroPlugin\WebpackEncore\Twig\Extension\EntryFilesTwigExtension;
use Twig\Extension\ExtensionInterface;

/**
 * @method WebpackEncorePluginConfigurationInterface configuration()
 */
class WebpackEncorePlugin implements TwigExtensionPluginInterface, ConfigurableInterface
{
    use PluginConfigurationTrait;

    public function provideTwigExtensions(): iterable
    {
        yield $this->createEntryFilesTwigExtension();
    }

    protected function createEntryFilesTwigExtension(): ExtensionInterface
    {
        return new EntryFilesTwigExtension($this->createTagRenderer(), $this->createEntrypointLookup());
    }

    protected function createTagRenderer(): TagRendererInterface
    {
        return new TagRenderer($this->createEntrypointLookup());
    }

    protected function createEntrypointLookup(): EntrypointLookupInterface
    {
        return new EntrypointLookup($this->configuration());
    }
}
