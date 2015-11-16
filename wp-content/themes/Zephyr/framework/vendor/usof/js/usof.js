/**
 * Retrieve/set/erase dom modificator class <mod>_<value> for UpSolution CSS Framework
 * @param {String} mod Modificator namespace
 * @param {String} [value] Value
 * @returns {string|jQuery}
 */
jQuery.fn.usMod = function(mod, value){
	if (this.length == 0) return this;
	// Remove class modificator
	if (value === false){
		return this.each(function(){
			this.className = this.className.replace(new RegExp('(^| )'+mod+'\_[a-z0-9\_]+( |$)'), '$2');
		});
	}
	var pcre = new RegExp('^.*?'+mod+'\_([a-z0-9\_]+).*?$'),
		arr;
	// Retrieve modificator
	if (value === undefined){
		return (arr = pcre.exec(this.get(0).className)) ? arr[1] : false;
	}
	// Set modificator
	else {
		return this.each(function(){
			this.className = this.className.replace(new RegExp('(^| )'+mod+'\_[a-z0-9\_]+( |$)'), '$1'+mod+'_'+value+'$2');
		});
	}
};


/**
 * Function.bind: simple function for defining scopes for functions called from events
 */
Function.prototype.usBind = function(scope, args){
	var self = this;
	return function(){
		return self.apply(scope, args || arguments);
	};
};


/**
 * USOF Fields
 */
