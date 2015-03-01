Query Builder for NRQL
======================

The New Relic Query Language (NRQL) is an SQL-flavored query language for making calls against the Insights Events database.

This project is a PHP library for assembling NRQL queries in object-oriented applications. Library implements the official [NRQL specification](https://docs.newrelic.com/docs/insights/new-relic-insights/using-new-relic-query-language/nrql-reference). It offers a "fluent" interface to specify query parts in an arbitrary order. That allows different application parts to influence the query w/o worrying about the query assembly order. Query integrity validation is performed upon rendering. Library provides object-oriented representation for complex elements of NRQL syntax. That enables code completion and avoids typos in contrast to plain text queries.

## Usage

The example below demonstrates a query with all available clauses:
```php
use Upscale\Nrql\QueryBuilder;
use Upscale\Nrql\TimePeriod;
use Upscale\Nrql\Moment\TimeAgo;
use Upscale\Nrql\Moment\ExactTime;
use Upscale\Nrql\Moment\Yesterday;

$nrql = new QueryBuilder();
$nrql->select([
        'userAgentName',
    ])
    ->from('PageView')
    ->where('userAgentOS = "Windows"')
    ->facet('countryCode')
    ->limit(20)
    ->since(new TimeAgo(new TimePeriod(4, TimePeriod::UNIT_DAYS)))
    ->until(new Yesterday())
    ->compareWith(new ExactTime(new \DateTime('2015-01-01 00:00:00')))
    ->timeSeries(new TimePeriod(1, TimePeriod::UNIT_HOURS))
;

echo $nrql->renderNrql();
```
