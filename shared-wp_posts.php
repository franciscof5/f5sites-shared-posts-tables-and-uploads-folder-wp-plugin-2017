<?php
/**
 * Plugin Name: Shared wp_posts
 * Plugin URI: http://f5sites.com/shared-wp_posts
 * Description: Shared wp posts, for use in fnetwork
 * Version: 0.1
 * Author: Francisco Matelli Matulovic
 * Author URI: http://franciscomat.com
 * License: Apache 2.0
 */

$settings = {
	"post_table":"f5sites_posts",
	"postmeta_table":"f5sites_postmeta"
}

function use_shared_wp_posts($query) {
	#global $query;
	//global $wp_query;
	#if(!is_page()) {
	//if ( is_page( 'post' ) ) {
	#var_dump(
	//$tipo = $wp_query->query_vars;
	#);
	
	#if($tipo=="post") {
	#if($wp_query->is_post()) {

	#global $wpdb;
	#$wpdb->terms="f5sites_terms";
	#$wpdb->term_relationships="f5sites_term_relationships";
	#$wpdb->term_taxonomy="f5sites_term_taxonomy";

	#$wpdb->comments="f5sites_comments";
	#$wpdb->commentmeta="f5sites_commentmeta";
	
	#echo "<pre>";
	#print_r($query->queried_object->post_type);
	#echo "</pre>";
	$ty = $query->queried_object->post_type;
	#echo $ty!="page";
	if ((isset($ty) && $ty!="page") || $query->is_main_query()) {
	#if($query->is_main_query()) {
		if(!is_admin()) {
		#if(!is_page()) {
			global $wpdb;
		
			$wpdb->posts="f5sites_posts";
			$wpdb->postmeta="f5sites_postmeta";

			$wpdb->terms="f5sites_terms";
			$wpdb->term_relationships="f5sites_term_relationships";
			$wpdb->term_taxonomy="f5sites_term_taxonomy";
			$wpdb->taxonomy="f5sites_taxonomy";
			$wpdb->termmeta="f5sites_termmeta";
		}
	}

		#$wpdb->taxonomy="f5sites_taxonomy";
		#$wpdb->categories="f5sites_categories";
		#$wpdb->term_post2cat="f5sites_post2cat";
		
		#[categories] => focalizador
   		#[post2cat] => focalizador_
		#$wpdb->termmeta="f5sites_termmeta";
		#$wpdb->comments="f5sites_comments";
		#$wpdb->commentmeta="f5sites_commentmeta";
		#[comments] => focalizador_comments
   		# [commentmeta] => focalizador_commentmeta
		#echo "<pre>";
		#print_r($wpdb);
		#echo "</pre><hr />";
		# [] => focalizador_terms
    	#[] => focalizador_term_relationships
    	#[] => focalizador_term_taxonomy
    	#[] => focalizador_termmeta
		#$wpdb->term_taxonomy;
		#var_dump($wpdb);die;	
	#}
		
	//}
	#}
	# is_post_type_archive('movie')
	
}
#add_filter('query', 'use_f5sites_posts');
add_action( 'pre_get_posts', 'use_shared_wp_posts' );

?>