<?php

/*
 * This file is part of Respect/Validation.
 *
 * (c) Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation\Test\Rules\Locale;

use malkusch\bav\BAV;
use Respect\Validation\Exceptions\Locale\GermanBankAccountException;
use Respect\Validation\Rules\Locale\GermanBankAccount;
use Respect\Validation\Test\Rules\LocaleTestCase;

/**
 * @group  rule
 * @covers GermanBankAccount
 * @covers GermanBankAccountException
 */
class GermanBankAccountTest extends LocaleTestCase
{
    public function testShouldAcceptBankOnConstructor()
    {
        $bank = '10000000';
        $rule = new GermanBankAccount($bank);

        $this->assertSame($bank, $rule->bank);
    }

    public function testShouldAcceptBAVInstanceOnConstructor(): void
    {
        $bank = '10000000';
        $bav = $this->getBavMock();
        $rule = new GermanBankAccount($bank, $bav);

        $this->assertSame($bav, $rule->bav);
    }

    public function testShouldHaveAnInstanceOfBAVByDefault(): void
    {
        $bank = '10000000';
        $rule = new GermanBankAccount($bank);

        $this->assertInstanceOf(BAV::class, $rule->bav);
    }

    public function testShouldUseBAVInstanceToValidate(): void
    {
        $bank = '10000000';
        $input = '67067';
        $bav = $this->getBavMock();
        $rule = new GermanBankAccount($bank, $bav);

        $bav->expects($this->once())
            ->method('isValidBankAccount')
            ->with($bank, $input)
            ->willReturn(true);

        $rule->validate($input);
    }

    public function testShouldReturnBAVInstanceResulteWhenValidating(): void
    {
        $bank = '10000000';
        $input = '67067';
        $bav = $this->getBavMock();
        $rule = new GermanBankAccount($bank, $bav);

        $bav->method('isValidBankAccount')
            ->with($bank, $input)
            ->willReturn(true);

        static::assertTrue($rule->validate($input));
    }

    public function testShouldThowsTheRightExceptionWhenChecking()
    {
        $this->expectExceptionMessage("\"67067\" must be a german bank account");
        $this->expectException(GermanBankAccountException::class);
        $bank = '10000000';
        $input = '67067';
        $bav = $this->getBavMock();
        $rule = new GermanBankAccount($bank, $bav);

        $bav->expects(static::any())
            ->method('isValidBankAccount')
            ->with($bank, $input)
            ->willReturn(false);

        $rule->check($input);
    }
}
