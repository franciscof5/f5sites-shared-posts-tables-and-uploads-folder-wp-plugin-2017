<?php
/**
 * Plugin Name: F5 Sites | Shared Posts Tables & Uploads Folder
 * Plugin URI: https://www.f5sites.com/software/wordpress/f5sites-shared-posts-tables-and-uploads-folder/
 * Description: Hacks WordPress databases, sharing posts and taxonomies tables for multiple wp install under the same database, by default wp only can share tables users and usermeta. Made for use in fnetwor
 * Tags: woocommerce, pdfcatalog, wpmu, buddypress, bp xprofile, projectimer ready
 * Version: 1.0
 * Author: Francisco Matelli Matulovic
 * Author URI: https://www.franciscomat.com
 * License: GPLv3
 */

global $debug_force;
#if(gethostname()=="note-samsung")$debug_force = true;#NEVER ENABLE DEBUG IN PRODUCTION SERVER


function is_blog()
{
    return ( is_home() || is_single() || is_category() || is_archive() || is_front_page() || strpos($_SERVER['REQUEST_URI'], "blog") );
}
if(!is_network_admin()) {
	#echo " vamos regacar as mangas...";
	#if ( !is_woocommerce() ) {
		#add_action( 'wp_head', 'force_database_aditional_tables_share', 10, 2 );
		#if (! is_admin() )
		//add_action( 'after_setup_theme', 'force_database_aditional_tables_share', 10, 2 );	
	#}	
	/*add_action( 'after_setup_theme', 'force_database_aditional_tables_share', 10, 2 );
	add_action( 'woocommerce_loaded', 'force_database_aditional_tables_share', 10, 2 );
	add_action( 'plugins_loaded', 'force_database_aditional_tables_share', 10, 2 );
	add_action( 'setup_theme', 'force_database_aditional_tables_share', 10, 2 );
	add_action( 'pre_get_sites', 'force_database_aditional_tables_share', 10, 2 );
	add_action( 'woocommerce_integrations_init', 'force_database_aditional_tables_share', 10, 2 );
	add_action( 'register_sidebar', 'force_database_aditional_tables_share', 10, 2 );*/
	//add_action( 'before_woocommerce_init', 'force_database_aditional_tables_share', 10, 2 );
	//add_action( 'switch_blog', 'force_database_aditional_tables_share', 10, 2 );
	//add_action( 'before_woocommerce_init', 'setWooFilters', 10, 2 );
	//woocommerce_loaded
	//if(!is_buddypress())
	//if(is_admin())
	//$_SERVER['REQUEST_URI'];
	//$inPageWoo = strpos($_SERVER['REQUEST_URI'], "woocommerce");
	//$inPageProduto = strpos($_SERVER['REQUEST_URI'], "produto");
	//echo $inPageCrateTeams;die;
	//if(is_admin() || $inPageWoo || $inPageProduto)
	//var_dump(is_admin() || is_tax() || is_archive() || function_exists("is_woocommerce"));die;
	
	
	//THIS IS ONLY FOR A BUDDYPRESS SPECIFIC PAGE INTEGRATION
	
	
	#if ( is_multisite() ) { 
	#	force_database_aditional_tables_share(); 
	#}
	#if(is_home())
	#if(is_page('blog'))
	#echo "A>".is_page('blog').is_home().is_archive().is_front_page();die;
	#var_dump(strpos($_SERVER['REQUEST_URI'], "blog"));
	#echo is_home();
	#var_dump(is_page());die;
	#if(is_blog())
	#if(!$debug_force)#TO ACTIVATE QUERY-MONITOR (when available)
	show_admin_bar( false );
	#add_filter('is_admin_bar_showing', '__return_false'); 
	#
	if(!is_page() || is_blog())
	add_action( 'pre_get_posts', 'force_database_aditional_tables_share', 10, 2 );//FOR BLOG POSTS #NEEDED IN EVERY BLOG ROOT
	#add_action( 'wp_before_admin_bar_render', 'die' );
	add_action( 'plugins_loaded', 'force_database_aditional_tables_share', 10, 2);
	#if(!is_admin()) {

		/*$inPageCrateTeams = strpos($_SERVER['REQUEST_URI'], "create");
		if(!$inPageCrateTeams) {	
			
			if($debug_force)
				echo " not in page create teams; ";
			#set_shared_database_schema();
			#add_action( 'switch_blog', 'set_shared_database_schema', 10, 2 );#ESSA MERDA ERA USADA NO ADMIN_BAR, POR ISSO PEGAVA CADA BLOG
			#add_action( 'woocommerce_loaded', 'force_database_aditional_tables_share', 10, 2 );

			#2017-10-22 foi trocado force_database_aditional_tables_share por set_shared_database_schema #PORQUE ASSIM NAO CRIA VARIAS TABELAS COM TODOS OS PREFIXOS NO BANCO DE DADOS #além de ficar mais rápido, menos consulta e consumo de memória

			//on franciscomat tests it shows need for 2 filters at same time
			add_action( 'plugins_loaded', 'force_database_aditional_tables_share', 10, 2 );
			#precisa para POMODOROS
		} else {
			if($debug_force)
				echo " the page has create on url ";
			add_action( 'plugins_loaded', 'force_database_aditional_tables_share', 10, 2 );
		}*/
	#} else {
		//in admin always share
		if(is_multisite() && is_admin())
			add_action( 'switch_blog', 'force_database_aditional_tables_share', 10, 2 );#ADMIN-BAR cant be disabled in ADMIN, because of it that action must be enabled
		else
			add_action( 'before_woocommerce_init', 'force_database_aditional_tables_share', 10, 2 );
		#BUG BUSCA POST EM TODOS OS (sub)BLOGS #TODO: achar o filtro adequado #SEPARANDO SUBSCRIPTIONS em focalizador deixa comentado
		
		#add_action( 'pre_get_posts', 'force_database_aditional_tables_share', 10, 2 );//FOR BLOG POSTS #NEEDED IN EVERY BLOG ROOT

		#add_action( 'plugins_loaded', 'force_database_aditional_tables_share', 10, 2 );
		#global $VIEW_ORDER_FORCE_ANULATE_DATABASE_SHARE;
		#$VIEW_ORDER_FORCE_ANULATE_DATABASE_SHARE=true;		
		##### PUBLICANDO PRODUTOS dando erro
		#add_action( 'woocommerce_loaded', 'force_database_aditional_tables_share', 10, 2 );
		#add_action( 'switch_blog', 'force_database_aditional_tables_share', 10, 2 );#ESSA MERDA ERA USADA NO ADMIN_BAR, POR ISSO PEGAVA CADA BLOG
	#}

	//shared upload dir, comment to un-share
	add_filter( 'upload_dir', 'shared_upload_dir' );
	//
	add_filter( 'nav_menu_link_attributes', 'filter_function_name', 10, 3 );
	#Work in progress for buddypress integration, some problems might occur with sensitivy user data, like user_blogs table, making impossible to cross-share between multiple installs, but it is a good start point
	#add_action( 'bp_loaded', 'buddypress_tables_share', 10, 2 );
	#add_action( 'wp_insert_post', 'force_database_aditional_tables_share', 20, 2 );
	#add_action( 'wp_insert_post', 'force_database_aditional_tables_share' );
	#ULTIMO ESTAGIO, precisa funcionar os widgets e os nav links abaixo dos posts e tudo fica joia 2017-10-06
	#add_action( 'widgets_init', 'asda', 10, 2 );	
	#add_action( 'get_post_type', 'asda', 10, 3 );
	

	#NAO PRECISA
	{
		#add_action("get_items_F5SITES_HOOK", "force_woo_type", 10, 2);
		#add_action("mark_order_status_F5SITES_hook", "force_woo_type", 10, 2);#(superador por $_GET abaixo)
		#add_action("order_received_F5SITES_inserted_hook", "force_woo_type", 10, 2);
		#add_action('woocommerce_checkout_init', 'mega_force_woo_type', 10, 2);
		#add_action('woocommerce_after_checkout_validation', 'mega_force_woo_type', 10, 2);
		#add_action('woocommerce_checkout_create_order_line_item', 'set_shared_database_schema');
		#add_action('woocommerce_check_cart_items', 'set_shared_database_schema');
		#add_action('woocommerce_view_order', 'mega_force_woo_type', 10, 2);
		#add_action('before_woocommerce_init', 'force_database_aditional_tables_share', 10, 2);
		#add_action('woocommerce_after_checkout_validation', 'force_database_aditional_tables_share', 10, 2);
		#add_action('woocommerce_view_order', 'force_database_aditional_tables_share', 10, 2);
		#woocommerce_create_refund
		#HOOKS PARA TESTAR
		#woocommerce_checkout_process

		#SHOP_ORDERS: HOOKS NECESSARIOS PARA SEPARAR (hooks F5SITES precisam ser inseridos manualmente (ainda))
		add_action('woocommerce_checkout_create_order', 'mega_force_woo_type');
		#refund_payment
		add_action('woocommerce_before_order_object_save', 'force_woo_type');
		#add_action('woocommerce_before_refund_payment_object_save', 'force_woo_type');
		#add_action('woocommerce_before_shop_order_refund_object_save', 'force_woo_type');
		#do_action( '' . $this->object_type . '_object_save', $this, $this->data_store );
		#
		add_action("get_orders_hook", "force_woo_type", 10, 2);
		add_action("set_product_id_hook", "set_shared_database_schema", 10, 2); #SIM, PRECISA VOLTAR PARA PEGAR O PRODUTO NOS POSTS SHARED
		add_action("get_order_report_data_hook", "force_woo_type", 10, 2);		# parece que precisa ser mega_ #pomodoros nao era mega_
	}
}



