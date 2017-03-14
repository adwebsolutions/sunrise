<?php
/**
 *	Functions to be used with Like 2 Discount plugin
 *
 *	Laborator.co
 *	www.laborator.co
 */


// Like 2 Discount Rules
if( ! function_exists('l2d_get_options'))
{
	function l2d_get_options($var_name = '')
	{
		$nl = PHP_EOL;
		$options = array();

		$options['enabled']    		  = get_option('wc_l2d_enabled', 'no');
		
		$options['display_type']  	  = get_option('wc_like2discount_type', 'like'); // Added in v1.3

		$options['emails_coupons']    = get_option('wc_like2discount_emails_coupons', array());
		$options['coupons_per_email'] = get_option('wc_like2discount_granted_coupons', '1');

		$options['fb_app_id'] 	  	  = get_option('wc_like2discount_fb_app_id', '');
		$options['fb_page_url'] 	  = get_option('wc_like2discount_fb_page_url', '');

		$options['coupon_title'] 	  = get_option('wc_like2discount_coupon_title', 'Get 10% off by liking our Facebook Page!');
		$options['coupon_description']= get_option('wc_like2discount_coupon_description', "Please like our Facebook page and get 10% off from your cart.{$nl}All you have to do is to like our Facebook Page and confirm it with your email.{$nl}{$nl}Note: The generated coupon number will be valid only after you confirm your email ownership.");
		$options['coupon_name']		  = get_option('wc_like2discount_coupon_name', "10% OFF COUPON");

		$options['entrance_class']	  = get_option('wc_like2discount_modal_entrance_class', "fadeInDown");
		$options['exit_class']	  	  = get_option('wc_like2discount_modal_exit_class', "fadeOutUp");

		$options['discount_type']	  = get_option('wc_like2discount_discount_type', 'percent'); // Type: fixed_cart, percent, fixed_product, percent_product
		$options['amount']			  = get_option('wc_like2discount_coupon_amount', '10');

		$options['indiviudal_use']	  = get_option('wc_like2discount_indiviudal_use', 'yes');
		$options['free_shipping']	  = get_option('wc_like2discount_free_shipping', 'no');
		$options['before_tax']	  	  = get_option('wc_like2discount_before_tax', 'yes');
		$options['exclude_sale_items']= get_option('wc_like2discount_exclude_sale_items', 'no');

		$options['expiry_date']	  	  = get_option('wc_like2discount_expiry_date', '');
		$options['usage_limit']	  	  = get_option('wc_like2discount_usage_limit', '1');
		$options['usage_limit_user']  = get_option('wc_like2discount_usage_limit_per_user', '');
		$options['minimum_spend']  	  = get_option('wc_like2discount_minimum_spend', '');

		$options['products_ids']  	  = get_option('wc_like2discount_products_ids');
		$options['ex_products_ids']   = get_option('wc_like2discount_products_ids_exclude');

		$options['category_ids']  	  = get_option('wc_like2discount_product_category_ids');
		$options['ex_category_ids']   = get_option('wc_like2discount_product_category_ids_exclude');

		// Display Setttings
		$options['display_after_addtocart']           = get_option('wc_like2discount_display_after_addtocart', 'yes');
		$options['display_after_firstvisit']          = get_option('wc_like2discount_display_after_firstvisit', 'no');
		$options['display_after_firstvisit_delay']    = get_option('wc_like2discount_display_after_firstvisit_delay', 5);

		# Added in v1.1
		// Email Confirmation
		$options['email_confirmation']    = get_option('wc_l2d_email_confirmation', 'yes');
		$options['allow_share']           = get_option('wc_l2d_allow_share', 'yes');
		
		// Added in v1.3
		// If share button is active, request
		if( $options['display_type'] == 'share' ) {
			$options['email_confirmation'] = true;
		}

		$single_view_banner_style = '.l2d-single-banner {
}

.l2d-single-banner a {
}

.l2d-single-banner.l2d-single-banner-type-image {
}

.l2d-single-banner.l2d-single-banner-type-text {
}';

		$options['banner_img_singleview']             = get_option('wc_like2discount_banner_img_singleview', '');
		$options['banner_img_singleview_position']    = get_option('wc_like2discount_banner_img_singleview_position', 'hide');
		$options['banner_img_singleview_style']       = get_option('wc_like2discount_banner_img_singleview_style', $single_view_banner_style);

		$options['banner_img_loopview']               = get_option('wc_like2discount_banner_img_loopview', '');
		$options['banner_img_loopview_position']      = get_option('wc_like2discount_banner_img_loopview_position', 'hide');
		$options['banner_img_loopview_style']         = get_option('wc_like2discount_banner_img_loopview_style', str_replace('single-banner', 'loop-banner', $single_view_banner_style));
		$options['banner_img_loopview_showoncart']    = get_option('wc_like2discount_banner_img_loopview_showoncart', 'no');

		$options['banner_img_side']                   = get_option('wc_like2discount_banner_img_side', '');
		$options['banner_img_side_position']          = get_option('wc_like2discount_banner_img_side_position', 'hide');

		// Email Template
		$options['verify_email_template']   = get_option('wc_like2discount_verify_email_template', l2d_get_verify_email_template_text());

		// Expiry Date
		if($options['expiry_date'] > 0)
			$options['expiry_date'] = date("Y-m-d", strtotime("today + {$options['expiry_date']} days"));

		// Set empty if have no elements
		$options['products_ids']      = l2d_implode($options['products_ids']);
		$options['ex_products_ids']   = l2d_implode($options['ex_products_ids']);
		$options['category_ids']      = l2d_implode($options['category_ids']);
		$options['ex_category_ids']   = l2d_implode($options['ex_category_ids']);

		// Get specific var only
		if($var_name)
			return $options[$var_name];

		return $options;
	}
}


