Feature: BB2
As an author
I can see data that helps me understand what my sales are to better rank my work.

Scenario: See data that helps me understand my sales
Given there is a "The Cucumber Book: Behaviour-Driven Development for Testers and Developers (Pragmatic Programmers)", which is listed on Amazon
When I view the BookBeat page
Then I should see "The Cucumber Book: Behaviour-Driven Development for Testers and Developers (Pragmatic Programmers)"
And I should see the Amazon sales rank of greater than 0
And I should see the number of reviews of 0 or greater
And I should see the average rating of greater or equal than 0 and less or equal 5


Scenario: See data that helps me understand my sales
Given there is a "BDD in Action: Behavior-driven development for the whole software lifecycle" which is listed on Amazon
When I view the BookBeat page
Then I should see "BDD in Action: Behavior-driven development for the whole software lifecycle"
And I should see the Amazon sales rank of greater than 0
And I should see the number of reviews of 0 or greater
And I should see the average rating of greater or equal than 0 and less or equal 5
