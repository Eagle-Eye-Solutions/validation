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
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Exceptions\KeyException;
use Respect\Validation\Rules\Key;
use Respect\Validation\Rules\Length;
use Respect\Validation\Rules\NotEmpty;

/**
 * @group  rule
 * @covers Key
 * @covers KeyException
 */
class KeyTest extends TestCase
{
    public function testArrayWithPresentKeyShouldReturnTrue(): void
    {
        $validator = new Key('bar');
        $someArray = [];
        $someArray['bar'] = 'foo';
        static::assertTrue($validator->validate($someArray));
    }

    /**
     * @throws ComponentException
     */
    public function testArrayWithNumericKeyShouldReturnTrue(): void
    {
        $validator = new Key(0);
        $someArray = [];
        $someArray[0] = 'foo';
        static::assertTrue($validator->validate($someArray));
    }

    public function testEmptyInputMustReturnFalse(): void
    {
        $validator = new Key('someEmptyKey');
        $input = '';

        static::assertFalse($validator->validate($input));
    }

    /**
     * @throws \Exception
     */
    public function testEmptyInputMustNotAssert(): void
    {
        $this->expectException(KeyException::class);
        $validator = new Key('someEmptyKey');
        $validator->assert('');
    }

    public function testEmptyInputMustNotCheck(): void
    {
        $this->expectException(KeyException::class);
        $validator = new Key('someEmptyKey');
        $validator->check('');
    }

    public function testArrayWithEmptyKeyShouldReturnTrue(): void
    {
        $validator = new Key('someEmptyKey');
        $input = [];
        $input['someEmptyKey'] = '';

        static::assertTrue($validator->validate($input));
    }

    /**
     * @throws ComponentException
     */
    public function testShouldHaveTheSameReturnValueForAllValidators(): void
    {
        $rule = new Key('key', new NotEmpty());
        $input = ['key' => ''];

        try {
            $rule->assert($input);
            $this->fail('`assert()` must throws exception');
        } catch (\Exception $e) {
        }

        try {
            $rule->check($input);
            $this->fail('`check()` must throws exception');
        } catch (\Exception $e) {
        }

        static::assertFalse($rule->validate($input));
    }

    /**
     * @throws \Exception
     */
    public function testArrayWithAbsentKeyShouldThrowKeyException(): void
    {
        $this->expectException(KeyException::class);
        $validator = new Key('bar');
        $someArray = [];
        $someArray['baraaaaaa'] = 'foo';
        static::assertTrue($validator->assert($someArray));
    }

    /**
     * @throws \Exception
     */
    public function testNotArrayShouldThrowKeyException(): void
    {
        $this->expectException(KeyException::class);
        $validator = new Key('bar');
        $someArray = 123;
        static::assertFalse($validator->assert($someArray));
    }

    public function testInvalidConstructorParametern(): void
    {
        $this->expectException(ComponentException::class);
        new Key(['invalid']);
    }

    /**
     * @throws ComponentException
     */
    public function testExtraValidatorShouldValidateKey(): void
    {
        $subValidator = new Length(1, 3);
        $validator = new Key('bar', $subValidator);
        $someArray = [];
        $someArray['bar'] = 'foo';
        static::assertTrue($validator->assert($someArray));
    }

    /**
     * @throws ComponentException
     */
    public function testNotMandatoryExtraValidatorShouldPassWithAbsentKey(): void
    {
        $subValidator = new Length(1, 3);
        $validator = new Key('bar', $subValidator, false);
        $someArray = [];
        static::assertTrue($validator->validate($someArray));
    }
}
