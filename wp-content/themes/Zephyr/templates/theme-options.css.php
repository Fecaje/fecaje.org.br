<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Generates and outputs theme options' generated styleshets
 *
 * @action Before the template: us_before_template:templates/theme-options.css
 * @action After the template: us_after_template:templates/theme-options.css
 */

$prefixes = array( 'heading', 'body', 'menu' );
$font_families = array();
$default_font_weights = array_fill_keys( $prefixes, 400 );
foreach ( $prefixes as $prefix ) {
	$font = explode( '|', us_get_option( $prefix . '_font_family', 'none' ), 2 );
	if ( $font[0] == 'none' ) {
		// Use the default font
		$font_families[ $prefix ] = '';
	} elseif ( strpos( $font[0], ',' ) === FALSE ) {
		// Use some specific font from Google Fonts
		if ( ! isset( $font[1] ) OR empty( $font[1] ) ) {
			// Fault tolerance for missing font-variants
			$font[1] = '400,700';
		}
		// The first active font-weight will be used for "normal" weight
		$default_font_weights[ $prefix ] = intval( $font[1] );
		$fallback_font_family = us_config( 'google-fonts.' . $font[0] . '.fallback', 'sans-serif' );
		$font_families[ $prefix ] = 'font-family: "' . $font[0] . '", ' . $fallback_font_family . ";\n";
	} else {
		// Web-safe font combination
		$font_families[ $prefix ] = 'font-family: ' . $font[0] . ";\n";
	}
}

global $smof_data;
?>

<?php if ( FALSE ): ?><style>/* Setting IDE context */<?php endif; ?>


/* Typography
   ========================================================================== */

body {
	<?php echo $font_families['body'] ?>
	font-size: <?php echo us_get_option( 'body_fontsize', 14 ) ?>px;
	line-height: <?php echo us_get_option( 'body_lineheight', 24 ) ?>px;
	font-weight: <?php echo $default_font_weights['body'] ?>;
	}
.page-template-page-blank-php .l-main {
	font-size: <?php echo us_get_option( 'body_fontsize', 14 ) ?>px;
	}

.l-header .menu-item-language,
.l-header .w-nav-item {
	<?php echo $font_families['menu'] ?>
	font-weight: <?php echo $default_font_weights['menu'] ?>;
	}
.type_desktop .menu-item-language > a,
.l-header .type_desktop .w-nav-anchor.level_1,
.type_desktop [class*="columns"] .menu-item-has-children .w-nav-anchor.level_2 {
	font-size: <?php echo us_get_option( 'menu_fontsize', 16 ) ?>px;
	}
.type_desktop .submenu-languages .menu-item-language > a,
.l-header .type_desktop .w-nav-anchor.level_2,
.l-header .type_desktop .w-nav-anchor.level_3,
.l-header .type_desktop .w-nav-anchor.level_4 {
	font-size: <?php echo us_get_option( 'menu_sub_fontsize', 15 ) ?>px;
	}
.type_mobile .menu-item-language > a,
.l-header .type_mobile .w-nav-anchor.level_1 {
	font-size: <?php echo us_get_option( 'menu_fontsize_mobile', 16 ) ?>px;
	}
.l-header .type_mobile .w-nav-anchor.level_2,
.l-header .type_mobile .w-nav-anchor.level_3,
.l-header .type_mobile .w-nav-anchor.level_4 {
	font-size: <?php echo us_get_option( 'menu_sub_fontsize_mobile', 15 ) ?>px;
	}

h1, h2, h3, h4, h5, h6,
.w-blog-post.format-quote blockquote,
.w-counter-number,
.w-logo-title,
.w-pricing-item-title,
.w-pricing-item-price,
.w-tabs-item-title,
.ult_price_figure,
.ult_countdown-amount,
.ultb3-box .ultb3-title,
.stats-block .stats-desc .stats-number {
	<?php echo $font_families['heading'] ?>
	font-weight: <?php echo $default_font_weights['heading'] ?>;
	}
h1 {
	font-size: <?php echo us_get_option( 'h1_fontsize', 40 ) ?>px;
	}
h2 {
	font-size: <?php echo us_get_option( 'h2_fontsize', 34 ) ?>px;
	}
h3 {
	font-size: <?php echo us_get_option( 'h3_fontsize', 28 ) ?>px;
	}
h4,
.widgettitle,
.comment-reply-title,
.ultb3-box .ultb3-title,
.flip-box-wrap .flip-box .ifb-face h3,
.aio-icon-box .aio-icon-header h3.aio-icon-title {
	font-size: <?php echo us_get_option( 'h4_fontsize', 24 ) ?>px;
	}
h5,
.w-portfolio-item-title {
	font-size: <?php echo us_get_option( 'h5_fontsize', 20 ) ?>px;
	}
h6 {
	font-size: <?php echo us_get_option( 'h6_fontsize', 18 ) ?>px;
	}
@media (max-width: 767px) {
body {
	font-size: <?php echo us_get_option( 'body_fontsize_mobile', 13 ) ?>px;
	line-height: <?php echo us_get_option( 'body_lineheight_mobile', 23 ) ?>px;
	}
h1 {
	font-size: <?php echo us_get_option( 'h1_fontsize_mobile', 30 ) ?>px;
	}
h2 {
	font-size: <?php echo us_get_option( 'h2_fontsize_mobile', 26 ) ?>px;
	}
h3 {
	font-size: <?php echo us_get_option( 'h3_fontsize_mobile', 22 ) ?>px;
	}
h4,
.widgettitle,
.comment-reply-title,
.ultb3-box .ultb3-title,
.flip-box-wrap .flip-box .ifb-face h3,
.aio-icon-box .aio-icon-header h3.aio-icon-title {
	font-size: <?php echo us_get_option( 'h4_fontsize_mobile', 20 ) ?>px;
	}
h5,
.w-portfolio-item-title {
	font-size: <?php echo us_get_option( 'h5_fontsize_mobile', 18 ) ?>px;
	}
h6 {
	font-size: <?php echo us_get_option( 'h6_fontsize_mobile', 16 ) ?>px;
	}
}

