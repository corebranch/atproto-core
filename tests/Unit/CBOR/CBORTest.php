<?php declare(strict_types = 1);

/**
 * This file is part of the ATProto Core package.
 *
 * (c) Core Branch
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace Tests\Unit\CBOR;

use ATProto\Core\CBOR\CBOR;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CBORTest extends TestCase
{
    #[DataProvider('validCases')]
    public function testEncode(int|string $data, string $expected): void
    {
        $encoded = CBOR::encode($data);
        $this->assertSame($expected, $encoded);
    }

    #[DataProvider('validCases')]
    public function testDecode(int|string $expected, string $data): void
    {
        $actual = CBOR::decode($data);

        $this->assertSame($expected, $actual);
    }

    /**
     * @return array[]
     */
    public static function validCases(): array
    {
        return [
            // Unsigned integer test cases
            [1, hex2bin('01')], // 1 encoded as CBOR unsigned integer
            [10, hex2bin('0a')], // 10 encoded as CBOR unsigned integer

            // String test cases
            ['hello', hex2bin('6568656C6C6F')], // "hello" encoded as CBOR text string
//
//            // Boolean test cases
//            [[true], hex2bin('f5')], // true encoded as CBOR special type
//            [[false], hex2bin('f4')], // false encoded as CBOR special type
//
//            // Null test case
//            [[null], hex2bin('f6')], // null encoded as CBOR special type
//
//            // Array test cases
//            [[[1, 2, 3]], hex2bin('83010203')], // [1, 2, 3] encoded as CBOR array
//
//            // Map test cases
//            [[['key' => 'value']], hex2bin('a1636b65796576616c7565')], // {"key": "value"} encoded as CBOR map
        ];
    }
}
