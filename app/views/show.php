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

	<form action="index.php" method="get">
    <select name="id">
    <?php             
    foreach ($branches as $branch){
        print "<option value=\"" . $branch->getUri() . "\"". ($branch->getUri() == $org->getUri() ? " selected" : "") . ">" . $branch->getName() . "</option>\n";
    }
    ?>
    </select>
    <input type="submit" value="Submit">
    </form>
	
<?php
$sortedHoursSpecs = $org->getNormalHoursSpecs();
$sortedSpecialHoursSpecs = $org->getSortedSpecialHoursSpecs();

if (! empty($sortedHoursSpecs)) {
    print "<div id=\"normalHours\">\n";
    print "<h2>Normal Hours</h2>\n";
    foreach (range(1, 7) as $number) {
        print "<p>";
        $hoursSpec = $sortedHoursSpecs[$number];
        print "<strong>" . $hoursSpec->getDayOfWeek() . ":</strong> ";
        
        if($hoursSpec->getOpenStatus() == 'Open24Hours') {
            print "Open 24 Hours";
        }elseif ($hoursSpec->getOpenStatus() == 'Open'){
            print $hoursSpec->getOpeningTime() . ' ';
            print $hoursSpec->getClosingTime() . ' ';
        }else {
            print "Closed";
        }
        print "</p>\n";
    }
    print "</div>";
} else {
    print "<p>There are no hours for this institution</p>";
}
?>

<?php

if (! empty($sortedSpecialHoursSpecs)) {
    print "<div id=\"specialHours\">\n";
    print "<h2>Breaks &amp; Holidays</h2>\n";
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
        print "\n";
    }
    print "</div>\n";
}
?>
</body>
</html>
