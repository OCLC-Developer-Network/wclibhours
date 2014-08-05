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

namespace WorldCat\Registry;

use \EasyRdf_Resource;
use \EasyRdf_Format;
use \EasyRdf_Graph;

/**
 * A class that represents an Organization in the WorldCat Registry
 *
 */
class Address extends EasyRdf_Resource
{ 
	
    /**
     * Return the street Address for the current Address.
     *
     * @return string
     */
    function getStreetAddress()
    {
        $streetAddress = $this->get('schema:streetAddress');
        return $streetAddress->getValue();
    }
    
    /**
     * Return the City for the current Address.
     *
     * @return string
     */
    function getCity()
    {
    	$city = $this->get('schema:addressLocality');
    	return $city->getValue();
    }
    
    /**
     * Return the State for the current Address.
     *
     * @return string
     */
    function getState()
    {
    	$state = $this->get('schema:addressRegion');
    	$state = substr($state->getValue(), strpos($state->getValue(), "-") + 1);
    	return $state;
    }
    
    /**
     * Return the Postal Code for the current Address.
     *
     * @return string
     */
    function getPostalCode()
    {
    	$postalCode = $this->get('schema:postalCode');
    	return $postalCode->getValue();
    }
    
    /**
     * Return the Country for the current Address.
     *
     * @return string
     */
    function getCountry()
    {
    	$country = $this->get('schema:addressCountry');
    	return $country->getValue();
    }
    
    /**
     * Return the Address type for the current Address.
     *
     * @return string
     */
    function getAddressType()
    {
		$type = array_filter($this->types(), function($type)
		{
			if (strpos($type, 'wcir:') !== false) {
				$present = true;
			} else {
				$present = false;
			}
			return($present);
		});
		$type = substr(current($type), strpos(current($type), ":")+1);
    	return $type;
    }
    
}
