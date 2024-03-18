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
use Respect\Validation\Exceptions\AllOfException;
use Respect\Validation\Exceptions\CallbackException;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Rules\AllOf;
use Respect\Validation\Rules\Callback;
use Respect\Validation\Rules\IntVal;
use Respect\Validation\Rules\Positive;

/**
 * @group  rule
 * @covers AllOf
 * @covers AllOfException
 */
class AllOfTest extends TestCase
{
    public function testRemoveRulesShouldRemoveAllRules()
    {
        $o = new AllOf(new IntVal(), new Positive());
        $o->removeRules();
        static::assertCount(0, $o->getRules());
    }

    public function testAddRulesUsingArrayOfRules()
    {
        $o = new AllOf();
        $o->addRules(
            [
                [$x = new IntVal(), new Positive()],
            ]
        );
        static::assertTrue($o->hasRule($x));
        static::assertTrue($o->hasRule('Positive'));
    }

    public function testAddRulesUsingSpecificationArray()
    {
        $o = new AllOf();
        $o->addRules(['Between' => [1, 2]]);
        static::assertTrue($o->hasRule('Between'));
    }

    public function testValidationShouldWorkIfAllRulesReturnTrue()
    {
        $valid1 = new Callback(function () {
            return true;
        });
        $valid2 = new Callback(function () {
            return true;
        });
        $valid3 = new Callback(function () {
            return true;
        });
        $o = new AllOf($valid1, $valid2, $valid3);
        static::assertTrue($o->__invoke('any'));
        static::assertTrue($o->check('any'));
        static::assertTrue($o->assert('any'));
        static::assertTrue($o->__invoke(''));
        static::assertTrue($o->check(''));
        static::assertTrue($o->assert(''));
    }

    /**
     * @dataProvider providerStaticDummyRules
     *
     */
    public function testValidationAssertShouldFailIfAnyRuleFailsAndReturnAllExceptionsFailed($v1, $v2, $v3)
    {
        $this->expectException(AllOfException::class);
        $o = new AllOf($v1, $v2, $v3);
        static::assertFalse($o->__invoke('any'));
        static::assertFalse($o->assert('any'));
    }

    /**
     * @dataProvider providerStaticDummyRules
     *
     */
    public function testValidationCheckShouldFailIfAnyRuleFailsAndThrowTheFirstExceptionOnly($v1, $v2, $v3)
    {
        $this->expectException(CallbackException::class);
        $o = new AllOf($v1, $v2, $v3);
        static::assertFalse($o->__invoke('any'));
        static::assertFalse($o->check('any'));
    }

    /**
     * @dataProvider providerStaticDummyRules
     *
     */
    public function testValidationCheckShouldFailOnEmptyInput($v1, $v2, $v3)
    {
        $this->expectException(ValidationException::class);
        $o = new AllOf($v1, $v2, $v3);
        static::assertTrue($o->check(''));
    }

    /**
     * @dataProvider providerStaticDummyRules
     */
    public function testValidationShouldFailIfAnyRuleFails($v1, $v2, $v3): void
    {
        $o = new AllOf($v1, $v2, $v3);
        static::assertFalse($o->__invoke('any'));
    }

    public static function providerStaticDummyRules()
    {
        $theInvalidOne = new Callback(function () {
            return false;
        });
        $valid1 = new Callback(function () {
            return true;
        });
        $valid2 = new Callback(function () {
            return true;
        });

        return [
            [$theInvalidOne, $valid1, $valid2],
            [$valid2, $valid1, $theInvalidOne],
            [$valid2, $theInvalidOne, $valid1],
            [$valid1, $valid2, $theInvalidOne],
            [$valid1, $theInvalidOne, $valid2],
        ];
    }
}
