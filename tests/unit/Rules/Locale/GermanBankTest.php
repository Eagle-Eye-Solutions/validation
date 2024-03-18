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
use Respect\Validation\Exceptions\Locale\GermanBankException;
use Respect\Validation\Rules\Locale\GermanBank;
use Respect\Validation\Test\Rules\LocaleTestCase;

/**
 * @group  rule
 * @covers GermanBank
 * @covers GermanBankException
 */
class GermanBankTest extends LocaleTestCase
{
    public function testShouldAcceptBAVInstanceOnConstrutor(): void
    {
        $bav = $this->getBavMock();
        $rule = new GermanBank($bav);

        $this->assertSame($bav, $rule->bav);
    }

    public function testShouldHaveAnInstanceOfBAVByDefault(): void
    {
        $rule = new GermanBank();

        $this->assertInstanceOf(BAV::class, $rule->bav);
    }

    public function testShouldUseBAVInstanceToValidate(): void
    {
        $input = '10000000';
        $bav = $this->getBavMock();
        $rule = new GermanBank($bav);

        $bav->expects(static::once())
            ->method('isValidBank')
            ->with($input)
            ->willReturn(true);

        $rule->validate($input);
    }

    public function testShouldReturnBAVInstanceResulteWhenValidating(): void
    {
        $input = '10000000';
        $bav = $this->getBavMock();
        $rule = new GermanBank($bav);

        $bav->method('isValidBank')
            ->with($input)
            ->willReturn(true);

        static::assertTrue($rule->validate($input));
    }

    public function testShouldThowsTheRightExceptionWhenChecking(): void
    {
        $this->expectExceptionMessage("\"10000000\" must be a german bank");
        $this->expectException(GermanBankException::class);
        $input = '10000000';
        $bav = $this->getBavMock();
        $rule = new GermanBank($bav);

        $bav->method('isValidBank')
            ->with($input)
            ->willReturn(false);

        $rule->check($input);
    }
}
