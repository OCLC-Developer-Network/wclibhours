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
            $this->graph->load($normalHours->getUri());
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
            $this->graph->load($specialHours->getUri());
            $specialHoursSpec = $specialHours;
            $hoursSpecs = $specialHoursSpec->all('wcir:hoursSpecifiedBy');
            $hoursSpecs = $this->sortSpecialHoursSpecs($hoursSpecs);
        }
        return $hoursSpecs;
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