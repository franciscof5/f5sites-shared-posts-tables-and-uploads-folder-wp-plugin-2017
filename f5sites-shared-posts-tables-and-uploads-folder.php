<?php
/* 
 * Plugin Name: F5 Sites | Shared Posts Tables & Uploads Folder
 * Plugin URI: https://www.f5sites.com/software/wordpress/f5sites-shared-posts-tables-and-uploads-folder/
 * Description: Hacks WordPress databases, sharing posts and taxonomies tables for multiple wp install under the same database, by default wp only can share tables users and usermeta. Made for use in fnetworl
 * Tags: woocommerce, pdfcatalog, wpmu, buddypress, bp xprofile, projectimer ready
 * Version: 1.9
 * Author: Francisco Matelli Matulovic
 * Author URI: https://www.franciscomat.com
 * License: GPLv3
 */

if ( ! defined( 'ABSPATH' ) ) {exit;}

#NEVER ENABLE DEBUG IN PRODUCTION SERVER, checked by hostname, if forget to comment line below and send it by mistake to prod
#global $show_debug_log; 
if(isset($_GET["debug_shared_posts"]) && $_GET["debug_shared_posts"]==true) {
	#if(gethostname()!="mail.f5sites.com") {
		$show_debug_log = true;
	#:}
}

if(!is_network_admin()) {
	#
	$current_server_name = $_SERVER['SERVER_NAME'];
	#It mess wpmu install (todo: test)
	show_admin_bar( false );
	
	#Disabled for pesquisa child sites (todo: test)
	if($current_server_name=="pesquisa.f5sites.com" && !is_main_site()) {
		return;
	}

	#todo: try to attach to only one hook
	#PRINCIPAL HOOK
	if($current_server_name!="www.pomodoros.com.br")
	add_action( 'pre_get_posts', 'force_database_aditional_tables_share', 10, 2 );
	
	if(is_admin()) {
		if($current_server_name!="www.pomodoros.com.br") {

			if(is_multisite())
				add_action( 'switch_blog', 'force_database_aditional_tables_share', 10, 2 );
				#ADMIN-BAR cant be disabled in ADMIN, because of it that action must be enabled (todo: understand comment)
			else
				add_action( 'wp_loaded', 'force_database_aditional_tables_share', 10, 2 );
		}
	}
	#
	if($current_server_name!="www.pomodoros.com.br")
	add_action( 'init', 'force_database_aditional_tables_share', 8, 2 );
	#
	add_filter( 'upload_dir', 'shared_upload_dir' );
	#
	add_action( 'woocommerce_checkout_update_order_review', 'force_woo_type');
	#add_action( 'woocommerce_before_shop_loop', 'force_woo_type');
	#add_action( 'woocommerce_after_shop_loop_item', 'force_woo_type' );
	#2020 shop not show products
}

#PRINCIPAL FUNCTION
function set_shared_database_schema() {
	global $wpdb;
	global $show_debug_log;
	global $post_type_current;
	global $woo_priority;

	if ($show_debug_log)
	echo "<strong>set_shared_database_schema();</strong><br>";

	if($woo_priority) {
		if($show_debug_log)
			echo "woo_priority=true <br> (return) ";
		return;
	} else {
		if($show_debug_log)
		echo "woo_priority=false <br>";
	}
	
	#
	if(is_file(dirname(__FILE__)."/config.php")) {
		include("config.php");
		#echo $config["posts"];die;
	} else {
		echo "F5 Sites Shared posts warning: please enter plugin folder and configure it: copy config.example.php and rename it to config.php with you changes";die;	
	}
	
	if(function_exists("is_woocommerce")) {
		if($show_debug_log)
			echo "is_woocommerce exists in this domain: ";
		
		if(!is_admin()) {
			if($show_debug_log)
			echo " but url is not wp-admin, filters needed <br>";
			activate_woocommerce_hooks();
		} else {
			if($show_debug_log)
			echo " is admin, not filters used <br>";
		}
	}

	###if(is_array($post_type_current) && isset($post_type_current[0]))
	###	$post_type_current = $post_type_current[0];
	
	if($show_debug_log)
		echo "loading config: wp posts table ".$config["posts"]."<br>";
	
	$wpdb->posts 				= $config["posts"];
	$wpdb->postmeta 			= $config["postmeta"];	
	$wpdb->terms 				= $config["terms"];
	$wpdb->term_taxonomy 		= $config["term_taxonomy"];
	$wpdb->term_relationships 	= $config["term_relationships"];
	$wpdb->termmeta 			= $config["termmeta"];
	$wpdb->taxonomy 			= $config["taxonomy"];
	#
	$wpdb->comments 			= $config["comments"];
	$wpdb->commentmeta 			= $config["commentmeta"];
	#
	$wpdb->links 				= $config["links"];
}

