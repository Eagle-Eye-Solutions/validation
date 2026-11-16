<?php

namespace Respect\Validation\Test\Rules;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\UploadedException;
use Respect\Validation\Rules\Uploaded;
use SplFileInfo;
use stdClass;

#[CoversClass(Uploaded::class)]
#[CoversClass(UploadedException::class)]
#[Group('rule')]
class UploadedTest extends RuleTestCase
{
    public const UPLOADED_FILENAME = 'uploaded.ext';

    public static function providerForValidInput(): array
    {
        $rule = new Uploaded();

        return [
            [$rule, self::UPLOADED_FILENAME],
            [$rule, new SplFileInfo(self::UPLOADED_FILENAME)],
        ];
    }

    public static function providerForInvalidInput(): array
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
            static::markTestSkipped('Extension "uopz" is required to test "Uploaded" rule');
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
