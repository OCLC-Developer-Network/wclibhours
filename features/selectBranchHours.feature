Feature: Select a library branch with hours
  As a library patron
  I want select from a list of library branches that have hours
  so that I can view that particular branches hours
  
  Scenario: Successfully View Branches with Hours
  When I go to "/index.php?id=http%3A%2F%2Flocalhost%2Fwclibhours%2Ftests%2Fsample-data%2Forganization_129057.rdf"
  Then I should see an "h1" element
    And I should see "COP Sandbox - East Branch" in the "h1" element
    And I should see 1 "form" element
    And I should see that "COP Sandbox - East Branch" in "id" is selected
    And I should see 2 "h2" elements
    And I should see "Normal Hours" in the "div#normalHours h2" element
    And I should see 7 "div#normalHours p" elements
    And I should see the following in the repeated "p" element within the context of the "div#normalHours" element:
    | text  |
    | Sunday: Closed |
    | Monday: 09:00 17:00 |
    | Tuesday: 09:00 17:00 |
    | Wednesday: 12:00 20:00 |
    | Thursday: 12:00 20:00 |
    | Friday: 09:00 17:00 |
    | Saturday: Closed |
    
  Scenario: Successfully Select a Branches with Hours
  Given I am on "/index.php"
  When I select "COP Sandbox - East Branch" from "id"
  And I press "Submit"
  Then I should be on "/index.php?id=http%3A%2F%2Flocalhost%2Fwclibhours%2Ftests%2Fsample-data%2Forganization_129057.rdf"
  And I should see 1 "form" element
  And I should see that "COP Sandbox - East Branch" in "id" is selected
    And I should see 2 "h2" elements
    And I should see "Normal Hours" in the "div#normalHours h2" element
    And I should see 7 "div#normalHours p" elements
    And I should see the following in the repeated "p" element within the context of the "div#normalHours" element:
    | text  |
    | Sunday: Closed |
    | Monday: 09:00 17:00 |
    | Tuesday: 09:00 17:00 |
    | Wednesday: 12:00 20:00 |
    | Thursday: 12:00 20:00 |
    | Friday: 09:00 17:00 |
    | Saturday: Closed |
  
    