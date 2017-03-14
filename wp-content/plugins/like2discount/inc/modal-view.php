<?php
/**
 *	Like2Discount Apply Modal
 *
 *	Laborator.co
 *	www.laborator.co
 */

$options            = l2d_get_options();

$display_type       = $options['display_type'];

$fb_app_id          = $options['fb_app_id'];
$fb_page_url        = $options['fb_page_url'];

$modal_title        = __($options['coupon_title'], 'woocommerce-like2discount');
$modal_description  = __($options['coupon_description'], 'woocommerce-like2discount');

$email_confirmation = $options['email_confirmation'] == 'yes';
$allow_share        = $options['allow_share'] == 'yes';

$button_type_class = 'fb-like';

if ( $display_type == 'share' ) {
	$button_type_class = 'fb-share-button';
} else if ( $display_type == 'send' ) {
	$button_type_class = 'fb-send';
}

?>
<?php if ( apply_filters( 'like2discount_include_facebook_sdk', true ) ) : ?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6&appId=<?php echo $fb_app_id; ?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<script>
var ajax_url = ajax_url || '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php endif; ?>


<div class="l2d-overlay"></div>
<div class="l2d-body" data-enter="<?php echo $options['entrance_class']; ?>" data-exit="<?php echo $options['exit_class']; ?>" data-require-confirmation="<?php echo $email_confirmation ? 1 : 0; ?>">
	<a href="#" class="close">&times;</a>

	<header>
		<h2><?php echo $modal_title; ?></h2>
	</header>

	<div class="like-and-email l2d-display-<?php echo $display_type; ?>">

		<div class="le-entry like-btn<?php if($email_confirmation == false) echo ' like-only'; if($allow_share) echo ' allow-share'; ?>">
			<span class="step"><?php echo $email_confirmation ? 1 : '&nbsp;'; ?></span>

			<div class="like-box" data-likemsg="<?php echo esc_attr( $display_type == 'send' ? __( 'You must click "Send" button and select recipients first!', 'woocommerce-like2discount' ) : __( 'You must like our page first! Please click "Like"', 'woocommerce-like2discount' ) ); ?>" data-enter-email="<?php echo esc_attr( __('Now enter your email address to activate the coupon code.', 'woocommerce-like2discount') ); ?>" data-coupon-confirmed="<?php echo esc_attr( __( 'Thank you for liking our page, now your coupon is active and ready to use. Enjoy it!', 'woocommerce-like2discount' ) ); ?>">
				<div class="<?php echo $button_type_class; ?>" data-href="<?php echo esc_attr($fb_page_url); ?>" data-layout="box_count" data-action="like" data-show-faces="false" data-share="false"></div>
			</div>
		</div>

		<?php if($email_confirmation): ?>
		<div class="le-entry email-btn">
			<span class="step">2</span>

			<div class="email-box">
				<input type="text" name="email" placeholder="<?php _e('Your email...', 'woocommerce-like2discount'); ?>" autocomplete="off" data-invalid="<?php echo esc_attr( __('Please enter a valid email!', 'woocommerce-like2discount') ); ?>" />
				
				<button class="email-submit" type="button"><?php echo _x('Submit', 'submit email in l2d dialog', 'woocommerce-like2discount'); ?></button>

				<!-- Loader -->
				<div class="l2d-spinner">
					<div class="l2d-spinner-container container1">
						<div class="circle1"></div>
						<div class="circle2"></div>
						<div class="circle3"></div>
					  <div class="circle4"></div>
					</div>
					<div class="l2d-spinner-container container2">
						<div class="circle1"></div>
						<div class="circle2"></div>
						<div class="circle3"></div>
						<div class="circle4"></div>
					</div>
					<div class="l2d-spinner-container container3">
						<div class="circle1"></div>
						<div class="circle2"></div>
						<div class="circle3"></div>
						<div class="circle4"></div>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>

	</div>

	<!-- notifications -->
	<div class="errors-container"></div>
	<div class="successmsg-container"></div>

	<div class="description">

		<?php echo wpautop($modal_description); ?>

		<?php if($options['products_ids']): ?>

			<?php
			$products_list = array();

			$pl_query = new WP_Query( array('post_type' => 'product', 'post__in' => explode(',', $options['products_ids'])) );

			while($pl_query->have_posts())
			{
				$pl_query->the_post();
				$products_list[] = '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
			}

			?>

			<p>
				<?php echo sprintf( __('Included products: %s', 'woocommerce-like2discount'), implode(', ', $products_list) ); ?>
			</p>

		<?php endif; ?>

		<?php if($options['category_ids']): ?>

			<?php
			$product_category_list = array();
			$pcl_terms = get_terms('product_cat');

			foreach($pcl_terms as $term)
			{
				if(in_array($term->term_id, explode(',', $options['category_ids'])))
				{
					$product_category_list[] = '<a href="' . get_term_link($term, 'product_cat') . '">' . $term->name . '</a>';
				}
			}

			?>

			<p>
				<?php echo sprintf( __('Included categories: %s', 'woocommerce-like2discount'), implode(', ', $product_category_list) ); ?>
			</p>

		<?php endif; ?>

	</div>

	<footer>

		<div class="get-coupon-description">
			<?php _e('Like our page and', 'woocommerce-like2discount'); ?>
			<span><?php _e('Get discount coupon', 'woocommerce-like2discount'); ?></span>
		</div>

		<div class="coupon-code">

			<div class="cc-wrapper closed">
				<input type="text" value="XXX-X-XX" readonly="true" />
				<div class="closed-text"><?php _e('Please complete the first<br /> step to generate the coupon.', 'woocommerce-like2discount'); ?></div>
			</div>

		</div>

	</footer>

</div>