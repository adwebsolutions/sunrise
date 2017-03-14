<?php
/**
 *	Single Item View Banner
 *
 *	Laborator.co
 *	www.laborator.co
 */

if(isset($_COOKIE['l2d_coupon_gained']) && $_COOKIE['l2d_coupon_gained'])
	return;

$options = l2d_get_options();

$is_image = preg_match("/\.[a-z0-9]+$/i", $options['banner_img_singleview']);

if(isset($is_shortcode))
	$options['banner_img_singleview_position'] = 'shortcode';

if($is_image):

	?>
	<div class="l2d-single-banner l2d-single-banner-type-image l2d-position-<?php echo $options['banner_img_singleview_position']; ?>">
		<a href="#" class="l2d-show-modal">
			<img src="<?php echo $options['banner_img_singleview']; ?>" class="img-responsive" />
		</a>
	</div>
	<?php

else:

	// Textual Banner

	?>
	<div class="l2d-single-banner l2d-single-banner-type-text l2d-position-<?php echo $options['banner_img_singleview_position']; ?>">
		<a href="#" class="l2d-show-modal"><?php echo $options['banner_img_singleview']; ?></a>
	</div>
	<?php

endif;

if(trim($options['banner_img_singleview_style'])):
?>
<style>
	<?php echo str_replace(PHP_EOL, ' ', $options['banner_img_singleview_style']); ?>
</style>
<?php
endif;