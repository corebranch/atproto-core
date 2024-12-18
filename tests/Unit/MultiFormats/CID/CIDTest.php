<?php

/**
 * This file is part of the ATProto Core package.
 *
 * (c) Core Branch
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace Tests\Unit\MultiFormats\CID;

use ATProto\Core\MultiFormats\CID\CID;
use ATProto\Core\MultiFormats\CID\Versions\CIDV1;
use PHPUnit\Framework\TestCase;

class CIDTest extends TestCase
{
    public function testItCanCreateValidCID()
    {
        $cid = new CID('content');

        $expectedHex = '01551220ed7002b439e9ac845f22357d822bac1444730fbdb6016d3ec9432297b9ec9f73';
        $actualHex   = bin2hex($cid->generate());

        $this->assertEquals($expectedHex, $actualHex, "Generated CID should be match the expected value.");
    }

    public function testGeneratesValidCIDForStringContent()
    {
        $cid = new CID('content', new CIDV1());
        $generated = $cid->__toString();

        $this->assertNotEmpty($generated, 'Generated CID should not be empty');
        $this->assertMatchesRegularExpression('/^b/', $generated, 'Generated CID should be Base32-encoded');
    }

    public function testHandlesEmptyDataError()
    {
        $this->expectException(\ValueError::class);
        $cid = new CID('', new CIDV1());
        $cid->generate();
    }

    public function testConsistentCIDGeneration()
    {
        $content = 'content';
        $cid1 = new CID($content, new CIDV1());
        $cid2 = new CID($content, new CIDV1());

        $this->assertEquals($cid1->generate(), $cid2->generate(), 'CID generation should be consistent for the same input');
    }
}
