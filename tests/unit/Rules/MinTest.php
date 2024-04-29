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

use DateTime;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\MinException;
use Respect\Validation\Rules\Min;

/**
 * @group  rule
 * @covers Min
 * @covers MinException
 */
class MinTest extends TestCase
{
    /**
     * @dataProvider providerForValidMin
     */
    public function testValidMinShouldReturnTrue($minValue, $inclusive, $input): void
    {
        $min = new Min($minValue, $inclusive);
        static::assertTrue($min->__invoke($input));
        static::assertTrue($min->check($input));
        static::assertTrue($min->assert($input));
    }

    /**
     * @dataProvider providerForInvalidMin
     * @throws \Exception
     */
    public function testInvalidMinShouldThrowMinException($minValue, $inclusive, $input): void
    {
        $this->expectException(MinException::class);
        $min = new Min($minValue, $inclusive);
        static::assertFalse($min->__invoke($input));
        static::assertFalse($min->assert($input));
    }

    public static function providerForValidMin(): array
    {
        return [
            [100, false, 165.0],
            [-100, false, 200],
            [200, true, 200],
            [200, false, 300],
            ['a', true, 'a'],
            ['a', true, 'c'],
            ['yesterday', true, 'now'],

            // Samples from issue #178
            ['13-05-2014 03:16', true, '20-05-2014 03:16'],
            [new DateTime('13-05-2014 03:16'), true, new DateTime('20-05-2014 03:16')],
            ['13-05-2014 03:16', true, new DateTime('20-05-2014 03:16')],
            [new DateTime('13-05-2014 03:16'), true, '20-05-2014 03:16'],
        ];
    }

    public static function providerForInvalidMin(): array
    {
        return [
            [100, true, ''],
            [100, false, ''],
            [500, false, 300],
            [0, false, -250],
            [0, false, -50],
            [50, false, 50],
        ];
    }
}
