<?php
$output = $el_class = $css_animation = '';

extract(shortcode_atts(array(
    'el_class' => '',
    'css' => '',
), $atts));

if (mb_substr(trim($content), 0, 1) != '<') {
    $content = '<p>'.$content;
}

$custom_css_class = (function_exists('vc_shortcode_custom_css_class'))?vc_shortcode_custom_css_class( $css, ' ' ):'';

$css_class = 'wpb_text_column '.$el_class.$custom_css_class;
$output .= "\n\t".'<div class="'.$css_class.'">';
$output .= "\n\t\t".'<div class="wpb_wrapper">';
$output .= "\n\t\t\t".do_shortcode(shortcode_unautop($content));
$output .= "\n\t\t".'</div> ';
$output .= "\n\t".'</div> ';

echo $output;
