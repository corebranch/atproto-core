<?php

namespace Tests\Unit\CBOR\MajorTypes;

use ATProto\Core\CBOR\MajorTypes\ByteString;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ByteStringTest extends TestCase
{
    #[DataProvider('provideValidCases')]
    public function testValidate(string $input, string $header): void
    {
        $this->assertTrue(ByteString::validate($header . $input));
    }

    public static function provideValidCases(): array
    {
        return [
            ['f', "\x41"],
            ['fo', "\x42"],
            ['foo', "\x43"],
            ['foob', "\x44"],
            ['fooba', "\x45"],
            ['foobar', "\x46"],
            [
                str_repeat("This is a longer string.", 4),
                "\x58\x60"
            ],
            [
                str_repeat("This is a longer string.", 12),
                "\x59\x01\x20"
            ],
            [
                str_repeat("This is a longer string.", 2731),
                "\x5A\x00\x01\x00\x08"
            ],
        ];
    }

    #[DataProvider('provideValidCases')]
    public function testEncode(string $input, string $header): void
    {
        $expected = $header . $input;
        $actual = ByteString::encode($input);

        $this->assertSame(bin2hex($expected), bin2hex($actual));
    }

    #[DataProvider('provideValidCases')]
    public function testDecode(string $input, string $header): void
    {
        $actual = ByteString::decode($header . $input);

        $this->assertSame($input, $actual);
    }

    public function testDecodeThrowsExceptionForInvalidMajorType(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage("Invalid CBOR ByteString major type.");

        ByteString::decode("\x0C");
    }

    public function testDecodeThrowsExceptionForLengthMismatch(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage("Invalid CBOR ByteString length mismatch.");

        ByteString::decode("\x45\x66\x6F\x6F\x62");
    }

    public function testDecodeThrowsExceptionForInvalidAdditionalInformation(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage("Invalid CBOR ByteString length information.");

        ByteString::decode("\x5F");
    }
}
