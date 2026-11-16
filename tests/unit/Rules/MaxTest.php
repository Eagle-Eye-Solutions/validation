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
use Respect\Validation\Exceptions\MaxException;
use Respect\Validation\Rules\Max;

#[CoversClass(Max::class)]
#[CoversClass(MaxException::class)]
#[Group('rule')]
class MaxTest extends TestCase
{
    #[DataProvider('providerForValidMax')]
    public function testValidMaxInputShouldReturnTrue($maxValue, $inclusive, $input)
    {
        $max = new Max($maxValue, $inclusive);
        static::assertTrue($max->validate($input));
        static::assertTrue($max->check($input));
        static::assertTrue($max->assert($input));
    }

    /**
     * @throws \Exception
     */
    #[DataProvider('providerForInvalidMax')]
    public function testInvalidMaxValueShouldThrowMaxException($maxValue, $inclusive, $input): void
    {
        $this->expectException(MaxException::class);
        $max = new Max($maxValue, $inclusive);
        static::assertFalse($max->validate($input));
        static::assertFalse($max->assert($input));
    }

    public static function providerForValidMax(): array
    {
        return [
            [200, false, ''],
            [200, false, 165.0],
            [200, false, -200],
            [200, true, 200],
            [200, false, 0],
            ['-18 years', true, '1988-09-09'],
            ['z', true, 'z'],
            ['z', false, 'y'],
            ['tomorrow', true, 'now'],
        ];
    }

    public static function providerForInvalidMax(): array
    {
        return [
            [200, false, 300],
            [200, false, 250],
            [200, false, 1500],
            [200, false, 200],
        ];
    }
}
