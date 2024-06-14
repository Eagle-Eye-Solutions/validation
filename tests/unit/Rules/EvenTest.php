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
use Respect\Validation\Exceptions\EvenException;
use Respect\Validation\Rules\Even;

/**
 * @group  rule
 * @covers Even
 * @covers EvenException
 */
class EvenTest extends TestCase
{
    protected $evenValidator;

    protected function setUp(): void
    {
        $this->evenValidator = new Even();
    }

    /**
     * @dataProvider providerForEven
     */
    public function testEvenNumbersShouldPass($input): void
    {
        static::assertTrue($this->evenValidator->validate($input));
        static::assertTrue($this->evenValidator->check($input));
        static::assertTrue($this->evenValidator->assert($input));
    }

    /**
     * @dataProvider providerForNotEven
     * @throws \Exception
     */
    public function testNotEvenNumbersShouldFail($input): void
    {
        $this->expectException(EvenException::class);
        static::assertFalse($this->evenValidator->validate($input));
        static::assertFalse($this->evenValidator->assert($input));
    }

    public static function providerForEven(): array
    {
        return [
            [''],
            [-2],
            [-0],
            [0],
            [32],
        ];
    }

    public static function providerForNotEven(): array
    {
        return [
            [-5],
            [-1],
            [1],
            [13],
        ];
    }
}
