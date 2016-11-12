<?php
/**
 * @package BBA_DB
 * @version 1.0
 */
/*
Plugin Name: Book Beat
Plugin URI: http://www.bookbeatapp.com
Description: A tool for authors to do competitive analysis
Author: Havard E-71, Fall 2016, Level 2 SCRUM tam
Version: 1.0
Author URI: http://www.bookbeatapp.com
*/

    // configuration
        require dirname(__FILE__) . "/includes/bba_config.php"; 
        require dirname(__FILE__) . "/includes/bba_helpers.php";
        require dirname(__FILE__) . "/includes/bba_book_beat_class.php";

    // Adds shortcode to call control loop from page
    add_shortcode( 'bba_display', 'bookbeat_func' );

function bookbeat_func($atts){
    $book_title = "Programming with Cucumber";
    $obj = new BookBeat;
//    $funcname = "setBookTitle";
    $obj->setBookTitle($book_title);  
//    $funcname = "viewBookTitle";
    $obj->viewBookTitle($book_title); 
}

?>
