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
            ->getMock();
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

        $namesSet = [];

        $simpleRuleMock = $this->createMock(Validatable::class);
        $simpleRuleMock->expects(self::exactly(2))
            ->method('getName')
            ->willReturnOnConsecutiveCalls(null, $ruleName1);
        $simpleRuleMock->expects(self::exactly(2))
            ->method('setName')
            ->willReturnCallback(
                static function (string $name) use (&$namesSet, $simpleRuleMock): Validatable {
                    $namesSet[] = $name;

                    return $simpleRuleMock;
                }
            );

        $compositeRuleMock = $this->getMockBuilder(AbstractComposite::class)
            ->onlyMethods(['validate'])
            ->getMock();
        $compositeRuleMock->setName($ruleName1);
        $compositeRuleMock->addRule($simpleRuleMock);
        $compositeRuleMock->setName($ruleName2);

        static::assertSame([$ruleName1, $ruleName2], $namesSet);
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
            ->getMock();
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
            ->getMock();
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
            ->getMock();
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
            ->getMock();
        $compositeRuleMock->addRule($simpleRuleMock);
        $compositeRuleMock->setName($ruleName);
    }

    /**
     * @throws Exception
     */
    public function testRemoveRules(): void
    {
        $simpleRuleMock = $this->createStub(Validatable::class);

        $compositeRuleMock = $this->getMockBuilder(AbstractComposite::class)->onlyMethods(['validate'])->getMock();
        $compositeRuleMock->addRule($simpleRuleMock);
        $compositeRuleMock->removeRules();

        static::assertEmpty($compositeRuleMock->getRules());
    }

    /**
     * @throws Exception
     */
    public function testAddedRules(): void
    {
        $compositeRuleMock = $this->getMockBuilder(AbstractComposite::class)->onlyMethods(['validate'])->getMock();
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
        $compositeRuleMock = $this->getMockBuilder(AbstractComposite::class)->onlyMethods(['validate'])->getMock();

        static::assertFalse($compositeRuleMock->hasRule(''));
    }

    /**
     * @throws Exception
     */
    public function testRuleIsNotFound(): void
    {
        $oneSimpleRuleMock = $this->createStub(Validatable::class);

        $compositeRuleMock = $this->getMockBuilder(AbstractComposite::class)->onlyMethods(['validate'])->getMock();
        $compositeRuleMock->addRule($oneSimpleRuleMock);

        $anotherSimpleRuleMock = $this->createStub(Validatable::class);

        static::assertFalse($compositeRuleMock->hasRule($anotherSimpleRuleMock));
    }

    /**
     * @throws Exception
     */
    public function testRulePassedAsStringIsNotFound(): void
    {
        $simpleRuleMock = $this->createStub(Validatable::class);

        $compositeRuleMock = $this->getMockBuilder(AbstractComposite::class)->onlyMethods(['validate'])->getMock();
        $compositeRuleMock->addRule($simpleRuleMock);

        static::assertFalse($compositeRuleMock->hasRule('SomeRule'));
    }

    public function testTrueWhenRuleIsFound(): void
    {
        $simpleRuleMock = $this->createStub(Validatable::class);

        $compositeRuleMock = $this->getMockBuilder(AbstractComposite::class)->onlyMethods(['validate'])->getMock();
        $compositeRuleMock->addRule($simpleRuleMock);

        static::assertTrue($compositeRuleMock->hasRule($simpleRuleMock));
    }

    /**
     * @throws Exception
     */
    public function testPassingThroughConstructor(): void
    {
        $simpleRuleMock = $this->createStub(Validatable::class);
        $anotherSimpleRuleMock = $this->createStub(Validatable::class);

        $compositeRuleMock = $this->getMockBuilder(AbstractComposite::class)->setConstructorArgs([
            $simpleRuleMock,
            $anotherSimpleRuleMock,
        ])->onlyMethods(['validate'])->getMock();

        static::assertCount(2, $compositeRuleMock->getRules());
    }
}
