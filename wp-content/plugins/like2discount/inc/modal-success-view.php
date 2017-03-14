<?php
/**
 *	Like2Discount Success Modal
 *	
 *	Laborator.co
 *	www.laborator.co 
 */

$coupon = l2d_get_options('emails_coupons');

if( ! is_array($coupon) || ! isset($coupon[L2D_COUPON_REGISTERED]))
	return;

$coupon = $coupon[L2D_COUPON_REGISTERED];
$coupon_obj = l2d_get_coupon_obj($coupon['coupon']);

$expiry_date = $coupon_obj->expiry_date;
$expiry_days = 0;

if($expiry_date)
{
	$expiry_days = ceil( ($expiry_date - time()) / 86400 );
}


?>
<div class="l2d-overlay"></div>
<div class="l2d-body is-success">
	<a href="#" class="close">&times;</a>
	
	<header>
		<h2><?php _e('Your coupon is now active!', 'woocommerce-like2discount'); ?></h2>
	</header>
	
	
	<div class="description">
		
		<p><?php _e('Thank you for verifying your email, now your coupon is active and ready to use. You can copy and save it or apply immediately to your cart by clicking the below button <strong>Apply Coupon Now</strong>.', 'woocommerce-like2discount'); ?></p>

		<?php if($expiry_days > 0): ?>
		<p><?php echo sprintf(__('Note: This coupon is available for <strong>%s</strong> only. It will expire on <strong>%s</strong>.', 'woocommerce-like2discount'), sprintf(_n('1 day', '%d days', $expiry_days, 'woocommerce-like2discount'), $expiry_days), date_i18n(get_option('date_format') . ' - ' . get_option('time_format'), $expiry_date)); ?></p>
		<?php endif; ?>
		
	</div>
	
	<footer>
		
		<div class="get-coupon-description<?php echo WC()->cart->get_cart_contents_count() == 0 ? ' large-box' : ''; ?>">
			<?php _e('Copy and save it', 'woocommerce-like2discount'); ?>
			<span><?php echo $coupon['coupon']; ?></span>
		</div>
		
		<?php if( WC()->cart->get_cart_contents_count() > 0 ): ?>
		<div class="coupon-code">
			
			<a href="<?php echo site_url("?l2d-apply-coupon={$coupon['coupon']}"); ?>" class="apply-coupon-now"><?php _e('Apply Coupon Now', 'woocommerce-like2discount'); ?></a>
			
		</div>
		<?php endif; ?>
		
	</footer>
	
</div>