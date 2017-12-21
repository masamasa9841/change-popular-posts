<?php
/**
 * Main plugin's file.
 *
 * @package change_popular_posts
 */

/**
 * Plugin Name: change_popular_posts
 * Plugin URI: https://github.com/masamasa/9841
 * Description: Unko plugin
 * Version: 1.0
 * Author: Masaya Okawa
 * Author URI: https://okawa.routecompass.net
 * License: GPLv2 or later
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */


if ( is_admin() ) {

	/**
	 * Box init.
	 */
	function add_popular_box_init() {
		add_meta_box( 'popular', 'Popular PostsのViewを上書き', 'add_my_box_popular', 'post', 'side' );
		add_meta_box( 'popular', 'Popular PostsのViewを上書き', 'add_my_box_popular', 'page', 'side' );
	}
	add_action( 'add_meta_boxes', 'add_popular_box_init' );

	/**
	 * Box html.
	 */
	function add_my_box_popular() {
		if ( function_exists( 'wpp_get_views' ) ) {
			$popular = wpp_get_views( get_the_ID() );
			$popular = str_replace( ',', '', $popular );
			$popular = htmlspecialchars( $popular );
			echo '<input type="text" style="width: 100%;" name="popular" value="' . esc_html( $popular ) . '" />';
			echo '<p class="howto" style="margin-top:0;">記事の更新で上書き</p>';
		} else {
			echo '<p class="howto" style="margin-top:0;">Popular Postsが無効</p>';

		}
	}

	/**
	 * Save pageview of Popular Posts.
	 *
	 * @param string $post_id post id.
	 */
	function save_popular_posts_pv( $post_id ) {
		global $wpdb;
		$popular_key = 'popular';
		$table       = $wpdb->prefix . 'popularposts';
		$pageviews   = filter_input( INPUT_POST, 'popular' );
		$wpdb->show_errors();
		$wpdb->update(
			"{$table}data",
			array( 'pageviews' => $pageviews ),
			array( 'postid' => $post_id ),
			array( '%d' ),
			array( '%d' )
		);
	}
	add_action( 'save_post', 'save_popular_posts_pv' );

}
