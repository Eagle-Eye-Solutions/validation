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

use PHPUnit\Framework\MockObject\Exception;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules\Bank;
use Respect\Validation\Rules\Locale\Factory;
use Respect\Validation\Rules\Locale\GermanBank;
use Respect\Validation\Validatable;

/**
 * @group  rule
 * @covers Bank
 * @covers BankException
 */
class BankTest extends LocaleTestCase
{
    /**
     * @throws Exception
     * @throws ComponentException
     */
    public function testShouldUseDefinedFactoryToCreateInternalRuleBasedOnGivenCountryCode()
    {
        $countryCode = 'XX';

        $validatable = $this->createMock(Validatable::class);
        $factory = $this->createMock(Factory::class);
        $factory->expects($this->once())
            ->method('bank')
            ->with($countryCode)
            ->willReturn($validatable);

        $rule = new Bank($countryCode, $factory);

        $this->assertSame($validatable, $rule->getValidatable());
    }

    public function testShouldUseDefaultFactoryToCreateInternalRuleBasedOnGivenCountryCodeWhenFactoryIsNotDefined()
    {
        $countryCode = 'DE';
        $rule = new Bank($countryCode);

        $this->assertInstanceOf(GermanBank::class, $rule->getValidatable());
    }
}
