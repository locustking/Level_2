<?php
/**
 * @package BBA_DB
 * @version 2.0
 */
/*
Plugin Name: Book Beat
Plugin URI: http://www.staging1.bookbeatapp.com
Description: A tool for authors to do competitive analysis
Author: Havard E-71, Fall 2016, Level 2 SCRUM tam
Version: 2.0
Author URI: http://www.staging1.bookbeatapp.com
*/



    // display errors, warnings, and notices
    ini_set("display_errors", true);
    error_reporting(E_ALL);

    // configuration
        require dirname(__FILE__) . "/BookBeatList.php"; 
        require dirname(__FILE__) . "/BookBeatJSON.php";
        require dirname(__FILE__) . "/BookBeat.php";
        require dirname(__FILE__) . "/bba_booklist_views.php";
        require dirname(__FILE__) . "/BookBeatSearch.php";
        require dirname(__FILE__) . "/functions.php";

    // Adds shortcode to call control loop from page
    add_shortcode( 'bba_display', 'bookbeat_func' );

function bookbeat_func($atts){
    // Get Book Data
    // init BookBeat, BookBeatJSON and BookBeatList instances

    $bookbeat = new BookBeat();
    $bookbeatjson = new BookBeatJSON();
    $bookbeatlist = new BookBeatList();
		
    // set json filename $arg1
    $bookbeatjson->setFilename("booklist.json");

    // wire up bookbeatjson object to bookbeatlist
    $bookbeatlist->setBookBeatJSON($bookbeatjson);
    
	// Check for POST method which means action, else render booklist view
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		// Debug statement: 
		switch ($_POST['formtype']){
			// clicked to edit booklist 
			case 'results' :
				$pageheader = bba_pageheader();
				$pagecontent = bba_booklist_display($bookbeat,$bookbeatjson,$bookbeatlist);
				break; 
            // Update JSON and re-display booklist
            case 'updateJSON' :
                $source = "amazon";
                $bookbeatlist->updateSalesRank($source);
                $source = "amazon_uk";
                $bookbeatlist->updateSalesRank($source);
                $pageheader = bba_pageheader();
                $pagecontent = bba_booklist_display($bookbeat,$bookbeatjson,$bookbeatlist);
                break; 
            case 'buildlist' :
				$pageheader = bba_searchheader();
				$pagecontent ="";
				$searchText = "";                    
				if(isset($_POST['textSearch'])){
					$searchText = $_POST['textSearch'];
					$pagecontent = $pagecontent . bba_book_search($searchText);
				}elseif(isset($_POST['editAction'])){
					if ($_POST['editAction']=="update"){
						if(isset($_POST['isbn']) && isset($_POST['is_author'])){
							bba_book_update_is_author($_POST['isbn'],$_POST['is_author']);
						}
					}elseif($_POST['editAction']=="delete"){
						if(isset($_POST['isbn'])){
							bba_book_delete($_POST['isbn']);
						}
					}
					$pagecontent = $pagecontent . bba_book_search("") . bba_edit_book_list();
				}else{
					$pagecontent = $pagecontent . bba_book_search("") . bba_edit_book_list();
				}
				break;
			// Added item to JSON
			case 'addItem' :
				$pageheader = bba_searchheader();
				//$isbn,$asin,$is_author,$author_name
				$pagecontent = bba_book_add($_POST['EAN'],$_POST['ASIN'],$_POST['Is_Author'],$_POST['AuthorName'],$_POST['Publisher'],$_POST['PublicationDate'],$_POST['Title']);   
				$pagecontent = $pagecontent . bba_book_search("") . bba_edit_book_list();						
				// will say something about item added to JSON
				break;
		} 
	}else{
		$pageheader = bba_pageheader();
		$pagecontent = bba_booklist_display($bookbeat,$bookbeatjson,$bookbeatlist);
	}
	$content = $pageheader . $pagecontent;
	return $content;
}

