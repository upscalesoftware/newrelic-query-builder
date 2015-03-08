<?php

namespace Upscale\Nrql;

class TimePeriodTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unit 'unsupported' is not supported
     */
    public function testConstructorUnsupportedUnit()
    {
        new TimePeriod(1, 'unsupported');
    }

    /**
     * @param int $duration
     * @param string $unit
     * @dataProvider durationUnitDataProvider
     */
    public function testGetDuration($duration, $unit)
    {
        $subject = new TimePeriod($duration, $unit);
        $this->assertEquals($duration, $subject->getDuration());
    }

    /**
     * @param int $duration
     * @param string $unit
     * @dataProvider durationUnitDataProvider
     */
    public function testGetUnit($duration, $unit)
    {
        $subject = new TimePeriod($duration, $unit);
        $this->assertEquals($unit, $subject->getUnit());
    }

    /**
     * @param int $duration
     * @param string $unit
     * @param string $expected
     * @dataProvider durationUnitDataProvider
     */
    public function testRenderNrql($duration, $unit, $expected)
    {
        $subject = new TimePeriod($duration, $unit);
        $this->assertEquals($expected, $subject->renderNrql());
    }
    
    public function durationUnitDataProvider()
    {
        return array(
            'one minute'    => array(1, TimePeriod::UNIT_MINUTES, '1 minutes'),
            'five hours'    => array(5, TimePeriod::UNIT_HOURS, '5 hours'),
            'fourteen days' => array(14, TimePeriod::UNIT_DAYS, '14 days'),
            'four weeks'    => array(4, TimePeriod::UNIT_WEEKS, '4 weeks'),
        );
    }
}
