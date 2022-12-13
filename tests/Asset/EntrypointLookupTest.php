<?php

declare(strict_types=1);

namespace Boo\WebpackEncorePlugin\Tests\Asset;

use Boo\WebpackEncorePlugin\Asset\EntrypointLookup;
use Boo\WebpackEncorePlugin\WebpackEncorePluginConfigurationInterface;
use PHPUnit\Framework\TestCase;

class EntrypointLookupTest extends TestCase
{
    private EntrypointLookup $model;

    protected function setUp(): void
    {
        $configurationMock = $this->getMockBuilder(WebpackEncorePluginConfigurationInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getOutputPath'])
            ->getMockForAbstractClass();

        $configurationMock->expects($this->any())
            ->method('getOutputPath')
            ->willReturn(__DIR__.'/../fixtures/build');

        $this->model = new EntrypointLookup($configurationMock);
    }

    /**
     * @dataProvider getCssFilesDataProvider
     */
    public function testGetCssFiles(string $entryName, array $expectedResult): void
    {
        $this->assertEquals($expectedResult, $this->model->getCssFiles($entryName));
    }

    public function getCssFilesDataProvider(): array
    {
        return [
            [
                'entryName' => 'entrypoint1',
                'expectedResult' => [],
            ],
            [
                'entryName' => 'entrypoint2',
                'expectedResult' => [
                    "/some/path/to/js/file.css"
                ],
            ],
        ];
    }

    /**
     * @dataProvider getJavaScriptFilesDataProvider
     */
    public function testGetJavaScriptFiles(string $entryName, array $expectedResult): void
    {
        $this->assertEquals($expectedResult, $this->model->getJavaScriptFiles($entryName));
    }

    public function getJavaScriptFilesDataProvider(): array
    {
        return [
            [
                'entryName' => 'entrypoint1',
                'expectedResult' => [
                    "/some/path/to/jsFile.js",
                    "/another/path/to/js/file.js",
                    "/build/app.js",
                ],
            ],
            [
                'entryName' => 'entrypoint2',
                'expectedResult' => [
                    "/some/path/to/another/js/file.js",
                ],
            ],
        ];
    }

    /**
     * @dataProvider entryExistsDataProvider
     */
    public function testEntryExists(string $entryName, bool $expectedResult): void
    {
        $this->assertEquals($expectedResult, $this->model->entryExists($entryName));
    }

    public function entryExistsDataProvider(): array
    {
        return [
            [
                'entryName' => 'entrypoint1',
                'expectedResult' => true,
            ],
            [
                'entryName' => 'entrypoint2',
                'expectedResult' => true,
            ],
            [
                'entryName' => 'entrypoint3',
                'expectedResult' => false,
            ],
        ];
    }
}
