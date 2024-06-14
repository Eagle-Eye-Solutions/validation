<?php

namespace Respect\Validation\Test\Rules;

use Respect\Validation\Rules\Writable;
use SplFileInfo;
use SplFileObject;
use stdClass;

class WritableTest extends RuleTestCase
{
    public function providerForValidInput(): array
    {
        $sut = new Writable();
        $directory = 'tests/fixtures/';
        $filename = 'tests/fixtures/valid-image.png';

        chmod($filename, 0644);
        chmod($directory, 0755);

        return [
            'writable file' => [$sut, $filename],
            'writable directory' => [$sut, $directory],
            'writable SplFileInfo file' => [$sut, new SplFileInfo($filename)],
            'writable SplFileObject file' => [$sut, new SplFileObject($filename)],
        ];
    }

    public function providerForInvalidInput(): array
    {
        $rule = new Writable();
        $filename =  'tests/fixtures/non-writable.txt';

        chmod($filename, 0555);

        return [
            'unwritable filename' => [$rule, $filename],
            'unwritable SplFileInfo file' => [$rule, new SplFileInfo($filename)],
            'unwritable SplFileObject file' => [$rule, new SplFileObject($filename)],
            'invalid filename' => [$rule, '/path/of/a/valid/writable/file.txt'],
            'empty string' => [$rule, ''],
            'boolean true' => [$rule, true],
            'boolean false' => [$rule, false],
            'integer' => [$rule, 123456],
            'float' => [$rule, 1.1111],
            'instance of stdClass' => [$rule, new stdClass()],
            'array' => [$rule, []],
        ];
    }
}