<?php
final class BookBeatList{
	private $bookbeatjson;
	private $bookbeat;

    public function __construct()
    {
        $this->bookbeat = new BookBeat();   
    }	
	
	public function setBookBeatJSON($bbjson){
		// set bookbeatjson attribute with $bbjson
		$this->bookbeatjson = $bbjson;
		$this->bookbeatjson->verifyJSON($this->bookbeatjson->getFilename());
	}
		
	public function updateSalesRank($source="amazon"){
		// updates sales rank, reviews and rating info with data collected from $source
		// $source can be - amazon or amazon_uk
		$books = $this->bookbeatjson->getBooks();
		foreach ($books as $book){
			if(isset($book->{"asin"})){
				$this->bookbeat->setBookAsin($book->{"asin"});
				$result = $this->bookbeat->updateBookBeat("asin",$source);
				$bookbeat = $this->bookbeat->getBookBeat();
				$this->bookbeatjson->updateJSONwithBookBeat($bookbeat[0],$bookbeat,$source);					
			}
		}
		
		return $this->bookbeatjson->getBooks();
	}
	
	public function countBooks(){
		// returns the number of books
		return $this->bookbeatjson->countBooks();
	}
	
	public function countSalesRanks($source="amazon"){
		// returns the number of sales rank
		$books = $this->bookbeatjson->getBooks();
		$salesranks=0;
		foreach ($books as $book){
			if (isset($book->{"isbn"}) && isset($book->{$source}->{"sales_rank"})){
				$salesranks++;
			}
		}
		return $salesranks;		
	}
	
	public function getAllRanks($source="amazon"){
		// returns an array with all ranks info
		$books = $this->bookbeatjson->getBooks();
		$ranks=[];
		foreach ($books as $book){
			if (isset($book->{$source}->{"sales_rank"})){
				array_push($ranks,$book->{$source}->{"sales_rank"});
			}
		}
		return $ranks;		
	}
	
	public function countNumberofReviews($source="amazon"){
		// returns the number of reviews
		$books = $this->bookbeatjson->getBooks();
		$numreviews=0;
		foreach ($books as $book){
			if (isset($book->{"isbn"}) && isset($book->{$source}->{"num_reviews"})){
				$numreviews++;
			}
		}
		return $numreviews;		
	}

	public function getAllReviews($source="amazon"){
		// returns an array with all reviews
		$books = $this->bookbeatjson->getBooks();
		$reviews=[];
		foreach ($books as $book){
				if (isset($book->{$source}->{"num_reviews"})){
					array_push($reviews,$book->{$source}->{"num_reviews"});
				}
		}
		return $reviews;		
	}

	public function countAvgRatings($source="amazon"){
		// returns the number of ratings
		$books = $this->bookbeatjson->getBooks();
		$avgratings=0;
		foreach ($books as $book){
			if (isset($book->{"isbn"}) && isset($book->{$source}->{"avg_ratings"})){
				$avgratings++;
			}				
		}
		return $avgratings;		
	}
	
	public function getAllRatings($source="amazon"){
		// returns an array with all ratings
		$books = $this->bookbeatjson->getBooks();
		$ratings=[];
		foreach ($books as $book){
			if (isset($book->{"avg_ratings"})){
				array_push($ratings,$book->{$source}->{"avg_ratings"});
			}
		}
		return $ratings;		
	}
	
	public function resetJSON(){
		// reset bookbeat json content attribute
		$books = $this->bookbeatjson->getBooks();
		foreach ($books as $book){
			$this->bookbeatjson->deleteBook($book->{"isbn"});
		}
	}
	
	public function addBook($isbn,$asin,$is_author,$author_name,$publisher_name="",$publish_date="",$title=""){
		// adds a book to bookbeat json
		$book = $this->getBookbyIsbn($isbn);
		if(isset($book->{"isbn"}) && $book->{"isbn"}==$isbn){
			$this->bookbeatjson->updateBook($isbn,$asin,$is_author,$author_name,$publisher_name,$publish_date,$title);
		}else{
			$this->bookbeatjson->addBook($isbn,$asin,$is_author,$author_name,$publisher_name,$publish_date,$title);
		}
	}
	
	public function getBookbyIsbn($isbn){
		// returns a book by ISBM
		$books = $this->bookbeatjson->getBooks();
		$book = (object)[];
		foreach ($books as $book){
			if ($book->{"isbn"}==$isbn){
				return $book;
			}
		}
		return $book;		
	}
	
	public function updateBook($isbn,$asin,$is_author,$author_name,$publisher_name="",$publish_date="",$title=""){
		// updates a book in bookbeat json
		$this->bookbeatjson->updateBook($isbn,$asin,$is_author,$author_name,$publisher_name,$publish_date,$title);
	}
	
	public function getBooks(){
		// returns all book from bookbeat json
		return $this->bookbeatjson->getBooks();
	}

	public function updateSalesRankFromJSON(){
		// updates sales rank, review and rating from bookbeat json
		$this->bookbeatjson->verifyJSON($this->bookbeatjson->getFilename());
		return $this->bookbeatjson->getBooks();
	}
	
	public function getTimestamp(){
		// returns timestamp from bookbeat json
		return $this->bookbeatjson->getTimeStamp();
	}
	
	public function setAsAuthor($isbn,$is_author="true"){
		// set is_author attribute of book $isbn to $is_author
		// returns a book by ISBM
		$book = $this->getBookbyIsbn($isbn);
		$this->updateBook($isbn,$book->{"asin"},$is_author,$book->{"author_name"},$book->{"publisher_name"},$book->{"publish_date"});
	}
}
?>