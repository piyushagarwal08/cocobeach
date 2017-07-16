(function ($) {
	"use strict";
	var checkFilter = true,
		isRTL = $('body').hasClass('rtl');
	var ERE_property_featured = {
		vars: {
			filter_id: '',
			property_items: []
		},
		init: function () {
			this.filterCarousel();
			this.resize();
			ERE.select_term();
			this.cityFilterScrollBar();
			var $propertySyncWrap = $('.property-sync-content-wrap');
			$propertySyncWrap.each(function () {
				ERE_property_featured.syncPropertyCarousel($(this));
			});
			this.calcPaddingTopBottom();
			setTimeout( this.calcPaddingTopBottom,300 );
			setTimeout( this.calcPaddingTopBottom,1000 );
		},
		filterCarousel: function () {
			var property_owl_filter = $('[data-filter-type="carousel"]');
			property_owl_filter.each(function () {
				var objectClick = $('a', $(this));
				ERE_property_featured.executeFilter(objectClick);
			});
		},
		executeFilter: function (objectClick) {
			$(objectClick).button({
				loadingText: '<span class="fa fa-spinner fa-spin"></span>'
			});
			$(objectClick).parent().next().children('select').children().button({
				loadingText: '<span class="fa fa-spinner fa-spin"> Loading ...</span>'
			});
			objectClick.on('click', function (event) {
				event.preventDefault();
				var thisObject = $(this),
					filterId = thisObject.parent().attr('data-filter_id'),
					$property_content = $('.property-content[data-filter_id="'+filterId+'"]'),
					ere_property = $property_content.parent();
				if (thisObject.hasClass('active-filter')) {
					thisObject.css('cursor', 'not-allowed');
					return false;
				} else {
					thisObject.parent().children('a').css('cursor', 'wait');
					if (checkFilter) {
						checkFilter = false;
						var dataFilter = thisObject.data('filter'),
							select_filter = thisObject.parent().next().children('select');
						ERE_property_featured.vars.filter_id = thisObject.parent().data('filter_id');
						thisObject.parent().find('.active-filter').removeClass('active-filter');
						thisObject.addClass('active-filter');
						ere_property.css('height',ere_property.outerHeight());
						if (typeof ERE_property_featured.vars.property_items[dataFilter + '-' + ERE_property_featured.vars.filter_id] == 'undefined') {
							thisObject.css('width', thisObject.outerWidth());
							select_filter.children().button('loading');
							var $this = thisObject.button('loading'),
								$ajax_url = objectClick.closest('.filter-wrap').data('admin-url');
							$.ajax({
								url: $ajax_url,
								data: {
									action: 'ere_property_featured_fillter_city_ajax',
									layout_style: thisObject.parent().data('layout-style'),
									property_city: thisObject.data('filter'),
									item_amount: thisObject.parent().data('item-amount'),
									color_scheme: thisObject.parent().data('color_scheme')
								},
								success: function (html) {
									var $newElems = $('.property-item', html);
									ERE_property_featured.vars.property_items[dataFilter + '-' + ERE_property_featured.vars.filter_id] = html;

									$property_content.css('opacity', 0);
									$property_content.trigger('destroy.owl.carousel');
									$property_content.html($newElems);
									$property_content.css('opacity', 1);
									$property_content.imagesLoaded(function () {
										ERE.set_item_effect($newElems, 'hide');
										ERE_Carousel.owlCarousel();
										$newElems = $('.property-item', $property_content);
										ERE.set_item_effect($newElems, 'show');
										setTimeout(function(){
											ere_property.css('height','auto');
										}, 200);
									});
									setTimeout(function () {
										thisObject.css('width', 'auto');
									},100);
									checkFilter = true;
									$this.button('reset');
									select_filter.children().button('reset');
									select_filter.removeAttr('disabled');
									select_filter.children('option').removeAttr('selected');
									select_filter.children('option[value="' + dataFilter + '"]').attr('selected', 'selected');
									
									thisObject.parent().children('a').css('cursor', 'pointer');
									thisObject.parent().children('.active-filter').css('cursor', 'not-allowed');
								},
								error: function (xhr) {
									var err = eval("(" + xhr.responseText + ")");
									console.log(err.Message);
									$this.button('reset');
									checkFilter = true;
								}
							});
						} else {
							var old_data = ERE_property_featured.vars.property_items[dataFilter + '-' + ERE_property_featured.vars.filter_id],
								$newElems = $('.property-item', old_data);
							$property_content.css('opacity', 0);
							$property_content.trigger('destroy.owl.carousel');
							$property_content.html($newElems);
							ERE.set_item_effect($newElems, 'hide');
							$property_content.css('opacity', 1);
							ERE_Carousel.owlCarousel();
							$property_content.imagesLoaded(function () {
								$newElems = $('.property-item', $property_content);
								ERE.set_item_effect($newElems, 'show');
								setTimeout(function(){
									ere_property.css('height','auto');
								}, 200);
							});
							checkFilter = true;
							select_filter.removeAttr('disabled');
							select_filter.children('option').removeAttr('selected');
							select_filter.children('option[value="' + dataFilter + '"]').attr('selected', 'selected');
							thisObject.parent().children('a').css('cursor', 'pointer');
							thisObject.parent().children('.active-filter').css('cursor', 'not-allowed');
						}
					}
				}
			});
		},
		resize: function () {
			$(window).resize(function () {
				ERE_property_featured.executeResize();
			});
			$(window).on('orientationchange', function () {
				ERE_property_featured.executeResize();
			});
		},
		executeResize: function () {
			$('.property-content.owl-carousel').each(function () {
				var container = $(this);
				setTimeout(function () {
					var $items = $('.property-item', container);
					ERE.set_item_effect($items, 'show');
				}, 500);
			});
			ERE_property_featured.cityFilterScrollBar();
			var $propertySyncWrap = $('.property-sync-content-wrap');
			$propertySyncWrap.each(function () {
				ERE_property_featured.syncPropertyCarousel($(this));
			});
			ERE_property_featured.calcPaddingTopBottom();
		},
		cityFilterScrollBar: function () {
			$('.property-filter-content', '.property-cities-filter').each(function () {
				var $this = $(this);
				if ($this.outerHeight() > 530 ) {
					$this.css('height', '530px');
					$this.css('overflow-y', 'auto');
					if ($.isFunction($.fn.perfectScrollbar)) {
						$this.perfectScrollbar({
							wheelSpeed: 0.5,
							suppressScrollX: true
						});
					}
				} else {
					$this.css('height', 'auto');
				}
			});
		},
		syncPropertyCarousel: function($propertySyncWrap){
			var $sliderMain = $propertySyncWrap.find('.property-content-carousel'),
				$sliderThumb = $propertySyncWrap.find('.property-image-carousel');
			$sliderMain.owlCarousel({
				items: 1,
				nav: false,
				dots:false,
				loop: false,
				smartSpeed: 500,
				rtl: isRTL
			}).on('changed.owl.carousel', syncPosition);

			$sliderThumb.on('initialized.owl.carousel', function () {
				$sliderThumb.find(".owl-item").eq(0).addClass("current");
			}).owlCarousel({
				items : 1,
				nav:true,
				navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
				dots: false,
				rtl: isRTL,
				margin: 0
			}).on('changed.owl.carousel', syncPosition2);

			function syncPosition(el){
				//if you set loop to false, you have to restore this next line
				var current = el.item.index;

				$sliderThumb
					.find(".owl-item")
					.removeClass("current")
					.eq(current)
					.addClass("current");
				var onscreen = $sliderThumb.find('.owl-item.active').length - 1;
				var start = $sliderThumb.find('.owl-item.active').first().index();
				var end = $sliderThumb.find('.owl-item.active').last().index();

				if (current > end) {
					$sliderThumb.data('owl.carousel').to(current, 500, true);
				}
				if (current < start) {
					$sliderThumb.data('owl.carousel').to(current - onscreen, 500, true);
				}
			}

			function syncPosition2(el) {
				var number = el.item.index;
				$sliderMain.data('owl.carousel').to(number, 500, true);
			}

			$sliderThumb.on("click", ".owl-item", function(e){
				e.preventDefault();
				if ($(this).hasClass('current')) return;
				var number = $(this).index();
				$sliderMain.data('owl.carousel').to(number, 500, true);
			});
		},
		calcPaddingTopBottom: function () {
			$('.main-content-inner', '.property-sync-carousel').each(function () {
				var $this = $(this),
					$thisHeight = $this.height(),
					$parentHeight = $this.parent().next('.property-image-content').children().outerHeight(),
					$differenceHeight = parseInt($parentHeight) - parseInt($thisHeight);
				if ($differenceHeight > 0 && window.matchMedia('(min-width: 1200px)').matches) {
					$this.css({
						'padding-top': $differenceHeight / 2 + 'px',
						'padding-bottom': $differenceHeight / 2 + 'px'
					});
				} else {
					$this.css({
						'padding-top': '',
						'padding-bottom': ''
					});
				}
			});
		}
	};
	$(document).ready(function () {
		ERE_property_featured.init();
	});
})(jQuery);