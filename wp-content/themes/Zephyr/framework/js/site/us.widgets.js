/**
 * UpSolution Shortcode: us_message
 */
(function ($) {
	"use strict";

	$.fn.usMessage = function(){
		return this.each(function(){
			var $this = $(this),
				$closer = $this.find('.w-message-close');
			$closer.click(function(){
				$this.wrap('<div></div>');
				var $wrapper = $this.parent();
				$wrapper.css({overflow: 'hidden', height: $this.outerHeight(true)});
				$wrapper.performCSSTransition({
					height: 0
				}, 400, function(){
					$wrapper.remove();
					$us.canvas.$container.trigger('contentChange');
				}, 'cubic-bezier(.4,0,.2,1)');
			});
		});
	};

	$(function(){
		$('.w-message').usMessage();
	});
})(jQuery);


/**
 * UpSolution Widget: w-lang
 */
(function($){
	"use strict";

	$.fn.wLang = function(){
		return this.each(function(){
			var $this = $(this),
				langList = $this.find('.w-lang-list'),
				currentLang = $this.find('.w-lang-current');
			if ($this.usMod('layout') == 'dropdown'){
				var closeListEvent = function(e){
					if ($this.has(e.target).length === 0){
						langList.slideUp(200, function(){
							$this.removeClass('active');
						});
						$us.canvas.$window.off('mouseup touchstart mousewheel DOMMouseScroll touchstart', closeListEvent);
					}
				};
				langList.slideUp(0);
				currentLang.click(function() {
					$this.addClass('active');
					langList.slideDown(200);
					$us.canvas.$window.on('mouseup touchstart mousewheel DOMMouseScroll touchstart', closeListEvent);
				});
			}
		});
	};

	$(function(){
		$('.w-lang').wLang();
	});
})(jQuery);


/**
 * UpSolution Widget: w-blog
 */
(function ($) {
	"use strict";

	$(function(){
		if ($.fn.isotope){
			// Applying isotope to blog posts
			$('.w-blog.layout_masonry .w-blog-list').each(function(index, list){
				var $list = $(list);
				$list.imagesLoaded(function(){
					$list.isotope({
						itemSelector: '.w-blog-post',
						layoutMode: 'masonry',
						isOriginLeft: ! $('.l-body').hasClass('rtl')
					});
				});
			});
		}
	});

	$.fn.gLoadmore = function(){
		return this.each(function(){
			var $this = $(this);
			if ( ! this.onclick) return;
			var data = this.onclick() || {},
				$btn = $this.find('a'),
				$list = $this.parents('.w-blog').find('.w-blog-list'),
				template_vars = data.template_vars || {};
			if (data.ajax_url === undefined) return;
			$this.removeAttr('onclick');
			$btn.click(function(e){
				e.preventDefault();
				$this.addClass('loading');
				if (template_vars.query_args === undefined || template_vars.query_args instanceof Array) template_vars.query_args = {};
				if (template_vars.query_args.paged === undefined) template_vars.query_args.paged = 1;
				template_vars.query_args.paged = template_vars.query_args.paged + 1;
				data.template_vars = JSON.stringify(template_vars);
				$.ajax({
					type: 'post',
					url: data.ajax_url,
					data: data,
					success: function(html){
						var $result = $(html),
							$container = $result.find('.w-blog-list'),
							$items = $container.children(),
							isotope = $list.data('isotope');
						$container.imagesLoaded(function(){
							$items.appendTo($list);
							$container.remove();
							var $sliders = $items.find('.w-slider');
							if (isotope) {
								isotope.appended($items);
								$items.revealGridMD();
							}
							$sliders.each(function(){
								$(this).wSlider().find('.royalSlider').data('royalSlider').ev.on('rsAfterInit', function() {
									if (isotope) {
										$list.isotope('layout');
									}
								});
							});
							$this.removeClass('loading');
						});
						if (template_vars.query_args.paged >= data.max_num_pages){
							$this.remove();
						}
					},
					error: function(){
						$this.removeClass('loading');
					}
				});
			});
		});
	};

	$(function(){
		$('.w-blog .g-loadmore').gLoadmore();
	});
})(jQuery);


