/**
 * SMOF js
 *
 * contains the core functionalities to be used
 * inside SMOF
 */

jQuery.noConflict();

/** Fire up jQuery - let's dance!
 */
jQuery(document).ready(function($){

	//(un)fold options in a checkbox-group
	jQuery('.fld').click(function() {
		$('.f_'+this.id).slideToggle('normal', "swing");
		$('.uf_'+this.id).slideToggle('normal', "swing");
	});

	//delays until AjaxUpload is finished loading
	//fixes bug in Safari and Mac Chrome
	if (typeof AjaxUpload != 'function') {
		return ++counter < 6 && window.setTimeout(init, counter * 500);
	}

	//hides warning if js is enabled
	$('#js-warning').hide();

	//Tabify Options
	$('.group').hide();

	// Display last current tab
	if ($.cookie("of_current_opt") === null) {
		$('.group:first').fadeIn('fast');
		$('#of-nav li:first').addClass('current');
	} else {

		var hooks = $('#hooks').html();
		hooks = jQuery.parseJSON(hooks);

		$.each(hooks, function(key, value) {

			if ($.cookie("of_current_opt") == '#of-option-'+ value) {
				$('.group#of-option-' + value).fadeIn();
				$('#of-nav li.' + value).addClass('current');
			}

		});

	}

	//Current Menu Class
	$('#of-nav li a').click(function(evt){
	// event.preventDefault();

		$('#of-nav li').removeClass('current');
		$(this).parent().addClass('current');

		var clicked_group = $(this).attr('href');

		$.cookie('of_current_opt', clicked_group, { expires: 7, path: '/' });

		$('.group').hide();

		$(clicked_group).fadeIn('fast');
		return false;

	});

	//Expand Options
	var flip = 0;

	$('#expand_options').click(function(){
		var $this = $(this);
		if(flip == 0){
			flip = 1;
			$('#of_container #of-nav').hide();
			$('#of_container #content').width(760);
			$('#of_container .group').add('#of_container .group h2').show();

			$this.removeClass('expand').addClass('close').text(smofTranslation['Close']);

		} else {
			flip = 0;
			$('#of_container #of-nav').show();
			$('#of_container #content').width(600);
			$('#of_container .group').add('#of_container .group h2').hide();
			$('#of_container .group:first').show();
			$('#of_container #of-nav li').removeClass('current');
			$('#of_container #of-nav li:first').addClass('current');

			$this.removeClass('close').addClass('expand').text(smofTranslation['Expand']);

		}

	});

	//Update Message popup
	$.fn.center = function () {
		this.animate({"top":( $(window).height() - this.height() - 200 ) / 2+$(window).scrollTop() + "px"},100);
		this.css("left", 250 );
		return this;
	};


	$('#of-popup-save').center();
	$('#of-popup-reset').center();
	$('#of-popup-fail').center();

	$(window).scroll(function() {
		$('#of-popup-save').center();
		$('#of-popup-reset').center();
		$('#of-popup-fail').center();
	});


	//Masked Inputs (images as radio buttons)
	$('.of-radio-img-img').click(function(){
		$(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
		$(this).addClass('of-radio-img-selected');
	});
	$('.of-radio-img-label').hide();
	$('.of-radio-img-img').show();
	$('.of-radio-img-radio').hide();

	//Masked Inputs (background images as radio buttons)
	$('.of-radio-tile-img').click(function(){
		$(this).parent().parent().find('.of-radio-tile-img').removeClass('of-radio-tile-selected');
		$(this).addClass('of-radio-tile-selected');
	});
	$('.of-radio-tile-label').hide();
	$('.of-radio-tile-img').show();
	$('.of-radio-tile-radio').hide();

	//AJAX Upload
	function of_image_upload() {
		$('.image_upload_button').each(function(){

			var clickedObject = $(this);
			var clickedID = $(this).attr('id');

			var nonce = $('#security').val();

			new AjaxUpload(clickedID, {
				action: ajaxurl,
				name: clickedID, // File upload name
				data: { // Additional data to send
					action: 'of_ajax_post_action',
					type: 'upload',
					security: nonce,
					data: clickedID },
				autoSubmit: true, // Submit file after selection
				responseType: false,
				onChange: function(file, extension){},
				onSubmit: function(file, extension){
					clickedObject.text(smofTranslation['Uploading ...']); // change button text, when user selects file
					this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
					interval = window.setInterval(function(){
						var text = clickedObject.text();
						if (text.length < 13){	clickedObject.text(text + '.'); }
						else { clickedObject.text(smofTranslation['Uploading ...']); }
						}, 200);
				},
				onComplete: function(file, response) {
					window.clearInterval(interval);
					clickedObject.text(smofTranslation['Upload']);
					this.enable(); // enable upload button


					// If nonce fails
					if(response==-1){
						var fail_popup = $('#of-popup-fail');
						fail_popup.fadeIn();
						window.setTimeout(function(){
						fail_popup.fadeOut();
						}, 2000);
					}

					// If there was an error
					else if(response.search('Upload Error') > -1){
						var buildReturn = '<span class="upload-error">' + response + '</span>';
						$(".upload-error").remove();
						clickedObject.parent().after(buildReturn);

						}
					else{
						var buildReturn = '<img class="hide of-option-image" id="image_'+clickedID+'" src="'+response+'" alt="" />';

						$(".upload-error").remove();
						$("#image_" + clickedID).remove();
						clickedObject.parent().after(buildReturn);
						$('img#image_'+clickedID).fadeIn();
						clickedObject.next('span').fadeIn();
						clickedObject.parent().prev('input').val(response);
					}
				}
			});

		});

	}

	of_image_upload();

	//AJAX Remove Image (clear option value)
	$('.image_reset_button').live('click', function(){

		var clickedObject = $(this);
		var clickedID = $(this).attr('id');
		var theID = $(this).attr('title');

		var nonce = $('#security').val();

		var data = {
			action: 'of_ajax_post_action',
			type: 'image_reset',
			security: nonce,
			data: theID
		};

		$.post(ajaxurl, data, function(response) {

			//check nonce
			if(response==-1){ //failed

				var fail_popup = $('#of-popup-fail');
				fail_popup.fadeIn();
				window.setTimeout(function(){
					fail_popup.fadeOut();
				}, 2000);
			}

			else {

				var image_to_remove = $('#image_' + theID);
				var button_to_hide = $('#reset_' + theID);
				image_to_remove.fadeOut(500,function(){ $(this).remove(); });
				button_to_hide.fadeOut();
				clickedObject.parent().prev('input').val('');
			}


		});

	});

	// Style Select
	(function ($) {
	styleSelect = {
		init: function () {
		$('.select_wrapper').each(function () {
			$(this).prepend('<span>' + $(this).find('.select option:selected').text() + '</span>');
		});
		$('.select').live('change', function () {
			$(this).prev('span').replaceWith('<span>' + $(this).find('option:selected').text() + '</span>');
		});
		$('.select').bind($.browser.msie ? 'click' : 'change', function(event) {
			$(this).prev('span').replaceWith('<span>' + $(this).find('option:selected').text() + '</span>');
		});
		}
	};
	$(document).ready(function () {
		styleSelect.init()
	})
	})(jQuery);


	/** Aquagraphite Slider MOD */

	//Hide (Collapse) the toggle containers on load
	$(".slide_body").hide();

	//Switch the "Open" and "Close" state per click then slide up/down (depending on open/close state)
	$(".slide_edit_button").live( 'click', function(){
		$(this).parent().toggleClass("active").next().slideToggle("fast");
		return false; //Prevent the browser jump to the link anchor
	});

	// Update slide title upon typing
	function update_slider_title(e) {
		var element = e;
		if ( this.timer ) {
			clearTimeout( element.timer );
		}
		this.timer = setTimeout( function() {
			$(element).parent().prev().find('strong').text( element.value );
		}, 100);
		return true;
	}

	$('.of-slider-title').live('keyup', function(){
		update_slider_title(this);
	});


	//Remove individual slide
	$('.slide_delete_button').live('click', function(){
	// event.preventDefault();
	var agree = confirm(smofTranslation['Are you sure you wish to delete this slide?']);
		if (agree) {
			var $trash = $(this).parents('li');
			//$trash.slideUp('slow', function(){ $trash.remove(); }); //chrome + confirm bug made slideUp not working...
			$trash.animate({
					opacity: 0.25,
					height: 0
				}, 500, function() {
					$(this).remove();
			});
			return false; //Prevent the browser jump to the link anchor
		} else {
		return false;
		}
	});

	//Add new slide
	$(".slide_add_button").live('click', function(){
		var slidesContainer = $(this).prev();
		var sliderId = slidesContainer.attr('id');
		var sliderInt = $('#'+sliderId).attr('rel');

		var numArr = $('#'+sliderId +' li').find('.order').map(function() {
			var str = this.id;
			str = str.replace(/\D/g,'');
			str = parseFloat(str);
			return str;
		}).get();

		var maxNum = Math.max.apply(Math, numArr);
		if (maxNum < 1 ) { maxNum = 0; }
		var newNum = maxNum + 1;

		var newSlide = '<li class="temphide"><div class="slide_header"><strong>';
		newSlide += smofTranslation['Slide'];
		newSlide += ' '+ newNum + '</strong><input type="hidden" class="slide of-input order" name="' + sliderId + '[' + newNum + '][order]" id="' + sliderId + '_slide_order-' + newNum + '" value="' + newNum + '"><a class="slide_edit_button" href="#">';
		newSlide += smofTranslation['Edit'];
		newSlide += '</a></div><div class="slide_body" style="display: none; "><label>';
		newSlide += smofTranslation['Title'];
		newSlide += '</label><input class="slide of-input of-slider-title" name="' + sliderId + '[' + newNum + '][title]" id="' + sliderId + '_' + newNum + '_slide_title" value=""><label>';
		newSlide += smofTranslation['Image URL'];
		newSlide += '</label><input class="slide of-input" name="' + sliderId + '[' + newNum + '][url]" id="' + sliderId + '_' + newNum + '_slide_url" value=""><div class="upload_button_div"><span class="button media_upload_button" id="' + sliderId + '_' + newNum + '" rel="'+sliderInt+'">';
		newSlide += smofTranslation['Upload'];
		newSlide += '</span><span class="button mlu_remove_button hide" id="reset_' + sliderId + '_' + newNum + '" title="' + sliderId + '_' + newNum + '">';
		newSlide += smofTranslation['Remove'];
		newSlide += '</span></div><div class="screenshot"></div><label>';
		newSlide += smofTranslation['Link URL (optional)'];
		newSlide += '</label><input class="slide of-input" name="' + sliderId + '[' + newNum + '][link]" id="' + sliderId + '_' + newNum + '_slide_link" value=""><label>';
		newSlide += smofTranslation['Description (optional)'];
		newSlide += '</label><textarea class="slide of-input" name="' + sliderId + '[' + newNum + '][description]" id="' + sliderId + '_' + newNum + '_slide_description" cols="8" rows="8"></textarea><a class="slide_delete_button" href="#">';
		newSlide += smofTranslation['Delete'];
		newSlide += '</a><div class="clear"></div></div></li>';

		slidesContainer.append(newSlide);
		$('.temphide').fadeIn('fast', function() {
			$(this).removeClass('temphide');
		});

		of_image_upload(); // re-initialise upload image..

		return false; //prevent jumps, as always..
	});

	//Sort slides
	jQuery('.slider').find('ul').each( function() {
		var id = jQuery(this).attr('id');
		$('#'+ id).sortable({
			placeholder: "placeholder",
			opacity: 0.6
		});
	});


	/**	Sorter (Layout Manager) */
	jQuery('.sorter').each( function() {
		var id = jQuery(this).attr('id');
		$('#'+ id).find('ul').sortable({
			items: 'li',
			placeholder: "placeholder",
			connectWith: '.sortlist_' + id,
			opacity: 0.6,
			update: function() {
				$(this).find('.position').each( function() {

					var listID = $(this).parent().attr('id');
					var parentID = $(this).parent().parent().attr('id');
					parentID = parentID.replace(id + '_', '')
					var optionID = $(this).parent().parent().parent().attr('id');
					$(this).prop("name", optionID + '[' + parentID + '][' + listID + ']');

				});
			}
		});
	});


	/**	Ajax Backup & Restore MOD */
	//backup button
	$('#of_backup_button').live('click', function(){

		var answer = confirm(smofTranslation['Click OK to backup your current saved options.'])

		if (answer){

			var clickedObject = $(this);
			var clickedID = $(this).attr('id');

			var nonce = $('#security').val();

			var data = {
				action: 'of_ajax_post_action',
				type: 'backup_options',
				security: nonce
			};

			$.post(ajaxurl, data, function(response) {

				//check nonce
				if(response==-1){ //failed

					var fail_popup = $('#of-popup-fail');
					fail_popup.fadeIn();
					window.setTimeout(function(){
						fail_popup.fadeOut();
					}, 2000);
				}

				else {

					var success_popup = $('#of-popup-save');
					success_popup.fadeIn();
					window.setTimeout(function(){
						location.reload();
					}, 1000);
				}

			});

		}

	return false;

	});

	//restore button
	$('#of_restore_button').live('click', function(){

		var answer = confirm(smofTranslation['Warning: All of your current options will be replaced with the data from your last backup! Proceed?'])

		if (answer){

			var clickedObject = $(this);
			var clickedID = $(this).attr('id');

			var nonce = $('#security').val();

			var data = {
				action: 'of_ajax_post_action',
				type: 'restore_options',
				security: nonce
			};

			$.post(ajaxurl, data, function(response) {

				//check nonce
				if(response==-1){ //failed

					var fail_popup = $('#of-popup-fail');
					fail_popup.fadeIn();
					window.setTimeout(function(){
						fail_popup.fadeOut();
					}, 2000);
				}

				else {

					var success_popup = $('#of-popup-save');
					success_popup.fadeIn();
					window.setTimeout(function(){
						location.reload();
					}, 1000);
				}

			});

		}

	return false;

	});

	/**	Ajax Transfer (Import/Export) Option */
	$('#of_import_button').live('click', function(){

		var answer = confirm(smofTranslation['Click OK to import options.'])

		if (answer){

			var clickedObject = $(this);
			var clickedID = $(this).attr('id');

			var nonce = $('#security').val();

			var import_data = $('#export_data').val();

			var data = {
				action: 'of_ajax_post_action',
				type: 'import_options',
				security: nonce,
				data: import_data
			};

			$.post(ajaxurl, data, function(response) {
				var fail_popup = $('#of-popup-fail');
				var success_popup = $('#of-popup-save');

				//check nonce
				if(response==-1){ //failed
					fail_popup.fadeIn();
					window.setTimeout(function(){
						fail_popup.fadeOut();
					}, 2000);
				}
				else
				{
					success_popup.fadeIn();
					window.setTimeout(function(){
						location.reload();
					}, 1000);
				}

			});

		}

	return false;

	});

	/** AJAX Save Options */
	$('#of_save').live('click',function() {

		var nonce = $('#security').val();

		$('.ajax-loading-img').fadeIn();

		//get serialized data from all our option fields
		var serializedReturn = $('#of_form :input[name][name!="security"][name!="of_reset"]').serialize();

		var data = {
			type: 'save',
			action: 'of_ajax_post_action',
			security: nonce,
			data: serializedReturn
		};

		$.post(ajaxurl, data, function(response) {
			var success = $('#of-popup-save');
			var fail = $('#of-popup-fail');
			var loading = $('.ajax-loading-img');
			loading.fadeOut();

			if (response==1) {
				success.fadeIn();
			} else {
				fail.fadeIn();
			}

			window.setTimeout(function(){
				success.fadeOut();
				fail.fadeOut();
			}, 2000);
		});

	return false;

	});


	/* AJAX Options Reset */
	$('#of_reset').click(function() {

		//confirm reset
		var answer = confirm(smofTranslation['Click OK to reset. All settings will be lost and replaced with default settings!']);

		//ajax reset
		if (answer){

			var nonce = $('#security').val();

			$('.ajax-reset-loading-img').fadeIn();

			var data = {

				type: 'reset',
				action: 'of_ajax_post_action',
				security: nonce
			};

			$.post(ajaxurl, data, function(response) {
				var success = $('#of-popup-reset');
				var fail = $('#of-popup-fail');
				var loading = $('.ajax-reset-loading-img');
				loading.fadeOut();

				if (response==1)
				{
					success.fadeIn();
					window.setTimeout(function(){
						location.reload();
					}, 1000);
				}
				else
				{
					fail.fadeIn();
					window.setTimeout(function(){
						fail.fadeOut();
					}, 2000);
				}


			});

		}

	return false;

	});


	/**	Tipsy @since v1.3 */
	if (jQuery().tipsy) {
		$('.typography-size, .typography-height, .typography-face, .typography-style, .of-typography-color').tipsy({
			fade: true,
			gravity: 's',
			opacity: 0.7
		});
	}


	/**
	  * JQuery UI Slider function
	  * Dependencies 	 : jquery, jquery-ui-slider
	  * Feature added by : Smartik - http://smartik.ws/
	  * Date 			 : 03.17.2013
	  */
	jQuery('.smof_sliderui').each(function() {

		var obj   = jQuery(this);
		var sId   = "#" + obj.data('id');
		var val   = parseInt(obj.data('val'));
		var min   = parseInt(obj.data('min'));
		var max   = parseInt(obj.data('max'));
		var step  = parseInt(obj.data('step'));

		//slider init
		obj.slider({
			value: val,
			min: min,
			max: max,
			step: step,
			slide: function( event, ui ) {
				jQuery(sId).val( ui.value );
			}
		});

	});


	/**
	  * Switch
	  * Dependencies 	 : jquery
	  * Feature added by : Smartik - http://smartik.ws/
	  * Date 			 : 03.17.2013
	  */
	jQuery(".cb-enable").click(function(){
		var parent = $(this).parents('.switch-options');
		jQuery('.cb-disable',parent).removeClass('selected');
		jQuery(this).addClass('selected');
		jQuery('.main_checkbox',parent).attr('checked', true);

		//fold/unfold related options
		var obj = jQuery(this);
		jQuery('.f_'+obj.data('id')).slideDown('normal', "swing");
		jQuery('.uf_'+obj.data('id')).slideUp('normal', "swing");
	});
	jQuery(".cb-disable").click(function(){
		var parent = $(this).parents('.switch-options');
		jQuery('.cb-enable',parent).removeClass('selected');
		jQuery(this).addClass('selected');
		jQuery('.main_checkbox',parent).attr('checked', false);

		//fold/unfold related options
		var obj = jQuery(this);
		jQuery('.f_'+obj.data('id')).slideUp('normal', "swing");
		jQuery('.uf_'+obj.data('id')).slideDown('normal', "swing");
	});
	//disable text select(for modern chrome, safari and firefox is done via CSS)
	if (($.browser.msie && $.browser.version < 10) || $.browser.opera) {
		$('.cb-enable span, .cb-disable span').find().attr('unselectable', 'on');
	}


	$('.ggf-container').each(function(){
		var $container = $(this),
			$selector = $container.find('select'),
			$input = $container.find('input[type="hidden"]:first'),
			inputId = $selector.attr('id'),
			linkClass = 'style_link_'+ inputId,
			$preview = $container.find('.google_font_preview'),
			$weightsContainer = $container.find('.ggf_weights'),
			$weightLabels = $weightsContainer.find('label'),
			$weights = $weightsContainer.find('input'),
			fontsJson = $('.google-fonts-json')[0].onclick() || {},
			curFont = $selector.find(':selected').val();
		var storeValue = function(){
			var weights = [];
			if (fontsJson[curFont] !== undefined){
				$weights.filter(':checked').each(function(){
					if ($.inArray($(this).val()+'', fontsJson[curFont].variants) != -1){
						weights.push($(this).val());
					}
				});
			}
			$input.val(curFont+'|'+weights.join(','));
		};
		var updateFont = function(){
			var newFont = $selector.find(':selected').val();
			if (newFont == curFont) return;
			$('.' + linkClass).remove();
			if (newFont == 'none'){
				// Selected no-font
				$preview.css('font-family', '');
				$weightLabels.addClass('hidden');
			}
			else if (newFont.indexOf(',') != -1){
				// Web-safe font combination
				$preview.css('font-family', newFont);
				$weightLabels.addClass('hidden');
			}
			else {
				// Selected some google font
				//add reference to google font family
				$('head').append('<link href="http://fonts.googleapis.com/css?family='+ newFont.replace(/\s+/g, '+') +'" rel="stylesheet" type="text/css" class="'+ linkClass +'">');
				//show the font in the preview box
				$preview.css('font-family', newFont +', sans-serif');
				if (fontsJson[newFont] === undefined){
					$weightLabels.addClass('hidden');
				}else{
					$weightLabels.each(function(index){
						var $this = $(this);
						$this.toggleClass('hidden', $.inArray($this.data('value')+'', fontsJson[newFont].variants) == -1);
					});
				}
			}
			curFont = newFont;
			storeValue();
		};
		$selector.on('change keyup', updateFont);
		$weights.on('change click keyup', storeValue);
		updateFont();
	});

}); //end doc ready

jQuery(document).ready(function($) {
	var colorsJsonContainer = $('#color_style + .json-data'),
		colors = colorsJsonContainer.length ? (colorsJsonContainer[0].onclick() || {}) : {};
	jQuery('#color_style').on('change keyup', function() {
		var colorScheme = colors[$(this).val()];
		if (colorScheme === undefined || colorScheme.values === undefined) return;
		for (var key in colorScheme.values) {
			if ( ! colorScheme.values.hasOwnProperty(key)) continue;
			var value = colorScheme.values[key];
			$('#section-' + key + ' .colorSelector').ColorPickerSetColor(value).children().css('backgroundColor', value);
			$('#section-' + key + ' .of-color').val(value);
		}
	});

	jQuery('#section-header_sticky .controls .switch-options').click(function(){
		if (jQuery('#section-header_sticky .controls .cb-enable').hasClass('selected')) {
			if (window.headerLayout == 'standard' || window.headerLayout == 'extended') {
				jQuery('#section-header_main_sticky_height_1').slideDown('normal', "swing");
			}
		} else {
			jQuery('#section-header_main_sticky_height_1').slideUp('normal', "swing");
		}
	});

	jQuery.fn.showIf = function(show){
		if (show){
			this.slideDown('normal', 'swing')
		}else{
			this.slideUp('normal', 'swing')
		}
		return this;
	};

	/**
	 * Provides proper conditioning along with sticky option
	 * @param show
	 * @returns {jQuery.fn}
	 */
	jQuery.fn.showIfStickyAnd = function(show){
		var headerIsSticky = jQuery('#section-header_sticky .controls .cb-enable').hasClass('selected');
		return this.toggleClass('f_header_sticky', show).showIf(headerIsSticky && show);
	};

	// Logo type switcher conditionals
	jQuery('#section-logo_type .of-radio-img-img').on('click', function(){
		window.logoType = jQuery(this).siblings('#of-radio-img-logo_type1').length ? 'text' : 'image';
		jQuery('#section-logo_text').showIf(logoType == 'text');
		jQuery('#section-logo_image, #section-logo_image_transparent').showIf(logoType == 'image');
		jQuery('#section-logo_height, #section-logo_height_sticky').showIf(logoType == 'image' && window.headerLayout != 'sided');
		jQuery('#section-logo_height_tablets, #section-logo_height_mobiles').showIf(logoType == 'image' && window.headerLayout != 'sided');
		jQuery('#section-logo_width').showIf(logoType == 'image' && window.headerLayout == 'sided');
		jQuery('#section-logo_font_size, #section-logo_font_size_tablets, #section-logo_font_size_mobiles').showIf(logoType == 'text');
	});
	if (jQuery('#section-logo_type .of-radio-img-img.of-radio-img-selected').length) {
		jQuery('#section-logo_type .of-radio-img-img.of-radio-img-selected').click();
	} else {
		jQuery('#section-logo_type .of-radio-img-img:first').click();
	}

	// Canvas layout switcher conditionals
	jQuery('#section-canvas_layout .of-radio-img-img').on('click', function(){
		window.canvasType = jQuery(this).siblings('#of-radio-img-canvas_layout1').length ? 'wide' : 'boxed';
		jQuery('#section-color_body_bg, #section-body_bg_image, #section-body_bg_image_repeat').showIf(canvasType == 'boxed');
		jQuery('#section-body_bg_image_position, #section-body_bg_image_attachment, #section-body_bg_image_size').showIf(canvasType == 'boxed');
		jQuery('#section-site_canvas_width').showIf(canvasType == 'boxed');
	});
	if (jQuery('#section-canvas_layout .of-radio-img-img.of-radio-img-selected').length) {
		jQuery('#section-canvas_layout .of-radio-img-img.of-radio-img-selected').click();
	} else {
		jQuery('#section-canvas_layout .of-radio-img-img:first').click();
	}

	// Header layout switcher conditionals
	jQuery('#section-header_layout .of-radio-img-img').on('click', function(){
		var $this = jQuery(this),
			headerIsSticky = jQuery('#section-header_sticky .controls .cb-enable').hasClass('selected'),
			layout = 'standard'; // = 1
		if (jQuery(this).siblings('#of-radio-img-header_layout2').length) {
			layout = 'extended'; // = 2
		}
		else if (jQuery(this).siblings('#of-radio-img-header_layout3').length) {
			layout = 'advanced'; // = 3
		}
		else if (jQuery(this).siblings('#of-radio-img-header_layout4').length) {
			layout = 'centered'; // = 4
		}
		else if (jQuery(this).siblings('#of-radio-img-header_layout5').length) {
			layout = 'sided'; // = 5
		}

		window.headerLayout = layout;
		jQuery('#section-header_hidden').showIfStickyAnd(layout != 'sided');
		jQuery('#section-header_main_height').showIf(layout != 'sided');
		jQuery('#section-header_main_sticky_height_1').showIfStickyAnd(layout == 'standard' || layout == 'extended');
		jQuery('#section-header_main_sticky_height_2').showIfStickyAnd(layout == 'advanced' || layout == 'centered');
		jQuery('#section-header_extra_height').showIf(layout == 'extended' || layout == 'advanced' || layout == 'centered');
		jQuery('#section-header_extra_sticky_height_1').showIfStickyAnd(layout == 'extended');
		jQuery('#section-header_extra_sticky_height_2').showIfStickyAnd(layout == 'advanced' || layout == 'centered');
		jQuery('#section-header_main_width').showIf(layout == 'sided');
		jQuery('#section-header_scroll_breakpoint').showIfStickyAnd(layout != 'sided');
		jQuery('#section-header_fullwidth').showIf(layout != 'sided');
		jQuery('#section-header_invert_logo_pos').showIf(layout == 'standard' || layout == 'extended' || layout == 'advanced');

		if (layout == 'standard' || layout == 'centered') {
			jQuery('#section-header_language_show').slideUp('normal', "swing");
			jQuery('#section-header_language_source').slideUp('normal', "swing");
			jQuery('#section-header_link_qty').slideUp('normal', "swing");
			jQuery('#section-header_link_title').slideUp('normal', "swing");
			jQuery('#section-header_link_1_label').slideUp('normal', "swing");
			jQuery('#section-header_link_1_url').slideUp('normal', "swing");
			jQuery('#section-header_link_2_label').slideUp('normal', "swing");
			jQuery('#section-header_link_2_url').slideUp('normal', "swing");
			jQuery('#section-header_link_3_label').slideUp('normal', "swing");
			jQuery('#section-header_link_3_url').slideUp('normal', "swing");
			jQuery('#section-header_link_4_label').slideUp('normal', "swing");
			jQuery('#section-header_link_4_url').slideUp('normal', "swing");
			jQuery('#section-header_link_5_label').slideUp('normal', "swing");
			jQuery('#section-header_link_5_url').slideUp('normal', "swing");
			jQuery('#section-header_link_6_label').slideUp('normal', "swing");
			jQuery('#section-header_link_6_url').slideUp('normal', "swing");
			jQuery('#section-header_link_7_label').slideUp('normal', "swing");
			jQuery('#section-header_link_7_url').slideUp('normal', "swing");
			jQuery('#section-header_link_8_label').slideUp('normal', "swing");
			jQuery('#section-header_link_8_url').slideUp('normal', "swing");
			jQuery('#section-header_link_9_label').slideUp('normal', "swing");
			jQuery('#section-header_link_9_url').slideUp('normal', "swing");

			jQuery('#section-header_contacts_show').slideUp('normal', "swing");
			jQuery('#section-header_contacts_phone').slideUp('normal', "swing");
			jQuery('#section-header_contacts_email').slideUp('normal', "swing");

			jQuery('#section-header_socials_show').slideUp('normal', "swing");
			jQuery('#section-header_socials_facebook').slideUp('normal', "swing");
			jQuery('#section-header_socials_twitter').slideUp('normal', "swing");
			jQuery('#section-header_socials_google').slideUp('normal', "swing");
			jQuery('#section-header_socials_linkedin').slideUp('normal', "swing");
			jQuery('#section-header_socials_youtube').slideUp('normal', "swing");
			jQuery('#section-header_socials_vimeo').slideUp('normal', "swing");
			jQuery('#section-header_socials_flickr').slideUp('normal', "swing");
			jQuery('#section-header_socials_instagram').slideUp('normal', "swing");
			jQuery('#section-header_socials_behance').slideUp('normal', "swing");
			jQuery('#section-header_socials_xing').slideUp('normal', "swing");
			jQuery('#section-header_socials_deviantart').slideUp('normal', "swing");
			jQuery('#section-header_socials_foursquare').slideUp('normal', "swing");
			jQuery('#section-header_socials_github').slideUp('normal', "swing");
			jQuery('#section-header_socials_pinterest').slideUp('normal', "swing");
			jQuery('#section-header_socials_skype').slideUp('normal', "swing");
			jQuery('#section-header_socials_tumblr').slideUp('normal', "swing");
			jQuery('#section-header_socials_dribbble').slideUp('normal', "swing");
			jQuery('#section-header_socials_vk').slideUp('normal', "swing");
			jQuery('#section-header_socials_soundcloud').slideUp('normal', "swing");
			jQuery('#section-header_socials_yelp').slideUp('normal', "swing");
			jQuery('#section-header_socials_twitch').slideUp('normal', "swing");
			jQuery('#section-header_socials_rss').slideUp('normal', "swing");
			jQuery('#section-header_socials_custom_icon').slideUp('normal', "swing");
			jQuery('#section-header_socials_custom_url').slideUp('normal', "swing");

			jQuery('#section-header_show_custom').slideUp('normal', "swing");
			jQuery('#section-header_contacts_custom_icon').slideUp('normal', "swing");
			jQuery('#section-header_contacts_custom_text').slideUp('normal', "swing");

		} else {
			jQuery('#section-header_contacts_show').slideDown('normal', "swing");
			jQuery('#section-header_contacts_show .controls .switch-options .selected').click();

			jQuery('#section-header_socials_show').slideDown('normal', "swing");
			jQuery('#section-header_socials_show .controls .switch-options .selected').click();

			jQuery('#section-header_show_custom').slideDown('normal', "swing");
			jQuery('#section-header_show_custom .controls .switch-options .selected').click();

			jQuery('#section-header_language_show').slideDown('normal', "swing");
			jQuery('#section-header_language_show .controls').click();
		}

		jQuery('#section-logo_height, #section-logo_height_sticky').showIf(logoType == 'image' && headerLayout != 'sided');
		jQuery('#section-logo_height_tablets, #section-logo_height_mobiles').showIf(logoType == 'image' && headerLayout != 'sided');
		jQuery('#section-logo_width').showIf(logoType == 'image' && headerLayout == 'sided');
	});
	if (jQuery('#section-header_layout .of-radio-img-img.of-radio-img-selected').length) {
		jQuery('#section-header_layout .of-radio-img-img.of-radio-img-selected').click();
	} else {
		jQuery('#section-header_layout .of-radio-img-img:first').click();
	}

	jQuery('#section-header_language_show .controls').live('click', function() {

		if (jQuery('#section-header_language_show .controls .cb-enable').hasClass('selected')) {
			jQuery('#section-header_language_source').slideDown('normal', "swing");
			jQuery('#header_language_source').change();

		} else {
			jQuery('#section-header_language_source').slideUp('normal', "swing");
			jQuery('#section-header_link_qty').slideUp('normal', "swing");
			jQuery('#section-header_link_title').slideUp('normal', "swing");
			jQuery('#section-header_link_1_label').slideUp('normal', "swing");
			jQuery('#section-header_link_1_url').slideUp('normal', "swing");
			jQuery('#section-header_link_2_label').slideUp('normal', "swing");
			jQuery('#section-header_link_2_url').slideUp('normal', "swing");
			jQuery('#section-header_link_3_label').slideUp('normal', "swing");
			jQuery('#section-header_link_3_url').slideUp('normal', "swing");
			jQuery('#section-header_link_4_label').slideUp('normal', "swing");
			jQuery('#section-header_link_4_url').slideUp('normal', "swing");
			jQuery('#section-header_link_5_label').slideUp('normal', "swing");
			jQuery('#section-header_link_5_url').slideUp('normal', "swing");
			jQuery('#section-header_link_6_label').slideUp('normal', "swing");
			jQuery('#section-header_link_6_url').slideUp('normal', "swing");
			jQuery('#section-header_link_7_label').slideUp('normal', "swing");
			jQuery('#section-header_link_7_url').slideUp('normal', "swing");
			jQuery('#section-header_link_8_label').slideUp('normal', "swing");
			jQuery('#section-header_link_8_url').slideUp('normal', "swing");
			jQuery('#section-header_link_9_label').slideUp('normal', "swing");
			jQuery('#section-header_link_9_url').slideUp('normal', "swing");

		}

	});


	jQuery('#header_language_source').live('change', function(){
		if (jQuery(this).val() == 'own') {
			jQuery('#section-header_link_qty').slideDown('normal', "swing");
			jQuery('#section-header_link_title').slideDown('normal', "swing");
			jQuery('#header_link_qty').change();

		} else {
			jQuery('#section-header_link_qty').slideUp('normal', "swing");
			jQuery('#section-header_link_title').slideUp('normal', "swing");
			jQuery('#section-header_link_1_label').slideUp('normal', "swing");
			jQuery('#section-header_link_1_url').slideUp('normal', "swing");
			jQuery('#section-header_link_2_label').slideUp('normal', "swing");
			jQuery('#section-header_link_2_url').slideUp('normal', "swing");
			jQuery('#section-header_link_3_label').slideUp('normal', "swing");
			jQuery('#section-header_link_3_url').slideUp('normal', "swing");
			jQuery('#section-header_link_4_label').slideUp('normal', "swing");
			jQuery('#section-header_link_4_url').slideUp('normal', "swing");
			jQuery('#section-header_link_5_label').slideUp('normal', "swing");
			jQuery('#section-header_link_5_url').slideUp('normal', "swing");
			jQuery('#section-header_link_6_label').slideUp('normal', "swing");
			jQuery('#section-header_link_6_url').slideUp('normal', "swing");
			jQuery('#section-header_link_7_label').slideUp('normal', "swing");
			jQuery('#section-header_link_7_url').slideUp('normal', "swing");
			jQuery('#section-header_link_8_label').slideUp('normal', "swing");
			jQuery('#section-header_link_8_url').slideUp('normal', "swing");
			jQuery('#section-header_link_9_label').slideUp('normal', "swing");
			jQuery('#section-header_link_9_url').slideUp('normal', "swing");

		}
	});

	jQuery('#header_link_qty').on('change', function(){
		var newValue = jQuery(this).val()-0;
		for(var i = 1; i < 10; i++) {
			jQuery('#section-header_link_'+i+'_label').showIf(i <= newValue);
			jQuery('#section-header_link_'+i+'_url').showIf(i <= newValue);
		}
	});



	jQuery('.of-color').on('change', function(){
		jQuery(this).siblings('.colorSelector').ColorPickerSetColor(jQuery(this).val());
		jQuery(this).siblings('.colorSelector').children('div').css('backgroundColor', jQuery(this).val());
	})

});
