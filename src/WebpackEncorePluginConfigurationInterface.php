<?php

declare(strict_types=1);

namespace Boo\WebpackEncorePlugin;

interface WebpackEncorePluginConfigurationInterface
{
    public function getOutputPath(): string;
}
