<?php
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
     * Get Name
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
     * Get Normal Hours Specifications
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
     * Get Special Hours Specifications
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
        $parent->load();
        return $parent;
    }
    
    /**
     * Binary for weather or not the organization is a branch
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
     * Sort the Normal Hours by day of the week
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
     * Sort the Special Hours by Date
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
     * Get the order of the day of the week
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