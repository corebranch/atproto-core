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
    #[DataProvider('provideCases')]
    public function testItCanEncodeCorrectly(string $case, string $header): void
    {
        $actual = bin2hex(TextString::encode($case));
        $expected = bin2hex($header . $case);

        $this->assertSame($expected, $actual);
    }

    #[DataProvider('provideCases')]
    public function testItCanDecodeCorrectly(string $case, string $header): void
    {
        $target = $header . $case;

        $actual = TextString::decode($target);
        $expected = $case;

        $this->assertSame($expected, $actual);
    }

    public static function provideCases(): array
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
}
