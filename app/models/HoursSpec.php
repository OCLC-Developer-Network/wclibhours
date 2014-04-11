<?php
namespace WorldCat\Registry;

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
    
    function getStartDate($format= FALSE)
    {
        if ($format){
            return static::formatDateTimeAsDate($this->get('wcir:validFrom'));
        } else {
            return $this->get('wcir:validFrom');
        }
  
    }
    
    function getEndDate($format = FALSE)
    {
        if ($format){
            return static::formatDateTimeAsDate($this->get('wcir:validTo'));
        } else {
            return $this->get('wcir:validTo');
        }
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
    
    private static function formatDateTimeAsDate($dateTime) 
    {
        global $config;
        $dateTimeObject = new \DateTime($dateTime, new \DateTimeZone($config['timezone']));
        return $dateTimeObject->format($config['dateFormat']);
    }
}
?>