<?php
@ini_set('upload_max_size', '128M');
@ini_set('post_max_size', '128M');
@ini_set('max_execution_time', '300');

/*
* Load theme setup
* ******************************************************************* */
require_once( get_template_directory() . '/theme/theme-setup.php' );

/*
* Load framework
* ******************************************************************* */
require_once( get_template_directory() . '/framework/init.php' );

/*
* Load theme
* ******************************************************************* */
require_once( get_template_directory() . '/theme/init.php' );


//* Excluye categorías específicas del widget "WooCommerce Product Categories":
function exclude_widget_categories($args){
    $exclude = "59,60,76,62,63,69,70,71,68,72,73,74,82,83,79,81,80,86,88,87,84,85"; //* Incluye el ID de las categorías que deseas excluir
    $args["exclude"] = $exclude;
    return $args;
}
add_filter("woocommerce_product_categories_widget_args","exclude_widget_categories");