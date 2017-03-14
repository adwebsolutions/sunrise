<?php
/**
 *	Submitting email and coupon code, send verification
 *
 *	Laborator.co
 *	www.laborator.co
 */


$resp = array(
	'success'  => false,
	'errmsg'   => ""
);

$email  = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
$coupon = isset($_REQUEST['requestedCoupon']) ? $_REQUEST['requestedCoupon'] : '';

// Rules
$options = l2d_get_options();

// Added in v1.1
$email_confirmation = $options['email_confirmation'] == 'yes';

if($coupon && (is_email($email) || $email_confirmation == false))
{
	$has_errors = false;

	if($email && $options['coupons_per_email'])
	{
		$email_coupons = l2d_get_coupons_by_email($email);

		if(count($email_coupons) >= $options['coupons_per_email'])
		{
			$has_errors = true;
			$resp['errmsg'] = __('This email has already reached the limit of granted coupons!', 'woocommerce-like2discount');
		}
	}

	if( ! $has_errors)
	{
		$resp['success'] = true;
		$resp['message'] = __( 'You are one step away to get the coupon, we have sent you a confirmation email, please check your inbox.', 'woocommerce-like2discount' );

		// Register email for verification
		$verify_hash = l2d_register_email_coupon($email, $coupon);

		// Send confirmation
		if($email_confirmation)
		{
			$email_subject = __('Confirm your coupon code and get the discount.', 'woocommerce-like2discount');
			$email_body = l2d_verify_email_template($email, $coupon, $verify_hash);

			wp_mail($email, $email_subject, $email_body);
		}
	}
}

die( json_encode($resp) );