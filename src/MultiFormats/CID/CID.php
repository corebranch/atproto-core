<?php declare(strict_types = 1);

/**
 * This file is part of the ATProto Core package.
 *
 * (c) Core Branch
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace ATProto\Core\MultiFormats\CID;

use ATProto\Core\MultiFormats\CID\Versions\CIDV1;
use ATProto\Core\MultiFormats\Interfaces\EncoderInterface;
use ATProto\Core\MultiFormats\MultiBase\Encoders\Base32;
use ATProto\Core\MultiFormats\MultiBase\MultiBaseEnum;
use ATProto\Core\MultiFormats\MultiCodecEnum;

class CID implements \Stringable
{
    private EncoderInterface $encoder;
    public private(set) MultiCodecEnum $codec = MultiCodecEnum::RAW;

    public CIDVersion $version {
        set {
            $this->version = $value;
            $this->version->setCID($this);
        }
    }

    public function __construct(
        public private(set) string $data,
        CIDVersion $version = new CIDV1(),
        MultiBaseEnum|EncoderInterface $encoder = new Base32(),
    )
    {
        $this->version = $version;
        $this->version->setCID($this);

        $this->encoder = $encoder;

        if ($this->encoder instanceof MultiBaseEnum) {
            $this->encoder = new ($this->encoder->value);
        }
    }

    public function generate(): string
    {
        return $this->version->generate();
    }

    public function __toString(): string
    {
        return $this->encoder::encode($this->generate());
    }
}
