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
use Respect\Validation\Exceptions\FactorException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Rules\Factor;

/**
 * @group  rule
 * @covers Factor
 * @covers FactorException
 *
 * @author David Meister <thedavidmeister@gmail.com>
 */
class FactorTest extends TestCase
{
    /**
     * @dataProvider providerForValidFactor
     * @throws ComponentException
     */
    public function testValidFactorShouldReturnTrue($dividend, $input): void
    {
        $min = new Factor($dividend);
        static::assertTrue($min->__invoke($input));
        static::assertTrue($min->check($input));
        static::assertTrue($min->assert($input));
    }

    /**
     * @dataProvider providerForInvalidFactor
     * @throws ComponentException
     * @throws \Exception
     */
    public function testInvalidFactorShouldThrowFactorException($dividend, $input): void
    {
        $this->expectException(FactorException::class);
        $this->expectExceptionMessage(ValidationException::stringify($input).' must be a factor of ' . $dividend);

        $min = new Factor($dividend);
        static::assertFalse($min->__invoke($input));
        static::assertFalse($min->assert($input));
    }

    /**
     * @dataProvider providerForInvalidFactorDividend
     */
    public function testInvalidDividentShouldThrowComponentException($dividend, $input): void
    {
        $this->expectException(ComponentException::class);
        $this->expectExceptionMessage('Dividend '.ValidationException::stringify($dividend) . ' must be an integer');

        new Factor($dividend);
    }

    public static function providerForValidFactor(): array
    {
        $tests = [
            // Run through the first few integers.
            [1, 1],
            [2, 1],
            [2, 2],
            [3, 1],
            [3, 3],
            [4, 1],
            [4, 2],
            [4, 4],
            [5, 1],
            [5, 5],
            [6, 1],
            [6, 2],
            [6, 3],
            [6, 6],
            // Zero as a dividend is always a pass.
            [0, 0],
            [0, 1],
            [0, mt_rand()],
        ];

        $tests = static::generateNegativeCombinations($tests);

        return static::generateStringAndFloatCombinations($tests);
    }

    public static function providerForInvalidFactor(): array
    {
        $tests = [
            // Run through the first few integers.
            [3, 2],
            [4, 3],
            [5, 2],
            [5, 3],
            [5, 4],
            // Zeros.
            [1, 0],
            [2, 0],
        ];

        $tests = static::generateNegativeCombinations($tests);

        $tests = static::generateStringAndFloatCombinations($tests);

        // Valid (but random) dividends, invalid inputs.
        $extra_tests = array_map(
            static function ($test) {
                return [mt_rand(), $test];
            },
            static::thingsThatAreNotIntegers()
        );
        return array_merge($tests, $extra_tests);
    }

    public static function providerForInvalidFactorDividend(): array
    {
        // Invalid dividends, valid (but random) inputs.
        $tests = array_map(
            static function ($test) {
                return [$test, mt_rand()];
            },
            static::thingsThatAreNotIntegers()
        );

        // Also check for an empty dividend string.
        $tests[] = ['', mt_rand()];

        return $tests;
    }

    private static function thingsThatAreNotIntegers(): array
    {
        return [
            0.5,
            1.5,
            -0.5,
            -1.5,
            PHP_INT_MAX + 1,
            // Non integer values.
            static::randomFloatBeweenZeroAndOne(),
            -static::randomFloatBeweenZeroAndOne(),
            'a',
            'foo',
            // Randomish string.
            uniqid('a', true),
            // Non-scalars.
            [],
            new \StdClass(),
            new \DateTime(),
            null,
            true,
            false,
        ];
    }

    /**
     * @return float|int
     */
    private static function randomFloatBeweenZeroAndOne()
    {
        return mt_rand(1, mt_getrandmax() - 1) / mt_getrandmax();
    }

    private static function generateNegativeCombinations($tests): array
    {
        // Negate all the dividends.
        $tests = array_merge(
            $tests,
            array_map(
                static function ($test) {
                    return [-$test[0], $test[1]];
                },
                $tests
            )
        );

        // Negate all the inputs.
        return array_merge(
            $tests,
            array_map(
                static function ($test) {
                    return [$test[0], -$test[1]];
                },
                $tests
            )
        );
    }

    /**
     * @param $tests
     * @return array
     */
    private static function generateStringAndFloatCombinations($tests): array
    {
        $base_tests = $tests;

        // Test everything again as a string.
        $tests = array_merge(
            $tests,
            array_map(
                static function ($test) {
                    return [(string) $test[0], (string) $test[1]];
                },
                $base_tests
            )
        );

        // Test everything again as a float.
        return array_merge(
            $tests,
            array_map(
                static function ($test) {
                    return [(float) $test[0], (float) $test[1]];
                },
                $base_tests
            )
        );
    }
}
