<?php  if ( ! defined('ETHEME_FW')) exit('No direct script access allowed');

if( ! function_exists('vc_remove_element')) return;

add_action( 'init', 'etheme_VC_setup');

if(!function_exists('etheme_VC_setup')) {
	function etheme_VC_setup() {
		vc_remove_element("vc_tour");
	}
}

if( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) ) {
	add_filter(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'etheme_vc_custom_css_class', 10, 3);
	if( ! function_exists('etheme_vc_custom_css_class') ) {
		function etheme_vc_custom_css_class( $classes, $base, $atts ) {
			if( ! empty( $atts['fixed_background'] ) ) {
				$classes .= ' et-parallax-' . $atts['fixed_background'];
			}
			if( ! empty( $atts['off_center'] ) ) {
				$classes .= ' off-center-' . $atts['off_center'];
			}
			return $classes;
		}
	}
}

// **********************************************************************//
// ! Add new option to vc_column
// **********************************************************************//
add_action( 'init', 'etheme_columns_options');
if(!function_exists('etheme_columns_options')) {
	function etheme_columns_options() {
		if(!function_exists('vc_map')) return;
		vc_add_param('vc_column', array(
			"type" => "dropdown",
			"heading" => esc_html__("Fixed background position", 'xstore'),
			"param_name" => "fixed_background",
			"group" => esc_html__('Design Options', 'xstore'),
			"edit_field_class" => 'vc_col-sm-5 vc_column',
			"value" => array(
				'' => '',
				__("Left center", 'xstore') => 'left',
				__("Right center", 'xstore') => 'right',
				__("Center center", 'xstore') => 'center',
			)
		));

		vc_add_param('vc_column', array(
			"type" => "dropdown",
			"heading" => esc_html__("Off center", 'xstore'),
			"param_name" => "off_center",
			"value" => array(
				'' => '',
				__("Left", 'xstore') => 'left',
				__("Right", 'xstore') => 'right',
			)
		));

		vc_add_param('vc_row', array(
			"type" => "dropdown",
			"heading" => esc_html__("Fixed background position", 'xstore'),
			"param_name" => "fixed_background",
			"group" => esc_html__('Design Options', 'xstore'),
			"edit_field_class" => 'vc_col-sm-5 vc_column',
			"value" => array(
				'' => '',
				__("Left center", 'xstore') => 'left',
				__("Right center", 'xstore') => 'right',
				__("Center center", 'xstore') => 'center',
			)
		));
	}
}


