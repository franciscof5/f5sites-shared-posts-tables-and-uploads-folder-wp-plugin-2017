<?php
/**
 * Plugin Name: Shared Post Type & Taxonomies Table + Uploads Folder
 * Plugin URI: https://www.f5sites.com/shared-wordpress-tables-posts-and-taxonomies
 * Description: Shared WordPress Tables Posts and Taxonomies for multiple wp install under multiple domains (using the same database), by default wp-config.php only can share users, limiting scalability. It is for advanced users and optimized for WordPress as composer package. Just create a wordpress category with the current site domain, like www.f5sites.com or www.franciscomat.com and it will only be displayed in the correct location Original project for use in f5sites fnetwork. (woocommerce, pdfcatalog, wpmu ready)
 * Version: 1.0
 * Author: Francisco Matelli Matulovic
 * Author URI: https://www.franciscomat.com
 * License: Apache 2.0
 */


#$settings = {#	"post_table":"1fnetwork_posts",#	"postmeta_table":"1fnetwork_postmeta"#}
function set_new_database_schema() {
	global $wpdb;
	#
	$wpdb->posts="1fnetwork_posts";
	$wpdb->postmeta="1fnetwork_postmeta";
	#
	$wpdb->comments="1fnetwork_comments";
	$wpdb->commentmeta="1fnetwork_commentmeta";
	#
	$wpdb->links="1fnetwork_links";
	#
	$wpdb->terms="1fnetwork_terms";
	$wpdb->term_taxonomy="1fnetwork_term_taxonomy";
	$wpdb->term_relationships="1fnetwork_term_relationships";
	$wpdb->termmeta="1fnetwork_termmeta";
	$wpdb->taxonomy="1fnetwork_taxonomy";
	# OLD WP SETTINGS
	#$wpdb->categories="1fnetwork_categories"; OLD WP SETTINGS
	#$wpdb->term_post2cat="1fnetwork_post2cat"; OLD WP SETTINGS
}
if(!is_network_admin()) {
	#need to be inserted in the 3 hooks (and probably more)
	add_action( 'pre_get_posts', 'force_database_aditional_tables_share' );
	add_action( 'plugins_loaded', 'force_database_aditional_tables_share' );
	add_action( 'switch_blog', 'force_database_aditional_tables_share' );
	#shared upload dir, comment to un-share
	add_filter( 'upload_dir', 'shared_upload_dir' );
}

function force_database_aditional_tables_share($query) {
	#
	set_new_database_schema();
	#
	if(function_exists("is_woocommerce")) {
		add_action( 'woocommerce_before_shop_loop_item', 'redirect_to_correct_store_in_shop_loop_title' );
		add_filter( 'woocommerce_loop_add_to_cart_link', 'redirect_to_correct_store_in_shop_loop_cart', 10, 2 ); 
		#
		add_action( 'woocommerce_before_main_content', 'redirect_to_correct_store_in_single_view', 10, 2);
	}

	#global $query;
	#global $wp_the_query;
	#var_dump($wp_the_query);

	#get current domain
	$current_server_name = $_SERVER['SERVER_NAME'];
	#$current_server_name_with_dashes = preg_replace('/\./', '-', $current_server_name);

	#get post type
	if(isset($query->query["post_type"]))
	$type = $query->query["post_type"];
	
	$current_server_name_shared_category = get_category_by_slug($current_server_name);

	if(isset($current_server_name_shared_category->term_id))
	$current_server_name_shared_category_id = $current_server_name_shared_category->term_id;
	#
	$is_pdf_catalog = isset($_GET["pdfcat"]);
	$is_pdf_catalog_all = isset($_GET["all"]);
	$is_defined_post_type = isset($type);
	$is_category = $query->query["is_category"];
	$category = $query->query["product_cat"];
		
	#$query->query_vars["category__in"] = $current_server_name_shared_category_id;
	#$query->query_vars["category__in"] = 357;
	#var_dump("tyyyyype: ".$type. " is_shop: ".is_shop(). "domain: ".$current_server_name. " is_woocommerce(): ".is_woocommerce(). "?pdfcat: ".$is_pdf_catalog. " gettype: ".gettype($query)." id:".$current_server_name_shared_category_id." category:".$category." is_categor:".$is_category);
	
	#the magic happens here
	if(!$is_defined_post_type || $type=="product") { # && !is_pdf_catalog
		if(gettype($query)!="string" && gettype($query)!="integer") {
			if(function_exists("is_woocommerce"))
			$is_woocommerce = is_woocommerce();
			else
			$is_woocommerce = false;
			if($is_woocommerce || $is_pdf_catalog) { # || $is_pdf_catalog
				if(!$is_pdf_catalog_all and !is_admin()) #not
				$query->set( 'product_cat', $current_server_name );
				#echo $current_server_name;die;
			} else {
				if(isset($current_server_name_shared_category_id))
				$query->set( 'cat', $current_server_name_shared_category_id );
			}
		}
	}
	#if ( $query->is_home ) {#product, shop_order, shop_coupon
}

