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
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validatable;

abstract class RuleTestCase extends TestCase
{
    abstract public function providerForValidInput(): array;

    abstract public function providerForInvalidInput(): array;

    /**
     * @param bool             $expectedResult
     * @param string[optional] $mockClassName
     *
     * @return Validatable
     */
    public function getRuleMock($expectedResult, $mockClassName = ''): Validatable
    {
        $ruleMocked = $this->getMockBuilder(Validatable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'assert', 'check', 'getName', 'reportError', 'setName', 'setTemplate', 'validate',
                ]
            )
            ->setMockClassName($mockClassName)
            ->getMock();

        $ruleMocked->method('validate')
            ->willReturn($expectedResult);

        if ($expectedResult) {
            $ruleMocked->method('check')
                ->willReturn($expectedResult);
            $ruleMocked->method('assert')
                ->willReturn($expectedResult);
        } else {
            $ruleMocked->method('check')
                ->willThrowException(new ValidationException('Exception for '.$mockClassName.':check() method'));
            $ruleMocked->method('assert')
                ->willThrowException(new ValidationException('Exception for '.$mockClassName.':assert() method'));
        }

        return $ruleMocked;
    }

    /**
     * @dataProvider providerForValidInput
     *
     * @param Validatable $validator
     * @param mixed       $input
     */
    public function testShouldValidateValidInput(Validatable $validator, $input): void
    {
        static::assertTrue($validator->validate($input));
    }

    /**
     * @dataProvider providerForInvalidInput
     *
     * @param Validatable $validator
     * @param mixed       $input
     */
    public function testShouldValidateInvalidInput(Validatable $validator, $input): void
    {
        static::assertFalse($validator->validate($input));
    }
}