/**
 * UpSolution Widget: w-tabs
 *
 * @requires $us.canvas
 */
!function( $ ){
	"use strict";

	$us.WTabs = function(container, options){
		this.init(container, options);
	};

	$us.WTabs.prototype = {

		init: function(container, options){
			// Setting options
			var defaults = {
				duration: 300,
				easing: 'cubic-bezier(.78,.13,.15,.86)'
			};
			this.options = $.extend({}, defaults, options);
			this.isRtl = $('.l-body').hasClass('rtl');

			// Commonly used dom elements
			this.$container = $(container);
			this.$tabsList = this.$container.find('.w-tabs-list:first');
			this.$tabs = this.$tabsList.find('.w-tabs-item');
			this.$sectionsWrapper = this.$container.find('.w-tabs-sections:first');
			this.$sectionsHelper = this.$sectionsWrapper.children();
			this.$sections = this.$sectionsHelper.children();
			this.$headers = this.$sections.children('.w-tabs-section-header');
			this.$contents = this.$sections.children('.w-tabs-section-content');

			// Class variables
			this.width = 0;
			this.tabWidths = [];
			this.isTogglable = (this.$container.usMod('type') == 'togglable');
			// Basic layout
			this.basicLayout = this.$container.hasClass('accordion') ? 'accordion' : (this.$container.usMod('layout') || 'default');
			// Current active layout (may be switched to 'accordion')
			this.curLayout = this.basicLayout;
			this.responsive = $us.canvas.options.responsive;
			// Array of active tabs indexes
			this.active = [];
			this.count = this.$tabs.length;
			// Container width at which we should switch to accordion layout
			this.minWidth = 0;

			if (this.count == 0) return;

			// Preparing arrays of jQuery objects for easier manipulating in future
			this.tabs = $.map(this.$tabs.toArray(), $);
			this.sections = $.map(this.$sections.toArray(), $);
			this.headers = $.map(this.$headers.toArray(), $);
			this.contents = $.map(this.$contents.toArray(), $);

			$.each(this.tabs, function(index){
				if (this.tabs[index].hasClass('active')){
					this.active.push(index);
				}
				this.tabs[index].add(this.headers[index]).on('click', function(){
					// Toggling accordion sections
					if (this.curLayout == 'accordion' && this.isTogglable){
						// Cannot toggle the only active item
						this.toggleSection(index);
					}
					// Setting tabs active item
					else if (index != this.active[0]){
						this.openSection(index);
					}
				}.usBind(this));
			}.usBind(this));

			// Boundable events
			this._events = {
				resize: this.resize.usBind(this),
				contentChanged: function(){
					$us.canvas.$container.trigger('contentChange');
				}
			};

			// Starting everything
			this.switchLayout(this.curLayout);
			if (this.curLayout != 'accordion' || ! this.isTogglable){
				this.openSection(this.active[0]);
			}

			setTimeout(this._events.resize, 50);
			$us.canvas.$window.on('resize load', this._events.resize);
		},

		switchLayout: function(to){
			this.cleanUpLayout(this.curLayout);
			this.prepareLayout(to);
			this.curLayout = to;
		},

		/**
		 * Clean up layout's special inline styles and/or dom elements
		 * @param from
		 */
		cleanUpLayout: function(from){
			if (from == 'default' || from == 'timeline'){
				this.$sectionsWrapper.clearPreviousTransitions().resetInlineCSS('width', 'height');
				this.$sectionsHelper.clearPreviousTransitions().resetInlineCSS('position', 'width', 'left');
				this.$sections.resetInlineCSS('width');
			}
			else if (from == 'accordion'){
				this.$container.removeClass('accordion');
				this.$contents.resetInlineCSS('height', 'padding-top', 'padding-bottom', 'display', 'opacity');
			}
			else if (from == 'ver'){
				this.$contents.resetInlineCSS('height', 'padding-top', 'padding-bottom', 'display', 'opacity');
			}
		},

		/**
		 * Apply layout's special inline styles and/or dom elements
		 * @param to
		 */
		prepareLayout: function(to){
			if (to == 'default' || to == 'timeline'){
				this.$sectionsHelper.css('position', 'absolute');
			}
			else if (to == 'accordion'){
				this.$container.addClass('accordion');
				this.$contents.hide();
				for (var i = 0; i < this.active.length; i++){
					if (this.contents[this.active[i]] !== undefined){
						this.contents[this.active[i]].show();
					}
				}
			}
			else if (to == 'ver'){
				this.$contents.hide();
				this.contents[this.active[0]].show();
			}
		},

		/**
		 * Measure needed sizes and store them to this.tabWidths variable
		 *
		 * TODO Count minWidth here as well
		 */
		measure: function(){
			if (this.basicLayout == 'ver'){
				// Measuring minimum tabs width
				this.$tabsList.css('width', 0);
				var minTabWidth = this.$tabsList.outerWidth(true);
				this.$tabsList.css('width', '');
				// Measuring the mininum content width
				this.$container.addClass('measure');
				var minContentWidth = this.$sectionsWrapper.outerWidth(true);
				this.$container.removeClass('measure');
				// Measuring minimum tabs width for percent-based sizes
				var navWidth = this.$container.usMod('navwidth');
				if (navWidth != 'auto'){
					// Percent-based measure
					minTabWidth = Math.max(minTabWidth, minContentWidth * parseInt(navWidth) / (100 - parseInt(navWidth)));
				}
				this.minWidth = Math.max(480, minContentWidth + minTabWidth + 1);
			}else{
				this.tabWidths = [];
				// We hide active line temporarily to count tab sizes properly
				this.$container.addClass('measure');
				for (var index = 0; index < this.tabs.length; index++){
					this.tabWidths.push(this.tabs[index].outerWidth(true));
				}
				this.$container.removeClass('measure');
				if (this.basicLayout == 'default' || this.basicLayout == 'timeline'){
					// Array sum
					this.minWidth = this.tabWidths.reduce(function(pv, cv){ return pv + cv; }, 0);
				}
			}
		},

		/**
		 * Open tab section
		 *
		 * @param index int
		 */
		openSection: function(index){
			if (this.sections[index] === undefined) return;
			if (this.curLayout == 'default' || this.curLayout == 'timeline'){
				var height = this.sections[index].height();
				this.$sectionsHelper.performCSSTransition({
					left: -this.width * (this.isRtl ? (this.count - index - 1 ) : index)
				}, this.options.duration, null, this.options.easing);
				this.$sectionsWrapper.performCSSTransition({
					height: height
				}, this.options.duration, this._events.contentChanged, this.options.easing);
			}
			else if (this.curLayout == 'accordion' || this.curLayout == 'ver'){
				if (this.contents[this.active[0]] !== undefined){
					this.contents[this.active[0]].css('display', 'block').slideUp(this.options.duration);
				}
				this.contents[index].css('display', 'none').slideDown(this.options.duration, this._events.contentChanged);
				// Scrolling to the opened section at small window dimensions
				if (this.curLayout == 'accordion' && $us.canvas.winWidth < 768){
					var newTop = this.headers[0].offset().top;
					for (var i = 0; i < index; i++){
						newTop += this.headers[i].outerHeight();
					}
					$us.scroll.scrollTo(newTop, true);
				}
			}
			this._events.contentChanged();
			this.$tabs.removeClass('active');
			this.tabs[index].addClass('active');
			this.$sections.removeClass('active');
			this.sections[index].addClass('active');
			this.active[0] = index;
		},

		/**
		 * Toggle some togglable accordion section
		 *
		 * @param index
		 */
		toggleSection: function(index){
			// (!) Can only be used within accordion state
			var indexPos = $.inArray(index, this.active);
			if (indexPos != -1){
				this.contents[index].css('display', 'block').slideUp(this.options.duration, this._events.contentChanged);
				this.tabs[index].removeClass('active');
				this.sections[index].removeClass('active');
				this.active.splice(indexPos, 1);
			}
			else {
				this.contents[index].css('display', 'none').slideDown(this.options.duration, this._events.contentChanged);
				this.tabs[index].addClass('active');
				this.sections[index].addClass('active');
				this.active.push(index);
			}
		},

		/**
		 * Resize-driven logics
		 */
		resize: function(){
			this.width = this.$container.width();
			this.$tabsList.removeClass('hidden');

			// Basic layout may be overriden
			if (this.responsive){
				if (this.basicLayout == 'ver' && this.curLayout != 'ver') this.switchLayout('ver');
				if (this.curLayout != 'accordion') this.measure();
				var nextLayout = (this.width < this.minWidth) ? 'accordion' : this.basicLayout;
				if (nextLayout !== this.curLayout) this.switchLayout(nextLayout);
			}

			// Fixing tabs display
			if (this.curLayout == 'default' || this.curLayout == 'timeline'){
				this.$sectionsWrapper.css('width', this.width);
				this.$sectionsHelper.css('width', this.count * this.width);
				this.$sections.css('width', this.width);
				if (this.contents[this.active[0]] !== undefined){
					this.$sectionsHelper.css('left', -this.width * (this.isRtl ? (this.count - this.active[0] - 1) : this.active[0]));
					var height = this.sections[this.active[0]].height();
					this.$sectionsWrapper.css('height', height);
				}
			}else if (this.curLayout == 'ver'){
				var sectionsWrapperWidth = this.$sectionsWrapper.width();
			}
			this._events.contentChanged()
		}

	};

	$.fn.wTabs = function(options){
		return this.each(function(){
			$(this).data('wTabs', new $us.WTabs(this, options));
		});
	};

}(jQuery);


