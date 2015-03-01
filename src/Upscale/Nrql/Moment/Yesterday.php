<?php

namespace Upscale\Nrql\Moment;

/**
 * Relative moment one day in the past
 */
class Yesterday extends MomentAbstract
{
    /**
     * {@inheritdoc}
     */
    public function renderNrql()
    {
        return 'YESTERDAY';
    }
}
