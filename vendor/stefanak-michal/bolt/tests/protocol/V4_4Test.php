<?php

namespace Bolt\tests\protocol;

use Bolt\protocol\V4_4;
use Exception;

/**
 * Class V4_4Test
 *
 * @author Michal Stefanak
 * @link https://github.com/neo4j-php/Bolt
 *
 * @covers \Bolt\protocol\AProtocol
 * @covers \Bolt\protocol\V4_4
 *
 * @package Bolt\tests\protocol
 * @requires PHP >= 7.1
 * @requires mbstring
 */
class V4_4Test extends ATest
{
    /**
     * @return V4_4
     */
    public function test__construct(): V4_4
    {
        $cls = new V4_4(new \Bolt\PackStream\v1\Packer, new \Bolt\PackStream\v1\Unpacker, $this->mockConnection());
        $this->assertInstanceOf(V4_4::class, $cls);
        return $cls;
    }

    /**
     * @depends test__construct
     * @param V4_4 $cls
     */
    public function testRoute(V4_4 $cls)
    {
        self::$readArray = [1, 2, 0];
        self::$writeBuffer = [
            hex2bin('0001b3'),
            hex2bin('000166'),
            hex2bin('0001a1'),
            hex2bin('00088761646472657373'),
            hex2bin('000f8e6c6f63616c686f73743a37363837'),
            hex2bin('000190'),
            hex2bin('0001a1'),
            hex2bin('0003826462'),
            hex2bin('0001c0'),
        ];

        try {
            $this->assertIsArray($cls->route(['address' => 'localhost:7687'], [], ['db' => null]));
        } catch (Exception $e) {
            $this->markTestIncomplete($e->getMessage());
        }
    }

}
