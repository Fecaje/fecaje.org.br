<?php defined('ABSPATH') OR die('This script cannot be accessed directly.');

/**
 * Header search block
 *
 * (!) Important: this file is not intended to be overloaded, so use the below hooks for customizing instead
 *
 * @action Before the template: 'us_before_template:templates/widgets/search'
 * @action After the template: 'us_after_template:templates/widgets/search'
 */
?>
<div class="w-search">
	<div class="w-search-h">
		<span class="w-search-show"></span>
		<div class="w-search-form-overlay"></div>
		<form class="w-search-form" action="<?php echo home_url( '/' ); ?>">
			<div class="w-search-form-h">
				<div class="w-search-form-row">
					<?php if (defined('ICL_LANGUAGE_CODE') AND ICL_LANGUAGE_CODE != '') { ?><input type="hidden" name="lang" value="<?php echo ICL_LANGUAGE_CODE; ?>"><?php } ?>
					<div class="w-search-label">
						<label for="s"><?php echo __("Just type and press 'enter'", 'us' ); ?></label>
					</div>
					<div class="w-search-input">
						<input type="text" value="" id="s" name="s"/>
						<span class="w-search-input-bar"></span>
					</div>
					<div class="w-search-submit">
						<input type="submit" id="searchsubmit"  value="Search" />
					</div>
					<div class="w-search-close"> &#10005; </div>
				</div>
			</div>
		</form>
	</div>
</div>
