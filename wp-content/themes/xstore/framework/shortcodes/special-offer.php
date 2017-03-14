<?php  if ( ! defined('ETHEME_FW')) exit('No direct script access allowed');

// **********************************************************************// 
// ! The look
// **********************************************************************// 

function etheme_offer_shortcode($atts, $content) {
    global $woocommerce_loop;

    if ( ! class_exists('Woocommerce') ) return false;

    $output = '';

    $atts = shortcode_atts(array(
        'post_type'  => 'ids',
        'include'  => '',
        'custom_query'  => '',
        'taxonomies'  => '',
        'items_per_page'  => 10,
        'columns' => 3,
        'orderby'  => 'date',
        'order'  => 'DESC',
        'meta_key'  => '',
        'exclude'  => '',
        'class'  => '',
        'product_view' => '',
        'product_view_color' => '',
        'css' => ''
    ), $atts);

    extract($atts);

    $paged = (get_query_var('page')) ? get_query_var('page') : 1;

    $args = array(
      'post_type' => 'product',
      'status' => 'published',
      'paged' => $paged,  
      'posts_per_page' => $items_per_page
    );

    if($post_type == 'ids' && $include != '') {
      $args['post__in'] = explode(',', $include);
      $orderby = 'post__in';
    }

    if(!empty( $exclude ) ) {
      $args['post__not_in'] = explode(',', $exclude);
    }


    if(!empty( $taxonomies )) {
      $terms = get_terms( array('product_cat', 'product_tag'), array(
        'orderby' => 'name',
        'include' => $taxonomies
      ));

      if( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
        $args['tax_query'] = array('relation' => 'OR');
        foreach ($terms as $key => $term) {
          $args['tax_query'][] = array(
                'taxonomy' => $term->taxonomy,                //(string) - Taxonomy.
                'field' => 'slug',                    //(string) - Select taxonomy term by ('id' or 'slug')
                'terms' => array( $term->slug ),    //(int/string/array) - Taxonomy term(s).
                'include_children' => true,           //(bool) - Whether or not to include children for hierarchical taxonomies. Defaults to true.
                'operator' => 'IN'  
          );
        }
      }
    }

    if(!empty( $order )) {
      $args['order'] = $order;
    }

    if(!empty( $meta_key )) {
      $args['meta_key'] = $meta_key;
    }

    if(!empty( $orderby )) {
      $args['orderby'] = $orderby;
    }

    $output = '';

    ob_start();

    $products = new WP_Query( $args );

    $class = $title_output = $images_class = '';

    $shop_url = get_permalink(woocommerce_get_page_id('shop'));

    $woocommerce_loop['columns'] = $columns;
    //$woocommerce_loop['size'] = 'shop_catalog_alt';

    if( ! empty($css) && function_exists( 'vc_shortcode_custom_css_class' )) {
        $images_class = vc_shortcode_custom_css_class( $css );
        $images_style = explode('{', $css);
        $images_style = '[data-class="' . $images_class . '"] .product-content-image img {' . $images_style[1];
        $css = '<style>' . $images_style . '</style>';
    }

    if( $banner_double ) {
      $columns = $columns / 2;
    }

    if ( $products->have_posts() ) : ?>
      <div class="et-offer" data-class="<?php echo esc_attr($images_class); ?>">
        <?php $i=0; while ( $products->have_posts() ) : $products->the_post(); ?>
            <div <?php post_class(); ?>>
                <div class="content-product">
                  <?php etheme_loader(); ?>
                  <?php
                    /**
                     * woocommerce_before_shop_loop_item hook.
                     *
                     * @hooked woocommerce_template_loop_product_link_open - 10
                     */
                    do_action( 'woocommerce_before_shop_loop_item' );

                    /**
                     * woocommerce_before_shop_loop_item_title hook.
                     *
                     * @hooked woocommerce_show_product_loop_sale_flash - 10
                     * @hooked woocommerce_template_loop_product_thumbnail - 10
                     */
                    do_action( 'woocommerce_before_shop_loop_item_title' );
                  ?>

                    <?php
                        etheme_product_cats();
                    ?>  
                    <p class="product-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </p>

                    <?php woocommerce_template_loop_rating(); ?>

                    <div class="product-image-wrapper hover-effect-<?php echo esc_attr( $hover ); ?>">

                      <?php etheme_product_availability(); ?>

                      <a class="product-content-image" href="<?php the_permalink(); ?>" data-images="<?php echo etheme_get_image_list( $size ); ?>">
                        <?php if( $hover == 'swap' ) etheme_get_second_image( $size ); ?>
                        <?php echo woocommerce_get_product_thumbnail( $size ); ?>
                      </a>

                      <footer class="footer-product">
                          <?php if (etheme_get_option('quick_view')): ?>
                              <span class="show-quickly" data-prodid="<?php echo get_the_ID();?>"><?php esc_html_e('Quick View', 'xstore') ?></span>
                          <?php endif ?>
                          <?php //if (etheme_get_option('product_page_addtocart')) {
                              do_action( 'woocommerce_after_shop_loop_item' );
                          //}?>
                          <?php echo etheme_wishlist_btn(__('Wishlist', 'xstore')); ?>
                      </footer>
                    </div>

                    <div class="product-details">

                        <?php woocommerce_template_loop_price(); ?>

                        <?php etheme_product_countdown(); ?>
                    </div>
              </div><!-- .content-product -->
            </div>
        <?php endwhile; // end of the loop. ?>
        <?php
          echo $css;
          unset($woocommerce_loop['columns']); 
          unset($woocommerce_loop['isotope']); 
          unset($woocommerce_loop['size']); 
          unset($woocommerce_loop['product_view']); 
          unset($woocommerce_loop['product_view_color']); 
        ?>
      </div>
    <?php endif;

    wp_reset_postdata();

    $output = ob_get_clean();
      
    return $output;
}

