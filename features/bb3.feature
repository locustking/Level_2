Feature: BB3
As an author I can specify books in order to see their stats

Scenario: The Author can specify books by ISBN
Given there is a file "booklist.json" with ISBN numbers
When I verify the file "booklist.json"
Then I should see ISBN numbers of greater or equal than 2 and less or equal 10
And I should see each ISBN digits of greater or equal than 10 and less or equal 13
And I should see each ISBN has ASIN
And I should see at least 1 author's book in the list