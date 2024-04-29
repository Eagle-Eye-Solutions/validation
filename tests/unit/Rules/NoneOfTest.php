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
use Respect\Validation\Exceptions\NoneOfException;
use Respect\Validation\Rules\Callback;
use Respect\Validation\Rules\NoneOf;

/**
 * @group  rule
 * @covers NoneOf
 * @covers NoneOfException
 */
class NoneOfTest extends TestCase
{
    /**
     * @throws ComponentException
     */
    public function testValid(): void
    {
        $valid1 = new Callback(function () {
            return false;
        });
        $valid2 = new Callback(function () {
            return false;
        });
        $valid3 = new Callback(function () {
            return false;
        });
        $o = new NoneOf($valid1, $valid2, $valid3);
        static::assertTrue($o->validate('any'));
        static::assertTrue($o->assert('any'));
        static::assertTrue($o->check('any'));
    }

    /**
     * @throws ComponentException
     */
    public function testInvalid(): void
    {
        $this->expectException(NoneOfException::class);
        $valid1 = new Callback(function () {
            return false;
        });
        $valid2 = new Callback(function () {
            return false;
        });
        $valid3 = new Callback(function () {
            return true;
        });
        $o = new NoneOf($valid1, $valid2, $valid3);
        static::assertFalse($o->validate('any'));
        static::assertFalse($o->assert('any'));
    }
}
