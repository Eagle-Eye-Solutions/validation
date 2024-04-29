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
use Respect\Validation\Exceptions\AgeException;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules\Age;

/**
 * @group  rule
 * @covers Age
 * @covers AgeException
 */
class AgeTest extends TestCase
{
    public function testShouldThrowsExceptionWhenThereIsNoArgumentsOnConstructor()
    {
        $this->expectException(ComponentException::class);
        $this->expectExceptionMessage("An age interval must be provided");
        new Age();
    }

    public function testShouldThrowsExceptionWhenMinimumAgeIsLessThenMaximumAge()
    {
        $this->expectExceptionMessage("20 cannot be greater than or equals to 10");
        $this->expectException(ComponentException::class);
        new Age(20, 10);
    }

    public function testShouldThrowsExceptionWhenMinimumAgeIsEqualsToMaximumAge()
    {
        $this->expectException(ComponentException::class);
        $this->expectExceptionMessage("20 cannot be greater than or equals to 20");
        new Age(20, 20);
    }

    public static function providerForValidAge()
    {
        return [
            [18, null, date('Y-m-d', strtotime('-18 years'))],
            [18, null, date('Y-m-d', strtotime('-19 years'))],
            [18, null, new DateTime('-18 years')],
            [18, null, new DateTime('-19 years')],

            [18, 50, date('Y-m-d', strtotime('-18 years'))],
            [18, 50, date('Y-m-d', strtotime('-50 years'))],
            [18, 50, new DateTime('-18 years')],
            [18, 50, new DateTime('-50 years')],

            [null, 50, date('Y-m-d', strtotime('-49 years'))],
            [null, 50, date('Y-m-d', strtotime('-50 years'))],
            [null, 50, new DateTime('-49 years')],
            [null, 50, new DateTime('-50 years')],
        ];
    }

    /**
     * @dataProvider providerForValidAge
     * @throws ComponentException
     */
    public function testShouldValidateValidAge($minAge, $maxAge, $input): void
    {
        $rule = new Age($minAge, $maxAge);

        static::assertTrue($rule->validate($input));
    }

    public static function providerForInvalidAge()
    {
        return [
            [18, null, date('Y-m-d', strtotime('-17 years'))],
            [18, null, new DateTime('-17 years')],

            [18, 50, date('Y-m-d', strtotime('-17 years'))],
            [18, 50, date('Y-m-d', strtotime('-51 years'))],
            [18, 50, new DateTime('-17 years')],
            [18, 50, new DateTime('-51 years')],

            [null, 50, date('Y-m-d', strtotime('-51 years'))],
            [null, 50, new DateTime('-51 years')],
        ];
    }

    /**
     * @dataProvider providerForInvalidAge
     */
    public function testShouldValidateInvalidAge($minAge, $maxAge, $input): void
    {
        $rule = new Age($minAge, $maxAge);

        static::assertFalse($rule->validate($input));
    }

    /**
     * @depends testShouldValidateInvalidAge
     * @throws ComponentException
     */
    public function testShouldThrowsExceptionWhenMinimumAgeFails(): void
    {
        $this->expectExceptionMessage("\"today\" must be lower than 18 years ago");
        $this->expectException(AgeException::class);
        $rule = new Age(18);
        $rule->assert('today');
    }

    /**
     * @depends testShouldValidateInvalidAge
     */
    public function testShouldThrowsExceptionWhenMaximunAgeFails()
    {
        $this->expectExceptionMessage("\"51 years ago\" must be greater than 50 years ago");
        $this->expectException(AgeException::class);
        $rule = new Age(null, 50);
        $rule->assert('51 years ago');
    }

    /**
     * @depends testShouldValidateInvalidAge
     */
    public function testShouldThrowsExceptionWhenMinimunAndMaximunAgeFails()
    {
        $this->expectException(AgeException::class);
        $this->expectExceptionMessage("\"today\" must be between 18 and 50 years ago");
        $rule = new Age(18, 50);
        $rule->assert('today');
    }
}
