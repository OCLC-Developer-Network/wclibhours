<?php
use WorldCat\Registry\Organization;

class OrganizationTest extends \PHPUnit_Framework_TestCase
{

    private $graph;

    function setUp()
    {
        EasyRdf_Format::unregister('json');
        EasyRdf_Namespace::set('schema', 'http://schema.org/');
        EasyRdf_Namespace::set('wcir', 'http://purl.org/oclc/ontology/wcir/');
        EasyRdf_TypeMapper::set('schema:Organization', 'WorldCat\Registry\Organization');
        EasyRdf_TypeMapper::set('wcir:hoursSpecification', 'WorldCat\Registry\HoursSpec');
        $this->graph = new EasyRdf_Graph();
        $this->graph->parseFile("sample-data/organization.rdf");
    }

    /**
     * can parse Organization
     */
    function testParse()
    {
        $orgs = $this->graph->allOfType('schema:Organization');
        $org = $orgs[0];
        $this->assertEquals('OCLC WorldShare Platform Sandbox Institution', $org->getName());
        
        $this->assertNotNull($org->getNormalHoursSpecs());
        $this->assertCount(7, $org->getNormalHoursSpecs());
        $normalHours = $org->getNormalHoursSpecs();
        $this->assertInstanceOf('WorldCat\Registry\HoursSpec', $normalHours[1]);
        $SundayHours = $normalHours[1];
        $this->assertEquals('Sunday', $SundayHours->getDayOfWeek());
        
        $this->assertNotNull($org->getSortedSpecialHoursSpecs());
        $this->assertCount(2, $org->getSortedSpecialHoursSpecs());
        $specialHours = $org->getSortedSpecialHoursSpecs();
        $this->assertInstanceOf('WorldCat\Registry\HoursSpec', $specialHours[0]);
    }

    /**
     * Organization with no special hours
     */
    function testParseNoSpecialHoursHoursGraph()
    {
        $this->graphOrgOnly = new EasyRdf_Graph();
        $this->graphOrgOnly->parseFile("sample-data/organizationNoSpecialHours.rdf");
        $orgs = $this->graphOrgOnly->allOfType('schema:Organization');
        $org = $orgs[0];
        $this->assertEquals('OCLC WorldShare Platform Sandbox Institution', $org->getName());
        
        $this->assertNotNull($org->getNormalHoursSpecs());
        $this->assertCount(7, $org->getNormalHoursSpecs());
        $normalHours = $org->getNormalHoursSpecs();
        $this->assertInstanceOf('WorldCat\Registry\HoursSpec', $normalHours[1]);
        $SundayHours = $normalHours[1];
        $this->assertEquals('Sunday', $SundayHours->getDayOfWeek());
        
        $this->assertEmpty($org->getSortedSpecialHoursSpecs());
    }

    /**
     * Organization with no hours
     */
    function testParseNoHoursGraph()
    {
        $this->graphOrgNoHours = new EasyRdf_Graph();
        $this->graphOrgNoHours->parseFile("sample-data/organizationNoHours.rdf");
        $orgs = $this->graphOrgNoHours->allOfType('schema:Organization');
        $org = $orgs[0];
        $this->assertEquals('OCLC WorldShare Platform Sandbox Institution', $org->getName());
        
        $this->assertEmpty($org->getNormalHoursSpecs());
        $this->assertEmpty($org->getSortedSpecialHoursSpecs());
    }
}