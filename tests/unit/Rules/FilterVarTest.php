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
use Respect\Validation\Rules\FilterVar;

/**
 * @group  rule
 * @covers FilterVar
 * @covers FilterVarException
 */
class FilterVarTest extends TestCase
{
    public function testShouldThrowsExceptionWhenFilterIsNotDefined()
    {
        $this->expectException(ComponentException::class);
        $this->expectExceptionMessage("Cannot validate without filter flag");
        new FilterVar();
    }

    public function testShouldThrowsExceptionWhenFilterIsNotValid()
    {
        $this->expectExceptionMessage("Cannot accept the given filter");
        $this->expectException(ComponentException::class);
        new FilterVar(FILTER_SANITIZE_EMAIL);
    }

    public function testShouldDefineFilterOnConstructor(): void
    {
        $rule = new FilterVar(FILTER_VALIDATE_REGEXP);

        $actualArguments = $rule->arguments;
        $expectedArguments = [FILTER_VALIDATE_REGEXP];

        static::assertEquals($expectedArguments, $actualArguments);
    }

    public function testShouldDefineFilterOptionsOnConstructor(): void
    {
        $rule = new FilterVar(FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);

        $actualArguments = $rule->arguments;
        $expectedArguments = [FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED];

        static::assertEquals($expectedArguments, $actualArguments);
    }

    public function testShouldUseDefineFilterToValidate(): void
    {
        $rule = new FilterVar(FILTER_VALIDATE_EMAIL);

        static::assertTrue($rule->validate('henriquemoody@users.noreply.github.com'));
    }

    public function testShouldUseDefineFilterOptionsToValidate()
    {
        $rule = new FilterVar(FILTER_VALIDATE_URL, FILTER_FLAG_QUERY_REQUIRED);

        static::assertTrue($rule->validate('http://example.com?foo=bar'));
    }
}
