<?php
/**
 *	Side View Banner
 *
 *	Laborator.co
 *	www.laborator.co
 */

if(isset($_COOKIE['l2d_coupon_gained']) && $_COOKIE['l2d_coupon_gained'])
	return;

$options = l2d_get_options();

?>
<div class="l2d-side-banner l2d-position-<?php echo $options['banner_img_side_position']; ?>">
	<a href="#" class="l2d-show-modal">
		<img src="<?php echo $options['banner_img_side']; ?>" class="img-responsive" />
	</a>
</div>