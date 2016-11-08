Feature: BB1
As an author
I want to find out about tools that can help my book online, in order to help my book sales.

Scenario: Find out about tools
Given I am online
And I search for tools that can help my book sales
When I google search for bookbeat
And I view the page
Then I should have the bookbeat website describing its app
