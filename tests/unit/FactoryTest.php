<?php

/*
 * This file is part of Respect/Validation.
 *
 * (c) Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation;

use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules\Uppercase;

/**
 * @covers Factory
 */
class FactoryTest extends TestCase
{
    public function testShouldHaveRulePrefixesByDefault(): void
    {
        $factory = new Factory();

        static::assertEquals(['Respect\\Validation\\Rules\\'], $factory->getRulePrefixes());
    }

    /**
     * @dataProvider provideRulePrefixes
     */
    public function testShouldBeAbleToAppendANewPrefix($namespace, $expectedNamespace): void
    {
        $factory = new Factory();
        $factory->appendRulePrefix($namespace);

        $currentRulePrefixes = $factory->getRulePrefixes();

        $this->assertSame(
            $expectedNamespace,
            array_pop($currentRulePrefixes),
            'Appended namespace rule was not found as expected into the prefix list.'.PHP_EOL.
            sprintf(
                'Appended "%s", current list is '.PHP_EOL.'%s',
                $namespace,
                implode(PHP_EOL, $factory->getRulePrefixes())
            )
        );
    }

    /**
     * @dataProvider provideRulePrefixes
     */
    public function testShouldBeAbleToPrependANewRulePrefix($namespace, $expectedNamespace): void
    {
        $factory = new Factory();
        $factory->prependRulePrefix($namespace);

        $currentRulePrefixes = $factory->getRulePrefixes();

        static::assertContains(
            $expectedNamespace,
            $currentRulePrefixes,
        );
    }

    public static function provideRulePrefixes(): array
    {
        return [
            'Namespace with trailing separator' => [
                'namespace' => 'My\\Validation\\Rules\\',
                'expected' => 'My\\Validation\\Rules\\',
            ],
            'Namespace without trailing separator' => [
                'namespace' => 'My\\Validation\\Rules',
                'expected' => 'My\\Validation\\Rules\\',
            ],
        ];
    }

    public function testShouldCreateARuleByName(): void
    {
        $factory = new Factory();

        static::assertInstanceOf(Uppercase::class, $factory->rule('uppercase'));
    }

    public function testShouldDefineConstructorArgumentsWhenCreatingARule(): void
    {
        $factory = new Factory();
        $rule = $factory->rule('date', ['Y-m-d']);

        static::assertEquals('Y-m-d', $rule->format);
    }

    public function testShouldThrowsAnExceptionWhenRuleNameIsNotValid(): void
    {
        $this->expectExceptionMessage("\"uterere\" is not a valid rule name");
        $this->expectException(ComponentException::class);
        $factory = new Factory();
        $factory->rule('uterere');
    }

    public function testShouldThrowsAnExceptionWhenRuleIsNotInstanceOfRuleInterface(): void
    {
        $this->expectExceptionMessage("\"Respect\Validation\Exceptions\AgeException\" is not a valid respect rule");
        $this->expectException(ComponentException::class);
        $factory = new Factory();
        $factory->appendRulePrefix('Respect\\Validation\\Exceptions\\');
        $factory->rule('AgeException');
    }
}
