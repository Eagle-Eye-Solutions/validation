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
use Respect\Validation\Exceptions\PostalCodeException;
use Respect\Validation\Rules\PostalCode;

/**
 * @group  rule
 * @covers PostalCode
 * @covers PostalCodeException
 */
class PostalCodeTest extends TestCase
{
    /**
     * @throws ComponentException
     */
    public function testShouldUsePatternAccordingToCountryCode(): void
    {
        $countryCode = 'BR';

        $rule = new PostalCode($countryCode);

        $actualPattern = $rule->regex;
        $expectedPattern = $rule->postalCodes[$countryCode];

        static::assertEquals($expectedPattern, $actualPattern);
    }

    public function testChoosingPatternAccordingToCountryCode()
    {
        $rule1 = new PostalCode('BR');
        $rule2 = new PostalCode('br');

        static::assertSame($rule1->regex, $rule2->regex);
    }

    public function testCountryCodeDoesNotHavePostalCode(): void
    {
        $rule = new PostalCode('ZW');

        $actualPattern = $rule->regex;
        $expectedPattern = PostalCode::DEFAULT_PATTERN;

        static::assertSame($expectedPattern, $actualPattern);
    }

    public function testUsingDefaultPatternEmptyString(): void
    {
        $rule = new PostalCode('ZW');

        static::assertTrue($rule->validate(''));
    }

    public function testUsingDefaultPatternNonEmptyString(): void
    {
        $rule = new PostalCode('ZW');

        static::assertFalse($rule->validate(' '));
    }

    public function testShouldThrowsExceptionWhenCountryCodeIsNotValid()
    {
        $this->expectExceptionMessage("Cannot validate postal code from \"Whatever\" country");
        $this->expectException(ComponentException::class);
        new PostalCode('Whatever');
    }

    /**
     * @dataProvider validPostalCodesProvider
     * @throws ComponentException
     */
    public function testPatternAccordingToTheDefinedCountryCode($countryCode, $postalCode): void
    {
        $rule = new PostalCode($countryCode);

        static::assertTrue($rule->validate($postalCode));
    }

    public static function validPostalCodesProvider(): array
    {
        return [
            ['BR', '02179-000'],
            ['BR', '02179000'],
            ['GB', 'GIR 0AA'],
            ['GB', 'PR1 9LY'],
            ['US', '02179'],
            ['YE', ''],
            ['PL', '99-300'],
        ];
    }

    /**
     * @dataProvider invalidPostalCodesProvider
     * @throws ComponentException
     */
    public function testShouldNotValidatePatternAccordingToTheDefinedCountryCode($countryCode, $postalCode): void
    {
        $rule = new PostalCode($countryCode);

        static::assertFalse($rule->validate($postalCode));
    }

    public function testShouldThrowsPostalCodeExceptionWhenValidationFails(): void
    {
        $this->expectExceptionMessage("\"02179-000\" must be a valid postal code on \"US\"");
        $this->expectException(PostalCodeException::class);
        $rule = new PostalCode('US');
        $rule->check('02179-000');
    }

    public static function invalidPostalCodesProvider(): array
    {
        return [
            ['BR', '02179'],
            ['BR', '02179.000'],
            ['GB', 'GIR 00A'],
            ['GB', 'GIR0AA'],
            ['GB', 'PR19LY'],
            ['US', '021 79'],
            ['YE', '02179'],
            ['PL', '99300'],
        ];
    }
}
