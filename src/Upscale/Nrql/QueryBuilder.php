<?php

namespace Upscale\Nrql;

use Upscale\Nrql\Moment\MomentAbstract;

/**
 * Builder of a query in New Relic Query Language (NRQL) with fluent interface to set query parts in an arbitrary order
 *
 * @link https://docs.newrelic.com/docs/insights/new-relic-insights/using-new-relic-query-language/nrql-reference 
 */
class QueryBuilder implements SyntaxRendererInterface
{
    /**#@+
     * Available parts of a query in NRQL 
     */
    const PART_SELECT       = 'SELECT';
    const PART_FROM         = 'FROM';
    const PART_WHERE        = 'WHERE';
    const PART_FACET        = 'FACET';
    const PART_LIMIT        = 'LIMIT';
    const PART_SINCE        = 'SINCE';
    const PART_UNTIL        = 'UNTIL';
    const PART_COMPARE_WITH = 'COMPARE WITH';
    const PART_TIME_SERIES  = 'TIMESERIES';
    /**#@-*/

    /**
     * Rendered parts of a query
     * 
     * @var array
     */
    protected $parts = array(
        self::PART_SELECT       => '',
        self::PART_FROM         => '',
        self::PART_WHERE        => '',
        self::PART_FACET        => '',
        self::PART_LIMIT        => '',
        self::PART_SINCE        => '',
        self::PART_UNTIL        => '',
        self::PART_COMPARE_WITH => '',
        self::PART_TIME_SERIES  => '',
    );

    /**
     * Assign NRQL expression to a query part with no syntax validation 
     * 
     * @param string $part Part name
     * @param string $value
     * @return $this
     * @throws \InvalidArgumentException Thrown when specified query part is not supported
     * @throws \InvalidArgumentException Thrown when attempting to override existing value of a query part
     */
    protected function setPart($part, $value)
    {
        if (!array_key_exists($part, $this->parts)) {
            throw new \InvalidArgumentException("Query part '$part' is not recognized.");
        }
        if (!empty($this->parts[$part])) {
            throw new \InvalidArgumentException("Value has already been assigned to the query part '$part'.");
        }
        $this->parts[$part] = $value;
        return $this;
    }

    /**
     * Assign SELECT statement to specify what the query is reporting
     * 
     * @param array $attributes Attribute names and/or attribute expressions
     * @return $this
     */
    public function select(array $attributes)
    {
        return $this->setPart(self::PART_SELECT, implode(', ', $attributes));
    }

    /**
     * Assign SELECT statement to specify what the query is reporting all available attributes
     * 
     * @return $this
     */
    public function selectAll()
    {
        return $this->setPart(self::PART_SELECT, '*');
    }

    /**
     * Assign FROM clause to specify the event type containing the attributes being queried
     * 
     * @param string $event Event name
     * @return $this
     */
    public function from($event)
    {
        return $this->setPart(self::PART_FROM, $event);
    }

    /**
     * Assign WHERE clause to specify a series of one or more conditions separated by the keywords AND or OR
     * 
     * @param string $conditions Conditions expression
     * @return $this
     */
    public function where($conditions)
    {
        return $this->setPart(self::PART_WHERE, $conditions);
    }

    /**
     * Assign FACET clause to break out your data by any string attribute
     * 
     * @param string $attribute Attribute name or expression
     * @return $this
     */
    public function facet($attribute)
    {
        return $this->setPart(self::PART_FACET, $attribute);
    }

    /**
     * Assign LIMIT clause to constrain the number of values returned
     * 
     * @param int $count
     * @return $this
     */
    public function limit($count)
    {
        return $this->setPart(self::PART_LIMIT, $count);
    }

    /**
     * Assign SINCE clause to define the beginning of a time range across which to pull data
     * 
     * @param MomentAbstract $moment
     * @return $this
     */
    public function since(MomentAbstract $moment)
    {
        return $this->setPart(self::PART_SINCE, $moment->renderNrql());
    }

    /**
     * Assign SINCE clause to define the end a time range across which to pull data
     * 
     * @param MomentAbstract $moment
     * @return $this
     */
    public function until(MomentAbstract $moment)
    {
        return $this->setPart(self::PART_UNTIL, $moment->renderNrql());
    }

    /**
     * Assign COMPARE WITH clause to compare the values for two different time ranges
     * 
     * @param MomentAbstract $moment Beginning moment of comparison range
     * @return $this
     */
    public function compareWith(MomentAbstract $moment)
    {
        return $this->setPart(self::PART_COMPARE_WITH, $moment->renderNrql());
    }

    /**
     * Assign TIMESERIES clause to return data as a time series broken out by a specified period of time
     * 
     * @param TimePeriod|null $period Specified time period or automatic detection if NULL specified
     * @return $this
     */
    public function timeSeries(TimePeriod $period = null)
    {
        return $this->setPart(self::PART_TIME_SERIES, $period ? $period->renderNrql() : 'AUTO');
    }

    /**
     * Return complete query assembled from individual pieces
     * {@inheritdoc}
     */
    public function renderNrql()
    {
        $this->validate($this->parts);
        $result = '';
        foreach ($this->parts as $name => $value) {
            $result .= ($result ? ' ' : '') . $name . ' ' . $value;
        }
        return $result;
    }

    /**
     * Perform integrity check on specified query parts
     *
     * @param array $parts Set of parts to validate 
     * @throws \LogicException Thrown when required query parts are missing or parts contradict to each other
     */
    protected function validate(array $parts)
    {
        if (empty($parts[self::PART_SELECT])) {
            throw new \LogicException('SELECT statement is missing.');
        }
        if (empty($parts[self::PART_FROM])) {
            throw new \LogicException('FROM clause is missing.');
        }
        if (!empty($parts[self::PART_COMPARE_WITH])
            && empty($parts[self::PART_SINCE])
            && empty($parts[self::PART_UNTIL])
        ) {
            throw new \LogicException('COMPARE WITH clause requires a SINCE or UNTIL clause.');
        }
    }
}