function force_woo_type() {	
	global $wpdb;
	global $show_debug_log;
	
	if($show_debug_log)
		echo " force_woo_type();, ";
	
	$table_woo_posts	= "6woo_".$wpdb->prefix."shop_order_posts";
	$table_woo_postmeta = "6woo_".$wpdb->prefix."shop_order_postmeta";

	if($show_debug_log)
		echo " force_woo_type(); TABELA $table_woo_posts e $table_woo_postmeta";

	$exists_table_woo_posts = $wpdb->get_var("SHOW TABLES LIKE '$table_woo_posts'");
	$exists_table_woo_postmeta = $wpdb->get_var("SHOW TABLES LIKE '$table_woo_postmeta'");

	#$wpdb->posts 		= $table_woo_posts;
	#$wpdb->postmeta 	= $table_woo_postmeta;
	#$wpdb->posts 		= "9woo_pomodoros_shop_order_posts";#FUNFA
	#$wpdb->postmeta 	= "9woo_pomodoros_shop_order_postmeta";#FUNFA
	
	if(($exists_table_woo_posts!=$table_woo_posts) || ($exists_table_woo_postmeta!=$table_woo_postmeta)) {
		if($show_debug_log)
		echo " separated tables woo not found, creating then ";
		create_separated_woo_table_for_prefix_then_return();
		#die;
	} else {
		if($show_debug_log)
		echo " woo tables found, proceed with change ";

		$wpdb->posts 		= $table_woo_posts;
		$wpdb->postmeta 	= $table_woo_postmeta;
		#$wpdb->posts 		= "9woo_pomodoros_shop_order_posts";#FUNFA
		#$wpdb->postmeta 	= "9woo_pomodoros_shop_order_postmeta";#FUNFA
		#$wpdb->posts 				= "9fnetwork_woo_shop_order_posts";#FUNFA
		#$wpdb->postmeta 			= "9fnetwork_woo_shop_order_postmeta";#FUNFA
		#create_separated_woo_table_for_prefix_then_return();
		#die;	
	}
}

function mega_force_woo_type() {
	global $wpdb;
	global $show_debug_log;
	global $woo_priority;
	$_SESSION['woo_priority']=true;
	$woo_priority=true;
	if($show_debug_log)
		echo " mega_force_woo_type(); ";
	force_woo_type(); 
	#2018-03-18
	#$wpdb->posts 				= "9fnetwork_woo_shop_order_posts";
	#$wpdb->postmeta 			= "9fnetwork_woo_shop_order_postmeta";
}

function activate_woocommerce_hooks() {
	global $show_debug_log;
	if($show_debug_log)
		echo "<strong>activate_woocommerce_hooks();</strong><br>";
	#
	add_action( 'woocommerce_before_shop_loop_item', 'redirect_to_correct_store_in_shop_loop_title' );
	add_filter( 'woocommerce_loop_add_to_cart_link', 'redirect_to_correct_store_in_shop_loop_cart', 10, 2 ); 
	add_action( 'woocommerce_before_main_content', 'redirect_to_correct_store_in_single_view', 10, 2);
}