function bba_booklist_display($bookbeat,$bookbeatjson,$bookbeatlist) {
    
    
    // Read JSON
     $result = $bookbeatlist->updateSalesRankFromJSON();
    
     
    // Table form
    $content = "<div id='tabs'>
        <ul>
            <li><a href='#tab-1'>Amazon US</a></li>
            <li><a href='#tab-2'>Amazon UK</a></li>
            <li><a href='#tab-3'>Compare</a></li>
        </ul>

        <div id='tab-1'>";
    $content = $content .  "<h2>Amazon US Data</h2><p>Click on the column heads to sort.</p><TABLE id='booklist' class='tablesorter {sortlist: [[2,0]]}'><THEAD><TR><TH>Title</TH><TH>Author</TH><TH>Sales Rank</TH><TH>Num Reviews</TH><TH>Avg Rating</TH></TR></THEAD><TBODY>";
    
    // Display book list
    foreach ($result as $res){
        if ($res->is_author == TRUE){
                $content = $content . "<tr class='author'>";
        }
        else{
            $content = $content . "<tr>";
            }
        $content = $content .  "<td>" . $res->book_title . "</td><td>" . $res->author_name . "</TD><TD>" . $res->amazon->sales_rank . "</TD><TD>" . $res->amazon->num_reviews . "</TD><TD>" . $res->amazon->avg_ratings . "</TD></TR>";
    }
//    
    $content = $content . "</TBODY></TABLE></DIV>";
    
    
    $content = $content .  "<div id='tab-2'><h2>Amazon UK Data</h2><TABLE id='booklist2' class='tablesorter {sortlist: [[2,0]]}'><THEAD><TR><TH>Title</TH><TH>Author</TH><TH>Sales Rank</TH><TH>Num Reviews</TH><TH>Avg Rating</TH></TR></THEAD><TBODY>";

    // Display book list
    foreach ($result as $res){
        if ($res->is_author == TRUE){
        $content = $content . "<tr class='author'>";
        }
        else{
            $content = $content . "<tr>";
            }
        $content = $content .  "<td>" . $res->book_title . "</td><td>" . $res->author_name . "</TD><TD>" . $res->amazon_uk->sales_rank . "</TD><TD>" . $res->amazon_uk->num_reviews . "</TD><TD>" . $res->amazon_uk->avg_ratings . "</TD></TR>";
        
    }


    // show table
     $content = $content . "</TBODY></TABLE></div>";
     $content = $content . "<div id='tab-3'><H2>Comparative Data</H2><TABLE id='booklist3' class='tablesorter {sortlist: [[2,0]]}'><THEAD><TR><TH>Title</TH><TH>Author</TH><TH>US Sales Rank</TH><TH>UK Sales Rank</TH></TR></THEAD><TBODY>";

    // Display book list
    foreach ($result as $res){
        if ($res->is_author == TRUE){
            $content = $content . "<tr class='author'>";
        }
        else{
            $content = $content . "<tr>";
            }
        $content = $content .  "<td>" . $res->book_title . "</td><td>" . $res->author_name . "</TD><TD>" . $res->amazon->sales_rank . "</TD><TD>" . $res->amazon_uk->sales_rank . "</TD></TR>";
    }
        
     $content = $content . "</TBODY></TABLE></div></div>";

    // Button and text to update JSON
     $curr_time =strtotime("10:30pm April 15 2014");
     $json_time = $bookbeatjson->getTimestamp();
     $content = $content . "<p>Updated as of: " . $json_time . "</p>";
     $content = $content . "<p>Elapsed Time: " . bba_getElapsedTime($curr_time,$json_time) . "</p>";
        $content = $content . "<form action = '' name='updateJSON' method = 'post'>
                <input type = 'hidden' name='formtype' value='updateJSON' />
                <button type='submit'>Update Rankings</button>
                </form><p>";


    return $content;
}

function bba_book_search($searchText){
	$bookbeatsearch = new BookBeatSearch();	
	$bookbeatsearch->clearSearch(); 

	$content = "<div id='searchForm'>";
	$content = $content . $bookbeatsearch->getSearchForm($searchText);
	
	if (strlen($searchText) > 0){
		$bookbeatsearch->BookSearch($searchText);
		$content = $content . "<div id='searchResults'>";
		$content = $content . $bookbeatsearch->getSearchResultsTable();
	}
	return $content;
}

function bba_book_add($isbn,$asin,$is_author,$author_name,$publisher_name,$publish_date,$title){

	//if(strtoupper($is_author) === "TRUE"){
	//	$is_author = true;	
	//}else{
	//	$is_author = false;	
	//}  
    
    // init BookBeat, BookBeatJSON and BookBeatList instances
    $bookbeat = new BookBeat();
    $bookbeatjson = new BookBeatJSON();
    $bookbeatlist = new BookBeatList();
        
    // set json filename $arg1
    $bookbeatjson->setFilename("booklist.json");

    // wire up bookbeatjson object to bookbeatlist
    $bookbeatlist->setBookBeatJSON($bookbeatjson);
     
    $bookbeatlist->addBook($isbn,$asin,$is_author,$author_name,$publisher_name,$publish_date,$title);
    
    $content = "<div id=\"addcomplete\">";
    $content = $content . "Book added. ISBN:" . $isbn . " " . $title;
    return $content;
	
}
function bba_book_update_is_author($isbn,$is_author){
    // init BookBeat, BookBeatJSON and BookBeatList instances
    $bookbeat = new BookBeat();
    $bookbeatjson = new BookBeatJSON();
    $bookbeatlist = new BookBeatList();
        
    // set json filename $arg1
    $bookbeatjson->setFilename("booklist.json");

    // wire up bookbeatjson object to bookbeatlist
    $bookbeatlist->setBookBeatJSON($bookbeatjson);
     
    $bookbeatlist->setAsAuthor($isbn,$is_author);
}

