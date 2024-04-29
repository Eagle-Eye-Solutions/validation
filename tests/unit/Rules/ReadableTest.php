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
use Respect\Validation\Rules\Readable;
use SplFileInfo;
use stdClass;


/**
 * @group  rule
 * @covers Readable
 * @covers ReadableException
 */
class ReadableTest extends RuleTestCase
{
    public function providerForValidInput(): array
    {
        $file = realpath(__DIR__.'/../../fixtures/valid-image.gif');
        $rule = new Readable();

        return [
            [$rule, $file],
            [$rule, new SplFileInfo($file)],
        ];
    }

    public function providerForInvalidInput(): array
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
