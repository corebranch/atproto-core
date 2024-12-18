<?php declare(strict_types = 1);

/**
 * This file is part of the ATProto Core package.
 *
 * (c) Core Branch
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace Tests\Unit\MultiFormats;

use ATProto\Core\MultiFormats\MultiCodecEnum;
use ATProto\Core\MultiFormats\MultiHash;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MultiHashTest extends TestCase
{
    #[DataProvider('provideValidHashCases')]
    public function testGenerateWithValidData(string $data, string $expectedResult): void
    {
        $expectedPrefix = chr(MultiCodecEnum::SHA2_256->value) . chr(32); // 32 bytes for SHA256 hash

        $hash = MultiHash::generate($data);
        $unprefixedHex = bin2hex(substr($hash, 2));

        $this->assertStringStartsWith($expectedPrefix, $hash, "Generated hash should have correct prefix.");
        $this->assertEquals(34, \strlen($hash), "Generated hash should have expected length.");
        $this->assertSame($expectedResult, $unprefixedHex, "Generated hash should have expected result.");
    }

    public function testGenerateWithEmptyData(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage("Data cannot be empty.");

        MultiHash::generate("");
    }

    public static function provideValidHashCases(): array
    {
        return [
            ['f', '252f10c83610ebca1a059c0bae8255eba2f95be4d1d7bcfa89d7248a82d9f111'],
            ['fo', '9c3aee7110b787f0fb5f81633a36392bd277ea945d44c874a9a23601aefe20cf'],
            ['foo', '2c26b46b68ffc68ff99b453c1d30413413422d706483bfa0f98a5e886266e7ae'],
            ['foob', 'a7452118bfc838ee7b2aac14a8bc88c50a1ae4620903c4f8cdd327bb79961899'],
            ['fooba', '41cbe1a87981490351ccad5346d96da0ac10678670b31fc0ab209aed1b5bc515'],
            ['foobar', 'c3ab8ff13720e8ad9047dd39466b3c8974e592c2fa383d4a3960714caef0c4f2']
        ];
    }
}
