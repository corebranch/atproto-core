<?php declare(strict_types=1);

/**
 * This file is part of the ATProto Core package.
 *
 * (c) Core Branch
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace ATProto\Core\CBOR;

use ATProto\Core\CBOR\MajorTypes\TextString;
use ATProto\Core\CBOR\MajorTypes\UnsignedInteger;

class CBOR
{
    public static function encode(string|int|array $data): string
    {
        switch (gettype($data)) {
            case 'integer':
                return UnsignedInteger::encode($data);
            break;
            case 'string':
                return TextString::encode($data);
            break;
        }

        throw new \ValueError("Unsupported type: " . gettype($data));
    }

    public static function decode(string $data): int|string
    {
        if (TextString::validate($data)) {
            return TextString::decode($data);
        }

        if (UnsignedInteger::validate($data)) {
            return UnsignedInteger::decode($data);
        }

        throw new \ValueError("Unsupported type.");
    }
}
