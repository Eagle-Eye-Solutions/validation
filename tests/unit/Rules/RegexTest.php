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

use Respect\Validation\Rules\Regex;

/**
 * @group  rule
 * @covers Regex
 */
class RegexTest extends RuleTestCase
{
    public function providerForValidInput(): array
    {
        return [
            [new Regex('/^[a-z]+$/'), 'wpoiur'],
            [new Regex('/^[a-z]+$/i'), 'wPoiur'],
            [new Regex('/^[0-9]+$/i'), 42],
        ];
    }

    public function providerForInvalidInput(): array
    {
        return [
            [new Regex('/^w+$/'), 'w poiur'],
            [new Regex('/^w+$/'), new \stdClass()],
            [new Regex('/^w+$/'), []],
        ];
    }
}
