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
use Respect\Validation\Exceptions\CallbackException;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules\Callback;

/**
 * @group  rule
 * @covers Callback
 * @covers CallbackException
 */
class CallbackTest extends TestCase
{
    private $truthy, $falsy;

    /**
     * @throws ComponentException
     */
    public function setUp(): void
    {
        $this->truthy = new Callback(function () {
            return true;
        });
        $this->falsy = new Callback(function () {
            return false;
        });
    }

    /**
     * @return true
     */
    public function thisIsASampleCallbackUsedInsideThisTest()
    {
        return true;
    }

    public function testShouldBeAbleToDefineLatestArgumentsOnConstructor()
    {
        $rule = new Callback('is_a', 'stdClass');

        static::assertTrue($rule->validate(new \stdClass()));
    }

    /**
     * @throws \Exception
     */
    public function testCallbackValidatorShouldReturnFalseForEmptyString()
    {
        $this->expectException(CallbackException::class);
        $this->falsy->assert('');
    }

    public function testCallbackValidatorShouldReturnTrueIfCallbackReturnsTrue()
    {
        static::assertTrue($this->truthy->assert('wpoiur'));
    }

    public function testCallbackValidatorShouldReturnFalseIfCallbackReturnsFalse()
    {
        $this->expectException(CallbackException::class);
        static::assertTrue($this->falsy->assert('w poiur'));
    }

    public function testCallbackValidatorShouldAcceptArrayCallbackDefinitions()
    {
        $v = new Callback([$this, 'thisIsASampleCallbackUsedInsideThisTest']);
        static::assertTrue($v->assert('test'));
    }

    public function testCallbackValidatorShouldAcceptFunctionNamesAsString()
    {
        $v = new Callback('is_string');
        static::assertTrue($v->assert('test'));
    }

    public function testInvalidCallbacksShouldRaiseComponentExceptionUponInstantiation()
    {
        $this->expectException(ComponentException::class);
        $v = new Callback(new \stdClass());
        static::assertTrue($v->assert('w poiur'));
    }
}
