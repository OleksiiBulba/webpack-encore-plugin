<?php

declare(strict_types=1);

namespace Boo\WebpackEncorePlugin\Tests\Unit\TagRenderer;

use Boo\WebpackEncorePlugin\Asset\EntrypointLookupInterface;
use Boo\WebpackEncorePlugin\TagRenderer\TagRenderer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Boo\WebpackEncorePlugin\TagRenderer\TagRenderer
 */
class TagRendererTest extends TestCase
{
    private TagRenderer $model;

    private EntrypointLookupInterface|MockObject $entrypointLookupMock;

    protected function setUp(): void
    {
        $this->entrypointLookupMock = $this->getMockBuilder(EntrypointLookupInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getCssFiles', 'getJavaScriptFiles'])
            ->getMockForAbstractClass();

        $this->model = new TagRenderer($this->entrypointLookupMock);
    }

    /**
     * @dataProvider renderWebpackLinkTagsDataProvider
     * @covers \Boo\WebpackEncorePlugin\TagRenderer\TagRenderer::renderWebpackLinkTags
     */
    public function testRenderWebpackLinkTags(string $entryName, array $extraAttributes, array $cssFiles, string $expectedLinkTagString)
    {
        $this->entrypointLookupMock->expects($this->once())
            ->method('getCssFiles')
            ->with($entryName)
            ->willReturn($cssFiles);

        $this->entrypointLookupMock->expects($this->never())
            ->method('getJavaScriptFiles');

        $this->entrypointLookupMock->expects($this->never())
            ->method('entryExists');

        $this->assertEquals($expectedLinkTagString, $this->model->renderWebpackLinkTags($entryName, $extraAttributes));
    }

    public function renderWebpackLinkTagsDataProvider(): array
    {
        return [
            'some-entry-with-two-css-files' => [
                'entryName' => 'some-entry-name',
                'extraAttributes' => [],
                'cssFiles' => [
                    '/path/to/css/file.css',
                    '/path/to/another/css/file.css',
                ],
                'expectedLinkTagString' => /** @lang text */ '<link rel="stylesheet" href="/path/to/css/file.css"><link rel="stylesheet" href="/path/to/another/css/file.css">',
            ]
        ];
    }

    /**
     * @dataProvider renderWebpackScriptTagsDataProvider
     * @covers \Boo\WebpackEncorePlugin\TagRenderer\TagRenderer::renderWebpackScriptTags
     */
    public function testRenderWebpackScriptTags(string $entryName, array $extraAttributes, array $jsFiles, string $expectedScriptTagString)
    {
        $this->entrypointLookupMock->expects($this->once())
            ->method('getJavaScriptFiles')
            ->with($entryName)
            ->willReturn($jsFiles);

        $this->entrypointLookupMock->expects($this->never())
            ->method('getCssFiles');

        $this->entrypointLookupMock->expects($this->never())
            ->method('entryExists');

        $this->assertEquals($expectedScriptTagString, $this->model->renderWebpackScriptTags($entryName, $extraAttributes));
    }

    public function renderWebpackScriptTagsDataProvider(): array
    {
        return [
            'some-entry-name' => [
                'entryName' => 'some-entry-name',
                'extraAttributes' => [],
                'jsFiles' => [
                    '/path/to/js/file.js',
                    '/path/to/another/js/file.js',
                ],
                'expectedScriptTagString' => /** @lang text */ '<script src="/path/to/js/file.js" type="application/javascript"></script><script src="/path/to/another/js/file.js" type="application/javascript"></script>',
            ],
            'some-entry-with-attribute-with-true-value' => [
                'entryName' => 'some-entry-name',
                'extraAttributes' => ['defer' => true],
                'jsFiles' => [
                    '/path/to/js/file.js',
                    '/path/to/another/js/file.js',
                ],
                'expectedScriptTagString' => /** @lang text */ '<script src="/path/to/js/file.js" type="application/javascript" defer></script><script src="/path/to/another/js/file.js" type="application/javascript" defer></script>',
            ],
        ];
    }
}
