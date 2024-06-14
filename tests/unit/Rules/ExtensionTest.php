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
use Respect\Validation\Exceptions\ExtensionException;
use Respect\Validation\Rules\Extension;
use SplFileInfo;

/**
 * @group  rule
 * @covers Extension
 * @covers ExtensionException
 */
class ExtensionTest extends TestCase
{
    public static function providerValidExtension(): array
    {
        return [
            ['filename.txt', 'txt'],
            ['filename.jpg', 'jpg'],
            ['filename.inc.php', 'php'],
            ['filename.foo.bar.bz2', 'bz2'],
        ];
    }

    /**
     * @dataProvider providerValidExtension
     */
    public function testShouldValidateExtension($filename, $extension): void
    {
        $rule = new Extension($extension);

        static::assertTrue($rule->validate($filename));
    }

    public function testShouldAcceptSplFileInfo(): void
    {
        $fileInfo = new SplFileInfo(__FILE__);

        $rule = new Extension('php');

        static::assertTrue($rule->validate($fileInfo));
    }

    public function testShouldInvalidWhenNotStringNorSplFileInfo(): void
    {
        $nonFile = [__FILE__];

        $rule = new Extension('php');

        static::assertFalse($rule->validate($nonFile));
    }

    public function testShouldThrowExtensionExceptionWhenCheckingValue()
    {
        $this->expectException(ExtensionException::class);
        $this->expectExceptionMessage("\"filename.jpg\" must have \"png\" extension");
        $rule = new Extension('png');
        $rule->check('filename.jpg');
    }
}
