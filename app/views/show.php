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

?>
<html>
<head>
    <title><?php print $org->getName(); ?></title>
    <style type="text/css">
        body {
        	font-family: Helvetica, Verdana, sans-serif;
        	margin: 2em 15%;
        }
    </style>
</head>
<body>

<h1><?php print $org->getName(); ?></h1>

<?php
$sortedHoursSpecs = $org->getNormalHoursSpecs();
$sortedSpecialHoursSpecs = $org->getSortedSpecialHoursSpecs();

if (! empty($sortedHoursSpecs)) {
    print "<h2>Normal Hours</h2>";
    foreach (range(1, 7) as $number) {
        print "<p>";
        $hoursSpec = $sortedHoursSpecs[$number];
        print "<strong>" . $hoursSpec->getDayOfWeek() . ":</strong> ";
        print $hoursSpec->getOpeningTime() . ' ';
        print $hoursSpec->getClosingTime() . ' ';
        print "</p>\n";
    }
}
?>

<?php

if (! empty($sortedSpecialHoursSpecs)) {
    print "<h2>Breaks &amp; Holidays</h2>";
    foreach ($sortedSpecialHoursSpecs as $hoursSpec) {
        print "<h3>" . $hoursSpec->getDescription() . "</h3>\n";
        print "<p>";
        print $hoursSpec->getStartDate(TRUE);
        if ($hoursSpec->getEndDate() != $hoursSpec->getStartDate())
            print ' - ' . $hoursSpec->getEndDate(TRUE) . ' ';
        print "</p>\n";
        print "<p>";
        print $hoursSpec->getOpenStatus() . ' ';
        print $hoursSpec->getOpeningTime() . ' ';
        print $hoursSpec->getClosingTime() . ' ';
        print "</p>";
        print "\n\n";
    }
}
?>
</body>
</html>
