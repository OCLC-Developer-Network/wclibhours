<?php
use Models\Institution;

Class InstitutionTest extends \PHPUnit_Framework_TestCase {

	private $graph;

	function setUp(){
		EasyRdf_Namespace::set('schema', 'http://schema.org/');
        EasyRdf_Namespace::set('wcir', 'http://purl.org/oclc/ontology/wcir/');
        EasyRdf_TypeMapper::set('schema:Organization', 'OCLC\WorldCatRegistryDemo\Models\Institution');
        EasyRdf_TypeMapper::set('wcir:hoursSpecification', 'OCLC\WorldCatRegistryDemo\Models\HoursSpec');
        $this->graph = new EasyRdf_Graph();
        $this->graph->parseFile("sample-data/organization.rdf");
        $this->graph->parseFile("sample-data/normal-hours.rdf");
        $this->graph->parseFile("sample-data/special-hours.rdf");
	}
	
	/** can parse Organization */
	function testParse(){
	    $orgs = $this->graph->allOfType('schema:Organization');
	    $org = $orgs[0];
	    $this->assertEquals('OCLC WorldShare Platform Sandbox Institution', $org->getName());
	    $this->assertNotNull($org->getNormalHoursSpecs());
	    $this->assertNotNull($org->getSortedSpecialHoursSpecs());

	}
}