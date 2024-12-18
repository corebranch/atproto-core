<?php

namespace ATProto\Core\MultiFormats\MultiBase;

enum MultiBaseEnum: string
{
    // RFC4648 case-insensitive - no padding
    case BASE32 = \ATProto\Core\MultiFormats\MultiBase\Encoders\Base32::class;
}
