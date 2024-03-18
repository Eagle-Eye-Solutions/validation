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
use Respect\Validation\Exceptions\CallableTypeException;
use Respect\Validation\Rules\CallableType;

/**
 * @group  rule
 * @covers CallableType
 * @covers CallableTypeException
 */
class CallableTypeTest extends TestCase
{
    protected $rule;

    protected function setUp(): void
    {
        $this->rule = new CallableType();
    }

    /**
     * @dataProvider providerForCallable
     */
    public function testValidateCallableTypeNumbers($input): void
    {
        static::assertTrue($this->rule->validate($input));
    }

    public function testValidateCallableFunctionTypeNumbers(): void
    {
        static::assertTrue($this->rule->validate([$this, __FUNCTION__]));
    }

    /**
     * @dataProvider providerForNonCallable
     */
    public function testValidateNonCallableTypeNumbers($input): void
    {
        static::assertFalse($this->rule->validate($input));
    }

    public function testShouldThrowCallableTypeExceptionWhenChecking(): void
    {
        $this->expectExceptionMessage("\"testShouldThrowCallableTypeExceptionWhenChecking\" must be a callable");
        $this->expectException(CallableTypeException::class);
        $this->rule->check(__FUNCTION__);
    }

    public static function providerForCallable(): array
    {
        return [
            [function () {
            }],
            ['trim'],
            [__METHOD__],
        ];
    }

    public static function providerForNonCallable(): array
    {
        return [
            [' '],
            [INF],
            [[]],
            [new \stdClass()],
            [null],
        ];
    }
}
