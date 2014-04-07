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
	    $this->graph->parseFile("tests/sample-data/special-hours.rdf");
	    
	    $this->assertEquals('Sandbox', $org->getName());
	}
	
	/** can parse Special Hours */
	function testParseSpecialHours(){
	    $this->graph = new EasyRdf_Graph();
	    $this->graph->parseFile("tests/sample-data/special-hours.rdf");
	}
}