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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules\Vatin;
use Respect\Validation\Validatable;

#[CoversClass(Vatin::class)]
#[Group('rule')]
class VatinTest extends TestCase
{
    /**
     * @throws ComponentException
     */
    public function testAcceptCountryCodeOnConstructor(): void
    {
        $countryCode = 'PL';
        $rule = new Vatin($countryCode);

        static::assertInstanceOf(Validatable::class, $rule->getValidatable());
    }

    public function testCountryCodeIsNotSupported(): void
    {
        $this->expectExceptionMessage("There is no support for VAT identification number from \"BR\"");
        $this->expectException(ComponentException::class);
        new Vatin('BR');
    }
}
