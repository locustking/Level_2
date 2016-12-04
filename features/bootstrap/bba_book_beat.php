<?php
/**
 * @package BBA_DB
 * @version 2.0
 */
/*
Plugin Name: Book Beat
Plugin URI: http://www.bookbeatapp.com
Description: A tool for authors to do competitive analysis
Author: Havard E-71, Fall 2016, Level 2 SCRUM tam
Version: 2.0
Author URI: http://www.bookbeatapp.com
*/

/*
TODO:
a. fix function operation
b. Add in Daryl code
c. add in tablesorter using enqueue
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

    // Adds shortcode to call control loop from page
    add_shortcode( 'bba_display', 'bookbeat_func' );

function bookbeat_func($atts){
        // Check for POST method which means action, else render booklist view
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            // Debug statement: 
            echo "<span style='background-color: yellow'>POST from : " . $_POST['formtype'] . "</span>";
            // determine what to do based on formtype of post (what happens if none?)
            switch ($_POST['formtype'])
            {
                // clicked to edit booklist 
                case 'buildlist' :
                    $pageheader = bba_searchheader();
						  $searchText = "";                    
                    if(isset($_POST['textSearch'])){
								$pagecontent = "<BR>Searching for:" . $_POST['textSearch'] ;                  		
                    		$searchText = $_POST['textSearch'];
                    }
                    /*if($searchText == ""){
                    	 $pagecontent = $pagecontent . "<BR>Searching for Nothing" ; 
                    }*/
                   
                    $pagecontent = $pagecontent . bba_book_search($searchText);
						  //$pagecontent = "<p>This will show the form to add books to the list. For now, click <a href='http://hotpug.com/bookbeat/search.php' target = '_blank'>here to see the mockup</a>";
  						  break;
                // redisplay booklist after editing it
                case 'results' :
                    $pageheader = bba_pageheader();
                    $pagecontent = bba_booklist_display();
                    break; 
                // Added item to JSON
                case 'addItem' :
						$pageheader = bba_searchheader();
						//$isbn,$asin,$is_author,$author_name
						$pagecontent = bba_book_add($_POST['EAN'],$_POST['ASIN'],$_POST['Is_Author'],$_POST['AuthorName']);                
                   // will say something about item added to JSON
                   break;
            } 
        }
        // if nothing relevant, show the booklist
        else
        {
              $pageheader = bba_pageheader();
              $pagecontent = bba_booklist_display();
//            $values = array ("title"=>"Search");
//            gaf_render("gaf_search_form.php", $values);
        }
            
    // if post (from search form) do search and show results
    
   
    $content = $pageheader . $pagecontent;
    return $content;
 
}

function bba_booklist_display() {
    
 
    // Table form
    $content = "<div>";
    $content = $content .  "<p>Click on the column heads to sort.</p><TABLE id='booklist' class='tablesorter {sortlist: [[2,0]]}'><THEAD><TR><TH>Title</TH><TH>Author</TH><TH>Sales Rank</TH><TH>Num Reviews</TH><TH>Avg Rating</TH></TR></THEAD><TBODY>";
    
    // Get Book Data
    // init BookBeat, BookBeatJSON and BookBeatList instances
    $bookbeat = new BookBeat();
    $bookbeatjson = new BookBeatJSON();
    $bookbeatlist = new BookBeatList();
		
    // set json filename $arg1
    $bookbeatjson->setFilename("booklist.json");

    // wire up bookbeatjson object to bookbeatlist
    $bookbeatlist->setBookBeatJSON($bookbeatjson);

    // update bookbeatlist. this will collect the sales rank from amazon and update the json file.
    // returning an array with the updated list
	$source = "amazon";
    $result = $bookbeatlist->updateSalesRank($source);
    
    // Display book list
    foreach ($result as $res){
        $content = $content .  "<tr><td>" . $res->book_title . "</td><td>" . $res->author_name . "</TD><TD>" . $res->$source->sales_rank . "</TD><TD>" . $res->$source->num_reviews . "</TD><TD>" . $res->$source->avg_ratings . "</TD></TR>";
    }
        

     $content = $content . "</TBODY></TABLE></div>";
     $content = $content . "<script src=https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.26.0/js/jquery.tablesorter.min.js' type='text/javascript'> </script>
<script type='text/javascript'>
			$(document).ready(function()
			{
				$('#booklist').tablesorter();
			}
			);
		</script>";
    return $content;
}
function bba_book_search($searchText){
	$bookbeatsearch = new BookBeatSearch();	
	$bookbeatsearch->clearSearch(); 

	$content = "<div id='searchForm'>";
	$content = $content . $bookbeatsearch->getSearchForm($searchText);
	
	if (strlen($searchText) > 0){
		$bookbeatsearch->BookSearch($searchText);
		$content = "<div id='searchResults'>";
		$content = $content . $bookbeatsearch->getSearchResultsTable();
	}
	return $content;
}

function bba_book_add($isbn,$asin,$is_author_check,$author_name){

	if($is_author_check  == "Yes"){
		$is_author = true;	
	}else{
		$is_author = false;	
	}  
    
    // init BookBeat, BookBeatJSON and BookBeatList instances
    $bookbeat = new BookBeat();
    $bookbeatjson = new BookBeatJSON();
    $bookbeatlist = new BookBeatList();
        
    // set json filename $arg1
    $bookbeatjson->setFilename("booklist.json");

    // wire up bookbeatjson object to bookbeatlist
    $bookbeatlist->setBookBeatJSON($bookbeatjson);
     
    $bookbeatlist->addBook($isbn,$asin,$is_author,$author_name);
    
    $content = "<div id=\"addcomplete\">";
    $content = $content . "Book added. ISBN:" . $isbn;
    return $content;
	
}

?>