function force_database_aditional_tables_share($query) {
	global $show_debug_log;
	global $wpdb;
	global $wp_the_query;
	global $post_type_current;
	global $last_type;
	global $please_dont_change_wpdb_woo_separated_tables;
	global $reverter_filtro_de_categoria_pra_forcar_funcionamento;
	global $force_publish_post_not_shared;
	#
	if($show_debug_log) {
		echo "<strong>force_database_aditional_tables_share(query)</strong>; <br>";
		#var_dump($query);
		#echo "<br>";
	}

	
	if($please_dont_change_wpdb_woo_separated_tables) {
		#NECESSARIO
		if($show_debug_log)
		echo " estavas prestes a refazer o schema, mas atendo ao pedido de retornar please_dont_change_wpdb_woo_separated_tables: $please_dont_change_wpdb_woo_separated_tables ";
		#die;
		if($reverter_filtro_de_categoria_pra_forcar_funcionamento) {
			#revert_database_schema();
			if($show_debug_log)
			echo " cancelando pedido, outra funcao exigiu nao usar posts compartilhados, por ex pomodoros_posts ";
		}
		else
			return;
	}
	
	if($query==NULL || $query=="") {
		if($show_debug_log)
			echo "query empty, return";
		#return; #2020 dont work in woocommerce
	}
	if(!is_object($query)) {
		if($show_debug_log)
			echo " query is not object";
		if(isset($wp_the_query)) {
			if($show_debug_log)
				echo ", then user wp_the_query <br>";
			$query = $wp_the_query;

		}
		#var_dump($wp_the_query);die;
	}

	/*if($query=="") {
		if($show_debug_log)
			echo " query is empty ";
		
		if($wp_the_query!=NULL) {
			if($show_debug_log)
			echo " wp_the_query NOT null ";
			$query = $wp_the_query;
		} else if($query!=NULL) {
			if($show_debug_log)
				echo " query NOT null ";
			$query = $query;
		} else {
			return;
		}
	} else {
		if($show_debug_log)
			echo " query=queryReceived no null ";
		$query = $queryReceived;
		#var_dump($query->query['post_type']);die;
	}*/
	#if($wp_the_query!=NULL)

	
	/*if(!isset($query)) {
		if($show_debug_log)
		echo "NAOVEIO QUERY";
		if($wp_the_query!=NULL) {
			#echo "SETOU A GLOBAL";
			$query = $wp_the_query;
		}
	} else {
		if($show_debug_log)
		echo "VEVEIO QUERY";

		if(is_object($query)) {
			#var_dump($query);
			if($show_debug_log)
			echo "VEIO UM OBJETO (query)";
			#if($wp_the_query!=NULL) {
			#	echo "SETOU A GLOBAL";
			#	$query = $wp_the_query;
			#}
		} else {
			#var_dump($query);
			if($show_debug_log)
			echo "SETOU A GLOBAL PORQUE VEIO VAZIO";
			$query = $wp_the_query;
			#var_dump($query);
			#return;
			#if($query==NULL)
			#	return;
		}
	}*/
	
	#
	$post_types_ignored = array("shop_order", "shop_order_refund", "customize_changeset");
	#
	$post_types_not_shared = array("projectimer_focus", "projectimer_rest", "projectimer_lost", "subscription"); 
	#
	$post_types_dont_need_cat_filter = array("page", "nav_menu_item", "attachment", "achievement");
	#
	$post_types_dont_need_cat_filter = array_merge($post_types_dont_need_cat_filter, $post_types_ignored);

	#todo: check it pomodoros
	if($force_publish_post_not_shared) {
		#post NAO sera compartilhado, vai pro bd especifico (prov pomodoros_)
		#situacao tipica: inserindo novo post por wp_insert_post
		$post_types_not_shared[] = "any";
	}
	
	$post_type_current="notknow";
	if(isset($query->query["post_type"])) {
		$post_type_current = $query->query["post_type"];
		if(is_array($post_type_current) && isset($post_type_current[0])) {
			$post_type_current=$post_type_current[0];	
		}
		if($show_debug_log)
			echo "current post_type is defined in query <br>";
	} else {
		if($show_debug_log)
			echo " current post_type is not on query";

		if(isset($_GET['post_type'])) {
			$post_type_current = $_GET['post_type'];
		} else {
			if($show_debug_log)
				echo ", try to get it from url <br>";

			$d = ($_SERVER['REQUEST_URI']);
			$ds = explode("/", $d);
			//
			if(!in_array("order-received", $ds) || !$ds[1]=="ver-pedido") {
				if(defined('WOOCOMMERCE_CHECKOUT')) {
					if(WOOCOMMERCE_CHECKOUT) {
						return;
					}
				}
			}
		}
	}

	if($show_debug_log)
		echo "post_type_current: <strong> $post_type_current </strong> <br>";
	
	#if($show_debug_log) {

	#}
	#echo ", in array types not shared: ".(in_array($post_type_current, $post_types_not_shared) ? "NOT-SHARED" : "YES-SHARED").", last_type: ".$last_type . " | ";
	
	###if(!isset($post_type_current[0]))
	###	$post_type_current[0]='';
	
	#if(in_array($post_type_current, $post_types_ignored) || in_array($post_type_current[0], $post_types_ignored)) {
	if(in_array($post_type_current, $post_types_ignored)) {
		if($show_debug_log)
			echo "$post_type is ignored. <hr> return <hr>";
		#force_woo_type();
		return;
	}


	if(!in_array($post_type_current, $post_types_not_shared)) {
		#type shared
		if($show_debug_log)
			echo "post_type: $post_type_current is shared <br>";


		if(isset($_GET['action']) && $_GET['action']=="woocommerce_mark_order_status") {
			if($show_debug_log)
				echo " completando uma ordem, precisa retornar ";
			return;
		} else {
			set_shared_database_schema();#AQUI NAO PODE SETAR QUANDO VAI COMPLETAR O PRODUTO
		}
		
		if(!in_array($post_type_current, $post_types_dont_need_cat_filter)) {
			if($show_debug_log)
				echo "$post_type_current precisa de filtro de categoria. Apllying category filter <br>";
			filter_posts_by_category_domain_attributed_to_post($query);			
		}
	} else {
		#type not shared
		if($show_debug_log)
			echo "post_type: $post_type_current is not not shared ALRT";
		
		if(!in_array($post_type_current, $post_types_ignored)) {
			#return;
			revert_database_schema();
		}

	}

	$last_type=$post_type_current;
	if($show_debug_log)
		echo "<br>end<hr>";
}

