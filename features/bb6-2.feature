Feature: BB6-2
 In order to add new books to my watch list
 As an author
 I need to be able to search for books

Scenario: The Author search Amazon by book title.
 Given The author searches for a book 
 When I search for "I Love You Madly: Marie-Antoinette and Count Fersen: The Secret Letters" 
 Then Search results return ISBN "0720618770" 

Scenario: The Author can search Amazon by ISBN
 Given the author searches for an ISBN
 When I search for "1606064835"
 Then Search results return ASIN "1606064835"

Scenario: The Author can search Amazon by Author
 Given the author searches for an Author
 When I search for "Geri Walton"
 Then Search results return ISBN "1473853338"

Scenario: The Search results are returned as an HTML table
 Given The author searches for a book 
 When I search for "Windows 10 For Seniors For Dummies"
 Then search results return a HTML table
 
