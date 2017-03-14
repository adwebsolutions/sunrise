<?php
/**
 *	L2C WooCommerce Class
 *
 *	Laborator.co
 *	www.laborator.co
 */


class WC_LikeToDiscounts_Tab
{
	private static $tabs;

	public static function init()
	{
		add_filter('woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50);
		add_action('woocommerce_settings_tabs_like2discount', __CLASS__ . '::settings_tab');
		add_action('woocommerce_update_options_like2discount', __CLASS__ . '::update_settings');

		add_action('woocommerce_settings_start', __CLASS__ . '::settings_start');
	}


	public static function add_settings_tab( $settings_tabs )
	{
		$settings_tabs['like2discount'] = __('Like 2 Discount', 'woocommerce-like2discount');
		return $settings_tabs;
	}


	public static function settings_tab()
	{
		woocommerce_admin_fields( self::get_settings() );
	}


	public static function update_settings()
	{
		woocommerce_update_options( self::get_settings() );
	}


	public static function get_settings()
	{
		$products_list = array();
		$product_categories_list = array();

		$pl_wp_query = new WP_Query(array('post_type' => 'product', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'asc'));
		$pcl_terms = get_terms('product_cat');

		while($pl_wp_query->have_posts())
		{
			$pl_wp_query->the_post();
			$products_list[get_the_id()] = get_the_title();
		}

		foreach($pcl_terms as $term)
		{
			$product_categories_list[$term->term_id] = $term->name;
		}

		wp_reset_postdata();


		$animated_transitions_list = array(
			"None"                 => '',
			"bounce"               => "bounce",
			"flash"                => "flash",
			"pulse"                => "pulse",
			"rubberBand"           => "rubberBand",
			"shake"                => "shake",
			"swing"                => "swing",
			"tada"                 => "tada",
			"wobble"               => "wobble",
			"bounceIn"             => "bounceIn",
			"bounceInDown"         => "bounceInDown",
			"bounceInLeft"         => "bounceInLeft",
			"bounceInRight"        => "bounceInRight",
			"bounceInUp"           => "bounceInUp",
			"fadeIn"               => "fadeIn",
			"fadeInDown"           => "fadeInDown",
			"fadeInDownBig"        => "fadeInDownBig",
			"fadeInLeft"           => "fadeInLeft",
			"fadeInLeftBig"        => "fadeInLeftBig",
			"fadeInRight"          => "fadeInRight",
			"fadeInRightBig"       => "fadeInRightBig",
			"fadeInUp"             => "fadeInUp",
			"fadeInUpBig"          => "fadeInUpBig",
			"flip"                 => "flip",
			"flipInX"              => "flipInX",
			"flipInY"              => "flipInY",
			"lightspeedIn"         => "lightspeedIn",
			"rotateIn"             => "rotateIn",
			"rotateInDownLeft"     => "rotateInDownLeft",
			"rotateInDownRight"    => "rotateInDownRight",
			"rotateInUpLeft"       => "rotateInUpLeft",
			"rotateInUpRight"      => "rotateInUpRight",
			"slideInDown"          => "slideInDown",
			"slideInLeft"          => "slideInLeft",
			"slideInRight"         => "slideInRight",
			"slideInUp"            => "slideInUp",
			"hinge"                => "hinge",
			"rollIn"               => "rollIn",
			"zoomIn"               => "zoomIn",
			"zoomInDown"           => "zoomInDown",
			"zoomInLeft"           => "zoomInLeft",
			"zoomInRight"          => "zoomInRight",
			"zoomInUp"             => "zoomInUp"
		);

		$animated_exit_transitions_list = array();

		foreach($animated_transitions_list as $key => $val)
		{
			$animated_exit_transitions_list[ str_replace('In', 'Out', $key) ] = str_replace('In', 'Out', $val);
		}

		$options = l2d_get_options();

		$settings = array(

			// ! General Settings
			array('name' => __('General Settings', 'woocommerce-like2discount'), 'desc' => __('Toggle the functionality of Like 2 Discount plugin.', 'woocommerce-like2discount'), 'type' => 'title'),

			array(
				'title'     => __('Enable', 'woocommerce-like2discount'),
				'desc'      => __('Enable Like2Discount coupons', 'woocommerce-like2discount'),
				'id'        => 'wc_l2d_enabled',
				'default'   => $options['enabled'],
				'type'      => 'checkbox'
			),

			array(
				'title'     => __('Allow share', 'woocommerce-like2discount'),
				'desc'      => __('Show "share on wall" popup after user likes the page.', 'woocommerce-like2discount'),
				'id'        => 'wc_l2d_allow_share',
				'default'   => $options['allow_share'],
				'type'      => 'checkbox'
			),

			array(
				'title'     => __('Email Confirmation', 'woocommerce-like2discount'),
				'desc'      => __('Require email confirmation', 'woocommerce-like2discount'),
				'id'        => 'wc_l2d_email_confirmation',
				'default'   => $options['email_confirmation'],
				'type'      => 'checkbox'
			),

			array(
				'title'             => __('Display Type', 'woocommerce-like2discount'),
				'desc'              => __('Choose Facebook button type to display.<br><br>If set to "Share", share confirmation event is not possible because Facebook API doesn\'t allow this, so user may or may not share the page/link. Also when "Share" option is selected, email confirmation for users is automatically requested!<br><br>If you select "Send" button, then Facebook URL is not accepted, include other domain!', 'woocommerce-like2discount'),
				'id'                => 'wc_like2discount_type',
				'css'               => 'min-width:100px;',
				'class'             => 'chosen_select',
				'default'           => $options['display_type'],
				'type'              => 'select',
				'options'           => array(
					'like'      	 => __('Like', 'woocommerce-like2discount'),
					'share'          => __('Share', 'woocommerce-like2discount'),
					'send'           => __('Send', 'woocommerce-like2discount'),
				),
				'desc_tip'          => true,
			),

			array(
				'title'     => __('*Facebook Page URL', 'woocommerce-like2discount'),
				'desc'      => '<p class="description" style="font-size: 11px;">Enter the Facebook page URL that users will have to like it. Example: https://www.facebook.com/laboratorcreative</p>',
				'id'        => 'wc_like2discount_fb_page_url',
				'type'      => 'text',
				'css'       => 'min-width:400px;',
				'desc_tip'  => false,
			),

			array(
				'title'     => __('*Facebook App ID', 'woocommerce-like2discount'),
				'desc'      => '<p class="description" style="font-size: 11px;">Enter your personal Facebook App ID. Learn more how to create <a href="https://developers.facebook.com/apps" target="_blank">Facebook App</a>.</p>',
				'id'        => 'wc_like2discount_fb_app_id',
				'type'      => 'text',
				'css'       => 'min-width:250px;'
			),

			array( 'type' => 'sectionend', 'id' => 'wc_like2discount_section_end'),



			// ! Coupon Settings
			array('name' => __('Coupon Settings', 'woocommerce-like2discount'), 'desc' => __('Coupon parameters will be used to create the coupon code.', 'woocommerce-like2discount'), 'type' => 'title'),

			array(
				'title'     => __('Individual use', 'woocommerce-like2discount'),
				'desc'      => 'Coupon cannot be used in conjunction with other coupons.',
				'id'        => 'wc_like2discount_indiviudal_use',
				'type'      => 'checkbox',
				'default'   => $options['indiviudal_use'],
				'desc_tip'  => false
			),

			array(
				'title'     => __('Allow free shipping', 'woocommerce-like2discount'),
				'desc'      => 'Coupon grants free shipping. The <a href="'.admin_url('admin.php?page=wc-settings&tab=shipping&section=WC_Shipping_Free_Shipping').'">free shipping method</a> must be enabled with the "must use coupon" setting.',
				'id'        => 'wc_like2discount_free_shipping',
				'type'      => 'checkbox',
				'default'   => $options['free_shipping'],
				'desc_tip'  => false
			),

			array(
				'title'     => __('Apply before tax', 'woocommerce-like2discount'),
				'desc'      => 'Coupon should be applied before calculating cart tax.',
				'id'        => 'wc_like2discount_before_tax',
				'type'      => 'checkbox',
				'default'   => $options['before_tax'],
				'desc_tip'  => false
			),

			array(
				'title'             => __('Discount type', 'woocommerce-like2discount'),
				'desc'              => __('Related to the amount of coupon.', 'woocommerce-like2discount'),
				'id'                => 'wc_like2discount_discount_type',
				'css'               => 'min-width:250px;',
				'class'             => 'chosen_select',
				'default'           => $options['discount_type'],
				'type'              => 'select',
				'options'           => array(
					'fixed_cart'       => __('Cart Discount', 'woocommerce-like2discount'),
					'percent'          => __('Cart % Discount', 'woocommerce-like2discount'),
					'fixed_product'    => __('Product Discount', 'woocommerce-like2discount'),
					'percent_product'  => __('Product % Discount', 'woocommerce-like2discount' )
				),
				'desc_tip'          => true,
			),

			array(
				'title'     => __('Coupon amount', 'woocommerce-like2discount'),
				'desc'      => 'Enter the amount of coupon (no appended currency or percentage). Currency is: ' . get_woocommerce_currency(),
				'id'        => 'wc_like2discount_coupon_amount',
				'type'      => 'number',
				'default'   => $options['amount'],
				'css'       => 'max-width:70px;',
				'desc_tip'  => true,
			),

			array(
				'title'     => __('Coupon availability', 'woocommerce-like2discount'),
				'desc'      => 'In days. (Leave empty for no expiring date)',
				'id'        => 'wc_like2discount_expiry_date',
				'type'      => 'number',
				'default'   => $options['expiry_date'],
				'css'       => 'max-width:70px;',
				'desc_tip'  => true
			),

			array(
				'title'     => __('Granted coupons per email', 'woocommerce-like2discount'),
				'desc'      => 'How many coupons can a single email acquire.',
				'id'        => 'wc_like2discount_granted_coupons',
				'type'      => 'number',
				'default'   => $options['coupons_per_email'],
				'css'       => 'max-width:70px;',
				'desc_tip'  => true
			),

			array(
				'title'     => __('Usage limit per coupon', 'woocommerce-like2discount'),
				'desc'      => 'How many times this coupon can be used before it is void.',
				'id'        => 'wc_like2discount_usage_limit',
				'type'      => 'number',
				'default'   => $options['usage_limit'],
				'css'       => 'max-width:70px;',
				'desc_tip'  => true
			),

			array(
				'title'     => __('Usage limit per user', 'woocommerce-like2discount'),
				'desc'      => 'How many times this coupon can be used by an invidual user. Uses billing email for guests, and user ID for logged in users.',
				'id'        => 'wc_like2discount_usage_limit_per_user',
				'type'      => 'number',
				'default'   => $options['usage_limit_user'],
				'css'       => 'max-width:70px;',
				'desc_tip'  => true
			),

			array(
				'title'     => __('Minimum spend', 'woocommerce-like2discount'),
				'desc'      => '(Optional) Set the minimum subtotal needed to use the coupon. Currency is: ' . get_woocommerce_currency(),
				'id'        => 'wc_like2discount_minimum_spend',
				'type'      => 'number',
				'default'   => $options['minimum_spend'],
				'css'       => 'max-width:70px;',
				'desc_tip'  => true
			),

			array(
				'title'     => __('Products', 'woocommerce-like2discount'),
				'desc'      => 'Products which need to be in the cart to use this coupon or, for "Product Discounts", which products are discounted.',
				'id'        => 'wc_like2discount_products_ids',
				'type'      => 'multiselect',
				'options'   => $products_list,
				'css'       => 'max-height:30px; min-width: 350px;',
				'class'     => 'chosen_products_list',
				'desc_tip'  => true
			),

			array(
				'title'     => __('Products excluded', 'woocommerce-like2discount'),
				'desc'      => 'Products which must not be in the cart to use this coupon or, for "Product Discounts", which products are not discounted.',
				'id'        => 'wc_like2discount_products_ids_exclude',
				'type'      => 'multiselect',
				'options'   => $products_list,
				'css'       => 'max-height:30px; min-width: 350px;',
				'class'     => 'chosen_products_list',
				'desc_tip'  => true
			),

			array(
				'title'     => __('Product categories', 'woocommerce-like2discount'),
				'desc'      => 'A product must be in this category for the coupon to remain valid or, for "Product Discounts", products in these categories will be discounted.',
				'id'        => 'wc_like2discount_product_category_ids',
				'type'      => 'multiselect',
				'options'   => $product_categories_list,
				'css'       => 'max-height:30px; min-width: 350px;',
				'class'     => 'chosen_product_categories_list',
				'desc_tip'  => true
			),

			'l2d_category_ids_exclude' => array(
				'title'     => __('Categories excluded', 'woocommerce-like2discount'),
				'desc'      => 'Product must not be in this category for the coupon to remain valid or, for "Product Discounts", products in these categories will not be discounted.',
				'id'        => 'wc_like2discount_product_category_ids_exclude',
				'type'      => 'multiselect',
				'options'   => $product_categories_list,
				'css'       => 'max-height:30px; min-width: 350px;',
				'class'     => 'chosen_product_categories_list',
				'desc_tip'  => true
			),


			array( 'type' => 'sectionend', 'id' => 'wc_like2discount_section_end'),



			// ! Modal Content Settings
			array('name' => __('Modal Content', 'woocommerce-like2discount'), 'desc' => __('Setup popup display information.', 'woocommerce-like2discount'), 'type' => 'title'),

			array(
				'title'     => __('Modal Title', 'woocommerce-like2discount'),
				'desc'      => 'Title to be displayed in the modal header. Example: Get 10% off coupon by liking our Facebook page!',
				'id'        => 'wc_like2discount_coupon_title',
				'type'      => 'text',
				'default'   => $options['coupon_title'],
				'css'       => 'min-width:400px;',
				'desc_tip'  => true
			),

			array(
				'title'     => __('Modal Description', 'woocommerce-like2discount'),
				'desc'      => 'Describe coupon and give instructions how to acquire it. HTML formatting is allowed.',
				'id'        => 'wc_like2discount_coupon_description',
				'type'      => 'textarea',
				'default'   => $options['coupon_description'],
				'css'       => 'min-width:400px; min-height:180px;',
				'desc_tip'  => true
			),

			array(
				'title'     => __('Coupon Name', 'woocommerce-like2discount'),
				'desc'      => 'Coupon name will be displayed on cart when applied.',
				'id'        => 'wc_like2discount_coupon_name',
				'type'      => 'text',
				'default'   => $options['coupon_name'],
				'css'       => 'min-width:400px;',
				'desc_tip'  => true
			),

			array(
				'title'     => __('Show effect', 'woocommerce-like2discount'),
				'desc'      => '<p class="description" style="font-size: 11px;">Animation effect when modal is shown.</p>',
				'id'        => 'wc_like2discount_modal_entrance_class',
				'class'     => 'chosen_select',
				'default'   => $options['entrance_class'],
				'type'      => 'select',
				'options'   => $animated_transitions_list ,
				'desc_tip'	=> true,
			),

			array(
				'title'     => __('Hide effect', 'woocommerce-like2discount'),
				'desc'      => '<p class="description" style="font-size: 11px;">Animation effect when modal is hidden.</p>',
				'id'        => 'wc_like2discount_modal_exit_class',
				'class'     => 'chosen_select',
				'default'   => $options['exit_class'],
				'type'      => 'select',
				'options'   => $animated_exit_transitions_list,
				'desc_tip'	=> true,
			),


			array( 'type' => 'sectionend', 'id' => 'wc_like2discount_section_end'),



			// ! Display Settings
			array('name' => __('Display Settings', 'woocommerce-like2discount'), 'desc' => __('When do you want to display <strong>Like 2 Discount</strong> modal.', 'woocommerce-like2discount'), 'type' => 'title'),

			array(
				'title'     => __('Add to cart', 'woocommerce-like2discount'),
				'desc'      => '<strong>Like 2 Discount</strong> modal will be shown after user adds an item to cart (one time only).',
				'id'        => 'wc_like2discount_display_after_addtocart',
				'type'      => 'checkbox',
				'default'   => $options['display_after_addtocart'],
				'desc_tip'  => false
			),

			array(
				'title'     => __('Entering the site', 'woocommerce-like2discount'),
				'desc'      => '<strong>Like 2 Discount</strong> modal will be shown when user enters the site for the first time.',
				'id'        => 'wc_like2discount_display_after_firstvisit',
				'type'      => 'checkbox',
				'default'   => $options['display_after_firstvisit'],
				'desc_tip'  => false
			),

			array(
				'title'     => __('Delay time', 'woocommerce-like2discount'),
				'desc'      => 'Number of seconds to wait before showing the modal for the first time (after user enters the site).',
				'id'        => 'wc_like2discount_display_after_firstvisit_delay',
				'type'      => 'number',
				'default'   => $options['display_after_firstvisit_delay'],
				'css'       => 'max-width:70px;',
				'desc_tip'  => true
			),

			array( 'type' => 'sectionend', 'id' => 'wc_like2discount_section_end'),



			// ! Banners
			array('name' => __('Banners', 'woocommerce-like2discount'), 'desc' => __('Show informational banners about the coupon discounts you grant. <br />You can use the popup link as shortcode <code>[l2d_link]http://image url[/l2d_link] or [l2d_link]Get 10% Off[/l2d_link]</code>', 'woocommerce-like2discount'), 'type' => 'title'),


			array(
				'title'     => __('Single item view', 'woocommerce-like2discount'),
				'desc'      => '<a href="#wc_like2discount_banner_img_singleview" class="open-media">Select an image from gallery</a> or Set banner text <p class="description" style="font-size: 11px;">When user is viewing the full item details this image or text will be shown.</p>',
				'id'        => 'wc_like2discount_banner_img_singleview',
				'type'      => 'text',
				'css'       => 'min-width:350px;',
				'default'   => $options['banner_img_singleview'],
				'desc_tip'	=> 'Select image or add some text.'
			),

			array(
				'title'     => __('Banner position', 'woocommerce-like2discount'),
				'desc'      => '<p class="description" style="font-size: 11px;">This option is related to <strong>Single item view</strong></p>',
				'id'        => 'wc_like2discount_banner_img_singleview_position',
				'class'     => 'chosen_select',
				'default'   => $options['banner_img_singleview_position'],
				'type'      => 'select',
				'options'   => array(
					'hide'             => __('Do not show this banner', 'woocommerce-like2discount'),
					'title_after'      => __('Under the title', 'woocommerce-like2discount'),
					'rating_after'     => __('Under the rating', 'woocommerce-like2discount'),
					'price_after'      => __('Under the price', 'woocommerce-like2discount'),
					'excerpt_after'    => __('Under the excerpt', 'woocommerce-like2discount'),
					'addtocart_after'  => __('Under the add to cart', 'woocommerce-like2discount'),
					'sharing_after'    => __('Under the share links', 'woocommerce-like2discount'),
					'single_after'     => __('Under the product block', 'woocommerce-like2discount'),
				),
				'desc_tip'	  => __('Select the position of image/text banner to show.', 'woocommerce-like2discount'),
			),

			array(
				'title'     => __('Banner CSS style', 'woocommerce-like2discount'),
				'desc'      => '<p class="description" style="font-size: 11px;">This option is related to <strong>Single item view</strong></p>',
				'id'        => 'wc_like2discount_banner_img_singleview_style',
				'type'      => 'textarea',
				'default'   => $options['banner_img_singleview_style'],
				'css'       => 'min-width:400px; min-height:180px;',
				'desc_tip'  => 'CSS styling only'
			),

			array( 'type' => 'separator'),

			array(
				'title'     => __('Products loop page', 'woocommerce-like2discount'),
				'desc'      => '<a href="#wc_like2discount_banner_img_loopview" class="open-media">Select an image from gallery</a> or Set banner text <p class="description" style="font-size: 11px;">When user is viewing products archive/category this image or text will be shown.</p>',
				'id'        => 'wc_like2discount_banner_img_loopview',
				'type'      => 'text',
				'css'       => 'min-width:350px;',
				'default'   => $options['banner_img_loopview'],
				'desc_tip'	=> 'Select image or add some text.'
			),

			array(
				'title'     => __('Banner position', 'woocommerce-like2discount'),
				'desc'      => '<p class="description" style="font-size: 11px;">This option is related to <strong>Products loop page</strong></p>',
				'id'        => 'wc_like2discount_banner_img_loopview_position',
				'class'     => 'chosen_select',
				'default'   => $options['banner_img_loopview_position'],
				'type'      => 'select',
				'options'   => array(
					'hide'             => __('Do not show this banner', 'woocommerce-like2discount'),
					'loop_before'      => __('Before the shop loop', 'woocommerce-like2discount'),
					'loop_after'       => __('After the shop loop', 'woocommerce-like2discount'),
					'loop_beforeafter' => __('Before & after the shop loop', 'woocommerce-like2discount'),
				),
				'desc_tip'	  => __('Select the position of image/text banner to show.', 'woocommerce-like2discount'),
			),

			array(
				'title'     => __('Banner CSS style', 'woocommerce-like2discount'),
				'desc'      => '<p class="description" style="font-size: 11px;">This option is related to <strong>Products loop page</strong></p>',
				'id'        => 'wc_like2discount_banner_img_loopview_style',
				'type'      => 'textarea',
				'default'   => $options['banner_img_loopview_style'],
				'css'       => 'min-width:400px; min-height:180px;',
				'desc_tip'  => 'CSS styling only'
			),

			array(
				'title'     => __('Show on "Cart" page', 'woocommerce-like2discount'),
				'desc'      => 'The same banner will be also shown on cart page.',
				'id'        => 'wc_like2discount_banner_img_loopview_showoncart',
				'type'      => 'checkbox',
				'default'   => $options['banner_img_loopview_showoncart'],
				'desc_tip'  => false
			),

			array( 'type' => 'separator' ),

			array(
				'title'     => __('Side banner', 'woocommerce-like2discount'),
				'desc'      => '<a href="#wc_like2discount_banner_img_side" class="open-media">Select an image from gallery</a> <p class="description" style="font-size: 11px;">Sticky banner on the left or right side. Image only!</p>',
				'id'        => 'wc_like2discount_banner_img_side',
				'type'      => 'text',
				'css'       => 'min-width:350px;',
				'default'   => $options['banner_img_side'],
				'desc_tip'	=> 'Select image or add some text.'
			),

			array(
				'title'     => __('Banner position', 'woocommerce-like2discount'),
				'desc'      => '<p class="description" style="font-size: 11px;">This option is related to <strong>Side banner</strong></p>',
				'id'        => 'wc_like2discount_banner_img_side_position',
				'class'     => 'chosen_select',
				'default'   => $options['banner_img_side_position'],
				'type'      => 'select',
				'options'   => array(
					'hide'             => __('Do not show this banner', 'woocommerce-like2discount'),
					'left'      => __('Left side', 'woocommerce-like2discount'),
					'right'     => __('Right side', 'woocommerce-like2discount'),
				),
				'desc_tip'	  => __('Select the position of image banner to show.', 'woocommerce-like2discount'),
			),

			array( 'type' => 'sectionend', 'id' => 'wc_like2discount_section_end'),



			// ! Email Template
			array('name' => __('Email Template', 'woocommerce-like2discount'), 'desc' => __('Edit email templates for Like 2 Discount plugin.', 'woocommerce-like2discount'), 'type' => 'title'),

			array(
				'title'     => __('Confirmation email', 'woocommerce-like2discount'),
				'desc'      => 'Confirmation email text when user is required to verify email and activate coupon. This text is NOT HTML formatted.',
				'id'        => 'wc_like2discount_verify_email_template',
				'type'      => 'textarea',
				'default'   => $options['verify_email_template'],
				'css'       => 'min-width:600px; min-height:230px;',
				'desc_tip'  => true
			),

			array( 'type' => 'sectionend', 'id' => 'wc_like2discount_section_end'),
		);

		return apply_filters( 'wc_like2discount_settings', $settings );
	}

	public static function settings_start()
	{
		if(isset($_GET['tab']) && $_GET['tab'] == 'like2discount')
		{
			wp_enqueue_media();
?>
<style>
	.chosen_products_list,
	.chosen_product_categories_list {
		min-height: 120px;
	}
	
	.chosen_products_list.select2-container,
	.chosen_product_categories_list.select2-container {
		min-height: 0;
	}
</style>
<script type="text/javascript">
jQuery(document).ready(function() {
	
	
	var $tabs_and_titles = jQuery("#mainform .form-table, #mainform h3, #mainform h2, #mainform h3 + p, #mainform h2 + p");

	$tabs_and_titles.show();
	
	jQuery( window ).scrollTop( 0 ); // Reset scroll
	
	jQuery( window ).load( function() {
		jQuery( window ).scrollTop( 0 ); // Reset scroll
	} );

	jQuery(".chosen_products_list").width(345).attr('data-placeholder', '<?php _e("Enter product names", 'woocommerce-like2discount'); ?>').select2();
	jQuery(".chosen_product_categories_list").width(345).attr('data-placeholder', '<?php _e("Enter product category names", 'woocommerce-like2discount'); ?>').select2();

	$tabs_and_titles.hide();

	// Create Tabs
	var $headings = jQuery("#mainform h3, #mainform h2"),
		$subsub = jQuery('<div class="subsubsub"></div>');
	

	$headings.each(function(i, el)
	{
		var $h3 = jQuery(el),
			$p = $h3.next(),
			$form_table = $h3.next().next(),
			id = 'tab-' + $h3.text().toLowerCase().replace(/\s+/g, '-');

		$subsub.append( '<li><a '+(i == 0 ? ' class="current"' : '')+' href="#'+id+'">' + (i > 0 ? ' | ' : '') + $h3.html() + '</a></li>' );

		$h3.attr('id', id + '-title');
		$p.attr('id', id + '-subtitle');
		$form_table.attr('id', id);

		if(i == 0)
		{
			$h3.show();
			$p.show(),
			$form_table.show();
		}
	});

	jQuery(".woo-nav-tab-wrapper").after( $subsub );
	$subsub.after( '<br class="clear">' );

	// Click Events
	$subsub.on('click', 'a', function(ev)
	{
		ev.preventDefault();

		var id = jQuery(this).attr('href');

		$subsub.find('a').removeClass('current');
		jQuery(this).addClass('current');

		$tabs_and_titles.hide();

		jQuery(id).add(id + '-title').add(id + '-subtitle').fadeIn('fast');

		var stop = jQuery(window).scrollTop();

		window.location.hash = id.replace('#', '');

		jQuery(window).scrollTop( stop );
	});

	var id;
	if(id = window.location.hash.toString())
	{
		$subsub.find('a').removeClass('current').filter('[href="'+id+'"]').addClass('current');

		$tabs_and_titles.hide();
		jQuery(id).add(id + '-title').add(id + '-subtitle').show();
	}


	// Media Opener
	var media, opening_id;

		media = {};

	_.extend( media, {
		view: {},
		controller: {}
	} );

	media.buttonID	= '.open-media',

	_.extend( media, {
		frame: function()
		{
			if ( this._frame )
				return this._frame;

			var states = [
				new wp.media.controller.Library(),

				new wp.media.controller.Library( {
					id:				 "gallery-finder",
					title:			  "Select an Image",
					priority:		   30,
					searchable:		 true,
					library:			wp.media.query( { type: "image" } ),
					multiple:		   false
					, contentUserSetting: true
				} ),
			];

			this._frame = wp.media( {states: states} );
			this._frame.state( 'library' ).on( 'select', this.select );
			this._frame.state( "gallery-finder" ).on( 'select', this.select );

			return this._frame;
		},

		select: function()
		{
			var selection = this.get( 'selection' ),
				attachment = selection.first().toJSON();

			jQuery(opening_id).val( attachment.url );
		},

		init: function()
		{
			jQuery( media.buttonID ).on( 'click.media_frame_open', function( e ) {
				e.preventDefault();

				opening_id = jQuery(this).attr('href');

				media.frame().open();
			} );
		}
	} );

	jQuery( media.init );
});
</script>

<style>
	/*
	#mainform h3, #mainform h3 + p, #mainform .form-table {
		display: none;
	}
	*/

	#mainform .open-media {
		text-decoration: none;
	}

	.woocommerce .chosen-container {
		min-width: 350px !important;
	}
</style>
<?php

			// vpcf
			include_once('form-vpc.php');
		}
	}
}