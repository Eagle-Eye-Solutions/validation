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

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use ReflectionObject;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validatable;

class AbstractWrapperTest extends TestCase
{
    public function testShouldThrowsAnExceptionWhenWrappedValidatableIsNotDefined()
    {
        $this->expectException(ComponentException::class);
        $this->expectExceptionMessage("There is no defined validatable");
        $wrapper = $this->getMockForAbstractClass(AbstractWrapper::class);
        $wrapper->getValidatable();
    }

    private function bindValidatable($wrapper, $validatable)
    {
        $reflectionObject = new ReflectionObject($wrapper);
        $reflectionProperty = $reflectionObject->getProperty('validatable');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($wrapper, $validatable);
    }

    /**
     * @throws Exception
     * @throws ComponentException
     */
    public function testShouldReturnDefinedValidatable(): void
    {
        $validatable = $this->createMock(Validatable::class);

        $wrapper = $this->getMockForAbstractClass(AbstractWrapper::class);
        $this->bindValidatable($wrapper, $validatable);

        static::assertSame($validatable, $wrapper->getValidatable());
    }

    /**
     * @throws Exception
     */
    public function testShouldUseWrappedToValidate(): void
    {
        $input = 'Whatever';

        $validatable = $this->createMock(Validatable::class);
        $validatable->expects($this->once())
            ->method('validate')
            ->with($input)
            ->willReturn(true);

        $wrapper = $this->getMockForAbstractClass(AbstractWrapper::class);
        $this->bindValidatable($wrapper, $validatable);

        static::assertTrue($wrapper->validate($input));
    }

    /**
     * @throws Exception
     */
    public function testShouldUseWrappedToAssert(): void
    {
        $input = 'Whatever';

        $validatable = $this->createMock(Validatable::class);
        $validatable->expects(static::once())
            ->method('assert')
            ->with($input)
            ->willReturn(true);

        $wrapper = $this->getMockForAbstractClass(AbstractWrapper::class);
        $this->bindValidatable($wrapper, $validatable);

        static::assertTrue($wrapper->assert($input));
    }

    public function testShouldUseWrappedToCheck(): void
    {
        $input = 'Whatever';

        $validatable = $this->createMock(Validatable::class);
        $validatable->expects($this->once())
            ->method('check')
            ->with($input)
            ->willReturn(true);

        $wrapper = $this->getMockForAbstractClass(AbstractWrapper::class);
        $this->bindValidatable($wrapper, $validatable);

        static::assertTrue($wrapper->check($input));
    }

    /**
     * @throws Exception
     */
    public function testShouldPassNameOnToWrapped()
    {
        $name = 'Whatever';

        $validatable = $this->createMock(Validatable::class);
        $validatable->expects($this->once())
            ->method('setName')
            ->with($name)
            ->willReturn($validatable);

        $wrapper = $this->getMockForAbstractClass(AbstractWrapper::class);
        $this->bindValidatable($wrapper, $validatable);

        static::assertSame($wrapper, $wrapper->setName($name));
    }
}