// Set empty string if array has no elements
if( ! function_exists('l2d_implode'))
{
	function l2d_implode($element)
	{
		$element = maybe_unserialize($element);

		if( ! is_array($element))
			return '';

		return implode(',', $element);
	}
}


// Generate Random String for coupon code
if( ! function_exists('l2d_generate_random_string'))
{
	function l2d_generate_random_string($length = 0)
	{
		if( ! $length)
			$length = mt_rand(6, 10);

		$str = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-"), 0, $length);

		$needle = '-';

		if(substr($str, -strlen($needle)) === $needle)
			$str = substr($str, 0, $length - 1);

		if(strpos($str, $needle) === 0)
			$str = substr($str, 1, $length);

	    return apply_filters( 'l2d_generate_coupon', $str );
	}
}


// Check email coupons
if( ! function_exists('l2d_get_coupons_by_email'))
{
	function l2d_get_coupons_by_email($email, $verified_only = true)
	{
		$emails_coupons = l2d_get_options('emails_coupons');


		foreach($emails_coupons as $i => $el)
		{
			if(strtolower($email) != strtolower($el['email']))
				unset($emails_coupons[$i]);
		}

		if($verified_only)
		{
			foreach($emails_coupons as $i => $el)
			{
				if( ! $el['verified'])
					unset($emails_coupons[$i]);
			}
		}

		return $emails_coupons;
	}
}


// Register email for coupon
if( ! function_exists('l2d_register_email_coupon'))
{
	function l2d_register_email_coupon($email, $coupon)
	{
		$emails_coupons = l2d_get_options('emails_coupons');
		$is_verified = false; // all emails should be verified

		$verify_hash = l2d_generate_random_string(20);

		$emails_coupons[] = array(
			'email'          => $email,
			'coupon'         => $coupon,
			'verified'       => $is_verified,
			'timestamp'      => time(),
			'verify_hash'    => $verify_hash,
			'ip'			 => $_SERVER["REMOTE_ADDR"]
		);

		if($email)
			update_option( 'wc_like2discount_emails_coupons', $emails_coupons );

		// Added in v1.1
		$email_confirmation = l2d_get_options('email_confirmation') == 'yes';

		if($email_confirmation == false)
		{
			l2d_register_coupon($email, $coupon);
		}

		return $verify_hash;
	}
}


