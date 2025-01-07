<?php declare(strict_types = 1);

/**
 * This file is part of the ATProto Core package.
 *
 * (c) Core Branch
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace ATProto\Core\CBOR\MajorTypes;

use ValueError;

class UnsignedInteger
{
    public static function decode(string $data): int
    {
        $firstByte = ord($data[0]);
        $majorType = ($firstByte >> 5) & 0x07;
        $additionalInfo = $firstByte & 0x1F;

        if ($majorType !== 0) {
            throw new ValueError("Invalid major type for unsigned integer: $majorType");
        }

        $offset = 1;

        if ($additionalInfo <= 23) {
            $value = $additionalInfo;
        } elseif ($additionalInfo === 24) {
            $value = ord($data[$offset]);
        } elseif ($additionalInfo === 25) {
            $value = unpack('n', substr($data, $offset, 2))[1];
        } elseif ($additionalInfo === 26) {
            $value = unpack('N', substr($data, $offset, 4))[1];
        } elseif ($additionalInfo === 27) {
            $value = unpack('J', substr($data, $offset, 8))[1];
        } else {
            throw new ValueError("Invalid additional information for unsigned integer: $additionalInfo");
        }

        return $value;
    }

    public static function encode(int $value): string
    {
        if ($value < 0) {
            throw new ValueError("\$value must be greater than 0");
        }

        $prefixedPack = fn (string $format, ?string $prefix)
            => $prefix . pack($format, $value);

        if ($value <= 0x17) {
            return chr($value);
        }

        if ($value <= 0xFF) {
            return "\x18" . chr($value);
        }

        if ($value <= 0xFFFF) {
            return $prefixedPack('n', "\x19");
        }

        if ($value <= 0xFFFFFFFF) {
            return $prefixedPack('N', "\x1A");
        }

        return $prefixedPack('J', "\x1B");
    }
}
