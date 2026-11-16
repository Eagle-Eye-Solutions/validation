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
use Respect\Validation\Exceptions\EachException;
use Respect\Validation\Rules\AlwaysInvalid;
use Respect\Validation\Rules\AlwaysValid;
use Respect\Validation\Rules\Each;

#[CoversClass(Each::class)]
#[CoversClass(EachException::class)]
#[Group('rule')]
class EachTest extends RuleTestCase
{
    public static function providerForValidInput(): array
    {
        $ruleNotEmpty = new Each(new AlwaysValid());
        $ruleAlphaItemIntKey = new Each(new AlwaysValid(), new AlwaysValid());
        $ruleOnlyKeyValidation = new Each(null, new AlwaysValid());

        $intStack = new \SplStack();
        $intStack->push(1);
        $intStack->push(2);
        $intStack->push(3);
        $intStack->push(4);
        $intStack->push(5);

        $stdClass = new \stdClass();
        $stdClass->name = 'Emmerson';
        $stdClass->age = 22;

        return [
            [$ruleNotEmpty, [1, 2, 3, 4, 5]],
            [$ruleNotEmpty, $intStack],
            [$ruleNotEmpty, $stdClass],
            [$ruleAlphaItemIntKey, ['a', 'b', 'c', 'd', 'e']],
            [$ruleOnlyKeyValidation, ['a', 'b', 'c', 'd', 'e']],
        ];
    }

    public static function providerForInvalidInput(): array
    {
        $rule = new Each(new AlwaysInvalid());
        $ruleOnlyKeyValidation = new Each(null, new AlwaysInvalid());

        return [
            [$rule, 123],
            [$rule, ''],
            [$rule, null],
            [$rule, false],
            [$rule, ['', 2, 3, 4, 5]],
            [$ruleOnlyKeyValidation, ['age' => 22]],
        ];
    }

    /**
     * @throws \Exception
     */
    public function testValidatorShouldPassIfEveryArrayItemPass(): void
    {
        $v = new Each($this->getRuleMock(true));
        $result = $v->check([1, 2, 3, 4, 5]);
        static::assertTrue($result);
        $result = $v->assert([1, 2, 3, 4, 5]);
        static::assertTrue($result);
    }

    /**
     * @throws \Exception
     */
    public function testValidatorShouldPassIfEveryArrayItemAndKeyPass(): void
    {
        $v = new Each($this->getRuleMock(true), $this->getRuleMock(true));
        $result = $v->check(['a', 'b', 'c', 'd', 'e']);
        static::assertTrue($result);
        $result = $v->assert(['a', 'b', 'c', 'd', 'e']);
        static::assertTrue($result);
    }

    /**
     * @throws \Exception
     */
    public function testValidatorShouldPassWithOnlyKeyValidation(): void
    {
        $v = new Each(null, $this->getRuleMock(true));
        $result = $v->check(['a', 'b', 'c', 'd', 'e']);
        static::assertTrue($result);
        $result = $v->assert(['a', 'b', 'c', 'd', 'e']);
        static::assertTrue($result);
    }

    /**
     * @throws \Exception
     */
    public function testValidatorShouldNotPassWithOnlyKeyValidation(): void
    {
        $this->expectException(EachException::class);
        $v = new Each(null, $this->getRuleMock(false));
        $v->assert(['a', 'b', 'c', 'd', 'e']);
    }

    /**
     * @throws \Exception
     */
    public function testAssertShouldFailOnInvalidItem(): void
    {
        $this->expectException(EachException::class);
        $v = new Each($this->getRuleMock(false));
        $v->assert(['a', 2, 3, 4, 5]);
    }

    /**
     * @throws \Exception
     */
    public function testAssertShouldFailWithNonIterableInput(): void
    {
        $this->expectException(EachException::class);
        $v = new Each($this->getRuleMock(false));
        $v->assert('a');
    }

    /**
     * @throws \Exception
     */
    public function testCheckShouldFailWithNonIterableInput(): void
    {
        $this->expectException(EachException::class);
        $v = new Each($this->getRuleMock(false));
        $v->check(null);
    }
}
