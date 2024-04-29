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
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\DateException;
use Respect\Validation\Rules\Date;

/**
 * @group  rule
 * @covers Date
 * @covers DateException
 */
class DateTest extends TestCase
{
    protected $dateValidator;

    protected function setUp(): void
    {
        $this->dateValidator = new Date();
    }

    public function testDateEmptyShouldNotValidate(): void
    {
        static::assertFalse($this->dateValidator->__invoke(''));
    }

    public function testDateEmptyShouldNotCheck()
    {
        $this->expectException(DateException::class);
        $this->dateValidator->check('');
    }

    /**
     * @throws \Exception
     */
    public function testDateEmptyShouldNotAssert(): void
    {
        $this->expectException(DateException::class);
        $this->dateValidator->assert('');
    }

    public function testDateWithoutFormatShouldValidate(): void
    {
        static::assertTrue($this->dateValidator->__invoke('today'));
    }

    public function testDateTimeInstancesShouldAlwaysValidate(): void
    {
        static::assertTrue($this->dateValidator->__invoke(new DateTime('today')));
    }

    public function testDateTimeImmutableInterfaceInstancesShouldAlwaysValidate(): void
    {
        if (!class_exists('DateTimeImmutable')) {
            $this->markTestSkipped('DateTimeImmutable does not exist');
        }

        static::assertTrue($this->dateValidator->validate(new DateTimeImmutable('today')));
    }

    public function testInvalidDateShouldFail(): void
    {
        static::assertFalse($this->dateValidator->__invoke('aids'));
    }
    public function testInvalidDateShouldFail_on_invalid_conversions(): void
    {
        $this->dateValidator->format = 'Y-m-d';
        static::assertFalse($this->dateValidator->__invoke('2009-12-00'));
    }

    public function testAnyObjectExceptDateTimeInstancesShouldFail(): void
    {
        static::assertFalse($this->dateValidator->__invoke(new \stdClass()));
    }

    public function testFormatsShouldValidateDateStrings(): void
    {
        $this->dateValidator = new Date('Y-m-d');
        static::assertTrue($this->dateValidator->assert('2009-09-09'));
    }

    /**
     * @throws \Exception
     */
    public function testFormatsShouldValidateDateStringsWithAnyFormats(): void
    {
        $this->dateValidator = new Date('d/m/Y');
        static::assertTrue($this->dateValidator->assert('23/05/1987'));
    }

    /**
     * @throws \Exception
     */
    public function testOnfailure()
    {
        $this->expectException(DateException::class);
        $this->dateValidator = new Date('y-m-d');
        static::assertFalse($this->dateValidator->assert('2009-09-09'));
    }

    /**
     * @throws \Exception
     */
    public function testOnFailurePHP8(): void
    {
        $this->expectException(DateException::class);
        $this->dateValidator = new Date('z');
        static::assertFalse($this->dateValidator->assert(320));
    }

    /**
     * @throws \Exception
     */
    public function testDateTimeExceptionalFormatsThatShouldBeValid(): void
    {
        $this->dateValidator = new Date('c');
        static::assertTrue($this->dateValidator->assert('2004-02-12T15:19:21+00:00'));

        $this->dateValidator = new Date('r');
        static::assertTrue($this->dateValidator->assert('Thu, 29 Dec 2005 01:02:03 +0000'));
    }

    /**
     * @param string $systemTimezone
     * @param string $input
     * @dataProvider providerForDateTimeTimezoneStrings
     * @throws \Exception
     */
    public function testDateTimeSystemTimezoneIndependent($systemTimezone, $format, $input): void
    {
        date_default_timezone_set($systemTimezone);
        $this->dateValidator = new Date($format);
        static::assertTrue($this->dateValidator->assert($input));
    }

    public static function providerForDateTimeTimezoneStrings(): array
    {
        return [
                ['UTC', 'Ym', '202302'],
                ['UTC', 'Ym', '202304'],
                ['UTC', 'Ym', '202306'],
                ['UTC', 'Ym', '202309'],
                ['UTC', 'Ym', '202311'],
                ['UTC', 'c', '2005-12-30T01:02:03+01:00'],
                ['UTC', 'c', '2004-02-12T15:19:21+00:00'],
                ['UTC', 'r', 'Thu, 29 Dec 2005 01:02:03 +0000'],
                ['Europe/Amsterdam', 'c', '2005-12-30T01:02:03+01:00'],
                ['Europe/Amsterdam', 'c', '2004-02-12T15:19:21+00:00'],
                ['Europe/Amsterdam', 'r', 'Thu, 29 Dec 2005 01:02:03 +0000'],
                ['UTC', 'U', 1464658596],
                ['UTC', 'U', 1464399539],
                ['UTC', 'g', 0],
                ['UTC', 'h', 6],
        ];
    }
}
