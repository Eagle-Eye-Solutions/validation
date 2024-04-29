<?php

namespace Respect\Validation\Test\Rules;

use Respect\Validation\Rules\File;
use SplFileInfo;
use SplFileObject;
use stdClass;

/**
 * @group  rule
 * @covers File
 * @covers FileException
 */
class FileTest extends RuleTestCase
{
    public function providerForValidInput(): array
    {
        $sut = new File();
        $fixturesDirectory = realpath(__DIR__.'/../../fixtures/');
        return [
            'filename' => [$sut, __FILE__],
            'SplFileInfo' => [$sut, new SplFileInfo($fixturesDirectory . '/valid-image.png')],
            'SplFileObject' => [$sut, new SplFileObject($fixturesDirectory .'/invalid-image.png')],
        ];
    }


    public function providerForInvalidInput(): array
    {
        $sut = new File();

        return [
            'directory' => [$sut, __DIR__],
            'object' => [$sut, new stdClass()],
            'array' => [$sut, []],
            'invalid filename' => [$sut, 'not-a-file-at-all'],
            'integer' => [$sut, PHP_INT_MAX],
            'float' => [$sut, 1.222],
            'boolean true' => [$sut, true],
            'boolean false' => [$sut, false],
        ];
    }
}
