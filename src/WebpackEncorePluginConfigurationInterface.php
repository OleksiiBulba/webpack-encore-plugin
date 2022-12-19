<?php

declare(strict_types=1);

/*
 * This file is part of the WebpackEncore plugin for Micro Framework.
 * (c) Oleksii Bulba <oleksii.bulba@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OleksiiBulba\WebpackEncorePlugin;

interface WebpackEncorePluginConfigurationInterface
{
    public function getOutputPath(): string;
}
