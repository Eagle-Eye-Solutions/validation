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

use finfo;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Respect\Validation\Rules\Image;
use SplFileInfo;
use SplFileObject;

#[CoversClass(Image::class)]
#[Group('rule')]
class ImageTest extends RuleTestCase
{
    public function testShouldAcceptAnInstanceOfInfoOnConstructor(): void
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $rule = new Image($finfo);

        static::assertSame($rule->fileInfo, $finfo);
    }

    public function testShouldHaveAnInstanceOfInfoByDefault(): void
    {
        $rule = new Image();

        static::assertInstanceOf('finfo', $rule->fileInfo);
    }

    public static function providerForValidInput(): array
    {
        $rule = new Image();
        $fixturesDirectory = realpath(__DIR__.'/../../fixtures/');

        return [
            [$rule, $fixturesDirectory.'/valid-image.gif'],
            [$rule, $fixturesDirectory.'/valid-image.jpg'],
            [$rule, $fixturesDirectory.'/valid-image.png'],
            [$rule, new SplFileInfo($fixturesDirectory.'/valid-image.gif')],
            [$rule, new SplFileInfo($fixturesDirectory.'/valid-image.jpg')],
            [$rule, new SplFileObject($fixturesDirectory.'/valid-image.png')],
        ];
    }

    public static function providerForInvalidInput(): array
    {
        $rule = new Image();
        $fixturesDirectory = realpath(__DIR__.'/../../fixtures/');

        return [
            [$rule, $fixturesDirectory.'/invalid-image.png'],
            [$rule, 'asdf'],
            [$rule, 1],
            [$rule, true],
        ];
    }
}