/* Layout Options
   ========================================================================== */

.l-body,
.l-header.pos_fixed {
	min-width: <?php echo us_get_option( 'site_canvas_width', '1300' ) ?>px;
	}
.l-canvas.type_boxed,
.l-canvas.type_boxed .l-subheader,
.l-canvas.type_boxed ~ .l-footer .l-subfooter {
	max-width: <?php echo us_get_option( 'site_canvas_width', '1300' ) ?>px;
	}
.l-subheader-h,
.l-titlebar-h,
.l-main-h,
.l-section-h,
.l-subfooter-h,
.w-tabs-section-content-h,
.w-blog-post-body {
	max-width: <?php echo us_get_option( 'site_content_width', '1140' ) ?>px;
	}
.l-sidebar {
	width: <?php echo us_get_option( 'sidebar_width', '25' ) ?>%;
	}
.l-content {
	width: <?php echo us_get_option( 'content_width', '68' ) ?>%;
	}
@media (max-width: <?php echo us_get_option( 'columns_stacking_width', '767' ) ?>px) {
.g-cols.offset_none,
.g-cols.offset_none > div {
	display: block;
	}
.g-cols > div {
	width: 100% !important;
	margin-left: 0 !important;
	margin-right: 0 !important;
	margin-bottom: 30px;
	}
.l-subfooter.at_top .g-cols > div {
	margin-bottom: 10px;
	}
.g-cols.offset_none > div,
.g-cols > div:last-child {
	margin-bottom: 0 !important;
	}
}

/* Header Options
   ========================================================================== */

@media (min-width: 901px) {
.l-subheader.at_middle {
	line-height: <?php echo us_get_option( 'header_main_height', 100 ) ?>px;
	}
.l-header.layout_advanced .l-subheader.at_middle,
.l-header.layout_centered .l-subheader.at_middle {
	height: <?php echo us_get_option( 'header_main_height', 100 ) ?>px;
	}
.l-header.layout_standard.sticky .l-subheader.at_middle,
.l-header.layout_extended.sticky .l-subheader.at_middle {
	line-height: <?php echo us_get_option( 'header_main_sticky_height_1', 60 ) ?>px;
	}
.l-header.layout_advanced.sticky .l-subheader.at_middle,
.l-header.layout_centered.sticky .l-subheader.at_middle {
	line-height: <?php echo us_get_option( 'header_main_sticky_height_2', 0 ) ?>px;
	height: <?php echo us_get_option( 'header_main_sticky_height_2', 0 ) ?>px;
	}
.l-subheader.at_top {
	height: <?php echo us_get_option( 'header_extra_height', 50 ) ?>px;
	}
.l-subheader.at_top,
.l-subheader.at_bottom {
	line-height: <?php echo us_get_option( 'header_extra_height', 50 ) ?>px;
	}
.l-header.layout_extended.sticky .l-subheader.at_top {
	line-height: <?php echo us_get_option( 'header_extra_sticky_height_1', 40 ) ?>px;
	height: <?php echo us_get_option( 'header_extra_sticky_height_1', 40 ) ?>px;
	}
.l-header.layout_advanced.sticky .l-subheader.at_bottom,
.l-header.layout_centered.sticky .l-subheader.at_bottom {
	line-height: <?php echo us_get_option( 'header_extra_sticky_height_2', 50 ) ?>px;
	}
.l-header.layout_standard.pos_fixed ~ .l-titlebar,
.l-canvas.titlebar_none.sidebar_left .l-header.layout_standard.pos_fixed ~ .l-main,
.l-canvas.titlebar_none.sidebar_right .l-header.layout_standard.pos_fixed ~ .l-main,
.l-canvas.titlebar_none.sidebar_none .l-header.layout_standard.pos_fixed ~ .l-main .l-section:first-child,
.l-header.layout_standard.pos_static.bg_transparent ~ .l-titlebar,
.l-canvas.titlebar_none.sidebar_left .l-header.layout_standard.pos_static.bg_transparent ~ .l-main,
.l-canvas.titlebar_none.sidebar_right .l-header.layout_standard.pos_static.bg_transparent ~ .l-main,
.l-canvas.titlebar_none.sidebar_none .l-header.layout_standard.pos_static.bg_transparent ~ .l-main .l-section:first-child {
	padding-top: <?php echo us_get_option( 'header_main_height', 100 ) ?>px;
	}
.l-header.layout_extended.pos_fixed ~ .l-titlebar,
.l-canvas.titlebar_none.sidebar_left .l-header.layout_extended.pos_fixed ~ .l-main,
.l-canvas.titlebar_none.sidebar_right .l-header.layout_extended.pos_fixed ~ .l-main,
.l-canvas.titlebar_none.sidebar_none .l-header.layout_extended.pos_fixed ~ .l-main .l-section:first-child,
.l-header.layout_extended.pos_static.bg_transparent ~ .l-titlebar,
.l-canvas.titlebar_none.sidebar_left .l-header.layout_extended.pos_static.bg_transparent ~ .l-main,
.l-canvas.titlebar_none.sidebar_right .l-header.layout_extended.pos_static.bg_transparent ~ .l-main,
.l-canvas.titlebar_none.sidebar_none .l-header.layout_extended.pos_static.bg_transparent ~ .l-main .l-section:first-child {
	padding-top: <?php echo us_get_option( 'header_main_height', 100 ) + us_get_option( 'header_extra_height', 50 ) ?>px;
	}
.l-header.layout_advanced.pos_fixed ~ .l-titlebar,
.l-canvas.titlebar_none.sidebar_left .l-header.layout_advanced.pos_fixed ~ .l-main,
.l-canvas.titlebar_none.sidebar_right .l-header.layout_advanced.pos_fixed ~ .l-main,
.l-canvas.titlebar_none.sidebar_none .l-header.layout_advanced.pos_fixed ~ .l-main .l-section:first-child,
.l-header.layout_advanced.pos_static.bg_transparent ~ .l-titlebar,
.l-canvas.titlebar_none.sidebar_left .l-header.layout_advanced.pos_static.bg_transparent ~ .l-main,
.l-canvas.titlebar_none.sidebar_right .l-header.layout_advanced.pos_static.bg_transparent ~ .l-main,
.l-canvas.titlebar_none.sidebar_none .l-header.layout_advanced.pos_static.bg_transparent ~ .l-main .l-section:first-child {
	padding-top: <?php echo us_get_option( 'header_main_height', 100 ) + us_get_option( 'header_extra_height', 50 ) ?>px;
	}
.l-header.layout_centered.pos_fixed ~ .l-titlebar,
.l-canvas.titlebar_none.sidebar_left .l-header.layout_centered.pos_fixed ~ .l-main,
.l-canvas.titlebar_none.sidebar_right .l-header.layout_centered.pos_fixed ~ .l-main,
.l-canvas.titlebar_none.sidebar_none .l-header.layout_centered.pos_fixed ~ .l-main .l-section:first-child,
.l-header.layout_centered.pos_static.bg_transparent ~ .l-titlebar,
.l-canvas.titlebar_none.sidebar_left .l-header.layout_centered.pos_static.bg_transparent ~ .l-main,
.l-canvas.titlebar_none.sidebar_right .l-header.layout_centered.pos_static.bg_transparent ~ .l-main,
.l-canvas.titlebar_none.sidebar_none .l-header.layout_centered.pos_static.bg_transparent ~ .l-main .l-section:first-child {
	padding-top: <?php echo us_get_option( 'header_main_height', 100 ) + us_get_option( 'header_extra_height', 50 ) ?>px;
	}
.l-body.header_aside {
	padding-left: <?php echo us_get_option( 'header_main_width', 300 ) ?>px;
	}
.rtl.l-body.header_aside {
	padding-left: 0;
	padding-right: <?php echo us_get_option( 'header_main_width', 300 ) ?>px;
	}
.l-header.layout_sided,
.l-header.layout_sided .w-cart-notification {
	width: <?php echo us_get_option( 'header_main_width', 300 ) ?>px;
	}
}

