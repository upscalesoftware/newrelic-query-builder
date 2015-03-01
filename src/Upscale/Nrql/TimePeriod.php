<?php

namespace Upscale\Nrql;

/**
 * Period of time of certain duration measured in one of the supported units
 */
class TimePeriod implements SyntaxRendererInterface
{
    /**#@+
     * Units a period duration is measured in 
     */
    const UNIT_MINUTES  = 'minutes';
    const UNIT_HOURS    = 'hours';
    const UNIT_DAYS     = 'days';
    const UNIT_WEEKS    = 'weeks';
    /**#@-*/

    /**
     * @var array
     */
    protected $availableUnits = array(
        self::UNIT_MINUTES,
        self::UNIT_HOURS,
        self::UNIT_DAYS,
        self::UNIT_WEEKS,
    );

    /**
     * @var int
     */
    private $duration;

    /**
     * @var string
     */
    private $unit;

    /**
     * @param int $duration
     * @param string $unit Constants self::UNIT_*
     */
    public function __construct($duration, $unit)
    {
        if (!in_array($unit, $this->availableUnits)) {
            throw new \InvalidArgumentException("Unit '$unit' is not supported.");
        }
        $this->duration = $duration;
        $this->unit = $unit;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * {@inheritdoc}
     */
    public function renderNrql()
    {
        return $this->duration . ' ' . $this->unit;
    }
}
