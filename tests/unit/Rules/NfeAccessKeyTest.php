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
use Respect\Validation\Exceptions\NfeAccessKeyException;
use Respect\Validation\Rules\NfeAccessKey;

/**
 * @group  rule
 * @covers NfeAccessKey
 * @covers NfeAccessKeyException
 */
class NfeAccessKeyTest extends TestCase
{
    protected NfeAccessKey $nfeValidator;

    protected function setUp(): void
    {
        $this->nfeValidator = new NfeAccessKey();
    }

    /**
     * @dataProvider validAccessKeyProvider
     * @throws \Exception
     */
    public function testValidAccessKey($aK): void
    {
        static::assertTrue($this->nfeValidator->assert($aK));
        static::assertTrue($this->nfeValidator->__invoke($aK));
        static::assertTrue($this->nfeValidator->check($aK));
    }

    /**
     * @dataProvider invalidAccessKeyProvider
     * @throws \Exception
     */
    public function testInvalidAccessKey($aK)
    {
        $this->expectException(NfeAccessKeyException::class);
        static::assertFalse($this->nfeValidator->assert($aK));
    }

    /**
     * @dataProvider invalidAccessKeyLengthProvider
     * @throws \Exception
     */
    public function testInvalidLengthCnh($aK)
    {
        $this->expectException(NfeAccessKeyException::class);
        static::assertFalse($this->nfeValidator->assert($aK));
    }

    public static function validAccessKeyProvider(): array
    {
        return [
            ['52060433009911002506550120000007800267301615'],
        ];
    }

    public static function invalidAccessKeyProvider(): array
    {
        return [
            ['31841136830118868211870485416765268625116906'],
            ['21470801245862435081451225624565260861852679'],
            ['45644318091447671194616059650873352394885852'],
            ['17214281716057582143671174314277906696193888'],
            ['56017280182977836779696364362142515138726654'],
            ['90157126614010548506235171976891004177042525'],
            ['78457064241662300187501877048374851128754067'],
            ['39950148079977322431982386613620895568235903'],
            ['90820939577654114875253907311677136672761216'],
        ];
    }

    public static function invalidAccessKeyLengthProvider(): array
    {
        return [
            ['11145573386990252067204852181837301'],
            ['6209433147444876'],
            ['00745996227609395385255721262102'],
            ['58215798856653'],
            ['24149625439084262707824706699374326'],
            ['163907274335'],
            ['67229454773008929675906894698'],
            ['5858836670181917762140106857095788313119136'],
            ['6098412281885524361833754087461339281130'],
            ['9025299113310221'],
        ];
    }
}
