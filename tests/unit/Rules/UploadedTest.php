<?php

namespace Respect\Validation\Test\Rules;

use PHPUnit\Framework\SkippedTestError;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Rules\Uploaded;
use SplFileInfo;
use stdClass;

/**
 * @group  rule
 * @covers Uploaded
 * @covers UploadedException
 */
class UploadedTest extends RuleTestCase
{
    public const UPLOADED_FILENAME = 'uploaded.ext';

    public function providerForValidInput(): array
    {
        $rule = new Uploaded();

        return [
            [$rule, self::UPLOADED_FILENAME],
            [$rule, new SplFileInfo(self::UPLOADED_FILENAME)],
        ];
    }

    public function providerForInvalidInput(): array
    {
        $rule = new Uploaded();

        return [
            [$rule, 'not-uploaded.ext'],
            [$rule, new SplFileInfo('not-uploaded.ext')],
            [$rule, []],
            [$rule, 1],
            [$rule, new stdClass()],
        ];
    }

    protected function setUp(): void
    {
        if (!extension_loaded('uopz')) {
            throw new SkippedTestError('Extension "uopz" is required to test "Uploaded" rule');
        }

        uopz_set_return(
            'is_uploaded_file',
            static function (string $filename): bool {
                return $filename === UploadedTest::UPLOADED_FILENAME;
            },
            true
        );
    }
}
