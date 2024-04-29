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

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Rules\AlwaysInvalid;
use Respect\Validation\Rules\When;

/**
 * @group  rule
 * @covers When
 */
class WhenTest extends RuleTestCase
{
    public function testWithoutElseRule(): void
    {
        $rule = new When($this->getRuleMock(true), $this->getRuleMock(true));

        static::assertInstanceOf(AlwaysInvalid::class, $rule->else);
    }

    public function testWithElseRule(): void
    {
        $rule = new When(
            $this->getRuleMock(true),
            $this->getRuleMock(true),
            $this->getRuleMock(true)
        );

        static::assertNotNull($rule->else);
    }

    public function testIfRuleIsValidAndTheThenRuleIsNotOnAssertMethod(): void
    {
        $this->expectExceptionMessage("Exception for ThenNotValid:assert() method");
        $this->expectException(ValidationException::class);
        $if = $this->getRuleMock(true);
        $then = $this->getRuleMock(false, 'ThenNotValid');
        $else = $this->getRuleMock(true);

        $rule = new When($if, $then, $else);
        $rule->assert('');
    }

    public function testTheIfRuleIsValidAndTheThenRuleIsNotOnCheckMethod(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Exception for ThenNotValid:check() method");
        $if = $this->getRuleMock(true);
        $then = $this->getRuleMock(false, 'ThenNotValid');
        $else = $this->getRuleMock(true);

        $rule = new When($if, $then, $else);
        $rule->check('');
    }

    public function testIfRuleIsNotValidAndTheElseRuleIsNotOnAssertMethod(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Exception for ElseNotValid:assert() method");
        $if = $this->getRuleMock(false);
        $then = $this->getRuleMock(false);
        $else = $this->getRuleMock(false, 'ElseNotValid');

        $rule = new When($if, $then, $else);
        $rule->assert('');
    }

    public function testTheIfRuleIsNotValidAndTheElseRuleIsNotOnCheckMethod(): void
    {
        $this->expectExceptionMessage("Exception for ElseNotValid:check() method");
        $this->expectException(ValidationException::class);
        $if = $this->getRuleMock(false);
        $then = $this->getRuleMock(false);
        $else = $this->getRuleMock(false, 'ElseNotValid');

        $rule = new When($if, $then, $else);
        $rule->check('');
    }

    public function providerForValidInput(): array
    {
        return [
            'int (all true)' => [
                new When($this->getRuleMock(true), $this->getRuleMock(true), $this->getRuleMock(true)),
                42,
            ],
            'bool (all true)' => [
                new When($this->getRuleMock(true), $this->getRuleMock(true), $this->getRuleMock(true)),
                true,
            ],
            'empty (all true)' => [
                new When($this->getRuleMock(true), $this->getRuleMock(true), $this->getRuleMock(true)),
                '',
            ],
            'object (all true)' => [
                new When($this->getRuleMock(true), $this->getRuleMock(true), $this->getRuleMock(true)),
                new \stdClass(),
            ],
            'empty array (all true)' => [
                new When($this->getRuleMock(true), $this->getRuleMock(true), $this->getRuleMock(true)),
                [],
            ],
            'not empty array (all true)' => [
                new When($this->getRuleMock(true), $this->getRuleMock(true), $this->getRuleMock(true)),
                ['test'],
            ],
            'when = true, then = false, else = true' => [
                new When($this->getRuleMock(true), $this->getRuleMock(true), $this->getRuleMock(false)),
                false,
            ],
        ];
    }

    public function providerForInvalidInput(): array
    {
        return [
            'when = true, then = false, else = false' => [
                new When($this->getRuleMock(true), $this->getRuleMock(false), $this->getRuleMock(false)),
                false,
            ],
            'when = true, then = false, else = true' => [
                new When($this->getRuleMock(true), $this->getRuleMock(false), $this->getRuleMock(true)),
                false,
            ],
            'when = false, then = false, else = false' => [
                new When($this->getRuleMock(false), $this->getRuleMock(false), $this->getRuleMock(false)),
                false,
            ],
        ];
    }
}
