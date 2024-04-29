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
use Respect\Validation\Rules\AbstractSearcher;

class AbstractSearcherTest extends TestCase
{
    protected $searcherRuleMock;

    protected function setUp(): void
    {
        $this->searcherRuleMock = $this->getMockForAbstractClass(AbstractSearcher::class);
    }

    public function testValidateShouldReturnTrueWhenEqualValueIsFoundInHaystack()
    {
        $this->searcherRuleMock->haystack = [1, 2, 3, 4];

        static::assertTrue($this->searcherRuleMock->validate('1'));
        static::assertTrue($this->searcherRuleMock->validate(1));
    }

    public function testValidateShouldReturnFalseWhenEqualValueIsNotFoundInHaystack()
    {
        $this->searcherRuleMock->haystack = [1, 2, 3, 4];

        static::assertFalse($this->searcherRuleMock->validate(5));
    }

    public function testValidateShouldReturnTrueWhenIdenticalValueIsFoundInHaystack()
    {
        $this->searcherRuleMock->haystack = [1, 2, 3, 4];
        $this->searcherRuleMock->compareIdentical = true;

        static::assertTrue($this->searcherRuleMock->validate(1));
        static::assertTrue($this->searcherRuleMock->validate(4));
    }

    public function testValidateShouldReturnFalseWhenIdenticalValueIsNotFoundInHaystack()
    {
        $this->searcherRuleMock->haystack = [1, 2, 3, 4];
        $this->searcherRuleMock->compareIdentical = true;

        static::assertFalse($this->searcherRuleMock->validate('1'));
        static::assertFalse($this->searcherRuleMock->validate('4'));
        static::assertFalse($this->searcherRuleMock->validate(5));
    }

    public function testValidateShouldReturnTrueWhenInputIsEmptyOrNullAndIdenticalToHaystack()
    {
        $this->searcherRuleMock->compareIdentical = true;

        static::assertTrue($this->searcherRuleMock->validate(null));

        $this->searcherRuleMock->haystack = '';

        static::assertTrue($this->searcherRuleMock->validate(''));
    }

    public function testValidateShouldReturnFalseWhenInputIsEmptyOrNullAndNotIdenticalToHaystack()
    {
        $this->searcherRuleMock->compareIdentical = true;

        static::assertFalse($this->searcherRuleMock->validate(''));

        $this->searcherRuleMock->haystack = '';

        static::assertFalse($this->searcherRuleMock->validate(null));
    }

    public function testValidateShouldReturnTrueWhenInputIsEmptyOrNullAndEqualsHaystack()
    {
        static::assertTrue($this->searcherRuleMock->validate(''));
        static::assertTrue($this->searcherRuleMock->validate(null));
    }

    public function testValidateShouldReturnFalseWhenInputIsEmptyOrNullAndNotEqualsHaystack()
    {
        $this->searcherRuleMock->haystack = 'Respect';

        static::assertFalse($this->searcherRuleMock->validate(''));
        static::assertFalse($this->searcherRuleMock->validate(null));
    }

    public function testValidateWhenHaystackIsNotArrayAndInputIsPartOfHaystack()
    {
        $this->searcherRuleMock->haystack = 'Respect';

        static::assertTrue($this->searcherRuleMock->validate('Res'));
        static::assertTrue($this->searcherRuleMock->validate('RES'));

        $this->searcherRuleMock->compareIdentical = true;

        static::assertFalse($this->searcherRuleMock->validate('RES'));
        static::assertTrue($this->searcherRuleMock->validate('Res'));
    }
}
