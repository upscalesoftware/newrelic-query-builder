<?php

namespace Upscale\Nrql\Moment;

use Upscale\Nrql\SyntaxRendererInterface;

/**
 * Moment in time that can be expressed in NRQL syntax
 */
abstract class MomentAbstract implements SyntaxRendererInterface
{
    /**
     * {@inheritdoc}
     */
    abstract public function renderNrql();
}
