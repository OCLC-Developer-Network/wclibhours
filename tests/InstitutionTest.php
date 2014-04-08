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
	    $this->assertCount(7, $org->getNormalHoursSpecs());
	    $normalHours = $org->getNormalHoursSpecs();
	    $this->assertInstanceOf('OCLC\WorldCatRegistryDemo\Models\HoursSpec', $normalHours[1]);
	    $SundayHours = $normalHours[1];
	    $this->assertEquals('Sunday', $SundayHours->getDayOfWeek());
	    
	    $this->assertNotNull($org->getSortedSpecialHoursSpecs());
	    $this->assertCount(2, $org->getSortedSpecialHoursSpecs());
	    $specialHours = $org->getSortedSpecialHoursSpecs();
	    $this->assertInstanceOf('OCLC\WorldCatRegistryDemo\Models\HoursSpec', $specialHours[0]);
	    
	}
	
	/** Load Organization but not Normal Hours part of graph 
	 * @expectedException Exception
     * @expectedExceptionMessage You must load the relevant normal hours into the graph
	 * 
	 */
	function testParseNoLoadNormalHoursGraph(){
	    $this->graphOrgOnly = new EasyRdf_Graph();
	    $this->graphOrgOnly->parseFile("sample-data/organization.rdf");
	    $orgs = $this->graphOrgOnly->allOfType('schema:Organization');
	    $org = $orgs[0];
	    $org->getNormalHoursSpecs();
	}
	
	/** Load Organization but not Special Hours part of graph
	 * @expectedException Exception
	 * @expectedExceptionMessage You must load the relevant special hours into the graph
	 *
	 */
	function testParseNoLoadSpecialHoursGraph(){
	    $this->graphOrgOnly = new EasyRdf_Graph();
	    $this->graphOrgOnly->parseFile("sample-data/organization.rdf");
	    $orgs = $this->graphOrgOnly->allOfType('schema:Organization');
	    $this->graphOrgOnly->parseFile("sample-data/normal-hours.rdf");
	    $org = $orgs[0];
	    $org->getSortedSpecialHoursSpecs();
	}
	
	/** Organization with no special hours */
	function testParseNoSpecialHoursHoursGraph(){
	    $this->graphOrgOnly = new EasyRdf_Graph();
	    $this->graphOrgOnly->parseFile("sample-data/organizationNoSpecialHours.rdf");
	    $this->graphOrgOnly->parseFile("sample-data/normal-hours.rdf");
	    $orgs = $this->graphOrgOnly->allOfType('schema:Organization');
	    $org = $orgs[0];
	    $this->assertEquals('OCLC WorldShare Platform Sandbox Institution', $org->getName());
	
	    $this->assertNotNull($org->getNormalHoursSpecs());
	    $this->assertCount(7, $org->getNormalHoursSpecs());
	    $normalHours = $org->getNormalHoursSpecs();
	    $this->assertInstanceOf('OCLC\WorldCatRegistryDemo\Models\HoursSpec', $normalHours[1]);
	    $SundayHours = $normalHours[1];
	    $this->assertEquals('Sunday', $SundayHours->getDayOfWeek());
	
	    $this->assertEmpty($org->getSortedSpecialHoursSpecs());
	     
	}
	
	/** Organization with no hours */
	function testParseNoHoursGraph(){
	    $this->graphOrgNoHours = new EasyRdf_Graph();
	    $this->graphOrgNoHours->parseFile("sample-data/organizationNoHours.rdf");
	    $orgs = $this->graphOrgNoHours->allOfType('schema:Organization');
	    $org = $orgs[0];
	    $this->assertEquals('OCLC WorldShare Platform Sandbox Institution', $org->getName());
	
	    $this->assertEmpty($org->getNormalHoursSpecs());
	    $this->assertEmpty($org->getSortedSpecialHoursSpecs());
	}
}