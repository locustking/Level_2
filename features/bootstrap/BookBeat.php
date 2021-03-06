<?php
final class BookBeat{
	private $book_title;
	private $sales_rank;
	private $num_reviews;
	private $avg_rating; 
	private $isbn;
	private $asin;
	private $authorname;
	private $publisher_name;
	private $publish_date;
	
	public function setBookTitle($book_title){
		// updates book attribute from Amazon search result by book title
		$data = $this->scrapeamazon($book_title);
		$this->book_title = $data[0];
		$this->sales_rank = $data[1];
		$this->num_reviews = $data[2];
		$this->avg_rating = $data[3];
	}

	public function viewBookTitle($book_title){
		// prints book title, sales rank, number of reviews and average rating
		echo "<H1>Book Result</H1>";
		echo "Book Title: ".$this->getBookTitle()."<br />";
		echo "Sales Rank: ".$this->getSalesRank()."<br />";
		echo "Number of Reviews: ".$this->getNumReviews()."<br />";
		echo "Average Rating: ".$this->getAvgRating()."<br />";
	}
	
	public function viewBookbeatPage(){
		// prints book title, sales rank, number of reviews and average rating
		echo "<H1>Book Result</H1>";
		echo "Book Title: ".$this->getBookTitle()."<br />";
		echo "Sales Rank: ".$this->getSalesRank()."<br />";
		echo "Number of Reviews: ".$this->getNumReviews()."<br />";
		echo "Average Rating: ".$this->getAvgRating()."<br />";
	}

	public function getBookTitle(){
		// returns book title attribute value
		return $this->book_title;
	}

	public function getSalesRank(){
		// returns sales rank attribute value
		return $this->sales_rank;
	}

	public function getNumReviews(){
		// returns number of reviews attribute value
		return $this->num_reviews;
	}
	public function getAvgRating(){
		// returns average rating attribute value
		return $this->avg_rating;
	}
	
	public function scrapeamazon($book_title){
		// searches book title at Amazon
		// returns book title, sales rank, number of reviews and average rating
		// deprecated, use updateBookBeat instead
		$book_search_url = "https://www.amazon.com/s/ref=nb_sb_noss?url=search-alias%3Dstripbooks&field-keywords=".urlencode($book_title);
		$page_content = $this->curl_get_contents($book_search_url);

		$dom_doc = new DOMDocument();
		libxml_use_internal_errors(true);
		$dom_doc->loadHTML($page_content);
		$xpath = new DOMXpath($dom_doc);

		//$element = $xpath->query('//li[@id="result_0"]/div/div/div/div[2]/div[2]/a/@href');
		$element = $xpath->query("//*[@id='result_0']/div/div/div/div[2]/div[2]/div[1]/a/@href");
		$search_result_url = $element->item(0)->nodeValue;

		$page_content = $this->curl_get_contents($search_result_url);

		$dom_doc = new DOMDocument();
		libxml_use_internal_errors(true);
		$dom_doc->loadHTML($page_content);
		$xpath = new DOMXpath($dom_doc);

		$element = $xpath->query('//span[@id="productTitle"]/text()');
		if (0 == $element->length){
			$title = $book_title;
		} else {
			$title = $element->item(0)->nodeValue;
		}

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
		// sends url query using curl package
		// returns the response string
		$curl = curl_init();
		$config['useragent'] = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';

		curl_setopt($curl, CURLOPT_USERAGENT, $config['useragent']);
		curl_setopt($curl, CURLOPT_REFERER, 'https://www.bookbeatapp.com/');
		curl_setopt($curl, CURLOPT_URL, $url);
		$dir = dirname(__FILE__);
		$config['cookie_file'] = $dir . '/cookies/' . md5('cookie') . '.txt';
		curl_setopt($curl, CURLOPT_COOKIEFILE, $config['cookie_file']);
		curl_setopt($curl, CURLOPT_COOKIEJAR, $config['cookie_file']);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$contents = curl_exec($curl);
		curl_close($curl);
		return $contents;
	}
	
	public function setBookIsbn($isbn){
		// updates book attribute from Amazon search result by isbn
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
		// returns an array with isbn, asin, author name, book title,
		// sales rank, number of reviews, average rating,
		// publisher name and publish date
		return [$this->isbn,$this->asin,$this->authorname,
				$this->book_title,$this->sales_rank,
				$this->num_reviews,$this->avg_rating,
			   	$this->publisher_name,$this->publish_date];
	}

