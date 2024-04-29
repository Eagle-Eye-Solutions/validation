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
use Respect\Validation\Rules\AbstractCtypeRule;

class AbstractCtypeRuleTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCtypeFunctionReturnsTrue(): void
    {
        $ctypeRuleMock = $this->getMockForAbstractClass(AbstractCtypeRule::class);
        $ctypeRuleMock->expects(static::once())
            ->method('ctypeFunction')
            ->willReturn(true);

        static::assertTrue($ctypeRuleMock->validateClean('anything'));
    }

    public function testCTypeFunctionReturnsFalse(): void
    {
        $ctypeRuleMock = $this->getMockForAbstractClass(AbstractCtypeRule::class);
        $ctypeRuleMock->expects(static::once())
            ->method('ctypeFunction')
            ->willReturn(false);

        static::assertFalse($ctypeRuleMock->validateClean('anything'));
    }
}
