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
     * Get day of the week from the URI for this hours specification.
     *
     * @return string
     */
    function getDayOfWeek()
    {
        return $this::parseDayOfWeekFromUri($this->getUri());
    }
    
    /**
     * Get the opening time for the current hours specification.
     *
     * @return EasyRdf_Literal
     */
    function getOpeningTime()
    {
        return $this->get('wcir:opens');
    }

    /**
     * Get the closing time for the current hours specification.
     *
     * @return EasyRdf_Literal
     */
    function getClosingTime()
    {
        return $this->get('wcir:closes');
    }
    
    /**
     * Get the opening time for the current hours specification. A description 
     * may be a free text value like 'Spring Break'.
     *
     * @return EasyRdf_Literal
     */
    function getDescription()
    {
        return $this->get('wcir:description');
    }
    
    /**
     * Get the start date for the current hours specification.
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
     * Get the end date for the current hours specification.
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
     * Get the open status for the current hours specification. Values 
     * typically include 'Open' or 'Closed'
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
     * Format the date. The format is specified in the global configuration.
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