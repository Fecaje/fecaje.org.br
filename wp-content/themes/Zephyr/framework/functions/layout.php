<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

class US_Layout {

	/**
	 * @var US_Layout
	 */
	protected static $instance;

	/**
	 * Singleton pattern: US_Layout::instance()->do_something()
	 *
	 * @return US_Layout
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * @var string Columns type: right, left, none
	 */
	public $sidebar_pos;

	/**
	 * @var string Canvas type: wide / boxed
	 */
	public $canvas_type;

	/**
	 * @var string Header layout: standard / extended / advanced / centered
	 */
	public $header_layout;

	/**
	 * @var string Header position: static / fixed
	 */
	public $header_pos;

	/**
	 * @var string Header background: solid / transparent
	 */
	public $header_bg;

	/**
	 * @var string Show header: always / never / onscroll
	 */
	public $header_show;

	/**
	 * @var string Titlebar type
	 */
	public $titlebar = 'none';

	/**
	 * @var bool Show footer's widgets area?
	 */
	public $footer_show_top;

	/**
	 * @var bool Show footer's copyright area?
	 */
	public $footer_show_bottom;

	protected function __construct() {

		do_action( 'us_layout_before_init', $this );

		if ( WP_DEBUG AND ! ( isset( $GLOBALS['post'] ) OR is_404() OR is_search() ) ) {
			wp_die( 'US_Layout can be inited only after the current post is obtained' );
		}

		if ( is_home() ) {
			// Default homepage blog listing
			$this->sidebar_pos = us_get_option( 'blog_sidebar', 'right' );
		} elseif ( is_archive() ) {
			// Archive
			$this->sidebar_pos = us_get_option( 'archive_sidebar', 'right' );
		} elseif ( is_singular( array( 'post', 'attachment' ) ) ) {
			// Posts and attachments
			$this->sidebar_pos = us_get_option( 'post_sidebar', 'right' );
		} elseif ( is_singular( array( 'us_portfolio' ) ) ) {
			// Portfolio item
			$this->sidebar_pos = us_get_option( 'portfolio_sidebar', 'none' );
		} elseif ( is_page() ) {
			// Page
			$this->sidebar_pos = us_get_option( 'page_sidebar', 'none' );
		} elseif ( is_404() ) {
			// 404 page
			$this->sidebar_pos = 'none';
		} else {
			$this->sidebar_pos = 'none';
		}
		// Some wrong value may came from various theme options, so filtering it
		if ( ! in_array( $this->sidebar_pos, array( 'right', 'left', 'none' ) ) ) {
			$this->sidebar_pos = 'right';
		}

		$this->canvas_type = us_get_option( 'canvas_layout', 'wide' );
		$this->header_layout = us_get_option( 'header_layout', 'standard' );
		$this->header_pos = us_get_option( 'header_sticky', FALSE ) ? 'fixed' : 'static';
		$this->header_bg = us_get_option( 'header_transparent', FALSE ) ? 'transparent' : 'solid';
		$this->header_show = us_get_option( 'header_hidden' ) ? 'onscroll' : 'always';
		$this->footer_show_top = us_get_option( 'footer_show_top' );
		$this->footer_show_bottom = us_get_option( 'footer_show_bottom', TRUE );

		// Some of the options may be overloaded by post's meta settings
		if ( is_singular( array( 'post', 'page', 'us_portfolio', 'product' ) ) ) {
			if ( rwmb_meta( 'us_header_remove' ) ) {
				$this->header_show = 'never';
			} elseif ( rwmb_meta( 'us_header_show' ) != '' ) {
				$this->header_show = ( rwmb_meta( 'us_header_show' ) == 'onscroll' ) ? 'onscroll' : 'always';
			}
			if ( rwmb_meta( 'us_header_bg' ) != '' ) {
				$this->header_bg = rwmb_meta( 'us_header_bg' );
			}
			if ( rwmb_meta( 'us_header_pos' ) != '' ) {
				$this->header_pos = rwmb_meta( 'us_header_pos' );
			}
			if ( rwmb_meta( 'us_sidebar' ) != '' ) {
				$this->sidebar_pos = rwmb_meta( 'us_sidebar' );
			}
			if ( rwmb_meta( 'us_footer_show_top' ) != '' ) {
				$this->footer_show_top = ( rwmb_meta( 'us_footer_show_top' ) != 'hide' );
			}
			if ( rwmb_meta( 'us_footer_show_bottom' ) != '' ) {
				$this->footer_show_bottom = ( rwmb_meta( 'us_footer_show_bottom' ) != 'hide' );
			}
		}

		// Titlebar show / hidden
		if ( is_singular( array( 'page', 'us_portfolio', 'product' ) ) ) {
			$this->titlebar = ( us_get_option( 'titlebar_content', 'all' ) == 'hide' ) ? 'none' : 'default';
			if ( rwmb_meta( 'us_titlebar_content' ) == 'hide' ) {
				$this->titlebar = 'none';
			} elseif ( in_array( rwmb_meta( 'us_titlebar_content' ), array( 'captions', 'all', ) ) ) {
				$this->titlebar = 'default';
			}
		}

		do_action( 'us_layout_after_init', $this );
	}

