<?php

namespace Respect\Validation\Test\Rules;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Respect\Validation\Rules\SymbolicLink;
use SplFileInfo;

#[CoversClass(SymbolicLink::class)]
#[Group('rule')]
class SymbolicLinkTest extends RuleTestCase
{
    public static function providerForValidInput(): array
    {
        $sut = new SymbolicLink();

        return [
            'filename' => [$sut, 'tests/fixtures/symbolic'],
            'SplFileInfo' => [$sut, new SplFileInfo('tests/fixtures/symbolic')],
        ];
    }

    public static function providerForInvalidInput(): array
    {
        $sut = new SymbolicLink();

        return [
            'no existing filename' => [$sut, 'tests/fixtures/non-existing-symbolic-link'],
            'no existing SplFileInfo' => [$sut, new SplFileInfo('tests/fixtures/non-existing-symbolic-link')],
            'bool true' => [$sut, true],
            'bool false' => [$sut, false],
            'empty string' => [$sut, ''],
            'array' => [$sut, []],
            'resource' => [$sut, tmpfile()],
        ];
    }
}