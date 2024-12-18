<?php declare(strict_types = 1);

/**
 * This file is part of the ATProto Core package.
 *
 * (c) Core Branch
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace ATProto\Core\MultiFormats;

class MultiHash
{
    private const array ALGO_ALIASES_MAP_BY_MULTICODEC = [
        MultiCodecEnum::SHA2_256->name => 'sha256',
    ];

    public static function generate(string $data, MultiCodecEnum $hashAlgo = MultiCodecEnum::SHA2_256): string
    {
        if (empty($data)) {
            throw new \ValueError("Data cannot be empty.");
        }

        $hash = \hash(self::ALGO_ALIASES_MAP_BY_MULTICODEC[$hashAlgo->name], $data, true);

        return \sprintf("%s%s%s",
            \chr($hashAlgo->value),
            \chr(\strlen($hash)),
            $hash
        );
    }
}
