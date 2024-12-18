<?php declare(strict_types=1);

/**
 * This file is part of the ATProto Core package.
 *
 * (c) Core Branch
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace Tests\Unit\MultiFormats\MultiBase;

use ATProto\Core\MultiFormats\Interfaces\EncoderInterface;
use ATProto\Core\MultiFormats\MultiBase\MultiBaseEnum;
use PHPUnit\Framework\TestCase;

class MultiBaseEnumTest extends TestCase
{
    public function testMultiBaseEnumCasesImplementEncoderInterface(): void
    {
        foreach(MultiBaseEnum::cases() as $case) {
            $this->assertInstanceOf(
                EncoderInterface::class,
                new ($case->value),
                sprintf(
                    'Case "%s" with value "%s" must implement %s.',
                    $case->name,
                    $case->value,
                    EncoderInterface::class
                )
            );
        }
    }
}
