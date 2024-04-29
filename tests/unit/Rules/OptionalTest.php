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

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules\Optional;
use Respect\Validation\Validatable;
use stdClass;

/**
 * @group  rule
 * @covers Optional
 */
class OptionalTest extends TestCase
{
    public static function providerForOptional(): array
    {
        return [
            [null],
            [''],
        ];
    }

    public static function providerForNotOptional(): array
    {
        return [
            [1],
            [[]],
            [' '],
            [0],
            ['0'],
            [0],
            ['0.0'],
            [false],
            [['']],
            [[' ']],
            [[0]],
            [['0']],
            [[false]],
            [[[''], [0]]],
            [new stdClass()],
        ];
    }

    /**
     * @throws Exception
     * @throws ComponentException
     */
    public function testShouldAcceptInstanceOfValidatobleOnConstructor(): void
    {
        $validatable = $this->createMock(Validatable::class);
        $rule = new Optional($validatable);

        static::assertSame($validatable, $rule->getValidatable());
    }

    /**
     * @dataProvider providerForOptional
     * @throws Exception
     */
    public function testShouldNotValidateRuleWhenInputIsOptional($input)
    {
        $validatable = $this->createMock(Validatable::class);
        $validatable->expects(static::never())
            ->method('validate');

        $rule = new Optional($validatable);

        static::assertTrue($rule->validate($input));
    }

    /**
     * @dataProvider providerForNotOptional
     * @throws Exception
     */
    public function testShouldValidateRuleWhenInputIsNotOptional($input): void
    {
        $validatable = $this->createMock(Validatable::class);
        $validatable->expects(static::once())
            ->method('validate')
            ->with($input)
            ->willReturn(true);

        $rule = new Optional($validatable);

        static::assertTrue($rule->validate($input));
    }

    /**
     * @throws Exception
     */
    public function testShouldNotAssertRuleWhenInputIsOptional(): void
    {
        $validatable = $this->createMock(Validatable::class);
        $validatable->expects(static::never())
            ->method('assert');

        $rule = new Optional($validatable);

        static::assertTrue($rule->assert(''));
    }

    /**
     * @throws Exception
     */
    public function testShouldAssertRuleWhenInputIsNotOptional(): void
    {
        $input = 'foo';

        $validatable = $this->createMock(Validatable::class);
        $validatable->expects(static::once())
            ->method('assert')
            ->with($input)
            ->willReturn(true);

        $rule = new Optional($validatable);

        static::assertTrue($rule->assert($input));
    }

    /**
     * @throws Exception
     */
    public function testShouldNotCheckRuleWhenInputIsOptional(): void
    {
        $validatable = $this->createMock(Validatable::class);
        $validatable->expects($this->never())
            ->method('check');

        $rule = new Optional($validatable);

        static::assertTrue($rule->check(''));
    }

    /**
     * @throws Exception
     */
    public function testShouldCheckRuleWhenInputIsNotOptional(): void
    {
        $input = 'foo';

        $validatable = $this->createMock(Validatable::class);
        $validatable->expects(static::once())
            ->method('check')
            ->with($input)
            ->willReturn(true);

        $rule = new Optional($validatable);

        static::assertTrue($rule->check($input));
    }
}
