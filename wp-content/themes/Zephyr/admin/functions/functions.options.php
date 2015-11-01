<?php

add_action( 'init', 'of_options' );

if ( ! function_exists( 'of_options' ) ) {
	function of_options() {
		global $us_stylesheet_directory, $us_template_directory_uri;

		//Access the WordPress Categories via an Array
		$of_categories = array();
		$of_categories_obj = get_categories( 'hide_empty=0' );
		foreach ( $of_categories_obj as $of_cat ) {
			$of_categories[ $of_cat->cat_ID ] = $of_cat->cat_name;
		}
		$categories_tmp = array_unshift( $of_categories, "Select a category:" );

		//Access the WordPress Pages via an Array
		$of_pages = array();
		$of_pages_obj = get_pages( 'sort_column=post_parent,menu_order' );
		foreach ( $of_pages_obj as $of_page ) {
			$of_pages[ $of_page->ID ] = $of_page->post_name;
		}
		$of_pages_tmp = array_unshift( $of_pages, "Select a page:" );

		//Testing
		$of_options_select = array(
			'one',
			'two',
			'three',
			'four',
			'five'
		);
		$of_options_radio = array(
			'one' => 'One',
			'two' => 'Two',
			'three' => 'Three',
			'four' => 'Four',
			'five' => 'Five'
		);

		//Sample Homepage blocks for the layout manager (sorter)
		$of_options_homepage_blocks = array(
			'disabled' => array(
				'placebo' => 'placebo', //REQUIRED!
				'block_one' => 'Block One',
				'block_two' => 'Block Two',
				'block_three' => 'Block Three',
			),
			'enabled' => array(
				'placebo' => 'placebo', //REQUIRED!
				'block_four' => 'Block Four',
			),
		);

		//Stylesheets Reader
		$alt_stylesheet_path = LAYOUT_PATH;
		$alt_stylesheets = array();

		if ( is_dir( $alt_stylesheet_path ) ) {
			if ( $alt_stylesheet_dir = opendir( $alt_stylesheet_path ) ) {
				while ( ( $alt_stylesheet_file = readdir( $alt_stylesheet_dir ) ) !== FALSE ){
					if ( stristr( $alt_stylesheet_file, ".css" ) !== FALSE ) {
						$alt_stylesheets[] = $alt_stylesheet_file;
					}
				}
			}
		}

		//Background Images Reader
		$bg_images_path = $us_stylesheet_directory . '/images/bg/'; // change this to where you store your bg images
		$bg_images_url = $us_template_directory_uri . '/images/bg/'; // change this to where you store your bg images
		$bg_images = array();

		if ( is_dir( $bg_images_path ) ) {
			if ( $bg_images_dir = opendir( $bg_images_path ) ) {
				while ( ( $bg_images_file = readdir( $bg_images_dir ) ) !== FALSE ){
					if ( stristr( $bg_images_file, ".png" ) !== FALSE || stristr( $bg_images_file, ".jpg" ) !== FALSE ) {
						$bg_images[] = $bg_images_url . $bg_images_file;
					}
				}
			}
		}

		/*-----------------------------------------------------------------------------------*/
		/* TO DO: Add options/functions that use these */
		/*-----------------------------------------------------------------------------------*/

		//More Options
		$uploads_arr = wp_upload_dir();
		$all_uploads_path = $uploads_arr['path'];
		$all_uploads = get_option( 'of_uploads' );
		$other_entries = array(
			'Select a number:',
			'1',
			'2',
			'3',
			'4',
			'5',
			'6',
			'7',
			'8',
			'9',
			'10',
			'11',
			'12',
			'13',
			'14',
			'15',
			'16',
			'17',
			'18',
			'19'
		);
		$body_repeat = array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' );
		$body_pos = array(
			'top left',
			'top center',
			'top right',
			'center left',
			'center center',
			'center right',
			'bottom left',
			'bottom center',
			'bottom right'
		);

		// Image Alignment radio box
		$of_options_thumb_align = array( 'alignleft' => 'Left', 'alignright' => 'Right', 'aligncenter' => 'Center' );

		// Image Links to Options
		$of_options_image_link_to = array( 'image' => 'The Image', 'post' => 'The Post' );

		$web_safe_fonts = array(
			'Georgia, serif' => 'Georgia, serif',
			'"Palatino Linotype", "Book Antiqua", Palatino, serif' => 'Palatino Linotype, Book Antiqua, Palatino, serif',
			'"Times New Roman", Times, serif' => 'Times New Roman, Times, serif',
			'Arial, Helvetica, sans-serif' => 'Arial, Helvetica, sans-serif',
			'Impact, Charcoal, sans-serif' => 'Impact, Charcoal, sans-serif',
			'"Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Lucida Sans Unicode, Lucida Grande, sans-serif',
			'Tahoma, Geneva, sans-serif' => 'Tahoma, Geneva, sans-serif',
			'"Trebuchet MS", Helvetica, sans-serif' => 'Trebuchet MS, Helvetica, sans-serif',
			'Verdana, Geneva, sans-serif' => 'Verdana, Geneva, sans-serif',
			'"Courier New", Courier, monospace' => 'Courier New, Courier, monospace',
			'"Lucida Console", Monaco, monospace' => 'Lucida Console, Monaco, monospace',
		);

		$google_fonts = us_config( 'google-fonts' );
		$google_font_names = array();
		foreach ( $google_fonts as $font_name => &$font_params ) {
			$google_font_names[ $font_name ] = $font_name;
		}

		$google_fonts_subsets = array(
			'latin' => 'latin',
			'latin-ext' => 'latin-ext',
			'cyrillic' => 'cyrillic',
			'cyrillic-ext' => 'cyrillic-ext',
			'greek' => 'greek',
			'greek-ext' => 'greek-ext',
			'vietnamese' => 'vietnamese',
			'khmer' => 'khmer',
		);

		$url_fontawesome = 'http://fontawesome.io/icons/';
		$url_mdfi = 'http://designjockey.github.io/material-design-fonticons/';
		/*-----------------------------------------------------------------------------------*/
		/* The Options Array */
		/*-----------------------------------------------------------------------------------*/

		// Set the Options Array
		global $of_options;
		$of_options = array();
		//$prefix = 'us_'

		$of_options[] = array(
			'name' => __( 'General Settings', 'us' ),
			'type' => 'heading',
			'classname' => 'generalsettings',
		);

		$url = ADMIN_DIR . 'assets/images/';
		$of_options[] = array(
			'name' => __( 'Logo Type', 'us' ),
			'id' => 'logo_type',
			'std' => 'text',
			'type' => 'images',
			'options' => array(
				'text' => $url . 'logo-text.png',
				'img' => $url . 'logo-img.png',
			),
		);

		$of_options[] = array(
			'name' => __( 'Logo Text', 'us' ),
			'desc' => __( 'Add text which will be shown as logo. Better keep it short.', 'us' ),
			'id' => 'logo_text',
			'std' => 'LOGO',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => __( 'Logo Text Size', 'us' ),
			'desc' => __( 'Set value from 12 to 60 (px)', 'us' ),
			'id' => 'logo_font_size',
			'type' => 'sliderui',
			'std' => '27',
			'min' => '12',
			'step' => '1',
			'max' => '60',
		);

		$of_options[] = array(
			'name' => __( 'Logo Text Size for Tablets <span class="w-info">(screen width < 900px)</span>', 'us' ),
			'desc' => __( 'Set value from 12 to 60 (px)', 'us' ),
			'id' => 'logo_font_size_tablets',
			'type' => 'sliderui',
			'std' => '24',
			'min' => '12',
			'step' => '1',
			'max' => '60',
		);

		$of_options[] = array(
			'name' => __( 'Logo Text Size for Mobiles <span class="w-info">(screen width < 600px)</span>', 'us' ),
			'desc' => __( 'Set value from 12 to 60 (px)', 'us' ),
			'id' => 'logo_font_size_mobiles',
			'type' => 'sliderui',
			'std' => '20',
			'min' => '12',
			'step' => '1',
			'max' => '60',
		);

		$of_options[] = array(
			'name' => __( 'Logo Image', 'us' ),
			'desc' => __( 'Maximum recommended size is 300px of height (also for retina displays)', 'us' ),
			'id' => 'logo_image',
			'std' => '',
			'type' => 'upload',
		);

		$of_options[] = array(
			'name' => __( 'Logo Image in the Transparent Header <span class="w-info">(optional)</span>', 'us' ),
			'desc' => __( 'Maximum recommended size is 300px of height (also for retina displays)', 'us' ),
			'id' => 'logo_image_transparent',
			'std' => '',
			'type' => 'upload',
		);

		$of_options[] = array(
			'name' => __( 'Logo Height', 'us' ),
			'desc' => __( 'Set value from 20 to 150 (px)', 'us' ),
			'id' => 'logo_height',
			'type' => 'sliderui',
			'std' => '60',
			'min' => '20',
			'step' => '1',
			'max' => '150',
		);

		$of_options[] = array(
			'name' => __( 'Logo Height in the Sticky Header', 'us' ),
			'desc' => __( 'Set value from 20 to 150 (px)', 'us' ),
			'id' => 'logo_height_sticky',
			'type' => 'sliderui',
			'std' => '60',
			'min' => '20',
			'step' => '1',
			'max' => '150',
		);

		$of_options[] = array(
			'name' => __( 'Logo Height for Tablets <span class="w-info">(screen width < 900px)</span>', 'us' ),
			'desc' => __( 'Set value from 20 to 80 (px)', 'us' ),
			'id' => 'logo_height_tablets',
			'type' => 'sliderui',
			'std' => '40',
			'min' => '20',
			'step' => '1',
			'max' => '80',
		);

		$of_options[] = array(
			'name' => __( 'Logo Height for Mobiles <span class="w-info">(screen width < 600px)</span>', 'us' ),
			'desc' => __( 'Set value from 20 to 50 (px)', 'us' ),
			'id' => 'logo_height_mobiles',
			'type' => 'sliderui',
			'std' => '30',
			'min' => '20',
			'step' => '1',
			'max' => '50',
		);

		$of_options[] = array(
			'name' => __( 'Logo Width', 'us' ),
			'desc' => __( 'Set value from 100 to 400 (px)', 'us' ),
			'id' => 'logo_width',
			'type' => 'sliderui',
			'std' => '200',
			'min' => '100',
			'step' => '1',
			'max' => '400',
		);

		$of_options[] = array(
			'name' => __( 'Favicon', 'us' ),
			'desc' => __( 'Upload an ICO/PNG/GIF image that will represent your website\'s favicon', 'us' ),
			'id' => 'favicon',
			'std' => '',
			'type' => 'upload'
		);

		$of_options[] = array(
			'name' => __( 'Sidebar at Regular Pages', 'us' ),
			'desc' => __( 'Select sidebar position or disable sidebar for all regular pages', 'us' ),
			'id' => 'page_sidebar',
			'std' => 'none',
			'type' => 'select',
			'options' => array(
				'right' => __( 'Right', 'us' ),
				'left' => __( 'Left', 'us' ),
				'none' => __( 'No Sidebar', 'us' ),
			)
		);

		$of_options[] = array(
			'name' => __( 'Page Comments', 'us' ),
			'desc' => __( 'Enable comments for all regular pages', 'us' ),
			'id' => 'page_comments',
			'std' => 0,
			'type' => 'switch'
		);

		$of_options[] = array(
			'name' => __( 'Theme Options CSS File', 'us' ),
			'desc' => __( 'Store Theme Options generated styles in a separate CSS file', 'us' ),
			'id' => 'generate_css_file',
			'std' => 1,
			'type' => 'switch'
		);

		if ( class_exists( 'Vc_Manager' ) ) {
			$of_options[] = array(
				'name' => __( 'Unsupported Features of Visual Composer', 'us' ),
				'desc' => __( 'Enable theme-disabled VC\'s shortcodes and features', 'us' ),
				'id' => 'enable_unsupported_vc_shortcodes',
				'std' => 0,
				'folds' => 1,
				'type' => 'switch'
			);
			$of_options[] = array(
				'name' => '',
				'std' => __( '<strong>Note</strong>: Enabling the theme-disabled VC\'s shortcodes and features will reduce your website load speed and performance. Also some of the theme-disabled shortcodes could be not fully supported by the theme and/or not styled properly.', 'us' ),
				'id' => 'enable_unsupported_vc_shortcodes_info',
				'type' => 'info',
				'fold' => 'enable_unsupported_vc_shortcodes',
			);
		}

		$of_options[] = array(
			'name' => __( 'Custom HTML Code', 'us' ),
			'desc' => __( 'Paste your custom code here, it will be added into the footer section of your site. You can use JS code with &lt;script&gt;&lt;/script&gt; tags. Also you can add Google Analytics or other tracking code into this field.', 'us' ),
			'id' => 'custom_html',
			'std' => '',
			'type' => 'textarea'
		);

		/*--------------------------------------------------------------------------*/

		$of_options[] = array(
			'name' => __( 'Layout Options', 'us' ),
			'type' => 'heading',
			'classname' => 'layoutoptions',
		);

		$of_options[] = array(
			'name' => __( 'Responsive Layout', 'us' ),
			'desc' => __( 'Enable responsive layout', 'us' ),
			'id' => 'responsive_layout',
			'std' => 1,
			'type' => 'switch'
		);
		$of_options[] = array(
			'name' => __( 'Site Canvas Layout', 'us' ),
			'id' => 'canvas_layout',
			'std' => 'wide',
			'type' => 'images',
			'options' => array(
				'wide' => $url . 'canvas-wide.png',
				'boxed' => $url . 'canvas-boxed.png',
			),
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Body Background Color', 'us' ),
			'id' => 'color_body_bg',
			'std' => '#eee',
			'type' => 'color'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Set Body Background Image', 'us' ),
			'id' => 'body_bg_image',
			'type' => 'upload'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Background Image repeat', 'us' ),
			'id' => 'body_bg_image_repeat',
			'type' => 'select',
			'std' => 'repeat',
			'options' => array(
				'repeat' => __( 'Repeat', 'us' ),
				'repeat-x' => __( 'Repeat Horizontally', 'us' ),
				'repeat-y' => __( 'Repeat Vertically', 'us' ),
				'no-repeat' => __( 'Do Not Repeat', 'us' ),
			)
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Background Image position', 'us' ),
			'id' => 'body_bg_image_position',
			'type' => 'select',
			'std' => 'top_center',
			'options' => array(
				'top center' => __( 'Top Center', 'us' ),
				'top left' => __( 'Top Left', 'us' ),
				'top right' => __( 'Top Right', 'us' ),
				'center center' => __( 'Center Center', 'us' ),
				'center left' => __( 'Center Left', 'us' ),
				'center right' => __( 'Center Right', 'us' ),
				'bottom center' => __( 'Bottom Center', 'us' ),
				'bottom left' => __( 'Bottom Left', 'us' ),
				'bottom right' => __( 'Bottom Right', 'us' ),
			)
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Background Image attachment', 'us' ),
			'id' => 'body_bg_image_attachment',
			'type' => 'select',
			'std' => 'scroll',
			'options' => array(
				'scroll' => __( 'Scroll', 'us' ),
				'fixed' => __( 'Fixed', 'us' ),
			)
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Background Image size', 'us' ),
			'id' => 'body_bg_image_size',
			'type' => 'select',
			'std' => 'cover',
			'options' => array(
				'cover' => __( 'Cover - Image will cover the whole browser viewport', 'us' ),
				'contain' => __( 'Contain - Image will fit inside the browser viewport', 'us' ),
				'initial' => __( 'Initial size', 'us' ),
			)
		);

		$of_options[] = array(
			'name' => __( 'Site Canvas Width', 'us' ),
			'desc' => __( 'Set value from 1000 to 1400 (px)', 'us' ),
			'id' => 'site_canvas_width',
			'type' => 'sliderui',
			'std' => '1300',
			'min' => '1000',
			'step' => '10',
			'max' => '1400',
		);
		$of_options[] = array(
			'name' => __( 'Site Content Width', 'us' ),
			'desc' => __( 'Set value from 900 to 1300 (px)', 'us' ),
			'id' => 'site_content_width',
			'type' => 'sliderui',
			'std' => '1140',
			'min' => '900',
			'step' => '10',
			'max' => '1300',
		);
		$of_options[] = array(
			'name' => __( 'Sidebar Width <span class="w-info">(for pages with sidebar only)</span>', 'us' ),
			'desc' => __( 'Set value from 20 to 50 (%)', 'us' ),
			'id' => 'sidebar_width',
			'type' => 'sliderui',
			'std' => '25',
			'min' => '20',
			'step' => '1',
			'max' => '50',
		);
		$of_options[] = array(
			'name' => __( 'Content Width <span class="w-info">(for pages with sidebar only)</span>', 'us' ),
			'desc' => __( 'Set value from 50 to 80 (%)', 'us' ),
			'id' => 'content_width',
			'type' => 'sliderui',
			'std' => '68',
			'min' => '50',
			'step' => '1',
			'max' => '80',
		);
		$of_options[] = array(
			'name' => __( 'Columns Stacking Width', 'us' ),
			'desc' => __( 'When screen width is less than this value, all columns within a row will become a single column.', 'us' ),
			'id' => 'columns_stacking_width',
			'type' => 'sliderui',
			'std' => '767',
			'min' => '480',
			'step' => '1',
			'max' => '1024',
		);
		$of_options[] = array(
			'name' => __( 'Effects Disabling Width', 'us' ),
			'desc' => __( 'When screen width is less than this value, vertical parallax and animation of elements appearance will be disabled.', 'us' ),
			'id' => 'disable_effects_width',
			'type' => 'sliderui',
			'std' => '900',
			'min' => '480',
			'step' => '1',
			'max' => '1024',
		);

		/*--------------------------------------------------------------------------*/

		$of_options[] = array(
			'name' => __( 'Styling', 'us' ),
			'type' => 'heading',
			'classname' => 'styling',
		);

		$style_schemes = us_config( 'style-schemes' );
		$of_options[] = array(
			'name' => __( 'Predefined Color Style', 'us' ),
			'id' => 'color_style',
			'type' => 'select_predefined_options',
			'options' => &$style_schemes,
		);

		/*--------------------------------------*/
		$of_options[] = array(
			'name' => __( 'Custom Color Style', 'us' ),
			'desc' => __( 'Change <strong>Header</strong> colors', 'us' ),
			'id' => 'change_header_colors',
			'std' => 0,
			'folds' => 1,
			'type' => 'switch'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Header Background Color', 'us' ),
			'id' => 'color_header_bg',
			'fold' => 'change_header_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Header Text Color', 'us' ),
			'id' => 'color_header_text',
			'fold' => 'change_header_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Header Text Hover Color', 'us' ),
			'id' => 'color_header_text_hover',
			'fold' => 'change_header_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Extended Header Background Color', 'us' ),
			'id' => 'color_header_ext_bg',
			'fold' => 'change_header_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Extended Header Text Color', 'us' ),
			'id' => 'color_header_ext_text',
			'fold' => 'change_header_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Extended Header Text Hover Color', 'us' ),
			'id' => 'color_header_ext_text_hover',
			'fold' => 'change_header_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Transparent Header Text Color', 'us' ),
			'id' => 'color_header_transparent_text',
			'fold' => 'change_header_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Transparent Header Hover Text Color', 'us' ),
			'id' => 'color_header_transparent_text_hover',
			'fold' => 'change_header_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Search Screen Background Color', 'us' ),
			'id' => 'color_header_search_bg',
			'fold' => 'change_header_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Search Screen Text Color', 'us' ),
			'id' => 'color_header_search_text',
			'fold' => 'change_header_colors',
			'type' => 'color'
		);

		/*--------------------------------------*/

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Change <strong>Main Menu</strong> colors', 'us' ),
			'id' => 'change_menu_colors',
			'std' => 0,
			'folds' => 1,
			'type' => 'switch'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Transparent Menu Active Text Color', 'us' ),
			'id' => 'color_menu_transparent_active_text',
			'fold' => 'change_menu_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Menu Active Text Color', 'us' ),
			'id' => 'color_menu_active_text',
			'fold' => 'change_menu_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Menu Hover Background Color', 'us' ),
			'id' => 'color_menu_hover_bg',
			'fold' => 'change_menu_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Menu Hover Text Color', 'us' ),
			'id' => 'color_menu_hover_text',
			'fold' => 'change_menu_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Dropdown Background Color', 'us' ),
			'id' => 'color_drop_bg',
			'fold' => 'change_menu_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Dropdown Text Color', 'us' ),
			'id' => 'color_drop_text',
			'fold' => 'change_menu_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Dropdown Hover Background Color', 'us' ),
			'id' => 'color_drop_hover_bg',
			'fold' => 'change_menu_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Dropdown Hover Text Color', 'us' ),
			'id' => 'color_drop_hover_text',
			'fold' => 'change_menu_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Dropdown Active Background Color', 'us' ),
			'id' => 'color_drop_active_bg',
			'fold' => 'change_menu_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Dropdown Active Text Color', 'us' ),
			'id' => 'color_drop_active_text',
			'fold' => 'change_menu_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Menu Button Background Color', 'us' ),
			'id' => 'color_menu_button_bg',
			'fold' => 'change_menu_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Menu Button Text Color', 'us' ),
			'id' => 'color_menu_button_text',
			'fold' => 'change_menu_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Menu Button Hover Background Color', 'us' ),
			'id' => 'color_menu_button_hover_bg',
			'fold' => 'change_menu_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Menu Button Hover Text Color', 'us' ),
			'id' => 'color_menu_button_hover_text',
			'fold' => 'change_menu_colors',
			'type' => 'color'
		);

		/*--------------------------------------*/
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Change <strong>Content</strong> colors', 'us' ),
			'id' => 'change_content_colors',
			'std' => 0,
			'folds' => 1,
			'type' => 'switch'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Background Color', 'us' ),
			'id' => 'color_content_bg',
			'fold' => 'change_content_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Alternate Background Color', 'us' ),
			'id' => 'color_content_bg_alt',
			'fold' => 'change_content_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Border Color', 'us' ),
			'id' => 'color_content_border',
			'fold' => 'change_content_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Heading Color', 'us' ),
			'id' => 'color_content_heading',
			'fold' => 'change_content_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Text Color', 'us' ),
			'id' => 'color_content_text',
			'fold' => 'change_content_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Primary Color', 'us' ),
			'id' => 'color_content_primary',
			'fold' => 'change_content_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Secondary Color', 'us' ),
			'id' => 'color_content_secondary',
			'fold' => 'change_content_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Faded Elements Color', 'us' ),
			'id' => 'color_content_faded',
			'fold' => 'change_content_colors',
			'type' => 'color'
		);

		/*--------------------------------------*/
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Change <strong>SubFooter</strong> colors', 'us' ),
			'id' => 'change_subfooter_colors',
			'std' => 0,
			'folds' => 1,
			'type' => 'switch'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Background Color', 'us' ),
			'id' => 'color_subfooter_bg',
			'fold' => 'change_subfooter_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Alternate Background Color', 'us' ),
			'id' => 'color_subfooter_bg_alt',
			'fold' => 'change_subfooter_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Border Color', 'us' ),
			'id' => 'color_subfooter_border',
			'fold' => 'change_subfooter_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Heading Color', 'us' ),
			'id' => 'color_subfooter_heading',
			'fold' => 'change_subfooter_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Text Color', 'us' ),
			'id' => 'color_subfooter_text',
			'fold' => 'change_subfooter_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Link Color', 'us' ),
			'id' => 'color_subfooter_link',
			'fold' => 'change_subfooter_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Link Hover Color', 'us' ),
			'id' => 'color_subfooter_link_hover',
			'fold' => 'change_subfooter_colors',
			'type' => 'color'
		);

		/*--------------------------------------*/
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Change <strong>Footer</strong> colors', 'us' ),
			'id' => 'change_footer_colors',
			'std' => 0,
			'folds' => 1,
			'type' => 'switch'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Background Color', 'us' ),
			'id' => 'color_footer_bg',
			'fold' => 'change_footer_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Text Color', 'us' ),
			'id' => 'color_footer_text',
			'fold' => 'change_footer_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Link Color', 'us' ),
			'id' => 'color_footer_link',
			'fold' => 'change_footer_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Link Hover Color', 'us' ),
			'id' => 'color_footer_link_hover',
			'fold' => 'change_footer_colors',
			'type' => 'color'
		);

		$of_options[] = array(
			'name' => __( 'Quick CSS', 'us' ),
			'desc' => __( 'Paste your CSS code. Do not include <strong>&lt;pre&gt;&lt;/pre&gt;</strong> tags or any html tag in this field.', 'us' ),
			'id' => 'custom_css',
			'type' => 'textarea'
		);

		/*--------------------------------------------------------------------------*/

		$of_options[] = array(
			'name' => __( 'Header Options', 'us' ),
			'type' => 'heading',
			'classname' => 'headeroptions',
		);

		$of_options[] = array(
			'name' => __( 'Header Layout', 'us' ),
			'id' => 'header_options_layout',
			'std' => 1,
			'type' => 'subheading'
		);

		$of_options[] = array(
			'name' => '',
			'id' => 'header_layout',
			'std' => 'standard',
			'type' => 'images',
			'options' => array(
				'standard' => $url . 'header1.png',
				'extended' => $url . 'header2.png',
				'advanced' => $url . 'header3.png',
				'centered' => $url . 'header4.png',
				'sided' => $url . 'header5.png',
			),
		);

		$of_options[] = array(
			'name' => __( 'Transparent Header', 'us' ),
			'desc' => __( 'Make the header transparent at its initial position by default', 'us' ),
			'id' => 'header_transparent',
			'std' => 0,
			'type' => 'switch'
		);

		$of_options[] = array(
			'name' => __( 'Sticky Header', 'us' ),
			'desc' => __( 'Fix the header at the top of a page during scroll by default', 'us' ),
			'id' => 'header_sticky',
			'std' => 1,
			'folds' => 1,
			'type' => 'switch'
		);

		$of_options[] = array(
			'name' => __( 'Disable Sticky Header at width:', 'us' ),
			'desc' => __( 'When screen width is less than this value, sticky header becomes non-sticky.', 'us' ),
			'id' => 'header_sticky_disable_width',
			'std' => '900',
			'min' => '300',
			'step' => '1',
			'max' => '1200',
			'fold' => 'header_sticky',
			'type' => 'sliderui'
		);

		$of_options[] = array(
			'name' => __( 'Hidden Header', 'us' ),
			'desc' => __( 'Hide the sticky header at its initial position by default', 'us' ),
			'id' => 'header_hidden',
			'fold' => 'header_sticky',
			'std' => 0,
			'type' => 'switch'
		);

		$of_options[] = array(
			'name' => '',
			'std' => __( '<strong>Transparent</strong>, <strong>Sticky</strong> and <strong>Hidden</strong> options can be set for a separate certain page when editing it. If the options above has no effect for some page, check its Header Options.', 'us' ),
			'id' => 'header_info',
			'type' => 'info'
		);

		$of_options[] = array(
			'name' => __( 'Fullwidth Header', 'us' ),
			'desc' => __( 'Stretch header content to the screen width', 'us' ),
			'id' => 'header_fullwidth',
			'std' => 0,
			'type' => 'switch'
		);

		$of_options[] = array(
			'name' => __( 'Inverted Logo Position', 'us' ),
			'desc' => __( 'Place Logo to the right side of the Header', 'us' ),
			'id' => 'header_invert_logo_pos',
			'std' => 0,
			'type' => 'switch'
		);

		$of_options[] = array(
			'name' => __( 'Main header area height', 'us' ),
			'desc' => __( 'Set value from 50 to 150 (px)', 'us' ),
			'id' => 'header_main_height',
			'std' => '100',
			'min' => '50',
			'step' => '1',
			'max' => '150',
			'type' => 'sliderui'
		);
		$of_options[] = array(
			'name' => __( 'Sticky Main header area height', 'us' ),
			'desc' => __( 'Set value from 50 to 150 (px)', 'us' ),
			'id' => 'header_main_sticky_height_1',
			'std' => '50',
			'min' => '50',
			'step' => '1',
			'max' => '150',
			'type' => 'sliderui'
		);
		$of_options[] = array(
			'name' => __( 'Sticky Main header area height', 'us' ),
			'desc' => __( 'Set value from 0 to 150 (px)', 'us' ),
			'id' => 'header_main_sticky_height_2',
			'std' => '50',
			'min' => '0',
			'step' => '1',
			'max' => '150',
			'type' => 'sliderui'
		);
		$of_options[] = array(
			'name' => __( 'Extra header area height', 'us' ),
			'desc' => __( 'Set value from 40 to 60 (px)', 'us' ),
			'id' => 'header_extra_height',
			'std' => '50',
			'min' => '40',
			'step' => '1',
			'max' => '60',
			'type' => 'sliderui'
		);
		$of_options[] = array(
			'name' => __( 'Sticky Extra header area height', 'us' ),
			'desc' => __( 'Set value from 0 to 60 (px)', 'us' ),
			'id' => 'header_extra_sticky_height_1',
			'std' => '40',
			'min' => '0',
			'step' => '1',
			'max' => '60',
			'type' => 'sliderui'
		);
		$of_options[] = array(
			'name' => __( 'Sticky Extra header area height', 'us' ),
			'desc' => __( 'Set value from 40 to 60 (px)', 'us' ),
			'id' => 'header_extra_sticky_height_2',
			'std' => '40',
			'min' => '40',
			'step' => '1',
			'max' => '60',
			'type' => 'sliderui'
		);

		$of_options[] = array(
			'name' => __( 'Header Width', 'us' ),
			'desc' => __( 'Set value from 200 to 400 (px)', 'us' ),
			'id' => 'header_main_width',
			'type' => 'sliderui',
			'std' => '300',
			'min' => '200',
			'step' => '1',
			'max' => '400',
		);

		$of_options[] = array(
			'name' => __( 'Header Scroll Breakpoint', 'us' ),
			'desc' => __( 'This option sets scroll distance (in pixels) from the top of a page after which the header will be shrunk if it\'s sticky, becomes visible if it\'s hidden, and becomes solid if it\'s transparent.', 'us' ),
			'id' => 'header_scroll_breakpoint',
			'std' => '100',
			'min' => '1',
			'step' => '1',
			'max' => '200',
			'fold' => 'header_sticky',
			'type' => 'sliderui'
		);

		$of_options[] = array(
			'name' => __( 'Header Elements', 'us' ),
			'id' => 'header_options_elements',
			'std' => 1,
			'type' => 'subheading'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Show <strong>Search Widget</strong> in the Header', 'us' ),
			'id' => 'header_search_show',
			'std' => 1,
			'type' => 'switch'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Show <strong>Contacts Widget</strong> in the Header', 'us' ),
			'id' => 'header_contacts_show',
			'std' => 0,
			'folds' => 1,
			'type' => 'switch'
		);

		$of_options[] = array(
			'name' => __( 'Contact Phone Number', 'us' ),
			'desc' => '',
			'id' => 'header_contacts_phone',
			'std' => '',
			'fold' => 'header_contacts_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => __( 'Contact Email', 'us' ),
			'desc' => '',
			'id' => 'header_contacts_email',
			'std' => '',
			'fold' => 'header_contacts_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => __( 'Contact Custom Icon', 'us' ),
			'desc' => sprintf( __( '<a href="%s" target="_blank">FontAwesome</a> or <a href="%s" target="_blank">Material Design</a> icon', 'us' ), $url_fontawesome, $url_mdfi ),
			'id' => 'header_contacts_custom_icon',
			'std' => '',
			'fold' => 'header_contacts_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => __( 'Contact Custom Text', 'us' ),
			'desc' => __( 'Use can use HTML tags in this field (e.g. &lt;a href=""&gt;&lt;/a&gt; for adding links)', 'us' ),
			'id' => 'header_contacts_custom_text',
			'std' => '',
			'fold' => 'header_contacts_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Show <strong>Social Links</strong> in the Header', 'us' ),
			'id' => 'header_socials_show',
			'std' => 0,
			'folds' => 1,
			'type' => 'switch'
		);

		$of_options[] = array(
			'name' => 'Facebook',
			'desc' => '',
			'id' => 'header_socials_facebook',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'Twitter',
			'desc' => '',
			'id' => 'header_socials_twitter',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'Google+',
			'desc' => '',
			'id' => 'header_socials_google',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'LinkedIn',
			'desc' => '',
			'id' => 'header_socials_linkedin',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'YouTube',
			'desc' => '',
			'id' => 'header_socials_youtube',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'Vimeo',
			'desc' => '',
			'id' => 'header_socials_vimeo',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'Flickr',
			'desc' => '',
			'id' => 'header_socials_flickr',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'Instagram',
			'desc' => '',
			'id' => 'header_socials_instagram',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'Behance',
			'desc' => '',
			'id' => 'header_socials_behance',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'Xing',
			'desc' => '',
			'id' => 'header_socials_xing',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'Pinterest',
			'desc' => '',
			'id' => 'header_socials_pinterest',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'Skype',
			'desc' => '',
			'id' => 'header_socials_skype',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'Tumblr',
			'desc' => '',
			'id' => 'header_socials_tumblr',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'Dribbble',
			'desc' => '',
			'id' => 'header_socials_dribbble',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'Vkontakte',
			'desc' => '',
			'id' => 'header_socials_vk',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'SoundCloud',
			'desc' => '',
			'id' => 'header_socials_soundcloud',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'Yelp',
			'desc' => '',
			'id' => 'header_socials_yelp',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'Twitch',
			'desc' => '',
			'id' => 'header_socials_twitch',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'DeviantArt',
			'desc' => '',
			'id' => 'header_socials_deviantart',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'Foursquare',
			'desc' => '',
			'id' => 'header_socials_foursquare',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'GitHub',
			'desc' => '',
			'id' => 'header_socials_github',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'Odnoklassniki',
			'desc' => '',
			'id' => 'header_socials_odnoklassniki',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => '500px',
			'desc' => '',
			'id' => 'header_socials_s500px',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'Houzz',
			'desc' => '',
			'id' => 'header_socials_houzz',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'RSS',
			'desc' => '',
			'id' => 'header_socials_rss',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => __( 'Custom Item Icon', 'us' ),
			'desc' => sprintf( __( '<a href="%s" target="_blank">FontAwesome</a> or <a href="%s" target="_blank">Material Design</a> icon', 'us' ), $url_fontawesome, $url_mdfi ),
			'id' => 'header_socials_custom_icon',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => __( 'Custom Item URL', 'us' ),
			'id' => 'header_socials_custom_url',
			'std' => '',
			'fold' => 'header_socials_show',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Show <strong>Language Widget</strong> in the Header', 'us' ),
			'id' => 'header_language_show',
			'std' => 0,
			'folds' => 1,
			'type' => 'switch'
		);

		$of_options[] = array(
			'name' => __( 'Languages Source', 'us' ),
			'id' => 'header_language_source',
			'std' => 'own',
			'type' => 'select',
			'options' => array(
				'own' => __( 'My own links', 'us' ),
				'wpml' => __( 'WPML switcher', 'us' ),
			),
			'fold' => 'header_language_show',
		);

		$of_options[] = array(
			'name' => __( 'Links Title', 'us' ),
			'desc' => __( 'This text will be shown as the first active item of the dropdown menu.', 'us' ),
			'id' => 'header_link_title',
			'std' => '',
			'type' => 'text',
			'fold' => 'header_language_show',
		);

		$of_options[] = array(
			'name' => __( 'Links Quantity', 'us' ),
			'id' => 'header_link_qty',
			'std' => '2',
			'type' => 'select',
			'options' => array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',
				'7' => '7',
				'8' => '8',
				'9' => '9',
			),
			'fold' => 'header_language_show',
		);

		$of_options[] = array(
			'name' => __( 'Link 1', 'us' ),
			'desc' => __( 'Link Label', 'us' ),
			'id' => 'header_link_1_label',
			'std' => '',
			'type' => 'text',
			'fold' => 'header_language_show',
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Link URL', 'us' ),
			'id' => 'header_link_1_url',
			'std' => '',
			'type' => 'text',
			'fold' => 'header_language_show',
		);

		$of_options[] = array(
			'name' => __( 'Link 2', 'us' ),
			'desc' => __( 'Link Label', 'us' ),
			'id' => 'header_link_2_label',
			'std' => '',
			'type' => 'text',
			'fold' => 'header_language_show',
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Link URL', 'us' ),
			'id' => 'header_link_2_url',
			'std' => '',
			'type' => 'text',
			'fold' => 'header_language_show',
		);

		$of_options[] = array(
			'name' => __( 'Link 3', 'us' ),
			'desc' => __( 'Link Label', 'us' ),
			'id' => 'header_link_3_label',
			'std' => '',
			'type' => 'text',
			'fold' => 'header_language_show',
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Link URL', 'us' ),
			'id' => 'header_link_3_url',
			'std' => '',
			'type' => 'text',
			'fold' => 'header_language_show',
		);

		$of_options[] = array(
			'name' => __( 'Link 4', 'us' ),
			'desc' => __( 'Link Label', 'us' ),
			'id' => 'header_link_4_label',
			'std' => '',
			'type' => 'text',
			'fold' => 'header_language_show',
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Link URL', 'us' ),
			'id' => 'header_link_4_url',
			'std' => '',
			'type' => 'text',
			'fold' => 'header_language_show',
		);

		$of_options[] = array(
			'name' => __( 'Link 5', 'us' ),
			'desc' => __( 'Link Label', 'us' ),
			'id' => 'header_link_5_label',
			'std' => '',
			'type' => 'text',
			'fold' => 'header_language_show',
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Link URL', 'us' ),
			'id' => 'header_link_5_url',
			'std' => '',
			'type' => 'text',
			'fold' => 'header_language_show',
		);

		$of_options[] = array(
			'name' => __( 'Link 6', 'us' ),
			'desc' => __( 'Link Label', 'us' ),
			'id' => 'header_link_6_label',
			'std' => '',
			'type' => 'text',
			'fold' => 'header_language_show',
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Link URL', 'us' ),
			'id' => 'header_link_6_url',
			'std' => '',
			'type' => 'text',
			'fold' => 'header_language_show',
		);

		$of_options[] = array(
			'name' => __( 'Link 7', 'us' ),
			'desc' => __( 'Link Label', 'us' ),
			'id' => 'header_link_7_label',
			'std' => '',
			'type' => 'text',
			'fold' => 'header_language_show',
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Link URL', 'us' ),
			'id' => 'header_link_7_url',
			'std' => '',
			'type' => 'text',
			'fold' => 'header_language_show',
		);

		$of_options[] = array(
			'name' => __( 'Link 8', 'us' ),
			'desc' => __( 'Link Label', 'us' ),
			'id' => 'header_link_8_label',
			'std' => '',
			'type' => 'text',
			'fold' => 'header_language_show',
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Link URL', 'us' ),
			'id' => 'header_link_8_url',
			'std' => '',
			'type' => 'text',
			'fold' => 'header_language_show',
		);

		$of_options[] = array(
			'name' => __( 'Link 9', 'us' ),
			'desc' => __( 'Link Label', 'us' ),
			'id' => 'header_link_9_label',
			'std' => '',
			'type' => 'text',
			'fold' => 'header_language_show',
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Link URL', 'us' ),
			'id' => 'header_link_9_url',
			'std' => '',
			'type' => 'text',
			'fold' => 'header_language_show',
		);

		/*--------------------------------------------------------------------------*/

		$of_options[] = array(
			'name' => __( 'Menu Options', 'us' ),
			'type' => 'heading',
			'classname' => 'menuoptions',
		);

		$of_options[] = array(
			'name' => __( 'Menu Dropdown Effect', 'us' ),
			'desc' => '',
			'id' => 'menu_dropdown_effect',
			'std' => 'mdesign',
			'type' => 'select',
			'options' => array(
				'opacity' => __( 'FadeIn', 'us' ),
				'height' => __( 'FadeIn + SlideDown', 'us' ),
				'mdesign' => __( 'Material Design Effect', 'us' ),
			)
		);

		$of_options[] = array(
			'name' => __( 'Mobile Menu at width (px):', 'us' ),
			'desc' => __( 'When screen width is less than this value, main menu transforms to mobile-friendly layout.', 'us' ),
			'id' => 'menu_mobile_width',
			'std' => '900',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => __( 'Mobile Menu Behaviour', 'us' ),
			'desc' => __( 'Open sub items on click at menu titles (instead of arrows)', 'us' ),
			'id' => 'menu_togglable_type',
			'std' => 1,
			'type' => 'switch'
		);

		/*--------------------------------------------------------------------------*/

		$of_options[] = array(
			'name' => __( 'Title Bar Options', 'us' ),
			'type' => 'heading',
			'classname' => 'titlebaroptions',
		);

		$of_options[] = array(
			'name' => __( 'Title Bar Content', 'us' ),
			'desc' => __( 'This option is applied to all regular pages, Portfolio Item pages, Archive pages and Search Results page.', 'us' ),
			'id' => 'titlebar_content',
			'std' => 'all',
			'type' => 'select',
			'options' => array(
				'all' => __( 'Captions and Breadcrumbs / Arrows', 'us' ),
				'caption' => __( 'Captions Only', 'us' ),
				'hide' => __( 'Hide Title Bar', 'us' ),
			)
		);

		$of_options[] = array(
			'name' => __( 'Title Bar Size', 'us' ),
			'desc' => __( 'This option is applied to all pages.', 'us' ),
			'id' => 'titlebar_size',
			'std' => 'large',
			'type' => 'select',
			'options' => array(
				'small' => __( 'Ultra Compact', 'us' ),
				'medium' => __( 'Compact', 'us' ),
				'large' => __( 'Large', 'us' ),
				'huge' => __( 'Huge', 'us' ),
			)
		);

		$of_options[] = array(
			'name' => __( 'Title Bar Color Style', 'us' ),
			'desc' => __( 'This option is applied to all pages.', 'us' ),
			'id' => 'titlebar_color',
			'std' => 'alternate',
			'type' => 'select',
			'options' => array(
				'default' => __( 'Content bg | Content text', 'us' ),
				'alternate' => __( 'Alternate bg | Content text', 'us' ),
				'primary' => __( 'Primary bg | White text', 'us' ),
				'secondary' => __( 'Secondary bg | White text', 'us' ),
			)
		);

		$of_options[] = array(
			'name' => '',
			'std' => __( '<strong>Title Bar options</strong> can be set for a separate certain page when editing it. If the options above has no effect for some page, check its Title Bar Options.', 'us' ),
			'id' => 'titlebar_info',
			'type' => 'info'
		);

		/*--------------------------------------------------------------------------*/

		$of_options[] = array(
			'name' => __( 'Footer Options', 'us' ),
			'type' => 'heading',
			'classname' => 'footeroptions',
		);

		$of_options[] = array(
			'name' => __( 'Subfooter', 'us' ),
			'desc' => __( 'Show <strong>Subfooter</strong> (widgets area)', 'us' ),
			'id' => 'footer_show_top',
			'std' => 0,
			'folds' => 1,
			'type' => 'switch'
		);

		$of_options[] = array(
			'name' => __( 'Subfooter Columns', 'us' ),
			'desc' => sprintf( __( 'Set number of columns in Subfooter. You can populate these columns with <a target="_blank" href="%s">widgets</a>.', 'us' ), admin_url() . 'widgets.php' ),
			'id' => 'footer_columns',
			'std' => 3,
			'type' => 'select',
			'fold' => 'footer_show_top',
			'options' => array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4'
			)
		);

		$of_options[] = array(
			'name' => __( 'Footer', 'us' ),
			'desc' => __( 'Show <strong>Footer</strong> (copyright and menu area)', 'us' ),
			'id' => 'footer_show_bottom',
			'std' => 1,
			'folds' => 1,
			'type' => 'switch'
		);

		$of_options[] = array(
			'name' => __( 'Copyright Text', 'us' ),
			'desc' => '',
			'id' => 'footer_copyright',
			'std' => 'Any text goes here',
			'fold' => 'footer_show_bottom',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => __( 'Typography', 'us' ),
			'type' => 'heading',
			'classname' => 'typography',
		);

		$of_options[] = array(
			'name' => __( 'Headings', 'us' ),
			'desc' => '',
			'id' => 'heading_font_family',
			'std' => 'Roboto',
			'type' => 'select_google_font',
			'preview' => array(
				'text' => 'Heading Font Preview', //this is the text from preview box
				'size' => '30px' //this is the text size from preview box
			),
			'options' => array(
				'web_safe_fonts' => $web_safe_fonts,
				'google_fonts' => $google_font_names,
			),
		);

		$of_options[] = array(
			'name' => __( 'Sizes on Desktops', 'us' ),
			'desc' => __( '<strong>Heading 1</strong> font size', 'us' ),
			'id' => 'h1_fontsize',
			'std' => '40',
			'class' => 'font',
			'type' => 'text'
		);
		$of_options[] = array(
			'name' => __( 'Sizes on Mobiles', 'us' ),
			'desc' => __( '<strong>Heading 1</strong> font size', 'us' ),
			'id' => 'h1_fontsize_mobile',
			'std' => '30',
			'class' => 'font',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( '<strong>Heading 2</strong> font size', 'us' ),
			'id' => 'h2_fontsize',
			'std' => '34',
			'class' => 'font',
			'type' => 'text'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( '<strong>Heading 2</strong> font size', 'us' ),
			'id' => 'h2_fontsize_mobile',
			'std' => '26',
			'class' => 'font',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( '<strong>Heading 3</strong> font size', 'us' ),
			'id' => 'h3_fontsize',
			'std' => '28',
			'class' => 'font',
			'type' => 'text'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( '<strong>Heading 3</strong> font size', 'us' ),
			'id' => 'h3_fontsize_mobile',
			'std' => '22',
			'class' => 'font',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( '<strong>Heading 4</strong> font size', 'us' ),
			'id' => 'h4_fontsize',
			'std' => '24',
			'class' => 'font',
			'type' => 'text'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( '<strong>Heading 4</strong> font size', 'us' ),
			'id' => 'h4_fontsize_mobile',
			'std' => '20',
			'class' => 'font',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( '<strong>Heading 5</strong> font size', 'us' ),
			'id' => 'h5_fontsize',
			'std' => '20',
			'class' => 'font',
			'type' => 'text'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( '<strong>Heading 5</strong> font size', 'us' ),
			'id' => 'h5_fontsize_mobile',
			'std' => '18',
			'class' => 'font',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( '<strong>Heading 6</strong> font size', 'us' ),
			'id' => 'h6_fontsize',
			'std' => '18',
			'class' => 'font',
			'type' => 'text'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( '<strong>Heading 6</strong> font size', 'us' ),
			'id' => 'h6_fontsize_mobile',
			'std' => '16',
			'class' => 'font',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => __( 'Regular Text', 'us' ),
			'desc' => '',
			'id' => 'body_font_family',
			'std' => 'Roboto',
			'type' => 'select_google_font',
			'preview' => array(
				'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec condimentum tellus purus condimentum pulvinar. Duis cursus bibendum dui, eget iaculis urna pharetra. Aenean semper nec ipsum vitae mollis.',
				//this is the text from preview box
				'size' => '15px'
				//this is the text size from preview box
			),
			'options' => array(
				'web_safe_fonts' => $web_safe_fonts,
				'google_fonts' => $google_font_names,
			),
		);

		$of_options[] = array(
			'name' => __( 'Sizes on Desktops', 'us' ),
			'desc' => __( 'Font size', 'us' ),
			'id' => 'body_fontsize',
			'std' => '14',
			'class' => 'font',
			'type' => 'text'
		);
		$of_options[] = array(
			'name' => __( 'Sizes on Mobiles', 'us' ),
			'desc' => __( 'Font size', 'us' ),
			'id' => 'body_fontsize_mobile',
			'std' => '13',
			'class' => 'font',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Line height', 'us' ),
			'id' => 'body_lineheight',
			'std' => '24',
			'class' => 'font',
			'type' => 'text'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Line height', 'us' ),
			'id' => 'body_lineheight_mobile',
			'std' => '23',
			'class' => 'font',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => __( 'Main Menu Text', 'us' ),
			'desc' => '',
			'id' => 'menu_font_family',
			'std' => 'Roboto',
			'type' => 'select_google_font',
			'preview' => array(
				'text' => 'Home&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;About&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Services&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Portfolio',
				//this is the text from preview box
				'size' => '16px'
				//this is the text size from preview box
			),
			'options' => array(
				'web_safe_fonts' => $web_safe_fonts,
				'google_fonts' => $google_font_names,
			),
		);

		$of_options[] = array(
			'name' => __( 'Sizes for Default Menu', 'us' ),
			'desc' => __( 'Font size of <strong>main</strong> items', 'us' ),
			'id' => 'menu_fontsize',
			'std' => '16',
			'class' => 'font',
			'type' => 'text'
		);
		$of_options[] = array(
			'name' => __( 'Sizes for Mobile Menu', 'us' ),
			'desc' => __( 'Font size of <strong>main</strong> items', 'us' ),
			'id' => 'menu_fontsize_mobile',
			'std' => '16',
			'class' => 'font',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Font size of <strong>sub</strong> items', 'us' ),
			'id' => 'menu_sub_fontsize',
			'std' => '15',
			'class' => 'font',
			'type' => 'text'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Font size of <strong>sub</strong> items', 'us' ),
			'id' => 'menu_sub_fontsize_mobile',
			'std' => '15',
			'class' => 'font',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => __( 'Subset', 'us' ),
			'desc' => __( 'Select characters subset for Google fonts. <strong>Please note: some fonts does not support particular subsets!</strong>', 'us' ),
			'id' => 'font_subset',
			'std' => 'latin',
			'type' => 'select',
			'options' => $google_fonts_subsets,
		);

		$of_options[] = array(
			'name' => __( 'Portfolio Options', 'us' ),
			'type' => 'heading',
			'classname' => 'portfoliooptions',
		);

		$of_options[] = array(
			'name' => __( 'Sidebar at Portfolio Items', 'us' ),
			'desc' => __( 'Select sidebar position or disable sidebar for all Portfolio Item pages', 'us' ),
			'id' => 'portfolio_sidebar',
			'std' => 'none',
			'type' => 'select',
			'options' => array(
				'right' => __( 'Right', 'us' ),
				'left' => __( 'Left', 'us' ),
				'none' => __( 'No Sidebar', 'us' ),
			)
		);

		$of_options[] = array(
			'name' => __( 'Portfolio Comments', 'us' ),
			'desc' => __( 'Enable comments for Portfolio Item pages', 'us' ),
			'id' => 'portfolio_comments',
			'std' => 0,
			'type' => 'switch'
		);

		$of_options[] = array(
			'name' => __( 'Navigation Within a Category', 'us' ),
			'desc' => __( 'Enable previous/next portfolio item navigation within a category', 'us' ),
			'id' => 'portfolio_prevnext_category',
			'std' => 0,
			'type' => 'switch'
		);

		$of_options[] = array(
			'name' => __( 'Portfolio Slug', 'us' ),
			'desc' => '',
			'id' => 'portfolio_slug',
			'std' => 'portfolio',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => 'Portfolio Slug Note',
			'std' => sprintf( __( 'Please go to <a href="%s">Permalinks Settings</a> and hit "Save Changes" button once after each time you change <strong>Portfolio Slug</strong> field. This will regenerate permalinks so they will match new Portfolio slug.', 'us' ), admin_url( 'options-permalink.php' ) ),
			'id' => 'portfolio_info',
			'type' => 'info'
		);

		$of_options[] = array(
			'name' => __( 'Blog Options', 'us' ),
			'type' => 'heading',
			'classname' => 'blogoptions',
		);

		$of_options[] = array(
			'name' => __( 'Post Pages', 'us' ),
			'id' => 'blog_options_post_pages',
			'std' => 1,
			'type' => 'subheading'
		);
		$of_options[] = array(
			'name' => __( 'Sidebar Position', 'us' ),
			'desc' => __( 'Select sidebar position or disable sidebar for all post pages', 'us' ),
			'id' => 'post_sidebar',
			'std' => 'right',
			'type' => 'select',
			'options' => array(
				'right' => __( 'Right', 'us' ),
				'left' => __( 'Left', 'us' ),
				'none' => __( 'No Sidebar', 'us' ),
			)
		);
		$of_options[] = array(
			'name' => __( 'Post Preview Layout', 'us' ),
			'desc' => __( 'Select Featured Image Layout for all post pages. You can set it for a separate certain post when editing it.', 'us' ),
			'id' => 'post_preview_layout',
			'std' => 'basic',
			'type' => 'select',
			'options' => array(
				'basic' => __( 'Standard', 'us' ),
				'modern' => __( 'Modern', 'us' ),
				'none' => __( 'No Preview', 'us' ),
			)
		);
		$of_options[] = array(
			'name' => __( 'Post Elements', 'us' ),
			'id' => 'post_meta',
			'type' => 'title'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Date', 'us' ),
			'id' => 'post_meta_date',
			'std' => 1,
			'type' => 'checkbox'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Author', 'us' ),
			'id' => 'post_meta_author',
			'std' => 1,
			'type' => 'checkbox'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Categories', 'us' ),
			'id' => 'post_meta_categories',
			'std' => 1,
			'type' => 'checkbox'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Comments number', 'us' ),
			'id' => 'post_meta_comments',
			'std' => 1,
			'type' => 'checkbox'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Tags', 'us' ),
			'id' => 'post_meta_tags',
			'std' => 1,
			'type' => 'checkbox'
		);
		$of_options[] = array(
			'name' => __( 'Author Box', 'us' ),
			'desc' => __( 'Show box with information about post author', 'us' ),
			'id' => 'post_author_box',
			'std' => 0,
			'type' => 'switch'
		);
		$of_options[] = array(
			'name' => __( 'Prev/Next Navigation', 'us' ),
			'desc' => __( 'Show links to previous/next posts', 'us' ),
			'id' => 'post_nav',
			'std' => 0,
			'type' => 'switch'
		);
		$of_options[] = array(
			'name' => __( 'Related Posts', 'us' ),
			'desc' => __( 'Show list of posts with same tags at every post page', 'us' ),
			'id' => 'post_related',
			'std' => 1,
			'folds' => 1,
			'type' => 'switch'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Select layout of related posts', 'us' ),
			'id' => 'post_related_layout',
			'std' => 'compact',
			'type' => 'select',
			'fold' => 'post_related',
			'options' => array(
				'compact' => __( 'Compact (without preview)', 'us' ),
				'related' => __( 'Standard (3 columns with preview)', 'us' ),
			)
		);

		$of_options[] = array(
			'name' => __( 'Default Front Page', 'us' ),
			'id' => 'blog_options_front_page',
			'std' => 1,
			'type' => 'subheading'
		);
		$of_options[] = array(
			'name' => __( 'Sidebar Position', 'us' ),
			'desc' => __( 'Select sidebar position or disable sidebar for Default Front page', 'us' ),
			'id' => 'blog_sidebar',
			'std' => 'right',
			'type' => 'select',
			'options' => array(
				'right' => __( 'Right', 'us' ),
				'left' => __( 'Left', 'us' ),
				'none' => __( 'No Sidebar', 'us' ),
			)
		);
		$of_options[] = array(
			'name' => __( 'Page Layout', 'us' ),
			'desc' => __( 'Select layout for Default Front page', 'us' ),
			'id' => 'blog_layout',
			'std' => 'large',
			'type' => 'select',
			'options' => array(
				'large' => __( 'Large Image', 'us' ),
				'smallcircle' => __( 'Small Image', 'us' ),
				'grid' => __( 'Regular Grid', 'us' ),
				'masonry' => __( 'Masonry Grid', 'us' ),
				'compact' => __( 'Compact', 'us' ),
			)
		);
		$of_options[] = array(
			'name' => __( 'Posts Content', 'us' ),
			'desc' => __( 'Select type of posts content which shows for Default Front page', 'us' ),
			'id' => 'blog_content_type',
			'std' => 'excerpt',
			'type' => 'select',
			'options' => array(
				'excerpt' => __( 'Excerpt', 'us' ),
				'content' => __( 'Full Content', 'us' ),
				'none' => __( 'None', 'us' ),
			)
		);
		$of_options[] = array(
			'name' => __( 'Pagination', 'us' ),
			'desc' => __( 'Select pagination type for Default Front page', 'us' ),
			'id' => 'blog_pagination',
			'std' => 'regular',
			'type' => 'select',
			'options' => array(
				'regular' => __( 'Regular Pagination', 'us' ),
				'ajax' => __( 'Ajax Pagination (Load More Button)', 'us' ),
			)
		);
		$of_options[] = array(
			'name' => __( 'Posts Elements', 'us' ),
			'id' => 'blog_meta',
			'type' => 'title'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Date', 'us' ),
			'id' => 'blog_meta_date',
			'std' => 1,
			'type' => 'checkbox'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Author', 'us' ),
			'id' => 'blog_meta_author',
			'std' => 1,
			'type' => 'checkbox'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Categories', 'us' ),
			'id' => 'blog_meta_categories',
			'std' => 1,
			'type' => 'checkbox'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Comments number', 'us' ),
			'id' => 'blog_meta_comments',
			'std' => 1,
			'type' => 'checkbox'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Tags', 'us' ),
			'id' => 'blog_meta_tags',
			'std' => 1,
			'type' => 'checkbox'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Read More button', 'us' ),
			'id' => 'blog_read_more',
			'std' => 1,
			'type' => 'checkbox'
		);

		$of_options[] = array(
			'name' => __( 'Archive Pages', 'us' ),
			'id' => 'blog_options_archive',
			'std' => 1,
			'type' => 'subheading'
		);
		$of_options[] = array(
			'name' => __( 'Sidebar Position', 'us' ),
			'desc' => __( 'Select sidebar position or disable sidebar for Archive pages', 'us' ),
			'id' => 'archive_sidebar',
			'std' => 'right',
			'type' => 'select',
			'options' => array(
				'right' => __( 'Right', 'us' ),
				'left' => __( 'Left', 'us' ),
				'none' => __( 'No Sidebar', 'us' ),
			)
		);
		$of_options[] = array(
			'name' => __( 'Page Layout', 'us' ),
			'desc' => __( 'Select layout for Archive pages', 'us' ),
			'id' => 'archive_layout',
			'std' => 'smallcircle',
			'type' => 'select',
			'options' => array(
				'large' => __( 'Large Image', 'us' ),
				'smallcircle' => __( 'Small Image', 'us' ),
				'grid' => __( 'Regular Grid', 'us' ),
				'masonry' => __( 'Masonry Grid', 'us' ),
				'compact' => __( 'Compact', 'us' ),
			)
		);
		$of_options[] = array(
			'name' => __( 'Posts Content', 'us' ),
			'desc' => __( 'Select type of posts content which shows for Archive pages', 'us' ),
			'id' => 'archive_content_type',
			'std' => 'excerpt',
			'type' => 'select',
			'options' => array(
				'excerpt' => __( 'Excerpt', 'us' ),
				'content' => __( 'Full Content', 'us' ),
				'none' => __( 'None', 'us' ),
			)
		);
		$of_options[] = array(
			'name' => __( 'Pagination', 'us' ),
			'desc' => __( 'Select pagination type for Archive pages', 'us' ),
			'id' => 'archive_pagination',
			'std' => 'regular',
			'type' => 'select',
			'options' => array(
				'regular' => __( 'Regular Pagination', 'us' ),
				'ajax' => __( 'Ajax Pagination (Load More Button)', 'us' ),
			)
		);
		$of_options[] = array(
			'name' => __( 'Posts Elements', 'us' ),
			'id' => 'archive_meta',
			'type' => 'title'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Date', 'us' ),
			'id' => 'archive_meta_date',
			'std' => 1,
			'type' => 'checkbox'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Author', 'us' ),
			'id' => 'archive_meta_author',
			'std' => 1,
			'type' => 'checkbox'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Categories', 'us' ),
			'id' => 'archive_meta_categories',
			'std' => 1,
			'type' => 'checkbox'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Comments number', 'us' ),
			'id' => 'archive_meta_comments',
			'std' => 1,
			'type' => 'checkbox'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Tags', 'us' ),
			'id' => 'archive_meta_tags',
			'std' => 1,
			'type' => 'checkbox'
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Read More button', 'us' ),
			'id' => 'archive_read_more',
			'std' => 0,
			'type' => 'checkbox'
		);

		$of_options[] = array(
			'name' => __( 'Search Results Page', 'us' ),
			'id' => 'blog_options_search_results',
			'std' => 1,
			'type' => 'subheading'
		);
		$of_options[] = array(
			'name' => __( 'Sidebar Position', 'us' ),
			'desc' => __( 'Select sidebar position or disable sidebar for Search Results page', 'us' ),
			'id' => 'search_sidebar',
			'std' => 'right',
			'type' => 'select',
			'options' => array(
				'right' => __( 'Right', 'us' ),
				'left' => __( 'Left', 'us' ),
				'none' => __( 'No Sidebar', 'us' ),
			)
		);
		$of_options[] = array(
			'name' => __( 'Page Layout', 'us' ),
			'desc' => __( 'Select layout for Search Results page', 'us' ),
			'id' => 'search_layout',
			'std' => 'compact',
			'type' => 'select',
			'options' => array(
				'large' => __( 'Large Image', 'us' ),
				'smallcircle' => __( 'Small Image', 'us' ),
				'grid' => __( 'Regular Grid', 'us' ),
				'masonry' => __( 'Masonry Grid', 'us' ),
				'compact' => __( 'Compact', 'us' ),
			),
		);
		$of_options[] = array(
			'name' => __( 'Posts Content', 'us' ),
			'desc' => __( 'Select type of posts content which shows for Search Results page', 'us' ),
			'id' => 'search_content_type',
			'std' => 'excerpt',
			'type' => 'select',
			'options' => array(
				'excerpt' => __( 'Excerpt', 'us' ),
				'content' => __( 'Full Content', 'us' ),
				'none' => __( 'None', 'us' ),
			),
		);
		$of_options[] = array(
			'name' => __( 'Pagination', 'us' ),
			'desc' => __( 'Select pagination type for Search Results page', 'us' ),
			'id' => 'search_pagination',
			'std' => 'regular',
			'type' => 'select',
			'options' => array(
				'regular' => __( 'Regular Pagination', 'us' ),
				'ajax' => __( 'Ajax Pagination (Load More Button)', 'us' ),
			),
		);
		$of_options[] = array(
			'name' => __( 'Posts Elements', 'us' ),
			'id' => 'search_meta',
			'type' => 'title',
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Date', 'us' ),
			'id' => 'search_meta_date',
			'std' => 1,
			'type' => 'checkbox',
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Author', 'us' ),
			'id' => 'search_meta_author',
			'std' => 0,
			'type' => 'checkbox',
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Categories', 'us' ),
			'id' => 'search_meta_categories',
			'std' => 0,
			'type' => 'checkbox',
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Comments number', 'us' ),
			'id' => 'search_meta_comments',
			'std' => 0,
			'type' => 'checkbox',
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Tags', 'us' ),
			'id' => 'search_meta_tags',
			'std' => 0,
			'type' => 'checkbox',
		);
		$of_options[] = array(
			'name' => '',
			'desc' => __( 'Read More button', 'us' ),
			'id' => 'search_read_more',
			'std' => 0,
			'type' => 'checkbox',
		);

		$of_options[] = array(
			'name' => __( 'Excerpt', 'us' ),
			'id' => 'blog_options_excerpt',
			'std' => 1,
			'type' => 'subheading',
		);
		$of_options[] = array(
			'name' => __( 'Excerpt Length', 'us' ),
			'desc' => __( 'Input amount of words in the Excerpt. If you want to show full content of posts, leave this field blank.', 'us' ),
			'id' => 'excerpt_length',
			'std' => '55',
			'type' => 'text',
		);

		if ( class_exists( 'woocommerce' ) ) {
			// WooCommerce Options
			$of_options[] = array(
				'name' => __( 'WooCommerce', 'us' ),
				'type' => 'heading',
				'classname' => 'woocommerce',
			);

			$of_options[] = array(
				'name' => __( 'Title Bar Content', 'us' ),
				'desc' => __( 'This option is applied to all Shop pages and Product pages', 'us' ),
				'id' => 'shop_titlebar_content',
				'std' => 'hide',
				'type' => 'select',
				'options' => array(
					'all' => __( 'Captions and Breadcrumbs', 'us' ),
					'caption' => __( 'Captions Only', 'us' ),
					'hide' => __( 'Hide Title Bar', 'us' ),
				)
			);

			$of_options[] = array(
				'name' => '',
				'std' => __( '<strong>Title Bar Content</strong> can be set for a separate certain product when editing it. If the option above has no effect for some product page, check its Title Bar Options.', 'us' ),
				'id' => 'shop_titlebar_info',
				'type' => 'info',
			);

			$of_options[] = array(
				'name' => __( 'Shop Sidebar', 'us' ),
				'desc' => __( 'Select sidebar position or disable sidebar at Shop pages', 'us' ),
				'id' => 'shop_sidebar',
				'std' => 'right',
				'type' => 'select',
				'options' => array(
					'right' => __( 'Right', 'us' ),
					'left' => __( 'Left', 'us' ),
					'none' => __( 'No Sidebar', 'us' ),
				)
			);

			$of_options[] = array(
				'name' => __( 'Product Sidebar', 'us' ),
				'desc' => __( 'Select sidebar position or disable sidebar at Product pages', 'us' ),
				'id' => 'product_sidebar',
				'std' => 'right',
				'type' => 'select',
				'options' => array(
					'right' => __( 'Right', 'us' ),
					'left' => __( 'Left', 'us' ),
					'none' => __( 'No Sidebar', 'us' ),
				)
			);

			$of_options[] = array(
				'name' => __( 'Products Grid Columns', 'us' ),
				'desc' => __( 'Select products quantity per row at Shop pages', 'us' ),
				'id' => 'shop_columns',
				'std' => '3',
				'type' => 'select',
				'options' => array(
					'2' => __( '2 columns', 'us' ),
					'3' => __( '3 columns', 'us' ),
					'4' => __( '4 columns', 'us' ),
					'5' => __( '5 columns', 'us' ),
				)
			);

			$of_options[] = array(
				'name' => __( 'Products Grid Style', 'us' ),
				'desc' => __( 'Select style of products grid at all pages', 'us' ),
				'id' => 'shop_listing_style',
				'std' => '2',
				'type' => 'select',
				'options' => array(
					'1' => __( 'Flat style', 'us' ),
					'2' => __( 'Card style', 'us' ),
				)
			);

			$of_options[] = array(
				'name' => __( 'Related Products Quantity', 'us' ),
				'desc' => __( 'Select related products quantity at Product pages and Cart page', 'us' ),
				'id' => 'product_related_qty',
				'std' => '3',
				'type' => 'select',
				'options' => array(
					'2' => __( '2 items', 'us' ),
					'3' => __( '3 items', 'us' ),
					'4' => __( '4 items', 'us' ),
					'5' => __( '5 items', 'us' ),
				)
			);
		}

		// Theme Update Options
		$of_options[] = array(
			'name' => __( 'Theme Update', 'us' ),
			'type' => 'heading',
			'classname' => 'themeupdate',
		);

		$of_options[] = array(
			'name' => 'TF_Update',
			'std' => __( 'Please enter your Themeforest username and Secret API Key below if you want to get update notifications for the theme.', 'us' ),
			'id' => 'themeforest_info',
			'type' => 'info'
		);

		$of_options[] = array(
			'name' => __( 'ThemeForest User Name', 'us' ),
			'desc' => '',
			'id' => 'themeforest_username',
			'std' => '',
			'type' => 'text'
		);

		$of_options[] = array(
			'name' => __( 'ThemeForest API Key', 'us' ),
			'desc' => sprintf( __( 'Copy API Key of your ThemeForest account here. Check this <a target="_blank" href="%s">screenshot</a> for more info', 'us' ), $us_template_directory_uri . '/img/find-api.png' ),
			'id' => 'themeforest_api_key',
			'std' => '',
			'type' => 'text'
		);

		// Manage Options
		$of_options[] = array(
			'name' => __( 'Manage Options', 'us' ),
			'type' => 'heading',
			'classname' => 'manageoptions',
		);

		$of_options[] = array(
			'name' => __( 'Backup and restore Theme Options', 'us' ),
			'id' => 'of_backup',
			'std' => '',
			'type' => 'backup',
			'desc' => __( 'You can use the two buttons below to backup your current options, and then restore them back later. This is useful if you want to experiment with the options but would like to keep the old settings in case you need them back.', 'us' ),
		);

		$of_options[] = array(
			'name' => __( 'Transfer Theme Options data', 'us' ),
			'id' => 'of_transfer',
			'std' => '',
			'type' => 'transfer',
			'desc' => __( 'You can transfer the saved options data between different installations by copying the text inside the text box. To import data from another installation, replace the data in the text box with the one from another installation and click "Import Options".', 'us' ),
		);

		// Applying default values from the first style scheme
		if ( is_array( $style_schemes ) AND ! empty( $style_schemes ) ) {
			foreach ( $style_schemes as &$style_scheme ) {
				foreach ( $of_options as &$option ) {
					if ( isset( $option['id'] ) AND isset( $style_scheme['values'][ $option['id'] ] ) ) {
						$option['std'] = $style_scheme['values'][ $option['id'] ];
					}
				}
				break;
			}
		}
	}//End function: of_options()
}//End chack if function exists: of_options()
?>