/**
 * UpSolution Shortcode: us_logos
 */
jQuery(function($){
	$(".w-logos.type_carousel .w-logos-list").each(function() {
		var $list = $(this),
			items = parseInt($list.data('items'));
		$list.owlCarousel({
			items: items,
			center: (items == 1),
			loop: true,
			rtl: $('.l-body').hasClass('rtl'),
			nav: $list.data('nav'),
			autoplay: $list.data('autoplay'),
			autoplayTimeout: $list.data('timeout'),
			autoplayHoverPause: true,
			responsive: {
				0: {items: 1, center: true},
				480: {items: Math.min(items, 2)},
				768: {items: Math.min(items, 3)},
				900: {items: Math.min(items, 4)},
				1200: {items: items}
			}
		});
	});
});


/**
 * UpSolution Shortcode: us_feedback
 */
jQuery(function($){

	$('.w-form.for_cform').each(function(){
		var $container = $(this),
			$form = $container.find('form:first'),
			$submitBtn = $form.find('.w-btn'),
			$successField = $form.find('.w-form-field-success'),
			$errorField = $form.find('.w-form-field-error'),
			options = $container.find('.w-form-json')[0].onclick();

		$form.submit(function(event){
			event.preventDefault();

			// Prevent double-sending
			if ($submitBtn.hasClass('loading')) return;

			$successField.html('');
			$errorField.html('');
			// Validation
			var errors = 0;
			$form.find('[data-required="1"]').each(function(){
				var $input = $(this),
					isEmpty = ($input.val() == ''),
					$row = $input.closest('.w-form-row'),
					errorText = options.errors[$input.attr('name')] || '';
				$row.toggleClass('check_wrong', isEmpty);
				$row.find('.w-form-state').html(isEmpty ? errorText : '');
				if (isEmpty){
					errors++;
				}
			});

			if (errors != 0) return;

			$submitBtn.addClass('loading');
			$.ajax({
				type: 'POST',
				url: options.ajaxurl,
				dataType: 'json',
				data: $form.serialize(),
				success: function(result){
					if (result.success){
						$successField.html(result.data);
						$form.find('.w-form-row.check_wrong').removeClass('check_wrong');
						$form.find('.w-form-state').html('');
						$form.find('input[type="text"], input[type="email"], textarea').val('').removeClass('not-empty');
					} else {
						if (result.data && typeof result.data == 'object'){
							for (var fieldName in result.data){
								if ( ! result.data.hasOwnProperty(fieldName)) continue;
								var $input = $form.find('[name="'+fieldName+'"]'),
									errorText = result.data[fieldName];
								$input.closest('.w-form-row').addClass('check_wrong')
									.find('.w-form-state').html(errorText);
							}
						}else{
							$errorField.html(result.data);
						}
					}
				},
				complete: function(){
					$submitBtn.removeClass('loading');
				}
			});
		});

	});
});


