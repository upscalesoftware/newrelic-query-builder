<?php

namespace Upscale\Nrql\Moment;

use Upscale\Nrql\TimePeriod;

class TimeAgoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TimeAgo
     */
    private $subject;

    /**
     * @var TimePeriod|\PHPUnit_Framework_MockObject_MockObject
     */
    private $period;

    protected function setUp()
    {
        $this->period = $this->getMock('Upscale\Nrql\TimePeriod', array(), array(), '', false);
        $this->subject = new TimeAgo($this->period);
    }

    public function testGetPeriod()
    {
        $this->assertSame($this->period, $this->subject->getPeriod());
    }

    public function testRenderNrql()
    {
        $this->period
            ->expects($this->once())
            ->method('renderNrql')
            ->willReturn('365 days')
        ;
        $this->assertEquals('365 days AGO', $this->subject->renderNrql());
    }
}
