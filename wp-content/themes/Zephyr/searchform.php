<div class="w-search submit_inside">
	<form class="w-search-form" action="<?php echo home_url( '/' ); ?>">
		<?php if ( defined('ICL_LANGUAGE_CODE') AND ICL_LANGUAGE_CODE != '' ) { ?><input type="hidden" name="lang" value="<?php echo esc_attr(ICL_LANGUAGE_CODE); ?>"><?php } ?>
		<div class="w-search-input">
			<input type="text" value="" name="s" placeholder="<?php esc_attr_e( 'search', 'us' ); ?>..."/>
			<i class="mdfi_action_search"></i>
			<span class="w-search-input-bar"></span>
		</div>
		<div class="w-search-submit">
			<input type="submit" value="<?php echo __( 'Search', 'us' ); ?>" />
		</div>
	</form>
</div>
