<?php  if ( ! defined('ETHEME_FW')) exit('No direct script access allowed');

// **********************************************************************// 
// ! Menu
// **********************************************************************// 

if ( ! function_exists( 'etheme_menu_shortcode' ) ) {
	function etheme_menu_shortcode($atts) {
		extract(shortcode_atts(array(
			'title' => '',
			'menu'  => '',
			'style' => '',
			'align' => '',
			'class' => '',
		), $atts));

		$output ='';

		$class = ( ! empty( $class ) ) ? $class . ' ' : '';
		$class .= $style;
		$class .= ' menu-align-' . $align;

		$output .='<div class="menu-element ' . $class . '">';

			if ( ! empty( $title ) ) {
				$output .= '<h5>' . $title . '</h5>';
			}

			ob_start();
				wp_nav_menu(array(
					'menu' => $menu,
					'before' => '',
					'container_class' => '',
					'after' => '',
					'link_before' => '',
					'link_after' => '',
					'depth' => 100,
					'fallback_cb' => false,
					'walker' => new ETheme_Navigation
				));
				$output .= ob_get_contents();
			ob_end_clean();

		$output .='</div>';

		return $output;

	}
}


// **********************************************************************//
// ! Register New Element: Menu
// **********************************************************************//
add_action( 'init', 'etheme_register_menu' );
if( ! function_exists( 'etheme_register_menu' ) ) {
	function etheme_register_menu() {
		if( ! function_exists( 'vc_map' ) ) return;
		$menus = wp_get_nav_menus();
		$menu_params ='';
		foreach ($menus as $menu) {
			$menu_params[$menu->name] = $menu->term_id;
		}

		$params = array(
			array(
				"type" => "textfield",
				"heading" => esc_html__( "Title", 'xstore' ),
				"param_name" => "title"
			),
			array(
				"type" => "dropdown",
				"heading" => esc_html__( "Menu", 'xstore' ),
				"param_name" => "menu",
				"value" => $menu_params
			),
			array(
				"type" => "dropdown",
				"heading" => esc_html__( "Style", 'xstore' ),
				"param_name" => "style",
				"value" => array(
					esc_html__( "Vertical", 'xstore' ) => "vertical",
					esc_html__( "Horizontal", 'xstore' ) => "horizontal",
				)
			),
			array(
				"type" => "dropdown",
				"heading" => esc_html__( "Align", 'xstore' ),
				"param_name" => "align",
				"value" => array(
					esc_html__( "Left", 'xstore' ) => "left",
					esc_html__( "Center", 'xstore' ) => "center",
					esc_html__( "Right", 'xstore' ) => "right",
				)
			),
			array(
				"type" => "textfield",
				"heading" => esc_html__( "Extra class name", 'xstore' ),
				"param_name" => "class"
			),
		);
		$banner_params = array(
			'name' => '[8THEME] Menu',
			'base' => 'menu',
			'icon' => ETHEME_CODE_IMAGES . 'vc/el-menu.png',
			'category' => 'Eight Theme',
			'params' => $params
		);

		vc_map( $banner_params );
	}
}
