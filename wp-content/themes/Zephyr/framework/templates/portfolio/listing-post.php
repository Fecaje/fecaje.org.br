<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output one post from portfolio listing.
 *
 * (!) Should be called in WP_Query fetching loop only.
 * @link https://codex.wordpress.org/Class_Reference/WP_Query#Standard_Loop
 *
 * @var $metas array Meta data that should be shown: array('title', 'date', 'categories')
 *
 * @action Before the template: 'us_before_template:templates/blog/listing-post'
 * @action After the template: 'us_after_template:templates/blog/listing-post'
 * @filter Template variables: 'us_template_vars:templates/blog/listing-post'
 */

// .w-portfolio item additional classes
$classes = '';
$anchor_atts = '';
$anchor_inner_css = '';

$available_ratios = array( '3x2', '4x3', '1x1', '2x3', '3x4' );

// In case of any image issue using placeholder so admin could understand it quickly
// TODO Move placeholder URL to some config
global $us_template_directory_uri;
$placeholder_url = $us_template_directory_uri . '/img/placeholder/500x500.gif';

$tnail_id = get_post_thumbnail_id();
if ( ! $tnail_id OR ! ( $image = wp_get_attachment_image_src( $tnail_id, 'tnail-masonry' ) ) ) {
	$image = array( $placeholder_url, 500, 500 );
}
$item_title = get_the_title();
$image_html = '<img src="' . $image[0] . '" width="' . $image[1] . '" height="' . $image[2] . '" alt="' . esc_attr( $item_title ) . '">';

$categories = get_the_terms( get_the_ID(), 'us_portfolio_category' );
$categories_slugs = array();
if ( ! is_array( $categories ) ) {
	$categories = array();
}
foreach ( $categories as $category ) {
	$classes .= ' ' . $category->slug;
	$categories_slugs[] = $category->slug;
}

if ( rwmb_meta( 'us_custom_link' ) != '' ) {
	$link = rwmb_meta( 'us_custom_link' );
	if ( rwmb_meta( 'us_custom_link_blank' ) == 1 ) {
		$anchor_atts .= ' target="_blank"';
	}
} elseif ( rwmb_meta( 'us_lightbox' ) == 1 ) {
	$link = $tnail_id ? wp_get_attachment_image_src( $tnail_id, 'full' ) : $placeholder_url;
	if ( $link ) {
		$link = $link[0];
		$anchor_atts .= ' ref="magnificPopup"';
	}
} else {
	$link = esc_url( apply_filters( 'the_permalink', get_permalink() ) );
}

$available_metas = array( 'title', 'date', 'categories' );
$metas = ( isset( $metas ) AND is_array( $metas ) ) ? array_intersect( $metas, $available_metas ) : array( 'title' );
$meta_html = array_fill_keys( $metas, '' );
if ( in_array( 'title', $metas ) ) {
	$meta_html['title'] = '<h2 class="w-portfolio-item-title">' . get_the_title() . '</h2>';
}
if ( in_array( 'date', $metas ) ) {
	$meta_html['date'] = '<span class="w-portfolio-item-text">' . get_the_date() . '</span>';
}
if ( in_array( 'categories', $metas ) AND count( $categories ) > 0 ) {
	$meta_html['categories'] = '<span class="w-portfolio-item-text">';
	foreach ( $categories as $index => $category ) {
		$meta_html['categories'] .= ( ( $index > 0 ) ? ' / ' : '' ) . $category->name;
	}
	$meta_html['categories'] .= '</span>';
}

$tile_size = '1x1';
if ( rwmb_meta( 'us_tile_size' ) != '' ) {
	$tile_size = rwmb_meta( 'us_tile_size' );
}
$classes .= ' size_' . $tile_size;

if ( rwmb_meta( 'us_tile_bg_color' ) != '' ) {
	$anchor_inner_css .= 'background-color: ' . rwmb_meta( 'us_tile_bg_color' ) . ';';
}
if ( rwmb_meta( 'us_tile_text_color' ) != '' ) {
	$anchor_inner_css .= 'color: ' . rwmb_meta( 'us_tile_text_color' ) . ';';
}
if ( $anchor_inner_css != '' ) {
	$anchor_inner_css = ' style="' . $anchor_inner_css . '"';
}

$classes .= ' animate_reveal';


?>
<div class="w-portfolio-item<?php echo $classes ?>" data-id="<?php the_ID() ?>" data-categories="<?php echo implode( ',', $categories_slugs ) ?>">
	<a class="w-portfolio-item-anchor" href="<?php echo $link ?>"<?php echo $anchor_atts . $anchor_inner_css ?>>
		<div class="w-portfolio-item-image" style="background-image: url(<?php echo $image[0] ?>)">
			<?php echo $image_html ?>
		</div>
<?php if ( ! empty( $meta_html ) ): ?>
		<div class="w-portfolio-item-meta">
			<div class="w-portfolio-item-meta-h">
				<?php echo implode( '', $meta_html ) ?>
			</div>
		</div>
<?php endif; ?>
	</a>
</div>
