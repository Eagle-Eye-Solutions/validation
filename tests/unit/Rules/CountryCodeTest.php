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
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules\CountryCode;

/**
 * @group  rule
 * @covers CountryCode
 */
class CountryCodeTest extends TestCase
{
    public function testShouldThrowsExceptionWhenInvalidFormat()
    {
        $this->expectExceptionMessage("\"whatever\" is not a valid country set");
        $this->expectException(ComponentException::class);
        new CountryCode('whatever');
    }

    public function testShouldUseISO3166Alpha2ByDefault()
    {
        $country = new CountryCode();
        static::assertEquals(CountryCode::ALPHA2, $country->set);
    }
    public function testShouldDefineACountryFormatOnConstructor()
    {
        $country = new CountryCode(CountryCode::NUMERIC);
        static::assertSame(CountryCode::NUMERIC, $country->set);
    }

    public static function providerForValidCountryCode()
    {
        return [
            [CountryCode::ALPHA2,  'BR'],
            [CountryCode::ALPHA3,  'BRA'],
            [CountryCode::NUMERIC, '076'],
            [CountryCode::ALPHA2,  'DE'],
            [CountryCode::ALPHA3,  'DEU'],
            [CountryCode::NUMERIC, '276'],
            [CountryCode::ALPHA2,  'US'],
            [CountryCode::ALPHA3,  'USA'],
            [CountryCode::NUMERIC, '840'],
        ];
    }

    public static function providerForInvalidCountryCode()
    {
        return [
            [CountryCode::ALPHA2,  'USA'],
            [CountryCode::ALPHA3,  'US'],
            [CountryCode::NUMERIC, '000'],
        ];
    }

    /**
     * @dataProvider providerForValidCountryCode
     */
    public function testValidContryCodes($format, $input)
    {
        $rule = new CountryCode($format);

        static::assertTrue($rule->validate($input));
    }

    /**
     * @dataProvider providerForInvalidCountryCode
     */
    public function testInvalidContryCodes($format, $input)
    {
        $rule = new CountryCode($format);

        static::assertFalse($rule->validate($input));
    }
}