!function($){

	var USOFField = function(row, options){
		this.$row = $(row);
		this.type = this.$row.usMod('type');
		this.id = this.$row.data('id');
		this.$input = this.$row.find('[name="'+this.id+'"]');
		this.inited = false;

		/**
		 * Boundable field events
		 */
		this.$$events = {
			beforeShow: [],
			afterShow: [],
			change: [],
			beforeHide: [],
			afterHide: []
		};

		// Overloading selected functions, moving parent functions to "parent" namespace: init => parentInit
		if (USOFField[this.type] !== undefined){
			for (var fn in USOFField[this.type]){
				if ( ! USOFField[this.type].hasOwnProperty(fn)) continue;
				if (this[fn] !== undefined){
					var parentFn = 'parent'+fn.charAt(0).toUpperCase()+fn.slice(1);
					this[parentFn] = this[fn];
				}
				this[fn] = USOFField[this.type][fn];
			}
		}

		this.$row.data('usofField', this);

		// Init on first show
		var initEvent = function(){
			this.init(options);
			this.inited = true;
			this.removeEvent('beforeShow', initEvent);
		}.usBind(this);
		this.addEvent('beforeShow', initEvent);
	};
	USOFField.prototype = {
		init: function(){
			this.$input.on('change', function(){
				this.fireEvent('change', this.getValue());
			}.usBind(this));
		},
		getValue: function(){
			return this.$input.val();
		},
		setValue: function(value){
			this.$input.val(value);
			this.fireEvent('change', value);
		},

		addEvent: function(trigger, fn){
			if (this.$$events[trigger] === undefined) this.$$events[trigger] = [];
			this.$$events[trigger].push(fn);
		},
		fireEvent: function(trigger, values){
			if (this.$$events[trigger] === undefined || this.$$events[trigger].length == 0) return;
			for (var index = 0; index < this.$$events[trigger].length; index++){
				this.$$events[trigger][index](this, values);
			}
		},
		removeEvent: function(trigger, fn){
			if (this.$$events[trigger] === undefined) return;
			var fnPos = $.inArray(fn, this.$$events[trigger]);
			if (fnPos != -1){
				this.$$events[trigger].splice(fnPos, 1);
			}
		}
	};

	/**
	 * USOF Field: Backup
	 */
	USOFField['backup'] = {

		init: function(){
			this.$backupStatus = this.$row.find('.usof-backup-status');
			this.$btnBackup = this.$row.find('.usof-button.type_backup').on('click', this.backup.usBind(this));
			this.$btnRestore = this.$row.find('.usof-button.type_restore').on('click', this.restore.usBind(this));

			// JS Translations
			var $i18n = this.$row.find('.usof-backup-i18n');
			this.i18n = {};
			if ($i18n.length > 0){
				this.i18n = $i18n[0].onclick() || {};
			}
		},

		backup: function(){
			$.ajax({
				type: 'POST',
				url: window.$usof.ajaxUrl,
				dataType: 'json',
				data: {
					action: 'usof_backup',
					_wpnonce: this.$row.closest('.usof-form').find('[name="_wpnonce"]').val(),
					_wp_http_referer: this.$row.closest('.usof-form').find('[name="_wp_http_referer"]').val()
				},
				success: function(result){
					this.$backupStatus.html(result.data.status);
					this.$btnRestore.show();
					alert(result.data.message);
				}.usBind(this)
			});
		},

		restore: function(){
			if ( ! confirm(this.i18n.restore_confirm)) return;
			$.ajax({
				type: 'POST',
				url: window.$usof.ajaxUrl,
				dataType: 'json',
				data: {
					action: 'usof_restore_backup',
					_wpnonce: this.$row.closest('.usof-form').find('[name="_wpnonce"]').val(),
					_wp_http_referer: this.$row.closest('.usof-form').find('[name="_wp_http_referer"]').val()
				},
				success: function(result){
					window.$usof.setValues(result.data.usof_options);
					window.$usof.save();
					alert(result.data.message);
				}.usBind(this)
			});
		}

	};

	/**
	 * USOF Field: Checkbox
	 */
	USOFField['checkboxes'] = {

		getValue: function(){
			var value = [];
			$.each(this.$input, function(){
				if (this.checked) value.push(this.value);
			});
			return value;
		},

		setValue: function(value){
			$.each(this.$input, function(){
				$(this).attr('checked', ($.inArray(this.value, value) != -1) ? 'checked' : false);
			});
		}

	};

	/**
	 * USOF Field: Color
	 */
	USOFField['color'] = {

		init: function(options){
			this.$preview = this.$row.find('.usof-color-preview');
			this.$input.colpick({
				layout: 'hex',
				color: (this.$input.val()||'').substring(1),
				submit: false,
				showEvent: 'focus',
				onChange: function(hsb, hex, rgb, el, bySetColor){
					this.$preview.css('background-color', '#'+hex);
					if ( ! bySetColor) this.$input.val('#'+hex);
				}.usBind(this),
				onHide: function(){
					this.fireEvent('change', this.$input.val());
				}.usBind(this)
			});
			this.$input.on('keyup', function(){
				var value = this.$input.val()||'';
				if (value.charAt(0) == '#') value = value.substring(1);
				if (value.length == 3 || value.length == 6){
					this.$input.colpickSetColor(value);
				}
			}.usBind(this));
			this.$input.on('change', function(){
				this.setValue(this.$input.val());
			}.usBind(this));
			this.$preview.on('click', function(){
				this.$input.colpickShow();
			}.usBind(this));
		},

		setValue: function(value){
			var cleanValue = value;
			if (cleanValue.charAt(0) == '#') cleanValue = cleanValue.substring(1);
			if (cleanValue.length == 3 || cleanValue.length == 6){
				this.$input.colpickSetColor(cleanValue);
				value = '#' + cleanValue;
			}
			this.parentSetValue(value);
		}

	};

	/**
	 * USOF Field: Font
	 */
	USOFField['font'] = {

		init: function(options){
			this.parentInit(options);
			this.$select = this.$row.find('select');
			this.$preview = this.$row.find('.usof-font-preview');
			this.$weightsContainer = this.$row.find('.usof-checkbox-list');
			this.$weightCheckboxes = this.$weightsContainer.find('.usof-checkbox');
			this.$weights = this.$weightsContainer.find('input');
			this.fonts = $('.usof-fonts-json')[0].onclick() || {};
			this.curFont = this.$select.find(':selected').val();

			this.$select.on('change', function(){
				this.setValue(this._getValue());
			}.usBind(this));
			this.$weights.on('change', function(){
				this.setValue(this._getValue());
			}.usBind(this));
			if (this.curFont != 'none' && this.curFont.indexOf(',') == -1){
				$('head').append('<link href="//fonts.googleapis.com/css?family='+ this.curFont.replace(/\s+/g, '+') +'" rel="stylesheet" type="text/css" class="usof_font_'+ this.id +'" />');
				this.$preview.css('font-family', this.curFont +'');
			}
			this.$select.select2();
		},

		setValue: function(value){
			var parts = value.split('|'),
				fontName = parts[0] || 'none',
				fontWeights = parts[1] || '400,700';
			fontWeights = fontWeights.split(',');
			if (fontName != this.curFont){
				$('.usof_font_' + this.id).remove();
				if (fontName == 'none'){
					// Selected no-font
					this.$preview.css('font-family', '');
				}
				else if (fontName.indexOf(',') != -1){
					// Web-safe font combination
					this.$preview.css('font-family', fontName);
				}
				else {
					// Selected some google font: show preview
					$('head').append('<link href="//fonts.googleapis.com/css?family='+ fontName.replace(/\s+/g, '+') +'" rel="stylesheet" type="text/css" class="usof_font_'+ this.id +'" />');
					this.$preview.css('font-family', fontName +', sans-serif');
				}
				if (this.$select.select2('val') != fontName){
					// setValue may be called both from inside and outside, so checking to avoid recursion
					this.$select.select2('val', fontName);
				}
				this.curFont = fontName;
			}
			// Show the available weights
			if (this.fonts[fontName] === undefined){
				this.$weightCheckboxes.addClass('hidden');
			}else{
				this.$weightCheckboxes.each(function(index, elm){
					var $elm = $(elm),
						weightValue = $elm.data('value')+'';
					$elm.toggleClass('hidden', $.inArray(weightValue, this.fonts[fontName].variants) == -1);
					$elm.attr('checked', ($.inArray(weightValue, fontWeights) == -1) ? 'checked' : false);
				}.usBind(this));
			}
			this.parentSetValue(value);
		},

		_getValue: function(){
			var fontName = this.$select.val(),
				fontWeights = [];
			if (this.fonts[fontName] !== undefined && this.fonts[fontName].variants !== undefined){
				this.$weights.filter(':checked').each(function(index, elm){
					var weightValue = $(elm).val()+'';
					if ($.inArray(weightValue, this.fonts[fontName].variants) != -1){
						fontWeights.push(weightValue);
					}
				}.usBind(this));
			}
			return fontName + '|' + fontWeights.join(',');
		}

	};

	/**
	 * USOF Field: Imgradio / Radio
	 */
	USOFField['imgradio'] = USOFField['radio'] = {

		getValue: function(){
			return this.$input.filter(':checked').val();
		},

		setValue: function(value){
			this.$input.filter('[value="'+value+'"]').attr('checked', 'checked');
		}

	};

	/**
	 * USOF Field: Slider
	 */
	USOFField['slider'] = {

		init: function(options){
			this.$slider = this.$row.find('.usof-slider');
			// Params
			this.min = parseInt(this.$slider.data('min'));
			this.max = parseInt(this.$slider.data('max'));
			this.step = parseInt(this.$slider.data('step')) || 1;
			this.prefix = this.$slider.data('prefix') || '';
			this.postfix = this.$slider.data('postfix') || '';
			this.$textfield = this.$row.find('input[type="text"]');
			this.$box = this.$row.find('.usof-slider-box');
			this.$range = this.$row.find('.usof-slider-range');
			this.$body = $(document.body);
			this.$window = $(window);
			// Needed box dimensions
			this.sz = {};
			var draggedValue;
			this._events = {
				dragstart: function(e){
					e.stopPropagation();
					this.sz = {left: this.$box.offset().left, width: this.$box.width()};
					this.$body.on('mousemove', this._events.dragmove);
					this.$window.on('mouseup', this._events.dragstop);
					this._events.dragmove(e);
				}.usBind(this),
				dragmove: function(e){
					e.stopPropagation();
					var x = Math.max(0, Math.min(1, (this.sz == 0) ? 0 : ((e.pageX - this.sz.left) / this.sz.width))),
						value = parseFloat(this.min + x * (this.max - this.min));
					value = Math.round(value / this.step) * this.step;
					this.renderValue(value);
					draggedValue = value;
				}.usBind(this),
				dragstop: function(e){
					e.stopPropagation();
					this.$body.off('mousemove', this._events.dragmove);
					this.$window.off('mouseup', this._events.dragstop);
					this.setValue(draggedValue);
				}.usBind(this)
			};
			this.$textfield.on('focus', function(){
				this.$textfield.val(this.getValue());
			}.usBind(this));
			this.$textfield.on('blur', function(){
				var value = parseInt(this.$textfield.val().replace('[^0-9]+', ''));
				this.setValue(value);
			}.usBind(this));
			this.$box.on('mousedown', this._events.dragstart);
		},

		renderValue: function(value){
			var x = Math.max(0, Math.min(1, (value - this.min) / (this.max - this.min)));
			this.$range.css('left', x * 100 + '%');
			this.$textfield.val(this.prefix + value + this.postfix);
		},

		setValue: function(value){
			this.renderValue(value);
			this.parentSetValue(value);
		}

	};

	/**
	 * USOF Field: Switch
	 */
	USOFField['switch'] = {

		getValue: function(){
			return (this.$input.is(':checked') ? this.$input.get(1).value : this.$input.get(0).value);
		},

		setValue: function(value){
			this.$input.get(1).checked = !! value;
		}

	};

	/**
	 * USOF Field: Text / Textarea
	 */
	USOFField['text'] = USOFField['textarea'] = {

		init: function(){
			this.$input.on('change keyup', function(){
				this.fireEvent('change', this.getValue());
			}.usBind(this));
		}

	};

	/**
	 * USOF Field: Transfer
	 */
	USOFField['transfer'] = {

		init: function(){
			this.$textarea = this.$row.find('textarea');
			this.translations = (this.$row.find('.usof-transfer-translations')[0].onclick() || {});
			this.$btnExport = this.$row.find('.usof-button.type_export');
			this.$btnImport = this.$row.find('.usof-button.type_import');
			this.$btnImport.on('click', this.importValues.usBind(this));

			this.exportValues();
			this.addEvent('beforeShow', this.exportValues.usBind(this));
		},

		exportValues: function(){
			var values = window.$usof.getValues();
			this.$textarea.val(JSON.stringify(values));
		},

		importValues: function(){
			var encoded = this.$textarea.val(),
				values;
			try {
				if (encoded.charAt(0) == '{'){
					// New USOF export: json-encoded
					values = JSON.parse(encoded);
				}else{
					// Old SMOF export: base64-encoded
					var serialized = window.atob(encoded),
						matches = serialized.match(/(s\:[0-9]+\:\"(.*?)\"\;)|(i\:[0-9]+\;)/g),
						_key = null,
						_value;
					values = {};
					for (var i = 0; i < matches.length; i++){
						_value = matches[i].replace((matches[i].charAt(0) == 's') ? /^s\:[0-9]+\:\"(.*?)\"\;$/ : /^i\:([0-9]+)\;$/, '$1');
						if (_key === null){
							_key = _value;
						}else{
							values[_key] = _value;
							_key = null;
						}
					}
				}
			}catch(error){
				return alert(this.translations.importError);
			}
			window.$usof.setValues(values);
			window.$usof.save();
		}

	};

	/**
	 * USOF Field: Style Scheme
	 */
	USOFField['style_scheme'] = {

		init: function(options){
			this.$select = this.$row.find('select');
			this.schemes = (this.$row.find('.usof-form-row-control-json')[0].onclick() || {});
			this.$select.on('change keyup', function(){
				var value = this.$select.val();
				if (window.$usof !== undefined){
					if (this.schemes[value] === undefined || this.schemes[value].values === undefined) return;
					$.each(this.schemes[value].values, function(id, value){
						window.$usof.setValue(id, value);
					});
				}
				this.fireEvent('change', value);
			}.usBind(this));
		}

	};

	/**
	 * USOF Field: Upload
	 */
	USOFField['upload'] = {

		init: function(options){
			this.parentInit(options);
			// Cached URLs for certain values (images IDs and sizes)
			this.imageUrls = {};
			this.$btnSet = this.$row.find('.usof-button.type_set');
			this.$btnRemove = this.$row.find('.usof-button.type_remove');
			this.$previewContainer = this.$row.find('.usof-upload-container');
			this.$previewImg = this.$previewContainer.find('img');
			this.$btnSet.add(this.$row.find('.usof-button.type_change')).on('click', function(){
				wp.media.view.l10n.insertIntoPost = this.$btnSet.text();
				wp.media.editor.send.attachment = function(props, attachment){
					var size = props.size || 'full',
						value = attachment.id + '|' + size;
					this.imageUrls[value] = attachment.sizes[size].url;
					this.setValue(value);
					return $.Deferred();
				}.usBind(this);
				wp.media.editor.open('usof', {
					frame: 'post',
					state: 'insert',
					multiple: false
				});
			}.usBind(this));
			this.$btnRemove.on('click', function(){
				this.setValue('');
			}.usBind(this));
		},

		setValue: function(value){
			if (value == ''){
				// Removed value
				this.$previewContainer.hide();
				this.$btnSet.show();
				this.$previewImg.attr('src', '');
			}else{
				if (value.match(/^[0-9]+(\|[a-z_\-0-9]+)?$/)){
					if (this.imageUrls[value] === undefined){
						$.ajax({
							type: 'POST',
							url: window.$usof.ajaxUrl,
							dataType: 'json',
							data: {
								action: 'usof_get_image',
								value: value,
								_wpnonce: this.$row.closest('.usof-form').find('[name="_wpnonce"]').val(),
								_wp_http_referer: this.$row.closest('.usof-form').find('[name="_wp_http_referer"]').val()
							},
							success: function(result){
								if ( ! result.success){
									return alert(result.message);
								}
								this.imageUrls[value] = result.data.url;
								this.$previewImg.attr('src', this.imageUrls[value]);
							}.usBind(this)
						});
					}else{
						this.$previewImg.attr('src', this.imageUrls[value]);
					}
				}else{
					// Direct image URL (for old SMOF framework compatibility)
					this.$previewImg.attr('src', value);
				}
				this.$previewContainer.show();
				this.$btnSet.hide();
			}
			this.parentSetValue(value);
		}
	};

	/**
	 * Field initialization
	 *
	 * @param options object
	 * @returns {USOFField}
	 */
	$.fn.usofField = function(options){
		return new USOFField(this, options);
	};

}(jQuery);


/**
 * USOF Core
 */
!function($){
	function USOF(container, options){
		window.$usof = this;
		this.init(container, options);
	}
	USOF.prototype = {
		init: function(container, options){
			this.$container = $(container);
			this.ajaxUrl = this.$container.data('ajaxurl');
			this.$title = this.$container.find('.usof-header-title h2');
			this.$blocks = {};
			this.$field = {};
			// Showing conditions (fieldId => condition)
			this.showIf = {};
			// Showing dependencies (fieldId => affected field ids)
			this.showIfDeps = {};
			$.each(this.$container.find('.usof-form-row, .usof-form-wrapper'), function(index, block){
				var $block = $(block),
					id = $block.data('id'),
					isRow = $block.hasClass('usof-form-row'),
					$showIf = $block.find(isRow ? '.usof-form-row-showif' : '.usof-form-wrapper-showif');
				this.$blocks[id] = $block;
				if ($showIf.length > 0){
					this.showIf[id] = $showIf[0].onclick() || [];
					$showIf.remove();
					// Writing dependencies
					var showIfVars = this.getShowIfVariables(this.showIf[id]);
					for (var i = 0; i < showIfVars.length; i++){
						if (this.showIfDeps[showIfVars[i]] === undefined) this.showIfDeps[showIfVars[i]] = [];
						this.showIfDeps[showIfVars[i]].push(id);
					}
				}
				if (isRow){
					this.$field[id] = $block.usofField(block);
				}
			}.usBind(this));
			for (var fieldId in this.showIfDeps){
				if ( ! this.showIfDeps.hasOwnProperty(fieldId)) continue;
				this.$field[fieldId].addEvent('change', function(field){
					$.each(this.showIfDeps[field.id], function(index, depFieldId){
						// Getting stored value to take animations into account as well
						var isShown = this.$blocks[depFieldId].data('isShown'),
							shouldBeShown = this.executeShowIf(this.showIf[depFieldId]);
						if (isShown === undefined){
							isShown = (this.$blocks[depFieldId].css('display') != 'none');
						}
						if (shouldBeShown && ! isShown){
							this.fireFieldEvent(this.$blocks[depFieldId], 'beforeShow');
							this.$blocks[depFieldId].stop(true, false).slideDown(function(){
								this.fireFieldEvent(this.$blocks[depFieldId], 'afterShow');
							}.usBind(this));
							this.$blocks[depFieldId].data('isShown', true);
						}else if ( ! shouldBeShown && isShown){
							this.fireFieldEvent(this.$blocks[depFieldId], 'beforeHide');
							this.$blocks[depFieldId].stop(true, false).slideUp(function(){
								this.fireFieldEvent(this.$blocks[depFieldId], 'afterHide');
							}.usBind(this));
							this.$blocks[depFieldId].data('isShown', false);
						}
					}.usBind(this));
				}.usBind(this));
			}

			this.active = null;
			this.$sections = {};
			this.$sectionContents = {};
			this.sectionFields = {};
			$.each(this.$container.find('.usof-section'), function(index, section){
				var $section = $(section),
					sectionId = $section.data('id');
				this.$sections[sectionId] = $section;
				this.$sectionContents[sectionId] = $section.find('.usof-section-content');
				if ($section.hasClass('current')){
					this.active = sectionId;
				}
				this.sectionFields[sectionId] = [];
				$.each($section.find('.usof-form-row'), function(index, row){
					var $row = $(row),
						fieldId = $row.data('id');
					if (fieldId){
						this.sectionFields[sectionId].push(fieldId);
					}
				}.usBind(this));
			}.usBind(this));

			this.sectionTitles = {};
			$.each(this.$container.find('.usof-nav-item.level_1'), function(index, item){
				var $item = $(item),
					sectionId = $item.data('id');
				this.sectionTitles[sectionId] = $item.find('.usof-nav-title').html();
			}.usBind(this));

			this.navItems = this.$container.find('.usof-nav-item.level_1, .usof-section-header');
			this.navItems.each(function(index, item){
				var $item = $(item),
					sectionId = $item.data('id');
				$item.on('click', function(){
					this.openSection(sectionId);
				}.usBind(this));
			}.usBind(this));

			// Handling initial document hash
			if (document.location.hash && document.location.hash.indexOf('#!') == -1){
				this.openSection(document.location.hash.substring(1));
			}

			// Initializing fields at the shown section
			if (this.$sections[this.active] !== undefined){
				this.fireFieldEvent(this.$sections[this.active], 'beforeShow');
				this.fireFieldEvent(this.$sections[this.active], 'afterShow');
			}

			// Save action
			this.$saveControl = this.$container.find('.usof-control.for_save');
			this.$saveBtn = this.$saveControl.find('.usof-button').on('click', this.save.usBind(this));
			this.$saveMessage = this.$saveControl.find('.usof-control-message');
			this.valuesChanged = {};
			this.saveStateTimer = null;
			for (var fieldId in this.$field){
				if ( ! this.$field.hasOwnProperty(fieldId)) continue;
				this.$field[fieldId].addEvent('change', function(field, value){
					if ($.isEmptyObject(this.valuesChanged)){
						clearTimeout(this.saveStateTimer);
						this.$saveControl.usMod('status', 'notsaved');
					}
					this.valuesChanged[field.id] = value;
				}.usBind(this));
			}

			// Reset action
			this.$resetControl = this.$container.find('.usof-control.for_reset');
			this.$resetBtn = this.$resetControl.find('.usof-button').on('click', this.reset.usBind(this));
			this.$resetMessage = this.$resetControl.find('.usof-control-message');
			this.resetStateTimer = null;


			this.$window = $(window);
			this.$header = this.$container.find('.usof-header');

			this._events = {
				scroll: this.scroll.usBind(this),
				resize: this.resize.usBind(this)
			};

			this.resize();
			this.$window.on('resize load', this._events.resize);
			this.$window.on('scroll', this._events.scroll);
		},

		scroll: function(){
			this.$container.toggleClass('footer_fixed', this.$window.scrollTop() > this.headerAreaSize);
		},

		resize: function(){
			this.headerAreaSize = this.$header.offset().top + this.$header.outerHeight();
			this.scroll();
		},

		openSection: function(sectionId){
			if (sectionId == this.active || this.$sections[sectionId] === undefined) return;
			if (this.$sections[this.active] !== undefined){
				this.hideSection();
			}
			this.showSection(sectionId);
		},

		showSection: function(sectionId){
			var i;
			this.navItems.filter('[data-id="'+sectionId+'"]').addClass('current');
			this.fireFieldEvent(this.$sectionContents[sectionId], 'beforeShow');
			this.$sectionContents[sectionId].stop(true, false).slideDown();
			this.$title.html(this.sectionTitles[sectionId]);
			this.fireFieldEvent(this.$sectionContents[sectionId], 'afterShow');
			this.active = sectionId;
		},

		hideSection: function(){
			this.navItems.filter('[data-id="'+this.active+'"]').removeClass('current');
			this.fireFieldEvent(this.$sectionContents[this.active], 'beforeHide');
			this.$sectionContents[this.active].stop(true, false).slideUp();
			this.$title.html('');
			this.fireFieldEvent(this.$sectionContents[this.active], 'afterHide');
			this.active = null;
		},

		/**
		 * Find all the fields within $container and fire a certain event there
		 * @param $container jQuery
		 * @param trigger string
		 */
		fireFieldEvent: function($container, trigger){
			var isRow = $container.hasClass('usof-form-row'),
				hideShowEvent = (trigger == 'beforeShow' || trigger == 'afterShow' || trigger == 'beforeHide' || trigger == 'afterHide');
			if ( ! isRow){
				$container.find('.usof-form-row').each(function(index, block){
					var $block = $(block),
						isShown = $block.data('isShown');
					if (isShown === undefined){
						isShown = ($block.css('display') != 'none');
					}
					// The block is not actually shown or hidden in this case
					if (hideShowEvent && ! isShown) return;
					$block.data('usofField').fireEvent(trigger);
				}.usBind(this));
			}else{
				$container.data('usofField').fireEvent(trigger);
			}
		},

		getShowIfVariables: function(condition){
			if ( ! $.isArray(condition) || condition.length < 3){
				return [];
			}else if ($.inArray(condition[1].toLowerCase(), ['and', 'or']) != -1){
				// Complex or / and statement
				var vars = this.getShowIfVariables(condition[0]),
					index = 2;
				while (condition[index] !== undefined){
					vars = vars.concat(this.getShowIfVariables(condition[index]));
					index = index + 2;
				}
				return vars;
			}else{
				return [condition[0]];
			}
		},

		executeShowIf: function(condition){
			var result = true;
			if ( ! $.isArray(condition) || condition.length < 3){
				return result;
			}else if ($.inArray(condition[1].toLowerCase(), ['and', 'or']) != -1){
				// Complex or / and statement
				result = this.executeShowIf(condition[0]);
				var index = 2;
				while (condition[index] !== undefined){
					condition[index-1] = condition[index-1].toLowerCase();
					if (condition[index-1] == 'and'){
						result = (result && this.executeShowIf(condition[index]));
					}else if (condition[index-1] == 'or'){
						result = (result || this.executeShowIf(condition[index]));
					}
					index = index + 2;
				}
			}else{
				if (this.$field[condition[0]] === undefined){
					return true;
				}
				var value = this.$field[condition[0]].getValue();
				if ( condition[1] == '=' ) {
					result = ( value == condition[2] );
				} else if ( condition[1] == '!=' || condition[1] == '<>' ) {
					result = ( value != condition[2] );
				} else if ( condition[1] == 'in' ) {
					result = ( ! $.isArray( condition[2] ) || $.inArray( value, condition[2] ) != -1 );
				} else if ( condition[1] == 'not in' ) {
					result = ( ! $.isArray( condition[2] ) || $.inArray( value, condition[2] ) == -1 );
				} else if ( condition[1] == '<=' ) {
					result = ( value <= condition[2] );
				} else if ( condition[1] == '<' ) {
					result = ( value < condition[2] );
				} else if ( condition[1] == '>' ) {
					result = ( value > condition[2] );
				} else if ( condition[1] == '>=' ) {
					result = ( value >= condition[2] );
				} else {
					result = true;
				}
			}
			return result;
		},

		getValue: function(id){
			if (this.$field[id] === undefined) return undefined;
			return this.$field[id].getValue();
		},

		setValue: function(id, value){
			if (this.$field[id] === undefined) return;
			if ( ! this.$field[id].inited){
				this.$field[id].fireEvent('beforeShow');
				this.$field[id].fireEvent('afterShow');
			}
			this.$field[id].setValue(value);
		},

		getValues: function(id){
			var values = {};
			for (var fieldId in this.$field){
				if ( ! this.$field.hasOwnProperty(fieldId)) continue;
				values[fieldId] = this.getValue(fieldId);
			}
			return values;
		},

		setValues: function(values){
			for (var fieldId in values){
				if ( ! values.hasOwnProperty(fieldId) || this.$field[fieldId] == undefined) continue;
				this.setValue(fieldId, values[fieldId]);
				this.$field[fieldId].fireEvent('change', values[fieldId]);
			}
		},

		save: function(){
			if ($.isEmptyObject(this.valuesChanged)) return;
			clearTimeout(this.saveStateTimer);
			this.$saveMessage.html('');
			this.$saveControl.usMod('status', 'loading');
			$.ajax({
				type: 'POST',
				url: this.ajaxUrl,
				dataType: 'json',
				data: {
					action: 'usof_save',
					usof_options: JSON.stringify(this.valuesChanged),
					_wpnonce: this.$container.find('[name="_wpnonce"]').val(),
					_wp_http_referer: this.$container.find('[name="_wp_http_referer"]').val()
				},
				success: function(result){
					if (result.success){
						this.valuesChanged = {};
						this.$saveMessage.html(result.data.message);
						this.$saveControl.usMod('status', 'success');
						this.saveStateTimer = setTimeout(function(){
							this.$saveMessage.html('');
							this.$saveControl.usMod('status', 'clear');
						}.usBind(this), 4000);
					}else{
						this.$saveMessage.html(result.data.message);
						this.$saveControl.usMod('status', 'error');
						this.saveStateTimer = setTimeout(function(){
							this.$saveMessage.html('');
							this.$saveControl.usMod('status', 'notsaved');
						}.usBind(this), 4000);
					}
				}.usBind(this)
			});
		},

		reset: function(){
			if ( ! confirm('Are you sure want to reset all the options to default values?')) return;
			clearTimeout(this.resetStateTimer);
			this.$resetMessage.html('');
			this.$resetControl.usMod('status', 'loading');
			$.ajax({
				type: 'POST',
				url: this.ajaxUrl,
				dataType: 'json',
				data: {
					action: 'usof_reset',
					_wpnonce: this.$container.find('[name="_wpnonce"]').val(),
					_wp_http_referer: this.$container.find('[name="_wp_http_referer"]').val()
				},
				success: function(result){
					this.$resetMessage.html(result.data.message);
					this.$resetControl.usMod('status', 'success');
					this.resetStateTimer = setTimeout(function(){
						this.$resetMessage.html('');
						this.$resetControl.usMod('status', 'clear');
					}.usBind(this), 4000);
					this.setValues(result.data.usof_options);
					this.valuesChanged = {};
					this.$saveControl.usMod('status', 'clear');
				}.usBind(this)
			});
		}
	};

	new USOF('.usof-container');
}(jQuery);