function bba_book_delete($isbn){
    // init BookBeat, BookBeatJSON and BookBeatList instances
    $bookbeat = new BookBeat();
    $bookbeatjson = new BookBeatJSON();
    $bookbeatlist = new BookBeatList();
        
    // set json filename $arg1
    $bookbeatjson->setFilename("booklist.json");

    // wire up bookbeatjson object to bookbeatlist
    $bookbeatlist->setBookBeatJSON($bookbeatjson);
     
    //$bookbeatlist->deleteBook($isbn);
}

function bba_edit_book_list(){
	
	 // init BookBeat, BookBeatJSON and BookBeatList instances
   $bookbeat = new BookBeat();
   $bookbeatjson = new BookBeatJSON();
   $bookbeatlist = new BookBeatList();
        
    // set json filename $arg1
   $bookbeatjson->setFilename("booklist.json");

    // wire up bookbeatjson object to bookbeatlist
   $bookbeatlist->setBookBeatJSON($bookbeatjson);
	$result=$bookbeatlist->getBooks();    
    
   //the edit table
   $table = "<table id='listEdit' class='tablesorter'>";		
		
	$tableHeader = "<thead>";
		$tableHeader = $tableHeader . "<tr>";
		$tableHeader = $tableHeader . "<th>Title</th>";
		$tableHeader = $tableHeader . "<th>Author</th>";
		$tableHeader = $tableHeader . "<th>Publisher</th>";
		$tableHeader = $tableHeader . "<th>Publication Date</th>";
		$tableHeader = $tableHeader . "<th>Is this your book</th>";
		$tableHeader = $tableHeader . "<th>Update</th>";
		$tableHeader = $tableHeader . "<th>Delete</th>";
		$tableHeader = $tableHeader . "</tr>";
	$tableHeader = $tableHeader . "</thead>";
	
	$tableBody = "<tbody>";

   // Display book list
	foreach ($result as $res){
		$tableRow = "<tr>";        
      $tableRow = $tableRow . "<td>" . $res->book_title . "</td>";
      $tableRow = $tableRow . "<td>" . $res->author_name . "</td>";
      $tableRow = $tableRow . "<td>" . $res->publisher_name . "</td>";
      $tableRow = $tableRow . "<td>" . $res->publish_date . "</td>";
		//update the is author flag      
      $tableRow = $tableRow . "<form action name=\"editList\" method=\"post\">";
      $tableRow = $tableRow . "<input type=\"hidden\" name=\"isbn\" value=\"" . $res->isbn . "\">";
		$tableRow = $tableRow . "<input type=\"hidden\" name=\"editAction\" value=\"update\">";  
		$tableRow = $tableRow . "<input type=\"hidden\" name=\"formtype\" value=\"buildlist\" \>";	
		
		$tableRow = $tableRow . "<input type=\"hidden\" name=\"is_author\" value=False \>";    
      $tableRow = $tableRow . "<td><input type=\"checkbox\" name=\"is_author\" value=True";
      
      if ($res->is_author) {$checked=" checked";}else{$checked="";} 
      $tableRow = $tableRow . $checked . "></td>";  
      
      $tableRow = $tableRow . "<td><input type=\"Submit\" value=\"Update\"></td></form>";
      //delete the book from list
      $tableRow = $tableRow . "<td><form action name=\"editList\" method=\"post\">";
      $tableRow = $tableRow . "<input type=\"hidden\" name=\"isbn\" value=\"" . $res->isbn . "\">";
		$tableRow = $tableRow . "<input type=\"hidden\" name=\"editAction\" value=\"delete\">";  
		$tableRow = $tableRow . "<input type=\"hidden\" name=\"formtype\" value=\"buildlist\" \>";	    
      $tableRow = $tableRow . "<input type=\"Submit\" value=\"Delete\"></form></td>"; 
 
      $tableRow = $tableRow . "</tr>";
      //add row to body
      $tableBody = $tableBody . $tableRow;
   }
	$tableBody = $tableBody ." </tbody>";		
	$table = $table . $tableHeader . $tableBody . "</table>"; 
	
	return $table;
}

?>
