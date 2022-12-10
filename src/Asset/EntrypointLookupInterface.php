<?php

declare(strict_types=1);

namespace Boo\MicroPlugin\WebpackEncore\Asset;

use Boo\MicroPlugin\WebpackEncore\Exception\EntrypointNotFoundException;

interface EntrypointLookupInterface
{
    /**
     * @throws EntrypointNotFoundException if an entry name is passed that does not exist in entrypoints.json
     */
    public function getJavaScriptFiles(string $entryName): array;

    /**
     * @throws EntrypointNotFoundException if an entry name is passed that does not exist in entrypoints.json
     */
    public function getCssFiles(string $entryName): array;

    public function entryExists(string $entryName): bool;
}
