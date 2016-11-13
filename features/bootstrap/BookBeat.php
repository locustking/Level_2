<?php
final class BookBeat{
	private $book_title;
	private $sales_rank;
	private $num_reviews;
	private $avg_rating; 
	
	public function setBookTitle($book_title){
		$data = $this->scrapeamazon($book_title);
		$this->book_title = $data[0];
		$this->sales_rank = $data[1];
		$this->num_reviews = $data[2];
		$this->avg_rating = $data[3];
	}

	public function viewBookTitle($book_title){
		echo "<H1>Book Result</H1>";
		echo "Book Title: ".$this->getBookTitle()."<br />";
		echo "Sales Rank: ".$this->getSalesRank()."<br />";
		echo "Number of Reviews: ".$this->getNumReviews()."<br />";
		echo "Average Rating: ".$this->getAvgRating()."<br />";
	}
	
	public function viewBookbeatPage(){
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
	
	public function scrapeamazon($book_title){

		$book_search_url = "https://www.amazon.com/s/ref=nb_sb_noss?field-keywords=".urlencode($book_title);
		$page_content = $this->curl_get_contents($book_search_url);

		$dom_doc = new DOMDocument();
		libxml_use_internal_errors(true);
		$dom_doc->loadHTML($page_content);
		$xpath = new DOMXpath($dom_doc);

		$element = $xpath->query('//li[@id="result_0"]/div/div/div/div[2]/div[2]/a/@href');
		$search_result_url = $element->item(0)->nodeValue;

		$page_content = $this->curl_get_contents($search_result_url);

		$dom_doc = new DOMDocument();
		libxml_use_internal_errors(true);
		$dom_doc->loadHTML($page_content);
		$xpath = new DOMXpath($dom_doc);

		$element = $xpath->query('//span[@id="productTitle"]/text()');
		$title = $element->item(0)->nodeValue;

		$element = $xpath->query('//li[@id="SalesRank"]/text()');
		$rank=preg_replace("/[^0-9]/","",$element->item(1)->nodeValue);

		$element = $xpath->query('//*[@id="acrCustomerReviewText"]/text()');
		$reviewers = preg_replace("/[^0-9]/","",$element->item(0)->nodeValue);

		$element = $xpath->query('//*[@id="avgRating"]/span/a/span/text()');
		$rating = floatval($element->item(0)->nodeValue);
		libxml_clear_errors();

		return array($title,$rank,$reviewers,$rating);
	}
	
	function curl_get_contents($url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$contents = curl_exec($curl);
		curl_close($curl);
		return $contents;
	}
}
?>