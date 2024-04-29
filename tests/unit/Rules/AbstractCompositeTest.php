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
use Respect\Validation\Rules\AbstractComposite;
use Respect\Validation\Validatable;

class AbstractCompositeTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testAddedComposite(): void
    {
        $ruleName = 'something';

        $simpleRuleMock = $this->createMock(Validatable::class);
        $simpleRuleMock
            ->expects(static::once())
            ->method('getName')
            ->willReturn(null);
        $simpleRuleMock
            ->expects(static::once())
            ->method('setName')
            ->with($ruleName);

        $compositeRuleMock = $this->getMockBuilder(AbstractComposite::class)
            ->onlyMethods(['validate'])
            ->getMockForAbstractClass();
        $compositeRuleMock->setName($ruleName);
        $compositeRuleMock->addRule($simpleRuleMock);
    }

    /**
     * @throws Exception
     */
    public function testNameIsUpdated(): void
    {
        $ruleName1 = 'something';
        $ruleName2 = 'something else';

        $simpleRuleMock = $this->createMock(Validatable::class);

        $simpleRuleMock->expects(self::once())
            ->method('setName')
            ->willReturn($ruleName1);

        $compositeRuleMock = $this->getMockBuilder(AbstractComposite::class)
            ->onlyMethods(['validate'])
            ->getMockForAbstractClass();
        $compositeRuleMock->setName($ruleName1);

        $compositeRuleMock = $this->getMockBuilder(AbstractComposite::class)
            ->onlyMethods(['validate'])
            ->getMockForAbstractClass();
        $simpleRuleMock->expects(self::exactly(2))
            ->method('getName')
            ->willReturnOnConsecutiveCalls([null, $ruleName1]);
        $simpleRuleMock->expects(self::once())
            ->method('setName')
            ->willReturn($ruleName2);

        $compositeRuleMock->addRule($simpleRuleMock);
        $compositeRuleMock->setName($ruleName2);
    }

    /**
     * @throws Exception
     */
    public function testAlreadyHasAName(): void
    {
        $simpleRuleMock = $this->createMock(Validatable::class);
        $simpleRuleMock->method('getName')
            ->willReturn('something');
        $simpleRuleMock->expects($this->never())
            ->method('setName');

        $compositeRuleMock = $this
            ->getMockBuilder(AbstractComposite::class)
            ->onlyMethods(['validate'])
            ->getMockForAbstractClass();
        $compositeRuleMock->addRule($simpleRuleMock);
        $compositeRuleMock->setName('Whatever');
    }

    /**
     * @throws Exception
     */
    public function testItsNameIsNull(): void
    {
        $ruleName = 'something';

        $simpleRuleMock = $this->createMock(Validatable::class);
        $simpleRuleMock->method('getName')
            ->willReturn(null);
        $simpleRuleMock->expects(static::once())
            ->method('setName')
            ->with($ruleName);

        $compositeRuleMock = $this->getMockBuilder(AbstractComposite::class)
            ->onlyMethods(['validate'])
            ->getMockForAbstractClass();
        $compositeRuleMock->addRule($simpleRuleMock);
        $compositeRuleMock->setName($ruleName);
    }

    /**
     * @throws Exception
     */
    public function testItHasNoName(): void
    {
        $ruleName = 'something';

        $simpleRuleMock = $this->createMock(Validatable::class);
        $simpleRuleMock->method('getName')
            ->willReturn(null);
        $simpleRuleMock->expects(static::once())
            ->method('setName')
            ->with($ruleName);

        $compositeRuleMock = $this->getMockBuilder(AbstractComposite::class)
            ->onlyMethods(['validate'])
            ->getMockForAbstractClass();
        $compositeRuleMock->addRule($simpleRuleMock);
        $compositeRuleMock->setName($ruleName);
    }

    public function testItHasAName(): void
    {
        $ruleName = 'something';

        $simpleRuleMock = $this->createMock(Validatable::class);
        $simpleRuleMock->method('getName')
            ->willReturn($ruleName);
        $simpleRuleMock->expects(static::never())
            ->method('setName');

        $compositeRuleMock = $this->getMockBuilder(AbstractComposite::class)
            ->onlyMethods(['validate'])
            ->getMockForAbstractClass();
        $compositeRuleMock->addRule($simpleRuleMock);
        $compositeRuleMock->setName($ruleName);
    }

    /**
     * @throws Exception
     */
    public function testRemoveRules(): void
    {
        $simpleRuleMock = $this->createMock(Validatable::class);

        $compositeRuleMock = $this->getMockForAbstractClass(AbstractComposite::class);
        $compositeRuleMock->addRule($simpleRuleMock);
        $compositeRuleMock->removeRules();

        static::assertEmpty($compositeRuleMock->getRules());
    }

    /**
     * @throws Exception
     */
    public function testAddedRules(): void
    {
        $compositeRuleMock = $this->getMockForAbstractClass(AbstractComposite::class);
        $compositeRuleMock->addRule($this->createMock(Validatable::class));
        $compositeRuleMock->addRule($this->createMock(Validatable::class));
        $compositeRuleMock->addRule($this->createMock(Validatable::class));

        static::assertCount(3, $compositeRuleMock->getRules());
    }

    /**
     * @throws Exception
     */
    public function testThereIsNoRuleAppended(): void
    {
        $compositeRuleMock = $this->getMockForAbstractClass(AbstractComposite::class);

        static::assertFalse($compositeRuleMock->hasRule(''));
    }

    /**
     * @throws Exception
     */
    public function testRuleIsNotFound(): void
    {
        $oneSimpleRuleMock = $this->createMock(Validatable::class);

        $compositeRuleMock = $this->getMockForAbstractClass(AbstractComposite::class);
        $compositeRuleMock->addRule($oneSimpleRuleMock);

        $anotherSimpleRuleMock = $this->createMock(Validatable::class);

        static::assertFalse($compositeRuleMock->hasRule($anotherSimpleRuleMock));
    }

    /**
     * @throws Exception
     */
    public function testRulePassedAsStringIsNotFound(): void
    {
        $simpleRuleMock = $this->createMock(Validatable::class);

        $compositeRuleMock = $this->getMockForAbstractClass(AbstractComposite::class);
        $compositeRuleMock->addRule($simpleRuleMock);

        static::assertFalse($compositeRuleMock->hasRule('SomeRule'));
    }

    public function testTrueWhenRuleIsFound(): void
    {
        $simpleRuleMock = $this->createMock(Validatable::class);

        $compositeRuleMock = $this->getMockForAbstractClass(AbstractComposite::class);
        $compositeRuleMock->addRule($simpleRuleMock);

        static::assertTrue($compositeRuleMock->hasRule($simpleRuleMock));
    }

    /**
     * @throws Exception
     */
    public function testPassingThroughConstructor(): void
    {
        $simpleRuleMock = $this->createMock(Validatable::class);
        $anotherSimpleRuleMock = $this->createMock(Validatable::class);

        $compositeRuleMock = $this->getMockForAbstractClass(AbstractComposite::class, [
            $simpleRuleMock,
            $anotherSimpleRuleMock,
        ]);

        static::assertCount(2, $compositeRuleMock->getRules());
    }
}
