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
use Respect\Validation\Exceptions\TypeException;
use Respect\Validation\Rules\Type;
use stdClass;

/**
 * @group  rule
 * @covers Type
 * @covers TypeException
 */
class TypeTest extends TestCase
{
    public function testShouldDefineTypeOnConstructor(): void
    {
        $type = 'int';
        $rule = new Type($type);

        static::assertSame($type, $rule->type);
    }

    public function testShouldNotBeCaseSensitive(): void
    {
        $rule = new Type('InTeGeR');

        static::assertTrue($rule->validate(42));
    }

    public function testShouldThrowExceptionWhenTypeIsNotValid(): void
    {
        $this->expectExceptionMessage("\"whatever\" is not a valid type");
        $this->expectException(ComponentException::class);
        new Type('whatever');
    }

    /**
     * @dataProvider providerForValidType
     * @throws ComponentException
     */
    public function testShouldValidateValidTypes($type, $input): void
    {
        $rule = new Type($type);

        static::assertTrue($rule->validate($input));
    }

    /**
     * @dataProvider providerForInvalidType
     * @throws ComponentException
     */
    public function testShouldNotValidateInvalidTypes($type, $input): void
    {
        $rule = new Type($type);

        static::assertFalse($rule->validate($input));
    }

    public function testShouldThrowTypeExceptionWhenCheckingAnInvalidInput()
    {
        $this->expectException(TypeException::class);
        $this->expectExceptionMessage("\"Something\" must be \"integer\"");
        $rule = new Type('integer');
        $rule->check('Something');
    }

    public static function providerForValidType(): array
    {
        return [
            ['array', []],
            ['bool', true],
            ['boolean', false],
            ['callable', function () {
            }],
            ['double', 0.8],
            ['float', 1.0],
            ['int', 42],
            ['integer', 13],
            ['null', null],
            ['object', new stdClass()],
            ['resource', tmpfile()],
            ['string', 'Something'],
        ];
    }

    public static function providerForInvalidType(): array
    {
        return [
            ['int', '1'],
            ['bool', '1'],
        ];
    }
}
