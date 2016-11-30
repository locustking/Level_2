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
		
	public function updateSalesRank(){
		$books = $this->bookbeatjson->getBooks();
		foreach ($books as $book){
			if (isset($book->{"isbn"})){
				$this->bookbeat->setBookIsbn($book->{"isbn"});
				$this->bookbeatjson->updateJSONwithBookBeat($book->{"isbn"},$this->bookbeat->getBookBeat());
			}
		}
		
		return $this->bookbeatjson->getBooks();
	}
	
	public function countBooks(){
		return $this->bookbeatjson->countBooks();
	}
	
	public function countSalesRanks(){
		$books = $this->bookbeatjson->getBooks();
		$salesranks=0;
		foreach ($books as $book){
			if (isset($book->{"isbn"}) && isset($book->{"sales_rank"})){
				$salesranks++;
			}
		}
		return $salesranks;		
	}
	
	public function getAllRanks(){
		$books = $this->bookbeatjson->getBooks();
		$ranks=[];
		foreach ($books as $book){
			if (isset($book->{"sales_rank"})){
				array_push($ranks,$book->{"sales_rank"});
			}
		}
		return $ranks;		
		
	}
	
	public function countNumberofReviews(){
		$books = $this->bookbeatjson->getBooks();
		$numreviews=0;
		foreach ($books as $book){
			if (isset($book->{"isbn"}) && isset($book->{"num_reviews"})){
				$numreviews++;
			}
		}
		return $numreviews;		
	}

	public function getAllReviews(){
		$books = $this->bookbeatjson->getBooks();
		$reviews=[];
		foreach ($books as $book){
			if (isset($book->{"num_reviews"})){
				array_push($reviews,$book->{"num_reviews"});
			}
		}
		return $reviews;		
		
	}

	public function countAvgRatings(){
		$books = $this->bookbeatjson->getBooks();
		$avgratings=0;
		foreach ($books as $book){
			if (isset($book->{"isbn"}) && isset($book->{"avg_ratings"})){
				$avgratings++;
			}
		}
		return $avgratings;		
	}
	
	public function getAllRatings(){
		$books = $this->bookbeatjson->getBooks();
		$ratings=[];
		foreach ($books as $book){
			if (isset($book->{"avg_ratings"})){
				array_push($ratings,$book->{"avg_ratings"});
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
	
	public function addBook($isbn,$asin,$is_author,$author_name){
		$this->bookbeatjson->addBook($isbn,$asin,$is_author,$author_name);
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
	
	public function updateBook($isbn,$asin,$is_author,$author_name){
		$this->bookbeatjson->updateBook($isbn,$asin,$is_author,$author_name);
	}
	
	public function getBooks(){
		return $this->bookbeatjson->getBooks();
	}

	public function updateSalesRankFromJSON(){
		$this->bookbeatjson->verifyJSON($this->bookbeatjson->getFilename());
		return $this->bookbeatjson->getBooks();
	}
	
	
}
?>