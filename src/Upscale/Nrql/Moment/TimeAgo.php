<?php

namespace Upscale\Nrql\Moment;

use Upscale\Nrql\TimePeriod;

/**
 * Relative moment in the past
 */
class TimeAgo extends MomentAbstract
{
    /**
     * @var TimePeriod
     */
    private $period;

    /**
     * @param TimePeriod $period Period towards the past
     */
    public function __construct(TimePeriod $period)
    {
        $this->period = $period;
    }

    /**
     * @return TimePeriod
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * {@inheritdoc}
     */
    public function renderNrql()
    {
        return $this->period->renderNrql() . ' AGO';
    }
}
