<?php
/*
	Plugin Name: Custom Disable Feeds
	Description: Disable Wordpress feeds except from Homepage
	Author: Seocom
	Version: 0.9.0
*/

class CustomDisableFeeds
{
    private $remove_general_feeds = false;

	function __construct()
	{
		add_action('do_feed', array(&$this, 'disable'), 1);
		add_action('do_feed_rdf', array(&$this, 'disable'), 1);
		add_action('do_feed_rss', array(&$this, 'disable'), 1);
		add_action('do_feed_rss2', array(&$this, 'disable'), 1);
		add_action('do_feed_atom', array(&$this, 'disable'), 1);
		add_action('do_feed_rss2_comments', array(&$this, 'disable'), 1);
		add_action('do_feed_atom_comments', array(&$this, 'disable'), 1);
		
		add_action('wp', array(&$this, 'remove_links'), 1);		
	}

	function disable() {
		if ( $this->allowFeeds() ) {
			return;
		}
		wp_die( __( 'Feeds are disabled, please visit the <a href="'. esc_url( home_url( '/' ) ) .'">homepage</a>!' ), 410 );
	}
	
	function remove_links() {
		if ( $this->allowFeeds() ) {
			return;
		}
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		if ( $this->remove_general_feeds) { remove_action( 'wp_head', 'feed_links', 2 ); }
	}
	
	function allowFeeds() {
		if ( is_feed() ) {
			if ( $this->isHomePageFeed() ) {
				return true;
			}
//			if ( $this->isCategoryFeed() ) {
//				return true;
//			}
		} else {
			if ( is_front_page() ) {
				return true;
			}
//			if ( is_category() ) {
//				return true;
//			}
		}
		return false;		
	}
	
	function isHomePageFeed() {
		global $wp_query;

		if ( count($wp_query->query) == 1 && !empty($wp_query->query['feed']) ) {
			return true;
		}
		
		return false;
	}
	
//	function isCategoryFeed() {
//		global $wp_query;
//
//		if ( count($wp_query->query) == 2
//			&& !empty($wp_query->query['category_name'])
//			&& !empty($wp_query->query['feed'])
//			) {
//			return true;
//		}
//
//		return false;
//	}
	
}

$CustomDisableFeeds = new CustomDisableFeeds();

