<?php
use Models\HoursSpec;

Class HoursSpecTest extends \PHPUnit_Framework_TestCase {

	private $graph;

	function setUp(){
		EasyRdf_Namespace::set('schema', 'http://schema.org/');
        EasyRdf_Namespace::set('wcir', 'http://purl.org/oclc/ontology/wcir/');
        EasyRdf_TypeMapper::set('wcir:hoursSpecification', 'HoursSpec');
        
	}
	
	/** can parse Normal Hours */
	function testParseNormalHours(){
	    $this->graph = new EasyRdf_Graph();
	    $this->graph->parseFile("tests/sample-data/normal-hours.rdf");
	    $hoursSpecs = $graph->allOfType('wcir:hoursSpecification');
	    $hoursSpec = $hoursSpecs[0];
	    $this->assertNotEmpty($hoursSpec->getDayOfWeek());
	    // want to check to see this is a real day of the week array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')
	    
	    $this->assertNotEmpty($hoursSpec->getOpeningTime());
	    $this->assertNotEmpty($hoursSpec->getClosingTime());
	    $this->assertNotEmpty($hoursSpec->getOpenStatus());
	}
	
	/** can parse Special Hours */
	function testParseSpecialHours(){
	    $this->graph = new EasyRdf_Graph();
	    $this->graph->parseFile("tests/sample-data/special-hours.rdf");
	    $hoursSpecs = $graph->allOfType('wcir:hoursSpecification');
	    $hoursSpec = $hoursSpecs[0];
	    $this->assertNotEmpty($hoursSpec->getStartDate());
	    $this->assertNotEmpty($hoursSpec->getEndDate());
	    $this->assertNotEmpty($hoursSpec->getDescription());
	    $this->assertNotEmpty($hoursSpec->getOpeningTime());
	    $this->assertNotEmpty($hoursSpec->getClosingTime());
	    $this->assertNotEmpty($hoursSpec->getOpenStatus());
	}
}