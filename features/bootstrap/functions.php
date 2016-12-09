<?php

// Functions for supporting JavaScript in BookBeat
// December 2016, Level 2 Team, Harvard CSCI-71

//function bba_scripts_method() {
//if ( !is_admin() ) {
//wp_enqueue_script(
//'custom-script',
//plugins_url( 'jscripts.js', __FILE__ ),
//array('jquery')
//);
//wp_enqueue_script('jquery-ui-tabs');
//}
//}
//add_action('wp_enqueue_scripts', 'bba_scripts_method')

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
    
    // Enqueue the javascript
    wp_enqueue_script('custom-script',
plugins_url( 'jscripts.js', __FILE__ ),
array('jquery')
);
}
 
add_action('init', 'load_jquery_ui');


//    
//function bba_stylesheets_method(){
//    wp_register_style('bbaStyleSheets', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/cupertino/jquery-ui.css');
//    wp_enqueue_style( 'bbaStyleSheets');
//}
//add_action('wp_print_styles', 'bba_stylesheets_method');
?>