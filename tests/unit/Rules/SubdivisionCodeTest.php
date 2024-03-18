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
use Respect\Validation\Exceptions\SubdivisionCode\BrSubdivisionCodeException;
use Respect\Validation\Rules\SubdivisionCode;

/**
 * @covers SubdivisionCode
 * @covers SubdivisionCodeException
 */
class SubdivisionCodeTest extends TestCase
{
    public function testShouldThrowsExceptionWhenInvalidFormat(): void
    {
        $this->expectException(ComponentException::class);
        $this->expectExceptionMessage("\"whatever\" is not a valid country code in ISO 3166-2");
        new SubdivisionCode('whatever');
    }

    public function testShouldNotAcceptWrongNamesOnConstructor(): void
    {
        $this->expectExceptionMessage("\"JK\" is not a valid country code in ISO 3166-2");
        $this->expectException(ComponentException::class);
        new SubdivisionCode('JK');
    }

    public function testShouldDefineASubdivisionCodeFormatOnConstructor(): void
    {
        $countrySubdivision = new SubdivisionCode('US');

        static::assertSame('US', $countrySubdivision->countryCode);
    }

    public static function providerForValidSubdivisionCodeInformation(): array
    {
        return [
            ['AQ',  null],
            ['BR',  'SP'],
            ['MV',  '00'],
            ['US',  'CA'],
            ['YT',  ''],
        ];
    }

    /**
     * @dataProvider providerForValidSubdivisionCodeInformation
     * @throws ComponentException
     */
    public function testShouldValidateValidSubdivisionCodeInformation($countryCode, $input): void
    {
        $countrySubdivision = new SubdivisionCode($countryCode);

        static::assertTrue($countrySubdivision->validate($input));
    }

    public static function providerForInvalidSubdivisionCodeInformation(): array
    {
        return [
            ['BR',  'CA'],
            ['MV',  0],
            ['US',  'CE'],
        ];
    }

    /**
     * @dataProvider providerForInvalidSubdivisionCodeInformation
     * @throws ComponentException
     */
    public function testShouldNotValidateInvalidSubdivisionCodeInformation($countryCode, $input): void
    {
        $countrySubdivision = new SubdivisionCode($countryCode);

        static::assertFalse($countrySubdivision->validate($input));
    }

    public function testShouldThrowsSubdivisionCodeException(): void
    {
        $this->expectException(BrSubdivisionCodeException::class);
        $this->expectExceptionMessage("\"CA\" must be a subdivision code of Brazil");
        $countrySubdivision = new SubdivisionCode('BR');
        $countrySubdivision->assert('CA');
    }
}
