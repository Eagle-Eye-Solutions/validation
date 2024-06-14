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
use Respect\Validation\Exceptions\ResourceTypeException;
use Respect\Validation\Rules\ResourceType;

/**
 * @group  rule
 * @covers ResourceType
 * @covers ResourceTypeException
 */
class ResourceTypeTest extends TestCase
{
    protected $rule;

    protected function setUp(): void
    {
        $this->rule = new ResourceType();
    }

    /**
     * @dataProvider providerForResource
     */
    public function testShouldValidateResourceNumbers($input): void
    {
        static::assertTrue($this->rule->validate($input));
    }

    /**
     * @dataProvider providerForNonResource
     */
    public function testShouldNotValidateNonResourceNumbers($input): void
    {
        static::assertFalse($this->rule->validate($input));
    }

    public function testShouldThrowResourceExceptionWhenChecking(): void
    {
        $this->expectExceptionMessage("\"Something\" must be a resource");
        $this->expectException(ResourceTypeException::class);
        $this->rule->check('Something');
    }

    public static function providerForResource(): array
    {
        return [
            [stream_context_create()],
            [tmpfile()],
        ];
    }

    public static function providerForNonResource(): array
    {
        return [
            ['String'],
            [123],
            [[]],
            [function () {
            }],
            [new \stdClass()],
            [null],
            [xml_parser_create()],
        ];
    }
}
