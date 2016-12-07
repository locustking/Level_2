<?php
final class BookBeatList{
	private $bookbeatjson;
	private $bookbeat;

    public function __construct()
    {
        $this->bookbeat = new BookBeat();   
    }	
	
	public function setBookBeatJSON($bbjson){
		$this->bookbeatjson = $bbjson;
		$this->bookbeatjson->verifyJSON($this->bookbeatjson->getFilename());
	}
		
	public function updateSalesRank($source="amazon"){
		$books = $this->bookbeatjson->getBooks();
		foreach ($books as $book){
			/*
			if (isset($book->{"isbn"})){
				$this->bookbeat->setBookIsbn($book->{"isbn"});
				$this->bookbeatjson->updateJSONwithBookBeat($book->{"isbn"},$this->bookbeat->getBookBeat());
			}
			*/
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
		return $this->bookbeatjson->countBooks();
	}
	
	public function countSalesRanks($source="amazon"){
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
		$books = $this->bookbeatjson->getBooks();
		foreach ($books as $book){
			$this->bookbeatjson->deleteBook($book->{"isbn"});
		}
	}
	
	public function addBook($isbn,$asin,$is_author,$author_name,$publisher_name="",$publish_date=""){
		$book = $this->getBookbyIsbn($isbn);
		if(isset($book->{"isbn"}) && $book->{"isbn"}==$isbn){
			$this->bookbeatjson->updateBook($isbn,$asin,$is_author,$author_name,$publisher_name,$publish_date);
		}else{
			$this->bookbeatjson->addBook($isbn,$asin,$is_author,$author_name,$publisher_name,$publish_date);
		}
	}
	
	public function getBookbyIsbn($isbn){
		$books = $this->bookbeatjson->getBooks();
		$book = (object)[];
		foreach ($books as $book){
			if ($book->{"isbn"}==$isbn){
				return $book;
			}
		}
		return $book;		
	}
	
	public function updateBook($isbn,$asin,$is_author,$author_name,$publisher_name="",$publish_date=""){
		$this->bookbeatjson->updateBook($isbn,$asin,$is_author,$author_name,$publisher_name,$publish_date);
	}
	
	public function getBooks(){
		return $this->bookbeatjson->getBooks();
	}

	public function updateSalesRankFromJSON(){
		$this->bookbeatjson->verifyJSON($this->bookbeatjson->getFilename());
		return $this->bookbeatjson->getBooks();
	}
	
	public function getTimestamp(){
		return $this->bookbeatjson->getTimeStamp();
	}
}
?>