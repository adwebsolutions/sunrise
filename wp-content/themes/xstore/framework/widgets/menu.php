<?php  if ( ! defined('ETHEME_FW')) exit('No direct script access allowed');

// **********************************************************************// 
// ! Menu Widget
// **********************************************************************// 
class ETheme_Menu_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'etheme_widget_menu', 'description' => esc_html__( "Menu", 'xstore') );
        parent::__construct('etheme-menu', '8theme - '.__('Menu', 'xstore'), $widget_ops);
        $this->alt_option_name = 'etheme_widget_menu';
    }

    function widget($args, $instance) {
        extract($args);


        $title = apply_filters('widget_title', empty($instance['title']) ? false : $instance['title']);
        if ( empty( $instance['number'] ) || !$number = (int) $instance['number'] )
            $number = 10;
        else if ( $number < 1 )
            $number = 1;
        else if ( $number > 15 )
            $number = 15;

        $menu = (!empty($instance['menu'])) ? $instance['menu'] : '';
        $style = (!empty($instance['style'])) ? $instance['style'] : '';
        $align = (!empty($instance['align'])) ? $instance['align'] : '';
        $class = (!empty($instance['class'])) ? $instance['class'] : '';

        echo $before_widget;
        if(!$title == '' ){
            echo $before_title;
            echo $title;
            echo $after_title;
        }

        echo etheme_menu_shortcode(array(
            'menu' => $menu,
            'style' => $style,
            'align' => $align,
            'class' => $class,
        ));

    echo $after_widget;

    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['menu']  = strip_tags($new_instance['menu']);
        $instance['style'] = strip_tags($new_instance['style']);
        $instance['align'] = strip_tags($new_instance['align']);
        $instance['class'] = strip_tags($new_instance['class']);
       

        return $instance;
    }

    function form( $instance ) {
        $title = @esc_attr($instance['title']);
        $menu  = @esc_attr($instance['menu']);
        $style = @esc_attr($instance['style']);
        $align = @esc_attr($instance['align']);
        $class = @esc_attr($instance['class']);

        $menus = wp_get_nav_menus();
        $menu_params ='';
        foreach ( $menus as $menu_param ) {
            $menu_params[$menu_param->term_id] = $menu_param->name;
        }

        etheme_widget_input_text(__('Title', 'xstore'), $this->get_field_id('title'),$this->get_field_name('title'), $title );
        
        etheme_widget_input_dropdown(__('Menu', 'xstore'), $this->get_field_id('menu'),$this->get_field_name('menu'), $menu, $menu_params );

        etheme_widget_input_dropdown(__('Style', 'xstore'), $this->get_field_id('style'),$this->get_field_name('style'), $style, array(
            'vertical'   => 'Vertical',
            'horizontal' => 'Horizontal',
        ));

        etheme_widget_input_dropdown(__('Align', 'xstore'), $this->get_field_id('align'),$this->get_field_name('align'), $align, array(
            'left'   => 'Left',
            'center' => 'Center',
            'right'  => 'Right',
        ));

        etheme_widget_input_text(__('Extra class name', 'xstore'), $this->get_field_id('class'),$this->get_field_name('class'), $class);
    }
}