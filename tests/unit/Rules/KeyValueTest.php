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
use Respect\Validation\Exceptions\AllOfException;
use Respect\Validation\Exceptions\EqualsException;
use Respect\Validation\Exceptions\KeyValueException;
use Respect\Validation\Rules\KeyValue;

/**
 * @group  rule
 * @covers KeyValue
 * @covers KeyValueException
 */
class KeyValueTest extends TestCase
{
    public function testShouldDefineValuesOnConstructor(): void
    {
        $comparedKey = 'foo';
        $ruleName = 'equals';
        $baseKey = 'bar';

        $rule = new KeyValue($comparedKey, $ruleName, $baseKey);

        static::assertSame($comparedKey, $rule->comparedKey);
        static::assertSame($ruleName, $rule->ruleName);
        static::assertSame($baseKey, $rule->baseKey);
    }

    public function testShouldNotValidateWhenComparedKeyDoesNotExist()
    {
        $rule = new KeyValue('foo', 'equals', 'bar');

        static::assertFalse($rule->validate(['bar' => 42]));
    }

    public function testShouldNotValidateWhenBaseKeyDoesNotExist(): void
    {
        $rule = new KeyValue('foo', 'equals', 'bar');

        static::assertFalse($rule->validate(['foo' => true]));
    }

    public function testShouldNotValidateRuleIsNotValid(): void
    {
        $rule = new KeyValue('foo', 'probably_not_a_rule', 'bar');

        static::assertFalse($rule->validate(['foo' => true, 'bar' => false]));
    }

    public function testShouldValidateWhenDefinedValuesMatch(): void
    {
        $rule = new KeyValue('foo', 'equals', 'bar');

        static::assertTrue($rule->validate(['foo' => 42, 'bar' => 42]));
    }

    public function testShouldValidateWhenDefinedValuesDoesNotMatch()
    {
        $rule = new KeyValue('foo', 'equals', 'bar');

        static::assertFalse($rule->validate(['foo' => 43, 'bar' => 42]));
    }

    public function testShouldAssertWhenDefinedValuesMatch()
    {
        $rule = new KeyValue('foo', 'equals', 'bar');

        static::assertTrue($rule->assert(['foo' => 42, 'bar' => 42]));
    }

    public function testShouldAssertWhenDefinedValuesDoesNotMatch(): void
    {
        $this->expectException(AllOfException::class);
        $this->expectExceptionMessage("All of the required rules must pass for foo");
        $rule = new KeyValue('foo', 'equals', 'bar');
        $rule->assert(['foo' => 43, 'bar' => 42]);
    }

    public function testShouldNotAssertWhenRuleIsNotValid(): void
    {
        $this->expectExceptionMessage("\"bar\" must be valid to validate \"foo\"");
        $this->expectException(KeyValueException::class);
        $rule = new KeyValue('foo', 'probably_not_a_rule', 'bar');
        $rule->assert(['foo' => 43, 'bar' => 42]);
    }

    public function testShouldCheckWhenDefinedValuesMatch(): void
    {
        $rule = new KeyValue('foo', 'equals', 'bar');

        static::assertTrue($rule->check(['foo' => 42, 'bar' => 42]));
    }

    public function testShouldCheckWhenDefinedValuesDoesNotMatch(): void
    {
        $this->expectExceptionMessage("foo must be equals \"bar\"");
        $this->expectException(EqualsException::class);
        $rule = new KeyValue('foo', 'equals', 'bar');
        $rule->check(['foo' => 43, 'bar' => 42]);
    }
}
