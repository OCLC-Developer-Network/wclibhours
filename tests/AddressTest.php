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

use WorldCat\Registry\Address;

class AddressTest extends \PHPUnit_Framework_TestCase
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
     * can parse Address
     */
    function testParse()
    {
        $graph = new EasyRdf_Graph("http://localhost/wclibhours/tests/sample-data/address_614816.rdf");
        $graph->load();
        $address = $graph->resource('http://localhost/wclibhours/tests/sample-data/address_614816.rdf');
        
        $this->assertInstanceOf('WorldCat\Registry\Address', $address);
        return $address;
    }
    
    /**
     * @depends testParse
     */
    
    function testAddressInfo($address){
        
        $this->assertEquals('6565 Kilgour Place', $address->getStreetAddress());
        $this->assertEquals('Dublin', $address->getCity());
        $this->assertEquals('OH', $address->getState());
        $this->assertEquals('43017', $address->getPostalCode());
        $this->assertEquals('US', $address->getCountry());
        $this->assertNotNull($address->types());
    }
    
    /**
     * @depends testParse
     */
    function testAddressType($address){
    	$this->assertEquals('Main-Address', $address->getAddressType());
    }
    
}
