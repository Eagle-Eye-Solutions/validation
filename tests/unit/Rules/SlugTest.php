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
use Respect\Validation\Rules\Slug;

/**
 * @group  rule
 * @covers Slug
 */
class SlugTest extends TestCase
{
    /**
     * @dataProvider providerValidSlug
     */
    public function testValidSlug($input)
    {
        $rule = new Slug();

        static::assertTrue($rule->validate($input));
    }

    /**
     * @dataProvider providerInvalidSlug
     */
    public function testInvalidSlug($input)
    {
        $rule = new Slug();

        static::assertFalse($rule->validate($input));
    }

    public static function providerValidSlug()
    {
        return [
            ['o-rato-roeu-o-rei-de-roma'],
            ['o-alganet-e-um-feio'],
            ['a-e-i-o-u'],
            ['anticonstitucionalissimamente'],
        ];
    }

    public static function providerInvalidSlug()
    {
        return [
            [''],
            ['o-alganet-é-um-feio'],
            ['á-é-í-ó-ú'],
            ['-assim-nao-pode'],
            ['assim-tambem-nao-'],
            ['nem--assim'],
            ['--nem-assim'],
            ['Nem mesmo Assim'],
            ['Ou-ate-assim'],
            ['-Se juntar-tudo-Então-'],
        ];
    }
}
