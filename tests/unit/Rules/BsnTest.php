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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\BsnException;
use Respect\Validation\Rules\Bsn;

#[CoversClass(Bsn::class)]
#[CoversClass(BsnException::class)]
#[Group('rule')]
class BsnTest extends TestCase
{
    /**
     * @var Bsn
     */
    private Bsn $rule;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->rule = new Bsn();
    }

    #[DataProvider('providerForBsn')]
    public function testShouldValidateBsn(string $input): void
    {
        static::assertTrue($this->rule->validate($input));
    }

    #[DataProvider('providerForInvalidBsn')]
    public function testShouldNotValidateBsn(string $input): void
    {
        static::assertFalse($this->rule->validate($input));
    }

    public static function providerForBsn(): array
    {
        return [
            ['612890053'],
            ['087880532'],
            ['386625918'],
            ['601608021'],
            ['254650703'],
            ['478063441'],
            ['478063441'],
            ['187368429'],
            ['541777348'],
            ['254283883'],
        ];
    }

    public static function providerForInvalidBsn(): array
    {
        return [
            ['1234567890'],
            ['0987654321'],
            ['13579024'],
            ['612890054'],
            ['854650703'],
            ['283958721'],
            ['231859081'],
            ['189023323'],
            ['238150912'],
            ['382409678'],
            ['38240.678'],
            ['38240a678'],
            ['abcdefghi'],
        ];
    }
}
