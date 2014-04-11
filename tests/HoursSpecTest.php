<?php
use WorldCat\Registry\HoursSpec;

class HoursSpecTest extends \PHPUnit_Framework_TestCase
{

    private $graph;

    function setUp()
    {
        EasyRdf_Namespace::set('schema', 'http://schema.org/');
        EasyRdf_Namespace::set('wcir', 'http://purl.org/oclc/ontology/wcir/');
        EasyRdf_TypeMapper::set('wcir:hoursSpecification', 'WorldCat\Registry\HoursSpec');
    }

    /**
     * can parse Normal Hours
     */
    function testParseNormalHours()
    {
        $this->graph = new EasyRdf_Graph();
        $this->graph->parseFile("sample-data/normal-hours.rdf");
        $hoursSpecs = $this->graph->allOfType('wcir:hoursSpecification');
        $hoursSpec = $hoursSpecs[0];
        $this->assertNotEmpty($hoursSpec->getDayOfWeek());
        // want to check to see this is a real day of the week
        $validDays = array(
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        );
        $this->assertContains($hoursSpec->getDayOfWeek(), $validDays);
        $this->assertEquals('08:00', $hoursSpec->getOpeningTime());
        $this->assertEquals('17:00', $hoursSpec->getClosingTime());
        $this->assertEquals('Open', $hoursSpec->getOpenStatus());
    }

    /**
     * can parse Special Hours Closed
     */
    function testParseSpecialHoursClosed()
    {
        $this->graph = new EasyRdf_Graph();
        $this->graph->parseFile("sample-data/special-hours.rdf");
        $hoursSpecs = $this->graph->allOfType('wcir:hoursSpecification');
        $hoursSpec = $hoursSpecs[0];
        $this->assertEquals('2013-12-25T12:00:00', $hoursSpec->getStartDate());
        $this->assertEquals('2013-12-25T12:00:00', $hoursSpec->getEndDate());
        $this->assertEquals('Christmas', $hoursSpec->getDescription());
        $this->assertEquals('Closed', $hoursSpec->getOpenStatus());
        $this->assertEmpty($hoursSpec->getOpeningTime());
        $this->assertEmpty($hoursSpec->getClosingTime());
    }

    /**
     * can parse Special Hours Open
     */
    function testParseSpecialHoursOpen()
    {
        $this->graph = new EasyRdf_Graph();
        $this->graph->parseFile("sample-data/special-hours.rdf");
        $hoursSpecs = $this->graph->allOfType('wcir:hoursSpecification');
        $hoursSpec = $hoursSpecs[1];
        $this->assertEquals('2014-03-16T12:00:00', $hoursSpec->getStartDate());
        $this->assertEquals('2014-03-22T12:00:00', $hoursSpec->getEndDate());
        $this->assertEquals('Spring Break 2014', $hoursSpec->getDescription());
        $this->assertEquals('Open', $hoursSpec->getOpenStatus());
        $this->assertEquals('09:00', $hoursSpec->getOpeningTime());
        $this->assertEquals('17:00', $hoursSpec->getClosingTime());
    }
}