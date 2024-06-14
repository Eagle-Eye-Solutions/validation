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
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Rules\AllOf;
use Respect\Validation\Rules\Digit;
use Respect\Validation\Rules\IntVal;
use Respect\Validation\Rules\NoneOf;
use Respect\Validation\Rules\Not;
use Respect\Validation\Rules\NoWhitespace;
use Respect\Validation\Rules\NumericVal;
use Respect\Validation\Rules\OneOf;
use Respect\Validation\Validator;

/**
 * @group  rule
 * @covers Not
 * @covers NotException
 */
class NotTest extends TestCase
{
    /**
     * @dataProvider providerForValidNot
     */
    public function testNot($v, $input): void
    {
        $not = new Not($v);
        static::assertTrue($not->assert($input));
    }

    /**
     * @dataProvider providerForInvalidNot
     *
     */
    public function testNotNotHaha($v, $input): void
    {
        $this->expectException(ValidationException::class);
        $not = new Not($v);
        static::assertFalse($not->assert($input));
    }

    /**
     * @dataProvider providerForSetName
     */
    public function testNotSetName($v): void
    {
        $not = new Not($v);
        $not->setName('Foo');

        static::assertSame('Foo', $not->getName());
        static::assertSame('Foo', $v->getName());
    }

    public static function providerForValidNot(): array
    {
        return [
            [new IntVal(), ''],
            [new IntVal(), 'aaa'],
            [new AllOf(new NoWhitespace(), new Digit()), 'as df'],
            [new AllOf(new NoWhitespace(), new Digit()), '12 34'],
            [new AllOf(new AllOf(new NoWhitespace(), new Digit())), '12 34'],
            [new AllOf(new NoneOf(new NumericVal(), new IntVal())), 13.37],
            [new NoneOf(new NumericVal(), new IntVal()), 13.37],
            [Validator::noneOf(Validator::numericVal(), Validator::intVal()), 13.37],
        ];
    }

    public static function providerForInvalidNot(): array
    {
        return [
            [new IntVal(), 123],
            [new AllOf(new OneOf(new NumericVal(), new IntVal())), 13.37],
            [new OneOf(new NumericVal(), new IntVal()), 13.37],
            [Validator::oneOf(Validator::numericVal(), Validator::intVal()), 13.37],
        ];
    }

    public static function providerForSetName(): array
    {
        return [
            [new IntVal()],
            [new AllOf(new NumericVal(), new IntVal())],
            [new Not(new Not(new IntVal()))],
            [Validator::intVal()->setName('Bar')],
            [Validator::noneOf(Validator::numericVal(), Validator::intVal())],
        ];
    }
}
