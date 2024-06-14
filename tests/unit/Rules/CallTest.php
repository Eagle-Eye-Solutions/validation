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
use Respect\Validation\Exceptions\CallException;
use Respect\Validation\Rules\ArrayVal;
use Respect\Validation\Rules\Call;

/**
 * @group  rule
 * @covers Call
 * @covers CallException
 */
class CallTest extends TestCase
{
    public function thisIsASampleCallbackUsedInsideThisTest()
    {
        return [];
    }

    public function testCallbackValidatorShouldAcceptEmptyString(): void
    {
        $v = new Call('str_split', new ArrayVal());
        static::assertTrue($v->assert(''));
    }

    public function testCallbackValidatorShouldAcceptStringWithFunctionName()
    {
        $v = new Call('str_split', new ArrayVal());
        static::assertTrue($v->assert('test'));
    }

    public function testCallbackValidatorShouldAcceptArrayCallbackDefinition()
    {
        $v = new Call([$this, 'thisIsASampleCallbackUsedInsideThisTest'], new ArrayVal());
        static::assertTrue($v->assert('test'));
    }

    public function testCallbackValidatorShouldAcceptClosures(): void
    {
        $v = new Call(function () {
            return [];
        }, new ArrayVal());
        static::assertTrue($v->assert('test'));
    }

    /**
     * @throws \Exception
     */
    public function testCallbackFailedShouldThrowCallException()
    {
        $this->expectException(CallException::class);
        $v = new Call('strrev', new ArrayVal());
        static::assertFalse($v->validate('test'));
        static::assertFalse($v->
        assert('test'));
    }
}
