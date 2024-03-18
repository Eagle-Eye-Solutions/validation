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
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Exceptions\VideoUrlException as VideoUrlExceptionAlias;
use Respect\Validation\Rules\VideoUrl;

/**
 * @group  rule
 * @covers VideoUrl
 * @covers VideoUrlException
 */
class VideoUrlTest extends TestCase
{
    public function testShouldThrowsAnExceptionWhenProviderIsNotValid(): void
    {
        $this->expectException(ComponentException::class);
        $this->expectExceptionMessage("\"teste\" is not a recognized video service.");
        new VideoUrl('teste');
    }

    public static function validVideoUrlProvider(): array
    {
        return [
            ['vimeo', 'https://player.vimeo.com/video/71787467'],
            ['vimeo', 'https://vimeo.com/71787467'],
            ['youtube', 'https://www.youtube.com/embed/netHLn9TScY'],
            ['youtube', 'https://www.youtube.com/watch?v=netHLn9TScY'],
            ['youtube', 'https://youtu.be/netHLn9TScY'],
            [null, 'https://player.vimeo.com/video/71787467'],
            [null, 'https://vimeo.com/71787467'],
            [null, 'https://www.youtube.com/embed/netHLn9TScY'],
            [null, 'https://www.youtube.com/watch?v=netHLn9TScY'],
            [null, 'https://youtu.be/netHLn9TScY'],
        ];
    }

    public static function invalidVideoUrlProvider(): array
    {
        return [
            ['vimeo', 'https://www.youtube.com/watch?v=netHLn9TScY'],
            ['youtube', 'https://vimeo.com/71787467'],
            [null, 'example.com'],
            [null, 'ftp://youtu.be/netHLn9TScY'],
            [null, 'https:/example.com/'],
            [null, 'https:/youtube.com/'],
            [null, 'https://vimeo'],
            [null, 'https://vimeo.com71787467'],
            [null, 'https://www.google.com'],
            [null, 'tel:+1-816-555-1212'],
            [null, 'text'],
        ];
    }

    /**
     * @dataProvider validVideoUrlProvider
     * @throws ComponentException
     */
    public function testShouldValidateVideoUrl($service, $input): void
    {
        $rule = new VideoUrl($service);

        static::assertTrue($rule->validate($input));
    }

    /**
     * @dataProvider invalidVideoUrlProvider
     * @throws ComponentException
     */
    public function testShouldInvalidateNonVideoUrl($service, $input): void
    {
        $rule = new VideoUrl($service);

        static::assertFalse($rule->validate($input));
    }

    public function testUseAProperExceptionMessageWhenVideoUrlIsNotValid(): void
    {
        $this->expectExceptionMessage("\"exemplo.com\" must be a valid video URL");
        $this->expectException(VideoUrlExceptionAlias::class);
        $rule = new VideoUrl();
        $rule->check('exemplo.com');
    }

    public function testUseAProperExceptionMessageWhenVideoUrlIsNotValidForTheDefinedProvider(): void
    {
        $this->expectExceptionMessage("\"exemplo.com\" must be a valid video URL");
        $this->expectException(VideoUrlExceptionAlias::class);
        $rule = new VideoUrl('YouTube');
        $rule->check('exemplo.com');
    }
}
