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

use ATProto\Core\CBOR\MajorTypes\UnsignedInteger;

class CBOR
{
    public static function encode(string|int|array $data): string
    {
        switch (gettype($data)) {
            case 'integer':
                return UnsignedInteger::encode($data);
            break;
        }

        throw new \ValueError("Unsupported type: " . gettype($data));
    }

    public static function decode(string $data): int
    {
        return UnsignedInteger::decode((string) $data);
    }
}
