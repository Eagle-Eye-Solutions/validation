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
use Respect\Validation\Rules\LeapYear;

/**
 * @group  rule
 * @covers LeapYear
 * @covers LeapYearException
 */
class LeapYearTest extends TestCase
{
    protected $leapYearValidator;

    protected function setUp(): void
    {
        $this->leapYearValidator = new LeapYear();
    }

    public function testValidLeapDate(): void
    {
        static::assertTrue($this->leapYearValidator->__invoke('2008'));
        static::assertTrue($this->leapYearValidator->__invoke('2008-02-29'));
        static::assertTrue($this->leapYearValidator->__invoke(2008));
        static::assertTrue($this->leapYearValidator->__invoke(new DateTime('2008-02-29')));
    }

    public function testInvalidLeapDate(): void
    {
        static::assertFalse($this->leapYearValidator->__invoke(''));
        static::assertFalse($this->leapYearValidator->__invoke('2009'));
        static::assertFalse($this->leapYearValidator->__invoke('2009-02-29'));
        static::assertFalse($this->leapYearValidator->__invoke(2009));
        static::assertFalse($this->leapYearValidator->__invoke(new DateTime('2009-02-29')));
        static::assertFalse($this->leapYearValidator->__invoke([]));
    }
}