/**
 * UpSolution Shortcode: us_counter
 */
jQuery(function($){
	$('.w-counter').each(function(index, elm){
		var $container = $(this),
			$number = $container.find('.w-counter-number'),
			initial = ($container.data('initial') || '0')+'',
			target = ($container.data('target') || '10')+'',
			prefix = $container.data('prefix') || '',
			suffix = $container.data('suffix') || '',
			// 0 for integers, 1+ for floats (number of digits after the decimal)
			precision = 0;
		if (target.indexOf('.') != -1){
			precision = target.length - 1 - target.indexOf('.');
		}
		initial = window[precision?'parseFloat':'parseInt'](initial, 10);
		target = window[precision?'parseFloat':'parseInt'](target, 10);

		$number.html(prefix+initial.toFixed(precision)+suffix);

		var startAnimation = function(){
			var	current = initial,
				step = 25,
				stepValue = (target - initial) / 25,
				interval = setInterval(function(){
					current += stepValue;
					step--;
					$number.html(prefix+current.toFixed(precision)+suffix);
					if (step <= 0) {
						$number.html(prefix+target.toFixed(precision)+suffix);
						window.clearInterval(interval);
					}
				}, 40);
		};

		if ($.fn.waypoint){
			new Waypoint({
				element: this,
				handler: function(){
					startAnimation();
					this.destroy();
				},
				offset: '85%'
			});
		}else{
			startAnimation();
		}
	});
});