function filter_posts_by_category_domain_attributed_to_post($queryReceived) {
	global $show_debug_log;
	global $wp_the_query;
	global $query;
	#
	if($show_debug_log)
		echo "<strong>filter_posts_by_category_domain_attributed_to_post();</strong><br>";
	
	/*if($query==NULL) {
		if($show_debug_log)
			echo "query is null, try other ";
		if($queryReceived==NULL) {
			if($show_debug_log)
				echo "queryReceived is null, return";
			return;
		} else {
			if($show_debug_log)
				echo "queryReceived is used ";
			$query = $queryReceived;
		}
	}*/
	
	$query = $queryReceived;

	$current_server_name = $_SERVER['SERVER_NAME'];
	$current_server_name_shared_category = get_category_by_slug($current_server_name);

	#var_dump($current_server_name_shared_category);
	#var_dump($current_server_name_shared_category->term_id);
	#die;

	if(isset($current_server_name_shared_category->term_id))
		$current_server_name_shared_category_id = $current_server_name_shared_category->term_id;
	else
		return;

	###$post_type_current="notknow";#(post or page problably)	
	###if(isset($query->query["post_type"])) 
	###	$post_type_current = $query->query["post_type"];
		
	$is_category = "";
	if(isset($query->query["is_category"]))
		$is_category = $query->query["is_category"];

	$category = "";
	if(isset($query->query["product_cat"]))
		$category = $query->query["product_cat"];

	$product_tag = "";
	if(isset($query->query["product_tag"]))
		$product_tag = $query->query["product_tag"];

	$is_search = "";
	if(isset($query->is_search))
		$is_search = $query->is_search;
	
	if($is_search || is_tax() || isset($query->query["is_post_type_archive"])) {
		return;
	}

	if($show_debug_log)
	echo ("is_shop: ".is_shop(). ", <br /> domain: ".$current_server_name. ", <br /> pdfcat X: ". ", <br /> gettype: ".gettype($query).", <br /> current_server_name_shared_category_id:".$current_server_name_shared_category_id.", <br /> category:".$category.", <br /> is_category:".$is_category.", <br /> typequery:".gettype($query)." <br />product_tag:".$product_tag.", <br />is_tag:".is_tag().",  <br /> is_search: ".$is_search.",  <br />is_tax:".is_tax());
			
			
	if($category=="") {
		if(!is_admin()) {
			##IS FRONT-END
			if($product_tag!="") {
				if($show_debug_log)
				echo " <br />product_tag: $product_tag <br>";
				$query->set( 'product_tag', $product_tag );
			} else {
				if($show_debug_log)
				echo " <br />not product_tag,<br>";
				
				global $reverter_filtro_de_categoria_pra_forcar_funcionamento;

				if(!$reverter_filtro_de_categoria_pra_forcar_funcionamento) {
					if($show_debug_log)
						echo "CATEGORY SETTED<br>";
					$query->set( 'cat', $current_server_name_shared_category_id );	
				} else {
					if($show_debug_log)
					echo "Devido ao cancelamento do filtro de categoria, todas as categorias serao exibidas";
				}
			}
		}
	}
}