// **********************************************************************// 
// ! Register New Element: The Look
// **********************************************************************//
add_action( 'init', 'etheme_register_offer');
if(!function_exists('etheme_register_offer')) {
	function etheme_register_offer() {
		if(!function_exists('vc_map')) return;

      add_filter( 'vc_autocomplete_et_offer_include_callback',
        'vc_include_field_search', 10, 1 ); // Get suggestion(find). Must return an array
      add_filter( 'vc_autocomplete_et_offer_include_render',
        'vc_include_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)

      // Narrow data taxonomies
      add_filter( 'vc_autocomplete_et_offer_taxonomies_callback',
        'vc_autocomplete_taxonomies_field_search', 10, 1 );
      add_filter( 'vc_autocomplete_et_offer_taxonomies_render',
        'vc_autocomplete_taxonomies_field_render', 10, 1 );

      // Narrow data taxonomies for exclude_filter
      add_filter( 'vc_autocomplete_et_offer_exclude_filter_callback',
        'vc_autocomplete_taxonomies_field_search', 10, 1 );
      add_filter( 'vc_autocomplete_et_offer_exclude_filter_render',
        'vc_autocomplete_taxonomies_field_render', 10, 1 );

      add_filter( 'vc_autocomplete_et_offer_exclude_callback',
        'vc_exclude_field_search', 10, 1 ); // Get suggestion(find). Must return an array
      add_filter( 'vc_autocomplete_et_offer_exclude_render',
    'vc_exclude_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)


      $post_types_list = array();
      $post_types_list[] = array( 'product', esc_html__( 'Product', 'xstore' ) );
      //$post_types_list[] = array( 'custom', esc_html__( 'Custom query', 'xstore' ) );
      $post_types_list[] = array( 'ids', esc_html__( 'List of IDs', 'xstore' ) );

	    $params = array(
	      'name' => '[8THEME] Best offer',
	      'base' => 'et_offer',
	      'icon' => 'icon-wpb-etheme',
	      'category' => 'Eight Theme',
        'content_element' => true,
        'icon' => ETHEME_CODE_IMAGES . 'vc/el-lookbook.png',
	      'params' => array(
          array(
            'type' => 'autocomplete',
            'heading' => esc_html__( 'Product', 'xstore' ),
            'param_name' => 'include',
            'settings' => array(
              'multiple' => false,
              'sortable' => true,
              'groups' => true,
            ),
          ),
	      )
	
	    );  
	
	    vc_map($params);
	}
}