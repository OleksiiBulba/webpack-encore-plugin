<?php

declare(strict_types=1);

namespace Boo\MicroPlugin\WebpackEncore\TagRenderer;

interface TagRendererInterface
{
    public function renderWebpackScriptTags(string $entryName, array $extraAttributes = []): string;

    public function renderWebpackLinkTags(string $entryName, array $extraAttributes = []): string;
}
