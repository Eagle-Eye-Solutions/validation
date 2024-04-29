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
use Respect\Validation\Exceptions\RomanException;
use Respect\Validation\Rules\Roman;

/**
 * @group  rule
 * @covers Roman
 * @covers RomanException
 */
class RomanTest extends TestCase
{
    protected $romanValidator;

    protected function setUp(): void
    {
        $this->romanValidator = new Roman();
    }

    /**
     * @dataProvider providerForRoman
     */
    public function testValidRomansShouldReturnTrue($input)
    {
        static::assertTrue($this->romanValidator->__invoke($input));
        static::assertTrue($this->romanValidator->assert($input));
        static::assertTrue($this->romanValidator->check($input));
    }

    /**
     * @dataProvider providerForNotRoman
     *
     */
    public function testInvalidRomansShouldThrowRomanException($input)
    {
        $this->expectException(RomanException::class);
        static::assertFalse($this->romanValidator->__invoke($input));
        static::assertFalse($this->romanValidator->assert($input));
    }

    public static function providerForRoman()
    {
        return [
            [''],
            ['III'],
            ['IV'],
            ['VI'],
            ['XIX'],
            ['XLII'],
            ['LXII'],
            ['CXLIX'],
            ['CLIII'],
            ['MCCXXXIV'],
            ['MMXXIV'],
            ['MCMLXXV'],
            ['MMMMCMXCIX'],
        ];
    }

    public static function providerForNotRoman()
    {
        return [
            [' '],
            ['IIII'],
            ['IVVVX'],
            ['CCDC'],
            ['MXM'],
            ['XIIIIIIII'],
            ['MIMIMI'],
        ];
    }
}
