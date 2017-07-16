(function ($) {
	"use strict";
	$(document).ready(function () {
		function  ere_agency_paging() {
			var handle = true;
			$('.paging-navigation', '.agency-paging-wrap').each(function () {
				$('a', $(this)).button({
					loadingText: '<span class="fa fa-spinner fa-spin"></span>'
				});
				$('a', $(this)).off('click').on('click', function (event) {
					event.preventDefault();
					if(handle) {
						handle = false;
						var $this = $(this);
						$this.css({
							'padding': '0px',
							'height': $this.outerHeight(),
							'width': $this.outerWidth()
						});
						$this.button('loading');
						var href = $this.attr('href'),
							data_paged = ERE.get_page_number_from_href(href),
							data_contain = $this.closest('.agency-paging-wrap'),
							agency_content = $this.closest('.ere-agency').find('.agency-content');
						$.ajax({
							url: data_contain.data('admin-url'),
							data: {
								action: 'ere_agency_paging_ajax',
								items_amount: data_contain.data('items-amount'),
								paged: data_paged
							},
							success: function (html) {
								var $newElems = $('.agency-item', html),
									paging = $('.agency-paging-wrap', html);

								agency_content.css('opacity', 0);

								agency_content.html($newElems);
								ERE.set_item_effect($newElems, 'hide');
								var contentTop = agency_content.offset().top - 30;
								$('html,body').animate({scrollTop: +contentTop + 'px'}, 500);
								agency_content.css('opacity', 1);
								agency_content.imagesLoaded(function () {
									$newElems = $('.agency-item', agency_content);
									ERE.set_item_effect($newElems, 'show');
									agency_content.closest('.ere-agency').find('.agency-paging-wrap').html(paging.html());
									 ere_agency_paging();
									 ere_agency_paging_control();
								});
								$this.css({
									'height': 'auto',
									'width': 'auto',
									'padding': ''
								});
								$this.button('reset');
								handle = true;
							},
							error: function (xhr) {
								var err = eval("(" + xhr.responseText + ")");
								console.log(err.Message);
								$this.css({
									'height': 'auto',
									'width': 'auto',
									'padding': ''
								});
								$this.button('reset');
								handle = true;
							}
						});
					}
				})
			});
		}
		 ere_agency_paging();
		function  ere_agency_paging_control() {
			$('.paging-navigation', '.ere-agency').each(function () {
				var $this = $(this);
				if($this.find('a.next').length === 0) {
					$this.addClass('next-disable');
				} else {
					$this.removeClass('next-disable');
				}
			});
		}
	});
})(jQuery);