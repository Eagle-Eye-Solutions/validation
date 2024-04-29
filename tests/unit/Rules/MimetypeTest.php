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

use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\MimetypeException;
use Respect\Validation\Rules\Mimetype;
use SplFileInfo;

/**
 * @author Henrique Moody <henriquemoody@gmail.com>
 * @group  rule
 * @covers Mimetype
 * @covers MimetypeException
 */
class MimetypeTest extends TestCase
{
    private $filename;

    protected function setUp(): void
    {
        $this->filename = sprintf('%s/validation.txt', sys_get_temp_dir());

        file_put_contents($this->filename, 'File content');
    }

    protected function tearDown(): void
    {
        unlink($this->filename);
    }

    public function testShouldValidateMimetype(): void
    {
        $mimetype = 'plain/text';

        $fileInfoMock = $this->getMockBuilder('finfo')
            ->disableOriginalConstructor()
            ->onlyMethods(['file'])
            ->getMock();

        $fileInfoMock->expects($this->once())
            ->method('file')
            ->with($this->filename)
            ->willReturn($mimetype);

        $rule = new Mimetype($mimetype, $fileInfoMock);

        $rule->validate($this->filename);
    }

    public function testShouldValidateSplFileInfoMimetype()
    {
        $fileInfo = new SplFileInfo($this->filename);
        $mimetype = 'plain/text';

        $fileInfoMock = $this->getMockBuilder('finfo')
            ->disableOriginalConstructor()
            ->onlyMethods(['file'])
            ->getMock();

        $fileInfoMock->expects($this->once())
            ->method('file')
            ->with($fileInfo->getPathname())
            ->willReturn($mimetype);

        $rule = new Mimetype($mimetype, $fileInfoMock);

        static::assertTrue($rule->validate($fileInfo));
    }

    public function testShouldInvalidateWhenNotStringNorSplFileInfo(): void
    {
        $rule = new Mimetype('application/octet-stream');

        static::assertFalse($rule->validate([__FILE__]));
    }

    public function testShouldInvalidateWhenItIsNotAValidFile(): void
    {
        $rule = new Mimetype('application/octet-stream');

        static::assertFalse($rule->validate(__DIR__));
    }

    public function testShouldThrowMimetypeExceptionWhenCheckingValue(): void
    {
        $this->expectExceptionMessageMatches("#\".+MimetypeTest.php\" must have \"application.?/json\" mimetype#");
        $this->expectException(MimetypeException::class);
        $rule = new Mimetype('application/json');
        $rule->check(__FILE__);
    }
}
