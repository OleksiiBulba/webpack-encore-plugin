<?php

declare(strict_types=1);

/*
 * This file is part of the WebpackEncore plugin for Micro Framework.
 * (c) Oleksii Bulba <oleksii.bulba@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boo\WebpackEncorePlugin\Asset;

use Boo\WebpackEncorePlugin\Exception\EntrypointNotFoundException;
use Boo\WebpackEncorePlugin\WebpackEncorePluginConfigurationInterface;

class EntrypointLookup implements EntrypointLookupInterface
{
    private mixed $entriesData = null;

    private array $returnedFiles = [];

    public function __construct(private readonly WebpackEncorePluginConfigurationInterface $configuration)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getJavaScriptFiles(string $entryName): array
    {
        return $this->getEntryFiles($entryName, 'js');
    }

    /**
     * {@inheritDoc}
     */
    public function getCssFiles(string $entryName): array
    {
        return $this->getEntryFiles($entryName, 'css');
    }

    public function entryExists(string $entryName): bool
    {
        $entriesData = $this->getEntriesData();

        return isset($entriesData['entrypoints'][$entryName]);
    }

    private function getEntryFiles(string $entryName, string $key): array
    {
        $this->validateEntryName($entryName);
        $entriesData = $this->getEntriesData();
        $entryData = $entriesData['entrypoints'][$entryName] ?? [];

        if (!isset($entryData[$key])) {
            // If we don't find the file type then just send back nothing.
            return [];
        }

        // make sure to not return the same file multiple times
        $entryFiles = $entryData[$key];
        $newFiles = array_values(array_diff($entryFiles, $this->returnedFiles));
        $this->returnedFiles = array_merge($this->returnedFiles, $newFiles);

        return $newFiles;
    }

    private function validateEntryName(string $entryName)
    {
        $entriesData = $this->getEntriesData();
        if (!isset($entriesData['entrypoints'][$entryName])) {
            if (false !== $dotPos = strrpos($entryName, '.')) {
                $withoutExtension = substr($entryName, 0, $dotPos);

                if (isset($entriesData['entrypoints'][$withoutExtension])) {
                    throw new EntrypointNotFoundException(sprintf('Could not find the entry "%s". Try "%s" instead (without the extension).', $entryName, $withoutExtension));
                }
            }

            throw new EntrypointNotFoundException(sprintf('Could not find the entry "%s" in "%s". Found: %s.', $entryName, $this->getEntrypointJsonPath(), implode(', ', array_keys($entriesData['entrypoints']))));
        }
    }

    private function getEntriesData(): array
    {
        if (null !== $this->entriesData) {
            return $this->entriesData;
        }

        if (!file_exists($this->getEntrypointJsonPath())) {
            throw new \InvalidArgumentException(sprintf('Could not find the entrypoints file from Webpack: the file "%s" does not exist.', $this->getEntrypointJsonPath()));
        }

        $this->entriesData = json_decode(file_get_contents($this->getEntrypointJsonPath()), true);

        if (null === $this->entriesData) {
            throw new \InvalidArgumentException(sprintf('There was a problem JSON decoding the "%s" file', $this->getEntrypointJsonPath()));
        }

        if (!isset($this->entriesData['entrypoints'])) {
            throw new \InvalidArgumentException(sprintf('Could not find an "entrypoints" key in the "%s" file', $this->getEntrypointJsonPath()));
        }

        return $this->entriesData;
    }

    private function getEntrypointJsonPath(): string
    {
        return $this->configuration->getOutputPath().'/entrypoints.json';
    }
}
