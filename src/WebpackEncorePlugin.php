<?php

declare(strict_types=1);

/*
 * This file is part of the WebpackEncore plugin for Micro Framework.
 * (c) Oleksii Bulba <oleksii.bulba@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boo\WebpackEncorePlugin;

use Boo\WebpackEncorePlugin\Asset\EntrypointLookup;
use Boo\WebpackEncorePlugin\Asset\EntrypointLookupInterface;
use Boo\WebpackEncorePlugin\TagRenderer\TagRenderer;
use Boo\WebpackEncorePlugin\TagRenderer\TagRendererInterface;
use Boo\WebpackEncorePlugin\Twig\Extension\EntryFilesTwigExtension;
use Micro\Framework\Kernel\Plugin\ConfigurableInterface;
use Micro\Framework\Kernel\Plugin\PluginConfigurationTrait;
use Micro\Plugin\Twig\Plugin\TwigExtensionPluginInterface;
use Twig\Extension\ExtensionInterface;

/**
 * @method WebpackEncorePluginConfigurationInterface configuration()
 *
 * @codeCoverageIgnore
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
