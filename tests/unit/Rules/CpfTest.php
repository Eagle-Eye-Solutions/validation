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
use Respect\Validation\Exceptions\CpfException;
use Respect\Validation\Rules\Cpf;

/**
 * @group  rule
 * @covers Cpf
 * @covers CpfException
 */
class CpfTest extends TestCase
{
    protected $cpfValidator;

    protected function setUp(): void
    {
        $this->cpfValidator = new Cpf();
    }

    /**
     * @dataProvider providerValidFormattedCpf
     */
    public function testFormattedCpfsShouldValidate($input)
    {
        static::assertTrue($this->cpfValidator->assert($input));
    }

    /**
     * @dataProvider providerValidUnformattedCpf
     */
    public function testUnformattedCpfsShouldValidates($input)
    {
        static::assertTrue($this->cpfValidator->assert($input));
    }

    /**
     * @dataProvider providerInvalidFormattedCpf
     *
     */
    public function testInvalidCpfShouldFailWhenFormatted($input)
    {
        $this->expectException(CpfException::class);
        static::assertFalse($this->cpfValidator->assert($input));
    }

    /**
     * @dataProvider providerInvalidUnformattedCpf
     */
    public function testInvalidCpfShouldFailWhenNotFormatted($input)
    {
        $this->expectException(CpfException::class);
        static::assertFalse($this->cpfValidator->assert($input));
    }

    /**
     * @dataProvider providerInvalidFormattedAndUnformattedCpfLength
     */
    public function testCpfsWithIncorrectLengthShouldFail($input)
    {
        $this->expectException(CpfException::class);
        static::assertFalse($this->cpfValidator->assert($input));
    }

    public static function providerValidFormattedCpf()
    {
        return [
            ['342.444.198-88'],
            ['342.444.198.88'],
            ['350.45261819'],
            ['693-319-118-40'],
            ['3.6.8.8.9.2.5.5.4.8.8'],
        ];
    }

    public static function providerValidUnformattedCpf()
    {
        return [
            ['11598647644'],
            ['86734718697'],
            ['86223423284'],
            ['24845408333'],
            ['95574461102'],
        ];
    }

    public static function providerInvalidFormattedCpf()
    {
        return [
            [''],
            ['000.000.000-00'],
            ['111.222.444-05'],
            ['999999999.99'],
            ['8.8.8.8.8.8.8.8.8.8.8'],
            ['693-319-110-40'],
            ['698.111-111.00'],
        ];
    }

    public static function providerInvalidUnformattedCpf()
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

    public static function providerInvalidFormattedAndUnformattedCpfLength()
    {
        return [
            ['1'],
            ['22'],
            ['123'],
            ['992999999999929384'],
        ];
    }
}
