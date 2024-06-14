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

use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\AttributeException;
use Respect\Validation\Exceptions\IntValException;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * phpunit has an issue with mocking exceptions when in HHVM:
 * https://github.com/sebastianbergmann/phpunit-mock-objects/issues/207.
 */
class PrivateNestedValidationException extends NestedValidationException
{
}

class NestedValidationExceptionTest extends TestCase
{
    public function testGetRelatedAddedByAddRelated(): void
    {
        $composite = new AttributeException();
        $node = new IntValException();
        $composite->addRelated($node);
        static::assertCount(1, $composite->getRelated());
        static::assertContainsEquals($node, $composite->getRelated());
    }

    public function testAddingTheSameInstance(): void
    {
        $composite = new AttributeException();
        $node = new IntValException();
        $composite->addRelated($node);
        $composite->addRelated($node);
        $composite->addRelated($node);
        static::assertCount(1, $composite->getRelated());
        static::assertContainsEquals($node, $composite->getRelated());
    }

    public function testFindRelatedShouldFindCompositeExceptions(): void
    {
        $foo = new AttributeException();
        $bar = new AttributeException();
        $baz = new AttributeException();
        $bat = new AttributeException();
        $foo->configure('foo');
        $bar->configure('bar');
        $baz->configure('baz');
        $bat->configure('bat');
        $foo->addRelated($bar);
        $bar->addRelated($baz);
        $baz->addRelated($bat);
        static::assertSame($bar, $foo->findRelated('bar'));
        static::assertSame($baz, $foo->findRelated('baz'));
        static::assertSame($baz, $foo->findRelated('bar.baz'));
        static::assertSame($baz, $foo->findRelated('baz'));
        static::assertSame($bat, $foo->findRelated('bar.bat'));
        static::assertNull($foo->findRelated('none'));
        static::assertNull($foo->findRelated('bar.none'));
    }
}