if( ! function_exists( 'etheme_get_slider_params' ) ) {
	function etheme_get_slider_params() {
		return array(
			array(
				"type" => "textfield",
				"heading" => esc_html__("Slider speed", 'xstore'),
				"param_name" => "slider_speed",
				"group" => esc_html__('Slider settings', 'xstore')
			),
			array(
				"type" => "checkbox",
				"heading" => esc_html__("Slider autoplay", 'xstore'),
				"param_name" => "slider_autoplay",
				"group" => esc_html__('Slider settings', 'xstore'),
				'value' => array( esc_html__( 'Yes, please', 'xstore' ) => 'yes' )

			),
			array(
				"type" => "checkbox",
				"heading" => esc_html__("Hide prev/next buttons", 'xstore'),
				"param_name" => "hide_buttons",
				"group" => esc_html__('Slider settings', 'xstore'),
				'value' => array( esc_html__( 'Yes, please', 'xstore' ) => 'yes' )

			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Pagination type', 'xstore' ),
				'param_name' => 'pagination_type',
				'group' => esc_html__('Slider settings', 'xstore'),
				'value' => array(
					__( 'Hide', 'xstore' ) => 'hide',
					__( 'Bullets', 'xstore' ) => 'bullets',
					__( 'Lines', 'xstore' ) => 'lines',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Hide pagination only for', 'xstore' ),
				'param_name' => 'hide_fo',
				'dependency' => array(
					'element' => 'pagination_type',
					'value' => array( 'bullets', 'lines' ),
				),
				'group' => esc_html__('Slider settings', 'xstore'),
				'value' => array(
					__( '', '' ) => '',
					__( 'Mobile', 'xstore' ) => 'mobile',
					__( 'Desktop', 'xstore' ) => 'desktop',
				),
			),
			array(
				"type" => "colorpicker",
				"heading" => __( "Pagination default color", "xstore" ),
				"param_name" => "default_color",
				'dependency' => array(
					'element' => 'pagination_type',
					'value' => array( 'bullets', 'lines' ),
				),
				"group" => esc_html__('Slider settings', 'xstore'),
				"value" => '#e6e6e6',
			),
			array(
				"type" => "colorpicker",
				"heading" => __( "Pagination active color", "xstore" ),
				"param_name" => "active_color",
				'dependency' => array(
					'element' => 'pagination_type',
					'value' => array( 'bullets', 'lines' ),
				),
				"group" => esc_html__('Slider settings', 'xstore'),
				"value" => '#b3a089',
			),
			array(
				"type" => "textfield",
				"heading" => esc_html__("Number of slides on large screens", 'xstore'),
				"param_name" => "large",
				"group" => esc_html__('Slider settings', 'xstore')
			),
			array(
				"type" => "textfield",
				"heading" => esc_html__("On notebooks", 'xstore'),
				"param_name" => "notebook",
				"group" => esc_html__('Slider settings', 'xstore')
			),
			array(
				"type" => "textfield",
				"heading" => esc_html__("On tablet landscape", 'xstore'),
				"param_name" => "tablet_land",
				"group" => esc_html__('Slider settings', 'xstore')
			),
			array(
				"type" => "textfield",
				"heading" => esc_html__("On tablet portrait", 'xstore'),
				"param_name" => "tablet_portrait",
				"group" => esc_html__('Slider settings', 'xstore')
			),
			array(
				"type" => "textfield",
				"heading" => esc_html__("On mobile", 'xstore'),
				"param_name" => "mobile",
				"group" => esc_html__('Slider settings', 'xstore')
			),
		);
	}
}


// **********************************************************************//
// ! Rewrite vc google font
// **********************************************************************//

if( ! function_exists( 'et_rewrite_vc_google_font' ) ) {

	function et_rewrite_vc_google_font(){

		// Get from js_composer/include/params/google_fonts/google_fonts.php

		$fonts_list['vc'] = '{"font_family":"Abril Fatface","font_styles":"regular","font_types":"400 regular:400:normal"},{"font_family":"Arimo","font_styles":"regular,italic,700,700italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal,700 bold italic:700:italic"},{"font_family":"Arvo","font_styles":"regular,italic,700,700italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal,700 bold italic:700:italic"},{"font_family":"Bitter","font_styles":"regular,italic,700","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal"},{"font_family":"Cabin","font_styles":"regular,italic,500,500italic,600,600italic,700,700italic","font_types":"400 regular:400:normal,400 italic:400:italic,500 bold regular:500:normal,500 bold italic:500:italic,600 bold regular:600:normal,600 bold italic:600:italic,700 bold regular:700:normal,700 bold italic:700:italic"},{"font_family":"Cinzel","font_styles":"regular,700,900","font_types":"400 regular:400:normal,700 bold regular:700:normal,900 bold regular:900:normal"},{"font_family":"Coda","font_styles":"regular,800","font_types":"400 regular:400:normal,800 bold regular:800:normal"},{"font_family":"Condiment","font_styles":"regular","font_types":"400 regular:400:normal"},{"font_family":"Delius","font_styles":"regular","font_types":"400 regular:400:normal"},{"font_family":"Dosis","font_styles":"200,300,regular,500,600,700,800","font_types":"200 light regular:200:normal,300 light regular:300:normal,400 regular:400:normal,500 bold regular:500:normal,600 bold regular:600:normal,700 bold regular:700:normal,800 bold regular:800:normal"},{"font_family":"Droid Sans","font_styles":"regular,700","font_types":"400 regular:400:normal,700 bold regular:700:normal"},{"font_family":"Droid Serif","font_styles":"regular,italic,700,700italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal,700 bold italic:700:italic"},{"font_family":"Exo","font_styles":"100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic","font_types":"100 light regular:100:normal,100 light italic:100:italic,200 light regular:200:normal,200 light italic:200:italic,300 light regular:300:normal,300 light italic:300:italic,400 regular:400:normal,400 italic:400:italic,500 bold regular:500:normal,500 bold italic:500:italic,600 bold regular:600:normal,600 bold italic:600:italic,700 bold regular:700:normal,700 bold italic:700:italic,800 bold regular:800:normal,800 bold italic:800:italic,900 bold regular:900:normal,900 bold italic:900:italic"},{"font_family":"Hind","font_styles":"300,regular,500,600,700","font_types":"300 light regular:300:normal,400 regular:400:normal,500 bold regular:500:normal,600 bold regular:600:normal,700 bold regular:700:normal"},{"font_family":"Istok Web","font_styles":"regular,italic,700,700italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal,700 bold italic:700:italic"},{"font_family":"Josefin Sans","font_styles":"100,100italic,300,300italic,regular,italic,600,600italic,700,700italic","font_types":"100 light regular:100:normal,100 light italic:100:italic,300 light regular:300:normal,300 light italic:300:italic,400 regular:400:normal,400 italic:400:italic,600 bold regular:600:normal,600 bold italic:600:italic,700 bold regular:700:normal,700 bold italic:700:italic"},{"font_family":"Josefin Slab","font_styles":"100,100italic,300,300italic,regular,italic,600,600italic,700,700italic","font_types":"100 light regular:100:normal,100 light italic:100:italic,300 light regular:300:normal,300 light italic:300:italic,400 regular:400:normal,400 italic:400:italic,600 bold regular:600:normal,600 bold italic:600:italic,700 bold regular:700:normal,700 bold italic:700:italic"},{"font_family":"Lato","font_styles":"100,100italic,300,300italic,regular,italic,700,700italic,900,900italic","font_types":"100 light regular:100:normal,100 light italic:100:italic,300 light regular:300:normal,300 light italic:300:italic,400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal,700 bold italic:700:italic,900 bold regular:900:normal,900 bold italic:900:italic"},{"font_family":"Libre Baskerville","font_styles":"regular,italic,700","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal"},{"font_family":"Lobster","font_styles":"regular","font_types":"400 regular:400:normal"},{"font_family":"Lora","font_styles":"regular,italic,700,700italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal,700 bold italic:700:italic"},{"font_family":"Merienda","font_styles":"regular,700","font_types":"400 regular:400:normal,700 bold regular:700:normal"},{"font_family":"Merriweather","font_styles":"300,300italic,regular,italic,700,700italic,900,900italic","font_types":"300 light regular:300:normal,300 light italic:300:italic,400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal,700 bold italic:700:italic,900 bold regular:900:normal,900 bold italic:900:italic"},{"font_family":"Merriweather Sans","font_styles":"300,300italic,regular,italic,700,700italic,800,800italic","font_types":"300 light regular:300:normal,300 light italic:300:italic,400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal,700 bold italic:700:italic,800 bold regular:800:normal,800 bold italic:800:italic"},{"font_family":"Montserrat","font_styles":"regular,700","font_types":"400 regular:400:normal,700 bold regular:700:normal"},{"font_family":"Muli","font_styles":"300,300italic,regular,italic","font_types":"300 light regular:300:normal,300 light italic:300:italic,400 regular:400:normal,400 italic:400:italic"},{"font_family":"Neuton","font_styles":"200,300,regular,italic,700,800","font_types":"200 light regular:200:normal,300 light regular:300:normal,400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal,800 bold regular:800:normal"},{"font_family":"Nothing You Could Do","font_styles":"regular","font_types":"400 regular:400:normal"},{"font_family":"Noto Sans","font_styles":"regular,italic,700,700italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal,700 bold italic:700:italic"},{"font_family":"Noto Serif","font_styles":"regular,italic,700,700italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal,700 bold italic:700:italic"},{"font_family":"Old Standard TT","font_styles":"regular,italic,700","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal"},{"font_family":"Oleo Script","font_styles":"regular,700","font_types":"400 regular:400:normal,700 bold regular:700:normal"},{"font_family":"Open Sans","font_styles":"300,300italic,regular,italic,600,600italic,700,700italic,800,800italic","font_types":"300 light regular:300:normal,300 light italic:300:italic,400 regular:400:normal,400 italic:400:italic,600 bold regular:600:normal,600 bold italic:600:italic,700 bold regular:700:normal,700 bold italic:700:italic,800 bold regular:800:normal,800 bold italic:800:italic"},{"font_family":"Open Sans Condensed","font_styles":"300,300italic,700","font_types":"300 light regular:300:normal,300 light italic:300:italic,700 bold regular:700:normal"},{"font_family":"Orbitron","font_styles":"regular,500,700,900","font_types":"400 regular:400:normal,500 bold regular:500:normal,700 bold regular:700:normal,900 bold regular:900:normal"},{"font_family":"Oswald","font_styles":"300,regular,700","font_types":"300 light regular:300:normal,400 regular:400:normal,700 bold regular:700:normal"},{"font_family":"Oxygen","font_styles":"300,regular,700","font_types":"300 light regular:300:normal,400 regular:400:normal,700 bold regular:700:normal"},{"font_family":"PT Sans","font_styles":"regular,italic,700,700italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal,700 bold italic:700:italic"},{"font_family":"PT Serif","font_styles":"regular,italic,700,700italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal,700 bold italic:700:italic"},{"font_family":"Pacifico","font_styles":"regular","font_types":"400 regular:400:normal"},{"font_family":"Permanent Marker","font_styles":"regular","font_types":"400 regular:400:normal"},{"font_family":"Philosopher","font_styles":"regular,italic,700,700italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal,700 bold italic:700:italic"},{"font_family":"Playfair Display","font_styles":"regular,italic,700,700italic,900,900italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal,700 bold italic:700:italic,900 bold regular:900:normal,900 bold italic:900:italic"},{"font_family":"Radley","font_styles":"regular,italic","font_types":"400 regular:400:normal,400 italic:400:italic"},{"font_family":"Raleway","font_styles":"100,200,300,regular,500,600,700,800,900","font_types":"100 light regular:100:normal,200 light regular:200:normal,300 light regular:300:normal,400 regular:400:normal,500 bold regular:500:normal,600 bold regular:600:normal,700 bold regular:700:normal,800 bold regular:800:normal,900 bold regular:900:normal"},{"font_family":"Roboto","font_styles":"100,100italic,300,300italic,regular,italic,500,500italic,700,700italic,900,900italic","font_types":"100 light regular:100:normal,100 light italic:100:italic,300 light regular:300:normal,300 light italic:300:italic,400 regular:400:normal,400 italic:400:italic,500 bold regular:500:normal,500 bold italic:500:italic,700 bold regular:700:normal,700 bold italic:700:italic,900 bold regular:900:normal,900 bold italic:900:italic"},{"font_family":"Roboto Condensed","font_styles":"300,300italic,regular,italic,700,700italic","font_types":"300 light regular:300:normal,300 light italic:300:italic,400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal,700 bold italic:700:italic"},{"font_family":"Roboto Slab","font_styles":"100,300,regular,700","font_types":"100 light regular:100:normal,300 light regular:300:normal,400 regular:400:normal,700 bold regular:700:normal"},{"font_family":"Satisfy","font_styles":"regular","font_types":"400 regular:400:normal"},{"font_family":"Signika","font_styles":"300,regular,600,700","font_types":"300 light regular:300:normal,400 regular:400:normal,600 bold regular:600:normal,700 bold regular:700:normal"},{"font_family":"Source Code Pro","font_styles":"200,300,regular,500,600,700,900","font_types":"200 light regular:200:normal,300 light regular:300:normal,400 regular:400:normal,500 bold regular:500:normal,600 bold regular:600:normal,700 bold regular:700:normal,900 bold regular:900:normal"},{"font_family":"Ubuntu","font_styles":"300,300italic,regular,italic,500,500italic,700,700italic","font_types":"300 light regular:300:normal,300 light italic:300:italic,400 regular:400:normal,400 italic:400:italic,500 bold regular:500:normal,500 bold italic:500:italic,700 bold regular:700:normal,700 bold italic:700:italic"},{"font_family":"Ubuntu Mono","font_styles":"regular,italic,700,700italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal,700 bold italic:700:italic"},{"font_family":"Vollkorn","font_styles":"regular,italic,700,700italic","font_types":"400 regular:400:normal,400 italic:400:italic,700 bold regular:700:normal,700 bold italic:700:italic"},{"font_family":"Yeseva One","font_styles":"regular","font_types":"400 regular:400:normal"}';

		$fonts_list['et'] = '{"font_family":"Catamaran","font_styles":"regular","font_types":"100 thin:100:normal,200 extra-light:200:normal,300 light:300:normal,400 regular:400:normal,500 medium:500:normal,600 semi-bold:600:normal,700 bold:700:normal,800 extra-bold:800:normal,900 black:900:normal"},{"font_family":"Palanquin","font_styles":"regular","font_types":"100 thin:100:normal,200 extra-light:200:normal,300 light:300:normal,400 regular:400:normal,500 medium:500:normal,600 semi-bold:600:normal,700 bold:700:normal"}';

		$fonts_list = sprintf( '[%1$s,%2$s]', $fonts_list['vc'], $fonts_list['et'] );

		return json_decode( $fonts_list );
	}

	add_filter( 'vc_google_fonts_get_fonts_filter', 'et_rewrite_vc_google_font' );
}