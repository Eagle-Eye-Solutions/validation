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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\EvenException;
use Respect\Validation\Rules\Even;

#[CoversClass(Even::class)]
#[CoversClass(EvenException::class)]
#[Group('rule')]
class EvenTest extends TestCase
{
    protected $evenValidator;

    protected function setUp(): void
    {
        $this->evenValidator = new Even();
    }

    #[DataProvider('providerForEven')]
    public function testEvenNumbersShouldPass($input): void
    {
        static::assertTrue($this->evenValidator->validate($input));
        static::assertTrue($this->evenValidator->check($input));
        static::assertTrue($this->evenValidator->assert($input));
    }

    /**
     * @throws \Exception
     */
    #[DataProvider('providerForNotEven')]
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
