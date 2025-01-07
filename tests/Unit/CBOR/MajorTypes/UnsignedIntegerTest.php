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

use ATProto\Core\CBOR\MajorTypes\UnsignedInteger;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UnsignedIntegerTest extends TestCase
{
    #[DataProvider('provideCases')]
    public function testEncodeCanEncodeCorrectly(int $case, string $expected): void
    {
        $actual = bin2hex(UnsignedInteger::encode($case));
        $expected = bin2hex($expected);

        $this->assertSame($actual, $expected);
    }

    #[DataProvider('provideCases')]
    public function testDecode(int $expected, string $case): void
    {
        $actual = bin2hex((string) UnsignedInteger::decode($case));
        $expected = bin2hex((string) $expected);

        $this->assertSame($expected, $actual);
    }

    public function testEncodeThrowsAnExceptionForValueGreaterThanIntMax(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage("Invalid CBOR data: Decoded value is negative, which is not valid for unsigned integers.");

        UnsignedInteger::decode("\x1B\x80\x00\x00\x00\x00\x00\x00\x00"); // PHP_INT_MAX + 1
    }

    #[DataProvider('provideNegativeCases')]
    public function testDecodeThrowsAnExceptionWhenPassedInvalidValue(string $case): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage("Invalid major type for unsigned integer: ");

        UnsignedInteger::decode($case);
    }

    public static function provideNegativeCases(): array
    {
        $arr = [];

        for ($i = -1; $i >= -100; $i--) {
            $arr[] = [hex2bin(dechex($i))];
        }

        return $arr;
    }

    public static function provideCases(): iterable
    {
        return [
            [0, "\x00"],
            [1, "\x01"],
            [10, "\x0a"],
            [23, "\x17"],
            [24, "\x18\x18"],
            [25, "\x18\x19"],
            [100, "\x18\x64"],
            [1000, "\x19\x03\xe8"],
            [1000000, "\x1a\x00\x0f\x42\x40"],
            [1000000000000, "\x1b\x00\x00\x00\xe8\xd4\xa5\x10\x00"],
        ];
    }
}
