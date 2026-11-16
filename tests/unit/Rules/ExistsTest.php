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
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ExistsException;
use Respect\Validation\Rules\Exists;
use SplFileInfo;

#[CoversClass(Exists::class)]
#[CoversClass(ExistsException::class)]
#[Group('rule')]
class ExistsTest extends TestCase
{
    #[DataProvider('fileProvider')]
    public function testExistentFileShouldReturnTrue($file)
    {
        $rule = new Exists();
        static::assertTrue($rule->validate($file->url()));
    }

    public function testNonExistentFileShouldReturnFalse()
    {
        $rule = new Exists();
        static::assertFalse($rule->validate('/path/of/a/non-existent/file'));
    }

    #[DataProvider('fileProvider')]
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
