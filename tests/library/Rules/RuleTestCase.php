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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validatable;

abstract class RuleTestCase extends TestCase
{
    abstract public static function providerForValidInput(): array;

    abstract public static function providerForInvalidInput(): array;

    /**
     * The name is only used in the exception messages of the mock. It is not used as the
     * mock class name, as PHPUnit 11 does not allow the same mock class name to be declared twice.
     *
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
     * @param Validatable $validator
     * @param mixed       $input
     */
    #[DataProvider('providerForValidInput')]
    public function testShouldValidateValidInput(Validatable $validator, $input): void
    {
        static::assertTrue($validator->validate($input));
    }

    /**
     * @param Validatable $validator
     * @param mixed       $input
     */
    #[DataProvider('providerForInvalidInput')]
    public function testShouldValidateInvalidInput(Validatable $validator, $input): void
    {
        static::assertFalse($validator->validate($input));
    }
}
