<?php
final class BookBeat{
	private $book_title;
	private $sales_rank;
	private $num_reviews;
	private $avg_rating; 
	private $isbn;
	private $asin;
	private $authorname;
	
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

		$book_search_url = "https://www.amazon.com/s/ref=nb_sb_noss?url=search-alias%3Dstripbooks&field-keywords=".urlencode($book_title);
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
	
	public function setBookIsbn($isbn){
		$data = $this->scrapeamazonbyisbn($isbn);
		$this->book_title = $data[0];
		$this->sales_rank = $data[1];
		$this->num_reviews = $data[2];
		$this->avg_rating = $data[3];
		$this->isbn = $data[4];
		$this->asin = $data[5];
		$this->authorname = $data[6];

	}
	
	public function getBookBeat(){
		return [$this->isbn,$this->asin,$this->authorname,
				$this->book_title,$this->sales_rank,
				$this->num_reviews,$this->avg_rating];
	}
	
	public function scrapeamazonbyisbn($isbn){
		$book_search_url = "https://www.amazon.com/gp/search/ref=sr_adv_b/?search-alias=stripbooks&unfiltered=1&field-isbn=".$isbn."&p_n_feature_browse-bin=618083011";
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
		$rank=intval(preg_replace("/[^0-9]/","",$element->item(1)->nodeValue));

		$element = $xpath->query('//*[@id="acrCustomerReviewText"]/text()');
		if (0 == $element->length){
			$reviewers = 0;
		} else {
			$reviewers = intval(preg_replace("/[^0-9]/","",$element->item(0)->nodeValue));
		}

		$element = $xpath->query('//*[@id="avgRating"]/span/a/span/text()');
		if (0 == $element->length){
			$rating = 0.;
		} else {
			$rating = floatval($element->item(0)->nodeValue);
		}

		// need to fix scraping isbn and asin
		//$element = $xpath->query('//*[@id="isbn_feature_div"]/div/div[1]/span[2]/text()');
		//print_r($element);
		//$isbn = $element->item(0)->nodeValue;

		//$element = $xpath->query('//*[@id="isbn_feature_div"]/div/div[2]/span[2]/text()');
		//$asin = $element->item(0)->nodeValue;
		$asin="1234567890";

		$element = $xpath->query('//*[@id="byline"]/span/span[1]/a[1]/text()');
		if (0 == $element->length){
			$authorname = "";
		} else {
			$authorname = $element->item(0)->nodeValue;
		}
		
		
		libxml_clear_errors();

		return array($title,$rank,$reviewers,$rating,$isbn,$asin,$authorname);
	}
	
}
?>