<?php

namespace Upscale\Nrql\Moment;

class YesterdayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Yesterday
     */
    private $subject;

    protected function setUp()
    {
        $this->subject = new Yesterday();
    }
    
    public function testRenderNrql()
    {
        $actual = $this->subject->renderNrql();
        $this->assertNotEmpty($actual);
        $this->assertSame($actual, $this->subject->renderNrql());
    }
}
