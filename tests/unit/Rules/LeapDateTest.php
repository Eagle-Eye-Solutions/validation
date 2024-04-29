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
use Respect\Validation\Rules\LeapDate;

/**
 * @group  rule
 * @covers LeapDate
 * @covers LeapDateException
 */
class LeapDateTest extends TestCase
{
    protected LeapDate $leapDateValidator;

    protected function setUp(): void
    {
        $this->leapDateValidator = new LeapDate('Y-m-d');
    }

    public function testValidLeapDate_with_string(): void
    {
        static::assertTrue($this->leapDateValidator->validate('1988-02-29'));
    }

    public function testValidLeapDate_with_date_time(): void
    {
        static::assertTrue($this->leapDateValidator->validate(new DateTime('1988-02-29')));
    }

    public function testInvalidLeapDateWithString(): void
    {
        static::assertFalse($this->leapDateValidator->validate('1989-02-29'));
    }

    public function testInvalidLeapDateWithDateTime(): void
    {
        static::assertFalse($this->leapDateValidator->validate(new DateTime('1989-02-29')));
    }
    public function testInvalidLeapDateInput(): void
    {
        static::assertFalse($this->leapDateValidator->validate([]));
    }
}
