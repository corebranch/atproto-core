<?php declare(strict_types=1);

/**
 * This file is part of the ATProto Core package.
 *
 * (c) Core Branch
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace ATProto\Core\MultiFormats;

enum MultiCodecEnum: int
{
    case CIDV1 = 0x01;
    case SHA2_256 = 0x012;
    case RAW = 0x55;
    case DAG_CBOR = 0x71;
}
