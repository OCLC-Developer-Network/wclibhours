<?php
use WorldCat\Registry\Organization;

class OrganizationTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        EasyRdf_Format::unregister('json');
        EasyRdf_Namespace::set('schema', 'http://schema.org/');
        EasyRdf_Namespace::set('wcir', 'http://purl.org/oclc/ontology/wcir/');
        EasyRdf_TypeMapper::set('schema:Organization', 'WorldCat\Registry\Organization');
        EasyRdf_TypeMapper::set('wcir:hoursSpecification', 'WorldCat\Registry\HoursSpec');
    }

    /**
     * can parse Organization
     */
    function testParse()
    {
        $graph = new EasyRdf_Graph("http://localhost/wclibhours/tests/sample-data/organization.rdf");
        $graph->load();
        $org = $graph->resource('http://localhost/wclibhours/tests/sample-data/organization.rdf');
        
        $this->assertInstanceOf('WorldCat\Registry\Organization', $org);

        $this->assertEquals('OCLC WorldShare Platform Sandbox Institution', $org->getName());
        $this->assertNotNull($org->getNormalHoursSpecs());
        $this->assertCount(7, $org->getNormalHoursSpecs());
        
        $normalHours = $org->getNormalHoursSpecs();
        $this->assertCount(7, $normalHours);
        foreach ($normalHours as $hoursSpec)
        {
            $this->assertInstanceOf('WorldCat\Registry\HoursSpec', $hoursSpec);
        }
        
        $specialHours = $org->getSortedSpecialHoursSpecs();
        $this->assertNotNull($specialHours);
        $this->assertCount(2, $specialHours);
        foreach ($specialHours as $hoursSpec)
        {
            $this->assertInstanceOf('WorldCat\Registry\HoursSpec', $hoursSpec);
        }
    }

    /**
     * Organization with no special hours
     */
    function testParseNoSpecialHoursHoursGraph()
    {
        $graph = new EasyRdf_Graph("http://localhost/wclibhours/tests/sample-data/organizationNoSpecialHours.rdf");
        $graph->load();
        $org = $graph->resource('http://localhost/wclibhours/tests/sample-data/organizationNoSpecialHours.rdf');
        
        $this->assertInstanceOf('WorldCat\Registry\Organization', $org);
        $this->assertEmpty($org->getSortedSpecialHoursSpecs());
    }
    
    /**
     * Organization with no hours
     */
    function testParseNoHoursGraph()
    {
        $graph = new EasyRdf_Graph("http://localhost/wclibhours/tests/sample-data/organizationNoHours.rdf");
        $graph->load();
        $org = $graph->resource('http://localhost/wclibhours/tests/sample-data/organizationNoHours.rdf');

        $this->assertInstanceOf('WorldCat\Registry\Organization', $org);
        $this->assertEmpty($org->getNormalHoursSpecs());
        $this->assertEmpty($org->getSortedSpecialHoursSpecs());
    }
}