/* Logo Options
   ========================================================================== */

@media (min-width: 901px) {
.w-logo-img {
	height: <?php echo min(us_get_option('logo_height', 60), us_get_option('header_main_height', 100)) ?>px;
	}
.w-logo.with_transparent .w-logo-img > img.for_default {
	margin-bottom: -<?php echo min(us_get_option('logo_height', 60), us_get_option('header_main_height', 100)) ?>px;
	}
.l-header.layout_standard.sticky .w-logo-img,
.l-header.layout_extended.sticky .w-logo-img {
	height: <?php echo min(us_get_option('logo_height_sticky', 40), us_get_option('header_main_sticky_height_1', 60)) ?>px;
	}
.l-header.layout_standard.sticky .w-logo.with_transparent .w-logo-img > img.for_default,
.l-header.layout_extended.sticky .w-logo.with_transparent .w-logo-img > img.for_default {
	margin-bottom: -<?php echo min(us_get_option('logo_height_sticky', 40), us_get_option('header_main_sticky_height_1', 60)) ?>px;
	}
.l-header.layout_advanced.sticky .w-logo-img,
.l-header.layout_centered.sticky .w-logo-img {
	height: <?php echo min(us_get_option('logo_height_sticky', 40), us_get_option('header_main_sticky_height_2', 50)) ?>px;
	}
.l-header.layout_advanced.sticky .w-logo.with_transparent .w-logo-img > img.for_default,
.l-header.layout_centered.sticky .w-logo.with_transparent .w-logo-img > img.for_default {
	margin-bottom: -<?php echo min(us_get_option('logo_height_sticky', 40), us_get_option('header_main_sticky_height_2', 50)) ?>px;
	}
.l-header.layout_sided .w-logo-img > img {
	width: <?php echo min(us_get_option('logo_width', 200), us_get_option('header_main_width', 300)) ?>px;
	}
.w-logo-title {
	font-size: <?php echo us_get_option( 'logo_font_size', 28 ) ?>px;
	}
}
@media (min-width: 601px) and (max-width: 900px) {
.w-logo-img {
	height: <?php echo us_get_option( 'logo_height_tablets', 40 ) ?>px;
	}
.w-logo.with_transparent .w-logo-img > img.for_default {
	margin-bottom: -<?php echo us_get_option( 'logo_height_tablets', 40 ) ?>px;
	}
.w-logo-title {
	font-size: <?php echo us_get_option( 'logo_font_size_tablets', 24 ) ?>px;
	}
}
@media (max-width: 600px) {
.w-logo-img {
	height: <?php echo us_get_option( 'logo_height_mobiles', 30 ) ?>px;
	}
.w-logo.with_transparent .w-logo-img > img.for_default {
	margin-bottom: -<?php echo us_get_option( 'logo_height_mobiles', 30 ) ?>px;
	}
.w-logo-title {
	font-size: <?php echo us_get_option( 'logo_font_size_mobiles', 20 ) ?>px;
	}
}
/* Color Styles
   ========================================================================== */

