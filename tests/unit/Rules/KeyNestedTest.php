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

use ArrayObject;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Exceptions\KeyNestedException;
use Respect\Validation\Rules\Equals;
use Respect\Validation\Rules\KeyNested;
use Respect\Validation\Rules\Length;

/**
 * @group  rule
 * @covers KeyNested
 * @covers KeyNestedException
 */
class KeyNestedTest extends TestCase
{
    public function testArrayWithPresentKeysWillReturnTrueForFullPathValidator(): void
    {
        $array = [
            'bar' => [
                'foo' => [
                    'baz' => 'hello world!',
                ],
                'foooo' => [
                    'boooo' => 321,
                ],
            ],
        ];

        $rule = new KeyNested('bar.foo.baz');

        static::assertTrue($rule->validate($array));
    }

    /**
     * @throws \Exception
     */
    public function testArrayWithNumericKeysWillReturnTrueForFullPathValidator(): void
    {
        $array = [
            0 => 'Zero, the hero!',
        ];

        $rule = new KeyNested(0, new Equals('Zero, the hero!'));

        static::assertTrue($rule->check($array));
    }

    public function testArrayWithPresentKeysWillReturnTrueForHalfPathValidator(): void
    {
        $array = [
            'bar' => [
                'foo' => [
                    'baz' => 'hello world!',
                ],
                'foooo' => [
                    'boooo' => 321,
                ],
            ],
        ];

        $rule = new KeyNested('bar.foo');

        static::assertTrue($rule->validate($array));
    }

    public function testObjectWithPresentPropertiesWillReturnTrueForDirtyPathValidator(): void
    {
        $object = (object) [
            'bar' => (object) [
                'foo' => (object) [
                    'baz' => 'hello world!',
                ],
                'foooo' => (object) [
                    'boooo' => 321,
                ],
            ],
        ];

        $rule = new KeyNested('bar.foooo.');

        static::assertTrue($rule->validate($object));
    }

    public function testEmptyInputMustReturnFalse(): void
    {
        $rule = new KeyNested('bar.foo.baz');

        static::assertFalse($rule->validate(''));
    }

    /**
     * @throws \Exception
     */
    public function testEmptyInputMustNotAssert(): void
    {
        $this->expectException(KeyNestedException::class);
        $rule = new KeyNested('bar.foo.baz');
        $rule->assert('');
    }

    /**
     * @throws \Exception
     */
    public function testEmptyInputMustNotCheck(): void
    {
        $this->expectException(KeyNestedException::class);
        $rule = new KeyNested('bar.foo.baz');
        $rule->check('');
    }

    public function testArrayWithEmptyKeyShouldReturnTrue()
    {
        $rule = new KeyNested('emptyKey');
        $input = ['emptyKey' => ''];

        static::assertTrue($rule->validate($input));
    }

    /**
     * @throws \Exception
     */
    public function testArrayWithAbsentKeyShouldThrowNestedKeyException(): void
    {
        $this->expectException(KeyNestedException::class);
        $validator = new KeyNested('bar.bar');
        $object = [
            'baraaaaaa' => [
                'bar' => 'foo',
            ],
        ];
        static::assertTrue($validator->assert($object));
    }

    /**
     * @throws \Exception
     */
    public function testNotArrayShouldThrowKeyException(): void
    {
        $this->expectException(KeyNestedException::class);
        $validator = new KeyNested('baz.bar');
        $object = 123;
        static::assertFalse($validator->assert($object));
    }

    /**
     * @throws ComponentException
     * @throws \Exception
     */
    public function testExtraValidatorShouldValidateKey(): void
    {
        $subValidator = new Length(3, 7);
        $validator = new KeyNested('bar.foo.baz', $subValidator);
        $object = [
            'bar' => [
                'foo' => [
                    'baz' => 'example',
                ],
            ],
        ];
        static::assertTrue($validator->assert($object));
    }

    /**
     * @throws ComponentException
     */
    public function testNotMandatoryExtraValidatorShouldPassWithAbsentKey(): void
    {
        $subValidator = new Length(1, 3);
        $validator = new KeyNested('bar.rab', $subValidator, false);
        $object = new \stdClass();
        static::assertTrue($validator->validate($object));
    }

    public function testArrayAccessWithPresentKeysWillReturnTrue(): void
    {
        $arrayAccess = [
            'bar' => [
                'foo' => [
                    'baz' => 'hello world!',
                ],
                'foooo' => [
                    'boooo' => 321,
                ],
            ],
        ];

        $rule = new KeyNested('bar.foo.baz');

        static::assertTrue($rule->validate($arrayAccess));
    }
}
