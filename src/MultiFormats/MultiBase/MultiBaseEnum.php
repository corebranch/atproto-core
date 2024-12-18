<?php declare(strict_types = 1);

/**
 * This file is part of the ATProto Core package.
 *
 * (c) Core Branch
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace ATProto\Core\MultiFormats\MultiBase;

enum MultiBaseEnum: string
{
    // RFC4648 case-insensitive - no padding
    case BASE32 = \ATProto\Core\MultiFormats\MultiBase\Encoders\Base32::class;
}
