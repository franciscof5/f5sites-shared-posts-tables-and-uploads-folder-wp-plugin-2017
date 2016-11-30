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

	$wpdb->comments="f5sites_comments";
	$wpdb->commentmeta="f5sites_commentmeta";
	$wpdb->links="f5sites_links";
	$wpdb->posts="f5sites_posts";
	$wpdb->postmeta="f5sites_postmeta";

	#$wpdb->categories="f5sites_categories";
	#$wpdb->term_post2cat="f5sites_post2cat";

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
}

?>

