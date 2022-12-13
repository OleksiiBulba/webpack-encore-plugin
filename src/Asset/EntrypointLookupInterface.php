<?php

declare(strict_types=1);

namespace Boo\WebpackEncorePlugin\Asset;

use Boo\WebpackEncorePlugin\Exception\EntrypointNotFoundException;

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
