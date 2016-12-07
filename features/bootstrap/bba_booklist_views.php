<?php
/* 
Project: BookBeat, by Level 2 team, Harvard E-71, fall 2016
Latest Date: 12/2016
Contains view functions to be called from bba_bookbeat.php
*/


// Configure
//        require dirname(__FILE__) . "/BookBeatList.php"; 
//        require dirname(__FILE__) . "/BookBeatJSON.php";
//        require dirname(__FILE__) . "/BookBeat.php";

/*
Shows the introductory information and buttons
*/
function bba_pageheader(){
    // Introduction Text    
    $content = "<p>The following App was designed and pushed to our site by the Level 2 team. It is an initial piece of working software to prove we are able to work with external data, and display it on our site. We connect to Amazon's API and the following snippet displays information pulled by our code, to display statistics about the selected books we've chosen. Our github repo is located @";
    
    // Link to GitHub line
    $content = $content . "<a href = 'https://github.com/locustking/Level_2/' >https://github.com/locustking/Level_2/</a> - The implementation file can be found at our github at: <a href='https://github.com/locustking/Level_2/tree/master/features/bootstrap'>https://github.com/locustking/Level_2/tree/master/features/bootstrap</a></p>";
    
    // Search form
    $content = $content . "<p>To create a custom booklist, click the button below.</p><form action = '' name='buildlist' method = 'post'>
                <input type = 'hidden' name='formtype' value='buildlist' />
                <button type='submit'>Build Book List</button>
                </form><p>";
    
    return $content;
}

/*
Placeholder to display book search form
*/
function bba_searchheader(){
    $content = "<p>To build your booklist, enter search terms in the search field below and press Search. You can enter titles, author names, or ASIN numbers.";
    // Button to display booklist 
    $content = $content . "<p>To return to the current book list, use this button.</p><p><form action = '' name='results' method = 'post'>
                <input type = 'hidden' name='formtype' value='results' />
                <button type='submit'>Show Results</button>
                </form>" .
                "<form action = '' name='results' method = 'post'>
                <input type = 'hidden' name='formtype' value='editList' />
                <button type='submit'>Edit List</button>
                </form>"
    return $content;
}
    

?>