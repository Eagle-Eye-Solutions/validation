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
use Respect\Validation\Exceptions\NotEmptyException;
use Respect\Validation\Rules\NotEmpty;

/**
 * @group  rule
 * @covers NotEmpty
 * @covers NotEmptyException
 */
class NotEmptyTest extends TestCase
{
    protected $object;

    protected function setUp(): void
    {
        $this->object = new NotEmpty();
    }

    /**
     * @dataProvider providerForNotEmpty
     * @throws \Exception
     */
    public function testStringNotEmpty($input): void
    {
        static::assertTrue($this->object->assert($input));
    }

    /**
     * @dataProvider providerForEmpty
     */
    public function testStringEmpty($input): void
    {
        $this->expectException(NotEmptyException::class);
        static::assertFalse($this->object->assert($input));
    }

    public static function providerForNotEmpty(): array
    {
        return [
            [1],
            [' oi'],
            [[5]],
            [[0]],
            [new \stdClass()],
        ];
    }

    public static function providerForEmpty(): array
    {
        return [
            [''],
            ['    '],
            ["\n"],
            [false],
            [null],
            [[]],
        ];
    }
}
