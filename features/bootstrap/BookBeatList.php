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
	}
	
	public function countBooks(){
		return $this->bookbeatjson->countBooks();
	}
	
	public function countSalesRanks(){
		$books = $this->bookbeatjson->getBooks();
		$salesranks=0;
		foreach ($books as $book){
			if (isset($book->{"isbn"}) && isset($book->{"salesrank"})){
				$salesranks++;
			}
		}
		return $salesranks;		
	}
	
	
}
?>