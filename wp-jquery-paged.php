<?php
/**
*   Plugin name: WP jQuery Pager Plugin
*   Plugin URI: http://wordpress.org/extend/plugins/wp-jquery-pdf-paged/
*   Description: Uses the built-in WP gallery to creates a book with turnable pages.
*   Author: IvyCat Web Services
*   Author URI: http://www.ivycat.com
*   Version: 1.4.0
*   License: GNU General Public License v2.0
*   License URI: http://www.gnu.org/licenses/gpl-2.0.html
 
 ------------------------------------------------------------------------
	WP jQuery Pager, Copyright 2013 IvyCat, Inc. (admins@ivycat.com)
	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

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
