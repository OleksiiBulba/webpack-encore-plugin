<?php

declare(strict_types=1);

namespace Boo\MicroPlugin\WebpackEncore;

use Micro\Framework\Kernel\Configuration\PluginConfigurationInterface;

interface WebpackEncorePluginConfigurationInterface extends PluginConfigurationInterface
{
    public function getOutputPath(): string;
}
