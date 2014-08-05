Feature: View Main Address
  As a library patron
  I want to view the main address for the library
  so that I can visit the library
  
  Scenario: Successfully View Address
    When I go to "/index.php"
    Then I should see "Address" in the "div#address h2" element
    And I should see 2 "div#address p" elements
    And I should see the following in the repeated "p" element within the context of the "div#address" element:
	| text  |
	| 6565 Kilgour Place |
	| Dublin, OH 43017 |