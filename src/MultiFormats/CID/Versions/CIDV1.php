<?php declare(strict_types = 1);

/**
 * This file is part of the ATProto Core package.
 *
 * (c) Core Branch
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace ATProto\Core\MultiFormats\CID\Versions;

use ATProto\Core\MultiFormats\CID\CIDVersion;
use ATProto\Core\MultiFormats\MultiCodecEnum;
use ATProto\Core\MultiFormats\MultiHash;

class CIDV1 extends CIDVersion
{
    public function generate(): string
    {
        $version = chr(MultiCodecEnum::CIDV1->value);
        $type    = chr($this->cid->codec->value);
        $data    = MultiHash::generate($this->cid->data);

        return sprintf("%s%s%s", $version, $type, $data);
    }
}
