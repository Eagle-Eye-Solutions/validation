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
use Respect\Validation\Rules\AbstractRegexRule;

class AbstractRegexRuleTest extends TestCase
{
    public function testValidateCleanShouldReturnOneIfPatternIsFound()
    {
        $regexRuleMock = $this->getMockForAbstractClass(AbstractRegexRule::class);
        $regexRuleMock->expects($this->once())
            ->method('getPregFormat')
            ->willReturn('/^Respect$/');

        static::assertSame(1, $regexRuleMock->validateClean('Respect'));
    }

    public function testValidateCleanShouldReturnZeroIfPatternIsNotFound()
    {
        $regexRuleMock = $this->getMockForAbstractClass(AbstractRegexRule::class);
        $regexRuleMock->expects($this->once())
            ->method('getPregFormat')
            ->willReturn('/^Respect$/');

        static::assertSame(0, $regexRuleMock->validateClean('Validation'));
    }
}
