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

    /**
     * @dataProvider providerForTrueAndFalse
     * @covers       AbstractRule::__invoke
     */
    public function testMagicMethodInvokeCallsValidateWithInput($booleanResult): void
    {
        $input = 'something';

        $abstractRuleMock = $this->getMockBuilder(AbstractRule::class)
            ->onlyMethods(['validate'])
            ->getMockForAbstractClass();

        $abstractRuleMock->expects(static::once())
            ->method('validate')
            ->with($input)
            ->willReturn($booleanResult);

        static::assertSame($booleanResult, $abstractRuleMock($input));
    }

    /**
     * @covers AbstractRule::assert
     * @throws \Exception
     */
    public function testAssertInvokesValidateOnSuccess(): void
    {
        $input = 'something';

        $abstractRuleMock = $this
            ->getMockBuilder(AbstractRule::class)
            ->onlyMethods(['validate', 'reportError'])
            ->getMockForAbstractClass();

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
     * @covers AbstractRule::assert
     * @throws \Exception
     */
    public function testAssertInvokesValidateAndReportErrorOnFailure(): void
    {
        $this->expectException(ValidationException::class);
        $input = 'something';

        $abstractRuleMock = $this->getMockBuilder(AbstractRule::class)
            ->onlyMethods(['validate', 'reportError'])
            ->getMockForAbstractClass();

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

    /**
     * @covers AbstractRule::check
     */
    public function testCheckInvokesAssertToPerformTheValidationByDefault(): void
    {
        $input = 'something';

        $abstractRuleMock = $this
            ->getMockBuilder(AbstractRule::class)
            ->onlyMethods(['assert'])
            ->getMockForAbstractClass();

        $abstractRuleMock
            ->expects(static::once())
            ->method('assert')
            ->with($input);

        $abstractRuleMock->check($input);
    }

    /**
     * @covers AbstractRule::reportError
     * @covers AbstractRule::createException
     */
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
            ->getMockForAbstractClass();

        $exception = $abstractRuleMock->reportError('something');

        static::assertInstanceOf(get_class($exceptionMock), $exception);
    }

    /**
     * @covers AbstractRule::reportError
     * @covers AbstractRule::setTemplate
     */
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
            ->onlyMethods(['createException'])
            ->getMockForAbstractClass();

        $abstractRuleMock->expects(static::once())
            ->method('createException')
            ->willReturn($exceptionMock);

        $abstractRuleMock->setTemplate($template);
        $abstractRuleMock->reportError('something');
    }

    /**
     * @covers AbstractRule::setTemplate
     */
    public function testShouldReturnTheCurrentObjectWhenDefinigTemplate(): void
    {
        $abstractRuleMock = $this->getMockBuilder(AbstractRule::class)
            ->getMockForAbstractClass();

        static::assertSame($abstractRuleMock, $abstractRuleMock->setTemplate('whatever'));
    }

    /**
     * @covers AbstractRule::setName
     */
    public function testShouldReturnTheCurrentObjectWhenDefinigName(): void
    {
        $abstractRuleMock = $this->getMockBuilder(AbstractRule::class)
            ->getMockForAbstractClass();

        static::assertSame($abstractRuleMock, $abstractRuleMock->setName('whatever'));
    }

    /**
     * @covers AbstractRule::setName
     * @covers AbstractRule::getName
     */
    public function testShouldBeAbleToDefineAndRetrivedRuleName(): void
    {
        $abstractRuleMock = $this->getMockBuilder(AbstractRule::class)
            ->getMockForAbstractClass();

        $name = 'something';

        $abstractRuleMock->setName($name);

        static::assertSame($name, $abstractRuleMock->getName());
    }
}
