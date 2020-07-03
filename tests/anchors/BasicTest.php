<?php
namespace tests\anchors;

use deflou\components\anchors\Anchor;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

/**
 * Class BasicTest
 * @package tests\anchors
 * @author jeyroik <jeyroik@gmail.com>
 */
class BasicTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
    }

    public function testGettersAndSetters()
    {
        $anchor = new Anchor();
        $lastCallTime = time();
        $anchor->setCallsNumber(1)
            ->setLastCallTime($lastCallTime)
            ->incCallsNumber();

        $this->assertEquals(2, $anchor->getCallsNumber());
        $this->assertEquals($lastCallTime, $anchor->getLastCallTime());
    }
}
