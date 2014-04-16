<?php
use WorldCat\Registry\HoursSpec;

class HoursSpecTest extends \PHPUnit_Framework_TestCase
{
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
        $graph = new EasyRdf_Graph("http://localhost/wclibhours/tests/sample-data/normal-hours.rdf");
        $graph->load();
        $hoursSpecs = $graph->allOfType('wcir:hoursSpecification');
        
        foreach ($hoursSpecs as $hoursSpec)
        {
            $openingTime = $hoursSpec->getOpeningTime();
            $closingTime = $hoursSpec->getClosingTime();
            $dayOfWeek = $hoursSpec->getDayOfWeek();
            
            if ($dayOfWeek == 'Friday')
            {
                $this->assertEquals('08:00', $openingTime);
                $this->assertEquals('17:00', $closingTime);
            }
            else if ($dayOfWeek == 'Saturday')
            {
                $this->assertEquals('10:00', $openingTime);
                $this->assertEquals('17:00', $closingTime);
            }
            else if ($dayOfWeek == 'Sunday')
            {
                $this->assertEquals('12:00', $openingTime);
                $this->assertEquals('17:00', $closingTime);
            }
            else
            {
                $this->assertEquals('08:00', $openingTime);
                $this->assertEquals('20:00', $closingTime);
            }

            $this->assertEquals('Open', $hoursSpec->getOpenStatus());
        }
    }

    /**
     * can parse Special Hours Closed
     */
    function testParseSpecialHoursClosed()
    {
        $graph = new EasyRdf_Graph('http://localhost/wclibhours/tests/sample-data/special-hours.rdf');
        $graph->load();
        $hoursSpec = $graph->resource('http://localhost/wclibhours/tests/sample-data/normal-hours.rdf#6972');
        
        $this->assertEquals('WorldCat\Registry\HoursSpec', get_class($hoursSpec));
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
        $graph = new EasyRdf_Graph('http://localhost/wclibhours/tests/sample-data/special-hours.rdf');
        $graph->load();
        $hoursSpec = $graph->resource('http://localhost/wclibhours/tests/sample-data/normal-hours.rdf#19096');
        
        $this->assertEquals('WorldCat\Registry\HoursSpec', get_class($hoursSpec));
        $this->assertEquals('2014-03-16T12:00:00', $hoursSpec->getStartDate());
        $this->assertEquals('2014-03-22T12:00:00', $hoursSpec->getEndDate());
        $this->assertEquals('Spring Break 2014', $hoursSpec->getDescription());
        $this->assertEquals('Open', $hoursSpec->getOpenStatus());
        $this->assertEquals('09:00', $hoursSpec->getOpeningTime());
        $this->assertEquals('17:00', $hoursSpec->getClosingTime());
    }
}