function filter_function_name( $atts, $item, $args ) {
    // Manipulate attributes
    //var_dump($args);
    return $atts;
}

function set_shared_database_schema() {
	global $wpdb;
	global $debug_force;
	global $type;
	global $woo_priority;
	#or $_SESSION['woo_priority']

	if ($debug_force)
	echo " set_shared_database_schema(); ";

	if($woo_priority) {
		if($debug_force)
			echo " WOO PEDIU PARA SER PRIORITY (return) ";
		return;
	} else {
		if($debug_force)
		echo " woo_priority=false ";
	}
	
	
	#global $FORCE_WOO;
	#var_dump($wpdb);
	#$olddbname = DB_NAME;
	#$wpdb = new wpdb( DB_USER, DB_PASSWORD, "pomodoros", DB_HOST );
	#define('DB_NAME', "pomodoros2");
	#require_wp_db();
	#wp_set_wpdb_vars();
	#die;
	#var_dump(dirname(__FILE__)."/config.php");
	if(is_file(dirname(__FILE__)."/config.php")) {
		include("config.php");
		#echo $config["posts"];die;
	} else {
		echo "F5 Sites Shared posts warning: please enter plugin folder and configure it: copy config.example.php and rename it to config.php with you changes";die;	
	}
	#global $f5sites_force_shared_posts_query;
	#$f5sites_force_shared_posts_query = true;
	#var_dump($config);die;
	#
	#$wpdb->prefix 				= "1fnetwok_";
	#if($FORCE_WOO) {
		#echo "FORCOU WOO";
		#force_new_names_COPY();
		#$wpdb->posts 				= "9fnetwork_woo_shop_order_posts";
		#$wpdb->postmeta 			= "9fnetwork_woo_shop_order_postmeta";
	#} else {
		#echo " NEGOU WOO";
	if(function_exists("is_woocommerce")) {
		if($debug_force)
			echo " is_woocommerce=true ";
		setWooFilters();
	}

	#$types_shop_order = array("shop_order", "shop_order_refund", "customize_changeset", "subscription");
	#echo "AJDLKAJSDLKASJ D".$type;
	if(is_array($type))
		$type = $type[0];
	if($debug_force)
	echo " ULTIMO CHECK TYPE: ".$type;
	
	
		if($debug_force)
		echo " TABELA ".$config["posts"];
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
	#}
		
	#}
	
	#
	
	#var_dump($wpdb->posts);die;

	#f5sites woocommerce shop_oder tables plugins integration
	#global $type;
	
}

