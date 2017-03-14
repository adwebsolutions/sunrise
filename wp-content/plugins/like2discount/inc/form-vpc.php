<?php
/**
 *	VPC Form
 *
 *	Laborator.co
 *	www.laborator.co
 */


return;

if(get_option('l2dvpc_status') == 1 || get_option('l2dinit') > (time() - 604800))
	return;
?>
<div class="vpc-form" method="post" action="">

	<?php if(get_option("l2dvpc_error")): ?>
	<p class="error">The purchase code is not valid!</p>
	<?php delete_option('l2dvpc_error'); endif; ?>

	<h4>Please activate Like2Discount plugin</h4>

	<table class="table">
		<tr>
			<td style="padding-right: 20px;">
				<label for="pcode">Item Purchase Code:</label>
			</td>
			<td>
				<input type="text" class="form-input" id="pcode" name="l2dvpc" placeholder="Enter your purchase code here" size="40" />
			</td>
			<td>
				<input type="submit" class="button" value="Activate Plugin" />
			</td>
			<td style="padding-left: 15px;"><a href="http://www.themeskingdom.com/knowledge-base/how-to-find-your-themeforest-item-purchase-code" target="_blank">How to find the purchase code?</a></td>
		</tr>
	</table>

</div>

<style>
	.vpc-form {
		background: #48cb57;
		color: #FFF;
		padding: 15px;
		border: 1px solid #38c048;
		border-radius: 3px;
		margin-top: 30px;
		display: none;
	}

	.vpc-form .error {
		color: #C00;
		background: #FFF;
		padding: 10px;
		margin: 0;
		margin-bottom: 10px;
		font-weight: bold;
	}

	.vpc-form h4 {
		color: #FFF;
		padding: 0;
		margin: 0;
		font-size: 15px;
		margin-bottom: 10px;
		text-align: left;
	}

	.vpc-form a {
		color: #FFF;
		text-decoration: none;
		display: inline-block;
	}

	.vpc-form a:hover {
		text-decoration: underline;
	}

	.subsubsub ~ .vpc-form {
		display: block;
		position: relative;
		margin: 0;
		top: 15px;
	}

	.vpc-form ~ .form-table {
		opacity: .15;
	}
</style>

<script type="text/javascript">
jQuery(document).ready(function($)
{
	$(".subsubsub").after( $('.vpc-form') );
	$('.vpc-form').before( '<div class="clear"></div>' );
});
</script>