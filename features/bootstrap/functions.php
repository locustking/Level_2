<?php

// Functions for supporting JavaScript in BookBeat
// December 2016, Level 2 Team, Harvard CSCI-71

function load_jquery_ui() {
    global $wp_scripts;
 
    // tell WordPress to load jQuery UI tabs
    wp_enqueue_script('jquery-ui-tabs');
 
    // get registered script object for jquery-ui
    $ui = $wp_scripts->query('jquery-ui-core');
    
 
    // tell WordPress to load the Smoothness theme from Google CDN
    $protocol = is_ssl() ? 'https' : 'http';
    $url = "$protocol://ajax.googleapis.com/ajax/libs/jqueryui/{$ui->ver}/themes/smoothness/jquery-ui.min.css";
    wp_enqueue_style('jquery-ui-smoothness', $url, false, null);
    
    // load the table sorter from CDN
    $protocol = is_ssl() ? 'https' : 'http';
    $url = "$protocol://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.26.0/js/jquery.tablesorter.min.js";
    wp_enqueue_script('jquery-tablesorter', $url, false, null);
    
    // Enqueue the javascript
    $url = plugins_url( 'jscripts.js', __FILE__ );
    
    wp_enqueue_script('custom-script', $url,array('jquery'));
    // load the plug in style sheet
    $url = plugins_url( 'wp-style.css', __FILE__ );
    wp_enqueue_style('wp-style.css',$url);
}
 
add_action('init', 'load_jquery_ui');

function bba_getElapsedTime($timestamp){
//    $now = strtotime(date('Y-m-d')); 
    $now = strtotime(date('2016-12-05'));
    $json_date = strtotime(substr($timestamp,0,10));
    $datediff = $json_date - $now;
    $elapsed = floor($datediff / (60 * 60 * 24));
    return $elapsed;
}
    
function updateSources($bookbeatlist){
    $source = "amazon";
    $bookbeatlist->updateSalesRank($source);
    $source = "amazon_uk";
    $bookbeatlist->updateSalesRank($source);
}

?>