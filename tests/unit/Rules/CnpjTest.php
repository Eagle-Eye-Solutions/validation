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
use Respect\Validation\Rules\Cnpj;

/**
 * @group  rule
 * @covers Cnpj
 * @covers CnpjException
 */
class CnpjTest extends TestCase
{
    protected $cnpjValidator;

    protected function setUp(): void
    {
        $this->cnpjValidator = new Cnpj();
    }

    /**
     * @dataProvider providerValidFormattedCnpj
     */
    public function testFormattedCnpjsShouldValidate($input): void
    {
        static::assertTrue($this->cnpjValidator->validate($input));
    }

    /**
     * @dataProvider providerValidUnformattedCnpj
     */
    public function testUnformattedCnpjsShouldValidate($input): void
    {
        static::assertTrue($this->cnpjValidator->validate($input));
    }

    /**
     * @dataProvider providerInvalidFormattedCnpj
     */
    public function testFormattedCnpjsShouldNotValidate($input): void
    {
        static::assertFalse($this->cnpjValidator->validate($input));
    }

    /**
     * @dataProvider providerInvalidUnformattedCnpj
     */
    public function testUnformattedCnpjsShouldNotValidate($input): void
    {
        static::assertFalse($this->cnpjValidator->validate($input));
    }

    /**
     * @dataProvider providerInvalidFormattedAndUnformattedCnpjLength
     */
    public function testFormattedAndUnformattedCnpjsShouldNotValidate($input): void
    {
        static::assertFalse($this->cnpjValidator->validate($input));
    }

    public static function providerValidFormattedCnpj(): array
    {
        return [
            ['32.063.364/0001-07'],
            ['24.663.454/0001-00'],
            ['57.535.083/0001-30'],
            ['24.760.428/0001-09'],
            ['27.355.204/0001-00'],
            ['36.310.327/0001-07'],
        ];
    }

    public static function providerValidUnformattedCnpj(): array
    {
        return [
            ['38175021000110'],
            ['37550610000179'],
            ['12774546000189'],
            ['77456211000168'],
            ['02023077000102'],
        ];
    }

    public static function providerInvalidFormattedCnpj(): array
    {
        return [
            ['12.345.678/9012-34'],
            ['11.111.111/1111-11'],
        ];
    }

    public static function providerInvalidUnformattedCnpj(): array
    {
        return [
            ['11111111111'],
            ['22222222222'],
            ['12345678900'],
            ['99299929384'],
            ['84434895894'],
            ['44242340000'],
        ];
    }

    public static function providerInvalidFormattedAndUnformattedCnpjLength(): array
    {
        return [
            ['1'],
            ['22'],
            ['123'],
            ['992999999999929384'],
            ['99-010-0.'],
        ];
    }
}