/* Body Background Color */
.l-body {
	background-color: <?php echo us_get_option( 'color_body_bg' ) ?>;
	}

/*************************** HEADER ***************************/

/* Header Background Color */
.l-subheader.at_middle,
.l-subheader.at_middle .w-lang-list,
.l-subheader.at_middle .type_mobile .w-nav-list.level_1 {
	background-color: <?php echo us_get_option( 'color_header_bg' ) ?>;
	}

/* Header Text Color */
.l-subheader.at_middle,
.transparent .l-subheader.at_middle .type_mobile .w-nav-list.level_1 {
	color: <?php echo us_get_option( 'color_header_text' ) ?>;
	}
.l-subheader.at_middle .w-nav-anchor.level_1 .ripple {
	background-color: <?php echo us_get_option( 'color_header_text' ) ?>;
	}

/* Header Text Hover Color */
.no-touch .w-logo-link:hover,
.no-touch .l-subheader.at_middle .w-contacts-item-value a:hover,
.no-touch .l-subheader.at_middle .w-lang-item:hover,
.no-touch .transparent .l-subheader.at_middle .w-lang.active .w-lang-item:hover,
.no-touch .l-subheader.at_middle .w-socials-item-link:hover,
.no-touch .l-subheader.at_middle .w-search-show:hover,
.no-touch .l-subheader.at_middle .w-cart-h:hover .w-cart-link {
	color: <?php echo us_get_option( 'color_header_text_hover' ) ?>;
	}

/* Extended Header Background Color */
.l-subheader.at_top,
.l-subheader.at_top .w-lang-list,
.l-subheader.at_bottom,
.l-subheader.at_bottom .type_mobile .w-nav-list.level_1 {
	background-color: <?php echo us_get_option( 'color_header_ext_bg' ) ?>;
	}

/* Extended Header Text Color */
.l-subheader.at_top,
.l-subheader.at_bottom,
.transparent .l-subheader.at_bottom .type_mobile .w-nav-list.level_1,
.w-lang.active .w-lang-item {
	color: <?php echo us_get_option( 'color_header_ext_text' ) ?>;
	}
.l-subheader.at_bottom .w-nav-anchor.level_1 .ripple {
	background-color: <?php echo us_get_option( 'color_header_ext_text' ) ?>;
	}

/* Extended Header Text Hover Color */
.no-touch .l-subheader.at_top .w-contacts-item-value a:hover,
.no-touch .l-subheader.at_top .w-lang-item:hover,
.no-touch .transparent .l-subheader.at_top .w-lang.active .w-lang-item:hover,
.no-touch .l-subheader.at_top .w-socials-item-link:hover,
.no-touch .l-subheader.at_bottom .w-search-show:hover,
.no-touch .l-subheader.at_bottom .w-cart-h:hover .w-cart-link {
	color: <?php echo us_get_option( 'color_header_ext_text_hover' ) ?>;
	}

/* Transparent Header Text Color */
.l-header.transparent .l-subheader {
	color: <?php echo us_get_option( 'color_header_transparent_text' ) ?>;
	}

/* Transparent Header Text Hover Color */
.no-touch .l-header.transparent .type_desktop .menu-item-language > a:hover,
.no-touch .l-header.transparent .type_desktop .menu-item-language:hover > a,
.no-touch .l-header.transparent .w-logo-link:hover,
.no-touch .l-header.transparent .l-subheader .w-contacts-item-value a:hover,
.no-touch .l-header.transparent .l-subheader .w-lang-item:hover,
.no-touch .l-header.transparent .l-subheader .w-socials-item-link:hover,
.no-touch .l-header.transparent .l-subheader .w-search-show:hover,
.no-touch .l-header.transparent .l-subheader .w-cart-h:hover .w-cart-link,
.no-touch .l-header.transparent .type_desktop .w-nav-item.level_1:hover .w-nav-anchor.level_1 {
	color: <?php echo us_get_option( 'color_header_transparent_text_hover' ) ?>;
	}

/* Search Screen Background Color */
.w-search-form-overlay {
	background-color: <?php echo us_get_option( 'color_header_search_bg' ) ?>;
	}

/* Search Screen Text Color */
.l-subheader .w-search-form {
	color: <?php echo us_get_option( 'color_header_search_text' ) ?>;
	}
.l-subheader .w-search-input:after,
.l-subheader input:focus ~ .w-search-input-bar:before,
.l-subheader input:focus ~ .w-search-input-bar:after {
	background-color: <?php echo us_get_option( 'color_header_search_text' ) ?>;
	}

/*************************** MAIN MENU ***************************/

/* Menu Hover Background Color */
.no-touch .l-header .menu-item-language > a:hover,
.no-touch .type_desktop .menu-item-language:hover > a,
.no-touch .l-header .w-nav-item.level_1:hover .w-nav-anchor.level_1 {
	background-color: <?php echo us_get_option( 'color_menu_hover_bg' ) ?>;
	}

/* Menu Hover Text Color */
.no-touch .l-header .menu-item-language > a:hover,
.no-touch .type_desktop .menu-item-language:hover > a,
.no-touch .l-header .w-nav-item.level_1:hover .w-nav-anchor.level_1 {
	color: <?php echo us_get_option( 'color_menu_hover_text' ) ?>;
	}

/* Menu Active Text Color */
.l-header .w-nav-item.level_1.active .w-nav-anchor.level_1,
.l-header .w-nav-item.level_1.current-menu-item .w-nav-anchor.level_1,
.l-header .w-nav-item.level_1.current-menu-ancestor .w-nav-anchor.level_1 {
	color: <?php echo us_get_option( 'color_menu_active_text' ) ?>;
	}

