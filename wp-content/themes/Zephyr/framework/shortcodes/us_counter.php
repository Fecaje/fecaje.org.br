<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_counter
 *
 * @var $shortcode {String} Current shortcode name
 * @var $shortcode_base {String} The original called shortcode name (differs if called an alias)
 * @var $atts {Array} Shortcode attributes
 * @var $content {String} Shortcode's inner content
 */

$atts = shortcode_atts( array(
	/**
	 * @var mixed The initial number value (integer or float)
	 */
	'initial' => '0',
	/**
	 * @var mixed The target number value (integer or float)
	 */
	'target' => '99',
	/**
	 * @var string number color: 'text' / 'primary' / 'secondary' / 'custom'
	 */
	'color' => 'text',
	/**
	 * @var string Custom color value
	 */
	'custom_color' => '',
	/**
	 * @var string Number size: 'small' / 'medium' / 'large'
	 */
	'size' => 'medium',
	/**
	 * @var string Title for the counter
	 */
	'title' => __( 'Projects completed', 'us' ),
	/**
	 * @var string Number prefix
	 */
	'prefix' => '',
	/**
	 * @var string Number suffix
	 */
	'suffix' => '',
	/**
	 * @var string Extra class name
	 */
	'el_class' => '',
), $atts );

$classes = '';
$elm_atts = '';
$number_atts = '';

$classes .= ' size_' . $atts['size'];

if ( $atts['color'] == 'custom' ) {
	$number_atts .= ' style="color: ' . $atts['custom_color'] . '"';
}
$classes .= ' color_' . $atts['color'];

if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

$elm_atts .= ' data-initial="' . $atts['initial'] . '"';
$elm_atts .= ' data-target="' . $atts['target'] . '"';
$elm_atts .= ' data-prefix="' . $atts['prefix'] . '"';
$elm_atts .= ' data-suffix="' . $atts['suffix'] . '"';

?>
<div class="w-counter<?php echo $classes ?>"<?php echo $elm_atts ?>>
	<div class="w-counter-h">
		<div class="w-counter-number"<?php echo $number_atts ?>>
			<?php echo $atts['prefix'] . $atts['initial'] . $atts['suffix'] ?>
		</div>
<?php if ( ! empty ( $atts['title'] ) ): ?>
		<h6 class="w-counter-title"><?php echo $atts['title'] ?></h6>
<?php endif; ?>
	</div>
</div>
