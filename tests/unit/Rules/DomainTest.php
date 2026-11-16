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
use Respect\Validation\Exceptions\DomainException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Rules\Domain;
use Respect\Validation\Validator as v;

#[CoversClass(Domain::class)]
#[CoversClass(DomainException::class)]
#[Group('rule')]
class DomainTest extends TestCase
{
    protected $object;

    protected function setUp(): void
    {
        $this->object = new Domain();
    }

    #[DataProvider('providerForDomain')]
    public function testValidDomainsShouldReturnTrue($input, $tldcheck = true): void
    {
        $this->object->tldCheck($tldcheck);
        static::assertTrue($this->object->__invoke($input));
        static::assertTrue($this->object->assert($input));
        static::assertTrue($this->object->check($input));
    }

    #[DataProvider('providerForNotDomain')]
    public function testNotDomain($input, $tldcheck = true)
    {
        $this->expectException(ValidationException::class);
        $this->object->tldCheck($tldcheck);
        static::assertFalse($this->object->check($input));
    }

    #[DataProvider('providerForNotDomain')]
    public function testNotDomainCheck($input, $tldcheck = true): void
    {
        $this->expectException(DomainException::class);
        $this->object->tldCheck($tldcheck);
        static::assertFalse($this->object->assert($input));
    }

    public static function providerForDomain(): array
    {
        return [
            ['111111111111domain.local', false],
            ['111111111111.domain.local', false],
            ['example.com'],
            ['xn--bcher-kva.ch'],
            ['mail.xn--bcher-kva.ch'],
            ['example-hyphen.com'],
        ];
    }

    public static function providerForNotDomain(): array
    {
        return [
            [null],
            [''],
            ['2222222domain.local'],
            ['example--invalid.com'],
            ['-example-invalid.com'],
            ['example.invalid.-com'],
            ['xn--bcher--kva.ch'],
            ['1.2.3.256'],
            ['1.2.3.4'],
        ];
    }

    #[DataProvider('providerForDomain')]
    public function testBuilder($validDomain, $checkTLD = true): void
    {
        static::assertTrue(
            v::domain($checkTLD)->validate($validDomain),
            sprintf('Domain "%s" should be valid. (Check TLD: %s)', $validDomain, var_export($checkTLD, true))
        );
    }
}
