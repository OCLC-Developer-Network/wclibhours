<?php
use Models\Institution;

Class InstitutionTest extends \PHPUnit_Framework_TestCase {

	private $graph;

	function setUp(){
		EasyRdf_Namespace::set('schema', 'http://schema.org/');
        EasyRdf_Namespace::set('wcir', 'http://purl.org/oclc/ontology/wcir/');
        EasyRdf_TypeMapper::set('schema:Organization', 'Institution');
        EasyRdf_TypeMapper::set('wcir:hoursSpecification', 'HoursSpec');
        $this->graph = new EasyRdf_Graph();
        $this->graph->parseFile("sample-data/organization.rdf");
        $this->graph->parseFile("tests/sample-data/normal-hours.rdf");
        $this->graph->parseFile("tests/sample-data/special-hours.rdf");
	}
	
	/** can parse Organization */
	function testParse(){
	    $orgs = $graph->allOfType('schema:Organization');
	    $org = $orgs[0];
	    $this->assertEquals('Sandbox', $org->getName());
	    $this->assertNotNull($org->getNormalHoursSpecs());
	    $this->assertNotNull($org->getSortedSpecialHoursSpecs());

	}
}