function buddypress_tables_share() {
	#
	global $wpdb;
	#var_dump($bp);
	$wpdb->base_prefix = "1fnetwork_";
	#var_dump($wpdb->base_prefix);die;
	/*
	$bp->bp_activity 			="1fnetwork_bp_activity";
	$bp->bp_activity_meta 	="1fnetwork_bp_activity_meta";
	$bp->bp_friends 			="1fnetwork_bp_friends";
	$bp->bp_group_livechat 	="1fnetwork_bp_group_livechat";
	$bp->bp_group_livechat_online 	="1fnetwork_bp_group_livechat_online";
	$bp->bp_groups 			="1fnetwork_bp_groups";
	$bp->bp_groups_members 	="1fnetwork_bp_groups_members";
	$bp->bp_messages_messages ="1fnetwork_bp_messages_messages";
	$bp->bp_messages_meta 	="1fnetwork_bp_messages_meta";
	$bp->bp_messages_notices 	="1fnetwork_bp_messages_notices";
	$bp->bp_messages_recipients ="1fnetwork_bp_messages_recipients";
	$bp->bp_notifications 	="1fnetwork_bp_notifications";
	$bp->bp_user_blogs 		="1fnetwork_bp_user_blogs";
	$bp->bp_user_blogs_blogmeta 	="1fnetwork_bp_user_blogs_blogmeta";
	$bp->bp_xprofile_data 	="1fnetwork_bp_xprofile_data";
	$bp->bp_xprofile_fields 	="1fnetwork_bp_xprofile_fields";
	$bp->bp_xprofile_groups 	="1fnetwork_bp_xprofile_groups";
	$bp->bp_xprofile_meta 	="1fnetwork_bp_xprofile_meta";
	/*$wpdb->bp_activity 			="1fnetwork_bp_activity";
	$wpdb->bp_activity_meta 	="1fnetwork_bp_activity_meta";
	$wpdb->bp_friends 			="1fnetwork_bp_friends";
	$wpdb->bp_group_livechat 	="1fnetwork_bp_group_livechat";
	$wpdb->bp_group_livechat_online 	="1fnetwork_bp_group_livechat_online";
	$wpdb->bp_groups 			="1fnetwork_bp_groups";
	$wpdb->bp_groups_members 	="1fnetwork_bp_groups_members";
	$wpdb->bp_messages_messages ="1fnetwork_bp_messages_messages";
	$wpdb->bp_messages_meta 	="1fnetwork_bp_messages_meta";
	$wpdb->bp_messages_notices 	="1fnetwork_bp_messages_notices";
	$wpdb->bp_messages_recipients ="1fnetwork_bp_messages_recipients";
	$wpdb->bp_notifications 	="1fnetwork_bp_notifications";
	$wpdb->bp_user_blogs 		="1fnetwork_bp_user_blogs";
	$wpdb->bp_user_blogs_blogmeta 	="1fnetwork_bp_user_blogs_blogmeta";
	$wpdb->bp_xprofile_data 	="1fnetwork_bp_xprofile_data";
	$wpdb->bp_xprofile_fields 	="1fnetwork_bp_xprofile_fields";
	$wpdb->bp_xprofile_groups 	="1fnetwork_bp_xprofile_groups";
	$wpdb->bp_xprofile_meta 	="1fnetwork_bp_xprofile_meta";*/
	# OLD WP SETTINGS
	#$wpdb->categories="1fnetwork_categories"; OLD WP SETTINGS
	#$wpdb->term_post2cat="1fnetwork_post2cat"; OLD WP SETTINGS
}

function revert_database_schema() {
	#
	global $show_debug_log;
	if($show_debug_log)
		echo " revert_database_schema();, ";
	global $wpdb;
	#in wp-config and wp-settings.php
	/*if($prefix=="") {
		if(table_prefix)
			$prefix=table_prefix;
		else
			$prefix = "pomodoros";	
	}*/
	global $table_prefix;
	$prefix=$table_prefix;
	
	#
	$wpdb->posts=$prefix."posts";
	$wpdb->postmeta=$prefix."postmeta";
	$wpdb->terms=$prefix."terms";
	$wpdb->term_taxonomy=$prefix."term_taxonomy";
	$wpdb->term_relationships=$prefix."term_relationships";
	$wpdb->termmeta=$prefix."termmeta";
	$wpdb->taxonomy=$prefix."taxonomy";
	#
	$wpdb->comments=$prefix."comments";
	$wpdb->commentmeta=$prefix."commentmeta";
	#
	$wpdb->links=$prefix."links";

	# OLD WP SETTINGS
	#$wpdb->categories="1fnetwork_categories"; OLD WP SETTINGS
	#$wpdb->term_post2cat="1fnetwork_post2cat"; OLD WP SETTINGS
}

