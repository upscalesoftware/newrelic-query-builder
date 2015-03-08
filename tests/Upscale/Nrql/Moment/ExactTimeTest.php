<?php

namespace Upscale\Nrql\Moment;

class ExactTimeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExactTime
     */
    private $subject;

    /**
     * @var \DateTime
     */
    private $time;

    protected function setUp()
    {
        $this->time = new \DateTime();
        $this->time
            ->setTimezone(new \DateTimeZone('America/Los_Angeles'))
            ->setDate(2015, 3, 8)
            ->setTime(12, 7, 36)
        ;
        $this->subject = new ExactTime($this->time);
    }

    public function testGetTime()
    {
        $this->assertSame($this->time, $this->subject->getTime());
    }

    public function testRenderNrql()
    {
        $this->assertEquals("'2015-03-08 12:07:36 PDT'", $this->subject->renderNrql());
    }
}
