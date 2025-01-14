<?php

namespace ATProto\Core\CBOR\MajorTypes;

use ValueError;

class ByteString
{
    public const int MAJOR_TYPE = 0x02;

    public static function validate(string $input): bool
    {
        return ((ord($input[0]) >> 5) & 0x07) === self::MAJOR_TYPE;
    }

    public static function encode(string $input): string
    {
        $len = strlen($input);

        if ($len <= 0x17) {
            $header = chr((self::MAJOR_TYPE << 5) | $len);
        } else if ($len <= 0xFF) {
            $header = chr((self::MAJOR_TYPE << 5) | 24) . chr($len);
        } else if ($len <= 0xFFFF) {
            $header = chr((self::MAJOR_TYPE << 5) | 25) . pack('n', $len);
        } else if ($len <= 0xFFFFFFFF) {
            $header = chr((self::MAJOR_TYPE << 5) | 26) . pack('N', $len);
        } else {
            $header = chr((self::MAJOR_TYPE << 5) | 27) . pack('J', $len);
        }

        return $header . $input;
    }

    public static function decode(string $input): string
    {
        if (! self::validate($input)) {
            throw new ValueError("Invalid CBOR ByteString major type.");
        }

        $addInfo = ord($input[0]) & 0x1F;
        $offset = 1;

        if ($addInfo <= 23) {
            $length = $addInfo;
        }

        if ($addInfo === 24) {
            $length = ord($input[$offset]);
            $offset += 1;
        }

        if ($addInfo === 25) {
            $length = unpack('n', substr($input, $offset, 2))[1];
            $offset += 2;
        }

        if ($addInfo === 26) {
            $length = unpack('N', substr($input, $offset, 4))[1];
            $offset += 4;
        }

        if ($addInfo === 27) {
            $length = unpack('J', substr($input, $offset, 8))[1];
            $offset += 8;
        }

        if (! isset($length)) {
            throw new \ValueError('Invalid CBOR ByteString length information.');
        }

        $text = substr($input, $offset, $length);

        if (strlen($text) !== $length) {
            throw new \ValueError("Invalid CBOR ByteString length mismatch.");
        }

        return $text;
    }
}
