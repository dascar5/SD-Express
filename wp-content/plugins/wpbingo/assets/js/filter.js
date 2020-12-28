(function($) {
	$.fn.binFilterProduct = function(opts) {
		/* default configuration */	
		var config = $.extend({}, {
			widget_id : null,
			id_category:null,
			base_url: null,
			attribute:null,
			showcount:null,
			show_price:null,
			relation:null,
			show_only_sale:null,
			show_in_stock:null,
			show_brand:null,
			array_value_url : null	
		}, opts);
		$(document).ready(function(){
			_event_dropdown_filter();
			_event_filter_product();
			_event_click_pagination();
			_eventFilter(1,false);
			_event_clear_all();
			_event_click_sub_categories();
			$("li",".woocommerce-sort-count").click(function() {
				$("li",".woocommerce-sort-count").removeClass("active");
				$(this).addClass("active");
				_eventFilter();
				return false;
			});		

			$("li",".woocommerce-ordering").click(function() {
				_eventFilter();
				return false;
			});			

			var view_products = $(".display",".bwp-top-bar");
			$("a",view_products).click(function(e) {
				e.preventDefault();
				if(!$(this).hasClass("active")){
					$("a",view_products).removeClass('active');
					var this_class	= $("ul.products").data("col");
					$(this).addClass('active');								
					_eventFilter();
				}
				return false;
			});

			$(".back-to-shop").click(function() {
				var $text = $(this).text();
				$(".text-title-heading").text($text);
				$("li",".woocommerce-product-subcategories").removeClass("active");
				$("input",config.widget_id).attr('checked', false);
				$("#price-filter-min-text",config.widget_id).val($("#bwp_slider_price",config.widget_id).data('min'));
				$("#price-filter-max-text",config.widget_id).val($("#bwp_slider_price",config.widget_id).data('max'));
				_eventFilter(1,true,true,true);
				return false;
			});			
			
		});		
		
		function _event_click_sub_categories(){
			var $subcategories = $(".woocommerce-product-subcategories");
			$("li",$subcategories).click(function() {
				$("li",$subcategories).removeClass("active");
				$(this).addClass("active");
				var $text = $(".woocommerce-loop-category__title a",$(this)).text();
				$(".text-title-heading").text($text);
				$("input",config.widget_id).attr('checked', false);
				$("#price-filter-min-text",config.widget_id).val($("#bwp_slider_price",config.widget_id).data('min'));
				$("#price-filter-max-text",config.widget_id).val($("#bwp_slider_price",config.widget_id).data('max'));
				_eventFilter(1,true,true);
				return false;
			});
		}
		
		function _event_dropdown_filter(){
			var $form_filter = $("#bwp_form_filter_product",".filter_dropdown");
			$("h3",$form_filter).click(function() {
				if($(this).parent().hasClass("active")){
					$(this).parent().removeClass("active");
				}else{
					$(this).parent().addClass("active");
				}
			});			
		}
		
		function _event_clear_all(){
			$(".filter_clear_all",".woocommerce-filter-title").click(function(e) {
					$("input",config.widget_id).attr('checked', false);
					$("#price-filter-min-text",config.widget_id).val($("#bwp_slider_price",config.widget_id).data('min'));
					$("#price-filter-max-text",config.widget_id).val($("#bwp_slider_price",config.widget_id).data('max'));
					_eventFilter();
			});
		}
		
		function _event_click_pagination(){
			$( "nav.woocommerce-pagination a.page-numbers").click(function(e) {
					e.preventDefault();
					$('ul.products','.main-archive-product').scrollTop(300);
					var status_id = $(this).attr('href').split('=');
					var paged = (status_id[1]) ? status_id[1] : 1;
					_eventFilter(paged);
				return false;
			});		
		}
		
		function _event_filter_product(){
			min_price = $("#price-filter-min-text",config.widget_id).val();
			max_price =  $("#price-filter-max-text",config.widget_id).val();
			$("#bwp_slider_price").slider({
			range:true,
			min: $("#bwp_slider_price",config.widget_id).data('min'),
			max: $("#bwp_slider_price",config.widget_id).data('max'),		
			values: [min_price,max_price],
			slide : function( event, ui ) {
					$("#text-price-filter-min-text",config.widget_id).html(ui.values[0]);
					$("#text-price-filter-max-text",config.widget_id).html(ui.values[1]);				
					$("#price-filter-min-text",config.widget_id).val(ui.values[0]);
					$("#price-filter-max-text",config.widget_id).val(ui.values[1]);		
				},
			change: function( event, ui ) {
					_eventFilter();	
					return false;	
				}		   			
			});	

			$( "#button-price-slider",config.widget_id ).click(function(e) {
				e.preventDefault();
				_eventFilter();
				return false;
			});	
			
			$("input:checkbox",config.widget_id ).on('click', function(){
				_eventFilter();	
				return false;
			});			
			
			$("li",config.widget_id ).on('click', function(){
				if($("input",$(this)).is(':checked'))
					$("input",$(this)).attr("checked", false);
				else
					$("input",$(this)).attr("checked", true);	
				_eventFilter();	
				return false;
			});
			
			$("span",".woocommerce-filter-title" ).on('click', function(){
				if( $(this).hasClass("text-price") ){
					$("#price-filter-min-text",config.widget_id).val($("#bwp_slider_price",config.widget_id).data('min'));
					$("#price-filter-max-text",config.widget_id).val($("#bwp_slider_price",config.widget_id).data('max'));
				}else{
					var $name = $(this).data("name");
					var $value = $(this).data("value");
					$("input[value="+$value+"]","#"+$name+"").attr("checked", false);
				}
				_eventFilter();
				return false;
			});			
		}	
		
		function _eventFilter(paged=1,load=true,direction=false,back=false){
				if(load){
					$('html, body').animate({
						scrollTop: 300
					}, 300);		
					$('ul.products','.main-archive-product').addClass('active');
					$('ul.products','.main-archive-product').append( '<div class="loading"><div class="chasing-dots"><div></div><div></div><div></div><div></div></div></div>' );
				}
				var $filter = new Object();
				var $ajax_url = filter_ajax.ajaxurl;
				if($('.woocommerce-product-subcategories').length > 0){
					var $id_category		= $('.woocommerce-product-subcategories').find('li.active').data("id_category") ? $('.woocommerce-product-subcategories').find('li.active').data("id_category") : 0;
				}else{
					var $id_category		= config.id_category;
				}
				if(back){
					$id_category = 0;
				}
				$filter.orderby 		=	$('.woocommerce-ordering').find('li.active').data("value");
				$filter.product_count 	=	$('.woocommerce-sort-count').find('li.active').data("value");	
				$filter.views			= ($('.view-grid.active').length > 0) ?  'grid' : 'list';	
				$filter.data 			= 	$("#bwp_form_filter_product",config.widget_id).serializeArray();
				$filter.default_min_price 	= $("#bwp_slider_price",config.widget_id).data("min");
				$filter.default_max_price 	= $("#bwp_slider_price",config.widget_id).data("max");	
				$filter.min_price 			= $("#price-filter-min-text",config.widget_id).val();	
				$filter.max_price 			= $("#price-filter-max-text",config.widget_id).val();
				$filter.paged				= paged;	
				jQuery.ajax({
					type: "POST", 
					url: $ajax_url,
					dataType: 'json',
					data: {
						filter 			: $filter,
						action 			: "bwp_filter_products_callback",
						id_category 	: $id_category,
						base_url 		: config.base_url,
						attribute 		: config.attribute,
						relation 		: config.relation,
						show_price 		: config.show_price,
						showcount 		: config.showcount,
						show_only_sale 	: config.show_only_sale,
						show_in_stock 	: config.show_in_stock,
						show_brand 		: config.show_brand,
						show_bestseller : config.show_bestseller,
						show_banner 	: config.show_banner,
						array_value_url : config.array_value_url
					},
					success: function (result) {	
						if (result.products){
							$('.content-products-list','.main-archive-product').html(result.products);
							_event_click_quickview_button($ajax_url);
							_wpbingo_countdown_product( $('ul.products','.main-archive-product') );
							_wpbingo_click_atribute_image();
							_wpbingo_click_add_to_cart();
						}else{
							$('.content-products-list','.main-archive-product').html('');
						}
						
						if($id_category > 0){
							$(".back-to-shop").addClass("active");
						}else{
							$(".back-to-shop").removeClass("active");
						}
						
						if(direction){
							if ($(".page-title").data("bg_default")){
								if(result.result_background){
									$(".page-title").css("background-image", "url(" + result.result_background + ")");
								}else{
									$(".page-title").css("background-image", "url(" + $(".page-title").data("bg_default") + ")");
								}
							}
						}
						if($('.bestseller-product','.content-products-list').length > 0 ){
							_event_load_slick_carousel( $('.bestseller-product .slick-carousel','.content-products-list') );
							_event_check_nav_slick( $('.bestseller-product .slick-carousel','.content-products-list') );
							_wpbingo_countdown_product( $('.bestseller-product','.content-products-list') );
						}
						_event_after_sucsess_ajax(result,config);
						if(load){
							setTimeout(function() {
								$('.content-products-list','.main-archive-product').removeClass('active');
								$( '.loading','.main-archive-product' ).remove();
							}, 400);
						}
						
					},
					error:function(jqXHR, textStatus, errorThrown) {
						console.log("error " + textStatus);
						console.log("incoming Text " + jqXHR.responseText);
					}
				});
			return false;	
		}
		
		function _event_click_quickview_button($ajax_url){
			$('.quickview-button').on( "click", function(e) {
				e.preventDefault();
				var product_id  = $(this).data('product_id');
				$(".quickview-"+product_id).addClass("loading");
				$.ajax({
					url: $ajax_url,
					data: {
						"action" : "cerio_quickviewproduct",
						'product_id' : product_id
					},
					success: function(results) {
						$('.bwp-quick-view').empty().html(results).addClass("active");
						$(".quickview-"+product_id).removeClass("loading");				
						$("#quickview-slick-carousel .slick-carousel").each(function(){
							_event_load_slick_carousel($(this));
							_event_check_nav_slick($(this));
						});
						if( typeof jQuery.fn.tawcvs_variation_swatches_form != 'undefined' ) {
							$( '.variations_form' ).wc_variation_form();
							$( '.variations_form' ).tawcvs_variation_swatches_form();
						}else{
							var form_variation = $(".bwp-quick-view").find('.variations_form');
							var form_variation_select = $(".bwp-quick-view").find('.variations_form .variations select');
							form_variation.wc_variation_form();
							form_variation_select.change();
						}
						if( $(".product-countdown",".bwp-quick-view").length > 0 ){
							_event_countdown_quickview( $(".product-countdown",".bwp-quick-view") );
						}
						_event_close_quickview();
					},
					error: function(errorThrown) { console.log(errorThrown); },
				});
			});
		}
			
		function _event_check_nav_slick($element){
			if($(".slick-arrow",$element).length > 0){
				var $prev = $(".fa-angle-left",$element).clone();
				$(".fa-angle-left",$element).remove();
				if($element.parent().find(".fa-angle-left").length == 0){
					$prev.prependTo($element.parent());
				}
				$prev.on( "click", function() {
					$element.slick('slickPrev');
				});
				var $next =  $(".fa-angle-right",$element).clone();
				$(".fa-angle-right",$element).remove();
				if($element.parent().find(".fa-angle-right").length == 0){
					$next.appendTo($element.parent());
				}
				$next.on( "click", function() {
					$element.slick('slickNext');
				});
			}	
		}
		function _event_countdown_quickview($element){
			$this = $element;
			$id = $(this).data("id");			
			$current_time 	= new Date().getTime();
			$sttime 	= $(this).data('sttime');
			$countdown_time = $this.data('cdtime');
			$day = $this.data('day') ? $this.data('day') : "D";
			$hour = $this.data('hour') ? $this.data('hour') : "H";
			$min = $this.data('min') ? $this.data('min') : "M";
			$sec = $this.data('sec') ? $this.data('sec') : "S";			
			var $austDay 	= new Date();
			$austDay 		= new Date( $countdown_time * 1000 );	
			if( $sttime > $current_time  ){
				$this.remove();
				return ;
			}
			if( $countdown_time.length > 0 && $current_time > $countdown_time ){
				$this.remove();
				return ;
			}
			if( $countdown_time.length <= 0 ){
				$this.remove();
				return ;
			}
			$this.countdown($austDay, function(event) {
				$(this).html(
					event.strftime('<span class="countdown-content"><span class="days"><span class="countdown-amount">%D</span><span class="countdown-text">'+$day+'</span></span><span class="countdown-section hours"><span class="countdown-amount">%H</span><span class="countdown-text">'+$hour+'</span></span><span class="countdown-section mins"><span class="countdown-amount">%M</span><span class="countdown-text">'+$min+'</span></span><span class="countdown-section secs"><span class="countdown-amount">%S</span><span class="countdown-text">'+$sec+'</span></span></span>')
				);
			}).on('finish.countdown', function(event){
				$this.remove();
				$id = $this.data( 'id' );
				$target = this;
				$this.hide('slow', function(){ $(this).remove(); });	
				$price = $this.data( 'price' );
				$('#' + $id + ' .item-price > span').hide('slow', function(){ $('#' + $id + ' .item-price > span').remove(); });					
				$('#' + $id + ' .item-price' ).append( '<span><span class="amount">' + $price + '</span></span>' );
			});	
		}		
		function _event_close_quickview(){
			$('.quickview-close').on( "click", function(e) {
				e.preventDefault();
				$('.bwp-quick-view').empty().removeClass("active");
			});		
		}
		function _wpbingo_click_atribute_image(){
			$(".image-attribute",".product-attribute").on( "click", function() {
				if(!$(this).hasClass("active")){
					$(".image-attribute",".product-attribute").removeClass("active");
					$(this).addClass("active");
					var $parent = $(this).closest(".products-entry");
					var $thumb = $(".product-thumb-hover", $parent);
					var $image = $(this).data("image");
					$("img",$thumb).last().attr("src", $image);	
				}
			});
		}
		function _wpbingo_click_add_to_cart(){
			$(".add_to_cart_button").on( "click", function() {
				if( !$(".cerio-topcart.popup").hasClass("active") ){
					$(".cerio-topcart.popup").addClass("active");
				}
				if( !$("body").hasClass("not-scroll") && $(".cerio-topcart").hasClass("popup") ){
					$("body").addClass("not-scroll");
				}
				$(".cart-popup").addClass("active");
			});
			$(".cart-remove").on( "click", function() {
				if( $(".cerio-topcart.popup").hasClass("active") ){
					$(".cerio-topcart.popup").removeClass("active");
				}
				if( $("body").hasClass("not-scroll") ){
					$("body").removeClass("not-scroll");
				}
			});
			$(".cart-icon").on( "click", function() {
				if( !$("body").hasClass("not-scroll") ){
					$("body").addClass("not-scroll");
				}
			});
			$(".remove-cart-shadow").on( "click", function() {
				if( $(".cerio-topcart.popup").hasClass("active") ){
					$(".cerio-topcart.popup").removeClass("active");
				}
				if( $("body").hasClass("not-scroll") ){
					$("body").removeClass("not-scroll");
				}
			});
		}		
		function _event_load_slick_carousel($element){
			$element.slick({
				arrows: $element.data("nav") ? true : false ,
				dots: $element.data("dots") ? true : false ,
				prevArrow: '<i class="slick-arrow fa fa-angle-left"></i>',
				nextArrow: '<i class="slick-arrow fa fa-angle-right"></i>',
				draggable : $element.data("draggable") ? false : true ,
				slidesToShow: $element.data("columns"),
				asNavFor: $element.data("asnavfor") ? $element.data("asnavfor") : false ,
				vertical: $element.data("vertical") ? true : false ,
				verticalSwiping: $element.data("verticalswiping") ? $element.data("verticalswiping") : false ,
				rtl: ($("body").hasClass("rtl") && !$element.data("vertical")) ? true : false ,
				centerMode: $element.data("centermode") ? $element.data("centermode") : false ,
				focusOnSelect: $element.data("focusonselect") ? $element.data("focusonselect") : false ,
				responsive: [	
					{
					  breakpoint: 1200,
					  settings: {
						slidesToShow: $element.data("columns1"),
					  }
					},				
					{
					  breakpoint: 1024,
					  settings: {
						slidesToShow: $element.data("columns2"),
					  }
					},
					{
					  breakpoint: 768,
					  settings: {
						slidesToShow: $element.data("columns3"),
						vertical: false,
						verticalSwiping : false,
					  }
					},
					{
					  breakpoint: 480,
					  vertical: false,
					  verticalSwiping : false,				  
					  settings: {
						slidesToShow: $element.data("columns4"),
						vertical: false,
						verticalSwiping : false,					
					  }
					}
				]								
			});	
		}		
		
		function _event_after_sucsess_ajax(result,config){
			if (result.pagination) 
				$('nav.woocommerce-pagination').replaceWith(result.pagination);
			else
				$('nav.woocommerce-pagination').html('');
			if (result.result_count) 
				$('.woocommerce-result-count').replaceWith(result.result_count);
			else
				$('.woocommerce-result-count').html('');
			if (result.total_html) 
				$('.woocommerce-found-posts').replaceWith(result.total_html);
			else
				$('.woocommerce-found-posts').html('');
			if (result.result_breadcrumb){
				$('.breadcrumb').html(result.result_breadcrumb);
			}
			$('.woocommerce-filter-title').html(result.result_title);
			$('.bwp-filter-ajax',config.widget_id).replaceWith(result.left_nav);
			if(($("#price-filter-min-text",config.widget_id).val() != $("#bwp_slider_price",config.widget_id).data("min")) || ($("#price-filter-max-text",config.widget_id).val() != $("#bwp_slider_price",config.widget_id).data("max")))
				check_price = true;
			else
				check_price = false;
			_event_dropdown_filter();
			_event_filter_product();
			_addClassProductList();
			_event_clear_all();
			_event_click_pagination();
			_event_ajax_add_to_cart();
			if (result.base_url != '') 
				history.pushState({}, "", result.base_url.replace(/&amp;/g, '&').replace(/%2C/g, ','));
		}
		
		function _event_ajax_add_to_cart(){
			$( "a.ajax_add_to_cart" ).on( "click", function() {
				setTimeout(function() {	
					if(!$(".top-cart",".wpbingoCartTop").hasClass("open"))
						$(".top-cart",".wpbingoCartTop").addClass("open");
				}, 2000);
			});
		}	

		function _addClassProductList(){
			var class_product_default = $("ul.products-list").data("col") ? $("ul.products-list").data("col") : "";
			var class_product_item = $('.view-grid.active').data('col') ? $('.view-grid.active').data('col') : class_product_default;
			if(class_product_item){
				var list_class 	= "col-lg-12 col-md-12 col-xs-12";	
				if($('.view-grid').hasClass('active')){
					$("ul.products-list").removeClass('list').addClass('grid');
					$("ul.products-list li").removeClass(list_class).addClass(class_product_item);
				}	
				if($('.view-list').hasClass('active')){
					$("ul.products-list").removeClass('grid').addClass('list');
					$("ul.products-list li").removeClass(class_product_item).addClass(list_class);
				}	
			}
		}
		
		return false;
	};
	
	function _wpbingo_countdown_product($element){
		$('.product-countdown',$element).each(function(event){
			var $this = $(this);
			var $id = $(this).data("id");		
			var $current_time 	= new Date().getTime();
			var $sttime 	= $(this).data('sttime');
			var $countdown_time = $this.data('cdtime');
			var $day = $this.data('day') ? $this.data('day') : "D";
			var $hour = $this.data('hour') ? $this.data('hour') : "H";
			var $min = $this.data('min') ? $this.data('min') : "M";
			var $sec = $this.data('sec') ? $this.data('sec') : "S";			
			var $austDay 	= new Date();
			$austDay 		= new Date( $countdown_time * 1000 );	
			if( $sttime > $current_time  ){
				$this.remove();
				return ;
			}
			if( $countdown_time.length > 0 && $current_time > $countdown_time ){
				$this.remove();
				return ;
			}
			if( $countdown_time.length <= 0 ){
				$this.remove();
				return ;
			}
			$this.countdown($austDay, function(event) {
				$(this).html(
					event.strftime('<span class="countdown-content"><span class="days"><span class="countdown-amount">%D</span><span class="countdown-text">'+$day+'</span></span><span class="countdown-section hours"><span class="countdown-amount">%H</span><span class="countdown-text">'+$hour+'</span></span><span class="countdown-section mins"><span class="countdown-amount">%M</span><span class="countdown-text">'+$min+'</span></span><span class="countdown-section secs"><span class="countdown-amount">%S</span><span class="countdown-text">'+$sec+'</span></span></span>')
				);
			}).on('finish.countdown', function(event){
				$this.remove();
				$id = $this.data( 'id' );
				$target = this;
				$this.hide('slow', function(){ $(this).remove(); });	
				$price = $this.data( 'price' );
				$('#' + $id + ' .item-price > span').hide('slow', function(){ $('#' + $id + ' .item-price > span').remove(); });			
				$('#' + $id + ' .item-price' ).append( '<span><span class="amount">' + $price + '</span></span>' );
			});			
		});	
	}	
})(jQuery);
