<?php
declare(strict_types=1);
namespace Respect\Validation\Test\Rules;

use Respect\Validation\Rules\Executable;
use SplFileInfo;
use SplFileObject;

/**
 * @group  rule
 * @covers Executable
 * @covers ExecutableException
 */
class ExecutableTest extends RuleTestCase
{
    public function providerForValidInput(): array
    {
        $rule = new Executable();

        return [
            [$rule, 'tests/fixtures/executable.php'],
            [$rule, new SplFileInfo('tests/fixtures/executable.php')],
        ];
    }

    public function providerForInvalidInput(): array
    {
        $rule = new Executable();

        return [
            [$rule, 'tests/fixtures/valid-image.gif'],
            [$rule, new SplFileInfo('tests/fixtures/valid-image.jpg')],
            [$rule, new SplFileObject('tests/fixtures/valid-image.png')],
        ];
    }
}
