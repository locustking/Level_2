Feature: BB5
As an author, I can sort the rankings to see how the rankings are via Amazon sales data (sub-group)

Scenario: The Author can sort the rank by sales rank
Given there is a file "booklist.json" with ISBN numbers
When I sort the order by "sales rank"
Then I should see the books are sorted by "sales rank"

Scenario: The Author can sort the rank by reviews
Given there is a file "booklist.json" with ISBN numbers
When I sort the order by "reviews"
Then I should see the books are sorted by "reviews"

Scenario: The Author can sort the rank by average rating
Given there is a file "booklist.json" with ISBN numbers
When I sort the order by "average ratings"
Then I should see the books are sorted by "average ratings"

Scenario: The Author can sort the rank by book title
Given there is a file "booklist.json" with ISBN numbers
When I sort the order by "book title"
Then I should see the books are sorted by "book title"

Scenario: The Author can sort the rank by author
Given there is a file "booklist.json" with ISBN numbers
When I sort the order by "author"
Then I should see the books are sorted by "author"
