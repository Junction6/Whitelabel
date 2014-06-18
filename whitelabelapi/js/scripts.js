(function($) { 
	$(document).ready(function() {
		
		//for customer/agent view removing the content
		$('.linkPersonalizedSelected').bind('click',function(event){
			event.preventDefault();
			$.get(this.href,{},function(response){
				$form = $('#J6OrderForm_OrderForm');
				$hidden = jQuery('<input>').attr('type','hidden').attr('name', 'action_updateOrder').attr('value', '1');
				$hidden.prependTo($form);
				$form.submit();
			});

		});
		
		  $(".MyFave").click(function(event) {
//			alert("Child Click");
			event.stopPropagation();  
		  });
		
		$('.dropdown-toggle').dropdown()
		
		// fix .fixable(s) on scroll
		var $win = $(window)
			, $nav = $('.fixable')
			, navTop = $('.fixable').length && $('.fixable').offset().top
			, isFixed = 0
		
		// set header height so that when nav is fixed, header holds its height
		//$('header').height($('header').height())

		processScroll()

		$win.on('scroll', processScroll)

		function processScroll() {
			var i, scrollTop = $win.scrollTop()
			if (scrollTop >= navTop && !isFixed) {
				isFixed = 1
				$nav.addClass('fixed').parents('.fixable-container').addClass('fixed-container')
			} else if (scrollTop <= navTop && isFixed) {
				isFixed = 0
				$nav.removeClass('fixed').parents('.fixable-container').removeClass('fixed-container')
			}
		}
		
		// Switch system form
		$('#Form_SwitchSystemForm select').change(function() {
			$(this).parents('form').submit();
		});
		
		// Switch currency form
		$('#Form_SwitchCurrencyForm select').change(function() {
			$(this).parents('form').submit();
		});
		// Switch client form
		$('#Form_SwitchAgentForm select').change(function() {
			$(this).parents('form').submit();
		});
		// Switch payment location form
		$('#Form_SwitchPaymentLocationForm select').change(function() {
			$(this).parents('form').submit();
		});
      // Switch theme form
		$('#Form_SwitchJ6ThemeForm select').change(function() {
			$(this).parents('form').submit();
		});
		
		// Switch theme form
		$('#Form_SwitchTimezoneForm select').change(function() {
			$(this).parents('form').submit();
		});
		
		// Chosen dropdown fields
		/*
	
		$('.container-fluid select').not('[name=PerPage]').chosen({
			allow_single_deselect: true
		});
		*/

		var preventSearch = false;
		var minResults = 12;
		 // if on mobile make sure the search input does not get used
		 if($('html.mobile-template').length > 0) {
			 preventSearch = true;
			 minResults = 1000;
		 }

		if (jQuery().select2) {
		  $('select').not('[name=PerPage]').select2({
			  allowClear: true,
			  width: 'resolve',
			  minimumResultsForSearch: minResults,
			  preventSearchInput: preventSearch
		  });
		}
		
		// don't need a search for a couple of themes
//		$('#Form_SwitchJ6ThemeForm_J6Theme').select2({
//			minimumResultsForSearch: -1
//		});
		
		$('input.tagField').each(function() {
			$this = $(this);
			//separator = /[\s,]+/; //Split by [',' or ' '] //$this.attr('rel');
			separator = /[,]+/; //Split by [','] //$this.attr('rel');
			availableTags = ($this.attr('tags')) ? $.map($this.attr('tags').split(separator),$.trim) : '';
			options = {
                      tags: availableTags,
                      tokenSeparators: [separator],
                      width: 'resolve'
            };
            $this.select2(options);
		})
		
		// Nav dropdowns
		$('.dropdown-toggle').dropdown();
		
		$('.help-popover').popover();
		
		$('.date.year input').datepicker({format: 'dd/mm/yyyy', autoclose: true, startView: 2 });
		var date = new Date();
		date.setDate(date.getDate()-1);
		$('#Form_buyForm_BookingTravelDate').datepicker({format: 'dd/mm/yyyy', autoclose: true, startDate: date});
	
		
		
		$('.date input').datepicker({format: 'dd/mm/yyyy', autoclose: true});
		$('.mobile-template .date input').attr('readonly', true);
		
		$('.disabled').click(function(event) {
			event.preventDefault();
		});
		
		$('[data-auto-submit]').each(function(){
			var $this = jQuery(this),
				$form = $this.closest('form');
				$hidden = false;
				
//			if(!$this.is('input, select, radio, checkbox'))	{
//				$this = $this.find('input, select, radio, checkbox');
//			}
		
			$this.on('change','input, select, radio, checkbox', function(){
				if(data = $this.data('auto-submit')) {
					data = data.split('=');
					if(!$hidden) {
						$hidden = jQuery('<input>').attr('type','hidden')
					}
					$hidden.attr('name',data[0]);
					if(data[1]) {
						$hidden.attr('value', data[1])
					}
					$hidden.prependTo($form);
				}
				$form.submit();
			});
			
		})

		if ($('.gotocheckout').length > 0) {
			$('.gotocheckout').attr('href', $('.gotocheckout').attr('href')+"?BackURL="+$(location).attr('pathname'));
		
		}
		
	//--- HACK MOBILE
	jQuery('.nav-collapse').on('hover',function(){
		if(window.innerWidth <= 978){
			if(jQuery(this).height() > 0){
				jQuery(this).css('height', '400px');
			};
		}
	});
	
	
	
	});
	
	$(window).load(function() {
	});
})(jQuery);

