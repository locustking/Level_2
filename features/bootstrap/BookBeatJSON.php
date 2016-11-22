<?php
final class BookBeatJSON{
	private $filename;
	private $json_content;
	
	public function setFilename($f){
		$this->filename = $f;
	}
	
	public function verifyJSON($f){
		//need a cleaner code to find the file
		$file = "/home/bas/Github/Level_2/features/bootstrap/".$this->filename;
		echo $file;
		if (file_exists($file)){
			//read json file
			$json = file_get_contents($file);
				
			//put on json content
			$this->json_content = json_decode($json);
		} else {
			echo "failed to find file";
		}
		echo var_dump($this->json_content);
	}
	
	public function countBooks(){
		// count the number of ISBN in the list
		return count($this->json_content);
	}
	
	public function getFilename(){
		return $this->filename;
	}
	
}
?>