function redirect_to_correct_store_in_shop_loop_title() {
	$purl = get_product_correct_url_by_id();
	if(!$purl) {
		$purl = get_permalink();
	} else {
		echo "<marquee> ! FNETWORK ! </marquee>";
	}
	echo '<a href="' . $purl . '" class="woocommerce-LoopProduct-link">';
}

function redirect_to_correct_store_in_shop_loop_cart( $array, $int ) { 
	$purl = get_product_correct_url_by_id();
	if(!$purl) {
		return $array;
	} else {
		echo "Produto disponível somente em <br />";
		$parse = parse_url($purl);
		echo "<a href=$purl class='button product_type_booking add_to_cart_button'>".$parse['host']."</a>";
	}
}

#
function get_product_correct_url_by_id($postid=0) {
	$current_server_name = $_SERVER['SERVER_NAME'];
	if($postid==0) {
		global $post;
		$product_id = $post->ID;
	}
	$terms = wp_get_post_terms( $product_id, 'product_cat' );
	#$terms = wp_get_post_terms( $product_id, 'product_cat' );
	foreach ( $terms as $term ) {
		$categories[] = $term->name;
		#child of
		if($term->parent==235) {
			#echo $parent_slug = get_term_by('id', $term->parent, 'product_cat');
			#if($parent_slug==$current_server_name) {
			$current_product_base_url=$term->name;
		}
	}
	if ( !in_array( $current_server_name, $categories ) ) {
		#$prouct_is_being_viewed_outside_home_url
		$perm = get_permalink( $post->ID );
		$fullurlr = str_replace($current_server_name, $current_product_base_url, $perm);
		#var_dump($fullurlr);
		#wp_redirect($fullurlr);
		return $fullurlr = str_replace($current_server_name, $current_product_base_url, $perm);
	} else {
		return;
	}
}
function redirect_to_correct_store_in_single_view () {
	if(is_product()) {
		$purl = get_product_correct_url_by_id();
		if($purl) {
			wp_redirect($purl);
		}
	}
}
function shared_upload_dir( $dirs ) {
    $dirs['baseurl'] = network_site_url( '/wp-content/uploads/shared-wp-posts-uploads-dir' );
    $dirs['basedir'] = ABSPATH . 'wp-content/uploads/shared-wp-posts-uploads-dir';
    $dirs['path'] = $dirs['basedir'] . $dirs['subdir'];
    $dirs['url'] = $dirs['baseurl'] . $dirs['subdir'];

    return $dirs;
}
// define the woocommerce_loop_add_to_cart_link callback 
/*function redirect_to_correct_store_in_shop_loop_cart( $array, $int ) { 
	// make filter magic happen here... 

	global $post;
	
	$terms = wp_get_post_terms( $post->ID, 'product_cat' );
	
	#var_dump($categories); 
	$current_server_name = $_SERVER['SERVER_NAME'];
	#$current_server_name_with_dashes = str_replace('-', '.', $current_server_name);
	$current_server_name_with_dashes = preg_replace('/\./', '-', $current_server_name);
	#echo " current_server_name_with_dashes:".$current_server_name_with_dashes;
	foreach ( $terms as $term ) {
		$categories[] = $term->slug;
		if($term->parent==235) {
			$lojaatual=$term->slug;
		}
	}
	if ( !in_array( $current_server_name_with_dashes, $categories ) ) {
		if($lojaatual) {
			echo "Produto disponível somente em <br />";
			$loja_do_product_url = preg_replace('/-/', '.', $lojaatual);
			$perm = get_permalink( $post->ID );
			$fullurlr = str_replace($current_server_name, $loja_do_product_url, $perm);
			echo "<a href=$fullurlr>$loja_do_product_url</a>";
		} else {
			echo "Produto indisponível";
		}
	} else {
		return $array;
	}
	/*foreach ( $terms as $term ) {
		#$categories[] = $term->slug;
		if ( !in_array( $current_server_name_with_dashes, $categories ) ) {
			echo "Produto disponível somente em: ";
		} else {
		  return $array;
		}
	}*
	return;

}; */
// add the filter 

#function force_database_aditional_tables_share(){}
/*function shared_for_real($query) {
	set_new_database_schema();
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
}*/


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
					$blog_prefix = "1fnetwork_";
					else
					$blog_prefix = $this->get_blog_prefix( $blog_id );#$GLOBALS['domain_table_prefix'];
					$tables[ $table ] = $blog_prefix . $table;
				}
*/
?>

