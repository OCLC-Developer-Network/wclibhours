#Epic: Related library hours

##Story:
As a library patron,  
I want to see the related libraries  
so that I can see their hours

###Acceptance Criteria:
When I go to the library hours page
And I should see a "select" element with at least 2 values
And I should see a "submit" button  

##Story:
As a library patron,  
I want to select a related library  
so that I can view that library's hours

###Acceptance Criteria:
Given I and on the library hours page
When I select "COP Sandbox - East Branch" from the branches select menu
And I press "Submit"
Then I should see 1 "h1" element with the value of "COP Sandbox - East Branch"
And I should see a section for normal hours  
And the section for normal hours contains:  
an h2 heading "Normal Hours"  
a p element for each day of the week which contains:   
- the name of the day of the week  
- the open and closing times for that day or  
- a statement the library is closed or  
- a statement the library is open all day