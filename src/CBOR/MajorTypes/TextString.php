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

class TextString
{
    public static function encode(string $input): string
    {
        $length = strlen($input);

        if ($length <= 23) {
            $header = chr((0x03 << 5) | $length);
        } elseif ($length <= 0xFF) {
            $header = chr((0x03 << 5) | 24) . chr($length);
        } elseif ($length <= 0xFFFF) {
            $header = chr((0x03 << 5) | 25) . pack('n', $length);
        } elseif ($length <= 0xFFFFFFFF) {
            $header = chr((0x03 << 5) | 26) . pack('N', $length);
        } else {
            $header = chr((0x03 << 5) | 27) . pack('J', $length);
        }

        return $header . $input;
    }

    public static function decode(string $input): string
    {
        // TODO
    }
}