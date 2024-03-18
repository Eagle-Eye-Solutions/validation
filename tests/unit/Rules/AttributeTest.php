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
use Respect\Validation\Exceptions\AttributeException;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Exceptions\LengthException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Rules\Attribute;
use Respect\Validation\Rules\Length;

class PrivClass
{
    private $bar = 'foo';
}

/**
 * @group  rule
 * @covers Attribute
 * @covers AttributeException
 */
class AttributeTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testAttributeWithNoExtraValidation(): void
    {
        $validator = new Attribute('bar');
        $obj = new \stdClass();
        $obj->bar = 'foo';
        static::assertTrue($validator->check($obj));
        static::assertTrue($validator->__invoke($obj));
    }

    public function testAbsentAttributeShouldRaiseAttributeException(): void
    {
        $this->expectException(AttributeException::class);
        $validator = new Attribute('bar');
        $obj = new \stdClass();
        $obj->baraaaaa = 'foo';
        static::assertFalse($validator->__invoke($obj));
        static::assertFalse($validator->assert($obj));
    }

    /**
     * @throws \Exception
     */
    public function testAbsentAttribute(): void
    {
        $this->expectException(ValidationException::class);
        $validator = new Attribute('bar');
        $obj = new \stdClass();
        $obj->baraaaaa = 'foo';
        static::assertFalse($validator->__invoke($obj));
        static::assertFalse($validator->check($obj));
    }

    /**
     * @dataProvider providerForInvalidAttributeNames
     */
    public function testInvalidConstructorArguments($attributeName): void
    {
        $this->expectException(ComponentException::class);
        new Attribute($attributeName);
    }

    public static function providerForInvalidAttributeNames(): array
    {
        return [
            [new \stdClass()],
            [123],
            [''],
        ];
    }

    /**
     * @throws ComponentException
     * @throws \Exception
     */
    public function testCheckExtraValidatorRulesForAttribute(): void
    {
        $subValidator = new Length(1, 3);
        $validator = new Attribute('bar', $subValidator);
        $obj = new \stdClass();
        $obj->bar = 'foo';
        static::assertTrue($validator->__invoke($obj));
        static::assertTrue($validator->check($obj));
    }

    /**
     * @throws ComponentException
     * @throws \Exception
     */
    public function testEmptyString(): void
    {
        $this->expectException(AttributeException::class);
        $subValidator = new Length(1, 3);
        $validator = new Attribute('bar', $subValidator);
        $obj = new \stdClass();
        $obj->bar = 'foo';

        static::assertFalse($validator->__invoke(''));
        $validator->assert('');
    }

    /**
     * @throws ComponentException
     */
    public function testExtraValidatorRulesForLongAttribute(): void
    {
        $subValidator = new Length(1, 3);
        $validator = new Attribute('bar', $subValidator);
        $obj = new \stdClass();
        $obj->bar = 'foo hey this has more than 3 chars';
        static::assertFalse($validator->__invoke($obj));
    }

    /**
     * @throws ComponentException
     * @throws \Exception
     */
    public function testCheckExtraValidatorRulesForLongAttribute(): void
    {
        $this->expectException(LengthException::class);
        $subValidator = new Length(1, 3);
        $validator = new Attribute('bar', $subValidator);
        $obj = new \stdClass();
        $obj->bar = 'foo hey this has more than 3 chars';
        static::assertFalse($validator->check($obj));
    }

    /**
     * @throws ComponentException
     * @throws \Exception
     */
    public function testAsertExtraValidatorRulesForAttribute(): void
    {
        $this->expectException(AttributeException::class);
        $subValidator = new Length(1, 3);
        $validator = new Attribute('bar', $subValidator);
        $obj = new \stdClass();
        $obj->bar = 'foo hey this has more than 3 chars';
        static::assertFalse($validator->assert($obj));
    }

    public function testNotMandatoryAttribute(): void
    {
        $validator = new Attribute('bar', null, false);
        $obj = new \stdClass();
        static::assertTrue($validator->__invoke($obj));
    }

    /**
     * @throws ComponentException
     */
    public function testNotMandatoryAttributeExtraValidator(): void
    {
        $subValidator = new Length(1, 3);
        $validator = new Attribute('bar', $subValidator, false);
        $obj = new \stdClass();
        static::assertTrue($validator->__invoke($obj));
    }

    /**
     * @throws ComponentException
     */
    public function testPrivateAttribute(): void
    {
        $subValidator = new Length(1, 3);
        $validator = new Attribute('bar', $subValidator);
        $obj = new PrivClass();
        static::assertTrue($validator->assert($obj));
    }

    /**
     * @throws ComponentException
     */
    public function testPrivateAttributeNotValid(): void
    {
        $subValidator = new Length(33333, 888888);
        $validator = new Attribute('bar', $subValidator);
        $obj = new PrivClass();
        static::assertFalse($validator->__invoke($obj));
    }
}
