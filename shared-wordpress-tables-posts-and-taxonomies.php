<?php
/**
 * Plugin Name: Shared WordPress Posts & Taxonomies Tables
 * Plugin URI: http://www.f5sites.com/shared-wordpress-tables-posts-and-taxonomies
 * Description: Shared WordPress Tables Posts and Taxonomies for multiple wp install under the same database, by default wp only can share users. Made for use in fnetwork. (woocommerce, pdfcatalog, wpmu ready)
 * Version: 0.2
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

#add_action( 'woocommerce_init', 'shared_for_real' );widgets_init
add_action( 'plugins_loaded', 'shared_for_real' );

#add_action( 'after_setup_theme', 'shared_for_real' );
#add_action( 'parse_site_query', 'shared_for_real' );

add_action( 'switch_blog', 'shared_for_real' );
#add_action( 'wp_loaded', 'shared_for_real' );


#add_action( 'parse_site_query', 'shared_for_real' );
#add_action( "init", "shared_for_real" );
#add_action("admin-init", "shared_for_real");
#shared_for_real();

function shared_for_real($query) {
	set_databases();
	#
	if(isset($query->query["post_type"]))
	$type = $query->query["post_type"];
	#
	$domain=$_SERVER['HTTP_HOST'];
	
	$idObj = get_category_by_slug($domain);
	#var_dump($idObj);die;
	if(isset($idObj->term_id))
	$id = $idObj->term_id;
	#
	$is_pdf_catalog = isset($_GET["pdfcat"]);
	$is_pdf_catalog_all = isset($_GET["all"]);
	$is_defined_post_type = isset($type);
	
	#if(!isset($id))
	#$id = 357;
	
	#
	#echo "type ".$type;
	#$query->query_vars["category__in"] = $id;
	#$query->query_vars["category__in"] = 357;
	#var_dump("tyyyyype: ".$type. " is_shop: ".is_shop(). "domain: ".$domain. " is_woocommerce(): ".is_woocommerce(). "?pdfcat: ".$is_pdf_catalog. " gettype: ".gettype($query));
	#var_dump($query); #&& !is_pdf_catalog
	if(!$is_defined_post_type || $type=="product") { # && !is_pdf_catalog
		if(gettype($query)!="string" && gettype($query)!="integer") {
			if(function_exists("is_woocommerce"))
			#var_dump(is_woocommerce());die;
			$is_woocommerce = is_woocommerce();
			else
			$is_woocommerce = false;
			if($is_woocommerce || $is_pdf_catalog) { # || $is_pdf_catalog
				if(!$is_pdf_catalog_all and !is_admin()) #not
				$query->set( 'product_cat', $domain );
				#echo $domain;die;
			} else {
				if(isset($id))
				$query->set( 'cat', $id );
			}
		}
	}
	
	

	#if ( $query->is_home ) {
	#product, shop_order, shop_coupon
	
		
		
		#woocommerce
		#$type=$query->query["post_type"];
		#if(!isset($type))
		#if($type==NULL)

	#}
}

function set_databases() {
	global $wpdb;
	
	$wpdb->posts="f5sites_posts";
	$wpdb->postmeta="f5sites_postmeta";
	$wpdb->comments="f5sites_comments";
	$wpdb->commentmeta="f5sites_commentmeta";
	$wpdb->links="f5sites_links";
	
	$wpdb->terms="f5sites_terms";
	$wpdb->term_taxonomy="f5sites_term_taxonomy";
	$wpdb->term_relationships="f5sites_term_relationships";
	$wpdb->termmeta="f5sites_termmeta";
	$wpdb->taxonomy="f5sites_taxonomy";
	
	$wpdb->categories="f5sites_categories";
	$wpdb->term_post2cat="f5sites_post2cat";
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
NO NEED FOR TRICK ANYMORE
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

