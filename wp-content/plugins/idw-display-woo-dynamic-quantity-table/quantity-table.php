<?php

/*
Plugin Name: Woo Dynamic Quantity Table
Plugin URI: https://idworks.com
Description: Shows Dynamic Pricing table, to be used within conjunction with Woocommerce Dynamic Table Plugin.
Author: Identity Works - Drew Teichman
Version: 1.12
Author URI: https://idworks.com/
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

error_reporting(0);
/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {


add_filter( 'woocommerce_get_price_html', 'omniwp_credit_dollars_price', 10, 2 );
			
	function omniwp_credit_dollars_price( $price, $product ) {
		$loop = 0;
		global $woocommerce, $product, $done;
		if (!is_shop() && !is_product_category() && !woocommerce_upsell_display() && !is_front_page() && !is_page() && !$done){
		$pricing_rule_sets = get_post_meta( $product->post->ID, '_pricing_rules', true );
		$pricing_rule_sets = array_shift( $pricing_rule_sets );
		 
		if ( $pricing_rule_sets 
			&& is_array( $pricing_rule_sets ) 
			&& sizeof( $pricing_rule_sets ) ) {
		ob_start();
		?>

<table width="100%" style="border: 0px;">
  <thead>
    <tr align='center' valign='middle' class='lablelight'>
      <th><?php _e('Quantity', 'omniwp_core_functionality' ) ?></th>
      <th><?php _e('Price', 'omniwp_core_functionality' ) ?></th>
    </tr>
  </thead>
  <?php
				foreach ( $pricing_rule_sets['rules'] as $key => $value ) {
					if ( '*' == $pricing_rule_sets['rules'][$key]['to'] ) {
		?>
  <tr align='center' valign='middle' class='lablelight'>
    <td style="border-bottom: 1px solid #e5e5e5!important;"><?php printf( __( '%s - %s', 'omniwp_core_functionality' ) , $pricing_rule_sets['rules'][$key]['from'] )  ?></td>
    <td style="border-bottom: 1px solid #e5e5e5!important;"><?php echo woocommerce_price( $pricing_rule_sets['rules'][$key]['amount'] ); ?></td>
  </tr>
  <?php
					} else {
		?>
  <tr align='center' valign='middle' class='lablelight'>
    <td style="border-bottom: 1px solid #e5e5e5!important;"><?php printf( __( '%s - %s', 'omniwp_core_functionality' ) , $pricing_rule_sets['rules'][$key]['from'], $pricing_rule_sets['rules'][$key]['to'] )  ?></td>
    <td style="border-bottom: 1px solid #e5e5e5!important;"><?php echo woocommerce_price( $pricing_rule_sets['rules'][$key]['amount'] ); ?></td>
  </tr>
  <?php
  					}
				}
?>
</table>
<style>
th{font-size:14px!important;text-align:center;}
th, td, table{border: 0px!important;}
th{border-top: 1px solid #e5e5e5!important;}
.tabledark {
	border: 0px;
	font-size:14px!important;
	background-color:#eee;
}
.lablelight
	{
		border: 0px;
		font-size:14px!important;
	background-color:#eee;
}
.savemoney{
	font-size:14px;
	font-weight:bold;
}
</style>
<?php		
				$price = ob_get_clean();
			} 
			return $price;
		}
	}
}

add_action( 'plugins_loaded', 'woocommerce_quantity_table_textdomain' );