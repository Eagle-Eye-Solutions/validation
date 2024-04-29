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

use org\bovigo\vfs\content\LargeFileContent;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Rules\Exists;
use SplFileInfo;

/**
 * @group  rule
 * @covers Exists
 * @covers ExistsException
 */
class ExistsTest extends TestCase
{
    /**
     * @dataProvider fileProvider
     * @covers Exists::validate
     */
    public function testExistentFileShouldReturnTrue($file)
    {
        $rule = new Exists();
        static::assertTrue($rule->validate($file->url()));
    }

    /**
     * @covers Exists::validate
     */
    public function testNonExistentFileShouldReturnFalse()
    {
        $rule = new Exists();
        static::assertFalse($rule->validate('/path/of/a/non-existent/file'));
    }

    /**
     * @dataProvider fileProvider
     * @covers Exists::validate
     */
    public function testShouldValidateObjects($file)
    {
        $rule = new Exists();
        $object = new SplFileInfo($file->url());
        static::assertTrue($rule->validate($object));
    }

    public static function fileProvider()
    {
        $root = vfsStream::setup();
        $file = vfsStream::newFile('2kb.txt')->withContent(LargeFileContent::withKilobytes(2))->at($root);

        return [
            [$file],
        ];
    }
}
