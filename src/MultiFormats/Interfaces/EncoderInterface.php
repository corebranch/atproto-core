<?php declare(strict_types = 1);

/**
 * This file is part of the ATProto Core package.
 *
 * (c) Core Branch
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace ATProto\Core\MultiFormats\Interfaces;

interface EncoderInterface
{
    public static function encode(string $data): string;
    public static function decode(string $data): string;
}
