<?php
final class BookBeatJSON{
	private $filename;
	private $json_content;
	
	public function setFilename($f){
		$this->filename = $f;
	}
	
	public function verifyJSON($f){
		//need a cleaner code to find the file
		$dir = dirname(__FILE__).DIRECTORY_SEPARATOR;
		$file = $dir.$this->filename;
		if (file_exists($file)){
			//read json file
			$json = file_get_contents($file);
				
			//put on json content
			$this->json_content = json_decode($json);
		} else {
			echo "failed to find file";
		}
		
		//bugfix rating float value
		$books = $this->json_content->{"book"};
		foreach ($books as $key => $book) {
			if(isset($book->{"avg_ratings"})){
				$this->json_content->{"book"}[$key]->{"avg_ratings"} = floatval($this->json_content->{"book"}[$key]->{"avg_ratings"});
			}
		}
	}
	
	public function countBooks(){
		// count the number of books in the json content
		// $this->json_content->{"book"} is an array
		return count($this->json_content->{"book"});
	}
	
	public function getFilename(){
		return $this->filename;
	}
	
	public function countLeastIsbnDigits(){
		$books = $this->json_content->{"book"};
		$isbn = $books[0]->{"isbn"};
		$digits = preg_match_all( "/[0-9]/", $isbn );
		foreach ($books as $book) {
			if ($digits > preg_match_all( "/[0-9]/", $book->{"isbn"} )){
				$digits = preg_match_all( "/[0-9]/", $book->{"isbn"} );
			}
		}
		return $digits;
	}

	public function countMostIsbnDigits(){
		$books = $this->json_content->{"book"};
		$isbn = $books[0]->{"isbn"};
		$digits = preg_match_all( "/[0-9]/", $isbn );
		foreach ($books as $book) {
			if ($digits < preg_match_all( "/[0-9]/", $book->{"isbn"} )){
				$digits = preg_match_all( "/[0-9]/", $book->{"isbn"} );
			}
		}
		return $digits;
	}
	
	public function countAsin(){
		$books = $this->json_content->{"book"};
		$asin=0;
		foreach ($books as $book){
			if (isset($book->{"isbn"}) && isset($book->{"asin"})){
				$asin++;
			}
		}
		return $asin;
	}
	
	public function countAuthorSBook(){
		$books = $this->json_content->{"book"};
		$authorsbook=0;
		foreach ($books as $book){
			if ($book->{"is_author"}){
				$authorsbook++;
			}
		}
		return $authorsbook;
	}

	public function countSalesRanks(){
		$books = $this->json_content->{"book"};
		$salesranks=0;
		foreach ($books as $book){
			if (isset($book->{"isbn"}) && isset($book->{"salesrank"})){
				$salesranks++;
			}
		}
		return $salesranks;
	}

	public function getBooks(){
		// $this->json_content->{"book"} is an array
		return $this->json_content->{"book"};
	}
	
	public function writeContentToJSON(){
		// write to booklist.json
		$dir = dirname(__FILE__).DIRECTORY_SEPARATOR;
		$file = $dir.$this->filename;
		file_put_contents($file,json_encode($this->json_content, JSON_PRETTY_PRINT));
	}
	
	public function updateJSONwithBookBeat($isbn,$data,$source="amazon"){
		$version=1;
		$books = $this->json_content->{"book"};
		// update json_content
		foreach ($books as $book){
				if ($isbn==preg_replace("/[^0-9]/", "",$book->{"isbn"})){
					$book->{"author_name"}=$data[2];
					$book->{"book_title"}=$data[3];
					if($source=="amazon" && $version==0){
						$book->{"sales_rank"}=$data[4];
						$book->{"num_reviews"}=$data[5];
						$book->{"avg_ratings"}=$data[6];
					} else if($source=="amazon" && $version==1){
						$book->{"amazon"}=(object)[];
						$book->{"amazon"}->{"sales_rank"}=$data[4];
						$book->{"amazon"}->{"num_reviews"}=$data[5];
						$book->{"amazon"}->{"avg_ratings"}=$data[6];
					} else if($source=="amazon_uk"){
						$book->{"amazon_uk"}=(object)[];
						$book->{"amazon_uk"}->{"sales_rank"}=$data[4];
						$book->{"amazon_uk"}->{"num_reviews"}=$data[5];
						$book->{"amazon_uk"}->{"avg_ratings"}=$data[6];
					}
				}
		}
		$this->setTimestamp();
		$this->writeContentToJSON();
	}
	
	public function deleteBook($isbn){
		$books = $this->json_content->{"book"};
		// delete json_content
		$json_array = json_decode(json_encode($books),true);
		$delete = [];
		foreach($json_array as $i => $val){
			if ($isbn==$json_array[$i]["isbn"]){
				array_push($delete,$i);
			}
		}
		foreach($delete as $d){
			unset($json_array[$d]);
		}
		$this->json_content->{"book"} = json_decode(json_encode($json_array));

		$this->writeContentToJSON();
	}
	
	public function addBook($isbn,$asin,$is_author,$author_name,$publisher_name,$publish_date){
		$books = $this->json_content->{"book"};
		$book = (object)[];
		$book->{"isbn"} = $isbn;
		$book->{"asin"} = $asin;
		$book->{"is_author"} = ($is_author=="true" || $is_author); // boolval($is_author);
		$book->{"author_name"} = $author_name;
		$book->{"publisher_name"} = $publisher_name;
		$book->{"publish_date"} = $publish_date;
		array_push($this->json_content->{"book"}, $book);
		$this->writeContentToJSON();
	}

	public function updateBook($isbn,$asin,$is_author,$author_name,$publisher_name,$publish_date){
		$books = $this->json_content->{"book"};
		// delete json_content
		foreach ($books as $key => $book){
			if ($isbn==$book->{"isbn"}){
				$book->{"asin"} = $asin;
				$book->{"is_author"} = boolval($is_author);
				$book->{"author_name"} = $author_name;
				$book->{"publisher_name"} = $publisher_name;
				$book->{"publish_date"} = $publish_date;
			}
		}
		$this->writeContentToJSON();
	}
	
	public function getTimestamp(){
		return $this->json_content->{"timestamp"};
	}
	
	public function setTimestamp(){
		date_default_timezone_set('America/New_York');
		$now = date("Y-m-d H:i:s");
		if(isset($this->json_content->{"timestamp"})){
			$this->json_content->{"timestamp"} = $now;
		} else {
			$this->json_content->{"timestamp"} = $now;
		}
		return $this->getTimestamp();
	}
}
?>