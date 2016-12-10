Feature: BB6
As an author I can edit, and reload sets of books through the website interface

Scenario: The Author can clean the booklist
Given there is a file "booklist.json" with ISBN numbers
When I clean the booklist
Then I should see the file has "0" books

Scenario: The Author can add a book
Given there is a file "booklist.json" with ISBN numbers
When I add with isbn "978-1473853331", asin "1473853338", author name "Geri Walton" and is author "true"
Then I should see the file has isbn "978-1473853331", asin "1473853338", author name "Geri Walton" and is author "true"

Scenario: The Author can edit a book by isbn
Given there is a file "booklist.json" with ISBN numbers
When I edit book with isbn "978-1473853331", asin "1473853338", author name "Geri Walton" and is author "true"
Then I should see the file has isbn "978-1473853331", asin "1473853338", author name "Geri Walton" and is author "true"

Scenario: The Author can load a book data
Given there is a file "booklist.json" with ISBN numbers
And the file has isbn "978-1473853331"
When I load book isbn "978-1473853331"
Then I should get asin "1473853338", author name "Geri Walton", is author "true"

Scenario: The Author can load sets of books
Given there is a file "booklist.json" with ISBN numbers
When I load all books
Then I should get all isbns, asins, author names and is authors

Scenario: The Author can add a book with publisher info
Given there is a file "booklist.json" with ISBN numbers
When I add with isbn "978-1473853331", asin "1473853338", author name "Geri Walton", is author "true", publisher name "Pen and Sword", and publish date "January 19, 2017"
Then I should see the file has isbn "978-1473853331", asin "1473853338", author name "Geri Walton", is author "true", publisher name "Pen and Sword", and publish date "January 19, 2017"

Scenario: The Author can specify his or her book by isbn
Given there is a file "booklist.json" with ISBN numbers
When I set as the author of isbn "978-0385489492" as "true"
Then I should get the author of isbn "978-0385489492" as "true" 

Scenario: The Author can specify his or her book by isbn
Given there is a file "booklist.json" with ISBN numbers
When I set as the author of isbn "978-0385489492" as "False"
Then I should get the author of isbn "978-0385489492" as "false" 