/**
 * UpSolution Shortcode: us_gallery
 */
jQuery(function($){
	if ($.fn.magnificPopup){
		$('.w-gallery.link_media .w-gallery-list').each(function(){
			$(this).magnificPopup({
				type: 'image',
				delegate: 'a.w-gallery-item',
				gallery: {
					enabled: true,
					navigateByImgClick: true,
					preload: [0, 1]
				},
				removalDelay: 300,
				mainClass: 'mfp-fade',
				fixedContentPos: false
			});
		});
	}
	if ($.fn.isotope){
		// Applying isotope to gallery
		$('.w-gallery.layout_masonry .w-gallery-list').each(function(index, container){
			var $container = $(container);
			$container.imagesLoaded(function(){
				$container.isotope({
					layoutMode: 'masonry',
					isOriginLeft: ! $('body').hasClass('rtl')
				});
			});
		});
	}
});


/**
 * UpSolution Shortcode: us_slider
 */
(function($){
	$.fn.wSlider = function(){
		return this.each(function(){
			var $this = $(this),
				$slider = $this.find('.royalSlider'),
				$options = $this.find('.w-slider-json'),
				options = $options[0].onclick() || {};
			$options.remove();
			if ( ! $.fn.royalSlider) {
				return;
			}
			// Always apply certain fit options for blog listing slider
			if ($this.parent().hasClass('w-blog-post-preview')) {
				options['imageScaleMode'] = 'fit';
			}
			$slider.royalSlider(options);
			var slider = $slider.data('royalSlider');
			if (options.fullscreen && options.fullscreen.enabled) {
				// Moving royal slider to the very end of body element to allow a proper fullscreen
				var rsEnterFullscreen = function(){
					$slider.appendTo($('body'));
					slider.ev.on('rsExitFullscreen', rsExitFullscreen);
				};
				slider.ev.on('rsEnterFullscreen', rsEnterFullscreen);
				var rsExitFullscreen = function(){
					$slider.prependTo($this);
					slider.ev.off('rsExitFullscreen', rsExitFullscreen);
					slider.exitFullscreen();
				};
			}
			$us.canvas.$container.on('contentChange', function(){
				slider.updateSliderSize();
			});
		});
	};
	$(function(){
		jQuery('.w-slider').wSlider();
	});
})(jQuery);