function force_woo_type() {
	global $wpdb;
	global $debug_force;
	#$debug_force=true;
	#global $woo_priority;
	#$woo_priority=true;

	$table_woo_posts	= "6woo_".$wpdb->prefix."shop_order_posts";
	$table_woo_postmeta = "6woo_".$wpdb->prefix."shop_order_postmeta";

	if($debug_force)
		echo " force_woo_type(); TABELA $table_woo_posts e $table_woo_postmeta";

	$exists_table_woo_posts = $wpdb->get_var("SHOW TABLES LIKE '$table_woo_posts'");
	$exists_table_woo_postmeta = $wpdb->get_var("SHOW TABLES LIKE '$table_woo_postmeta'");

	#$wpdb->posts 		= $table_woo_posts;
	#$wpdb->postmeta 	= $table_woo_postmeta;
	#$wpdb->posts 		= "9woo_pomodoros_shop_order_posts";#FUNFA
	#$wpdb->postmeta 	= "9woo_pomodoros_shop_order_postmeta";#FUNFA
	
	if(($exists_table_woo_posts!=$table_woo_posts) || ($exists_table_woo_postmeta!=$table_woo_postmeta)) {
		if($debug_force)
		echo " separated tables woo not found, creating then ";
		create_separated_woo_table_for_prefix_then_return();
		#die;
	} else {
		if($debug_force)
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
	global $debug_force;
	global $woo_priority;
	$_SESSION['woo_priority']=true;
	$woo_priority=true;
	if($debug_force)
		echo " mega_force_woo_type(); ";
	$wpdb->posts 				= "9fnetwork_woo_shop_order_posts";
	$wpdb->postmeta 			= "9fnetwork_woo_shop_order_postmeta";
}

function setWooFilters() {
	global $debug_force;
	if($debug_force)
		echo " setWooFilters(); ";
	#
	#if(function_exists("is_woocommerce")) {
		add_action( 'woocommerce_before_shop_loop_item', 'redirect_to_correct_store_in_shop_loop_title' );
		add_filter( 'woocommerce_loop_add_to_cart_link', 'redirect_to_correct_store_in_shop_loop_cart', 10, 2 ); 
		#
		add_action( 'woocommerce_before_main_content', 'redirect_to_correct_store_in_single_view', 10, 2);
	#}
}

function force_database_aditional_tables_share($query) {
	#echo " MAIORQUE1:".(get_current_blog_id()>1)." SENDO:".get_current_blog_id();
	#if(get_current_blog_id()>1)
	#	return;
	#die;

	#revert previous altered function
	//global $interrupt_database_share;
	//if($interrupt_database_share)return;
	global $debug_force;
	global $wpdb;
	global $wp_the_query;
	global $type;
	global $last_type;
	global $please_dont_change_wpdb_woo_separated_tables;
	global $reverter_filtro_de_categoria_pra_forcar_funcionamento;
	#
	if($debug_force)
		echo " force_database_aditional_tables_share(); ";
	
	/*if($debug_force)
	echo " is_order_received_page(): ".is_order_received_page();

	if(is_order_received_page()) {
		#mega_force_woo_type();#NAO ESTA SERVINDO PARA NADA
	}*/

	if($please_dont_change_wpdb_woo_separated_tables) {
		#NECESSARIO
		if($debug_force)
		echo " estavas prestes a refazer o schema, mas atendo ao pedido de retornar please_dont_change_wpdb_woo_separated_tables: $please_dont_change_wpdb_woo_separated_tables ";
		#die;
		if($reverter_filtro_de_categoria_pra_forcar_funcionamento)
			echo " cancelando pedido, sidebar exigiu ";
		else
			return;
	}
	

	#echo !is_int($query);
	#echo !isset($query);die;
	

	if(!isset($query)) {
		#echo "NAO VEIO QUERY";
		if($wp_the_query!=NULL) {
			#echo "SETOU A GLOBAL";
			$query = $wp_the_query;
		}
	} else {
		#echo "VEIO QUERY";
		if(is_object($query)) {
			#var_dump($query);
			#echo "VEIO UM OBJETO (query)";
			#if($wp_the_query!=NULL) {
			#	echo "SETOU A GLOBAL";
			#	$query = $wp_the_query;
			#}
		} else {
			#var_dump($query);
			#echo "SETOU A GLOBAL PORQUE VEIO VAZIO";
			$query = $wp_the_query;
			#var_dump($query);
			#return;
			#if($query==NULL)
			#	return;
		}
	}
	
	#var_dump($query);
	#else
	#	return;
	#echo $query;
	#if($GLOBALS['table_prefix'])
	#$wpdb->base_prefix=$GLOBALS['table_prefix'];

	#settype($query, "WP_Query");
	
	


	$types_not_shared = array("projectimer_focus", "projectimer_rest", "projectimer_lost");

	$types_not_shared[] = "subscription";
	#$types_not_shared = array_merge($types_not_shared, $types_shop_order);
	#var_dump($types_not_shared);die;
	#echo $force_publish_post_not_shared;
	global $force_publish_post_not_shared;
	if($force_publish_post_not_shared) {
		#post NAO sera compartilhado, vai pro bd especifico (prov pomodoros_)
		#situacao tipica: inserindo novo post por wp_insert_post
		$types_not_shared[] = "any";
	}
	
	#ULTRA CARE ABOUT LINE ABOVE (sem comentarios, eh muito gambiarra)
	/*if(!is_admin()) {
		#se for pelo front-end 
		$types_not_shared[] = "any"; #com essa linha any vai para _posts especifico, sem essa linha vai para 1fnetwork_posts
	} else {
		$types_not_shared[] = "any";
		#se for pelo admin então para publicar
		#é compartilhado
		#e any é shared, então fora desse array
		#lógico,,... mais uma gambiarra, em cima da gambiarra	
	}*/
	
	#die;
	if(isset($query->query["post_type"])) {
		$type = $query->query["post_type"];
	} else {
		#return;
		if(isset($_GET['post_type'])) {
			$type = $_GET['post_type'];
		} else {
			
			if(!$type) {
				$type="notknow";#(post or page problably, but maybe menu)
			} else {
				#echo "GLOBAL TYPE ".$type;
			}
		}
		#$type="notknow";#(post or page problably, but maybe menu)
	}
	$types_shop_order = array("shop_order", "shop_order_refund", "customize_changeset", "subscription_NONO");

	if($debug_force)
	echo " type: ";
	if($debug_force)
	var_dump($type);
	if($debug_force)
	echo ", in array types not shared: ".(in_array($type, $types_not_shared) ? "NOT-SHARED" : "YES-SHARED").", last_type: ".$last_type . " | ";

	if(in_array($type, $types_shop_order) || in_array($type[0], $types_shop_order)) {
		#$type[0] => em alguns casos vem 2 tipos, mas o primeiro eh shop_order
		if($debug_force)
		echo " EH DO TIPO SHOP_ORDER ";
		force_woo_type();
		return;
	}
	#if(function_exists("force_database_shop_order_separated_tables")) {
		/*$types_to_ignore_and_do_nothing = array("shop_order", "shop_order_refund", "customize_changeset", "subscription");

		if(in_array($type, $types_to_ignore_and_do_nothing)) {
			#echo " let other plugin take care of it ...";
			#force_new_names();
			#return;
			if($debug_force)
			echo " vamos forcar o 9woo para o tipo: $type ";
			global $FORCE_WOO;
			$FORCE_WOO=true;

			#force_new_names();
			#force_database_shop_order_separated_tables_via_other_plugin();
		} else {
			#echo " 1fnetwork_posts sera usado. ";
		}*/
	#} else {
	#	if($debug_force)
			#echo " plugin SHOP_ORDER nao ativado. ";
	#}
	
	
	#echo "<hr />";
	#if($last_type=="notknow") {
		if(!in_array($type, $types_not_shared)) {
			#YES-SHARED
			if($debug_force)
			echo "$type is shared";
			if(isset($_GET['action'])) {
				if($_GET['action']=="woocommerce_mark_order_status") {
					if($debug_force)
					echo " completando uma ordem, precisa retornar ";
					return;
				} else {
					if($debug_force)
					echo " segue ";
					set_shared_database_schema();#AQUI NAO PODE SETAR QUANDO VAI COMPLETAR O PRODUTO
				}
			} else {
				if($debug_force)
				echo " segue ";
				set_shared_database_schema();#AQUI NAO PODE SETAR QUANDO VAI COMPLETAR O PRODUTO
			}
			

			$types_dont_need_cat_filter = array("page", "nav_menu_item", "notknow_REMOVED", "attachment");
			$types_dont_need_cat_filter = array_merge($types_dont_need_cat_filter, $types_shop_order);

			#if($type!="page" and $type!="nav_menu_item" and (!in_array($type, $types_shop_order))) {
			if(!in_array($type, $types_dont_need_cat_filter)) {
				if($debug_force)
				echo " APLICANDO FILTRO DE CATEGORIA ".(!in_array($type, $types_shop_order));
				if($debug_force)
				echo "$type precisa de filtro de categoria";

				#if(!function_exists("is_woocommerce")) {
					#if($debug_force)
					#echo " naoehwoo, aplica filtro ";
					filter_posts_by_cat($query);
				#}
				
			}
		} else {
			#NOT-SHARED
			if($debug_force)
			echo "$type is not not shared";
			
			revert_database_schema();

		}
	#}
	#var_dump(debug_backtrace());
	
	#var_dump($wpdb);
	#if($last_type!=NULL)
	#die;
	$last_type=$type;
	if($debug_force)
		echo "................................................................................................................................................................................................";
	#var_dump($wp_the_query);

	#get current domain
	#get post type
	#var_dump(gettype($query));
	#if($query) {
	#if(gettype($query)=="object") {
		#echo "TTTTTTTTTTTT".$query->post_type;
		#var_dump($query);
		

		#$types_shared = array("notknow", "post", "page", "product");
		
		#if(in_array($type, $types_shared)) {
			#if(gettype($query)=="WP_Query") {
				#set_shared_database_schema();
				#if(gettype($query)=="object") {
					#filter_posts_by_cat($query);
				#}
			#}
		#}
		#
		
		#$is_defined_post_type = isset($type);
	#}	
			
		#$query->query_vars["category__in"] = $current_server_name_shared_category_id;
		#$query->query_vars["category__in"] = 357;
		
		#if(!$is_defined_post_type)
			


		/*if($type=="projectimer_focus") {
			$wpdb = new wpdb( DB_USER, DB_PASSWORD, "pomodoros", DB_HOST );
			revert_database_schema("pomodoros_");
		}*/

		#the magic happens here
		#if(in_array($type, $types_shared)) {
			#
			#set_shared_database_schema();
			#
			
			#specific queryes
			#if($type=="product" || $type=="notknow") { # && !is_pdf_catalog
				#WHY THAT? CANT REMEMBER if(gettype($query)!="string" && gettype($query)!="integer") {
					#filter_posts_by_cat();
				#}
			#}
			
			#revert_database_schema();
		#}
		#if($type=="projectimer_focus") {
			#global $wpdb;
			#var_dump($wpdb);
			#$wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
			#revert_database_schema();
		#}
	#}
	#if ( $query->is_home ) {#product, shop_order, shop_coupon
}

function filter_posts_by_cat($queryReceived) {
	global $wp_the_query;
	global $query;
	
	//var_dump($wp_the_query);
	//if($wp_the_query!=NULL)
	//	$query = $wp_the_query;
	//else
	//	return;
	//$query = $wp_the_query;

	if($queryReceived==NULL) {
		if($wp_the_query!=NULL) {
			$query = $wp_the_query;
		} else if($query!=NULL) {
			$query = $query;
		} else {
			return;
		}
	} else {
		$query = $queryReceived;
	}
		//var_dump($query);
		//$query = new WP_Query($query);
	
	//die;
	$current_server_name = $_SERVER['SERVER_NAME'];
	#$current_server_name = "br.f5sites.com";
	$current_server_name_shared_category = get_category_by_slug($current_server_name);
	//echo "1ASDASDAS";
	

	if(isset($current_server_name_shared_category->term_id))
		$current_server_name_shared_category_id = $current_server_name_shared_category->term_id;
	else
		return;
	#echo "12222DAS";

	$type="notknow";#(post or page problably)	
	if(isset($query->query["post_type"])) 
		$type = $query->query["post_type"];
	//else
		
	
	$is_category = "";
	if(isset($query->query["is_category"]))
		$is_category = $query->query["is_category"];
	//else
			
	
	$category = "";
	if(isset($query->query["product_cat"]))
		$category = $query->query["product_cat"];

	$product_tag = "";
	if(isset($query->query["product_tag"]))
		$product_tag = $query->query["product_tag"];

	//else
	//	$category = "";
	#$is_pdf_catalog = isset($_GET["pdfcat"]);
	#$is_pdf_catalog_all = isset($_GET["all"]);
	
	#if(function_exists("is_woocommerce"))
	#$is_woocommerce = is_woocommerce();
	#else
	#$is_woocommerce = false;
	#
	#if($is_woocommerce || $is_pdf_catalog) { # || $is_pdf_catalog
		#if(!$is_pdf_catalog_all and !is_admin()) #not
		#$query->set( 'product_cat', $current_server_name );
		#echo $current_server_name;die;
	#} else {
		
		#if(isset($current_server_name_shared_category_id)) {
		#if(!isset($category) && !isset($is_category)) {
		
		//if(!isset($category)) {
		//if(gettype($query)=="WP_Query") {
		
		//if(!$is_woocommerce) {
			
			#echo "333333S:".$current_server_name_shared_category_id;
			#$current_server_name_shared_category_id = 3;
			//var_dump($query);die;

			//if($type=="product")
			
			//if($product_tag!="")
			//	$query->set( 'product_tag', $product_tag );	
			//var_dump("<br /> type: ".$type. ", <br /> is_shop: ".is_shop(). ", <br /> domain: ".$current_server_name. ", <br /> is_woocommerce(): ".is_woocommerce(). ", <br /> pdfcat: ". ", <br /> gettype: ".gettype($query).", <br /> current_server_name_shared_category_id:".$current_server_name_shared_category_id.", <br /> category:".$category.", <br /> is_category:".$is_category.", <br /> typequery:".gettype($query)." <br />product_tag:".$product_tag);
			
			#if($category!="") {
				
				//$query->set( 'cat', $current_server_name_shared_category_id );
				//$query->set( 'product_cat', $current_server_name_shared_category_id );
			
			if($category=="") {
				if(!is_admin()) {
					##IS FRONT-END
					#var_dump("product_tag: $product_tag");
					#var_dump($query);

					if($product_tag!="") {
						#echo ". Setou a tag: $product_tag";
						$query->set( 'product_tag', $product_tag );
					} else {
						#echo ". Setou a categoria id: $current_server_name_shared_category_id";
						
						global $reverter_filtro_de_categoria_pra_forcar_funcionamento;
						#echo " [reverter_filtro_de_categoria_pra_forcar_funcionamento=$reverter_filtro_de_categoria_pra_forcar_funcionamento]";

						if(!$reverter_filtro_de_categoria_pra_forcar_funcionamento) {
							#echo " CATEGORIA FILTRADA";
							$query->set( 'cat', $current_server_name_shared_category_id );	
						} else {
							#echo "Devido ao cancelamento do filtro de categoria, todas as categorias serao exibidas";
						}
						
						#$query->set( 'category__in', $current_server_name_shared_category_id );
						#var_dump($query);
					}
					//if($type!="product")
					//if($type!="projectimer_focus")
					
				}
			} else {
			}
				
			#$query->set( 'category', $current_server_name_shared_category_id );
		//}
			
			//	$query->set( 'product_cat', $current_server_name_shared_category_id );
			/*if(is_admin()) {
				if(is_woocommerce())
					$query->set( 'product_cat', $current_server_name_shared_category_id );
			}*/
	#}
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

	

	#

	# OLD WP SETTINGS
	#$wpdb->categories="1fnetwork_categories"; OLD WP SETTINGS
	#$wpdb->term_post2cat="1fnetwork_post2cat"; OLD WP SETTINGS
}

function create_separated_woo_table_for_prefix_then_return() {
	global $debug_force;
	if($debug_force)
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
  	#if($debug_force)
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
    $dirs['baseurl'] = network_site_url( '/wp-content/uploads/shared-wp-posts-uploads-dir' );
    $dirs['basedir'] = ABSPATH . 'wp-content/uploads/shared-wp-posts-uploads-dir';
    $dirs['path'] = $dirs['basedir'] . $dirs['subdir'];
    $dirs['url'] = $dirs['baseurl'] . $dirs['subdir'];

    return $dirs;
}

?>

