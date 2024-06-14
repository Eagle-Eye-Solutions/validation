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
use Respect\Validation\Exceptions\KeySetException;
use Respect\Validation\Rules\AllOf;
use Respect\Validation\Rules\AlwaysValid;
use Respect\Validation\Rules\Key;
use Respect\Validation\Rules\KeySet;

/**
 * @group  rule
 * @covers KeySet
 * @covers KeySetException
 */
class KeySetTest extends TestCase
{
    /**
     * @throws ComponentException
     */
    public function testShouldAcceptKeyRule(): void
    {
        $key = new Key('foo', new AlwaysValid(), false);
        $keySet = new KeySet($key);

        $rules = $keySet->getRules();

        static::assertSame(current($rules), $key);
    }

    /**
     * @throws ComponentException
     */
    public function testShouldAcceptAllOfWithOneKeyRule(): void
    {
        $key = new Key('foo', new AlwaysValid(), false);
        $allOf = new AllOf($key);
        $keySet = new KeySet($allOf);

        $rules = $keySet->getRules();

        static::assertSame(current($rules), $key);
    }

    public function testShouldNotAcceptAllOfWithMoreThanOneKeyRule(): void
    {
        $this->expectExceptionMessage("AllOf rule must have only one Key rule");
        $this->expectException(ComponentException::class);
        $key1 = new Key('foo', new AlwaysValid(), false);
        $key2 = new Key('bar', new AlwaysValid(), false);
        $allOf = new AllOf($key1, $key2);

        new KeySet($allOf);
    }

    public function testShouldNotAcceptAllOfWithANonKeyRule(): void
    {
        $this->expectExceptionMessage("KeySet rule accepts only Key rules");
        $this->expectException(ComponentException::class);
        $alwaysValid = new AlwaysValid();
        $allOf = new AllOf($alwaysValid);

        new KeySet($allOf);
    }

    public function testShouldNotAcceptANonKeyRule(): void
    {
        $this->expectExceptionMessage("KeySet rule accepts only Key rules");
        $this->expectException(ComponentException::class);
        $alwaysValid = new AlwaysValid();

        new KeySet($alwaysValid);
    }

    /**
     * @throws ComponentException
     */
    public function testShouldReturnKeys(): void
    {
        $key1 = new Key('foo', new AlwaysValid(), true);
        $key2 = new Key('bar', new AlwaysValid(), false);

        $keySet = new KeySet($key1, $key2);

        static::assertEquals(['foo', 'bar'], $keySet->getKeys());
    }

    /**
     * @throws ComponentException
     */
    public function testShouldValidateKeysWhenThereAreMissingRequiredKeys(): void
    {
        $input = [
            'foo' => 42,
        ];

        $key1 = new Key('foo', new AlwaysValid(), true);
        $key2 = new Key('bar', new AlwaysValid(), true);

        $keySet = new KeySet($key1, $key2);

        static::assertFalse($keySet->validate($input));
    }

    /**
     * @throws ComponentException
     */
    public function testShouldValidateKeysWhenThereAreMissingNonRequiredKeys(): void
    {
        $input = [
            'foo' => 42,
        ];

        $key1 = new Key('foo', new AlwaysValid(), true);
        $key2 = new Key('bar', new AlwaysValid(), false);

        $keySet = new KeySet($key1, $key2);

        static::assertTrue($keySet->validate($input));
    }

    /**
     * @throws ComponentException
     */
    public function testShouldValidateKeysWhenThereAreMoreKeys(): void
    {
        $input = [
            'foo' => 42,
            'bar' => 'String',
            'baz' => false,
        ];

        $key1 = new Key('foo', new AlwaysValid(), false);
        $key2 = new Key('bar', new AlwaysValid(), false);

        $keySet = new KeySet($key1, $key2);

        static::assertFalse($keySet->validate($input));
    }

    public function testShouldValidateKeysWhenEmpty(): void
    {
        $input = [];

        $key1 = new Key('foo', new AlwaysValid(), true);
        $key2 = new Key('bar', new AlwaysValid(), true);

        $keySet = new KeySet($key1, $key2);

        static::assertFalse($keySet->validate($input));
    }

    /**
     * @throws ComponentException
     */
    public function testShouldCheckKeys(): void
    {
        $this->expectExceptionMessage("Must have keys { \"foo\", \"bar\" }");
        $this->expectException(KeySetException::class);
        $input = [];

        $key1 = new Key('foo', new AlwaysValid(), true);
        $key2 = new Key('bar', new AlwaysValid(), true);

        $keySet = new KeySet($key1, $key2);
        $keySet->check($input);
    }

    /**
     * @throws ComponentException
     */
    public function testShouldAssertKeys(): void
    {
        $this->expectExceptionMessage("Must have keys { \"foo\", \"bar\" }");
        $this->expectException(KeySetException::class);
        $input = [];

        $key1 = new Key('foo', new AlwaysValid(), true);
        $key2 = new Key('bar', new AlwaysValid(), true);

        $keySet = new KeySet($key1, $key2);
        $keySet->assert($input);
    }

    /**
     * @dataProvider providerForInvalidArguments
     */
    public function testShouldThrowExceptionInCaseArgumentIsAnythingOtherThanArray($input): void
    {
        $this->expectExceptionMessage("Must have keys { \"name\" }");
        $this->expectException(KeySetException::class);
        $keySet = new KeySet(new Key('name'));
        $keySet->assert($input);
    }

    public static function providerForInvalidArguments(): array
    {
        return [
            [''],
            [null],
            [0],
            [new \stdClass()],
        ];
    }
}