/**
 * UpSolution Widget: w-portfolio
 */
jQuery(function($){

	// Non-interactive portfolio
	if ( ! $.fn.isotope) return;

	var $containers = $('.w-portfolio');
	$containers.each(function(){
		var $container = $(this);
		if ( ! $container.hasClass('position_isotope')) return;
		var $filters = $container.find('.g-filters-item'),
			$list = $container.find('.w-portfolio-list'),
			$items = $container.find('.w-portfolio-item'),
			$pagination = $container.find('.g-pagination'),
			$loadmore = $container.find('.g-loadmore'),
			paginationType = $pagination.length ? 'regular' : ($loadmore.length ? 'ajax' : 'none'),
			items = {},
			curCategory = '*',
			perpage,
			curPage,
			ajaxUrl,
			order,
			sizes,
			templateVars,
			loading = false;
		$items.each(function(){
			var $item = $(this),
				itemID = parseInt($item.data('id'));
			items[itemID] = $item;
		});
		var setState = function(page, category){
			if (loading) return;
			category = category || curCategory;
			var start = (paginationType == 'ajax') ? 0 : ((page-1)*perpage),
				length = page*perpage,
				showIds = (order[category]||[]).slice(start, length),
				loadIds = [],
				$newItems = [];
			$.each(showIds, function(i, id){
				// Determining which items we need to load via ajax and creating temporary stubs for them
				if (items[id] !== undefined) return;
				var itemSize = (sizes[id] || '1x1'),
					itemHtml = '<div class="w-portfolio-item size_'+itemSize+' loading" data-id="'+id+'">' +
					'<div class="w-portfolio-item-image"><div class="g-preloader style_2"></div></div></div>';
				items[id] = $(itemHtml).appendTo($list);
				$newItems.push(items[id][0]);
				loadIds.push(showIds[i]);
			});
			if (loadIds.length > 0){
				var $insertedItems = $();
				$.ajax({
					type: 'post',
					url: ajaxUrl,
					data: {
						action: 'us_ajax_portfolio',
						ids: loadIds.join(','),
						template_vars: templateVars
					},
					success: function(html){
						var $ajaxContainer = $('<div>', {html: html}),
							$ajaxItems = $ajaxContainer.children();
						$ajaxItems.each(function(){
							var $ajaxItem = $(this),
								itemID = parseInt($ajaxItem.data('id'));
							$ajaxItem.imagesLoaded(function(){
								items[itemID].attr('class', $ajaxItem.attr('class')).usMod('animate', false);
								items[itemID].attr('style', $ajaxItem.attr('style')).css('opacity', 0);
								items[itemID].html($ajaxItem.html());
								items[itemID].find('a[ref=magnificPopup]').magnificPopup({
									type: 'image',
									fixedContentPos: false
								});
								$insertedItems = $insertedItems.add(items[itemID]);
								if ($insertedItems.length >= loadIds.length){
									$ajaxContainer.remove();
									$insertedItems.revealGridMD();
								}
							});
						});
					}
				});
			}
			$list.isotope({filter: function(){
				return (showIds.indexOf(parseInt(this.getAttribute('data-id'))) != -1);
			}});
			if (loadIds.length > 0){
				$list.isotope('insert', $newItems);
			}
			curPage = page;
			curCategory = category;
			renderPagination();
		};
		var isotopeOptions = {
			itemSelector: '.w-portfolio-item',
			layoutMode: 'masonry',
			//percentPosition: true,
			masonry: {
				columnWidth: '.size_1x1'
			},
			isOriginLeft: ! $('.l-body').hasClass('rtl')
		};
		if (paginationType != 'none'){
			var $jsonContainer = $container.find('.w-portfolio-json');
			if ($jsonContainer.length == 0) return;
			var data = $jsonContainer.get(0).onclick() || {};
			ajaxUrl = data.ajax_url || '';
			templateVars = JSON.stringify(data.template_vars || {});
			perpage = data.perpage || $items.length;
			order = data.order || {};
			sizes = data.sizes || {};
			curPage = data.page || 1;
			$jsonContainer.remove();
			isotopeOptions.sortBy = 'number';
			isotopeOptions.getSortData = {
				number: function(elm){
					return order['*'].indexOf(parseInt(elm.getAttribute('data-id')));
				}
			};
		}else{
			// Overloading setState by a simple categories filter
			setState = function(page, category){
				$list.isotope({filter: (category == '*') ? '*' : ('.' + category)});
				curCategory = category;
			};
		}
		if (paginationType == 'ajax'){
			var renderPagination = function(){
				var maxPage = Math.ceil(order[curCategory].length / perpage);
				$loadmore[(curPage < maxPage)?'slideDownCSS':'slideUpCSS']();
			};
			$loadmore.on('click', function(){
				var maxPage = Math.ceil(order[curCategory].length / perpage);
				if (curPage < maxPage){
					setState(curPage + 1);
				}
			});
		}
		else if (paginationType == 'regular'){
			var pcre = new RegExp('/page/([0-9]+)/$'),
				loc = location.href.replace(pcre, '/'),
				$navLinks = $container.find('.nav-links'),
				pageUrl = function(page){ return (page == 1) ? loc : (loc + 'page/' + page + '/'); };
			var renderPagination = function(){
				var maxPage = Math.ceil(order[curCategory].length / perpage),
					html = '';
				if (maxPage > 1){
					if (curPage > 1){
						html += '<a href="'+pageUrl(curPage-1)+'" class="prev page-numbers">&lt;</a>';
					}else{
						html += '<span class="prev page-numbers">&lt;</span>';
					}
					for (var i = 1; i <= maxPage; i++){
						if (i != curPage){
							html += '<a href="'+pageUrl(i)+'" class="page-numbers">'+i+'</a>';;
						}else{
							html += '<span class="page-numbers current"><span>'+i+'</span></span>';
						}
					}
					if (curPage < maxPage){
						html += '<a href="'+pageUrl(curPage+1)+'" class="next page-numbers">&gt;</a>';
					}else{
						html += '<span class="next page-numbers">&gt;</span>';
					}
				}
				$navLinks.html(html);
			};
			$navLinks.on('click', 'a', function(e){
				e.preventDefault();
				var arr,
					pageNum = (arr = pcre.exec(this.href)) ? parseInt(arr[1]) : 1;
				setState(pageNum);
			});
			renderPagination(curPage);
		}
		$filters.click(function(){
			var $filter = $(this),
				category = $filter.data('category');
			if (category != curCategory){
				setState((paginationType == 'regular') ? 1 : curPage, category);
				$filters.removeClass('active');
				$filter.addClass('active');
			}
		});
		// Applying isotope
		loading = true;
		$list.imagesLoaded(function(){
			$list.isotope(isotopeOptions);
			loading = false;
			$us.canvas.$container.on('contentChange', function(){
				$list.isotope('layout');
			});
			$(window).on('resize', function(){
				$list.isotope('layout');
			});
		});
	});
});