/* Transparent Menu Active Text Color */
.l-header.transparent .type_desktop .w-nav-item.level_1.active .w-nav-anchor.level_1,
.l-header.transparent .type_desktop .w-nav-item.level_1.current-menu-item .w-nav-anchor.level_1,
.l-header.transparent .type_desktop .w-nav-item.level_1.current-menu-ancestor .w-nav-anchor.level_1 {
	color: <?php echo us_get_option( 'color_menu_transparent_active_text' ) ?>;
	}

/* Dropdown Background Color */
.type_desktop .submenu-languages,
.l-header .w-nav-list.level_2,
.l-header .w-nav-list.level_3,
.l-header .w-nav-list.level_4 {
	background-color: <?php echo us_get_option( 'color_drop_bg' ) ?>;
	}

/* Dropdown Text Color */
.type_desktop .submenu-languages,
.l-header .w-nav-anchor.level_2,
.l-header .w-nav-anchor.level_3,
.l-header .w-nav-anchor.level_4,
.type_desktop [class*="columns"] .w-nav-item.menu-item-has-children.active .w-nav-anchor.level_2,
.type_desktop [class*="columns"] .w-nav-item.menu-item-has-children.current-menu-item .w-nav-anchor.level_2,
.type_desktop [class*="columns"] .w-nav-item.menu-item-has-children.current-menu-ancestor .w-nav-anchor.level_2,
.no-touch .type_desktop [class*="columns"] .w-nav-item.menu-item-has-children:hover .w-nav-anchor.level_2 {
	color: <?php echo us_get_option( 'color_drop_text' ) ?>;
	}
.l-header .w-nav-anchor.level_2 .ripple,
.l-header .w-nav-anchor.level_3 .ripple,
.l-header .w-nav-anchor.level_4 .ripple {
	background-color: <?php echo us_get_option( 'color_drop_text' ) ?>;
	}

/* Dropdown Hover Background Color */
.no-touch .type_desktop .submenu-languages .menu-item-language:hover > a,
.no-touch .l-header .w-nav-item.level_2:hover .w-nav-anchor.level_2,
.no-touch .l-header .w-nav-item.level_3:hover .w-nav-anchor.level_3,
.no-touch .l-header .w-nav-item.level_4:hover .w-nav-anchor.level_4 {
	background-color: <?php echo us_get_option( 'color_drop_hover_bg' ) ?>;
	}

/* Dropdown Hover Text Color */
.no-touch .type_desktop .submenu-languages .menu-item-language:hover > a,
.no-touch .l-header .w-nav-item.level_2:hover .w-nav-anchor.level_2,
.no-touch .l-header .w-nav-item.level_3:hover .w-nav-anchor.level_3,
.no-touch .l-header .w-nav-item.level_4:hover .w-nav-anchor.level_4 {
	color: <?php echo us_get_option( 'color_drop_hover_text' ) ?>;
	}

/* Dropdown Active Background Color */
.l-header .w-nav-item.level_2.current-menu-item .w-nav-anchor.level_2,
.l-header .w-nav-item.level_2.current-menu-ancestor .w-nav-anchor.level_2,
.l-header .w-nav-item.level_3.current-menu-item .w-nav-anchor.level_3,
.l-header .w-nav-item.level_3.current-menu-ancestor .w-nav-anchor.level_3,
.l-header .w-nav-item.level_4.current-menu-item .w-nav-anchor.level_4,
.l-header .w-nav-item.level_4.current-menu-ancestor .w-nav-anchor.level_4 {
	background-color: <?php echo us_get_option( 'color_drop_active_bg' ) ?>;
	}

/* Dropdown Active Text Color */
.l-header .w-nav-item.level_2.current-menu-item .w-nav-anchor.level_2,
.l-header .w-nav-item.level_2.current-menu-ancestor .w-nav-anchor.level_2,
.l-header .w-nav-item.level_3.current-menu-item .w-nav-anchor.level_3,
.l-header .w-nav-item.level_3.current-menu-ancestor .w-nav-anchor.level_3,
.l-header .w-nav-item.level_4.current-menu-item .w-nav-anchor.level_4,
.l-header .w-nav-item.level_4.current-menu-ancestor .w-nav-anchor.level_4 {
	color: <?php echo us_get_option( 'color_drop_active_text' ) ?>;
	}

/* Menu Button Background Color */
.btn.w-nav-item .w-nav-anchor.level_1 {
	background-color: <?php echo us_get_option( 'color_menu_button_bg' ) ?> !important;
	}

/* Menu Button Text Color */
.btn.w-nav-item .w-nav-anchor.level_1 {
	color: <?php echo us_get_option( 'color_menu_button_text' ) ?> !important;
	}

/* Menu Button Hover Background Color */
.no-touch .btn.w-nav-item:hover .w-nav-anchor.level_1 {
	background-color: <?php echo us_get_option( 'color_menu_button_hover_bg' ) ?> !important;
	}

/* Menu Button Hover Text Color */
.no-touch .btn.w-nav-item:hover .w-nav-anchor.level_1 {
	color: <?php echo us_get_option( 'color_menu_button_hover_text' ) ?> !important;
	}

/*************************** MAIN CONTENT ***************************/

/* Background Color */
.l-canvas,
.w-blog.layout_masonry .w-blog-post-h,
.w-cart-dropdown,
.w-portfolio-item-anchor,
.w-pricing.style_1 .w-pricing-item-h,
.w-person.style_card,
#lang_sel ul ul,
#lang_sel_click ul ul,
#lang_sel_footer,
.woocommerce .form-row .chosen-drop,
.woocommerce-ordering:after,
.woocommerce-type_2 .product-h,
.no-touch .woocommerce-type_2 .product-meta,
.woocommerce #payment .payment_box,
.widget_layered_nav ul li.chosen,
.wpcf7-form-control-wrap.type_select:after {
	background-color: <?php echo us_get_option( 'color_content_bg' ) ?>;
	}
