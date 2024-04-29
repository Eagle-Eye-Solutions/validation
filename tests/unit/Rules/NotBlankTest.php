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
use Respect\Validation\Exceptions\NotBlankException;
use Respect\Validation\Rules\NotBlank;
use stdClass;

/**
 * @group  rule
 * @covers NotBlank
 * @covers NotBlankException
 */
class NotBlankTest extends TestCase
{
    /**
     * @dataProvider providerForNotBlank
     */
    public function testNotBlank($input): void
    {
        $rule = new NotBlank();

        static::assertTrue($rule->validate($input));
    }

    /**
     * @dataProvider providerForBlank
     */
    public function testBlank($input): void
    {
        $rule = new NotBlank();

        static::assertFalse($rule->validate($input));
    }

    public function testFailure(): void
    {
        $this->expectExceptionMessage('0 must not be blank');
        $this->expectException(NotBlankException::class);
        $rule = new NotBlank();
        $rule->check(0);
    }

    public function testFailureAndDoesHaveAName(): void
    {
        $this->expectExceptionMessage('whatever must not be blank');
        $this->expectException(NotBlankException::class);
        $rule = new NotBlank();
        $rule->setName('whatever');
        $rule->check(0);
    }

    public static function providerForNotBlank(): array
    {
        $object = new stdClass();
        $object->foo = true;

        return [
            [1],
            [' oi'],
            [[5]],
            [[1]],
            [$object],
        ];
    }

    public static function providerForBlank(): array
    {
        return [
            [null],
            [''],
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
}
