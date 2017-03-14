<?php
/**
 *	Request New Coupon Code
 *	
 *	Laborator.co
 *	www.laborator.co 
 */

$resp = l2d_generate_random_string();

// Two attempts to retrieve unique coupon, no recursion needed (chance to be the same two coupons is 1:10000000000)
if(l2d_get_coupon_obj($resp))
	$resp = l2d_generate_random_string();

die( json_encode($resp) );