<?php
final class BookBeat{
	private $book_title;
	private $sales_rank;
	private $num_reviews;
	private $avg_rating; 
	
	public function setBookTitle($book_title){
		$this->book_title = $book_title;
		//read from csv, and populate the data
		$filename = getcwd()."/features/bootstrap/"."AmazonData.csv";
		if (($amazondata = fopen($filename, "r")) !== FALSE) {
		  	$data = fgetcsv($amazondata, 1000, ",");
		  	$this->sales_rank = $data[1];
		  	$this->num_reviews = $data[2];
		  	$this->avg_rating = $data[3];
		}
		fclose($amazondata);
	}

	public function viewBookTitle($book_title){
		echo "<H1>Book Result</H1>";
		echo "Book Title: ".$this->getBookTitle()."<br />";
		echo "Sales Rank: ".$this->getSalesRank()."<br />";
		echo "Number of Reviews: ".$this->getNumReviews()."<br />";
		echo "Average Rating: ".$this->getAvgRating()."<br />";
	}

	public function getBookTitle(){
		return $this->book_title;
	}

	public function getSalesRank(){
		return $this->sales_rank;
	}

	public function getNumReviews(){
		return $this->num_reviews;
	}
	public function getAvgRating(){
		return $this->avg_rating;
	}
}
?>