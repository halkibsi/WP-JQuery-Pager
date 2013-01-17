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

if ( ! function_exists( 'add_action' ) )
	wp_die( 'You are trying to access this file in a manner not allowed.', 'Direct Access Forbidden', array( 'response' => '403' ) );

if ( ! defined( 'ICJPAGE_DIR' ) )
	define( 'ICJPAGE_DIR', plugin_dir_path( __FILE__ ) );
	
if ( ! defined( 'ICJPAGE_URL' ) )
	define( 'ICJPAGE_URL', plugin_dir_url( __FILE__ ) );

class WPJqueryPaged {
    
    protected $atts;

    public function __construct() {
        add_shortcode( 'wp-jquery-paged', array( &$this, 'display' ) );
    }
    
    public function display( $atts ) {
        
		$this->atts = wp_parse_args( $atts, array(
			'use_styles' => true,
			'ids' => false
		) );
		if( true === $this->atts['use_styles'] ) 
			self::load_assets( );
        return self::output_pages( );
    }
	
	protected function output_pages( ) {
		global $post;
		$output = '';
		$imgs = self::get_gallery_page_imgs( $post->ID );
		ob_start();
			require_once 'assets/views/output-view.php';
		$output .= ob_get_contents( );
		ob_end_clean( );
		return $output;
	}
	
	protected function load_assets( ) {
		wp_enqueue_script( 'jq-easing', plugins_url('assets/jquery.easing.1.3.js', __FILE__), array( 'jquery' ) );
		wp_enqueue_script( 'jq-booklet', plugins_url('assets/jquery.booklet.1.1.0.min.js', __FILE__), array( 'jquery' ) );
		wp_enqueue_style( 'jq-booklet-styles', plugins_url('assets/booklet-styles.css', __FILE__) );
	}
    
    protected function get_gallery_page_imgs( $id ) {
       global $wpdb;
       $given_ids = $this->atts['ids'];
       if ( $given_ids ) :
			$sql = "SELECT * from ".$wpdb->posts." WHERE post_type='attachment' AND ID IN ( $given_ids ) ORDER BY FIELD(ID, $given_ids)";
		else :
			$sql = "SELECT * from ".$wpdb->posts." WHERE post_type='attachment' AND menu_order > 0 AND post_parent=$id
				AND post_mime_type LIKE 'image/%' ORDER BY menu_order ASC";
        endif;
        return $wpdb->get_results( $sql );
   }
   
}

function init_JqueryPaged(){
	new WPJqueryPaged();
}
add_action( 'init', 'init_JqueryPaged' );
