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
use Respect\Validation\Exceptions\VersionException;
use Respect\Validation\Rules\Version;

/**
 * @group  rule
 * @covers Version
 * @covers VersionException
 */
class VersionTest extends TestCase
{
    /**
     * @dataProvider providerForValidVersion
     */
    public function testValidVersionShouldReturnTrue($input): void
    {
        $rule = new Version();
        static::assertTrue($rule->__invoke($input));
        static::assertTrue($rule->assert($input));
        static::assertTrue($rule->check($input));
    }

    /**
     * @dataProvider providerForInvalidVersion
     *
     * @throws \Exception
     */
    public function testInvalidVersionShouldThrowException($input): void
    {
        $this->expectException(VersionException::class);
        $rule = new Version();
        static::assertFalse($rule->__invoke($input));
        static::assertFalse($rule->assert($input));
    }

    public static function providerForValidVersion(): array
    {
        return [
            ['1.0.0'],
            ['1.0.0-alpha'],
            ['1.0.0-alpha.1'],
            ['1.0.0-0.3.7'],
            ['1.0.0-x.7.z.92'],
            ['1.3.7+build.2.b8f12d7'],
            ['1.3.7-rc.1'],
        ];
    }

    public static function providerForInvalidVersion()
    {
        return [
            [''],
            ['1.3.7--'],
            ['1.3.7++'],
            ['foo'],
            ['1.2.3.4'],
            ['1.2.3.4-beta'],
            ['beta'],
        ];
    }
}
