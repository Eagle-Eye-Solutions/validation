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
use Respect\Validation\Exceptions\MinimumAgeException;
use Respect\Validation\Rules\MinimumAge;

/**
 * @group  rule
 * @covers MinimumAge
 * @covers MinimumAgeException
 */
class MininumAgeTest extends TestCase
{
    /**
     * @dataProvider providerForValidDateValidMinimumAge
     * @throws ComponentException
     * @throws \Exception
     */
    public function testValidMinimumAgeInsideBoundsShouldPass($age, $format, $input): void
    {
        $minimumAge = new MinimumAge($age, $format);
        static::assertTrue($minimumAge->__invoke($input));
        static::assertTrue($minimumAge->assert($input));
        static::assertTrue($minimumAge->check($input));
    }

    /**
     * @dataProvider providerForValidDateInvalidMinimumAge
     * @throws ComponentException
     * @throws \Exception
     */
    public function testInvalidMinimumAgeShouldThrowException($age, $format, $input): void
    {
        $this->expectException(MinimumAgeException::class);
        $minimumAge = new MinimumAge($age, $format);
        static::assertFalse($minimumAge->__invoke($input));
        static::assertFalse($minimumAge->assert($input));
    }

    /**
     * @dataProvider providerForInvalidDate
     * @throws ComponentException
     * @throws \Exception
     */
    public function testInvalidDateShouldNotPass($age, $format, $input): void
    {
        $this->expectException(MinimumAgeException::class);
        $minimumAge = new MinimumAge($age, $format);
        static::assertFalse($minimumAge->__invoke($input));
        static::assertFalse($minimumAge->assert($input));
    }

    public function testShouldNotAcceptNonIntegerAgeOnConstructor(): void
    {
        $this->expectExceptionMessage("The age must be a integer value");
        $this->expectException(ComponentException::class);
        new MinimumAge('L12');
    }

    public static function providerForValidDateValidMinimumAge(): array
    {
        return [
            [18, 'Y-m-d', ''],
            [18, 'Y-m-d', '1969-07-20'],
            [18, null, new \DateTime('1969-07-20')],
            [18, 'Y-m-d', new \DateTime('1969-07-20')],
            ['18', 'Y-m-d', '1969-07-20'],
            [18.0, 'Y-m-d', '1969-07-20'],
        ];
    }

    public static function providerForValidDateInvalidMinimumAge(): array
    {
        return [
            [18, 'Y-m-d', (new \DateTime('-16 Years'))->format('Y-m-d')],
            [18, null, new \DateTime('-16 Years')],
            [18, 'Y-m-d', new \DateTime('-16 Years')],
        ];
    }

    public static function providerForInvalidDate(): array
    {
        return [
            [18, null, 'invalid-input'],
            [18, null, new \stdClass()],
            [18, 'y-m-d', (new \DateTime('-16 Years'))->format('Y-m-d')],
        ];
    }
}
