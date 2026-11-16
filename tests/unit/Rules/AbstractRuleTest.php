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
use Respect\Validation\Rules\AbstractRule;

class AbstractRuleTest extends TestCase
{
    public static function providerForTrueAndFalse(): array
    {
        return [
            [true],
            [false],
        ];
    }

    #[DataProvider('providerForTrueAndFalse')]
    public function testMagicMethodInvokeCallsValidateWithInput($booleanResult): void
    {
        $input = 'something';

        $abstractRuleMock = $this->getMockBuilder(AbstractRule::class)
            ->onlyMethods(['validate'])
            ->getMock();

        $abstractRuleMock->expects(static::once())
            ->method('validate')
            ->with($input)
            ->willReturn($booleanResult);

        static::assertSame($booleanResult, $abstractRuleMock($input));
    }

    /**
     * @throws \Exception
     */
    public function testAssertInvokesValidateOnSuccess(): void
    {
        $input = 'something';

        $abstractRuleMock = $this
            ->getMockBuilder(AbstractRule::class)
            ->onlyMethods(['validate', 'reportError'])
            ->getMock();

        $abstractRuleMock->expects(static::once())
            ->method('validate')
            ->with($input)
            ->willReturn(true);

        $abstractRuleMock
            ->expects(static::never())
            ->method('reportError');

        $abstractRuleMock->assert($input);
    }

    /**
     * @throws \Exception
     */
    public function testAssertInvokesValidateAndReportErrorOnFailure(): void
    {
        $this->expectException(ValidationException::class);
        $input = 'something';

        $abstractRuleMock = $this->getMockBuilder(AbstractRule::class)
            ->onlyMethods(['validate', 'reportError'])
            ->getMock();

        $abstractRuleMock->expects(static::once())
            ->method('validate')
            ->with($input)
            ->willReturn(false);

        $abstractRuleMock->expects(static::once())
            ->method('reportError')
            ->with($input)
            ->will(static::throwException(new ValidationException()));

        $abstractRuleMock->assert($input);
    }

    public function testCheckInvokesAssertToPerformTheValidationByDefault(): void
    {
        $input = 'something';

        $abstractRuleMock = $this
            ->getMockBuilder(AbstractRule::class)
            ->onlyMethods(['assert', 'validate'])
            ->getMock();

        $abstractRuleMock
            ->expects(static::once())
            ->method('assert')
            ->with($input);

        $abstractRuleMock->check($input);
    }

    public function testShouldCreateExceptionBasedOnTheCurrentClassName()
    {
        if (defined('HHVM_VERSION')) {
            $this->markTestSkipped('If you are a HHVM user, and you are in the mood, please fix it');
        }

        $exceptionMock = $this
            ->getMockBuilder(ValidationException::class)
            ->setMockClassName('MockRule1Exception')
            ->getMock();

        $abstractRuleMock = $this
            ->getMockBuilder(AbstractRule::class)
            ->setMockClassName('MockRule1')
            ->onlyMethods(['validate'])
            ->getMock();

        $exception = $abstractRuleMock->reportError('something');

        static::assertInstanceOf($exceptionMock::class, $exception);
    }

    public function testShouldUseDefinedTemplateOnCreatedException(): void
    {
        $template = 'This is my template';

        $exceptionMock = $this
            ->getMockBuilder(ValidationException::class)
            ->onlyMethods(['setTemplate'])
            ->getMock();

        $exceptionMock->expects(static::once())
            ->method('setTemplate')
            ->with($template);

        $abstractRuleMock = $this->getMockBuilder(AbstractRule::class)
            ->onlyMethods(['createException', 'validate'])
            ->getMock();

        $abstractRuleMock->expects(static::once())
            ->method('createException')
            ->willReturn($exceptionMock);

        $abstractRuleMock->setTemplate($template);
        $abstractRuleMock->reportError('something');
    }

    public function testShouldReturnTheCurrentObjectWhenDefinigTemplate(): void
    {
        $abstractRuleMock = $this->getMockBuilder(AbstractRule::class)
            ->onlyMethods(['validate'])
            ->getMock();

        static::assertSame($abstractRuleMock, $abstractRuleMock->setTemplate('whatever'));
    }

    public function testShouldReturnTheCurrentObjectWhenDefinigName(): void
    {
        $abstractRuleMock = $this->getMockBuilder(AbstractRule::class)
            ->onlyMethods(['validate'])
            ->getMock();

        static::assertSame($abstractRuleMock, $abstractRuleMock->setName('whatever'));
    }

    public function testShouldBeAbleToDefineAndRetrivedRuleName(): void
    {
        $abstractRuleMock = $this->getMockBuilder(AbstractRule::class)
            ->onlyMethods(['validate'])
            ->getMock();

        $name = 'something';

        $abstractRuleMock->setName($name);

        static::assertSame($name, $abstractRuleMock->getName());
    }
}
