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
            ['f', 'MY'],
            ['fo', 'MZXQ'],
            ['foo', 'MZXW6'],
            ['foob', 'MZXW6YQ'],
            ['fooba', 'MZXW6YTB'],
            ['foobar', 'MZXW6YTBOI'],
        ];
    }

    public static function provideValidDecodeCases(): array
    {
        return [
            ['', ''],
            ['MY======', 'f'],
            ['MZXQ====', 'fo'],
            ['MZXW6===', 'foo'],
            ['MZXW6YQ=', 'foob'],
            ['MZXW6YTB', 'fooba'],
            ['MZXW6YTBOI======', 'foobar'],
        ];
    }
}
