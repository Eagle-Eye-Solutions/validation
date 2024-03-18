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
use Respect\Validation\Exceptions\Locale\GermanBicException;
use Respect\Validation\Rules\Locale\GermanBic;
use Respect\Validation\Test\Rules\LocaleTestCase;

/**
 * @group  rule
 * @covers GermanBic
 * @covers GermanBicException
 */
class GermanBicTest extends LocaleTestCase
{
    public function testShouldAcceptBAVInstanceOnConstrutor(): void
    {
        $bav = $this->getBavMock();
        $rule = new GermanBic($bav);

        static::assertSame($bav, $rule->bav);
    }

    public function testShouldHaveAnInstanceOfBAVByDefault(): void
    {
        $rule = new GermanBic();

        static::assertInstanceOf(BAV::class, $rule->bav);
    }

    public function testShouldUseBAVInstanceToValidate(): void
    {
        $input = '10000000';
        $bav = $this->getBavMock();
        $rule = new GermanBic($bav);

        $bav->expects(static::once())
            ->method('isValidBIC')
            ->with($input)
            ->willReturn(true);

        $rule->validate($input);
    }

    public function testShouldReturnBAVInstanceResulteWhenValidating(): void
    {
        $input = '10000000';
        $bav = $this->getBavMock();
        $rule = new GermanBic($bav);

        $bav->method('isValidBIC')
            ->with($input)
            ->willReturn(true);

        static::assertTrue($rule->validate($input));
    }

    public function testShouldThowsTheRightExceptionWhenChecking()
    {
        $this->expectExceptionMessage("\"10000000\" must be a german BIC");
        $this->expectException(GermanBicException::class);
        $input = '10000000';
        $bav = $this->getBavMock();
        $rule = new GermanBic($bav);

        $bav->method('isValidBIC')
            ->with($input)
            ->willReturn(false);

        $rule->check($input);
    }
}
