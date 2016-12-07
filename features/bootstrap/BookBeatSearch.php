<?php

final class BookBeatSearch{
	private $searchResultsXML;

	public function clearSearch(){
		$this->searchResultsXML = "";	
	}
	
	public function getSearchForm($searchText){
		
		$searchForm = "<form action name=\"buildlist\" method=\"post\">";
		$searchForm = $searchForm . "<input type=\"hidden\" name=\"formtype\" value=\"buildlist\" \>";
		$searchForm = $searchForm . "<label for=\"textSearch\">Add Book:</label>";
		$searchForm = $searchForm . "<input type=\"text\" name=\"textSearch\" id=\"textSearch\" value=\"";
		$searchForm = $searchForm . $searchText;
		$searchForm = $searchForm .  "\">";
		$searchForm = $searchForm . "<input type=\"submit\" Value=\"Search\"></form>";
		
		return $searchForm;
		
	}		
	
public function BookSearch($searchText){
		$dir = dirname(__FILE__).DIRECTORY_SEPARATOR;
		$file = $dir."aws_key.php";
		if(file_exists($file)){
    		include 'aws_key.php';
		} else{
			$aws_access_key_id = "aws_id";
			$aws_secret_key = "aws_secret";
		}

		// The region you are interested in
		$endpoint = "webservices.amazon.com";
		$uri = "/onca/xml";
		$params = array(
			"Service" => "AWSECommerceService",
			"Operation" => "ItemSearch",
			"AWSAccessKeyId" => $aws_access_key_id,
			"AssociateTag" => "bookbeatapp-20",
			"SearchIndex" => "Books",
			//"Keywords" => "marie antoinette",
			"Keywords" => $searchText,
			"ResponseGroup" => "Images,Medium",
			"Sort" => "relevancerank"
		);

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
		// Generate the canonical query
		$canonical_query_string = join("&", $pairs);
		// Generate the string to be signed
		$string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;
		// Generate the signature required by the Product Advertising API
		$signature = base64_encode(hash_hmac("sha256", $string_to_sign, $aws_secret_key, true));
		// Generate the signed URL
		$request_url = 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);
			//debug message
			//echo "Signed URL: \"".$request_url."\"";
			//return $request_url;

			$xml = file_get_contents($request_url);
			//$xml = curl_get_contents($request_url);		
			//echo $xml;
			$simple_xml=simplexml_load_string($xml);
			$this->searchResultsXML = $simple_xml;
			//echo $this->searchResultsXML;
	
	}
	public function getSearchResultsXML(){
		//echo "geeting results";		
		return $this->searchResultsXML;	
	}
	
	public function getSearchResultsTable(){
		$xml = $this->searchResultsXML;
		$table = "<table id='searchresults' class='tablesorter'>";		
		
		$tableHeader = "<thead>";
		$tableHeader = $tableHeader . "<tr>";
		$tableHeader = $tableHeader . "<th></th>";
		$tableHeader = $tableHeader . "<th>Title</th>";
		$tableHeader = $tableHeader . "<th>Author</th>";
		$tableHeader = $tableHeader . "<th>Publisher</th>";
		$tableHeader = $tableHeader . "<th>Publication Date</th>";
		$tableHeader = $tableHeader . "<th>Author Book</th>";
		$tableHeader = $tableHeader . "<th>Add To List</th>";
		$tableHeader = $tableHeader . "</tr>";
		$tableHeader = $tableHeader . "</thead>";
	
		$tableBody = "<tbody>";
		
		//printf("there are %d items found",  $xml->Items->Item->count());
		if($xml->Items->Item->count() > 0){
			foreach ($xml->Items->Item as $FoundBook) :
			
				$tableRow = "";
				$tableRow = $tableRow . "<tr>";
				$tableRow = $tableRow . "<td><img src=\"" . $FoundBook->SmallImage->URL . "\" height=\"75\" width=\"50\"></td>";
				$tableRow = $tableRow . "<td>" . $FoundBook->ItemAttributes->Title . "</td>";
				$tableRow = $tableRow . "<td>" . $FoundBook->ItemAttributes->Author ."</td>";
				$tableRow = $tableRow . "<td>" . $FoundBook->ItemAttributes->Publisher . "</td>";
				$tableRow = $tableRow . "<td>" . $FoundBook->ItemAttributes->PublicationDate . "</td>";
			
				//the form portion of the row
				$tableRow = $tableRow . "<form action name=\"addItem\" method=\"post\">";
				$tableRow = $tableRow . "<td><input type=\"checkbox\" name=\"Is_Author\" value=\"true\"></td>" ;			
				$tableRow = $tableRow . "<td><input type=\"Submit\" value=\"Add\"></td>"; 

				//hidden attributes to send to the jason 
				$tableRow = $tableRow . "<input type=\"hidden\" name=\"formtype\" value=\"addItem\" \>";				
				$tableRow = $tableRow . "<input type=\"hidden\" name=\"ISBN\" value=\"" . $FoundBook->ItemAttributes->ISBN . "\">";
				$tableRow = $tableRow . "<input type=\"hidden\" name=\"Title\" value=\"" . $FoundBook->ItemAttributes->Title . "\">";
				$tableRow = $tableRow . "<input type=\"hidden\" name=\"AuthorName\" value=\"" . $FoundBook->ItemAttributes->Author . "\">";
				$tableRow = $tableRow . "<input type=\"hidden\" name=\"Publisher\" value=\"" . $FoundBook->ItemAttributes->Publisher . "\">";
				$tableRow = $tableRow . "<input type=\"hidden\" name=\"PublicationDate\" value=\"" . $FoundBook->ItemAttributes->PublicationDate . "\">";
				$tableRow = $tableRow . "<input type=\"hidden\" name=\"EAN\" value=\"" . $FoundBook->ItemAttributes->EAN . "\">";
				$tableRow = $tableRow . "<input type=\"hidden\" name=\"ASIN\" value=\"". $FoundBook->ASIN . "\">"; 
				$tableRow = $tableRow .  "</form>";
				$tableRow = $tableRow . "</tr>";
			
				$tableBody = $tableBody . $tableRow;
			
			endforeach;
			$tableBody = $tableBody ." </tbody>";		
		}
		$table = $table . $tableHeader . $tableBody . "</table>"; 
		
		return $table;
		}		
	
}
?>
