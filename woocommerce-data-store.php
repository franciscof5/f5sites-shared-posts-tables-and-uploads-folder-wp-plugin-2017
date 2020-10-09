<?php
/**
 * WC Coupon Data Store: Custom Table.
 *
class WC_Order_Data_Store_CPT_FNETWORK extends WC_Data_Store_WP implements WC_Object_Data_Store_Interface, WC_Abstract_Order_Data_Store_Interface {

}

function myplugin_set_wc_coupon_data_store( $stores ) {
	$stores['order'] = 'WC_Coupon_Data_Store_Custom_Table';
	return $stores;
}

add_filter( 'woocommerce_data_stores', 'myplugin_set_wc_coupon_data_store' );
#WC_Object_Data_Store_Interface, WC_Abstract_Order_Data_Store_Interface

#class Abstract_WC_Order_Data_Store_CPT extends WC_Data_Store_WP implements WC_Object_Data_Store_Interface, WC_Abstract_Order_Data_Store_Interface

# WC_Abstract_Order WC_Order_Data_Store_CPT WC_Shop_Order_Data_Store_Custom_Table*/