<?php

/*
    Copyright 2014 OCLC

    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.
*/

use WorldCat\Registry\Organization;

class OrganizationTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        EasyRdf_Format::unregister('json');
        EasyRdf_Namespace::set('schema', 'http://schema.org/');
        EasyRdf_Namespace::set('wcir', 'http://purl.org/oclc/ontology/wcir/');
        EasyRdf_TypeMapper::set('schema:Organization', 'WorldCat\Registry\Organization');
        EasyRdf_TypeMapper::set('schema:PostalAddress', 'WorldCat\Registry\Address');
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
        return $org;
    }
    
    /**
     * @depends testParse
     */
    
    function testNormalHours($org){
        $normalHours = $org->getNormalHoursSpecs();
        
        $this->assertNotNull($normalHours);
        $this->assertCount(7, $normalHours);
        foreach (range(1, 7) as $number)
        {
            // Verify that each normal hours object is an HoursSpec
            $this->assertInstanceOf('WorldCat\Registry\HoursSpec', $normalHours[$number]);
        
            // Verify the sort order based on the test_config.yml file
            if ($number == 1) $this->assertEquals('Monday', $normalHours[$number]->getDayOfWeek());
            else if ($number == 2) $this->assertEquals('Tuesday', $normalHours[$number]->getDayOfWeek());
            else if ($number == 3) $this->assertEquals('Wednesday', $normalHours[$number]->getDayOfWeek());
            else if ($number == 4) $this->assertEquals('Thursday', $normalHours[$number]->getDayOfWeek());
            else if ($number == 5) $this->assertEquals('Friday', $normalHours[$number]->getDayOfWeek());
            else if ($number == 6) $this->assertEquals('Saturday', $normalHours[$number]->getDayOfWeek());
            else if ($number == 7) $this->assertEquals('Sunday', $normalHours[$number]->getDayOfWeek());
        }
    }
    
    /**
     * @depends testParse
     */
    
    function testSpecialHours($org){
        $specialHours = $org->getSortedSpecialHoursSpecs();
        $this->assertNotNull($specialHours);
        $this->assertCount(2, $specialHours);
        foreach ($specialHours as $hoursSpec)
        {
            $this->assertInstanceOf('WorldCat\Registry\HoursSpec', $hoursSpec);
        }
    }
    
    /**
     * @depends testParse
     */
    
    function testBranches($org){
        $branches = $org->getSortedBranches();
        $this->assertNotNull($branches);
        $this->assertCount(4, $branches);
        
        foreach (range(0, 3) as $number)
        {
            // Verify that each branch is an Organization
            $this->assertInstanceOf('WorldCat\Registry\Organization', $branches[$number]);
        
            // Verify the sort order based on the test_config.yml file
            if ($number == 0) $this->assertEquals('COP Sandbox - East Branch', $branches[$number]->getName());
            else if ($number == 1) $this->assertEquals('COP Sandbox - North Branch', $branches[$number]->getName());
            else if ($number == 2) $this->assertEquals('COP Sandbox - South Branch', $branches[$number]->getName());
            else if ($number == 3) $this->assertEquals('COP Sandbox - West Branch', $branches[$number]->getName());
        }
    }
    
    /**
     *@depends testParse
     */
    function testGetAddresses($org){
    	$addresses = $org->getAddresses();
    	$this->assertNotEmpty($addresses);
    	
    	foreach ($addresses as $address){
    		$this->assertInstanceOf('WorldCat\Registry\Address', $address);
    	}
    }
    
    /**
     * Test to getMainAddress
     * @depends testParse
     */
    function testGetMainAddress($org)
    {
    	$mainAddress = $org->getMainAddress();
    	$this->assertInstanceOf('WorldCat\Registry\Address', $mainAddress);
    	$this->assertContains('wcir:Main-Address', $mainAddress->types());
    }
    
    /**
     * Organization which is a branch
     */
    
    function testBranch(){
        $graph = new EasyRdf_Graph("http://localhost/wclibhours/tests/sample-data/organization_129055.rdf");
        $graph->load();
        $org = $graph->resource('http://localhost/wclibhours/tests/sample-data/organization_129055.rdf');
    
        $this->assertInstanceOf('WorldCat\Registry\Organization', $org);
        $this->assertEmpty($org->getSortedBranches());
        $this->assertNotEmpty($org->getBranchParent());
        $this->assertEmpty($org->getSortedBranches());
        $this->assertNotEmpty($org->getBranchParent()->getSortedBranches());
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
