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
class Organization extends EasyRdf_Resource
{ 
	
    /**
     * Return the name for the current Organization.
     *
     * @return string
     */
    function getName()
    {
        $names = $this->all('schema:name');
        $name = $names[0];
        return $name->getValue();
    }
    
    /**
     * Get the normal hours specification data for a library in the order 
     * specified by the configuration. Sorting is configured by day of the 
     * week.
     *
     * @return array
     */
    function getNormalHoursSpecs()
    {
        $normalHours = $this->getResource('wcir:normalHours');
        
        if (empty($normalHours)) {
            $sortedHoursSpecs = array();
        } else {
            $normalHours->load();
            $sortedHoursSpecs = $this->sortNormalHoursSpecs($normalHours);
        }
        return $sortedHoursSpecs;
    }
    
    /**
     * Get the special hours specification data for a library. Special hours
     * specs are exceptions that override normal hours. The data is returned
     * in sorted order according to the date to which it applies.
     *
     * @return array
     */

    function getSortedSpecialHoursSpecs()
    {
        $specialHours = $this->getResource('wcir:specialHours');
        
        if (empty($specialHours)) {
            $hoursSpecs = array();
        } else {
            $specialHours->load();
            $specialHoursSpec = $specialHours;
            $hoursSpecs = $specialHoursSpec->all('wcir:hoursSpecifiedBy');
            $hoursSpecs = $this->sortSpecialHoursSpecs($hoursSpecs);
        }
        return $hoursSpecs;
    }
    
    /**
     * Get the branches of the library
     */
    
    function getSortedBranches()
    {
        $branches = $this->allResources('wcir:hasBranch');
        
        $fullGraph = new EasyRdf_Graph();
        $fullGraph->load($this->getUri());
        if ($branches){
            foreach ($branches as $branch){
                $fullGraph->load($branch->getUri());
            }
        }
        
        $this->graph = $fullGraph;

        $branches = $this->allResources('wcir:hasBranch');
    
        return $this->sortBranches($branches);
    }
    
    /**
     * Get the parent of a branch
     */
    
    function getBranchParent() 
    {
        $parent = $this->getResource('schema:branchOf');
        $fullGraph = new EasyRdf_Graph();
        $fullGraph->load($this->getUri());
        $fullGraph->load($parent->getUri());
        
        $this->graph = $fullGraph;

        $parent = $this->getResource('schema:branchOf');
    
        return $parent;
    }
    
    /**
     * Binary for whether or not the organization is a branch
     */
    
    function isBranch()
    {
        if ($this->getResource('schema:branchOf')){
            $isBranch = TRUE;
        }else{
            $isBranch = FALSE;
        }    
        return $isBranch;
    }
    
    /**
     * Get an array of addresses
     * @return array
     */
    
    function getAddresses(){
    	$addresses = $this->allResources('schema:location');
    	
    	$fullGraph = new EasyRdf_Graph();
        $fullGraph->load($this->getUri());
        if ($addresses){
            foreach ($addresses as $address){
                $fullGraph->load($address->getUri());
            }
        }
        
        $this->graph = $fullGraph;
        
        $addresses = $this->allResources('schema:location');
    	
    	return $addresses;
    }
    
    /**
     * Get a WorldCat/Registry/Address object
     * @return WorldCat/Registry/Address
     */

    function getMainAddress(){
    	$addresses = self::getAddresses();
    	
    	$mainAddress = array_filter($addresses, function($address){
    		return(in_array('wcir:Main-Address', $address->types()));
    	});
    	
    	return $mainAddress[0];
    }
    
    /**
     * Sort the normal hours by day of the week.
     *
     * @var array 
     * @return array
     */
    private function sortNormalHoursSpecs($hoursResources)
    {
        $sortedHoursResources = array();
        foreach ($hoursResources->all('wcir:hoursSpecifiedBy') as $hoursSpec) {
            $dayOfWeek = HoursSpec::parseDayOfWeekFromUri($hoursSpec->getUri());
            $sortedHoursResources[static::getDayOrder($dayOfWeek)] = $hoursSpec;
        }
        return $sortedHoursResources;
    }

    /**
     * Sort the special hours by date
     *
     * @var array
     * @return array
     */
    private function sortSpecialHoursSpecs($specialHoursSpecs)
    {
        $sortedHoursSpecs = array();
        $hoursSpecsByStartDate = array();
        foreach ($specialHoursSpecs as $specialHoursSpec) {
            $startDateStr = $specialHoursSpec->getStartDate()->getValue();
            $hoursSpecsByStartDate[$startDateStr] = $specialHoursSpec;
        }
        
        $dates = array_keys($hoursSpecsByStartDate);
        sort($dates);
        $i = 0;
        foreach ($dates as $date) {
            $sortedHoursSpecs[$i] = $hoursSpecsByStartDate[$date];
            $i ++;
        }
        return $sortedHoursSpecs;
    }

    /**
     * Sort the Branches by Name
     *
     * @var array
     * @return array
     */
    
    private function sortBranches($branches)
    {
        usort($branches, function($a, $b) {
            return strcmp($a->getName(), $b->getName());
        });
        
        return $branches;
    }
    
    /**
     * Get the order of the day of the week as configured by the global YAML file
     *
     * @var string
     * @return integer
     */
    private static function getDayOrder($dayOfWeek)
    {
        global $config;
        $days = $config['dayOrder'];
        return $days[$dayOfWeek];
    }
}
