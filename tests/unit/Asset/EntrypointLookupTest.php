<?php

declare(strict_types=1);

/*
 * This file is part of the WebpackEncore plugin for Micro Framework.
 * (c) Oleksii Bulba <oleksii.bulba@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OleksiiBulba\WebpackEncorePlugin\Tests\Unit\Asset;

use OleksiiBulba\WebpackEncorePlugin\Asset\EntrypointLookup;
use OleksiiBulba\WebpackEncorePlugin\Exception\EntrypointNotFoundException;
use OleksiiBulba\WebpackEncorePlugin\WebpackEncorePluginConfigurationInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\JsonDecode;

/**
 * @covers \OleksiiBulba\WebpackEncorePlugin\Asset\EntrypointLookup
 */
class EntrypointLookupTest extends TestCase
{
    private EntrypointLookup $model;

    private WebpackEncorePluginConfigurationInterface|MockObject $configurationMock;

    protected function setUp(): void
    {
        $this->configurationMock = $this->getMockBuilder(WebpackEncorePluginConfigurationInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getOutputPath'])
            ->getMockForAbstractClass();

        $this->model = new EntrypointLookup($this->configurationMock, new JsonDecode([JsonDecode::ASSOCIATIVE => true]));
    }

    /**
     * @dataProvider getCssFilesDataProvider
     *
     * @covers \OleksiiBulba\WebpackEncorePlugin\Asset\EntrypointLookup::getCssFiles
     */
    public function testGetCssFiles(string $entryName, array $expectedResult): void
    {
        $this->configurationMock->expects($this->any())
            ->method('getOutputPath')
            ->willReturn(__DIR__.'/../fixtures/correct');

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
                    '/some/path/to/js/file.css',
                ],
            ],
        ];
    }

    /**
     * @dataProvider getJavaScriptFilesDataProvider
     *
     * @covers \OleksiiBulba\WebpackEncorePlugin\Asset\EntrypointLookup::getJavaScriptFiles
     */
    public function testGetJavaScriptFiles(string $entryName, array $expectedResult): void
    {
        $this->configurationMock->expects($this->any())
            ->method('getOutputPath')
            ->willReturn(__DIR__.'/../fixtures/correct');

        $this->assertEquals($expectedResult, $this->model->getJavaScriptFiles($entryName));
    }

    public function getJavaScriptFilesDataProvider(): array
    {
        return [
            [
                'entryName' => 'entrypoint1',
                'expectedResult' => [
                    '/some/path/to/jsFile.js',
                    '/another/path/to/js/file.js',
                    '/build/app.js',
                ],
            ],
            [
                'entryName' => 'entrypoint2',
                'expectedResult' => [
                    '/some/path/to/another/js/file.js',
                ],
            ],
        ];
    }

    /**
     * @dataProvider entryExistsDataProvider
     *
     * @covers \OleksiiBulba\WebpackEncorePlugin\Asset\EntrypointLookup::entryExists
     */
    public function testEntryExists(string $entryName, bool $expectedResult): void
    {
        $this->configurationMock->expects($this->any())
            ->method('getOutputPath')
            ->willReturn(__DIR__.'/../fixtures/correct');

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

    /**
     * @dataProvider exceptionsInValidateEntryNameDataProvider
     */
    public function testExceptionsInValidateEntryName(string $entryName, string $expectedExceptionMessageMatches): void
    {
        $this->configurationMock->expects($this->any())
            ->method('getOutputPath')
            ->willReturn(__DIR__.'/../fixtures/correct');

        $this->expectException(EntrypointNotFoundException::class);
        $this->expectExceptionMessageMatches($expectedExceptionMessageMatches);
        $this->model->getJavaScriptFiles($entryName);
    }

    public function exceptionsInValidateEntryNameDataProvider(): array
    {
        return [
            [
                'entryName' => 'entrypoint1.js',
                'expectedExceptionMessageMatches' => '/Could not find the entry "entrypoint1.js"\. Try "entrypoint1" instead \(without the extension\)\./',
            ],
            [
                'entryName' => 'entrypoint3',
                'expectedExceptionMessageMatches' => '/Could not find the entry "entrypoint3" in ".*". Found: entrypoint1, entrypoint2\./',
            ],
        ];
    }

    /**
     * @dataProvider invalidArgumentExceptionInGetEntriesDataDataProvider
     */
    public function testInvalidArgumentExceptionInGetEntriesData(string $entrypointFilePath, string $expectedExceptionMessageMatches): void
    {
        $this->configurationMock->expects($this->any())
            ->method('getOutputPath')
            ->willReturn(__DIR__.$entrypointFilePath);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches($expectedExceptionMessageMatches);
        $this->model->entryExists('some-entry-name');
    }

    public function invalidArgumentExceptionInGetEntriesDataDataProvider(): array
    {
        return [
            [
                'entrypointFilePath' => '/../fixtures/bad_json',
                'expectedExceptionMessageMatches' => '/There was a problem JSON decoding the ".*\/fixtures\/bad_json\/entrypoints.json" file/',
            ],
            [
                'entrypointFilePath' => '/../fixtures/no_entrypoint_key',
                'expectedExceptionMessageMatches' => '/Could not find an "entrypoints" key in the ".*\/fixtures\/no_entrypoint_key\/entrypoints.json" file/',
            ],
            [
                'entrypointFilePath' => '/../fixtures/no_file',
                'expectedExceptionMessageMatches' => '/Could not find the entrypoints file from Webpack: the file ".*\/fixtures\/no_file\/entrypoints.json" does not exist\./',
            ],
        ];
    }
}
