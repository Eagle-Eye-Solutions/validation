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
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ReadableException;
use Respect\Validation\Rules\Readable;
use SplFileInfo;
use stdClass;


#[CoversClass(Readable::class)]
#[CoversClass(ReadableException::class)]
#[Group('rule')]
class ReadableTest extends RuleTestCase
{
    public static function providerForValidInput(): array
    {
        $file = realpath(__DIR__.'/../../fixtures/valid-image.gif');
        $rule = new Readable();

        return [
            [$rule, $file],
            [$rule, new SplFileInfo($file)],
        ];
    }

    public static function providerForInvalidInput(): array
    {
        $file = realpath(__DIR__.'/../../fixtures/invalid-image.gif');
        $rule = new Readable();

        return [
            [$rule, $file],
            [$rule, new SplFileInfo($file)],
            [$rule, new stdClass()],
        ];
    }
}
