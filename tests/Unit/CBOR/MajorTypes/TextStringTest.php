<?php declare(strict_types = 1);

/**
 * This file is part of the ATProto Core package.
 *
 * (c) Core Branch
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace Tests\Unit\CBOR\MajorTypes;

use ATProto\Core\CBOR\MajorTypes\TextString;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TextStringTest extends TestCase
{
    #[DataProvider('provideValidCases')]
    public function testEncodeProducesCorrectCBORRepresentation(string $input, string $expectedHeader): void
    {
        $actual = bin2hex(TextString::encode($input));
        $expected = bin2hex($expectedHeader . $input);

        $this->assertSame($expected, $actual);
    }

    #[DataProvider('provideValidCases')]
    public function testDecodeExtractsOriginalStringFromCBORRepresentation(string $input, string $header): void
    {
        $encoded = $header . $input;

        $actual = TextString::decode($encoded);
        $expected = $input;

        $this->assertSame($expected, $actual);
    }

    public static function provideValidCases(): array
    {
        return [
            ["f", "\x61"],
            ["fo", "\x62"],
            ["foo", "\x63"],
            ["foob", "\x64"],
            ["fooba", "\x65"],
            ["foobar", "\x66"],
            [
                "This is a longer string. This is a longer string. This is a longer string. This is a longer string. 
                This is a longer string. This is a longer string. This is a longer string. This is a longer string. 
                This is a longer string. This is a longer string. This is a longer string. This is a longer string.",
                "\x79\x01\x4D"
            ]
        ];
    }

    public function testDecodeThrowsExceptionForInvalidMajorType(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage("Invalid CBOR TextString major type.");

        TextString::decode("\x0C");
    }

    public function testDecodeThrowsExceptionForLengthMismatch(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage("Invalid CBOR TextString length mismatch.");

        // Encoded length is 5, but actual length is 4
        TextString::decode("\x65\x66\x6F\x6F\x62");
    }

    public function testDecodeThrowsExceptionForInvalidAdditionalInformation(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage("Invalid CBOR TextString length information.");

        // Additional info 28 is invalid for text strings
        TextString::decode("\x7C\x01");
    }
}