	public function scrapeamazonbyisbn($isbn){
		// searches a book at Amazon by ISBN
		// deprecated, use updateBookBeat instead
		$this->{"isbn"}=$isbn;
		return $this->updateBookBeat("isbn","amazon");
	}
	
	public function setBookAsin($asin){
		// set ASIN attribute
		$this->asin = $asin;
	}
	
	function updateBookBeatWithAmazon($key="asin"){
		// searches a book at Amazon by $key attribute - asin or isbn
		// deprecated, use updateBookBeat instead
		return $this->updateBookBeat($key,"amazon");
	}

	function updateBookBeatWithAmazonUK($key="asin"){
		// searches a book at Amazon UK by $key attribute - asin or isbn
		// deprecated, use updateBookBeat instead
		return $this->updateBookBeat($key,"amazon_uk");
	}

	function updateBookBeat($key="asin",$source="amazon"){
		// searches a book at $source by $key attribute
		// $key can be - asin or isbn
		// $source can be - amazon or amazon_uk
		$dir = dirname(__FILE__).DIRECTORY_SEPARATOR;
		$file = $dir."aws_key.php";
		if(file_exists($file)){
    		include 'aws_key.php';
		}else{
			$aws_access_key_id = "aws_id";
			$aws_secret_key = "aws_secret";
		}
		
		
		if($source=="amazon"){
			$endpoint = "webservices.amazon.com";
		}else if($source=="amazon_uk"){
			$endpoint = "webservices.amazon.co.uk";
		}

		// Lookup parameters
		$uri = "/onca/xml";
		$params = array(
			"Service" => "AWSECommerceService",
			"Operation" => "ItemLookup",
			"AWSAccessKeyId" => $aws_access_key_id,
			"AssociateTag" => "bookbeatapp-20",
			"IncludeReviewsSummary"=> true,
			"ItemId" => $this->asin,
			"ResponseGroup" => "ItemAttributes,SalesRank,Reviews"
		);

		
		// Set ISBN parameter if lookup by isbn
		if($key=="isbn"){
			$param["IdType"]="EAN";
			$param["ItemId"]=preg_replace("/[^0-9]/", "", $this->isbn);
		}
		
		// Set current timestamp if not set
		if (!isset($params["Timestamp"])) {
			$params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
		}
		
		// Sort the parameters by key
		ksort($params);
		$pairs = array();
		foreach ($params as $key => $value) {
			array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
		}

		// Generate request URL
		$canonical_query_string = join("&", $pairs);
		$string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;
		$signature = base64_encode(hash_hmac("sha256", $string_to_sign, $aws_secret_key, true));		// Generate the signed URL
		$request_url = 'https://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);

		// Get XML output
		$xml = file_get_contents($request_url);
		$simple_xml=simplexml_load_string($xml);

		// Parse XML, set this object's attributes
		$this->book_title = $simple_xml->Items->Item->ItemAttributes->Title->__toString();
		$this->sales_rank=intval($simple_xml->Items->Item->SalesRank);
		$reviews_url = $simple_xml->Items->Item->CustomerReviews->IFrameURL;
		$page_content = $this->curl_get_contents($reviews_url);
		$dom_doc = new DOMDocument();
		libxml_use_internal_errors(true);
		$dom_doc->loadHTML($page_content);
		$xpath = new DOMXpath($dom_doc);
		$element = $xpath->query("//span[@class='crAvgStars']/span/a/img/@title");
		if($element->{"length"}==0){
			$this->avg_rating = 0.0;
		} else {
			$this->avg_rating = floatval(explode(" ",trim($element->item(0)->nodeValue))[0]);
		}
		$element = $xpath->query("//span[@class='crAvgStars']/a/text()");
		if($element->{"length"}==0){
			$this->num_reviews = 0;
		} else {
			$this->num_reviews=intval(explode(" ",trim($element->item(0)->nodeValue))[0]);
		}
		libxml_clear_errors();
		$this->isbn = $simple_xml->Items->Item->ItemAttributes->EAN->__toString();
		$this->asin = $simple_xml->Items->Item->ASIN->__toString();
		$this->authorname = $simple_xml->Items->Item->ItemAttributes->Author->__toString();
		return array($this->getBookBeat());
	}

	
}
?>