function create_separated_woo_table_for_prefix_then_return() {
	global $show_debug_log;
	if($show_debug_log)
	echo " create_separated_woo_table_for_prefix_then_return(); ";
	#return;
	global $wpdb;
	#
	$table_woo_posts	= "6woo_".$wpdb->prefix."shop_order_posts";
	$table_woo_postmeta = "6woo_".$wpdb->prefix."shop_order_postmeta";
	#
	#$charset_collate = $wpdb->get_charset_collate();
	$charset_collate = ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ';
	#echo "CHAR: ".$charset_collate;
	#die;
	/*$sql_settings = '
		SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
		SET AUTOCOMMIT = 0;
		START TRANSACTION;
		SET time_zone = "+00:00";';*/
	#
	$sql_posts = 'CREATE TABLE IF NOT EXISTS `'.$table_woo_posts.'` (
	  `ID` bigint(20) UNSIGNED NOT NULL,
	  `post_author` bigint(20) UNSIGNED NOT NULL DEFAULT "0",
	  `post_date` datetime NOT NULL DEFAULT "0000-00-00 00:00:00",
	  `post_date_gmt` datetime NOT NULL DEFAULT "0000-00-00 00:00:00",
	  `post_content` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	  `post_title` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	  `post_excerpt` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	  `post_status` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT "publish",
	  `comment_status` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT "open",
	  `ping_status` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT "open",
	  `post_password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT "",
	  `post_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT "",
	  `to_ping` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	  `pinged` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	  `post_modified` datetime NOT NULL DEFAULT "0000-00-00 00:00:00",
	  `post_modified_gmt` datetime NOT NULL DEFAULT "0000-00-00 00:00:00",
	  `post_content_filtered` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	  `post_parent` bigint(20) UNSIGNED NOT NULL DEFAULT "0",
	  `guid` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT "",
	  `menu_order` int(11) NOT NULL DEFAULT "0",
	  `post_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT "post",
	  `post_mime_type` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT "",
	  `comment_count` bigint(20) NOT NULL DEFAULT "0"
		) '.$charset_collate.';';
  	#
	$a1 = 'ALTER TABLE `'.$table_woo_posts.'`
		  ADD PRIMARY KEY (`ID`),
		  ADD KEY `post_name` (`post_name`);';

	$a2 = 'ALTER TABLE `'.$table_woo_posts.'`
  			MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;';

	$sql_post_meta = 'CREATE TABLE IF NOT EXISTS `'.$table_woo_postmeta.'` (
		  `meta_id` bigint(20) UNSIGNED NOT NULL,
		  `post_id` bigint(20) UNSIGNED NOT NULL DEFAULT "0",
		  `meta_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
		  `meta_value` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci
		) '.$charset_collate;

	$a11 = 'ALTER TABLE `'.$table_woo_postmeta.'`
		  ADD PRIMARY KEY (`meta_id`),
		  ADD KEY `post_id` (`post_id`),
		  ADD KEY `meta_key` (`meta_key`);';
	$a22 = 'ALTER TABLE `'.$table_woo_postmeta.'`
  		  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;';
  	#$sql_settings..$sql_post_meta
  	#$final_sql = $sql_posts;
  	#if($show_debug_log)
  	#var_dump($final_sql);
  	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  	$sql1ok = dbDelta( $sql_posts );
  	$sql2ok = dbDelta( $sql_post_meta );

  	$wpdb->query($a1);
  	
  	$wpdb->query($a11);

  	$wpdb->query($a2);

  	$wpdb->query($a22);
  	#var_dump($sqlok);
  	if($sql1ok && $sql2ok)
  		force_woo_type();
  	else 
  		echo " Problems create tables, check plugin F5 Sites | Shared Posts Tables and Upload Folder";
}