// Verify Text
function l2d_get_verify_email_template_text()
{
	return __('Hello dear customer,

You are about to get the discount coupon "#coupon-name#", you just need to verify email ownership.

Please confirm your coupon code "#coupon-code#" and get the sale immediately, click on the link below:

#verify-url#


If you didn\'t applied for the sale coupon, please ignore this email!

--

This email was sent automatically by our system, do not reply to it!

#site-name#', 'woocommerce-like2discount');
}


// Get Verify URL
if( ! function_exists('l2d_get_verify_url'))
{
	function l2d_get_verify_url($email, $verify_hash)
	{
		$email = urlencode($email);
		return home_url( "?l2d-action=verify-coupon&l2d-email={$email}&l2d-code={$verify_hash}" );
	}
}


// Email Template
if( ! function_exists("l2d_verify_email_template"))
{
	function l2d_verify_email_template($email, $coupon_code, $verify_hash)
	{
		$site_name = get_bloginfo('title');

		$coupon_name = l2d_get_options('coupon_name');
		$verify_url = l2d_get_verify_url($email, $verify_hash);

		$content = l2d_get_options('verify_email_template');

		$find     = array('#verify-url#', '#coupon-code#', '#site-name#', '#coupon-name#');
		$replace  = array($verify_url, $coupon_code, $site_name, $coupon_name);
		$content  = str_replace($find, $replace, $content);

		return $content;
	}
}


// Verify Coupon Action
if( ! function_exists('l2d_check_coupon_verification'))
{
	function l2d_check_coupon_verification($email, $verify_hash)
	{
		$options              = l2d_get_options();

		$emails_coupons       = $options['emails_coupons'];
		$coupons_per_email    = $options['coupons_per_email'];

		$verified_coupons	  = l2d_get_coupons_by_email($email);


		// Found entry
		$found_entry = -1;

		foreach($emails_coupons as $i => $entry)
		{
			if($entry['email'] == $email && $entry['verify_hash'] == $verify_hash)
			{
				$found_entry = $i;
				break;
			}
		}

		if($found_entry >= 0)
		{
			if(count($verified_coupons) >= $coupons_per_email && $emails_coupons[$found_entry]['verified'] == false)
			{
				return -1;
			}

			if( ! $emails_coupons[$found_entry]['verified'])
			{
				$emails_coupons[$found_entry]['verified'] = true;

				update_option('wc_like2discount_emails_coupons', $emails_coupons);

				// Register coupon
				l2d_register_coupon($email, $emails_coupons[$found_entry]['coupon']);
			}

			return $found_entry;
		}

		return -1;
	}
}



// Register coupon
if( ! function_exists('l2d_register_coupon'))
{
	function l2d_register_coupon($email, $coupon)
	{
		$options = l2d_get_options();

		// REGISTER COUPON
		$coupon_code      = $coupon;
		$discount_type    = $options['discount_type'];
		$expiry_date	  = $options['expiry_date'];

		$coupon = array(
			'post_title'     => $coupon_code,
			'post_excerpt'   => $options['coupon_name'],
			'post_content'   => '',
			'post_status'    => 'publish',
			'post_author'    => 1,
			'post_type'      => 'shop_coupon'
		);

		$new_coupon_id = wp_insert_post($coupon);

		// Add meta
		update_post_meta($new_coupon_id, 'discount_type', $options['discount_type']);
		update_post_meta($new_coupon_id, 'coupon_amount', $options['amount']);

		update_post_meta($new_coupon_id, 'individual_use', $options['indiviudal_use']);
		update_post_meta($new_coupon_id, 'free_shipping', $options['free_shipping']);
		update_post_meta($new_coupon_id, 'apply_before_tax', $options['before_tax']);
		update_post_meta($new_coupon_id, 'exclude_sale_items', $options['exclude_sale_items']);

		update_post_meta($new_coupon_id, 'expiry_date', $options['expiry_date']);
		update_post_meta($new_coupon_id, 'usage_limit', $options['usage_limit']);
		update_post_meta($new_coupon_id, 'usage_limit_per_user', $options['usage_limit_user']);
		update_post_meta($new_coupon_id, 'minimum_amount', $options['minimum_spend']);

		update_post_meta($new_coupon_id, 'product_ids', $options['products_ids']);
		update_post_meta($new_coupon_id, 'exclude_product_ids', $options['ex_products_ids']);

		update_post_meta($new_coupon_id, 'product_categories', $options['category_ids']);
		update_post_meta($new_coupon_id, 'exclude_product_categories', $options['ex_category_ids']);

		update_post_meta($new_coupon_id, 'created_by', 'like2discount');
	}
}



// Get coupon object (if has)
if( ! function_exists('l2d_get_coupon_obj'))
{
	function l2d_get_coupon_obj($coupon_code)
	{
		$coupon = new WC_Coupon($coupon_code);

		return $coupon->id ? $coupon : null;
	}
}



// Get all registered emails
if( ! function_exists('l2d_get_all_emails'))
{
	function l2d_get_all_emails($type = 'all')
	{
		$options = l2d_get_options();
		$emails_coupons = $options['emails_coupons'];

		if($type == 'verified')
		{
			foreach($emails_coupons as $i => $el)
			{
				if( ! $el['verified'])
				{
					unset($emails_coupons[$i]);
				}
			}
		}
		else
		if($type == 'not_verified')
		{
			foreach($emails_coupons as $i => $el)
			{
				if($el['verified'])
				{
					unset($emails_coupons[$i]);
				}
			}
		}

		return $emails_coupons;
	}
}


// Delete Email from list
if( ! function_exists('l2d_delete_email'))
{
	function l2d_delete_email($email, $verify_hash)
	{
		$options = l2d_get_options();
		$emails_coupons = $options['emails_coupons'];

		foreach($emails_coupons as $i => $el)
		{
			if($el['email'] == $email && $el['verify_hash'] == $verify_hash)
			{
				unset($emails_coupons[$i]);

				$emails_coupons = array_values($emails_coupons);

				update_option( 'wc_like2discount_emails_coupons', $emails_coupons );

				return true;
			}
		}

		return false;
	}
}



// Settings field type
if( ! function_exists('woocommerce_admin_field_separator'))
{
	function woocommerce_admin_field_separator()
	{
?>
<tr valign="top">
	<td colspan="2" style="padding-bottom:20px;">
		<hr />
	</td>
</tr>
	<?php
	}
}



// Settings link
if( ! function_exists('l2d_settings_link'))
{
	function l2d_settings_link($links)
	{
		$settings_link = '<a href="admin.php?page=wc-settings&tab=like2discount">Settings</a>';
		array_unshift($links, $settings_link);

		return $links;
	}
}


// Check Purchase
if(is_admin() && isset($_POST['l2dvpc']) && strlen($_POST['l2dvpc']) > 2)
{
	$u = 'http://plugins.laborator.co/like2discount/wp-admin/admin-ajax.php?action=l2dvpc&pc=' . $_POST['l2dvpc'];

	try
	{
		$content = wp_remote_get($u);

		if( ! is_wp_error($content))
		{
			$body = wp_remote_retrieve_body($content);

			update_option('l2dvpc_status', $body);

			if($body != 1)
				update_option("l2dvpc_error", 1);
		}
	}
	catch(Exception $e)
	{
		update_option('l2dvpc_status', 1);
	}
}


if( ! function_exists('l2d_vpcnotification'))
{
	function l2d_vpcnotification()
	{
		if(isset($_GET['tab']) && $_GET['tab'] == 'like2discount')
			return;
		?>
		<div class="updated">
			<p>
				<strong>Notice:</strong>
				Please activate <a href="<?php echo admin_url('admin.php?page=wc-settings&tab=like2discount'); ?>">Like 2 Discount</a> plugin!
			</p>
		</div>
		<?php
	}
}
