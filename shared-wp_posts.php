<?php
/**
 * Plugin Name: Shared wp_posts
 * Plugin URI: http://f5sites.com/shared-wp_posts
 * Description: Shared wp posts, for use in fnetwork. (not working in wp-mu, trick must be made, end of file))
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

	$wpdb->categories="f5sites_categories";
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
		
		#$type=$query->query["post_type"];
		#if(!isset($type))
		#if($type==NULL)
		if(!isset($query->query["post_type"]))
		$query->set( 'cat', $id );
	}
}


function shared_upload_dir( $dirs ) {
    $dirs['baseurl'] = network_site_url( '/wp-content/uploads/fnetwork' );
    $dirs['basedir'] = ABSPATH . 'wp-content/uploads/fnetwork';
    $dirs['path'] = $dirs['basedir'] . $dirs['subdir'];
    $dirs['url'] = $dirs['baseurl'] . $dirs['subdir'];

    return $dirs;
}

add_filter( 'upload_dir', 'shared_upload_dir' );

/*
THE TRICK FOR MULTISTE
you must change wp-db.php file for change all queries before anything, replacing content on line 1055 (please suggest an improvment)

wp-db.php
********************************** REPLACE THAT **********************************************
1059				else
1060					$tables[ $table ] = $blog_prefix . $table;

********************************** WITH THAT **********************************************
				else {	
					#TODO: F5SITES AQUI PASSAR PRA PLUGIN 
					#if(in_array($domain, FNETWORK)
					$SHARED_TABLES_NO_PREFIXED = array( 'posts', 'postmeta', 'comments', 'links', 'postmeta', 'terms', 'term_taxonomy', 'term_relationships', 'termmeta', 'commentmeta' );
					if (in_array( $table, $SHARED_TABLES_NO_PREFIXED))
					$blog_prefix = "f5sites_";
					else
					$blog_prefix = $this->get_blog_prefix( $blog_id );#$GLOBALS['domain_table_prefix'];
					$tables[ $table ] = $blog_prefix . $table;
				}
*/
?>

