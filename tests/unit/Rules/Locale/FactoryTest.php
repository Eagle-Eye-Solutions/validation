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

use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules\Locale\Factory;
use Respect\Validation\Test\Rules\LocaleTestCase;
use Respect\Validation\Validatable;

/**
 * @covers Factory
 */
class FactoryTest extends LocaleTestCase
{
    public function testShouldThrowExceptionWhenFailedToGetBIC(): void
    {
        $this->expectExceptionMessage("Cannot provide BIC validation for country \"XX\"");
        $this->expectException(ComponentException::class);
        $factory = new Factory();
        $factory->bic('XX');
    }

    public function testShouldReturnBICRuleAccordingToCountry(): void
    {
        $factory = new Factory();

        static::assertInstanceOf(Validatable::class, $factory->bic('DE'));
    }

    /**
     * @throws ComponentException
     */
    public function testShouldNotBeCaseSensitiveToReturnBIC(): void
    {
        $factory = new Factory();

        static::assertEquals($factory->bic('DE'), $factory->bic('de'));
    }

    public function testShouldThrowExceptionWhenFailedToGetBank(): void
    {
        $this->expectExceptionMessage("Cannot provide bank validation for country \"XX\"");
        $this->expectException(ComponentException::class);
        $factory = new Factory();
        $factory->bank('XX');
    }

    public function testShouldReturnBankRuleAccordingToCountry(): void
    {
        $factory = new Factory();

        $this->assertInstanceOf(Validatable::class, $factory->bank('DE'));
    }

    /**
     * @throws ComponentException
     */
    public function testShouldNotBeCaseSensitiveToReturnBank(): void
    {
        $factory = new Factory();

        static::assertEquals($factory->bank('DE'), $factory->bank('de'));
    }

    public function testShouldThrowExceptionWhenFailedToGetBankAccount(): void
    {
        $this->expectExceptionMessage("Cannot provide bank account validation for country \"XX\" and bank \"123\"");
        $this->expectException(ComponentException::class);
        $factory = new Factory();
        $factory->bankAccount('XX', '123');
    }

    public function testShouldReturnBankAccountRuleAccordingToCountry(): void
    {
        $factory = new Factory();

        $this->assertInstanceOf(Validatable::class, $factory->bankAccount('DE', '123'));
    }

    public function testShouldNotBeCaseSensitiveToReturnBankAccount(): void
    {
        $factory = new Factory();

        static::assertEquals($factory->bankAccount('DE', '123'), $factory->bankAccount('de', '123'));
    }
}
