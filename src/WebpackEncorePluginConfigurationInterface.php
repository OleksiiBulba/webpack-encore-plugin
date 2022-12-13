<?php

declare(strict_types=1);

namespace Boo\MicroPlugin\WebpackEncore;

interface WebpackEncorePluginConfigurationInterface
{
    public function getOutputPath(): string;
}
