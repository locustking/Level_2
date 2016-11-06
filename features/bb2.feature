
Feature: BB2
As an author
I can see data that helps me understand what my sales are to better rank my work.

Scenario: See data that helps me understand my sales
Given there is a "my book title", which is listed on Amazon
When I add "my book title"
Then I should have 1 book in the widget pane
And the book title should be "my book title"
