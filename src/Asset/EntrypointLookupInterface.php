<?php

declare(strict_types=1);

/*
 * This file is part of the WebpackEncore plugin for Micro Framework.
 * (c) Oleksii Bulba <oleksii.bulba@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OleksiiBulba\WebpackEncorePlugin\Asset;

use OleksiiBulba\WebpackEncorePlugin\Exception\EntrypointNotFoundException;

interface EntrypointLookupInterface
{
    /**
     * @throws EntrypointNotFoundException if an entry name is passed that does not exist in entrypoints.json
     *
     * @psalm-return array<string>
     */
    public function getJavaScriptFiles(string $entryName): array;

    /**
     * @throws EntrypointNotFoundException if an entry name is passed that does not exist in entrypoints.json
     *
     * @psalm-return array<string>
     */
    public function getCssFiles(string $entryName): array;

    public function entryExists(string $entryName): bool;
}
