<?php
/*
Plugin Name: Remove Zemanta Bug
Plugin URI: http://cfo.com
Description: This plugin filters the content to remove the Zemanta bug produced by using the plugin. 
Version: 0.1
Author: Aram Zucker-Scharff
Author URI: http://aramzs.me
License: GPL2
*/

/*  Copyright 2012  CFO Publishing LLC  (email : aramzs@cfo.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once('rzb_simple_html_dom.php');

class ZemantaCleaner {

	function __construct() {
		add_filter('the_content', array($this, 'filter_bug_from_content'));
		add_filter('the_content_feed', array($this, 'filter_bug_from_content'));
		add_filter('wpseo_opengraph_image', array($this, 'zfilter_wpseo_opengraph_image'));
	}
	
	public static function filter_bug_from_content($content){
		if (is_single() || is_feed() || is_page()){
			$html = rzb_str_get_html($content);
			if ( !empty($html) ){
				$divs = $html->find('div.zemanta-pixie');
				if ( !isset($divs) || !$divs){
					return $content;
				} else {				
					foreach ($divs as $div){
						$div->outertext = '';
					}
					
					return $html;
				}
			} else {
				return $content;
			}
		} else {
			return $content;
		}
	}
	
	public static function zfilter_wpseo_opengraph_image($url){
		if (is_single()|| is_page()){
			if (strpos( $url, 'zemanta' ) != 0 ){
				return NULL;
			} else {
				return $url;
			}
		} else { 
			return $url;
		}
	
	}

}

$ZCPlugin = new ZemantaCleaner();