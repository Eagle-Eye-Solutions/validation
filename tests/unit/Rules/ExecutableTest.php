<?php
declare(strict_types=1);
namespace Respect\Validation\Test\Rules;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Respect\Validation\Exceptions\ExecutableException;
use Respect\Validation\Rules\Executable;
use SplFileInfo;
use SplFileObject;

#[CoversClass(Executable::class)]
#[CoversClass(ExecutableException::class)]
#[Group('rule')]
class ExecutableTest extends RuleTestCase
{
    public static function providerForValidInput(): array
    {
        $rule = new Executable();
        chmod('tests/fixtures/executable', 705);
        return [
            [$rule, 'tests/fixtures/executable'],
            [$rule, new SplFileInfo('tests/fixtures/executable')],
        ];
    }

    public static function providerForInvalidInput(): array
    {
        $rule = new Executable();

        return [
            [$rule, 'tests/fixtures/valid-image.gif'],
            [$rule, new SplFileInfo('tests/fixtures/valid-image.jpg')],
            [$rule, new SplFileObject('tests/fixtures/valid-image.png')],
        ];
    }
}