button.w-btn.color_contrast.style_raised,
a.w-btn.color_contrast.style_raised,
.w-iconbox.style_circle.color_contrast .w-iconbox-icon,
.w-socials.style_4 .w-socials-item-link {
	color: <?php echo us_get_option( 'color_content_bg' ) ?>;
	}

/* Alternate Background Color */
.l-section.color_alternate,
.l-titlebar.color_alternate,
.no-touch .l-titlebar .g-nav-item:hover,
.l-section.for_blogpost .w-blog-post-preview,
.l-section.for_author .l-section-h,
.l-section.for_related .l-section-h,
.l-canvas.sidebar_none .l-section.for_comments,
.no-touch .w-btn.style_flat:hover,
.no-touch .pagination a.page-numbers:hover,
.w-actionbox.color_alternate,
.w-blog-post-preview-icon,
.w-form.for_protected,
.w-iconbox.style_circle.color_light .w-iconbox-icon,
.no-touch .w-logos .owl-prev:hover,
.no-touch .w-logos .owl-next:hover,
.w-profile,
.w-pricing.style_1 .w-pricing-item-header,
.w-pricing.style_2 .w-pricing-item-h,
.w-socials-item-link,
.w-tabs-item .ripple,
.w-testimonial.style_1,
.widget_calendar #calendar_wrap,
.no-touch .l-main .widget_nav_menu a:hover,
.no-touch #lang_sel ul ul a:hover,
.no-touch #lang_sel_click ul ul a:hover,
.woocommerce .login,
.woocommerce .checkout_coupon,
.woocommerce .register,
.no-touch .woocommerce-type_2 .product-h .button:hover,
.woocommerce .variations_form,
.woocommerce .variations_form .variations td.value:after,
.woocommerce .comment-respond,
.woocommerce .stars span a:after,
.woocommerce .cart_totals,
.no-touch .woocommerce .product-remove a:hover,
.woocommerce .checkout #order_review,
.woocommerce ul.order_details,
.widget_shopping_cart,
.widget_layered_nav ul,
.smile-icon-timeline-wrap .timeline-wrapper .timeline-block,
.smile-icon-timeline-wrap .timeline-feature-item.feat-item {
	background-color: <?php echo us_get_option( 'color_content_bg_alt' ) ?>;
	}
.timeline-wrapper .timeline-post-right .ult-timeline-arrow l,
.timeline-wrapper .timeline-post-left .ult-timeline-arrow l,
.timeline-feature-item.feat-item .ult-timeline-arrow l {
	border-color: <?php echo us_get_option( 'color_content_bg_alt' ) ?>;
	}

/* Border Color */
input[type="text"],
input[type="password"],
input[type="email"],
input[type="url"],
input[type="tel"],
input[type="number"],
input[type="date"],
input[type="search"],
textarea,
select,
.l-section,
.g-cols > div,
.w-form-field > input:focus,
.w-form-field > textarea:focus,
.w-search.submit_inside input[type="text"]:focus,
.w-separator,
.w-sharing.type_simple .w-sharing-item,
.w-tabs-list,
.w-tabs-section,
.w-tabs-section-header:before,
.l-main .widget_nav_menu > div,
.l-main .widget_nav_menu .menu-item a,
#lang_sel a.lang_sel_sel,
#lang_sel_click a.lang_sel_sel,
.woocommerce table th,
.woocommerce table td,
.woocommerce .quantity.buttons_added input.qty,
.woocommerce .quantity.buttons_added .plus,
.woocommerce .quantity.buttons_added .minus,
.woocommerce-tabs .tabs,
.woocommerce .related,
.woocommerce .upsells,
.woocommerce .cross-sells,
.woocommerce ul.order_details li,
.woocommerce .shop_table.my_account_orders,
.select2-container a.select2-choice,
.smile-icon-timeline-wrap .timeline-line {
	border-color: <?php echo us_get_option( 'color_content_border' ) ?>;
	}
.w-iconbox.style_default.color_light .w-iconbox-icon,
.w-separator,
.w-testimonial.style_2:before,
.pagination .page-numbers,
.woocommerce .star-rating:before {
	color: <?php echo us_get_option( 'color_content_border' ) ?>;
	}
.no-touch .l-titlebar.color_alternate .g-nav-item:hover,
button.w-btn.color_light.style_raised,
a.w-btn.color_light.style_raised,
.no-touch .color_alternate .w-btn.style_flat:hover,
.no-touch .color_alternate .w-logos .owl-prev:hover,
.no-touch .color_alternate .w-logos .owl-next:hover,
.no-touch .color_alternate .pagination a.page-numbers:hover,
.widget_price_filter .ui-slider:before {
	background-color: <?php echo us_get_option( 'color_content_border' ) ?>;
	}

/* Heading Color */
h1, h2, h3, h4, h5, h6,
.w-counter-number,
.w-portfolio-item-anchor,
.no-touch .w-portfolio-item-anchor:hover,
.l-section.color_primary a.w-portfolio-item-anchor,
.l-section.color_secondary a.w-portfolio-item-anchor {
	color: <?php echo us_get_option( 'color_content_heading' ) ?>;
	}

/* Text Color */
.l-canvas,
button.w-btn.color_light.style_raised,
a.w-btn.color_light.style_raised,
.w-blog.layout_masonry .w-blog-post-h,
.w-cart-dropdown,
.w-iconbox.style_circle.color_light .w-iconbox-icon,
.w-pricing-item-h,
.w-person.style_card,
.w-testimonial.style_1,
.woocommerce .form-row .chosen-drop,
.woocommerce-type_2 .product-h {
	color: <?php echo us_get_option( 'color_content_text' ) ?>;
	}
