<html>
<head>
<title><?php print $org->getName(); ?></title>
  <style type="text/css">
  body { font-family: Helvetica, Verdana, sans-serif; margin: 2em 15%; }
  </style>
</head>
<body>
<h1><?php print $org->getName(); ?></h1>

<?php if (!empty($sortedHoursSpecs)):?>
    <h2>Normal Hours</h2>
    <?php
    foreach (range(1, 7) as $number) 
    {
        print "<p>";
        $hoursSpec = $sortedHoursSpecs[$number];
        print '<strong>' . $hoursSpec->getDayOfWeek() . ':</strong> ';
        print $hoursSpec->getOpeningTime() . ' ';
        print $hoursSpec->getClosingTime() . ' ';
        print "</p>\n";
    }
endif;
?>

<?php if (!empty($sortedSpecialHoursSpecs)):?>
    <h2>Breaks &amp; Holidays</h2>
    <?php
    foreach($sortedSpecialHoursSpecs as $hoursSpec)
    {
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
endif;
?>
</body>
</html>
