<?php

declare(strict_types=1);

namespace Boo\WebpackEncorePlugin\Tests\TagRenderer;

use Boo\WebpackEncorePlugin\Asset\EntrypointLookupInterface;
use Boo\WebpackEncorePlugin\TagRenderer\TagRenderer;
use PHPUnit\Framework\TestCase;

class TagRendererTest extends TestCase
{
    private $model;

    private $entrypointLookupMock;

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
                'expectedLinkTagString' => '<link rel="stylesheet" href="/path/to/css/file.css"><link rel="stylesheet" href="/path/to/another/css/file.css">',
            ]
        ];
    }

    /**
     * @dataProvider renderWebpackScriptTagsDataProvider
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
                'expectedScriptTagString' => '<script src="/path/to/js/file.js" type="application/javascript"></script><script src="/path/to/another/js/file.js" type="application/javascript"></script>',
            ]
        ];
    }
}