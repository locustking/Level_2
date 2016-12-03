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
a. create helpers/functions files: to render, set globals
b. make basic loop that shows bba_booklist_view.php
c. add button to show search form; make it just show test message for now.
d. add call back to bba_booklist_view to other php
e. add in tablesorter using enqueue
*/

    // configuration
//        require dirname(__FILE__) . "/BookBeatList.php"; 
//        require dirname(__FILE__) . "/BookBeatJSON.php";
//        require dirname(__FILE__) . "/BookBeat.php";
        require dirname(__FILE__) . "/bba_booklist_views.php";

    // Adds shortcode to call control loop from page
    add_shortcode( 'bba_display', 'bookbeat_func' );

function bookbeat_func($atts){
        // Check for POST method which means action, else render booklist view
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            // Debug statement: 
            echo "<span style='background-color: green'>POST from : " . $_POST['formtype'] . "</span>";
            // determine what to do based on formtype of post (what happens if none?)
            switch ($_POST['formtype'])
            {
                // clicked to edit booklist 
                case 'buildlist' :
                    $pagecontent = bba_searchform();
                    break;
                // redisplay booklist after editing it
                case 'results' :
                    $pagecontent = "<p>Results Updated</p>" . bba_booklist_display();
                    break; 
                // Added item to JSON
                case 'addItem' :
                   // will say something about item added to JSON
                   break;
            } 
        }
        // if nothing relevant, show the booklist
        else
        {
            
              $pagecontent = $content . bba_booklist_display();
//            $values = array ("title"=>"Search");
//            gaf_render("gaf_search_form.php", $values);
        }
            
    // if post (from search form) do search and show results
    
    $pageheader = bba_pageheader();
    $content = $pageheader . $pagecontent;
    return $content;
 
}

?>

