<?php  if ( ! defined('ETHEME_FW')) exit('No direct script access allowed');

// **********************************************************************// 
// ! Add admin styles and scripts
// **********************************************************************// 

if(!function_exists('etheme_load_admin_styles')) {
	add_action( 'admin_enqueue_scripts', 'etheme_load_admin_styles', 150 );
	function etheme_load_admin_styles() {
		global $pagenow;
		
	    wp_enqueue_style('farbtastic');
	    $depends = '';
	    if(class_exists('Redux') && $pagenow == 'admin.php' && @$_GET['page'] == '_options') {
	    	$depends = array('redux-admin-css', 'select2-css');
	    	wp_dequeue_style( 'woocommerce_admin_styles' );
	    }
	    wp_enqueue_style('etheme_admin_css', ETHEME_CODE_CSS.'admin.css', $depends);
	    wp_enqueue_style("font-awesome", get_template_directory_uri().'/css/font-awesome.min.css');
	}
}

if(!function_exists('etheme_add_admin_script')) {
	add_action('admin_init','etheme_add_admin_script', 1130);
	function etheme_add_admin_script(){
		global $pagenow;
	    add_thickbox();

		$depends = array();
		if( $pagenow == 'widgets.php' ) {
			$depends = array();
		}
	    wp_enqueue_script('theme-preview');
	    wp_enqueue_script('common');
	    wp_enqueue_script('wp-lists');
	    wp_enqueue_script('postbox');
	    wp_enqueue_script('farbtastic');
	    //wp_enqueue_script('et_masonry', get_template_directory_uri().'/js/jquery.masonry.min.js',array(),false,true);
	    wp_enqueue_script('etheme_admin_js', ETHEME_CODE_JS.'admin.js', $depends, false,true);
	}
}


if(!function_exists('etheme_rate_redirect')) {
	add_action( 'init', 'etheme_rate_redirect');
	function etheme_rate_redirect() {
		if( isset( $_GET['page'] ) && $_GET['page'] === '_et_open_support' && false === headers_sent() ) {
			wp_redirect( ETHEME_SUPPORT_LINK );
			exit;
		}
		if( isset( $_GET['page'] ) && $_GET['page'] === '_et_rate_theme' && false === headers_sent() ) {
			wp_redirect( ETHEME_RATE_LINK );
			exit;
		}
		if( isset( $_GET['page'] ) && $_GET['page'] === '_et_open_documentation' && false === headers_sent() ) {
			wp_redirect( ETHEME_DOCS_LINK );
			exit;
		}
	}
}

if(!function_exists('etheme_support_chat')) {
	function etheme_support_chat() {
		if( ! etheme_get_option('support_chat') ) return;
		?>
		<script>
			window.intercomSettings = {
			app_id: 't84fcdk1',
		};
		</script>
		<script data-cfasync="false">(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/t84fcdk1';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()
		</script>
		<?php
	}
}