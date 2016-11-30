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


#$settings = {
#	"post_table":"f5sites_posts",
#	"postmeta_table":"f5sites_postmeta"
#}

/*function use_shared_wp_posts($query) {
	$ty = $query->query["post_type"];

	#var_dump(" qqqqq ");
	#var_dump($query);
	$isp = is_page();
	$ism = is_main_query();
	$issin = is_single();
	#$is_loop = is_loop();
	#var_dump("type: ".$ty. ", ispage: ".$isp . ", main_query: ".$ism.", issin:".$issin);
	
	global $wpdb;
	$wpdb->posts_original = $wpdb->posts;
	$wpdb->postmeta_original = $wpdb->postmeta;
	$wpdb->categories_original = $wpdb->categories;
	$wpdb->term_post2cat_original = $wpdb->term_post2cat;
	$wpdb->terms_original = $wpdb->terms;
	$wpdb->term_relationships_original = $wpdb->term_relationships;
	$wpdb->term_taxonomy_original = $wpdb->term_taxonomy;
	$wpdb->taxonomy_original = $wpdb->taxonomy;
	$wpdb->termmeta_original = $wpdb->termmeta;
	
	#if(!isset($isp))$isp=false;
	#if($ty==NULL || $ty=="" || $ty=="nav_menu_item") {
		$wpdb->posts="f5sites_posts";
		$wpdb->postmeta="f5sites_postmeta";

		$wpdb->categories="f5sites_categories";
		$wpdb->term_post2cat="f5sites_post2cat";

		$wpdb->terms="f5sites_terms";
		$wpdb->term_relationships="f5sites_term_relationships";
		$wpdb->term_taxonomy="f5sites_term_taxonomy";
		$wpdb->taxonomy="f5sites_taxonomy";
		$wpdb->termmeta="f5sites_termmeta";
	#} else {
		/*$wpdb->posts=$wpdb->posts_original;
		$wpdb->postmeta=$wpdb->postmeta_original;
		$wpdb->categories = $wpdb->categories_original;
		$wpdb->term_post2cat = $wpdb->term_post2cat_original;
		$wpdb->terms = $wpdb->terms_original;
		$wpdb->term_relationships = $wpdb->term_relationships_original;
		$wpdb->term_taxonomy = $wpdb->term_taxonomy_original;
		$wpdb->taxonomy = $wpdb->taxonomy_original;
		$wpdb->termmeta = $wpdb->termmeta_original;*/
	#}
	/*if(!isset($ty)) {
		#echo "NOT: ";
		#var_dump($query);
		$wpdb->posts=$wpdb->posts_original;
		$wpdb->postmeta=$wpdb->postmeta_original;
		$wpdb->categories = $wpdb->categories_original;
		$wpdb->term_post2cat = $wpdb->term_post2cat_original;
		$wpdb->terms = $wpdb->terms_original;
		$wpdb->term_relationships = $wpdb->term_relationships_original;
		$wpdb->term_taxonomy = $wpdb->term_taxonomy_original;
		$wpdb->taxonomy = $wpdb->taxonomy_original;
		$wpdb->termmeta = $wpdb->termmeta_original;
	}*/
	#global $wp_post_types;
	#var_dump($wp_post_types);
	#global $query;
	#global $wp_query;
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
	
	#$ty = $query->queried_object->post_type;
	/*
	$ty = $query->query["post_type"];
	echo "<pre>";
	print_r($query);
	print_r("wp_query: ".$wp_query);
	print_r("TYPEEEEE ".$ty);
	print_r("MAIN: ".$query->is_main_query());
	#print_r($query->is_page);
	#print_r($query->queried_object);
	#print_r($query->queried_object->post_type);
	echo "</pre>";
	
	#echo $ty!="page";
	if ($query->is_main_query()) {
	#if($query->is_main_query()) {
		if(!is_admin() && !is_page() && $ty!="page") {
		#if(!is_page()) {
			
			#$wpdb->prefix="f5sites_";

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
	
}*/
#add_filter('query', 'use_f5sites_posts');
#add_action( 'plugins_loaded', 'shared_for_real' );
add_action( 'pre_get_posts', 'shared_for_real' );
#add_action( 'parse_site_query', 'shared_for_real' );

#add_action( "init", "shared_for_real" );
#add_action("admin-init", "shared_for_real");
#shared_for_real();
function shared_for_real($query) {
	#var_dump($query);

	global $wpdb;
	#die;
	/*$wpdb->posts_original = $wpdb->posts;
	$wpdb->postmeta_original = $wpdb->postmeta;
	$wpdb->categories_original = $wpdb->categories;
	$wpdb->term_post2cat_original = $wpdb->term_post2cat;
	$wpdb->terms_original = $wpdb->terms;
	$wpdb->term_relationships_original = $wpdb->term_relationships;
	$wpdb->term_taxonomy_original = $wpdb->term_taxonomy;
	$wpdb->taxonomy_original = $wpdb->taxonomy;
	$wpdb->termmeta_original = $wpdb->termmeta;*/

	#if(!isset($isp))$isp=false;
	#if($ty==NULL || $ty=="" || $ty=="nav_menu_item") {
	#'links', 'postmeta', 'terms', 'term_taxonomy', 'term_relationships', 'termmeta', 'commentmeta' );
	$wpdb->comments="f5sites_comments";
	$wpdb->commentmeta="f5sites_commentmeta";
	$wpdb->links="f5sites_links";
	$wpdb->posts="f5sites_posts";
	$wpdb->postmeta="f5sites_postmeta";

	#$wpdb->categories="f5sites_categories";
	$wpdb->term_post2cat="f5sites_post2cat";

	$wpdb->terms="f5sites_terms";
	$wpdb->term_relationships="f5sites_term_relationships";
	$wpdb->term_taxonomy="f5sites_term_taxonomy";
	$wpdb->taxonomy="f5sites_taxonomy";
	$wpdb->termmeta="f5sites_termmeta";
	
	if ( $query->is_home ) {
		$domain=$_SERVER['HTTP_HOST'];
		$idObj = get_category_by_slug($domain); 
		$id = $idObj->term_id;
		
		$type=$query->query["post_type"];
		
		if($type==NULL)
		$query->set( 'cat', $id );
	}
	#return $query;
	#$args = array( 

	#'category__not_in' => 2 ,

	#'category__in' => 4 

	#);
	#global $wp_query;
	#return $query = new WP_Query( $args );
	#var_dump($query);
	#die;
}
#rename_function('have_posts', 'real_feof');
#override_function('have_posts', '$handle', 'return true;');

#namespace MyNamespace {
#        function have_posts($object) 
#        {
#            echo "<pre>", \print_r($object, true), "</pre>"; 
#        }
#}

#override_function('have_posts', '$a,$b', 'echo "DOING TEST"; return $a * $b;');

#function get_posts3() {
#	die;
#}

?>

