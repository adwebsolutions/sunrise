<?php
/**
 *	Loop View Banner
 *
 *	Laborator.co
 *	www.laborator.co
 */

if(isset($_COOKIE['l2d_coupon_gained']) && $_COOKIE['l2d_coupon_gained'])
	return;

$options = l2d_get_options();

$is_image = preg_match("/\.[a-z0-9]+$/i", $options['banner_img_loopview']);

if(isset($is_shortcode))
	$options['banner_img_loopview_position'] = 'shortcode';

$is_after = defined("L2D_LVB_BEFORE_SHOWN");

if($is_image):

	?>
	<div class="l2d-loop-banner l2d-loop-banner-type-image l2d-position-<?php echo $options['banner_img_loopview_position']; echo $is_after ? ' is-after' : ''; ?>">
		<a href="#" class="l2d-show-modal">
			<img src="<?php echo $options['banner_img_loopview']; ?>" class="img-responsive" />
		</a>
	</div>
	<?php

else:

	// Textual Banner

	?>
	<div class="l2d-loop-banner l2d-loop-banner-type-text l2d-position-<?php echo $options['banner_img_loopview_position']; echo $is_after ? ' is-after' : ''; ?>">
		<a href="#" class="l2d-show-modal"><?php echo $options['banner_img_loopview']; ?></a>
	</div>
	<?php

endif;

if(trim($options['banner_img_loopview_style']) && ! defined("L2D_LVB_SHOWN")):
?>
<style>
	<?php echo str_replace(PHP_EOL, ' ', $options['banner_img_loopview_style']); ?>
</style>
<?php
endif;