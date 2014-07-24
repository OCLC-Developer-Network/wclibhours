Feature: View Main Branch Hours
  As a library patron
  I want to view library hours for the main library branch
  so that I can visit the library when it is open
  
  Scenario: Successfully View Open Hours
    When I go to "/index.php"
    Then I should see an "h1" element
    And I should see "OCLC WorldShare Platform Sandbox Institution" in the "h1" element
    And I should see 2 "h2" elements
    And I should see "Normal Hours" in the "div#normalHours h2" element
    And I should see 7 "div#normalHours p" elements
    And I should see the following in the repeated "p" element within the context of the "div#normalHours" element:
	| text  |
	| Sunday: 12:00 17:00 |
	| Monday: 08:00 20:00 |
	| Tuesday: 08:00 20:00 |
	| Wednesday: 08:00 20:00 |
    | Thursday: 08:00 20:00 |
    | Friday: 08:00 17:00 |
    | Saturday: 10:00 17:00 |
    
    
  Scenario: Successfully View Special Hours
    When I go to "/index.php"
    Then I should see an "h1" element
    And I should see "OCLC WorldShare Platform Sandbox Institution" in the "h1" element
    And I should see "Breaks & Holidays" in the "div#specialHours h2" element
    And I should see 2 "h3" elements
    And I should see the following in the repeated "h3" element within the context of the "div#specialHours" element:
    | text  |
    | Christmas |
    | Spring Break 2014 |
    And I should see 4 "div#specialHours p" elements
    And I should see the following in the repeated "p" element within the context of the "div#specialHours" element:
    | text  |
    | December 25, 2013 |
    | Closed |
    | March 16, 2014 - March 22, 2014 |
    | Open 09:00 17:00 |