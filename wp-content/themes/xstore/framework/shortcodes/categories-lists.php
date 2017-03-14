<?php  if ( ! defined('ETHEME_FW')) exit('No direct script access allowed');

// **********************************************************************// 
// ! etheme_categories
// **********************************************************************// 

function etheme_categories_lists_shortcode($atts) {
    global $woocommerce_loop;
    extract( shortcode_atts( array(
        'number'     => null,
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => 1,
        'columns' => 3,
        'parent'     => 0,
        'display_type' => 'grid',
        'ids'        => '',
        'exclude'    => '',
        'large' => 4,
        'notebook' => 3,
        'tablet_land' => 2,
        'tablet_portrait' => 2,
        'mobile' => 1,
        'slider_autoplay' => false,
        'slider_speed' => 10000,
        'pagination_type' => 'hide',
        'default_color' => '#e6e6e6',
        'active_color' => '#b3a089',
        'hide_fo' => '',
        'hide_buttons' => false,
        'class'      => ''
    ), $atts ) );

    if ( isset( $atts[ 'ids' ] ) && ! empty( $atts[ 'ids' ] ) ) {
        $ids = explode( ',', $atts[ 'ids' ] );
        $ids = array_map( 'trim', $ids );
        $parent = '';
    } else {
        $ids = false;
    }

    if ( isset( $atts[ 'exclude' ] ) ) {
        $exclude = explode( ',', $atts[ 'exclude' ] );
        $exclude = array_map( 'trim', $exclude );
    } else {
        $exclude = array();
    }

    $hide_empty = ( $hide_empty == true || $hide_empty == 1 ) ? 1 : 0;

    // get terms and workaround WP bug with parents/pad counts
    $args = array(
        'orderby'    => $orderby,
        'order'      => $order,
        'hide_empty' => $hide_empty,
        'include'    => $ids,
        'exclude'    => $exclude,
        'pad_counts' => true,
        'parent' => $parent
    );

    $product_categories = get_terms( 'product_cat', $args );

    if ( $hide_empty && ! is_wp_error( $product_categories ) ) {
        foreach ( $product_categories as $key => $category ) {
            if ( $category->count == 0 ) {
                unset( $product_categories[ $key ] );
            }
        }
    }

    if ( $number ) {
        $product_categories = array_slice( $product_categories, 0, $number );
    }

    $box_id = rand(1000,10000);

    $class .= ' slider-' . $box_id;

    ob_start();

    if ( $product_categories ) {

        if($display_type == 'slider') {
            $class .= ' categories-lists-slider owl-carousel carousel-area';
        } else {
            $class .= ' categories-lists-grid';
            $class .= ' categories-columns-' . $columns;
        }

        echo '<div class="'.$class.' slider-'.$box_id.'">';

        foreach ( $product_categories as $category ) {

            ?>
              <div class="category-list-item-wrapper">
                <div class="category-list-item">
                  <a href="<?php echo get_term_link( $category, 'product_cat' ); ?>" class="category-image">
                    <?php woocommerce_subcategory_thumbnail( $category ); ?>
                  </a>
                  <ul>
                    <?php etheme_show_category_in_the_list( $category, $orderby, $order, $exclude, $hide_empty ) ?>
                   </ul>
                </div>
              </div>
            <?php

        }

        echo '</div>';
            
        if($display_type == 'slider') {
            echo '
                <script type="text/javascript">
                    (function() {
                        var options = {
                            items:5,
                            autoPlay: ' . (($slider_autoplay == "yes") ? $slider_speed : "false" ). ',
                            pagination: ' . (($pagination_type == "hide") ? "false" : "true") . ',
                            navigation: ' . (($hide_buttons == "yes") ? "false" : "true" ). ',
                            navigationText:false,
                            rewindNav: ' . (($slider_autoplay == "yes") ? "true" : "false" ). ',
                            itemsCustom: [[0, ' . esc_js($mobile) . '], [479, ' . esc_js($tablet_portrait) . '], [619, ' . esc_js($tablet_portrait) . '], [768, ' . esc_js($tablet_land) . '],  [1200, ' . esc_js($notebook) . '], [1600, ' . esc_js($large) . ']]
                        };

                        jQuery(".slider-'.$box_id.'").owlCarousel(options);

                        var owl = jQuery(".slider-'.$box_id.'").data("owlCarousel");

                        jQuery( window ).bind( "vc_js", function() {
                            owl.reinit(options);
                            jQuery(".slider-'.$box_id.' .owl-pagination").addClass("pagination-type-'.$pagination_type.' hide-for-'.$hide_fo.'");
                        } );
                    })();
                </script>
            ';
        }
        if ( $pagination_type != 'hide' && $default_color != '#e6e6e6' && $active_color !='#b3a089' ) {
          echo '
              <style>
                  .slider-'.$box_id.' .owl-pagination .owl-page{
                      background-color:'.$default_color.';
                  }
                  .slider-'.$box_id.' .owl-carousel .owl-pagination .owl-page:hover{
                      background-color:'.$active_color.';
                  }
                  .slider-'.$box_id.' .owl-pagination .owl-page.active{
                      background-color:'.$active_color.';
                  }
              </style>
          ';
        }
    }

    return ob_get_clean();
}

