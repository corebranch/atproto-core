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