/**
 * UpSolution Widget: w-cart
 *
 * @requires $us.canvas
 * @requires $us.nav
 */
jQuery(function($){
	var $cart = $('.w-cart');
	if ($cart.length == 0) return;

	var $notification = $cart.find('.w-cart-notification'),
		$productName = $notification.find('.product-name'),
		$cartLink = $cart.find('.w-cart-link'),
		$dropdown = $cart.find('.w-cart-dropdown'),
		$quantity = $cart.find('.w-cart-quantity'),
		productName = $productName.text(),
		animationType = (window.$us !== undefined && window.$us.nav !== undefined) ? $us.nav.animationType : 'opacity',
		showFn = 'fadeInCSS',
		hideFn = 'fadeOutCSS',
		opened = false;

	if (animationType == 'height'){
		showFn = 'slideDownCSS';
		hideFn = 'slideUpCSS';
	}
	else if (animationType == 'mdesign'){
		showFn = 'showMD';
		hideFn = 'hideMD';
	}

	$notification.on('click', function(){
		$notification[hideFn]();
	});

	jQuery('body').bind('added_to_cart', function(event, fragments, cart_hash, $button){
		if (event === undefined) return;

		$quantity.html(parseInt($quantity.html(), 10) + 1);

		$cart.addClass('has_items');

		productName = $button.closest('.product').find('.product-meta h3:first').text();
		$productName.html(productName);

		$notification[showFn](undefined, function(){
			var newTimerId = setTimeout(function(){
				$notification[hideFn]();
			}, 3000);
			$notification.data('animation-timers', $notification.data('animation-timers') + ',' + newTimerId);
		});
	});

	if ($.isMobile){
		var outsideClickEvent = function(e){
			if (jQuery.contains($cart[0], e.target)) return;
			$dropdown[hideFn]();
			$us.canvas.$body.off('touchstart', outsideClickEvent);
			opened = false;
		};
		$cartLink.on('click', function(e){
			if ( ! opened){
				e.preventDefault();
				$dropdown[showFn]();
				$us.canvas.$body.on('touchstart', outsideClickEvent);
			}else{
				$dropdown[hideFn]();
				$us.canvas.$body.off('touchstart', outsideClickEvent);
			}
			opened = ! opened;
		});
	}else{
		var hideTimer = null;
		$cartLink.on('hover', function(){
			if (opened) return;
			$dropdown[showFn]();
			opened = true;
		});
		$cart.hover(function(){
			clearTimeout(hideTimer);
		}, function(){
			clearTimeout(hideTimer);
			hideTimer = setTimeout(function(){
				if ( ! opened) return;
				$dropdown[hideFn]();
				opened = false;
			}, 250);
		});
	}
});


