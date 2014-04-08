<?php
namespace OCLC\WorldCatRegistryDemo\Models;

use \EasyRdf_Resource;

Class Institution extends EasyRdf_Resource
{
    function getName()
    {
        $names = $this->all('schema:name');
        $name = $names[0];
        return $name->getValue();
    }
    
    function getNormalHoursSpecs()
    {
        $normalHours = $this->all('wcir:normalHours');
        if (!empty($normalHours) && count($normalHours[0]->all('wcir:hoursSpecifiedBy')) == 0){
            Throw new \Exception('You must load the relevant normal hours into the graph');
        }
        
        if(empty($normalHours)) {
            $sortedHoursSpecs = array();
        } else {
            $sortedHoursSpecs = $this->sortNormalHoursSpecs($normalHours[0]);
        }
        return $sortedHoursSpecs;
    }

    function getSortedSpecialHoursSpecs()
    {
        $specialHours = $this->all('wcir:specialHours');
        if (!empty($specialHours) && count($specialHours[0]->all('wcir:hoursSpecifiedBy')) == 0){
            Throw new \Exception('You must load the relevant special hours into the graph');
        }
        
        if (empty($specialHours)){
            $hoursSpecs = array();
        } else {
            $specialHoursSpec = $specialHours[0];
            $hoursSpecs = $specialHoursSpec->all('wcir:hoursSpecifiedBy');
            $hoursSpecs = $this->sortSpecialHoursSpecs($hoursSpecs);
        }
        return $hoursSpecs;
    }
    
    private function sortNormalHoursSpecs($hoursResources)
    {
        $sortedHoursResources = array();
        foreach ($hoursResources->all('wcir:hoursSpecifiedBy') as $hoursSpec)
        {
            $dayOfWeek = HoursSpec::parseDayOfWeekFromUri($hoursSpec->getUri());
            $sortedHoursResources[static::getDayOrder($dayOfWeek)] = $hoursSpec;
        }
        return $sortedHoursResources;
    }

    private function sortSpecialHoursSpecs($specialHoursSpecs)
    {
        $sortedHoursSpecs = array();
        $hoursSpecsByStartDate = array();
        foreach ($specialHoursSpecs as $specialHoursSpec)
        {
            $startDateStr = $specialHoursSpec->getStartDate()->getValue();
            $hoursSpecsByStartDate[$startDateStr] = $specialHoursSpec;
        }
        
        $dates = array_keys($hoursSpecsByStartDate);
        sort($dates);
        $i = 0;
        foreach ($dates as $date)
        {
            $sortedHoursSpecs[$i] = $hoursSpecsByStartDate[$date];
            $i++;
        }
        return $sortedHoursSpecs;
    }
    
    private static function getDayOrder($dayOfWeek)
    {
        global $config;
        $days = $config['dayOrder'];
        return $days[$dayOfWeek];
    }
}
?>