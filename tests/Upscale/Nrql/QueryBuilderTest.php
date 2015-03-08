<?php

namespace Upscale\Nrql;

use Upscale\Nrql\Moment\MomentAbstract;

class QueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var QueryBuilder
     */
    private $query;

    protected function setUp()
    {
        $this->query = new QueryBuilder();
        $this->query
            ->selectAll()
            ->from(array('PageView'))
        ;
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage SELECT statement is missing
     */
    public function testSelectMissing()
    {
        $this->query = new QueryBuilder();
        $this->query->renderNrql();
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage FROM clause is missing
     */
    public function testFromMissing()
    {
        $this->query = new QueryBuilder();
        $this->assertSame($this->query, $this->query->selectAll());
        $this->query->renderNrql();
    }

    public function testSelectFrom()
    {
        $this->query = new QueryBuilder();
        $this->assertSame($this->query, $this->query->select(array('attr1', 'attr2')));
        $this->assertSame($this->query, $this->query->from(array('event1', 'event2')));
        $this->assertEquals('SELECT attr1, attr2 FROM event1, event2', $this->query->renderNrql());
    }

    public function testSelectAllFrom()
    {
        $this->query = new QueryBuilder();
        $this->assertSame($this->query, $this->query->selectAll());
        $this->assertSame($this->query, $this->query->from(array('event1', 'event2')));
        $this->assertEquals('SELECT * FROM event1, event2', $this->query->renderNrql());
    }

    public function testWhere()
    {
        $this->assertSame($this->query, $this->query->where('userAgentOS = "Mac"'));
        $this->assertEquals('SELECT * FROM PageView WHERE userAgentOS = "Mac"', $this->query->renderNrql());
    }

    public function testFacet()
    {
        $this->assertSame($this->query, $this->query->facet('countryCode'));
        $this->assertEquals('SELECT * FROM PageView FACET countryCode', $this->query->renderNrql());
    }

    public function testLimit()
    {
        $this->assertSame($this->query, $this->query->limit(10));
        $this->assertEquals('SELECT * FROM PageView LIMIT 10', $this->query->renderNrql());
    }

    public function testSince()
    {
        $moment = $this->createMomentMock('5 hours AGO');
        $this->assertSame($this->query, $this->query->since($moment));
        $this->assertEquals('SELECT * FROM PageView SINCE 5 hours AGO', $this->query->renderNrql());
        return $this->query;
    }

    public function testUntil()
    {
        $moment = $this->createMomentMock('2 hours AGO');
        $this->assertSame($this->query, $this->query->until($moment));
        $this->assertEquals('SELECT * FROM PageView UNTIL 2 hours AGO', $this->query->renderNrql());
        return $this->query;
    }

    /**
     * @param QueryBuilder $query
     * @depends testSince
     */
    public function testCompareWithSince(QueryBuilder $query)
    {
        $moment = $this->createMomentMock('6 months AGO');
        $this->assertSame($query, $query->compareWith($moment));
        $this->assertEquals('SELECT * FROM PageView SINCE 5 hours AGO COMPARE WITH 6 months AGO', $query->renderNrql());
    }

    /**
     * @param QueryBuilder $query
     * @depends testUntil
     */
    public function testCompareWithUntil(QueryBuilder $query)
    {
        $moment = $this->createMomentMock('4 months AGO');
        $this->assertSame($query, $query->compareWith($moment));
        $this->assertEquals('SELECT * FROM PageView UNTIL 2 hours AGO COMPARE WITH 4 months AGO', $query->renderNrql());
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage COMPARE WITH clause requires a SINCE or UNTIL clause
     */
    public function testCompareWithAmbiguous()
    {
        $moment = $this->createMomentMock('2 weeks AGO');
        $this->assertSame($this->query, $this->query->compareWith($moment));
        $this->query->renderNrql();
    }

    public function testTimeSeriesAuto()
    {
        $this->assertSame($this->query, $this->query->timeSeries());
        $this->assertEquals('SELECT * FROM PageView TIMESERIES AUTO', $this->query->renderNrql());
    }

    public function testTimeSeriesPeriod()
    {
        $period = $this->getMock('Upscale\Nrql\TimePeriod', array(), array(), '', false);
        $period
            ->expects($this->once())
            ->method('renderNrql')
            ->willReturn('30 minutes')
        ;
        $this->assertSame($this->query, $this->query->timeSeries($period));
        $this->assertEquals('SELECT * FROM PageView TIMESERIES 30 minutes', $this->query->renderNrql());
    }

    /**
     * Return newly created abstract moment that renders to a specified NRQL expression 
     * 
     * @param string $fixtureNrql
     * @return MomentAbstract|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function createMomentMock($fixtureNrql)
    {
        $result = $this->getMock('Upscale\Nrql\Moment\MomentAbstract', array(), array(), '', false);
        $result
            ->expects($this->once())
            ->method('renderNrql')
            ->willReturn($fixtureNrql)
        ;
        return $result;
    }
}
