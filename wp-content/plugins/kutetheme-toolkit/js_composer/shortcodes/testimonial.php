<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
vc_map( array(
    "name" => __( "KT Testimonial", 'kutetheme'),
    "base" => "kt_testimonial",
    "category" => __('Kute theme', 'kutetheme' ),
    "description" => __( 'Display a Testimonial slide', 'kutetheme' ),
    "params" => array(
        array(
            "type"        => "textfield",
            "heading"     => __( "Title", 'kutetheme' ),
            "param_name"  => "title",
            "admin_label" => true
        ),
        array(
            'type'        => 'textfield',
            'heading'     => __( 'Per page', 'kutetheme' ),
            'value'       => '4',
            'default'     => '4',
            'param_name'  => 'columns',
            'admin_label' => false,
        ),
        array(
            "type"        => "colorpicker",
            "heading"     => __( "Overlay color", 'kutetheme' ),
            "param_name"  => "overlay_color",
            "admin_label" => true,
            "default"     =>'#000000'
        ),
        array(
            'type'        => 'dropdown',
            'heading'     => __( 'Overlay opacity', 'kutetheme' ),
            'value'       => array(
                __( '0.1', 'kutetheme' )      => '0.1',
                __( '0.2', 'kutetheme' )      => '0.2',
                __( '0.3', 'kutetheme' )      => '0.3',
                __( '0.4', 'kutetheme' )      => '0.4',
                __( '0.5', 'kutetheme' )      => '0.5',
                __( '0.6', 'kutetheme' )      => '0.6',
                __( '0.7', 'kutetheme' )      => '0.7',
                __( '0.8', 'kutetheme' )      => '0.8',
                __( '0.9', 'kutetheme' )      => '0.9',
                __( '1', 'kutetheme' )        => '1',
            ),
            'default'     =>'0.7',
            'param_name'  => 'overlay_opacity',
            'admin_label' => false,
        ),
        array(
            "type"        => "textfield",
            "heading"     => __( "Extra class name", "js_composer" ),
            "param_name"  => "el_class",
            "description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
            'admin_label' => false,
        ),
        array(
            'type'        => 'css_editor',
            'heading'     => __( 'Css', 'js_composer' ),
            'param_name'  => 'css',
            'group'       => __( 'Design options', 'js_composer' ),
            'admin_label' => false,
        ),
    ),
));

class WPBakeryShortCode_kt_testimonial extends WPBakeryShortCode {
    
    protected function content($atts, $content = null) {
        $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'kt_testimonial', $atts ) : $atts;
        $atts = shortcode_atts( array(
            'title'           => 'LOOK BOOKS',
            'sub_title'       => '',
            'columns'         => 4,
            'overlay_opacity' =>'0.7',
            'overlay_color'   =>'#000000',
            'el_class'        => '',
            'css'             => '',
            
        ), $atts );
        extract($atts);
        $elementClass = array(
            'base'             => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ', $this->settings['base'], $atts ),
            'extra'            => $this->getExtraClass( $el_class ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' )
        );
        
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( 'section8 block-testimonials ', '' ), implode( ' ', $elementClass ) );
        if( $overlay_color =="") $overlay_color ="#000000";
        $overlay_color = kt_hex2rgb( $overlay_color );
        $args = array(
              'post_type'      => 'testimonial',
              'post_status'    => 'publish',
              'posts_per_page' => $columns,
        );
        $testimonial_query = new WP_Query(  $args );

        ob_start();
        ?>
        <div class="<?php echo esc_attr( $elementClass );?>">
            <div class="overlay" style="background-color: rgba(<?php echo esc_attr( $overlay_color['red'] );?>,<?php echo esc_attr( $overlay_color['green'] );?>,<?php echo esc_attr( $overlay_color['blue'] );?>,<?php echo esc_attr( $overlay_opacity );?>);"></div>
            <div class="container">
                <?php if( $title ):?>
                    <h3 class="section-title"><?php echo esc_html( $title );?></h3>
                    <?php endif; ?>
                    <?php if( $testimonial_query->have_posts()): ?>
                    <div class="testimonial-wapper">
                        <div class="testimonials">
                            <ul class="testimonial <?php echo is_rtl() ? 'testimonial-carousel-rtl' :'testimonial-carousel';?> ">
                            <?php
                            while ( $testimonial_query->have_posts()) {
                                $testimonial_query->the_post();
                                ?>
                                <li>
                                    <?php if( has_post_thumbnail( )):?>
                                    <div class="testimonial-image">
                                       <a href="#"><?php the_post_thumbnail('testimonial-thumb');?></a>
                                    </div>
                                    <?php endif;?>
                                    <div class="info">
                                        <?php the_content();?>
                                        <p class="testimonial-nane"><?php the_title( );?></p>
                                    </div>
                                </li>
                                <?php
                            }
                            ?>
                            </ul>
                            
                        </div>
                        <div class="testimonial-caption"></div>
                    </div>
                    <?php endif;?>
            </div>
        </div>
        <?php 
        wp_reset_query();
        wp_reset_postdata();
        return ob_get_clean();

    }

}