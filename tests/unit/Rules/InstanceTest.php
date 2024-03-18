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
use Respect\Validation\Exceptions\InstanceException;
use Respect\Validation\Rules\Instance;

/**
 * @group  rule
 * @covers Instance
 * @covers InstanceException
 */
class InstanceTest extends TestCase
{
    protected Instance $instanceValidator;

    protected function setUp(): void
    {
        $this->instanceValidator = new Instance('ArrayObject');
    }

    public function testInstanceValidationForEmpty(): void
    {
        static::assertFalse($this->instanceValidator->__invoke(''));
    }

    /**
     * @throws \Exception
     */
    public function testInstanceValidationAssertEmpty(): void
    {
        $this->expectException(InstanceException::class);
        $this->instanceValidator->assert('');
    }

    public function testInstanceValidationCheckEmpty(): void
    {
        $this->expectException(InstanceException::class);
        $this->instanceValidator->check('');
    }

    /**
     * @throws \Exception
     */
    public function testInstanceValidation(): void
    {
        static::assertTrue($this->instanceValidator->__invoke(new \ArrayObject()));
        static::assertTrue($this->instanceValidator->assert(new \ArrayObject()));
        static::assertTrue($this->instanceValidator->check(new \ArrayObject()));
    }

    /**
     * @throws \Exception
     */
    public function testInvalidInstances(): void
    {
        $this->expectException(InstanceException::class);
        static::assertFalse($this->instanceValidator->validate(new \stdClass()));
        static::assertFalse($this->instanceValidator->assert(new \stdClass()));
    }
}
