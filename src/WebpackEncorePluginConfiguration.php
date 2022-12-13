<?php

declare(strict_types=1);

namespace Boo\WebpackEncorePlugin;

use Micro\Framework\Kernel\Configuration\PluginConfiguration;

class WebpackEncorePluginConfiguration extends PluginConfiguration implements WebpackEncorePluginConfigurationInterface
{
    const CFG_OUTPUT_PATH = 'WEBPACK_ENCORE_OUTPUT_PATH';

    public function getOutputPath(): string
    {
        return $this->configuration->get(self::CFG_OUTPUT_PATH,
            $this->configuration->get('BASE_PATH') . '/public/build'
        );
    }
}
