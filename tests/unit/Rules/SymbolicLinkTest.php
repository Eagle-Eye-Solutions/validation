<?php

namespace Respect\Validation\Test\Rules;

use Respect\Validation\Rules\SymbolicLink;
use SplFileInfo;

/**
 * @group rule
 * @covers SymbolicLink
 */
class SymbolicLinkTest extends RuleTestCase
{
    public function providerForValidInput(): array
    {
        $sut = new SymbolicLink();

        return [
            'filename' => [$sut, 'tests/fixtures/symbolic'],
            'SplFileInfo' => [$sut, new SplFileInfo('tests/fixtures/symbolic')],
        ];
    }

    public function providerForInvalidInput(): array
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