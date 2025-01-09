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
        $initialByte = ord($input[0]);
        $majorType = ($initialByte >> 5) & 0x07;

        if ($majorType !== 0x03) {
            throw new \ValueError('Invalid CBOR TextString major type.');
        }

        $additionalInfo = $initialByte & 0x1F;
        $offset = 1;

        if ($additionalInfo <= 23) {
            $length = $additionalInfo;
        } elseif ($additionalInfo === 24) {
            $length = ord($input[$offset]);
            $offset += 1;
        } elseif ($additionalInfo === 25) {
            $length = unpack('n', substr($input, $offset, 2))[1];
            $offset += 2;
        } elseif ($additionalInfo === 26) {
            $length = unpack('N', substr($input, $offset, 4))[1];
            $offset += 4;
        } elseif ($additionalInfo === 27) {
            $length = unpack('J', substr($input, $offset, 8))[1];
            $offset += 8;
        } else {
            throw new \ValueError('Invalid CBOR TextString length information.');
        }

        $text = substr($input, $offset, $length);

        if (strlen($text) !== $length) {
            throw new \ValueError('Invalid CBOR TextString length mismatch.');
        }

        return $text;
    }
}
