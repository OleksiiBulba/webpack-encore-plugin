<?php

declare(strict_types=1);

/*
 * This file is part of the WebpackEncore plugin for Micro Framework.
 * (c) Oleksii Bulba <oleksii.bulba@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OleksiiBulba\WebpackEncorePlugin;

use Micro\Framework\Kernel\Plugin\ConfigurableInterface;
use Micro\Framework\Kernel\Plugin\PluginConfigurationTrait;
use Micro\Framework\Kernel\Plugin\PluginDependedInterface;
use Micro\Plugin\Twig\Plugin\TwigExtensionPluginInterface;
use Micro\Plugin\Twig\TwigPlugin;
use OleksiiBulba\WebpackEncorePlugin\Asset\EntrypointLookup;
use OleksiiBulba\WebpackEncorePlugin\Asset\EntrypointLookupInterface;
use OleksiiBulba\WebpackEncorePlugin\TagRenderer\TagRenderer;
use OleksiiBulba\WebpackEncorePlugin\TagRenderer\TagRendererInterface;
use OleksiiBulba\WebpackEncorePlugin\Twig\Extension\EntryFilesTwigExtension;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Twig\Extension\ExtensionInterface;

/**
 * @psalm-suppress ImplementedReturnTypeMismatch
 *
 * @method WebpackEncorePluginConfigurationInterface configuration()
 *
 * @codeCoverageIgnore
 */
class WebpackEncorePlugin implements TwigExtensionPluginInterface, ConfigurableInterface, PluginDependedInterface
{
    use PluginConfigurationTrait;

    public function provideTwigExtensions(): iterable
    {
        yield $this->createEntryFilesTwigExtension();
    }

    public function getDependedPlugins(): iterable
    {
        yield TwigPlugin::class;
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
        return new EntrypointLookup($this->configuration(), $this->createDecoder());
    }

    protected function createDecoder(): DecoderInterface
    {
        return new JsonDecode([JsonDecode::ASSOCIATIVE => true]);
    }
}