/* WOOCOMMERCE FNETWORK */
function redirect_to_correct_store_in_shop_loop_title() {
	$purl = get_product_correct_url_by_id();
	if(!$purl) {
		$purl = get_permalink();
	} else {
		echo "<marquee> ! FNETWORK - LOJA PARCEIRA ! </marquee>";
	}
	echo '<a href="' . $purl . '" class="woocommerce-LoopProduct-link">';
}

function redirect_to_correct_store_in_shop_loop_cart( $array, $int ) { 
	$purl = get_product_correct_url_by_id();
	if(!$purl) {
		return $array;
	} else {
		echo "Produto disponível somente em: <br />";
		$parse = parse_url($purl);
		echo "<a href=$purl class='button product_type_booking add_to_cart_button'>".$parse['host']."</a>";
	}
}

#
function get_product_correct_url_by_id($postid=0) {
	$current_server_name = $_SERVER['SERVER_NAME'];
	$categories=array();
	if($postid==0) {
		global $post;
		$product_id = $post->ID;
		#echo "PROOOOOOOID:".$product_id;
	}
	$terms = wp_get_post_terms( $product_id, 'product_cat' );
	#$terms = wp_get_post_terms( $product_id, 'product_cat' );
	#echo "terms:";
	#var_dump($terms);
	if(count($terms)<=0) {
		#echo "VOLTAN";
		return;
	}
	else
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

/* UPLOAD FOLDER */
function shared_upload_dir( $dirs ) {
    $dirs['baseurl'] = site_url( '/wp-content/uploads/shared-wp-posts-uploads-dir' );
    $dirs['basedir'] = ABSPATH . 'wp-content/uploads/shared-wp-posts-uploads-dir';
    $dirs['path'] = $dirs['basedir'] . $dirs['subdir'];
    $dirs['url'] = $dirs['baseurl'] . $dirs['subdir'];

    return $dirs;
}

/*NAVIGATION LINKS*/

function print_blog_nav_links($post, $tagfilter="") {
	?>
	<div class="navigation">
			<?php
				#$post_id = $post->ID; // Get current post ID
				#$cat = get_the_category(); 
				#$current_cat_id = $cat[0]->cat_ID; // Get current Category ID 
				$current_cat_id = get_cat_ID($_SERVER["HTTP_HOST"]);

				$args = array(
					'category'=>$current_cat_id,
					'orderby'=>'post_date',
					'order'=> 'DESC',
					'tag'=>$tagfilter
					);
				$posts = get_posts($args);
				// Get IDs of posts retrieved by get_posts function
				$ids = array();
				foreach ($posts as $thepost) {
				    $ids[] = $thepost->ID;
				}
				// Get and Echo the Previous and Next post link within same Category
				$index = array_search($post->ID, $ids);
				if(($index-1)>=0)
				$prev_post = $ids[$index-1];
				if(($index+1)<count($ids))
				$next_post = $ids[$index+1];
				?>

			<div class="alignright">
			<?php if (!empty($prev_post)){ ?> <a class="previous-post" rel="prev" href="<?php echo get_permalink($prev_post) ?>"> <?php echo get_the_title($prev_post); ?>&rarr;</a> (<?php echo human_time_diff( get_the_time('U', $prev_post), current_time('timestamp') ) . ' atrás'; ?>)  <?php } ?>
			</div>

			<div class="alignleft">
			<?php if (!empty($next_post)){ ?> <a class="next-post" rel="next" href="<?php echo get_permalink($next_post) ?>"> &larr; <?php echo get_the_title($next_post); ?> </a> (<?php echo human_time_diff( get_the_time('U', $next_post), current_time('timestamp') ) . ' atrás'; ?>) <?php } ?>
			</div>
			
					<?php	#if(function_exists('force_database_aditional_tables_share')) {
			       			#force_database_aditional_tables_share();
			       		#}
			       		#"328,344,339,409"
			       		#previous_post_link('; %link', '%title', true);
						#next_post_link('%link &raquo;', '%title', true);
			       	?>
					<!--div class="alignright"">
						<?php next_post_link('%link >> ', '%title', TRUE); ?>
						<?php #next_posts_link( __( '&larr; Previous Entries', 'buddypress' ) ) ?>
					</div>
					<div class="alignleft">
						<?php previous_post_link('%link << ', '%title', TRUE);
						#previous_posts_link( __( 'Next Entries &rarr;', 'buddypress' ) ) ?>
					</div-->

				</div>
	<?php
}

/* WOO SIDEBARS */
