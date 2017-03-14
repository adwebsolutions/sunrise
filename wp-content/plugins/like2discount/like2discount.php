<?php
/**
 * Plugin Name: Like 2 Discount
 * Plugin URI: http://laborator.co/view-plugin/like2discount/
 * Description: Offer discounts coupons to all of them who like your Facebook Page. It includes email verification for each coupon, so you will get 1 like + 1 customer email in return, cool!
 * Version: 1.3.1
 * Author: Laborator
 * Author URI: http://laborator.co
 *
 * Text Domain: woocommerce-like2discount
 * Domain Path: /languages/
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) die( 0 );


// Load Locale
load_plugin_textdomain( 'woocommerce-like2discount', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


// Functions and classes
include_once('inc/functions.php');
include_once('inc/settings-tab-class.php');


// When Activated
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
{
	$options = l2d_get_options();

	add_action('init', 'l2d_init');
	add_action('admin_menu', 'l2d_woocommerce_emails_menu_item', 100);
	add_option('l2dinit', time());

	if( $options['enabled'] == 'yes' )
	{
		add_action('wp_enqueue_scripts', 'l2d_wp_enqueue_scripts');
		add_action('wp_footer', 'l2d_wp_footer');

		if($options['display_after_addtocart'] == 'yes')
		{
			add_filter('body_class', 'l2d_add_to_cart_modal_show_class');
		}

		if($options['display_after_firstvisit'] == 'yes')
		{
			add_filter('body_class', 'l2d_after_first_visit_class');
		}

		// Single View Banner Options
		if($options['banner_img_singleview_position'] != 'hide' && $options['banner_img_singleview'])
		{
			$l2d_single_view_priorities = array(
				'title_after'       => 6,
				'rating_after'      => 11,
				'price_after'       => 12,
				'excerpt_after'     => 21,
				'addtocart_after'   => 35,
				'sharing_after'     => 51,
				'single_after'		=> 11
			);

			$l2d_single_view_priority = $l2d_single_view_priorities[ $options['banner_img_singleview_position'] ];

			if($options['banner_img_singleview_position'] == 'addtocart_after') // Add to cart has different action hooks names
			{
				add_action('woocommerce_single_product_summary', 'l2d_single_view_banner_show', $l2d_single_view_priority);

			}
			else
			if($options['banner_img_singleview_position'] == 'single_after') // Append after product block
			{
				add_action('woocommerce_after_single_product_summary', 'l2d_single_view_banner_show', $l2d_single_view_priority);
			}
			else
			{
				add_action('woocommerce_single_product_summary', 'l2d_single_view_banner_show', $l2d_single_view_priority);
			}
		}

		// Products Loop Banner Options
		if($options['banner_img_loopview_position'] != 'hide' && $options['banner_img_loopview'])
		{
			$l2d_loop_view_priorities = array(
				'loop_before'       => 40,
				'loop_after'        => 40,
				'loop_beforeafter'  => 40
			);

			$l2d_loop_view_priority = $l2d_loop_view_priorities[ $options['banner_img_loopview_position'] ];


			if($options['banner_img_loopview_position'] == 'loop_beforeafter') // Show on both positions
			{
				add_action('woocommerce_before_shop_loop', 'l2d_loop_view_banner_show', $l2d_loop_view_priority);
				add_action('woocommerce_after_shop_loop', 'l2d_loop_view_banner_show', $l2d_loop_view_priority);

				if($options['banner_img_loopview_showoncart'] == 'yes')
				{
					add_action('woocommerce_before_cart', 'l2d_loop_view_banner_show', $l2d_loop_view_priority);
					add_action('woocommerce_after_cart', 'l2d_loop_view_banner_show', $l2d_loop_view_priority);
				}
			}
			else
			if($options['banner_img_loopview_position'] == 'loop_before')
			{
				add_action('woocommerce_before_shop_loop', 'l2d_loop_view_banner_show', $l2d_loop_view_priority);

				if($options['banner_img_loopview_showoncart'] == 'yes')
					add_action('woocommerce_before_cart', 'l2d_loop_view_banner_show', $l2d_loop_view_priority);
			}
			else
			if($options['banner_img_loopview_position'] == 'loop_after')
			{
				add_action('woocommerce_after_shop_loop', 'l2d_loop_view_banner_show', $l2d_loop_view_priority);

				if($options['banner_img_loopview_showoncart'] == 'yes')
					add_action('woocommerce_after_cart', 'l2d_loop_view_banner_show', $l2d_loop_view_priority);
			}

		}

		// Side banner
		if($options['banner_img_side_position'] != 'hide' && $options['banner_img_side'])
		{
			add_action('wp_footer', 'l2d_side_view_banner');
		}
	}
}

function l2d_woocommerce_emails_menu_item()
{
	include_once('inc/emails-table-class.php');

	add_submenu_page( 'woocommerce', __('L2D Emails', 'woocommerce-like2discount'), __('L2D Emails', 'woocommerce-like2discount'), 'manage_options', 'l2d-registered-emails', 'l2d_registered_emails_table' );

	if(isset($_GET['page']) && $_GET['page'] == 'l2d-registered-emails' && isset($_GET['to']) && $_GET['to'] == 'csv')
	{
		$filter_type = isset($_REQUEST['filter_type']) ? $_REQUEST['filter_type'] : '';
		$data = l2d_get_all_emails($filter_type);

		ob_start();

		foreach($data as $i => $entry)
		{
			$nr = $i + 1;
			$date = date_i18n("Y-m-d H:i", $entry['timestamp']);

			echo PHP_EOL . $nr . ',' . $entry['email'] . ',' . $entry['coupon'] . ',' . $date . ',' . ($entry['verified'] ? 'Yes' : 'No') . ',' . $entry['ip'];
		}

		$data = ob_get_clean();

		// Start Download
		header("Content-type: text/csv", true, 200);
		header("Content-Disposition: attachment; filename=l2d-data-".date("Ymd").".csv");
		header("Pragma: no-cache");
		header('Content-Length: ' . strlen($data));
		header("Expires: 0");

		die(
			'List Num.,Email,Coupon Code,Register Date,Verified,IP Address' .
			$data
		);
	}
}

function l2d_registered_emails_table()
{
	include_once('inc/emails-table.php');
}

function l2d_after_first_visit_class($classes)
{
	$classes[] = 'l2d-modal l2d-show-delay-' . get_option('wc_like2discount_display_after_firstvisit_delay');

	return $classes;
}

function l2d_add_to_cart_modal_show_class($classes)
{
	$classes[] = 'l2d-after-add-to-cart';

	return $classes;
}

function l2d_single_view_banner_show()
{
	include('inc/single-view-banner.php');
}

function l2d_loop_view_banner_show()
{
	include('inc/loop-view-banner.php');

	if( ! defined("L2D_LVB_SHOWN"))
		define("L2D_LVB_SHOWN", true);
}

function l2d_side_view_banner()
{
	include_once('inc/side-view-banner.php');
}


// Init WooCommerce Settings Tab
WC_LikeToDiscounts_Tab::init();


// Request New Coupon Code
add_action('wp_ajax_laborator_l2d_request_new_coupon_code', 'laborator_l2d_request_new_coupon_code');
add_action('wp_ajax_nopriv_laborator_l2d_request_new_coupon_code', 'laborator_l2d_request_new_coupon_code');

function laborator_l2d_request_new_coupon_code()
{
	include_once('inc/request-coupon-code.php');
}


// Submit Email
add_action('wp_ajax_laborator_l2d_submit_coupon_email', 'laborator_l2d_submit_coupon_email');
add_action('wp_ajax_nopriv_laborator_l2d_submit_coupon_email', 'laborator_l2d_submit_coupon_email');

function laborator_l2d_submit_coupon_email()
{
	include_once('inc/submit-email-coupon.php');
}


// Init Like2Discount
function l2d_init()
{
	$plugin_url = plugins_url() . "/like2discount/";

	wp_register_script('like2discount', "{$plugin_url}assets/js/like2discount-min.js");

	// Stylesheets
	wp_register_style('like2discount', "{$plugin_url}assets/css/like2discount.css");
	wp_register_style('animate.css', "{$plugin_url}assets/css/animate.css");

	// Check for verify action
	if(isset($_GET['l2d-action']) && $_GET['l2d-action'] == 'verify-coupon' && ! empty($_GET['l2d-email']) && ! empty($_GET['l2d-code']))
	{
		$email = $_GET['l2d-email'];
		$verify_hash = $_GET['l2d-code'];

		$index = l2d_check_coupon_verification($email, $verify_hash);

		if($index >= 0)
		{
			define("L2D_COUPON_REGISTERED", $index);
		}
	}

	// Notice
	if(get_option('l2dinit') < (time() - 604800))
	{
		#add_action('admin_notices', 'l2d_vpcnotification');
	}
}


// Apply Coupon
if ( ! empty( $_GET['l2d-apply-coupon'] ) ) {
	add_action( 'wp', 'l2d_apply_coupon_action' );
}
 
function l2d_apply_coupon_action() {

	global $woocommerce;
	
	$coupon_code = $_GET['l2d-apply-coupon']; 
	
	if ( $woocommerce->cart->has_discount( $coupon_code ) ) return;
	
	$woocommerce->cart->add_discount( $coupon_code );
	
	wp_redirect( WC()->cart->get_cart_url() );
}


// Eneuque Scripts
function l2d_wp_enqueue_scripts()
{
	wp_enqueue_script('jquery');
	wp_enqueue_script('like2discount');

	wp_enqueue_style(array('animate.css', 'like2discount'));
}


// Footer
function l2d_wp_footer()
{
	// Show Success Modal
	if(defined("L2D_COUPON_REGISTERED"))
	{
		include_once('inc/modal-success-view.php');
	}
	// Default Register Modal
	else
	{
		include_once('inc/modal-view.php');
	}


	// After Add To Cart show modal
	if(get_option('wc_like2discount_display_after_addtocart') == 'yes')
	{
		$all_notices  = WC()->session->get( 'wc_notices', array() );

		if(isset($_REQUEST['add-to-cart']))
		{
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($){ setTimeout(window.showModalAfterAddingToCart, 1000); });
		</script>
		<?php
		}
	}
}


// Shortcode Banner
add_shortcode('l2d_link', 'l2d_link_shortcode');

function l2d_link_shortcode($atts = array(), $content = '')
{
	$is_image = preg_match("/\.[a-z0-9]+$/i", $content);

	$img_or_text = $is_image ? ('<img src="'.str_replace('&#215;', 'x', $content).'" />') : $content;

	$html = '<a href="#" class="l2d-show-modal">' . $img_or_text . '</a>';

	return $html;
}


// WooCommerce Separator Field
add_action('woocommerce_admin_field_separator', 'woocommerce_admin_field_separator');


// Settings Link
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'l2d_settings_link' );
