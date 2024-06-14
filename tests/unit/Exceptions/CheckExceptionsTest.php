<?php

/*
 * This file is part of Respect/Validation.
 *
 * (c) Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation\Test\Exceptions;

use DirectoryIterator;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Respect\Validation\Exceptions\ValidationException;

class CheckExceptionsTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public static function provideListOfRuleNames(): array
    {
        $rulesDirectory = 'library/Rules';
        $rulesDirectoryIterator = new DirectoryIterator($rulesDirectory);
        $ruleNames = [];
        foreach ($rulesDirectoryIterator as $fileInfo) {
            if ($fileInfo->isDir()) {
                continue;
            }

            $ruleName = mb_substr($fileInfo->getBasename(), 0, -4);
            $isRuleClassFile = $fileInfo->getExtension() !== 'php';
            if ($isRuleClassFile) {
                continue;
            }

            $className = 'Respect\\Validation\\Rules\\'.$ruleName;
            $reflectionClass = new ReflectionClass($className);
            if ($reflectionClass->isAbstract() || $reflectionClass->isInterface()) {
                continue;
            }

            $ruleNames[] = [$ruleName];
        }

        return $ruleNames;
    }

    /**
     * @dataProvider provideListOfRuleNames
     */
    public function testRuleHasAnExceptionWhichHasValidApi($ruleName): void
    {
        $exceptionClass = 'Respect\\Validation\\Exceptions\\'.$ruleName.'Exception';
        static::assertTrue(
            class_exists($exceptionClass),
            sprintf('Expected exception class to exist: %s.', $ruleName)
        );

        $expectedMessage = 'Test exception message.';
        $exceptionObject = new $exceptionClass($expectedMessage);
        $this->assertInstanceOf(
            'Exception',
            $exceptionObject,
            'Every exception should extend an Exception class.'
        );
        $this->assertInstanceOf(
            ValidationException::class,
            $exceptionObject,
            'Every Respect/Validation exception must extend ValidationException.'
        );
    }
}
