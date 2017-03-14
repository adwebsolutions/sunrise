<?php 
    $ht = etheme_get_header_type();
    $color = etheme_get_header_color();
    $menu_class = 'menu-align-' . etheme_get_option('menu_align');
?>

<div class="header-wrapper header-<?php echo esc_attr( $ht ); ?> header-color-<?php echo esc_attr( $color ); ?>">
    <?php get_template_part('headers/parts/top-bar'); ?>
    <header class="header main-header">
        <div class="container">
            <div class="container-wrapper">
                <div class="header-logo"><?php etheme_logo(); ?></div>
                <?php if ( has_nav_menu( 'secondary' ) && etheme_get_option( 'secondary_menu' ) ): ?>
                    <div class="secondary-menu-wrapper">
                        <div class="secondary-title">
                            <div class="secondary-menu-toggle">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </div>
                            <?php etheme_option('all_departments_text'); ?>
                        </div>
                        <?php etheme_get_main_menu('secondary'); ?>
                    </div>
                <?php endif ?>
                <div class="menu-wrapper <?php echo esc_attr($menu_class); ?>"><?php etheme_get_main_menu(); ?></div>
                <div class="navbar-toggle">
                    <span class="sr-only"><?php esc_html_e('Menu', 'xstore'); ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </div>
                <div class="navbar-header">
                    <?php if(etheme_get_option('search_form')): ?>
                        <?php etheme_search_form(); ?>
                    <?php endif; ?>

                    <?php if( etheme_woocommerce_installed() && etheme_get_option( 'top_wishlist_widget' ) ) etheme_wishlist_widget(); ?>

                    <?php if(etheme_woocommerce_installed() && current_theme_supports('woocommerce') && !etheme_get_option('just_catalog') && etheme_get_option('cart_widget')): ?>
                        <?php etheme_top_cart(); ?>
                    <?php endif ;?>
                </div>
            </div>
        </div>
    </header>
</div>