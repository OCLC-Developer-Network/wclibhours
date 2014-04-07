<?php
namespace Models;

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
    
    function getStartDate()
    {
        return $this->get('wcir:validFrom');
    }
    
    function getEndDate()
    {
        return $this->get('wcir:validTo');
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
}
?>