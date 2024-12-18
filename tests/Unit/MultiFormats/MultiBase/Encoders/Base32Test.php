<?php declare(strict_types = 1);

/**
 * This file is part of the ATProto Core package.
 *
 * (c) Core Branch
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace Tests\Unit\MultiFormats\MultiBase\Encoders;

use ATProto\Core\MultiFormats\Interfaces\EncoderInterface;
use ATProto\Core\MultiFormats\MultiBase\Encoders\Base32;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class Base32Test extends TestCase
{
    public function testImplementsEncoderInterface()
    {
        $this->assertInstanceOf(EncoderInterface::class, new Base32());
    }

    #[DataProvider('provideValidEncodeCases')]
    public function testEncode(string $input, string $expectedOutput)
    {
        $this->assertSame($expectedOutput, Base32::encode($input));
    }

    #[DataProvider('provideValidDecodeCases')]
    public function testDecode(string $input, string $expectedOutput)
    {
        $this->assertSame($expectedOutput, Base32::decode($input));
    }

    public function testEncodeAndDecodeAreSymmetric()
    {
        $input = 'Hello, World!';
        $encoded = Base32::encode($input);
        $decoded = Base32::decode($encoded);

        $this->assertSame($input, $decoded, 'Decoding the encoded value should return the original input.');
    }

    public function testDecodeWithInvalidCharactersThrowsException()
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Invalid Base32 encoded data.');
        Base32::decode('INVALID@CHAR!');
    }

    // Data Providers
    public static function provideValidEncodeCases(): array
    {
        return [
            ['', ''],
            ['f', 'bmy'],
            ['fo', 'bmzxq'],
            ['foo', 'bmzxw6'],
            ['foob', 'bmzxw6yq'],
            ['fooba', 'bmzxw6ytb'],
            ['foobar', 'bmzxw6ytboi'],
        ];
    }

    public static function provideValidDecodeCases(): array
    {
        return [
            ['', ''],
            ['BMY======', 'f'],
            ['BMZXQ====', 'fo'],
            ['BMZXW6===', 'foo'],
            ['BMZXW6YQ=', 'foob'],
            ['BMZXW6YTB', 'fooba'],
            ['BMZXW6YTBOI======', 'foobar'],
            ['BMY', 'f'],
            ['BMZXQ', 'fo'],
            ['BMZXW6', 'foo'],
            ['BMZXW6YQ', 'foob'],
            ['BMZXW6YTB', 'fooba'],
            ['BMZXW6YTBOI', 'foobar'],
            ['bmy======', 'f'],
            ['bmzxq====', 'fo'],
            ['bmzxw6===', 'foo'],
            ['bmzxw6yq=', 'foob'],
            ['bmzxw6ytb', 'fooba'],
            ['bmzxw6ytboi======', 'foobar'],
            ['bmy', 'f'],
            ['bmzxq', 'fo'],
            ['bmzxw6', 'foo'],
            ['bmzxw6yq', 'foob'],
            ['bmzxw6ytb', 'fooba'],
            ['bmzxw6ytboi', 'foobar'],
        ];
    }
}
