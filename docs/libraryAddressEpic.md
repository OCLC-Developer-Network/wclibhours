#Epic: Library Address

##Story:
As a library patron,  
I want to see the library address and hours 
so that I can visit a library when it is open

###Acceptance Criteria:
Given I and on the library hours page
Then I should see 1 "h1" element with the value of "COP Sandbox - East Branch"
And I should see 2 "div#address p" elements
And I should see 2 p elements within the context of the "div#address" element.
And the first p element should contain: 6565 Kilgour Place
And the second p element should contain: Dublin, OH 43017
And I should see a section for normal hours  
And the section for normal hours contains:  
an h2 heading "Normal Hours"  
a p element for each day of the week which contains:   
- the name of the day of the week  
- the open and closing times for that day or  
- a statement the library is closed or  
- a statement the library is open all day