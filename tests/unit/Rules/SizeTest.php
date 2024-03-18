<?php

/*
 * This file is part of Respect/Validation.
 *
 * (c) Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation\Test\Rules;

use bovigo\vfs\content\LargeFileContent;
use bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Exceptions\SizeException;
use Respect\Validation\Rules\Size;
use SplFileInfo;

/**
 * @author Henrique Moody <henriquemoody@gmail.com>
 * @group  rule
 * @covers Size
 * @covers SizeException
 */
class SizeTest extends TestCase
{
    public static function validSizeProvider(): array
    {
        return [
            [42, 42],
            ['1b', 1],
            ['1kb', 1024],
            ['1mb', 1048576],
            ['1gb', 1073741824],
            ['1tb', 1099511627776],
            ['1pb', 1125899906842624],
            ['1eb', 1152921504606846976],
            ['1zb', 1.1805916207174113E+21],
            ['1yb', 1.2089258196146292E+24],
        ];
    }

    public static function validFileProvider(): array
    {
        $root = vfsStream::setup();

        $file2Kb = vfsStream::newFile('2kb.txt')->withContent(LargeFileContent::withKilobytes(2))->at($root);
        $file2Mb = vfsStream::newFile('2mb.txt')->withContent(LargeFileContent::withMegabytes(2))->at($root);

        return [
            // Valid data
            [$file2Kb->url(), '1kb', null, true],
            [$file2Kb->url(), '2kb', null, true],
            [$file2Kb->url(), null, '2kb', true],
            [$file2Kb->url(), null, '3kb', true],
            [$file2Kb->url(), '1kb', '3kb', true],
            [$file2Mb->url(), '1mb', null, true],
            [$file2Mb->url(), '2mb', null, true],
            [$file2Mb->url(), null, '2mb', true],
            [$file2Mb->url(), null, '3mb', true],
            [$file2Mb->url(), '1mb', '3mb', true],
            // Invalid data
            [$file2Kb->url(), '3kb', null, false],
            [$file2Kb->url(), null, '1kb', false],
            [$file2Kb->url(), '1kb', '1.5kb', false],
            [$file2Mb->url(), '2.5mb', null, false],
            [$file2Mb->url(), '3gb', null, false],
            [$file2Mb->url(), null, '1b', false],
            [$file2Mb->url(), '1pb', '3pb', false],
        ];
    }

    /**
     * @dataProvider validSizeProvider
     */
    public function testShouldConvertUnitonConstructor($size, $bytes): void
    {
        $rule = new Size($size);

        static::assertSame($bytes, $rule->minValue);
    }

    public function testShouldThrowsAnExceptionWhenSizeIsNotValid(): void
    {
        $this->expectExceptionMessage("\"42jb\" is not a recognized file size");
        $this->expectException(ComponentException::class);
        new Size('42jb');
    }

    /**
     * @dataProvider validFileProvider
     */
    public function testShouldValidateFile($filename, $minSize, $maxSize, $expectedValidation): void
    {
        $rule = new Size($minSize, $maxSize);

        static::assertSame($expectedValidation, $rule->validate($filename));
    }

    public function testShouldValidateSplFileInfo(): void
    {
        $root = vfsStream::setup();
        $file1Gb = vfsStream::newFile('1gb.txt')->withContent(LargeFileContent::withGigabytes(1))->at($root);
        $file1GbObject = new SplFileInfo($file1Gb->url());

        $rule = new Size('1MB', '2GB');

        static::assertTrue($rule->validate($file1GbObject));
    }

    /**
     * @throws \Exception
     */
    public function testShouldThrowsSizeExceptionWhenAsserting()
    {
        $this->expectExceptionMessageMatches("#\"vfs:.?/.?/root.?/1gb.txt\" must be greater than \"2pb\"#");
        $this->expectException(SizeException::class);
        $root = vfsStream::setup();
        $file1Gb = vfsStream::newFile('1gb.txt')->withContent(LargeFileContent::withGigabytes(1))->at($root);

        $rule = new Size('2pb');
        $rule->assert($file1Gb->url());
    }
}
