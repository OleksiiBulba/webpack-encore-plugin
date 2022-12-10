<?php

declare(strict_types=1);

namespace Boo\MicroPlugin\WebpackEncore;

use Micro\Component\DependencyInjection\Container;
use Micro\Framework\Kernel\Configuration\PluginConfigurationInterface;
use Micro\Framework\Kernel\Plugin\ConfigurableInterface;
use Micro\Framework\Kernel\Plugin\DependencyProviderInterface;
use Micro\Plugin\Twig\Plugin\TwigExtensionPluginInterface;
use Boo\MicroPlugin\WebpackEncore\Asset\EntrypointLookup;
use Boo\MicroPlugin\WebpackEncore\Asset\EntrypointLookupInterface;
use Boo\MicroPlugin\WebpackEncore\TagRenderer\TagRenderer;
use Boo\MicroPlugin\WebpackEncore\TagRenderer\TagRendererInterface;
use Boo\MicroPlugin\WebpackEncore\Twig\Extension\EntryFilesTwigExtension;
use Twig\Extension\ExtensionInterface;

class WebpackEncorePlugin implements DependencyProviderInterface, TwigExtensionPluginInterface, ConfigurableInterface
{
    private ?TagRendererInterface $tagRenderer = null;

    private ?EntrypointLookupInterface $entrypointLookup = null;

    private WebpackEncorePluginConfigurationInterface $configuration;

    public function provideDependencies(Container $container): void
    {
        $container->register(TagRendererInterface::class, function () {
            return $this->createTagRenderer();
        });
        $container->register(EntrypointLookupInterface::class, function () {
            return $this->createEntrypointLookup();
        });
    }

    public function provideTwigExtensions(): iterable
    {
        yield $this->createEntryFilesTwigExtension();
    }

    /**
     * {@inheritDoc}
     */
    public function setConfiguration(PluginConfigurationInterface $pluginConfiguration): void
    {
        if ($pluginConfiguration instanceof WebpackEncorePluginConfigurationInterface) {
            $this->configuration = $pluginConfiguration;

            return;
        }

        throw new \InvalidArgumentException(sprintf(
            'Plugin configuration should implement %s, provided %s',
            WebpackEncorePluginConfigurationInterface::class,
            \get_class($pluginConfiguration)
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function configuration(): WebpackEncorePluginConfigurationInterface
    {
        return $this->configuration;
    }

    private function createEntryFilesTwigExtension(): ExtensionInterface
    {
        return new EntryFilesTwigExtension($this->createTagRenderer(), $this->createEntrypointLookup());
    }

    private function createTagRenderer(): TagRendererInterface
    {
        if (null === $this->tagRenderer) {
            $this->tagRenderer = new TagRenderer($this->createEntrypointLookup());
        }

        return $this->tagRenderer;
    }

    private function createEntrypointLookup(): EntrypointLookupInterface
    {
        if (null === $this->entrypointLookup) {
            $this->entrypointLookup = new EntrypointLookup($this->configuration->getOutputPath().'/entrypoints.json');
        }

        return $this->entrypointLookup;
    }
}