button.w-btn.color_contrast.style_raised,
a.w-btn.color_contrast.style_raised,
.w-iconbox.style_circle.color_contrast .w-iconbox-icon {
	background-color: <?php echo us_get_option( 'color_content_text' ) ?>;
	}

/* Primary Color */
a,
.highlight_primary,
.w-blog.layout_compact .w-blog-post-link,
.w-blog.layout_related .w-blog-post-link,
button.w-btn.color_primary.style_flat,
a.w-btn.color_primary.style_flat,
.w-counter.color_primary .w-counter-number,
.w-iconbox.style_default.color_primary .w-iconbox-icon,
.g-filters-item.active,
.w-form-field > input:focus ~ i,
.w-form-field > textarea:focus ~ i,
.w-search-input > input:focus ~ i,
.w-separator.color_primary,
.w-tabs-item.active,
.w-tabs-section.active .w-tabs-section-header,
.l-main .widget_nav_menu .menu-item.current-menu-item > a,
.no-touch .woocommerce-type_2 .product-h a.button,
.woocommerce-tabs .tabs li.active,
input[type="radio"]:checked + .wpcf7-list-item-label:before,
input[type="checkbox"]:checked + .wpcf7-list-item-label:before {
	color: <?php echo us_get_option( 'color_content_primary' ) ?>;
	}
.l-section.color_primary,
.l-titlebar.color_primary,
.highlight_primary_bg,
button,
input[type="submit"],
button.w-btn.color_primary.style_raised,
a.w-btn.color_primary.style_raised,
.pagination .page-numbers.current,
.w-actionbox.color_primary,
input:focus ~ .w-form-field-bar:before,
input:focus ~ .w-form-field-bar:after,
textarea:focus ~ .w-form-field-bar:before,
textarea:focus ~ .w-form-field-bar:after,
input:focus ~ .w-search-input-bar:before,
input:focus ~ .w-search-input-bar:after,
.w-iconbox.style_circle.color_primary .w-iconbox-icon,
.w-pricing.style_1 .type_featured .w-pricing-item-header,
.w-pricing.style_2 .type_featured .w-pricing-item-h,
.w-tabs-list-bar,
.w-tabs.layout_timeline .w-tabs-item.active,
.no-touch .w-tabs.layout_timeline .w-tabs-item:hover,
.w-tabs.layout_timeline .w-tabs-section.active .w-tabs-section-header-h,
.rsDefault .rsThumb.rsNavSelected,
.tp-bullets.custom .bullet.selected,
.no-touch .tp-bullets.custom .bullet.selected:hover,
.woocommerce .button.alt,
.woocommerce .button.checkout,
.widget_price_filter .ui-slider-range,
.widget_price_filter .ui-slider-handle,
.smile-icon-timeline-wrap .timeline-separator-text .sep-text,
.smile-icon-timeline-wrap .timeline-wrapper .timeline-dot,
.smile-icon-timeline-wrap .timeline-feature-item .timeline-dot {
	background-color: <?php echo us_get_option( 'color_content_primary' ) ?>;
	}
.g-html blockquote,
.g-filters-item.active,
input:focus,
textarea:focus,
.w-separator.color_primary,
.woocommerce .quantity.buttons_added input.qty:focus,
.validate-required.woocommerce-validated input:focus,
.validate-required.woocommerce-invalid input:focus,
.woocommerce .form-row .chosen-search input[type="text"]:focus,
.woocommerce-tabs .tabs li.active,
.select2-dropdown-open.select2-drop-above a.select2-choice {
	border-color: <?php echo us_get_option( 'color_content_primary' ) ?>;
	}
input:focus,
textarea:focus,
.select2-dropdown-open a.select2-choice {
	box-shadow: 0 -1px 0 0 <?php echo us_get_option( 'color_content_primary' ) ?> inset;
	}

/* Secondary Color */
.no-touch a:hover,
.highlight_secondary,
button.w-btn.color_secondary.style_flat,
a.w-btn.color_secondary.style_flat,
.no-touch .w-blog-post-link:hover .w-blog-post-title span,
.no-touch .w-blog-post-link:hover .w-blog-post-preview-icon,
.no-touch .w-blog-post-meta a:hover,
.no-touch .w-blognav-prev:hover .w-blognav-title,
.no-touch .w-blognav-next:hover .w-blognav-title,
.w-counter.color_secondary .w-counter-number,
.w-iconbox.style_default.color_secondary .w-iconbox-icon,
.w-iconbox.style_default .w-iconbox-link:active .w-iconbox-icon,
.no-touch .w-iconbox.style_default .w-iconbox-link:hover .w-iconbox-icon,
.w-iconbox-link:active .w-iconbox-title,
.no-touch .w-iconbox-link:hover .w-iconbox-title,
.w-separator.color_secondary,
.no-touch .l-main .widget_tag_cloud a:hover,
.no-touch .l-main .widget_product_tag_cloud .tagcloud a:hover,
.woocommerce .star-rating span:before,
.woocommerce .stars span a:after {
	color: <?php echo us_get_option( 'color_content_secondary' ) ?>;
	}
.l-section.color_secondary,
.l-titlebar.color_secondary,
.highlight_secondary_bg,
button.w-btn.color_secondary.style_raised,
a.w-btn.color_secondary.style_raised,
.w-actionbox.color_secondary,
.w-iconbox.style_circle.color_secondary .w-iconbox-icon,
.no-touch .w-toplink.active:hover,
.no-touch .tp-leftarrow.custom:hover,
.no-touch .tp-rightarrow.custom:hover,
p.demo_store,
.woocommerce .onsale,
.woocommerce .form-row .chosen-results li.highlighted {
	background-color: <?php echo us_get_option( 'color_content_secondary' ) ?>;
	}
