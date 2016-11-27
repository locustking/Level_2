<?php
/**
 * @package BBA_DB_STAGE
 * @version 1.0
 */
/*
Plugin Name: Book Beat Staging
Plugin URI: http://www.bookbeatapp.com
Description: Staging Plugin for Book Beat. A tool for authors to do competitive analysis
Author: Havard E-71, Fall 2016, Level 2 SCRUM tam
Version: 1.0
Author URI: http://www.bookbeatapp.com
*/

    // configuration
        require dirname(__FILE__) . "/BookBeatList.php"; 
        require dirname(__FILE__) . "/BookBeatJSON.php";
        require dirname(__FILE__) . "/BookBeat.php";

    // Adds shortcode to call control loop from page
    add_shortcode( 'bbastage_display', 'bookbeat_stage_func' );

function bookbeat_stage_func($atts){

    
    //    Introduction Text    
    $content = "<p>The following App was designed and pushed to our site by the Level 2 team. It is an initial piece of working software to prove we are able to work with external data, and display it on our site. We connect to Amazon's API and the following snippet displays information pulled by our code, to display statistics about the selected books we've chosen. Our github repo is located @";
    $content = $content . "<a href = 'https://github.com/locustking/Level_2/' >https://github.com/locustking/Level_2/</a> - The implementation file can be found at our github at: <a href='https://github.com/locustking/Level_2/tree/master/features/bootstrap'>https://github.com/locustking/Level_2/tree/master/features/bootstrap</a></p><p>Click on the column heads to sort.";

    // Table form
    $content = $content . "<div>";
    $content = $content .  "<TABLE id='booklist' class='tablesorter {sortlist: [[2,0]]}'><THEAD><TR><TH>Title</TH><TH>Author</TH><TH>Sales Rank</TH><TH>Num Reviews</TH><TH>Avg Rating</TH></TR></THEAD><TBODY>";
    
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
    $result = $bookbeatlist->updateSalesRank();
    
    // Display book list
    foreach ($result as $res){
        $content = $content .  "<tr><td>" . $res->book_title . "</td><td>" . $res->author_name . "</TD><TD>" . $res->sales_rank . "</TD><TD>" . $res->num_reviews . "</TD><TD>" . $res->avg_ratings . "</TD></TR>";
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

?>
