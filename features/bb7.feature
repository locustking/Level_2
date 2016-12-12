Feature: BB7
As an author I want to be able to see my book’ number reviewers, sales rank, ratings and comments via Amazon UK

Scenario: The Author can pull sales rank of the Author's selected books from Amazon UK
Given there is a file "booklist.json" with ISBN numbers
When I pull the sales rank from Amazon UK
Then I should see the sales rank of each book from Amazon UK
And the sales rank should be integer from Amazon UK
And I should see number of reviews from Amazon UK
And the number of reviews should be integer from Amazon UK
And I should see average ratings from Amazon UK
And the average rating should be float from Amazon UK
And I should see a timestamp
And the delta timestamp should be positive integer

Scenario: The Author can pull sales rank of the Author's selected books from Amazon UK
Given there is a file "booklist.json" with ISBN numbers
When I add with isbn "978-1606064832", asin "1606064835", author name "Helene Delalex" and is author "false"
And I pull the sales rank from Amazon UK
Then I should see the sales rank of each book from Amazon UK
And the sales rank should be integer from Amazon UK
And I should see number of reviews from Amazon UK
And the number of reviews should be integer from Amazon UK
And I should see average ratings from Amazon UK
And the average rating should be float from Amazon UK
And I should see a timestamp
And the delta timestamp should be positive integer