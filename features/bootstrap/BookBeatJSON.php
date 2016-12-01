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
	
	public function updateJSONwithBookBeat($isbn,$data){
		$books = $this->json_content->{"book"};
		// update json_content
		foreach ($books as $book){
				if ($isbn==$book->{"isbn"}){
					$book->{"author_name"}=$data[2];
					$book->{"book_title"}=$data[3];
					$book->{"sales_rank"}=$data[4];
					$book->{"num_reviews"}=$data[5];
					$book->{"avg_ratings"}=$data[6];
				}
		}
		$this->writeContentToJSON();
	}
	
	public function deleteBook($isbn){
		$books = $this->json_content->{"book"};
		// delete json_content
		foreach ($books as $key => $book){
			if ($isbn==$book->{"isbn"}){
				unset($this->json_content->{"book"}[$key]);
			}
		}
		$this->writeContentToJSON();
	}
	
	public function addBook($isbn,$asin,$is_author,$author_name){
		$books = $this->json_content->{"book"};
		$book = (object)[];
		$book->{"isbn"} = $isbn;
		$book->{"asin"} = $asin;
		$book->{"is_author"} = boolval($is_author);
		$book->{"author_name"} = $author_name;
		array_push($this->json_content->{"book"}, $book);
		$this->writeContentToJSON();
	}

	public function updateBook($isbn,$asin,$is_author,$author_name){
		$books = $this->json_content->{"book"};
		// delete json_content
		foreach ($books as $key => $book){
			if ($isbn==$book->{"isbn"}){
				$book->{"asin"} = $asin;
				$book->{"is_author"} = boolval($is_author);
				$book->{"author_name"} = $author_name;
			}
		}
		$this->writeContentToJSON();
	}
}
?>