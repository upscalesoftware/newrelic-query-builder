<?php

namespace Upscale\Nrql\Moment;

/**
 * Absolute moment in time
 */
class ExactTime extends MomentAbstract
{
    /**
     * @var \DateTime
     */
    private $time;

    /**
     * @param \DateTime $time
     */
    public function __construct(\DateTime $time)
    {
        $this->time = $time;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * {@inheritdoc}
     */
    public function renderNrql()
    {
        return "'" . $this->time->format('Y-m-d H:i:s T') . "'";
    }
}
