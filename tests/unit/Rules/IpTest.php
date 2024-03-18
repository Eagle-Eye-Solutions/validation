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
use Respect\Validation\Exceptions\IpException;
use Respect\Validation\Rules\Ip;

/**
 * @group  rule
 * @covers Ip
 * @covers IpException
 */
class IpTest extends TestCase
{
    /**
     * @dataProvider providerForIp
     * @throws \Exception
     */
    public function testValidIpsShouldReturnTrue($input, $options = null): void
    {
        $ipValidator = new Ip($options);
        static::assertTrue($ipValidator->__invoke($input));
        static::assertTrue($ipValidator->assert($input));
        static::assertTrue($ipValidator->check($input));
    }

    /**
     * @dataProvider providerForIpBetweenRange
     * @throws \Exception
     */
    public function testIpsBetweenRangeShouldReturnTrue($input, $networkRange): void
    {
        $ipValidator = new Ip($networkRange);
        static::assertTrue($ipValidator->__invoke($input));
        static::assertTrue($ipValidator->assert($input));
        static::assertTrue($ipValidator->check($input));
    }

    /**
     * @dataProvider providerForNotIp
     *
     * @throws \Exception
     */
    public function testInvalidIpsShouldThrowIpException($input, $options = null): void
    {
        $this->expectException(IpException::class);
        $ipValidator = new Ip($options);
        static::assertFalse($ipValidator->__invoke($input));
        static::assertFalse($ipValidator->assert($input));
    }

    /**
     * @dataProvider providerForIpOutsideRange
     * @throws \Exception
     */
    public function testIpsOutsideRangeShouldReturnFalse($input, $networkRange): void
    {
        $this->expectException(IpException::class);
        $ipValidator = new Ip($networkRange);
        static::assertFalse($ipValidator->__invoke($input));
        static::assertFalse($ipValidator->assert($input));
    }

    public static function providerForIp(): array
    {
        return [
            ['127.0.0.1'],
        ];
    }

    public static function providerForIpBetweenRange(): array
    {
        return [
            ['127.0.0.1', '127.*'],
            ['127.0.0.1', '127.0.*'],
            ['127.0.0.1', '127.0.0.*'],
            ['192.168.2.6', '192.168.*.6'],
            ['192.168.2.6', '192.*.2.6'],
            ['10.168.2.6', '*.168.2.6'],
            ['192.168.2.6', '192.168.*.*'],
            ['192.10.2.6', '192.*.*.*'],
            ['192.168.255.156', '*'],
            ['192.168.255.156', '*.*.*.*'],
            ['127.0.0.1', '127.0.0.0-127.0.0.255'],
            ['192.168.2.6', '192.168.0.0-192.168.255.255'],
            ['192.10.2.6', '192.0.0.0-192.255.255.255'],
            ['192.168.255.156', '0.0.0.0-255.255.255.255'],
            ['220.78.173.2', '220.78.168/21'],
            ['220.78.173.2', '220.78.168.0/21'],
            ['220.78.173.2', '220.78.168.0/255.255.248.0'],
        ];
    }

    public static function providerForNotIp(): array
    {
        return [
            [''],
            [null],
            ['j'],
            [' '],
            ['Foo'],
            ['192.168.0.1', FILTER_FLAG_NO_PRIV_RANGE],
        ];
    }

    public static function providerForIpOutsideRange(): array
    {
        return [
            ['127.0.0.1', '127.0.1.*'],
            ['192.168.2.6', '192.163.*.*'],
            ['192.10.2.6', '193.*.*.*'],
            ['127.0.0.1', '127.0.1.0-127.0.1.255'],
            ['192.168.2.6', '192.163.0.0-192.163.255.255'],
            ['192.10.2.6', '193.168.0.0-193.255.255.255'],
            ['220.78.176.1', '220.78.168/21'],
            ['220.78.176.2', '220.78.168.0/21'],
            ['220.78.176.3', '220.78.168.0/255.255.248.0'],
        ];
    }

    /**
     * @dataProvider providerForInvalidRanges
     *
     */
    public function testInvalidRangeShouldRaiseException($range): void
    {
        $this->expectException(ComponentException::class);
        new Ip($range);
    }

    public static function providerForInvalidRanges(): array
    {
        return [
            ['192.168'],
            ['asd'],
            ['192.168.0.0-192.168.0.256'],
            ['192.168.0.0-192.168.0.1/4'],
            ['192.168.0/1'],
            ['192.168.2.0/256.256.256.256'],
            ['192.168.2.0/8.256.256.256'],
        ];
    }
}
