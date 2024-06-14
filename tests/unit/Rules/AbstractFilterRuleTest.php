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
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules\AbstractFilterRule;

class AbstractFilterRuleTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testParamIsNotString(): void
    {
        $this->expectExceptionMessage("Invalid list of additional characters to be loaded");
        $this->expectException(ComponentException::class);
        $this->getMockForAbstractClass(AbstractFilterRule::class, [1]);
    }

    /**
     * @throws Exception
     */
    public function testValidateShouldReturnTrueForValidArguments(): void
    {
        $filterRuleMock = $this->getMockForAbstractClass(AbstractFilterRule::class);
        $filterRuleMock->method('validateClean')
            ->willReturn(true);

        static::assertTrue($filterRuleMock->validate('hey'));
    }

    public function testValidateShouldReturnFalseForInvalidArguments(): void
    {
        $filterRuleMock = $this->getMockForAbstractClass(AbstractFilterRule::class);
        $filterRuleMock->method('validateClean')
            ->willReturn(true);

        static::assertFalse($filterRuleMock->validate(''));
        static::assertFalse($filterRuleMock->validate([]));
    }
}
