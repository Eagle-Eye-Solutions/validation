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
use Respect\Validation\Exceptions\MacAddressException;
use Respect\Validation\Rules\MacAddress;

/**
 * @group  rule
 * @covers MacAddress
 * @covers MacAddressException
 */
class MacAddressTest extends TestCase
{
    protected MacAddress $macaddressValidator;

    protected function setUp(): void
    {
        $this->macaddressValidator = new MacAddress();
    }

    /**
     * @dataProvider providerForMacAddress
     */
    public function testValidMacaddressesShouldReturnTrue($input): void
    {
        static::assertTrue($this->macaddressValidator->__invoke($input));
        static::assertTrue($this->macaddressValidator->assert($input));
        static::assertTrue($this->macaddressValidator->check($input));
    }

    /**
     * @dataProvider providerForNotMacAddress
     * @throws \Exception
     */
    public function testInvalidMacaddressShouldThrowMacAddressException($input): void
    {
        $this->expectException(MacAddressException::class);
        static::assertFalse($this->macaddressValidator->__invoke($input));
        static::assertFalse($this->macaddressValidator->assert($input));
    }

    public static function providerForMacAddress(): array
    {
        return [
            ['00:11:22:33:44:55'],
            ['66-77-88-99-aa-bb'],
            ['AF:0F:bd:12:44:ba'],
            ['90-bc-d3-1a-dd-cc'],
        ];
    }

    public static function providerForNotMacAddress(): array
    {
        return [
            [''],
            ['00-1122:33:44:55'],
            ['66-77--99-jj-bb'],
            ['HH:0F-bd:12:44:ba'],
            ['90-bc-nk:1a-dd-cc'],
        ];
    }
}
