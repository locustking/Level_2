Feature: BB4
As an author I want to be able to see my book’ rank against other books to know how I am doing via Amazon sales data 

Scenario: The Author can pull sales rank of the Author's selected books
Given there is a file "booklist.json" with ISBN numbers
When I pull the sales rank
Then I should see the sales rank of each book
And the sales rank should be integer
And I should see number of reviews
And the number of reviews should be integer
And I should see average ratings
And the average rating should be float