	/**
	 * Obtain theme-defined CSS classes for <html> element
	 *
	 * @return string
	 */
	public function html_classes() {
		$classes = '';

		if ( ! us_get_option( 'responsive_layout', TRUE ) ) {
			$classes .= 'no-responsive';
		}

		return $classes;
	}

	/**
	 * Obtain theme-defined CSS classes for <body> element
	 *
	 * @return string
	 */
	public function body_classes() {

		// TODO Dynamically prepare theme slug name
		$classes = 'us-theme_' . strtolower( US_THEMENAME ) . '_' . str_replace( '.', '-', US_THEMEVERSION );

		if ( $this->header_layout == 'sided' AND $this->header_show != 'never' ) {
			$classes .= ' header_aside';
		}

		if (us_get_option( 'links_underline' ) == TRUE) {
			$classes .= ' links_underline';
		}

		if ( us_get_option( 'rounded_corners' ) !== NULL AND us_get_option( 'rounded_corners' ) == FALSE) {
			$classes .= ' rounded_none';
		}

		return $classes;
	}

	/**
	 * Obtain inner styles for .l-body
	 *
	 * @param boolean $with_attr
	 *
	 * @return string
	 */
	public function body_styles( $with_attr = TRUE ) {
		$styles = array();

		$bg_image_value = us_get_option( 'body_bg_image' );
		if ( $bg_image_value AND ( $bg_image = usof_get_image_src( $bg_image_value ) ) ) {
			$styles['background-image'] = 'url(' . $bg_image[0] . ')';
			$styles['background-repeat'] = us_get_option( 'body_bg_image_repeat', 'repeat' );
			$styles['background-position'] = us_get_option( 'body_bg_image_position', 'top center' );
			$styles['background-attachment'] = us_get_option( 'body_bg_image_attachment', 'scroll' );
			$styles['background-size'] = us_get_option( 'body_bg_image_size', 'cover' );
		}

		$result = '';
		foreach ( $styles as $prop => $value ) {
			$result .= $prop . ': ' . $value . ';';
		}
		if ( $with_attr AND ! empty( $result ) ) {
			$result = ' style="' . $result . '"';
		}

		return $result;
	}

	/**
	 * Obtain CSS classes for .l-canvas
	 *
	 * @return string
	 */
	public function canvas_classes() {

		$classes = 'sidebar_' . $this->sidebar_pos . ' type_' . $this->canvas_type . ' titlebar_' . $this->titlebar;

		// Language modificator
		if ( defined( 'ICL_LANGUAGE_CODE' ) AND ICL_LANGUAGE_CODE ) {
			$classes .= ' wpml_lang_' . ICL_LANGUAGE_CODE;
		}

		return $classes;
	}

	/**
	 * Obtain CSS classes for .l-header
	 *
	 * @return string
	 */
	public function header_classes() {

		$classes = 'layout_' . $this->header_layout;
		$classes .= ' pos_' . $this->header_pos;
		$classes .= ' bg_' . $this->header_bg;
		if ( $this->header_bg == 'transparent' ) {
			// Initial transparent state class
			$classes .= ' transparent';
		}
		if ( $this->header_show != 'always' ) {
			$classes .= ' show_' . $this->header_show;
		}

		if ( us_get_option( 'header_invert_logo_pos', FALSE ) ) {
			$classes .= ' logopos_right';
		}
		if ( us_get_option( 'header_fullwidth', FALSE ) ) {
			$classes .= ' width_full';
		}

		return $classes;
	}

}
