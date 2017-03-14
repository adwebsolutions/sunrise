<?php
/**
 *	Emails Table Class
 *	
 *	Laborator.co
 *	www.laborator.co 
 */


if(!class_exists('WP_List_Table'))
{
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class L2D_Emails_Table extends WP_List_Table 
{
	public $data_count;
			
	function __construct()
	{
		global $status, $page;
				
		parent::__construct( array(
			'singular'  => 'l2d-email',
			'plural'	=> 'l2d-emails',
			'ajax'	  => false
		) );
	}
	
	
	function column_default($item, $column_name)
	{
		if($column_name == 'register_date')
		{
			return date_i18n(get_option('date_format') . ' - ' . get_option('time_format'), $item['timestamp']) . ' ('.human_time_diff($item['timestamp'], time()).' ago)';
		}
		elseif($column_name == 'ip')
		{
			if(isset($item['ip']))
			{
				return '<a href="http://www.iplocation.net/index.php?query='.$item['ip'].'" target="_blank">'.$item['ip'].'</a>';
			}
			else
			{
				return 'N/A';
			}
		}
		elseif($column_name == 'verified')
		{
			return $item['verified'] ? 'Yes' : 'No';
		}
		elseif($column_name == 'action')
		{
			$filter_type = isset($_REQUEST['filter_type']) ? $_REQUEST['filter_type'] : '';
			return '<a onclick="return confirm(\'Are you sure you want to delete it?\');" href="'.sprintf('?page=%s&action=%s&email=%s&hash=%s&filter_type=%s', $_REQUEST['page'], 'delete', $item['email'], $item['verify_hash'], $filter_type).'"><font color="#c00">Delete</font></a>';
		}
		
		return $item[$column_name];
	}
	
	
	function get_columns()
	{
		$columns = array(
			'email'          => 'Email',
			'coupon'         => 'Coupon Code',
			'verified'       => 'Verified',
			'ip'         	 => 'IP Address',
			'register_date'  => 'Register Date',
			'action'  		 => 'Action'
		);
		
		return $columns;
	}
	
	
	function get_sortable_columns()
	{
		$sortable_columns = array(
			'email'          => array('email',false),
			'verified'  	 => array('verified',false),
			'register_date'  => array('timestamp',true),
			'coupon'         => array('coupon',false)
		);
		
		return $sortable_columns;
	}
	
	
	function get_bulk_actions()
	{
		$actions = array();
		
		return $actions;
	}
	

	function prepare_items() 
	{
		global $wpdb;
		
		// Delete Item
		if(isset($_GET['action']) && $_GET['action'] == 'delete')
		{
			$email = $_GET['email'];
			$hash = $_GET['hash'];
			
			l2d_delete_email($email, $hash);
		}

		$per_page = 50;		
		
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();
		
		
		$this->_column_headers = array($columns, $hidden, $sortable);
		
		$filter_type = isset($_REQUEST['filter_type']) ? $_REQUEST['filter_type'] : '';
		$data = l2d_get_all_emails($filter_type);
		
		$this->data_count = count($data);
			
			
		function usort_reorder($a,$b)
		{
			$orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'timestamp';
			$order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc';
			$result = strcmp($a[$orderby], $b[$orderby]);
			
			return ($order==='asc') ? $result : -$result;
		}
		
		usort($data, 'usort_reorder');
		
		
		$current_page = $this->get_pagenum();
		$total_items  = count($data);
		$data         = array_slice($data,(($current_page-1)*$per_page),$per_page);
		
		$this->items  = $data;
		
		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'	=> $per_page,
			'total_pages' => ceil($total_items/$per_page)
		) );
	}
	
	
	public function extra_tablenav($which) 
	{
		if($which == 'top'):
			
			$filter_type = isset($_REQUEST['filter_type']) ? $_REQUEST['filter_type'] : '';
			
			?>
			<select name="filter_type">
				<option value=""><?php _e( 'Show all coupons and emails', 'woocommerce-like2discount' ); ?></option>
				<option value="verified"<?php echo selected('verified', $filter_type); ?>><?php _e( 'Verified Only', 'woocommerce-like2discount' ); ?></option>
				<option value="not_verified"<?php echo selected('not_verified', $filter_type); ?>><?php _e( 'Not Verified', 'woocommerce-like2discount' ); ?></option>
			</select>
			
			<input type="submit" name="" id="post-query-submit" class="button" value="Filter">
			<?php
			
		endif;
	}
}
