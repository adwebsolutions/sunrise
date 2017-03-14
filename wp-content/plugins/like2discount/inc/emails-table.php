<?php
/**
 *	Registered Emails
 *	
 *	Laborator.co
 *	www.laborator.co 
 */

$emails_table = new L2D_Emails_Table();
$emails_table->prepare_items();

?>
<style>
    .column-verified, .column-action {
        width: 100px;
    }
    
    .column-ip {
        width: 150px;
    }
    
    .column-register_date {
        width: 280px;
    }
    
    .column-email {
        white-space: nowrap;
    }
    
    .actions.bulkactions, .tablenav.bottom {
		display: none;
	}
	
	.csv-download {
		background: url(<?php echo plugins_url("like2discount/assets/img/csv@2x.png"); ?>) no-repeat left center;
		background-size: 16px;
		-moz-background-size: 16px;
		text-decoration: none;
		color: #555;
		padding-left: 20px;
		float: right;
		display: block;
		margin-top: 20px;
	}
</style>

<div class="wrap">
	<h2><?php _e('Like 2 Discount &ndash; Emails List', 'woocommerce-like2discount'); ?></h2>
	
	<form method="get">
	
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />

        <?php echo $emails_table->display(); ?>
        
       
        
        <?php if($emails_table->data_count): ?>
		<a href="?page=<?php echo $_REQUEST['page']; ?>&filter_type=<?php echo isset($_GET['filter_type']) ? $_GET['filter_type'] : ''; ?>&to=csv" class="csv-download"><?php _e('Download list as CSV file', 'woocommerce-like2discount'); ?></a>
        <?php endif; ?>
        
        <p>&copy; <?php echo date('Y'); ?> Plugin created by <a href="http://www.laborator.co" target="_blank">Laborator</a></p>
    </form>
</div>