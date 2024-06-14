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
use Respect\Validation\Rules\AbstractRelated;
use Respect\Validation\Validatable;

class AbstractRelatedTest extends TestCase
{
    public static function providerForOperations(): array
    {
        return [
            ['validate'],
            ['check'],
            ['assert'],
        ];
    }

    /**
     * @throws Exception
     */
    public function testConstructionOfAbstractRelatedClass(): void
    {
        $validatableMock = $this->createMock(Validatable::class);
        $relatedRuleMock = $this->getMockForAbstractClass(AbstractRelated::class, ['foo', $validatableMock]);

        static::assertSame('foo', $relatedRuleMock->getName());
        static::assertEquals('foo', $relatedRuleMock->reference);
        static::assertTrue($relatedRuleMock->mandatory);
        static::assertInstanceOf(Validatable::class, $relatedRuleMock->validator);
    }

    /**
     * @dataProvider providerForOperations
     * @throws Exception
     */
    public function testReferenceValidatesItsValue($method): void
    {
        $validatableMock = $this->createMock(Validatable::class);
        $validatableMock->expects(self::once())
            ->method($method)
            ->willReturn(true);

        $relatedRuleMock = $this->getMockForAbstractClass(AbstractRelated::class, ['foo', $validatableMock]);
        $relatedRuleMock->expects(self::once())
            ->method('hasReference')
            ->willReturn(true);

        static::assertTrue($relatedRuleMock->$method('foo'));
    }

    /**
     * @throws Exception
     */
    public function testIsMandatoryAndThereIsNoReference(): void
    {
        $relatedRuleMock = $this->getMockForAbstractClass(AbstractRelated::class, ['foo']);
        $relatedRuleMock->expects(self::once())
            ->method('hasReference')
            ->willReturn(false);

        static::assertFalse($relatedRuleMock->validate('foo'));
    }

    public function testAcceptReferenceOnConstructor(): void
    {
        $reference = 'something';

        $abstractMock = $this->getMockBuilder(AbstractRelated::class)
            ->setConstructorArgs([$reference])
            ->getMock();

        static::assertSame($reference, $abstractMock->reference);
    }

    public function testShouldBeMandatoryByDefault(): void
    {
        $abstractMock = $this->getMockBuilder(AbstractRelated::class)
            ->setConstructorArgs(['something'])
            ->getMock();

        static::assertTrue($abstractMock->mandatory);
    }

    /**
     * @throws Exception
     */
    public function testShouldAcceptReferenceAndRuleOnConstructor(): void
    {
        $ruleMock = $this->createMock(Validatable::class);

        $abstractMock = $this->getMockBuilder(AbstractRelated::class)
            ->setConstructorArgs(['something', $ruleMock])
            ->getMock();

        static::assertSame($ruleMock, $abstractMock->validator);
    }

    public function testShouldDefineRuleNameAsReferenceWhenRuleDoesNotHaveAName(): void
    {
        $reference = 'something';

        $ruleMock = $this->createMock(Validatable::class);
        $ruleMock->expects(self::once())
            ->method('getName')
            ->willReturn(null);
        $ruleMock->expects(self::once())
            ->method('setName')
            ->with($reference);

        $abstractMock = $this
            ->getMockBuilder(AbstractRelated::class)
            ->setConstructorArgs(['something', $ruleMock])
            ->getMock();

        static::assertSame($ruleMock, $abstractMock->validator);
    }

    /**
     * @throws Exception
     */
    public function testShouldNotDefineRuleNameAsReferenceWhenRuleDoesHaveAName(): void
    {
        $ruleMock = $this->createMock(Validatable::class);
        $ruleMock->expects(self::once())
            ->method('getName')
            ->willReturn('something else');
        $ruleMock->expects(static::never())
            ->method('setName');

        $abstractMock = $this->getMockBuilder(AbstractRelated::class)
            ->setConstructorArgs(['something', $ruleMock])
            ->getMock();

        static::assertSame($ruleMock, $abstractMock->validator);
    }

    /**
     * @throws Exception
     */
    public function testShouldAcceptMandatoryFlagOnConstructor(): void
    {
        $mandatory = false;

        $abstractMock = $this
            ->getMockBuilder(AbstractRelated::class)
            ->setConstructorArgs(['something', $this->createMock(Validatable::class), $mandatory])
            ->getMock();

        static::assertSame($mandatory, $abstractMock->mandatory);
    }

    /**
     * @throws Exception
     */
    public function testShouldDefineChildNameWhenDefiningTheNameOfTheParent(): void
    {
        $name = 'My new name';
        $ruleMock = $this->createMock(Validatable::class);
        $ruleMock->expects(self::once())
            ->method('getName')
            ->willReturn('something else');
        $ruleMock->expects(self::once())
            ->method('setName')
            ->with($name);

        $this->getMockBuilder(AbstractRelated::class)
            ->setConstructorArgs(['something', $ruleMock])
            ->getMock();

        $ruleMock->setName($name);
    }
}