if( ! function_exists( 'etheme_show_category_in_the_list' ) ) {
  function etheme_show_category_in_the_list( $category, $orderby, $order, $exclude, $hide_empty ) {
    ?>
      <li>
        <a href="<?php echo get_term_link( $category, 'product_cat' ); ?>" class="category-name"><?php echo $category->name; ?>
          <?php
            if ( $category->count > 0 )
              echo ' <mark class="count">(' . $category->count . ')</mark>';
          ?>
        </a>
        <?php 
          $subcategories =  get_terms( 'product_cat', array(
            'orderby'    => $orderby,
            'order'      => $order,
            'exclude'    => $exclude,
            'hide_empty' => $hide_empty,
            'pad_counts' => true,
            'parent' => $category->term_id
          ) );

         ?>
    
         <?php if( ! empty( $subcategories ) && ! is_wp_error( $subcategories ) ) {
            echo '<ul>';
            foreach ($subcategories as $category) {
              etheme_show_category_in_the_list( $category, $orderby, $order, $exclude, $hide_empty );
            }
            echo '</ul>';
         } ?>
       </li>
    <?php
  }
}


// **********************************************************************// 
// ! Register New Element: scslug
// **********************************************************************//
add_action( 'init', 'etheme_register_etheme_categories_lists');
if(!function_exists('etheme_register_etheme_categories_lists')) {
  if( class_exists('Vc_Vendor_Woocommerce')) {
    $Vc_Vendor_Woocommerce = new Vc_Vendor_Woocommerce();
    add_filter( 'vc_autocomplete_etheme_categories_lists_ids_callback', array($Vc_Vendor_Woocommerce, 'productCategoryCategoryAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array
    add_filter( 'vc_autocomplete_etheme_categories_lists_ids_render', array($Vc_Vendor_Woocommerce, 'productCategoryCategoryRenderByIdExact',), 10, 1 ); // Render exact category by id. Must return an array (label,value)
    add_filter( 'vc_autocomplete_etheme_categories_lists_exclude_callback', array($Vc_Vendor_Woocommerce, 'productCategoryCategoryAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array
    add_filter( 'vc_autocomplete_etheme_categories_lists_exclude_render', array($Vc_Vendor_Woocommerce, 'productCategoryCategoryRenderByIdExact',), 10, 1 ); // Render exact category by id. Must return an array (label,value)
  }

	function etheme_register_etheme_categories_lists() {
		if(!function_exists('vc_map')) return;
      $order_by_values = array(
        '',
        esc_html__( 'ID', 'xstore' ) => 'ID',
        esc_html__( 'Title', 'xstore' ) => 'name',
        esc_html__( 'Modified', 'xstore' ) => 'modified',
        esc_html__( 'Products count', 'xstore' ) => 'count',
          esc_html__( 'As IDs provided order', 'xstore' ) => 'include',
      );

      $order_way_values = array(
        '',
        esc_html__( 'Descending', 'xstore' ) => 'DESC',
        esc_html__( 'Ascending', 'xstore' ) => 'ASC',
      );
      
	    $params = array(
	      'name' => '[8theme] Product categories lists',
	      'base' => 'etheme_categories_lists',
	      'icon' => 'icon-wpb-etheme',
        'icon' => ETHEME_CODE_IMAGES . 'vc/el-categories.png',
	      'category' => 'Eight Theme',
	      'params' => array_merge(array(
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Number of categories", 'xstore'),
	          "param_name" => "number"
	        ),
            array(
              'type' => 'autocomplete',
              'heading' => esc_html__( 'Categories', 'xstore' ),
              'param_name' => 'ids',
              'settings' => array(
                'multiple' => true,
                'sortable' => true,
              ),
              'save_always' => true,
              'description' => esc_html__( 'List of product categories', 'xstore' ),
            ),
            array(
              'type' => 'autocomplete',
              'heading' => esc_html__( 'Exclude Categories', 'xstore' ),
              'param_name' => 'exclude',
              'settings' => array(
                'multiple' => true,
                'sortable' => true,
              ),
              'save_always' => true,
              'description' => esc_html__( 'List of product categories to exclude', 'xstore' ),
            ),
            array(
              "type" => "dropdown",
              "heading" => esc_html__("Display type", 'xstore'),
              "param_name" => "display_type",
              "value" => array( 
                  esc_html__("Grid", 'xstore') => 'grid',
                  esc_html__("Slider", 'xstore') => 'slider',
                )
            ),
            array(
              "type" => "dropdown",
              "heading" => esc_html__("Columns", 'xstore'),
              "param_name" => "columns",
              "value" => array( 
                  esc_html__("2", 'xstore') => 2,
                  esc_html__("3", 'xstore') => 3,
                  esc_html__("4", 'xstore') => 4,
                  esc_html__("5", 'xstore') => 5,
                  esc_html__("6", 'xstore') => 6,
                ),
              "dependency" => array('element' => "display_type", 'value' => array('grid'))
            ),
            array(
              'type' => 'dropdown',
              'heading' => esc_html__( 'Order by', 'xstore' ),
              'param_name' => 'orderby',
              'value' => $order_by_values,
              'save_always' => true,
              'description' => sprintf( esc_html__( 'Select how to sort retrieved products. More at %s.', 'xstore' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
            ),
            array(
              'type' => 'dropdown',
              'heading' => esc_html__( 'Sort order', 'xstore' ),
              'param_name' => 'order',
              'value' => $order_way_values,
              'save_always' => true,
              'description' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'xstore' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
            ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Extra Class", 'xstore'),
	          "param_name" => "class"
	        )
          ), etheme_get_slider_params())
	    );  
	
	    vc_map($params);
	}
}
