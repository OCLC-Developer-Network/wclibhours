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

/**
 * A class that represents an Hours Specification in the WorldCat Registry
 *
 */
class HoursSpec extends EasyRdf_Resource
{

    /**
     * Get Day of the week
     *
     * @return string
     */
    function getDayOfWeek()
    {
        return $this::parseDayOfWeekFromUri($this->getUri());
    }
    
    /**
     * Get Opening Time
     *
     * @return EasyRdf_Literal
     */

    function getOpeningTime()
    {
        return $this->get('wcir:opens');
    }

    /**
     * Get Closing time
     *
     * @return EasyRdf_Literal
     */
    function getClosingTime()
    {
        return $this->get('wcir:closes');
    }
    
    /**
     * Get Description
     *
     * @return EasyRdf_Literal
     */

    function getDescription()
    {
        return $this->get('wcir:description');
    }
    
    /**
     * Get Start Date
     *
     * @return EasyRdf_Literal
     */

    function getStartDate($format = FALSE)
    {
        if ($format) {
            return static::formatDateTimeAsDate($this->get('wcir:validFrom'));
        } else {
            return $this->get('wcir:validFrom');
        }
    }
    
    /**
     * Get End Date
     *
     * @return EasyRdf_Literal
     */

    function getEndDate($format = FALSE)
    {
        if ($format) {
            return static::formatDateTimeAsDate($this->get('wcir:validTo'));
        } else {
            return $this->get('wcir:validTo');
        }
    }
    
    /**
     * Get Open Status
     *
     * @return EasyRdf_Literal
     */

    function getOpenStatus()
    {
        return $this->get('wcir:openStatus');
    }
    
    /**
     * Retrieve the Day of the week from a URI
     * @var string 
     * @return string
     */

    static function parseDayOfWeekFromUri($uri)
    {
        $dayOfWeek = substr($uri, strpos($uri, "#") + 1);
        return $dayOfWeek;
    }
    
    /**
     * Format the Date
     * @var string
     * @return string
     */

    private static function formatDateTimeAsDate($dateTime)
    {
        global $config;
        $dateTimeObject = new \DateTime($dateTime, new \DateTimeZone($config['timezone']));
        return $dateTimeObject->format($config['dateFormat']);
    }
}