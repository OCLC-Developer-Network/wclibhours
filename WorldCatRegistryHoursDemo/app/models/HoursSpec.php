<?php
namespace OCLC\WorldCatRegistryDemo\Models;

use \EasyRdf_Resource;

class HoursSpec extends EasyRdf_Resource
{
    function getDayOfWeek()
    {
        return $this::parseDayOfWeekFromUri($this->getUri());
    }
    
    function getOpeningTime()
    {
        return $this->get('wcir:opens');
    }

    function getClosingTime()
    {
        return $this->get('wcir:closes');
    }

    function getDescription()
    {
        return $this->get('wcir:description');
    }
    
    function getStartDate($format = null)
    {   if (empty($format)){
            $format = 'F j, Y';
        }
        return static::formatDateTimeAsDate($this->get('wcir:validFrom'), $format);
    }
    
    function getEndDate($format = null)
    {
        if (empty($format)){
            $format = 'F j, Y';
        }
        return static::formatDateTimeAsDate($this->get('wcir:validTo'), $format);
    }

    function getOpenStatus()
    {
        return $this->get('wcir:openStatus');
    }
    
    static function parseDayOfWeekFromUri($uri)
    {
        $dayOfWeek = substr($uri, strpos($uri, "#")+1);
        return $dayOfWeek;
    }
    
    private static function formatDateTimeAsDate($dateTime, $format) 
    {
        $dateTimeObject = new DateTime($dateTime, new DateTimeZone('America/New_York')); // needs to come from YAML
        return $dateTimeObject->format($format);
    }
}
?>