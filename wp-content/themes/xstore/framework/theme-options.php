<?php
/**
 * ReduxFramework Sample Config File
 * For full documentation, please visit: http://docs.reduxframework.com/
 */


if ( ! class_exists( 'Redux' ) || ! etheme_is_activated() ) {
    global $et_options;

    $et_options = array(
        'main_layout' => 'wide',
        'header_type' => 'xstore',
        'header_full_width' => '1',
        'header_color' => 'white',
        'header_overlap' => '1',
        'top_bar' => '1',
        'top_bar_color' => 'white',
        'logo_width' => '200',
        'top_links' => '1',
        'search_form' => '1',
        'breadcrumb_type' => 'default',
        'breadcrumb_size' => 'small',
        'breadcrumb_effect' => 'none',
        'breadcrumb_bg' =>
            array (
                'background-color' => '#d64444',
            ),
        'breadcrumb_color' => 'white',
        'activecol' => '#d64444',
        'blog_hover' => 'default',
        'blog_byline' => '1',
        'read_more' => '1',
        'views_counter' => '1',
        'blog_sidebar' => 'right',
        'excerpt_length' => '25',
        'post_template' => 'default',
        'blog_featured_image' => '1',
    );
    return;
}


if(!function_exists('etheme_redux_init')) {
    function etheme_redux_init() {
        // This is your option name where all the Redux data is stored.
        $opt_name = "et_options";


        /**
         * ---> SET ARGUMENTS
         * All the possible arguments for Redux.
         * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
         * */

        $theme = wp_get_theme(); // For use with some settings. Not necessary.

        $args = array(
            // TYPICAL -> Change these values as you need/desire
            'opt_name'             => $opt_name,
            // This is where your data is stored in the database and also becomes your global variable name.
            'display_name'         => ETHEME_THEME_NAME . ' <span>' . esc_html__('Theme Activated', 'xstore') . ' - <small>' . get_option( 'xtheme_purchase_code', '' ) .'</small></span>',
            // Name that appears at the top of your panel
            'display_version'      => $theme->get( 'Version' ),
            // Version that appears at the top of your panel
            'menu_type'            => 'menu',
            //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
            'allow_sub_menu'       => true,
            // Show the sections below the admin menu item or not
            'menu_title'           => esc_html__( '8Theme Options', 'xstore' ),
            'page_title'           => esc_html__( '8Theme Options', 'xstore' ),
            // You will need to generate a Google API key to use this feature.
            // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
            'google_api_key'       => '',
            // Set it you want google fonts to update weekly. A google_api_key value is required.
            'google_update_weekly' => false,
            // Must be defined to add google fonts to the typography module
            'async_typography'     => false,
            // Use a asynchronous font on the front end or font string
            //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
            'admin_bar'            => false,
            // Show the panel pages on the admin bar
            'admin_bar_icon'       => 'dashicons-portfolio',
            // Choose an icon for the admin bar menu
            'admin_bar_priority'   => 50,
            // Choose an priority for the admin bar menu
            'global_variable'      => '',
            // Set a different name for your global variable other than the opt_name
            'dev_mode'             => false,
            // Show the time the page took to load, etc
            'update_notice'        => true,
            // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
            'customizer'           => true,
            // Enable basic customizer support
            //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
            //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

            // OPTIONAL -> Give you extra features
            'page_priority'        => 63,
            // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
            'page_parent'          => 'themes.php',
            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
            'page_permissions'     => 'manage_options',
            // Permissions needed to access the options panel.
            'menu_icon'            => ETHEME_CODE_IMAGES . 'icon-etheme.png',
            // Specify a custom URL to an icon
            'last_tab'             => '',
            // Force your panel to always open to a specific tab (by id)
            'page_icon'            => 'icon-themes',
            // Icon displayed in the admin panel next to your menu_title
            'page_slug'            => '_options',
            // Page slug used to denote the panel
            'save_defaults'        => true,
            // On load save the defaults to DB before user clicks save or not
            'default_show'         => false,
            // If true, shows the default value next to each field that is not the default value.
            'default_mark'         => '',
            // What to print by the field's title if the value shown is default. Suggested: *
            'show_import_export'   => true,
            // Shows the Import/Export panel when not used as a field.

            // CAREFUL -> These options are for advanced use only
            'transient_time'       => 60 * MINUTE_IN_SECONDS,
            'output'               => true,
            // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
            'output_tag'           => true,
            // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
            'footer_credit'     => '8theme',                   // Disable the footer credit of Redux. Please leave if you can help it.


            'templates_path' => ETHEME_BASE . ETHEME_CODE_3D . 'options-framework/et-templates/',

            // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
            'database'             => '',
            // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
            'system_info'          => false
        );


        Redux::setArgs( $opt_name, $args );

        /*
         * ---> END ARGUMENTS
         */

        // -> START Basic Fields

        Redux::setSection( $opt_name, array(
            'title' => __( 'General', 'xstore' ),
            'id' => 'general',
            'icon' => 'el-icon-home',
        ) );


        Redux::setSection( $opt_name, array(
            'title' => __( 'Layout', 'xstore' ),
            'id' => 'general-layout',
            'subsection' => true,
            'icon' => 'el-icon-home',
            'fields' => array (
                array (
                    'id' => 'main_layout',
                    'type' => 'select',
                    'operator' => 'and',
                    'title' => __( 'Site Layout', 'xstore' ),
                    'options' => array (
                        'wide' => __( 'Wide layout', 'xstore' ),
                        'boxed' => __( 'Boxed', 'xstore' ),
                        'framed' => __( 'Framed', 'xstore' ),
                        'bordered' => __( 'Bordered', 'xstore' ),
                    ),
                    'default' => 'wide'
                ),
                array (
                    'id' => 'site_preloader',
                    'type' => 'switch',
                    'title' => __( 'Use site preloader', 'xstore' ),
                    'default' => false,
                ),
                array (
                    'id' => 'support_chat',
                    'type' => 'switch',
                    'title' => __( 'Support chat', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'static_blocks',
                    'type' => 'switch',
                    'title' => __( 'Enable static blocks', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'portfolio_projects',
                    'type' => 'switch',
                    'title' => __( 'Portfolio projects', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'testimonials_type',
                    'type' => 'switch',
                    'title' => __( 'Testimonials', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'enable_brands',
                    'type' => 'switch',
                    'title' => __( 'Brands', 'xstore' ),
                    'default' => true,
                ),
            ),
        ) );

        Redux::setSection( $opt_name, array(
            'title' => __( 'Header Type', 'xstore' ),
            'id' => 'general-header',
            'icon' => 'el-icon-cog',
            'subsection' => true,
            'fields' => array (
                array (
                    'id' => 'header_type',
                    'type' => 'image_select',
                    'title' => __( 'Header Type', 'xstore' ),
                    'options' => array (
                        'xstore' => array (
                            'title' => __( 'Variant xstore', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'headers/xstore.jpg',
                        ),
                        'xstore2' => array (
                            'title' => __( 'Variant xstore2', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'headers/xstore2.jpg',
                        ),
                        'center' => array (
                            'title' => __( 'Variant center', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'headers/center.jpg',
                        ),
                        'center2' => array (
                            'title' => __( 'Variant center 2', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'headers/center2.jpg',
                        ),
                        'center3' => array (
                            'title' => __( 'Variant center 3', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'headers/center3.jpg',
                        ),
                        'standard' => array (
                            'title' => __( 'Variant standard', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'headers/standard.jpg',
                        ),
                        'double-menu' => array (
                            'title' => __( 'Double menu', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'headers/double-menu.jpg',
                        ),
                        'two-rows' => array (
                            'title' => __( 'Two rows', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'headers/two-rows.jpg',
                        ),
                        'advanced' => array (
                            'title' => __( 'Advanced', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'headers/advanced.jpg',
                        ),
                        'simple' => array (
                            'title' => __( 'Variant simple', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'headers/simple.jpg',
                        ),
                        'hamburger-icon' => array (
                            'title' => __( 'Variant hamburger', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'headers/hamburger-icon.jpg',
                        ),
                    ),
                    'default' => 'xstore'
                ),
            ),
        ) );

        Redux::setSection( $opt_name, array(
            'title' => __( 'Header Settings', 'xstore' ),
            'id' => 'general-header-settings',
            'icon' => 'el-icon-cog',
            'subsection' => true,
            'fields' => array (
                array (
                    'id' => 'header_full_width',
                    'type' => 'switch',
                    'title' => __( 'Header wide', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id'        => 'header_width',
                    'type'      => 'slider',
                    'title'     => __( 'Header maximum width', 'xstore' ),
                    "default"   => 1600,
                    "min"       => 1300,
                    "step"      => 1,
                    "max"       => 3000,
                    'display_value' => 'label',
                    'required' => array(
                        array( 'header_full_width', 'equals', true)
                    )
                ),
                array (
                    'id' => 'fixed_header',
                    'type' => 'switch',
                    'title' => __( 'Fixed header', 'xstore' ),
                    'default' => true
                ),
                array (
                    'id' => 'header_overlap',
                    'type' => 'switch',
                    'title' => __( 'Header overlaps the content', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'header_color',
                    'type' => 'select',
                    'title' => __( 'Header text color', 'xstore' ),
                    'options' => array (
                        'dark' => __( 'Dark', 'xstore' ),
                        'white' => __( 'White', 'xstore' ),
                    ),
                    'default' => 'white'
                ),
                array (
                    'id' => 'header_bg',
                    'type' => 'background',
                    'title' => __( 'Header background', 'xstore' ),
                    'output' => array('.main-header,.navigation-wrapper')
                ),
                array (
                    'id' => 'fixed_header_color',
                    'type' => 'select',
                    'title' => __( 'Fixed header text color', 'xstore' ),
                    'options' => array (
                        'dark' => __( 'Dark', 'xstore' ),
                        'white' => __( 'White', 'xstore' ),
                    ),
                    'default' => 'dark'
                ),
                array (
                    'id' => 'fixed_header_bg',
                    'type' => 'background',
                    'title' => __( 'Fixed header background', 'xstore' ),
                    'output' => array('.fixed-header')
                ),
                array (
                    'id' => 'top_bar',
                    'type' => 'switch',
                    'title' => __( 'Enable top bar', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'top_bar_bg',
                    'type' => 'background',
                    'title' => __( 'Top bar background', 'xstore' ),
                    'output' => array('.top-bar')
                ),
                array (
                    'id' => 'top_bar_color',
                    'type' => 'select',
                    'title' => __( 'Top bar text color', 'xstore' ),
                    'options' => array (
                        'dark' => __( 'Dark', 'xstore' ),
                        'white' => __( 'White', 'xstore' ),
                    ),
                    'default' => 'white'
                ),
                array (
                    'id' => 'header_custom_block',
                    'type' => 'editor',
                    'title' => __( 'Header custom HTML', 'xstore' ),
                    'required' => array(
                        array( 'header_type', 'equals', array('standard', 'advanced'))
                    )
                ),
                array (
                    'id' => 'logo',
                    'type' => 'media',
                    'desc' => __( 'Upload image: png, jpg or gif file', 'xstore' ),
                    'title' => __( 'Logo image', 'xstore' ),
                ),
                array (
                    'id' => 'logo_fixed',
                    'type' => 'media',
                    'desc' => __( 'Upload image: png, jpg or gif file', 'xstore' ),
                    'title' => __( 'Logo image for fixed header', 'xstore' ),
                ),
                array (
                    'id'        => 'logo_width',
                    'type'      => 'slider',
                    'title'     => __( 'Logo max width', 'xstore' ),
                    "default"   => 200,
                    "min"       => 50,
                    "step"      => 1,
                    "max"       => 500,
                    'display_value' => 'label'
                ),
                array (
                    'id' => 'top_wishlist_widget',
                    'type' => 'switch',
                    'title' => __( 'Enable wishlist widget in header', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'top_links',
                    'type' => 'switch',
                    'title' => __( 'Enable Sign In link', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'search_form',
                    'type' => 'switch',
                    'title' => __( 'Enable search form in header', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'search_ajax',
                    'type' => 'switch',
                    'title' => __( 'AJAX Search', 'xstore' ),
                    'default' => true,
                    'required' => array(
                        array( 'search_form', 'equals', true)
                    )
                ),
                array (
                    'id' => 'top_panel',
                    'type' => 'switch',
                    'title' => __( 'Enable top panel', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'shopping_cart_icon',
                    'type' => 'image_select',
                    'title' => __( 'Shopping cart icon', 'xstore' ),
                    'options' => array (
                        1 => array (
                            'img' => ETHEME_CODE_IMAGES . 'cart/default.png',
                        ),
                        2 => array (
                            'img' => ETHEME_CODE_IMAGES . 'cart/additional_1.png',
                        ),
                        3 => array (
                            'img' => ETHEME_CODE_IMAGES . 'cart/additional_2.png',
                        ),
                        4 => array (
                            'img' => ETHEME_CODE_IMAGES . 'cart/additional_3.png',
                        ),

                    ),
                    'default' => 1
                ),
                array (
                    'id' => 'shopping_cart_icon_bg',
                    'type' => 'switch',
                    'title' => __( 'Icon with background', 'xstore' ),
                    'default' => false,
                ),
                array (
                    'id' => 'favicon_label',
                    'type' => 'switch',
                    'title' => __( 'Show number of cart items on favicon', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'cart_badge_bg',
                    'type' => 'color_rgba',
                    'title' => __( 'Background color for cart number label', 'xstore' ),
                    'output' => array(
                        'background-color' => '.header-color-inherit .et-wishlist-widget .wishlist-count, .header-color-dark .et-wishlist-widget .wishlist-count, .header-color-white .et-wishlist-widget .wishlist-count, .cart-bag .badge-number, .shopping-container.ico-design-2 .cart-bag .badge-number, .shopping-container.ico-design-3 .cart-bag .badge-number, .shopping-container.ico-design-1.ico-bg-yes .badge-number'
                    )
                ),
                array (
                    'id' => 'cart_icon_label',
                    'type' => 'select',
                    'title' => __( 'Label position', 'xstore' ),
                    'options' => array (
                        'top' => __( 'Top', 'xstore' ),
                        'bottom' => __( 'Bottom', 'xstore' ),
                    ),
                    'default' => 'top'
                ),
            ),
        ) );

        Redux::setSection( $opt_name, array(
            'title' => __( 'Secondary menu', 'xstore' ),
            'id' => 'general-header-secondary',
            'icon' => 'el-icon-cog',
            'subsection' => true,
            'fields' => array (
                array (
                    'id' => 'secondary_menu',
                    'type' => 'switch',
                    'title' => __( 'Enable secondary menu', 'xstore' ),
                    'default' => false,
                ),
                array (
                    'id' => 'secondary_menu_visibility',
                    'type' => 'select',
                    'title' => __( 'Secondary menu visibility', 'xstore' ),
                    'options' => array (
                        'opened' => __( 'Opened', 'xstore' ),
                        'on_click' => __( 'Opened by click', 'xstore' ),
                        'on_hover' => __( 'Opened on hover', 'xstore' ),
                    ),
                    'default' => 'on_hover'
                ),
                array (
                    'id' => 'secondary_menu_home',
                    'type' => 'switch',
                    'title' => __( 'For home page only', 'xstore' ),
                    'default' => true,
                    'required' => array(
                        array( 'secondary_menu_visibility', 'equals', 'opened')
                    )
                ),
                array (
                    'id' => 'secondary_menu_darkening',
                    'type' => 'switch',
                    'title' => __( 'Darkening', 'xstore' ),
                    'default' => true,
                    'required' => array(
                        array( 'secondary_menu_visibility', 'equals', array( 'on_click', 'on_hover'))
                    )
                ),
                array (
                    'id' => 'all_departments_text',
                    'type' => 'text',
                    'title' => __( 'All departaments text', 'xstore' ),
                    'default' => __( 'All departaments', 'xstore' )
                ),
            ),
        ) );

        Redux::setSection( $opt_name, array(
            'title' => __( 'Breadcrumbs', 'xstore' ),
            'id' => 'general-header-breadcrumbs',
            'icon' => 'el-icon-cog',
            'subsection' => true,
            'fields' => array (
                array (
                    'id' => 'breadcrumb_type',
                    'type' => 'select',
                    'title' => __( 'Breadcrumbs Style', 'xstore' ),
                    'options' => array (
                        'default' => __( 'Align center', 'xstore' ),
                        'left' => __( 'Align left', 'xstore' ),
                        'left2' => __( 'Left inline', 'xstore' ),
                        'disable' => __( 'Disable', 'xstore' ),
                    ),
                    'default' => 'default'
                ),
                array (
                    'id' => 'breadcrumb_size',
                    'type' => 'select',
                    'title' => __( 'Breadcrumbs size', 'xstore' ),
                    'options' => array (
                        'small' => __( 'Small', 'xstore' ),
                        'large' => __( 'Large', 'xstore' ),
                    ),
                    'default' => 'large'
                ),
                array (
                    'id' => 'breadcrumb_effect',
                    'type' => 'select',
                    'title' => __( 'Breadcrumbs effect', 'xstore' ),
                    'options' => array (
                        'none' => __( 'None', 'xstore' ),
                        'mouse' => __( 'Parallax on mouse move', 'xstore' ),
                        'text-scroll' => __( 'Text animation on scroll', 'xstore' ),
                    ),
                    'default' => 'mouse'
                ),
                array (
                    'id' => 'breadcrumb_bg',
                    'type' => 'background',
                    'title' => __( 'Breadcrumbs background', 'xstore' ),
                    'default' => array(
                        'background-color' => '#dc5958',
                        'background-image' => 'http://8theme.com/import/xstore/wp-content/uploads/2016/05/breadcrumb-1.png'
                    )
                ),
                array (
                    'id' => 'breadcrumb_color',
                    'type' => 'select',
                    'title' => __( 'Breadcrumbs text color', 'xstore' ),
                    'options' => array (
                        'dark' => __( 'Dark', 'xstore' ),
                        'white' => __( 'White', 'xstore' ),
                    ),
                    'default' => 'white'
                ),
                array (
                    'id' => 'return_to_previous',
                    'type' => 'switch',
                    'title' => __( '"Back to previous page" button', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'breadcrumb_padding',
                    'type' => 'spacing',
                    'title' => __( 'Breadcrumbs padding', 'xstore' ),
                    'output' => array('.page-heading, .et-header-overlap .page-heading, .et-header-overlap .page-heading.bc-size-small, .page-heading.bc-size-small'),
                    'units'          => array('em', 'px'),
                    'units_extended' => 'false',
                    'default' => ''
                ),
                array (
                    'id' => 'bc_title_font',
                    'type' => 'typography',
                    'title' => __( 'Breadcrumbs title font', 'xstore' ),
                    'output' => '.page-heading .title, .page-heading.bc-size-small .title',
                    'text-align' => false,
                    'text-transform' => true,
                ),
                array (
                    'id' => 'bc_breadcrumbs_font',
                    'type' => 'typography',
                    'title' => __( 'Breadcrumbs font', 'xstore' ),
                    'output' => '.woocommerce-breadcrumb, #breadcrumb, .bbp-breadcrumb, .woocommerce-breadcrumb a, #breadcrumb a, .bbp-breadcrumb a, .woocommerce-breadcrumb .delimeter, #breadcrumb .delimeter, .bbp-breadcrumb .delimeter, .page-heading.bc-type-left2 .back-history, .page-heading.bc-type-left2 .title, .page-heading.bc-type-left2 .woocommerce-breadcrumb a, .page-heading.bc-type-left2 .breadcrumbs a',
                    'text-align' => false,
                    'text-transform' => true,
                ),
                array (
                    'id' => 'bc_return_font',
                    'type' => 'typography',
                    'title' => __( '"Return to previous page" font', 'xstore' ),
                    'output' => '.page-heading .back-history',
                    'text-align' => false,
                    'text-transform' => true,
                ),
            ),
        ) );


        Redux::setSection( $opt_name, array(
            'title' => 'Footer',
            'id' => 'general-footer',
            'subsection' => true,
            'icon' => 'el-icon-cog',
            'fields' => array (
                array (
                    'id' => 'footer_columns',
                    'type' => 'select',
                    'title' => __( 'Footer columns', 'xstore' ),
                    'options' => array (
                        1 => __( '1 Column', 'xstore' ),
                        2 => __( '2 Columns', 'xstore' ),
                        3 => __( '3 Columns', 'xstore' ),
                        4 => __( '4 Columns', 'xstore' ),
                    ),
                    'default' => 4
                ),
                array (
                    'id' => 'footer_demo',
                    'type' => 'switch',
                    'title' => __( 'Show footer demo blocks', 'xstore' ),
                    'desc' => __( 'Will be shown if footer sidebars are empty', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'footer_fixed',
                    'type' => 'switch',
                    'title' => __( 'Footer fixed', 'xstore' ),
                    'default' => false,
                ),
                array (
                    'id' => 'to_top',
                    'type' => 'switch',
                    'title' => __( '"Back To Top" button', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'to_top_mobile',
                    'type' => 'switch',
                    'title' => __( '"Back To Top" button on mobile', 'xstore' ),
                    'default' => true,
                ),
            ),
        ));

        Redux::setSection( $opt_name, array(
            'title' => __( '404 page', 'xstore' ),
            'id' => 'general-page-not-found',
            'subsection' => true,
            'icon' => 'el-icon-cog',
            'fields' => array (
                array (
                    'id' => '404_text',
                    'type' => 'editor',
                    'title' => __( '404 page content', 'xstore' )
                ),
            ),
        ));

        Redux::setSection( $opt_name, array(
            'title' => __( 'Facebook Login', 'xstore' ),
            'id' => 'general-facebook',
            'subsection' => true,
            'icon' => 'el-icon-facebook',
            'fields' => array (
                array(
                    'id'   => 'fb_info',
                    'type' => 'info',
                    'desc' => __( 'To create Facebook APP ID follow the instructions <a href="https://developers.facebook.com/docs/apps/register" target="_blank">https://developers.facebook.com/docs/apps/register</a>', 'xstore' )
                ),
                array (
                    'id' => 'facebook_app_id',
                    'type' => 'text',
                    'title' => __( 'Facebook APP ID', 'xstore' )
                ),
                array (
                    'id' => 'facebook_app_secret',
                    'type' => 'text',
                    'title' => __( 'Facebook APP SECRET', 'xstore' )
                ),
            ),
        ));


        Redux::setSection( $opt_name, array(
            'title' => __( 'Share buttons', 'xstore' ),
            'id' => 'general-share',
            'subsection' => true,
            'icon' => 'el-icon-share',
            'fields' => array (
                array (
                    'id' => 'share_twitter',
                    'type' => 'switch',
                    'title' => __( 'Share twitter', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'share_facebook',
                    'type' => 'switch',
                    'title' => __( 'Share facebook', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'share_vk',
                    'type' => 'switch',
                    'title' => __( 'Share vk', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'share_pinterest',
                    'type' => 'switch',
                    'title' => __( 'Share pinterest', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'share_google',
                    'type' => 'switch',
                    'title' => __( 'Share google', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'share_mail',
                    'type' => 'switch',
                    'title' => __( 'Share mail', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'share_linkedin',
                    'type' => 'switch',
                    'title' => __( 'Share linkedin', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'share_whatsapp',
                    'type' => 'switch',
                    'title' => __( 'Share whatsapp', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'share_skype',
                    'type' => 'switch',
                    'title' => __( 'Share skype', 'xstore' ),
                    'default' => true,
                ),
            ),
        ));

        Redux::setSection( $opt_name, array(
            'title' => __( 'Styling', 'xstore' ),
            'id' => 'style',
            'icon' => 'el-icon-picture',
        ) );


        Redux::setSection( $opt_name, array(
            'title' => __( 'Content', 'xstore' ),
            'id' => 'style-content',
            'icon' => 'el-icon-picture',
            'subsection' => true,
            'fields' => array (
                array (
                    'id' => 'dark_styles',
                    'type' => 'switch',
                    'title' => __( 'Dark version', 'xstore' ),
                ),
                array (
                    'id' => 'activecol',
                    'type' => 'color',
                    'title' => __( 'Main Color', 'xstore' ),
                    'default' => '#d64444'
                ),
                array (
                    'id' => 'background_img',
                    'type' => 'background',
                    'output' => 'body',
                    'title' => __( 'Site Background', 'xstore' ),
                ),

                array (
                    'id' => 'container_bg',
                    'type' => 'color_rgba',
                    'title' => __( 'Container Background Color', 'xstore' ),
                    'output' => array(
                        'background-color' =>'.select2-results, .select2-drop, .select2-container .select2-choice, .form-control, .page-wrapper, .cart-popup-container, select, .quantity input[type="number"], .emodal, input[type="text"], input[type="email"], input[type="password"], input[type="tel"], textarea, #searchModal, .quick-view-popup, #etheme-popup, .et-wishlist-widget .wishlist-dropdown, textarea.form-control, textarea'
                    )
                ),
                array (
                    'id' => 'forms_inputs_bg',
                    'type' => 'color_rgba',
                    'title' => __( 'Form inputs color', 'xstore' ),
                    'output' => array(
                        'border-color' =>'.select2-results, .select2-drop, .select2-container .select2-choice, .form-control, select, .quantity input[type="number"], .emodal, input[type="text"], input[type="email"], input[type="password"], input[type="tel"], textarea, textarea.form-control, textarea',
                        'background-color' =>'.select2-results, .select2-drop, .select2-container .select2-choice, .form-control, select, .quantity input[type="number"], .emodal, input[type="text"], input[type="email"], input[type="password"], input[type="tel"], textarea, textarea.form-control, textarea, input[type="search"]'
                    )
                ),
            ),
        ));

        
        Redux::setSection( $opt_name, array(
            'title' => __( 'Navigation', 'xstore' ),
            'id' => 'style-nav',
            'icon' => 'el-icon-picture',
            'subsection' => true,
            'fields' => array (
                array (
                    'id' => 'menu_align',
                    'type' => 'select',
                    'title' => __( 'Menu links align', 'xstore' ),
                    'options' => array (
                        'center' => __( 'Center', 'xstore' ),
                        'left' => __( 'Left', 'xstore' ),
                        'right' => __( 'Right', 'xstore' ),
                    ),
                    'default' => 'center'
                ),
            ),
        ));


        Redux::setSection( $opt_name, array(
            'title' => __( 'Footer', 'xstore' ),
            'id' => 'style-footer',
            'subsection' => true,
            'icon' => 'el-icon-cog',
            'fields' => array (
                array (
                    'id' => 'footer_color',
                    'type' => 'select',
                    'title' => __( 'Footer text color scheme', 'xstore' ),
                    'options' => array (
                        'light' => __( 'Light', 'xstore' ),
                        'dark' => __( 'Dark', 'xstore' ),
                    ),
                    'default' => 'light'
                ),
                array (
                    'id' => 'footer-links',
                    'type' => 'link_color',
                    'title' => __( 'Footer Links', 'xstore' ),
                    'output' => array('.template-container .template-content .footer a, .template-container .template-content .footer .vc_wp_posts .widget_recent_entries li a')
                ),
                array (
                    'id' => 'footer_bg_color',
                    'type' => 'background',
                    'title' => __( 'Footer Background Color', 'xstore' ),
                    'output' => array(
                        'background' => 'footer.footer'
                    )
                ),
                array (
                    'id' => 'footer_padding',
                    'type' => 'spacing',
                    'title' => __( 'Footer padding', 'xstore' ),
                    'output' => array('.footer'),
                    'units'          => array('em', 'px'),
                    'units_extended' => 'false',
                    'default' => ''
                ),
            ),
        ));

        Redux::setSection( $opt_name, array(
            'title' => __( 'Copyrights', 'xstore' ),
            'id' => 'style-copyrights',
            'subsection' => true,
            'icon' => 'el-icon-cog',
            'fields' => array (
                array (
                    'id' => 'copyrights_color',
                    'type' => 'select',
                    'title' => __( 'Copyrights text color scheme', 'xstore' ),
                    'options' => array (
                        'light' => __( 'Light', 'xstore' ),
                        'dark' => __( 'Dark', 'xstore' ),
                    ),
                    'default' => 'light'
                ),
                array (
                    'id' => 'copyrights-links',
                    'type' => 'link_color',
                    'title' => __( 'Copyrights Links', 'xstore' ),
                    'output' => array('.footer-bottom a')
                ),
                array (
                    'id' => 'copyrights_bg_color',
                    'type' => 'background',
                    'title' => __( 'Copyrights Background Color', 'xstore' ),
                    'output' => array(
                        'background' => '.footer-bottom'
                    )
                ),
                array (
                    'id' => 'copyrights_padding',
                    'type' => 'spacing',
                    'title' => __( 'Copyrights padding', 'xstore' ),
                    'output' => array('.footer-bottom'),
                    'units'          => array('em', 'px'),
                    'units_extended' => 'false',
                    'default' => ''
                ),
            ),
        ));
        
        Redux::setSection( $opt_name, array(
            'title' => __( 'Custom CSS', 'xstore' ),
            'id' => 'style-custom_css',
            'icon' => 'el-icon-css',
            'subsection' => true,
            'fields' => array (
                array (
                    'id' => 'custom_css',
                    'type' => 'ace_editor',
                    'mode' => 'css',
                    'title' => __( 'Global Custom CSS', 'xstore' ),
                ),
                array (
                    'id' => 'custom_css_desktop',
                    'type' => 'ace_editor',
                    'mode' => 'css',
                    'title' => __( 'Custom CSS for desktop (992px+)', 'xstore' ),
                ),
                array (
                    'id' => 'custom_css_tablet',
                    'type' => 'ace_editor',
                    'mode' => 'css',
                    'title' => __( 'Custom CSS for tablet (768px - 991px)', 'xstore' ),
                ),
                array (
                    'id' => 'custom_css_wide_mobile',
                    'type' => 'ace_editor',
                    'mode' => 'css',
                    'title' => __( 'Custom CSS for mobile landscape (481px - 767px)', 'xstore' ),
                ),
                array (
                    'id' => 'custom_css_mobile',
                    'type' => 'ace_editor',
                    'mode' => 'css',
                    'title' => __( 'Custom CSS for mobile (0 - 420px)', 'xstore' ),
                ),
            ),
        ));

        Redux::setSection( $opt_name, array(
            'title' => __( 'Typography', 'xstore' ),
            'id' => 'typography',
            'icon' => 'el-icon-font',
        ));

        Redux::setSection( $opt_name, array(
            'title' => __( 'Page', 'xstore' ),
            'id' => 'typography-page',
            'icon' => 'el-icon-font',
            'subsection' => true,
            'fields' => array(
                array (
                    'id' => 'sfont',
                    'type' => 'typography',
                    'title' => __( 'Body Font', 'xstore' ),
                    'output' => 'body, .quantity input[type="number"], .page-wrapper, p',
                    'text-align' => false,
                    'text-transform' => true,
                ),
                array (
                    'id' => 'headings',
                    'type' => 'typography',
                    'title' => __( 'Headings', 'xstore' ),
                    'output' => 'h1, h2, h3, h4, h5, h6, .title h3, blockquote, .share-post .share-title, .sidebar-widget .tabs .tab-title, .widget-title, .related-posts .title span, .posts-slider article h2 a, .content-product .product-title a, table.cart .product-details a, .product_list_widget .product-title a, .woocommerce table.wishlist_table .product-name a, .comment-reply-title, .et-tabs .vc_tta-title-text, .single-product-right .product-information-inner .product_title, .single-product-right .product-information-inner h1.title, .post-heading h2 a, .sidebar .recent-posts-widget .post-widget-item h4 a, .et-tabs-wrapper .tabs .accordion-title span, .vc_tta-tabs .vc_tta-title-text',
                    'text-align' => false,
                    'font-size' => false,
                    'text-transform' => true,
                ),
            )
        ));


        Redux::setSection( $opt_name, array(
            'title' => __( 'Navigation', 'xstore' ),
            'id' => 'typography-menu',
            'icon' => 'el-icon-font',
            'subsection' => true,
            'fields' => array(
                array (
                    'id' => 'menu_level_1',
                    'type' => 'typography',
                    'title' => __( 'Menu first level font', 'xstore' ),
                    'output' => '.menu-wrapper .menu > li > a, .mobile-menu-wrapper .menu > li > a, .mobile-menu-wrapper .links li a, .secondary-menu-wrapper .menu > li > a, .secondary-title, .fullscreen-menu .menu > li > a, .fullscreen-menu .menu > li .inside > a',
                    'text-align' => false,
                    'text-transform' => true,
                ),
                array (
                    'id' => 'menu_level_2',
                    'type' => 'typography',
                    'title' => __( 'Menu second level', 'xstore' ),
                    'output' => '.item-design-mega-menu .nav-sublist-dropdown .item-level-1 > a, .secondary-menu-wrapper .nav-sublist-dropdown .menu-item-has-children.item-level-1 > a, .secondary-menu-wrapper .nav-sublist-dropdown .menu-widgets .widget-title, .fullscreen-menu .menu-item-has-children .nav-sublist-dropdown li a',
                    'text-align' => false,
                    'text-transform' => true,
                ),
                array (
                    'id' => 'menu_level_3',
                    'type' => 'typography',
                    'title' => __( 'Menu third level', 'xstore' ),
                    'output' => '.item-design-dropdown .nav-sublist-dropdown ul > li > a, .item-design-mega-menu .nav-sublist-dropdown .item-link, .secondary-menu-wrapper .nav-sublist-dropdown .menu-item-has-children .nav-sublist ul > li > a, .item-design-mega-menu .nav-sublist-dropdown .nav-sublist a, .fullscreen-menu .menu-item-has-children .nav-sublist-dropdown ul > li > a',
                    'text-align' => false,
                    'text-transform' => true,
                ),
            )
        ));



        if( current_theme_supports('woocommerce') ) {

            Redux::setSection( $opt_name, array(
                'title' => __( 'E-Commerce', 'xstore' ),
                'id' => 'shop',
                'icon' => 'el-icon-shopping-cart',
            ));

            Redux::setSection( $opt_name, array(
                'title' => __( 'Shop', 'xstore' ),
                'id' => 'shop-shop',
                'icon' => 'el-icon-shopping-cart',
                'subsection' => true,
                'fields' => array (
                    array (
                        'id' => 'cart_widget',
                        'type' => 'switch',
                        'title' => __( 'Enable cart widget in header', 'xstore' ),
                        'default' => true,
                    ),
                    array (
                        'id' => 'just_catalog',
                        'type' => 'switch',
                        'description' => __( 'Disable "Add To Cart" button and shopping cart', 'xstore' ),
                        'title' => __( 'Just Catalog', 'xstore' ),
                    ),
                    array (
                        'id' => 'top_toolbar',
                        'type' => 'switch',
                        'title' => __( 'Show products toolbar on the shop page', 'xstore' ),
                        'default' => true,
                    ),
                    array (
                        'id' => 'filters_columns',
                        'type' => 'select',
                        'title' => __( 'Widgets columns for filters area', 'xstore' ),
                        'options' => array (
                            2 => '2',
                            3 => '3',
                            4 => '4',
                            5 => '5',
                        ),
                        'default' => 3
                    ),
                    array (
                        'id' => 'filter_opened',
                        'type' => 'switch',
                        'title' => __( 'Open filter by default', 'xstore' ),
                        'default' => false,
                    ),
                    array (
                        'id' => 'cats_accordion',
                        'type' => 'switch',
                        'title' => __( 'Enable Navigation Accordion', 'xstore' ),
                        'default' => true,
                    ),
                    array (
                        'id' => 'out_of_icon',
                        'type' => 'switch',
                        'title' => __( 'Enable "Out of stock" icon', 'xstore' ),
                        'default' => true,
                    ),
                    array (
                        'id' => 'sale_icon',
                        'type' => 'switch',
                        'title' => __( 'Enable "Sale" icon', 'xstore' ),
                        'default' => true,
                    ),
                    array (
                        'id' => 'sale_percentage',
                        'type' => 'switch',
                        'title' => __( 'Show sale percentage', 'xstore' ),
                        'desc' => __( 'For simple and external product types', 'xstore' ),
                        'default' => false,
                    ),
                    array (
                        'id' => 'product_bage_banner',
                        'type' => 'editor',
                        'desc' => __( 'Upload image: png, jpg or gif file', 'xstore' ),
                        'title' => __( 'Product Page Banner', 'xstore' ),
                    ),
                    array (
                        'id' => 'empty_cart_content',
                        'type' => 'editor',
                        'title' => __( 'Text for empty cart', 'xstore' ),
                        'default' => '<h1 style="text-align: center;">YOUR SHOPPING CART IS EMPTY</h1>
<p style="text-align: center;">We invite you to get acquainted with an assortment of our shop.
Surely you can find something for yourself!</p> ',
                    ),
                    // array (
                    //     'id' => 'register_text',
                    //     'type' => 'editor',
                    //     'title' => 'Text for registration page',
                    //     'default' => 'text',
                    // ),
                ),
            ));

            Redux::setSection( $opt_name, array(
                'title' => __( 'Categories', 'xstore' ),
                'id' => 'shop-categories',
                'icon' => 'el-icon-shopping-cart',
                'subsection' => true,
                'fields' => array (
                    array (
                        'id' => 'cat_style',
                        'type' => 'select',
                        'title' => __( 'Categories style', 'xstore' ),
                        'options' => array (
                            'default' => __( 'Default', 'xstore' ),
                            'with-bg' => __( 'Title with background', 'xstore' ),
                            'zoom' => __( 'Zoom', 'xstore' ) ,
                            'diagonal' => __( 'Diagonal', 'xstore' ),
                            'classic' => __( 'Classic', 'xstore' ),
                        ),
                        'default' => 'default'
                    ),
                    array (
                        'id' => 'cat_text_color',
                        'type' => 'select',
                        'title' => __( 'Categories text color', 'xstore' ),
                        'options' => array (
                            'dark' => __( 'Dark', 'xstore' ),
                            'white' => __( 'Light', 'xstore' ),
                        ),
                        'default' => 'dark'
                    ),
                    array (
                        'id' => 'cat_valign',
                        'type' => 'select',
                        'title' => __( 'Text vertical align', 'xstore' ),
                        'options' => array (
                            'center' => __( 'Center', 'xstore' ),
                            'top' => __( 'Top', 'xstore' ),
                            'bottom' => __( 'Bottom', 'xstore' ),
                        ),
                        'default' => 'center'
                    ),
                ),
            ));

            Redux::setSection( $opt_name, array(
                'title' => __( 'Products Page Layout', 'xstore' ),
                'id' => 'shop-product_grid',
                'icon' => 'el-icon-view-mode',
                'subsection' => true,
                'fields' => array (
                    array (
                        'id' => 'view_mode',
                        'type' => 'select',
                        'title' => __( 'Products view mode', 'xstore' ),
                        'options' => array (
                            'grid_list' => __( 'Grid/List', 'xstore' ),
                            'list_grid' => __( 'List/Grid', 'xstore' ),
                            'grid' => __( 'Only Grid', 'xstore' ),
                            'list' => __( 'Only List', 'xstore' ),
                        ),
                        'default' => 'grid_list'
                    ),
                    array (
                        'id' => 'prodcuts_per_row',
                        'type' => 'select',
                        'title' => __( 'Products per row', 'xstore' ),
                        'options' => array (
                            1 => '1',
                            2 => '2',
                            3 => '3',
                            4 => '4',
                            5 => '5',
                            6 => '6',
                        ),
                        'default' => 3
                    ),
                    array (
                        'id' => 'products_per_page',
                        'type' => 'text',
                        'title' => __( 'Products per page', 'xstore' ),
                    ),
                    array (
                        'id' => 'et_ppp_options',
                        'type' => 'text',
                        'title' => __( 'Per page variants separated by commas', 'xstore' ),
                        'default' => __( '12,24,36,-1', 'xstore' ),
                        'desc' => __( 'For example: 12,24,36,-1. Set -1 to show all products', 'xstore' )
                    ),
                    array (
                        'id' => 'grid_sidebar',
                        'type' => 'image_select',
                        'desc' => __( 'Sidebar position', 'xstore' ),
                        'title' => __( 'Layout', 'xstore' ),
                        'options' => array (
                            'without' => array (
                                'alt' => __( 'full width', 'xstore' ),
                                'img' => ETHEME_CODE_IMAGES . 'layout/full-width.png',
                            ),
                            'left' => array (
                                'alt' => __( 'Left Sidebar', 'xstore' ),
                                'img' => ETHEME_CODE_IMAGES . 'layout/left-sidebar.png',
                            ),
                            'right' => array (
                                'alt' => __( 'Right Sidebar', 'xstore' ),
                                'img' => ETHEME_CODE_IMAGES . 'layout/right-sidebar.png',
                            ),
                        ),
                        'default' => 'left'
                    ),
                    array (
                        'id' => 'shop_sticky_sidebar',
                        'type' => 'switch',
                        'title' => __( 'Enable sticky sidebar', 'xstore' ),
                        'default' => false,
                    ),
                    array (
                        'id' => 'sidebar_for_mobile',
                        'type' => 'select',
                        'title' => __( 'Sidebar position for mobile', 'xstore' ),
                        'options' => array (
                            'top' => __( 'Top', 'xstore' ),
                            'bottom' => __( 'Bottom', 'xstore' ),
                        ),
                        'default' => 'top',
                    ),
                    array (
                        'id' => 'shop_sidebar_hide_mobile',
                        'type' => 'switch',
                        'title' => __( 'Hide sidebar for mobile devices', 'xstore' ),
                    ),
                    array (
                        'id' => 'shop_full_width',
                        'type' => 'switch',
                        'title' => __( 'Full width', 'xstore' ),
                    ),
                    array (
                        'id' => 'products_masonry',
                        'type' => 'switch',
                        'title' => __( 'Products masonry', 'xstore' ),
                        'default' => false,
                    ),
                    array (
                        'id' => 'product_img_hover',
                        'type' => 'select',
                        'title' => __( 'Image hover effect', 'xstore' ),
                        'options' => array (
                            'disable' => __( 'Disable', 'xstore' ),
                            'swap' => __( 'Swap', 'xstore' ),
                            'slider' => __( 'Images Slider', 'xstore' ),
                        ),
                        'default' => 'slider',
                    ),
                    array (
                        'id' => 'product_view',
                        'type' => 'select',
                        'title' => __( 'Buttons hover', 'xstore' ),
                        'options' => array (
                            'disable' => __( 'Disable', 'xstore' ),
                            'default' => __( 'Default', 'xstore' ),
                            'mask3' => __( 'Buttons on hover middle', 'xstore' ),
                            'mask' => __( 'Buttons on hover bottom', 'xstore' ),
                            'mask2' => __( 'Buttons on hover right', 'xstore' ),
                            'info' => __( 'Information mask', 'xstore' ),
                            'booking' => __( 'Booking', 'xstore' ),
                        ),
                        'default' => 'disable',
                    ),
                    array (
                        'id' => 'product_view_color',
                        'type' => 'select',
                        'title' => __( 'Hover Color Scheme', 'xstore' ),
                        'options' => array (
                            'white' => __( 'White', 'xstore' ),
                            'dark' => __( 'Dark', 'xstore' ),
                            'transparent' => 'Transparent',
                        ),
                        'default' => 'white',
                        'required' => array(
                            array('product_view','equals', array('info','mask','mask2')),
                        )
                    ),
                    array (
                        'id' => 'hide_buttons_mobile',
                        'type' => 'switch',
                        'title' => __( 'Hide hover buttons on mobile', 'xstore' ),
                        'default' => false,
                    ),
                    array (
                        'id' => 'product_page_productname',
                        'type' => 'switch',
                        'title' => __( 'Show product name', 'xstore' ),
                        'default' => true,
                    ),
                    array (
                        'id' => 'product_page_cats',
                        'type' => 'switch',
                        'title' => __( 'Show product categories', 'xstore' ),
                    ),
                    array (
                        'id' => 'product_page_price',
                        'type' => 'switch',
                        'title' => __( 'Show Price', 'xstore' ),
                        'default' => true,
                    ),
                    array (
                        'id' => 'product_page_addtocart',
                        'type' => 'switch',
                        'title' => __( 'Show "Add to cart" button', 'xstore' ),
                        'default' => true,
                    ),
                ),
            ));


            Redux::setSection( $opt_name, array(
                'title' => __( 'Single Product Page', 'xstore' ),
                'id' => 'shop-single_product',
                'subsection' => true,
                'icon' => 'el-icon-indent-left',
                'fields' => array (
                    array (
                        'id' => 'single_sidebar',
                        'type' => 'image_select',
                        'title' => __( 'Sidebar position', 'xstore' ),
                        'options' => array (
                            'without' => array (
                                'alt' => __( 'Without Sidebar', 'xstore' ),
                                'img' => ETHEME_CODE_IMAGES . 'layout/full-width.png',
                            ),
                            'left' => array (
                                'alt' => __( 'Left Sidebar', 'xstore' ),
                                'img' => ETHEME_CODE_IMAGES . 'layout/left-sidebar.png',
                            ),
                            'right' => array (
                                'alt' => __( 'Right Sidebar', 'xstore' ),
                                'img' => ETHEME_CODE_IMAGES . 'layout/right-sidebar.png',
                            ),
                        ),
                        'default' => 'without'
                    ),
                    array (
                        'id' => 'single_layout',
                        'type' => 'image_select',
                        'title' => __( 'Page Layout', 'xstore' ),
                        'options' => array (
                            'small' => array (
                                'alt' => __( 'Small', 'xstore' ),
                                'img' => ETHEME_CODE_IMAGES . 'layout/product-small.png',
                            ),
                            'default' => array (
                                'alt' => __( 'Default', 'xstore' ),
                                'img' => ETHEME_CODE_IMAGES . 'layout/product-medium.png',
                            ),
                            'xsmall' => array (
                                'alt' => __( 'Thin description', 'xstore' ),
                                'img' => ETHEME_CODE_IMAGES . 'layout/product-thin.png',
                            ),
                            'large' => array (
                                'alt' => __( 'Large', 'xstore' ),
                                'img' => ETHEME_CODE_IMAGES . 'layout/product-large.png',
                            ),
                            'fixed' => array (
                                'alt' => __( 'Fixed content', 'xstore' ),
                                'img' => ETHEME_CODE_IMAGES . 'layout/product-fixed.png',
                            ),
                            'center' => array (
                                'alt' => __( 'Image center', 'xstore' ),
                                'img' => ETHEME_CODE_IMAGES . 'layout/product-center.png',
                            ),
                            'wide' => array (
                                'alt' => __( 'Wide', 'xstore' ),
                                'img' => ETHEME_CODE_IMAGES . 'layout/product-wide.png',
                            ),
                            'right' => array (
                                'alt' => __( 'Image right', 'xstore' ),
                                'img' => ETHEME_CODE_IMAGES . 'layout/product-right.png',
                            ),
                            'booking' => array (
                                'alt' => __( 'Booking', 'xstore' ),
                                'img' => ETHEME_CODE_IMAGES . 'layout/product-booking.png',
                            ),
                        ),
                        'default' => 'default'
                    ),
                    array (
                        'id' => 'single_product_hide_sidebar',
                        'type' => 'switch',
                        'title' => __( 'Hide sidebar on mobile', 'xstore' ),
                        'default' => false
                    ),
                    array (
                        'id' => 'fixed_images',
                        'type' => 'switch',
                        'title' => __( 'Fixed product image', 'xstore' ),
                        'default' => false,
                        'required' => array(
                            array('single_layout','equals', array('small', 'default', 'xsmall', 'large', 'wide', 'right')),
                        )
                    ),
                    array (
                        'id' => 'fixed_content',
                        'type' => 'switch',
                        'title' => __( 'Fixed product content', 'xstore' ),
                        'default' => false,
                        'required' => array(
                            array('single_layout','equals', array('small', 'default', 'xsmall', 'large', 'wide', 'right')),
                        )
                    ),
                    array (
                        'id' => 'product_name_signle',
                        'type' => 'switch',
                        'title' => __( 'Show product name above the price', 'xstore' ),
                        'default' => true,
                    ),
                    array (
                        'id' => 'upsell_location',
                        'type' => 'select',
                        'title' => __( 'Location of upsell products', 'xstore' ),
                        'options' => array (
                            'sidebar' => __( 'Sidebar', 'xstore' ),
                            'after_content' => __( 'After content', 'xstore' ),
                        ),
                    ),
                    array (
                        'id' => 'ajax_add_to_cart',
                        'type' => 'switch',
                        'title' => __( 'AJAX add to cart for simple products', 'xstore' ),
                        'default' => true,
                    ),
                    array (
                        'id' => 'product_photoswipe',
                        'type' => 'switch',
                        'title' => __( 'Lightbox for product images', 'xstore' ),
                        'default' => true,
                    ),
                    array (
                        'id' => 'show_related',
                        'type' => 'switch',
                        'title' => __( 'Display related products', 'xstore' ),
                        'default' => true,
                    ),
                    array (
                        'id' => 'related_limit',
                        'type' => 'text',
                        'title' => __( 'Display related products', 'xstore' ),
                        'default' => 10,
                        'required' => array(
                            array('show_related','equals', true),
                        )
                    ),
                    array (
                        'id' => 'thumbs_slider',
                        'type' => 'switch',
                        'title' => __( 'Enable slider for gallery thumbnails', 'xstore' ),
                        'default' => true,
                    ),
                    array (
                        'id' => 'product_posts_links',
                        'type' => 'switch',
                        'title' => __( 'Show Next/Previous product navigation', 'xstore' ),
                        'default' => true,
                    ),
                    array (
                        'id' => 'share_icons',
                        'type' => 'switch',
                        'title' => __( 'Show share buttons', 'xstore' ),
                        'default' => true,
                    ),
                    array (
                        'id' => 'tabs_type',
                        'type' => 'select',
                        'title' => __( 'Tabs type', 'xstore' ),
                        'options' => array (
                            'tabs-default' => __( 'Default', 'xstore' ),
                            'left-bar' => __( 'Left Bar', 'xstore' ),
                            'accordion' => __( 'Accordion', 'xstore' ),
                            'disable' => __( 'Disable', 'xstore' ),
                        ),
                        'default' => 'tabs-default'
                    ),
                    array (
                        'id' => 'tabs_scroll',
                        'type' => 'switch',
                        'title' => __( 'Tabs content scroll', 'xstore' ),
                        'default' => false,
                        'required' => array(
                            array('tabs_type', 'equals', 'accordion'),
                        )
                    ),
                    array(
                        'id'        => 'tab_height',
                        'type'      => 'slider',
                        'title'     => __('Tab content height', 'redux-framework-demo'),
                        "default"   => 250,
                        "min"       => 50,
                        "step"      => 1,
                        "max"       => 800,
                        'display_value' => 'label',
                        'required' => array(
                            array('tabs_type', 'equals', 'accordion'),
                            array('tabs_scroll', 'equals', true),
                        )
                    ),
                    array (
                        'id' => 'tabs_location',
                        'type' => 'select',
                        'title' => __( 'Location of product tabs', 'xstore' ),
                        'options' => array (
                            'after_image' => __( 'Next to image', 'xstore' ),
                            'after_content' => __( 'Under content', 'xstore' ),
                        ),
                        'default' => 'after_content',
                        'required' => array(
                            array('tabs_type','!=', 'disable'),
                        )
                    ),
                    array (
                        'id' => 'reviews_position',
                        'type' => 'select',
                        'title' => __( 'Reviews position', 'xstore' ),
                        'options' => array (
                            'tabs' => __( 'Tabs', 'xstore' ),
                            'outside' => __( 'Next to tabs', 'xstore' ),
                        ),
                        'default' => 'tabs',
                        'required' => array(
                            array('tabs_type','!=', 'disable'),
                        )
                    ),
                    array (
                        'id' => 'custom_tab_title',
                        'type' => 'text',
                        'title' => __( 'Custom Tab Title', 'xstore' ),
                        'required' => array(
                            array('tabs_type','!=', 'disable'),
                        ),
                    ),
                    array (
                        'id' => 'custom_tab',
                        'type' => 'editor',
                        'desc' => __( 'Enter custom content you would like to output to the product custom tab (for all products)', 'xstore' ),
                        'title' => __( 'Custom tab content', 'xstore' ),
                        'required' => array(
                            array('tabs_type','!=', 'disable'),
                        ),
                    ),
                ),
            ));


            Redux::setSection( $opt_name, array(
                'title' => __( 'Quick View', 'xstore' ),
                'id' => 'shop-quick_view',
                'subsection' => true,
                'icon' => 'el-icon-zoom-in',
                'fields' => array (
                    array (
                        'id' => 'quick_view',
                        'type' => 'switch',
                        'title' => __( 'Enable Quick View', 'xstore' ),
                        'default' => true,
                    ),
                    array (
                        'id' => 'quick_images',
                        'type' => 'select',
                        'title' => __( 'Product images', 'xstore' ),
                        'options' => array (
                            'slider' => __( 'Slider', 'xstore' ),
                            'single' => __( 'Single', 'xstore' ),
                        ),
                        'default' => 'slider',
                        'required' => array(
                            array('quick_view','equals', true),
                        )
                    ),
                    array (
                        'id' => 'quick_view_layout',
                        'type' => 'select',
                        'title' => __( 'Quick view layout', 'xstore' ),
                        'options' => array (
                            'default' => __( 'Default', 'xstore' ),
                            'centered' => __( 'Centered', 'xstore' ),
                        ),
                        'default' => 'default',
                        'required' => array(
                            array('quick_view','equals', true),
                        )
                    ),
                    array (
                        'id' => 'quick_product_name',
                        'type' => 'switch',
                        'title' => __( 'Product name', 'xstore' ),
                        'default' => true,
                        'required' => array(
                            array('quick_view','equals', true),
                        ),
                    ),
                    array (
                        'id' => 'quick_categories',
                        'type' => 'switch',
                        'title' => __( 'Product categories', 'xstore' ),
                        'default' => true,
                        'required' => array(
                            array('quick_view','equals', true),
                        ),
                    ),
                    array (
                        'id' => 'quick_price',
                        'type' => 'switch',
                        'title' => __( 'Price', 'xstore' ),
                        'default' => true,
                        'required' => array(
                            array('quick_view','equals', true),
                        ),
                    ),
                    array (
                        'id' => 'quick_rating',
                        'type' => 'switch',
                        'title' => __( 'Product star rating', 'xstore' ),
                        'default' => true,
                        'required' => array(
                            array('quick_view','equals', true),
                        ),
                    ),
                    array (
                        'id' => 'quick_descr',
                        'type' => 'switch',
                        'title' => __( 'Short description', 'xstore' ),
                        'default' => true,
                        'required' => array(
                            array('quick_view','equals', true),
                        ),
                    ),
                    array (
                        'id' => 'quick_add_to_cart',
                        'type' => 'switch',
                        'title' => __( 'Add to cart', 'xstore' ),
                        'default' => true,
                        'required' => array(
                            array('quick_view','equals', true),
                        ),
                    ),
                    array (
                        'id' => 'quick_share',
                        'type' => 'switch',
                        'title' => __( 'Share icons', 'xstore' ),
                        'default' => true,
                        'required' => array(
                            array('quick_view','equals', true),
                        ),
                    ),
                    array (
                        'id' => 'product_link',
                        'type' => 'switch',
                        'title' => __( 'Product link', 'xstore' ),
                        'default' => true,
                        'required' => array(
                            array('quick_view','equals', true),
                        ),
                    ),

                ),
            ));



            Redux::setSection( $opt_name, array(
                'title' => __( 'Promo Popup', 'xstore' ),
                'id' => 'shop-promo_popup',
                'subsection' => true,
                'icon' => 'el-icon-tag',
                'fields' => array (
                    array (
                        'id' => 'promo_popup',
                        'type' => 'switch',
                        'operator' => 'and',
                        'title' => __( 'Enable promo popup', 'xstore' ),
                        'default' => true,
                    ),
                    array (
                        'id' => 'promo_auto_open',
                        'type' => 'switch',
                        'title' => __( 'Open popup on enter', 'xstore' ),
                        'required' => array(
                            array('promo_popup','equals', true),
                        ),
                    ),
                    array (
                        'id' => 'promo_open_scroll',
                        'type' => 'switch',
                        'title' => __( 'Open when scrolled to the bottom of the page', 'xstore' ),
                        'required' => array(
                            array('promo_auto_open','equals', true),
                        ),
                    ),
                    array (
                        'id' => 'promo_link',
                        'type' => 'switch',
                        'operator' => 'and',
                        'title' => __( 'Show link in the top bar', 'xstore' ),
                        'default' => true,
                        'required' => array(
                            array('promo_popup','equals', true),
                        ),
                    ),
                    array (
                        'id' => 'promo-link-text',
                        'type' => 'text',
                        'title' => __( 'Promo link text', 'xstore' ),
                        'default' => __( 'Newsletter', 'xstore' ),
                        'required' => array(
                            array('promo_popup','equals', true),
                        ),
                    ),
                    array (
                        'id' => 'pp_content',
                        'type' => 'editor',
                        'operator' => 'and',
                        'title' => __( 'Popup content', 'xstore' ),
                        'default' => '<p>You can add any HTML here (admin -&gt; Theme Options -&gt; E-Commerce -&gt; Promo Popup).<br /> We suggest you create a static block and put it here using shortcode</p>',
                        'required' => array(
                            array('promo_popup','equals', true),
                        ),
                    ),
                    array (
                        'id' => 'pp_width',
                        'type' => 'text',
                        'operator' => 'and',
                        'title' => __( 'Popup width', 'xstore' ),
                        'required' => array(
                            array('promo_popup','equals', true),
                        ),
                    ),
                    array (
                        'id' => 'pp_height',
                        'type' => 'text',
                        'operator' => 'and',
                        'title' => __( 'Popup height', 'xstore' ),
                        'required' => array(
                            array('promo_popup','equals', true),
                        ),
                    ),
                    array (
                        'id' => 'pp_bg',
                        'type' => 'background',
                        'title' => __( 'Popup background', 'xstore' ),
                        'required' => array(
                            array('promo_popup','equals', true),
                        ),
                    ),
                ),
            ));

        }

        Redux::setSection( $opt_name, array(
            'title' => __( 'Blog & Portfolio', 'xstore' ),
            'id' => 'blog',
            'icon' => 'el-icon-wordpress',
        ));

        Redux::setSection( $opt_name, array(
            'title' => __( 'Blog Layout', 'xstore' ),
            'id' => 'blog-blog_page',
            'subsection' => true,
            'icon' => 'el-icon-wordpress',
            'fields' => array (
                array (
                    'id' => 'blog_layout',
                    'type' => 'image_select',
                    'title' => __( 'Blog Layout', 'xstore' ),
                    'options' => array(
                        'default' => array(
                            'title' => __( 'Default', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'blog/posts1-1.png',
                        ),
                        'center' => array(
                            'title' => __( 'Center', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'blog/posts-center.png',
                        ),
                        'grid' => array(
                            'title' => __( 'Grid', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'blog/posts2-1.png',
                        ),
                        'timeline' => array(
                            'title' => __( 'Timeline', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'blog/posts5-1.png',
                        ),
                        'timeline2' => array(
                            'title' => __( 'Timeline 2', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'blog/timeline2.png',
                        ),
                        'small' => array(
                            'title' => __( 'List', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'blog/posts3-1.png',
                        ),
                        'chess' => array(
                            'title' => __( 'Chess', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'blog/posts-chess.png',
                        ),
                        'framed' => array(
                            'title' => __( 'Framed', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'blog/posts-framed.png',
                        ),
                        'with-author' => array(
                            'title' => __( 'With author', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'blog/posts-with-author.png',
                        ),
                    ),
                    'default' => 'default',
                ),
                array (
                    'id' => 'blog_columns',
                    'type' => 'select',
                    'title' => __( 'Columns', 'xstore' ),
                    'options' => array (
                        2 => '2',
                        3 => '3',
                        4 => '4',
                    ),
                    'default' => 3,
                    'required' => array(
                        array('blog_layout','equals', array('grid')),
                    ),
                ),
                array (
                    'id' => 'blog_full_width',
                    'type' => 'switch',
                    'title' => __( 'Full width', 'xstore' ),
                    'required' => array(
                        array('blog_layout','equals', array('grid')),
                    ),
                ),
                array (
                    'id' => 'blog_hover',
                    'type' => 'select',
                    'title' => __( 'Blog image hover', 'xstore' ),
                    'options' => array (
                        'default' => __( 'Default', 'xstore' ),
                        'zoom' => __( 'Zoom', 'xstore' ),
                        'animated' => __( 'Animated', 'xstore' ),
                    ),
                    'default' => 'default',
                ),
                array (
                    'id' => 'blog_byline',
                    'type' => 'switch',
                    'title' => __( 'Show "byline" on the blog', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'read_more',
                    'type' => 'switch',
                    'title' => __( 'Show "Continue reading link"', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'views_counter',
                    'type' => 'switch',
                    'title' => __( 'Enable views counter', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'blog_sidebar',
                    'type' => 'image_select',
                    'title' => __( 'Sidebar position', 'xstore' ),
                    'options' => array (
                        'without' => array (
                            'alt' => __( 'Without Sidebar', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'layout/full-width.png',
                        ),
                        'left' => array (
                            'alt' => __( 'Left Sidebar', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'layout/left-sidebar.png',
                        ),
                        'right' => array (
                            'alt' => __( 'Right Sidebar', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'layout/right-sidebar.png',
                        ),
                    ),
                    'default' => 'right'
                ),
                array (
                    'id' => 'blog_pagination_align',
                    'type' => 'select',
                    'title' => __( 'Pagination align', 'xstore' ),
                    'options' => array (
                        'left' => __( 'Left', 'xstore' ),
                        'center' => __( 'Center', 'xstore' ),
                        'right' => __( 'Right', 'xstore' ),
                    ),
                    'default' => 'right'
                ),
                array (
                    'id' => 'sticky_sidebar',
                    'type' => 'switch',
                    'title' => __( 'Enable sticky sidebar', 'xstore' ),
                    'default' => false,
                ),
                array (
                    'id' => 'excerpt_length',
                    'type' => 'text',
                    'title' => __( 'Excerpt length (words)', 'xstore' ),
                    'default' => 25,
                ),
                array (
                    'id' => 'blog_images_size',
                    'type' => 'text',
                    'title' => __( 'Images sizes for blog', 'xstore' ),
                    'subtitle' => __( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'js_composer' ),
                    'default' => 'large',
                ),
                array (
                    'id' => 'blog_related_images_size',
                    'type' => 'text',
                    'title' => __( 'Images sizes for related articles', 'xstore' ),
                    'subtitle' => __( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'js_composer' ),
                    'default' => 'medium',
                ),
            ),
        ));

        Redux::setSection( $opt_name, array(
            'title' => __( 'Single post', 'xstore' ),
            'id' => 'blog-single-post',
            'subsection' => true,
            'icon' => 'el-icon-wordpress',
            'fields' => array (
                array (
                    'id' => 'post_template',
                    'type' => 'image_select',
                    'title' => __( 'Post template', 'xstore' ),
                    'options' => array (
                        'default' => array(
                            'title' => __( 'Default', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'blog/3.png',
                        ),
                        'full-width' => array(
                            'title' => __( 'Large', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'blog/2.png',
                        ),
                        'large' => array(
                            'title' => __( 'Full width', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'blog/1.png',
                        ),
                        'large2' => array(
                            'title' => __( 'Full width centered', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'blog/5.png',
                        ),
                        'framed' => array(
                            'title' => __( 'Framed', 'xstore' ),
                            'img' => ETHEME_CODE_IMAGES . 'blog/6.png',
                        ),
                    ),
                    'default' => 'default'
                ),
                array (
                    'id' => 'blog_featured_image',
                    'type' => 'switch',
                    'title' => __( 'Display featured image on single post', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'post_share',
                    'type' => 'switch',
                    'operator' => 'and',
                    'title' => __( 'Show Share buttons', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'about_author',
                    'type' => 'switch',
                    'operator' => 'and',
                    'title' => __( 'Show About Author block', 'xstore' ),
                    'default' => false,
                ),
                array (
                    'id' => 'posts_links',
                    'type' => 'switch',
                    'title' => __( 'Posts previous/next buttons', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'post_related',
                    'type' => 'switch',
                    'operator' => 'and',
                    'title' => __( 'Show Related posts', 'xstore' ),
                    'default' => true,
                ),
                array (
                    'id' => 'related_query',
                    'type' => 'select',
                    'title' => __( 'Related query type', 'xstore' ),
                    'options' => array (
                        'categories' => __( 'Categories', 'xstore' ),
                        'tags' => __( 'Tags', 'xstore' ),
                    ),
                    'default' => 'categories',
                    'required' => array(
                        array('post_related','equals', true),
                    ),
                ),

            ),
        ));



        Redux::setSection( $opt_name, array(
            'title' => __( 'Portfolio', 'xstore' ),
            'id' => 'blog-portfolioo',
            'subsection' => true,
            'icon' => 'el-icon-briefcase',
            'fields' => array (
                array (
                    'id' => 'portfolio_style',
                    'type' => 'select',
                    'title' => __( 'Project grid style', 'xstore' ),
                    'options' => array (
                        'default' => __( 'With title', 'xstore' ),
                        'classic' => __( 'Classic', 'xstore' ),
                    ),
                    'default' => 'default'
                ),
                array (
                    'id' => 'portfolio_fullwidth',
                    'type' => 'switch',
                    'title' => __( 'Full width portfolio', 'xstore' ),
                    'default' => false
                ),
                array (
                    'id' => 'port_first_wide',
                    'type' => 'switch',
                    'title' => __( 'Make first project wide', 'xstore' ),
                    'default' => false
                ),
                array (
                    'id' => 'portfolio_images_size',
                    'type' => 'text',
                    'title' => __( 'Images sizes for portfolio', 'xstore' ),
                    'subtitle' => __( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'js_composer' ),
                    'default' => 'large',
                ),
                array (
                    'id' => 'portfolio_columns',
                    'type' => 'select',
                    'title' => __( 'Columns', 'xstore' ),
                    'options' => array (
                        2 => '2',
                        3 => '3',
                        4 => '4',
                        5 => '5',
                        6 => '6',
                    ),
                    'default' => 3
                ),
                array (
                    'id' => 'portfolio_margin',
                    'type' => 'select',
                    'title' => __( 'Portfolio item spacing', 'xstore' ),
                    'options' => array (
                        1 => '0',
                        5 => '5',
                        10 => '10',
                        15 => '15',
                        20 => '20',
                        30 => '30',
                    ),
                    'default' => 15
                ),
                array (
                    'id' => 'portfolio_count',
                    'type' => 'text',
                    'desc' => __( 'Use -1 to show all items', 'xstore' ),
                    'title' => __( 'Items per page', 'xstore' ),
                ),
            ),
        ));


        Redux::setSection( $opt_name, array(
            'title' => __( 'Import / Export', 'xstore' ),
            'id' => 'import',
            'icon'   => 'el-icon-refresh',
        ));

        Redux::setSection( $opt_name, array(
            'title' => __( 'Dummy content', 'xstore' ),
            'id' => 'import-dummy',
            'subsection' => true,
            'icon' => 'el-icon-inbox',
            'fields' => array (
                array(
                    'id'         => 'dummy-content',
                    'type'       => 'dummy_content',
                    'title'      => __( 'Install Dummy content', 'xstore' )
                ),
            )
        ));

        Redux::setSection( $opt_name, array(
            'title'  => esc_html__( 'Options', 'xstore' ),
            'desc'   => esc_html__( 'Import and Export your theme settings from file, text or URL.', 'xstore' ),
            'id' => 'import-export',
            'subsection' => true,
            'icon'   => 'el-icon-refresh',
            'fields' => array(
                array(
                    'id'         => 'opt-import-export',
                    'type'       => 'import_export',
                    'title'      => __( 'Import Export', 'xstore' ),
                    'subtitle'   => __( 'Save and restore your theme options', 'xstore' ),
                    'full_width' => false,
                ),
            ),
        ));


        /*
         * <--- END SECTIONS
         */
    }

    add_action( 'after_setup_theme', 'etheme_redux_init', 1 );
}


// If Redux is running as a plugin, this will remove the demo notice and links
add_action( 'redux/loaded', 'remove_demo' );

// Remove the demo link and the notice of integrated demo from the redux-framework plugin

if ( ! function_exists( 'remove_demo' ) ) {
    function remove_demo() {
        // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
        if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
            remove_filter( 'plugin_row_meta', array(
                ReduxFrameworkPlugin::instance(),
                'plugin_metalinks'
            ), null, 2 );

            // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
            remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
        }
    }
}


