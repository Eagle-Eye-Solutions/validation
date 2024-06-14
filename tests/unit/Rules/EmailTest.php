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
use Respect\Validation\Exceptions\EmailException;
use Respect\Validation\Rules\Email;

/**
 * @group  rule
 * @covers Email
 * @covers EmailException
 */
class EmailTest extends TestCase
{
    /**
     * @dataProvider providerForValidEmail
     * @throws \Exception
     */
    public function testValidEmailShouldPass($validEmail): void
    {
        $validator = new Email();
        static::assertTrue($validator->__invoke($validEmail));
        static::assertTrue($validator->check($validEmail));
        static::assertTrue($validator->assert($validEmail));
    }

    /**
     * @dataProvider providerForInvalidEmail
     * @throws \Exception
     */
    public function testInvalidEmailsShouldFailValidation($invalidEmail): void
    {
        $this->expectException(EmailException::class);
        $validator = new Email();
        static::assertFalse($validator->__invoke($invalidEmail));
        static::assertFalse($validator->assert($invalidEmail));
    }

    public static function providerForValidEmail(): array
    {
        return [
            ['test@test.com'],
            ['mail+mail@gmail.com'],
            ['mail.email@e.test.com'],
            ['a@a.a'],
        ];
    }

    public static function providerForInvalidEmail(): array
    {
        return [
            [''],
            ['test@test'],
            ['test'],
            ['test@тест.рф'],
            ['@test.com'],
            ['mail@test@test.com'],
            ['test.test@'],
            ['test.@test.com'],
            ['test@.test.com'],
            ['test@test..com'],
            ['test@test.com.'],
            ['.test@test.com'],
        ];
    }
}