.w-separator.color_secondary {
	border-color: <?php echo us_get_option( 'color_content_secondary' ) ?>;
	}

/* Fade Elements Color */
.highlight_faded,
button.w-btn.color_light.style_flat,
a.w-btn.color_light.style_flat,
.w-blog-post-preview-icon,
.w-blog-post-meta,
.w-comments-item-date,
.w-comments-item-answer a,
.w-profile-link.for_logout,
.w-socials.style_3 .w-socials-item-link,
.g-tags,
.l-main .widget_tag_cloud a,
.l-main .widget_product_tag_cloud .tagcloud a,
.woocommerce .stars span:after {
	color: <?php echo us_get_option( 'color_content_faded' ) ?>;
	}
.w-btn.style_flat .ripple,
.w-btn.color_light.style_raised .ripple,
.w-socials.style_4 .w-socials-item-link {
	background-color: <?php echo us_get_option( 'color_content_faded' ) ?>;
	}

/*************************** SUBFOOTER ***************************/

/* Background Color */
.l-subfooter.at_top,
.l-subfooter.at_top #lang_sel ul ul,
.l-subfooter.at_top #lang_sel_click ul ul,
.l-subfooter.at_top .wpcf7-form-control-wrap.type_select:after {
	background-color: <?php echo us_get_option( 'color_subfooter_bg' ) ?>;
	}

/* Alternate Background Color */
.no-touch .l-subfooter.at_top #lang_sel ul ul a:hover,
.no-touch .l-subfooter.at_top #lang_sel_click ul ul a:hover,
.l-subfooter.at_top .w-socials-item-link,
.l-subfooter.at_top .widget_calendar #calendar_wrap,
.l-subfooter.at_top .widget_shopping_cart {
	background-color: <?php echo us_get_option( 'color_subfooter_bg_alt' ) ?>;
	}

/* Border Color */
.l-subfooter.at_top,
.l-subfooter.at_top #lang_sel a.lang_sel_sel,
.l-subfooter.at_top #lang_sel_click a.lang_sel_sel,
.l-subfooter.at_top input,
.l-subfooter.at_top textarea,
.l-subfooter.at_top select,
.l-subfooter.at_top .w-form-field > input:focus,
.l-subfooter.at_top .w-form-field > textarea:focus,
.l-subfooter.at_top .w-search.submit_inside input[type="text"]:focus {
	border-color: <?php echo us_get_option( 'color_subfooter_border' ) ?>;
	}

/* Heading Color */
.l-subfooter.at_top h1,
.l-subfooter.at_top h2,
.l-subfooter.at_top h3,
.l-subfooter.at_top h4,
.l-subfooter.at_top h5,
.l-subfooter.at_top h6 {
	color: <?php echo us_get_option( 'color_subfooter_heading' ) ?>;
	}

/* Text Color */
.l-subfooter.at_top {
	color: <?php echo us_get_option( 'color_subfooter_text' ) ?>;
	}

/* Link Color */
.l-subfooter.at_top a,
.l-subfooter.at_top .widget_tag_cloud .tagcloud a,
.l-subfooter.at_top .widget_product_tag_cloud .tagcloud a {
	color: <?php echo us_get_option( 'color_subfooter_link' ) ?>;
	}

/* Link Hover Color */
.no-touch .l-subfooter.at_top a:hover,
.l-subfooter.at_top .w-form-field > input:focus ~ i,
.l-subfooter.at_top .w-form-field > textarea:focus ~ i,
.l-subfooter.at_top .w-search-input > input:focus ~ i,
.no-touch .l-subfooter.at_top .widget_tag_cloud .tagcloud a:hover,
.no-touch .l-subfooter.at_top .widget_product_tag_cloud .tagcloud a:hover {
	color: <?php echo us_get_option( 'color_subfooter_link_hover' ) ?>;
	}
.l-subfooter.at_top input:focus ~ .w-form-field-bar:before,
.l-subfooter.at_top input:focus ~ .w-form-field-bar:after,
.l-subfooter.at_top textarea:focus ~ .w-form-field-bar:before,
.l-subfooter.at_top textarea:focus ~ .w-form-field-bar:after,
.l-subfooter.at_top input:focus ~ .w-search-input-bar:before,
.l-subfooter.at_top input:focus ~ .w-search-input-bar:after {
	background-color: <?php echo us_get_option( 'color_subfooter_link_hover' ) ?>;
	}
.l-subfooter.at_top input:focus,
.l-subfooter.at_top textarea:focus {
	border-color: <?php echo us_get_option( 'color_subfooter_link_hover' ) ?>;
	}
.l-subfooter.at_top input:focus,
.l-subfooter.at_top textarea:focus {
	box-shadow: 0 -1px 0 0 <?php echo us_get_option( 'color_subfooter_link_hover' ) ?> inset;
	}

/*************************** FOOTER ***************************/

/* Background Color */
.l-subfooter.at_bottom {
	background-color: <?php echo us_get_option( 'color_footer_bg' ) ?>;
	}

/* Text Color */
.l-subfooter.at_bottom {
	color: <?php echo us_get_option( 'color_footer_text' ) ?>;
	}

/* Link Color */
.l-subfooter.at_bottom a {
	color: <?php echo us_get_option( 'color_footer_link' ) ?>;
	}

/* Link Hover Color */
.no-touch .l-subfooter.at_bottom a:hover {
	color: <?php echo us_get_option( 'color_footer_link_hover' ) ?>;
	}

<?php echo us_get_option('custom_css', '') ?>

<?php if ( FALSE ): ?>/* Setting IDE context */</style><?php endif; ?>
