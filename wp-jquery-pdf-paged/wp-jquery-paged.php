<?php
/**
*   Plugin name: WP jQuery Pager Plugin
*   Plugin URI: http://wordpress.org/extend/plugins/wp-jquery-pdf-paged/
*   Description: Uses the built-in WP gallery to creates a book with turnable pages.
*   Author: IvyCat Web Services
*   Author URI: http://www.ivycat.com
*   Version: 1.03
*   License: GPLv3
**/
class WPJqueryPaged{
    
    public function __construct(){
        add_shortcode( 'wp-jquery-paged', array( &$this, 'display' ) );
        wp_enqueue_script( 'jq-easing', plugins_url('assets/jquery.easing.1.3.js', __FILE__), array( 'jquery' ) );
        wp_enqueue_script( 'jq-booklet', plugins_url('assets/jquery.booklet.1.1.0.min.js', __FILE__), array( 'jquery' ) );
        wp_enqueue_style( 'jq-booklet-styles', plugins_url('assets/booklet-styles.css', __FILE__) );
    }
    
    public function display(){
        global $post;
        $imgs = self::get_gallery_page_imgs( $post->ID );
        //fprint_r( $imgs );
         ob_start();
         require_once 'assets/views/output-view.php';
         $output .= ob_get_contents();
         ob_end_clean();
         return $output;
    }
    
    protected function get_gallery_page_imgs( $id ){
        global $wpdb;
        $sql = "SELECT * from ".$wpdb->posts." WHERE post_type='attachment' AND menu_order > 0 AND post_parent=$id AND post_mime_type LIKE 'image/%' ORDER BY menu_order ASC";
        //echo $sql;
        return $wpdb->get_results( $sql );
   }
   
}
new WPJqueryPaged();
