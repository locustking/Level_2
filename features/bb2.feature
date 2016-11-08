Feature: BB2
As an author
I can see data that helps me understand what my sales are to better rank my work.

Scenario: See data that helps me understand my sales
Given there is a "Programming with Cucumber", which is listed on Amazon
When I view a "Programming with Cucumber"
Then I should see "Programming with Cucumber"
And I should see the Amazon sales rank of "412320"
And I should see the number of reviews of "107"
And I should see the average rating of "3.7"
