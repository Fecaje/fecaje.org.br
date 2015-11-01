<div class="wrap" id="of_container">

	<div id="of-popup-save" class="of-save-popup">
		<div class="of-save-save"><?php _e('Options Updated', 'us') ?></div>
	</div>

	<div id="of-popup-reset" class="of-save-popup">
		<div class="of-save-reset"><?php _e('Options Reset', 'us' ) ?></div>
	</div>

	<div id="of-popup-fail" class="of-save-popup">
		<div class="of-save-fail"><?php _e('Error!', 'us' ) ?></div>
	</div>

	<span style="display: none;" id="hooks"><?php echo json_encode(of_get_header_classes_array()); ?></span>
	<input type="hidden" id="reset" value="<?php if(isset($_REQUEST['reset'])) echo sanitize_text_field($_REQUEST['reset']); ?>" />
	<input type="hidden" id="security" name="security" value="<?php echo wp_create_nonce('of_ajax_nonce'); ?>" />

	<form id="of_form" method="post" action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ) ?>" enctype="multipart/form-data" >

		<div id="header">

			<div class="logo">
				<h2><?php echo US_THEMENAME; ?></h2>
				<span><?php echo ('v'. THEMEVERSION); ?></span>
			</div>

			<div id="js-warning"><?php _e('Warning - This options panel will not work properly without javascript!', 'us' ) ?></div>
			<div class="icon-option"></div>
			<div class="clear"></div>

		</div>

		<div id="info_bar">

			<img style="display:none" src="<?php echo ADMIN_DIR; ?>assets/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />

			<button id="of_save" type="button" class="button-primary">
				<?php _e('Save All Changes', 'us');?>
			</button>

		</div><!--.info_bar-->

		<div id="main">

			<div id="of-nav">
				<ul>
				  <?php echo $options_machine->Menu ?>
				</ul>
			</div>

			<div id="content">
				<?php echo $options_machine->Inputs /* Settings */ ?>
			</div>

			<div class="clear"></div>

		</div>

		<div class="save_bar">

			<img style="display:none" src="<?php echo ADMIN_DIR; ?>assets/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
			<button id ="of_save" type="button" class="button-primary"><?php _e('Save All Changes', 'us');?></button>
			<button id ="of_reset" type="button" class="button submit-button reset-button" ><?php _e('Reset Options', 'us');?></button>
			<img style="display:none" src="<?php echo ADMIN_DIR; ?>assets/images/loading-bottom.gif" class="ajax-reset-loading-img ajax-loading-img-bottom" alt="Working..." />

		</div><!--.save_bar-->

	</form>

	<div style="clear:both;"></div>

</div><!--wrap-->
<?php
$smof_translation = array(
	'Uploading ...' => __( 'Uploading ...', 'us' ),
	'Upload' => __( 'Upload', 'us' ),
	'Remove' => __( 'Remove', 'us' ),
	'Click OK to backup your current saved options.' => __( 'Click OK to backup your current saved options.', 'us' ),
	'Warning: All of your current options will be replaced with the data from your last backup! Proceed?' => __( 'Warning: All of your current options will be replaced with the data from your last backup! Proceed?', 'us' ),
	'Click OK to import options.' => __( 'Click OK to import options.', 'us' ),
	'Click OK to reset. All settings will be lost and replaced with default settings!' => __( 'Click OK to reset. All settings will be lost and replaced with default settings!', 'us' ),
);
?>
<script>
window.smofTranslation = <?php echo json_encode($smof_translation) ?>;
</script>