/**
 * UpSolution Widget: w-maps
 *
 * Used for [us_gmaps] shortcode
 */
!function($){
	"use strict";

	$us.WMaps = function(container, options){

		this.$container = $(container);

		var $jsonContainer = this.$container.find('.w-map-json'),
			jsonOptions = $jsonContainer[0].onclick() || {};
		$jsonContainer.remove();

		// Setting options
		var defaults = {};
		this.options = $.extend({}, defaults, jsonOptions, options);

		this._events = {
			redraw: this.redraw.usBind(this)
		};

		// Initializing the map itself
		this.$container.gMap(this.options);
		this.map = this.$container.data('gMap.reference');

		$us.canvas.$container.on('contentChange', this._events.redraw);

		// In case some toggler was opened before the actual page load
		$us.canvas.$window.load(this._events.redraw);
	};

	$us.WMaps.prototype = {
		/**
		 * Fixing hidden and other breaking-cases maps
		 */
		redraw: function(){
			if (this.$container.is(':hidden')) return;
			var center = this.map.getCenter();
			google.maps.event.trigger(this.$container[0], 'resize');
			if (this.$container.data('gMap.infoWindows').length) {
				this.$container.data('gMap.infoWindows')[0].open(this.map, this.$container.data('gMap.overlays')[0]);
			}
			this.map.setCenter(center);
		}
	};

	$.fn.wMaps = function(options){
		return this.each(function(){
			$(this).data('wMaps', new $us.WMaps(this, options));
		});
	};

	$(function(){
		$('.w-map').wMaps();
	});
}(jQuery);
