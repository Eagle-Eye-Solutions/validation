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

use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\DirectoryException;
use Respect\Validation\Rules\Directory;

/**
 * @group  rule
 * @covers Directory
 * @covers DirectoryException
 */
class DirectoryTest extends TestCase
{
    /**
     * @dataProvider providerForValidDirectory
     */
    public function testValidDirectoryShouldReturnTrue($input)
    {
        $rule = new Directory();
        static::assertTrue($rule->__invoke($input));
        static::assertTrue($rule->assert($input));
        static::assertTrue($rule->check($input));
    }

    /**
     * @dataProvider providerForInvalidDirectory
     *
     */
    public function testInvalidDirectoryShouldThrowException($input)
    {
        $this->expectException(DirectoryException::class);
        $rule = new Directory();
        static::assertFalse($rule->__invoke($input));
        static::assertFalse($rule->assert($input));
        static::assertFalse($rule->check($input));
    }

    /**
     * @dataProvider providerForDirectoryObjects
     */
    public function testDirectoryWithObjects($object, $valid)
    {
        $rule = new Directory();
        static::assertEquals($valid, $rule->validate($object));
    }

    public static function providerForDirectoryObjects()
    {
        return [
            [new \SplFileInfo(__DIR__), true],
            [new \SplFileInfo(__FILE__), false],
            /*
             * PHP 5.4 does not allows to use SplFileObject with directories.
             * array(new \SplFileObject(__DIR__), true),
             */
            [new \SplFileObject(__FILE__), false],
        ];
    }

    public static function providerForValidDirectory()
    {
        $directories = [
            sys_get_temp_dir().DIRECTORY_SEPARATOR.'dataprovider-1',
            sys_get_temp_dir().DIRECTORY_SEPARATOR.'dataprovider-2',
            sys_get_temp_dir().DIRECTORY_SEPARATOR.'dataprovider-3',
            sys_get_temp_dir().DIRECTORY_SEPARATOR.'dataprovider-4',
            sys_get_temp_dir().DIRECTORY_SEPARATOR.'dataprovider-5',
        ];

        return array_map(
            function ($directory) {
                if (!is_dir($directory)) {
                    mkdir($directory, 0766, true);
                }

                return [realpath($directory)];
            },
            $directories
        );
    }

    public static function providerForInvalidDirectory()
    {
        return array_chunk(
            [
                '',
                __FILE__,
                __DIR__.'/../../../../../README.md',
                __DIR__.'/../../../../../composer.json',
                new \stdClass(),
                [__DIR__],
            ],
            1
        );
    }
}
