jQuery.noConflict();
;(function(w, $) {
    'use strict';
    var link_brand_pre = [];
    var trigger_filter_price_woocommerce = function () {
        $(document.body).on('price_slider_create', function( e, min, max ) {
            window.priceFilterRange = [ min, max ];
        } );
        $(document.body).on('price_slider_change', function( e, min, max ) {
            if (window.priceFilterRange[0] != min || window.priceFilterRange[1] != max) {
                $( '.widget.woocommerce.widget_price_filter .button[type="submit"]' ).click();

                let min_max_price = min+'-'+max;
                if(min_max_price) {
                    $.ajax({
                        cache: false,
                        timeout: 8000,
                        url: hch_array_ajaxp.admin_ajax,
                        type: "POST",
                        data: ({ 
                            action			:	'SaveDataPopularFilter', 
                            min_max_price		:	min_max_price,
                        }),
                        beforeSend: function() {
                        },
                        success: function( data, textStatus, jqXHR ){	
                        },
                        error: function( jqXHR, textStatus, errorThrown ){
                            console.log( 'The following error occured: ' + textStatus, errorThrown );
                        },
                        complete: function( jqXHR, textStatus ){
                        }
                    });
                }
            }
        } );

        $(document.body).on('change','.price_slider_amount #min_price',function(e){
            $( '.widget.woocommerce.widget_price_filter .button[type="submit"]' ).click();

            let min_max_price = $('.price_slider_amount #min_price').val()+'-'+$('.price_slider_amount #max_price').val();
            if(min_max_price) {
                $.ajax({
                    cache: false,
                    timeout: 8000,
                    url: hch_array_ajaxp.admin_ajax,
                    type: "POST",
                    data: ({ 
                        action			:	'SaveDataPopularFilter', 
                        min_max_price		:	min_max_price,
                    }),
                    beforeSend: function() {
                    },
                    success: function( data, textStatus, jqXHR ){	
                    },
                    error: function( jqXHR, textStatus, errorThrown ){
                        console.log( 'The following error occured: ' + textStatus, errorThrown );
                    },
                    complete: function( jqXHR, textStatus ){
                    }
                });
            }


            
        })

        $(document.body).on('change','.price_slider_amount #max_price',function(e){
            $( '.widget.woocommerce.widget_price_filter .button[type="submit"]' ).click();

            let min_max_price = $('.price_slider_amount #min_price').val()+'-'+$('.price_slider_amount #max_price').val();
            if(min_max_price) {
                $.ajax({
                    cache: false,
                    timeout: 8000,
                    url: hch_array_ajaxp.admin_ajax,
                    type: "POST",
                    data: ({ 
                        action			:	'SaveDataPopularFilter', 
                        min_max_price		:	min_max_price,
                    }),
                    beforeSend: function() {
                    },
                    success: function( data, textStatus, jqXHR ){	
                    },
                    error: function( jqXHR, textStatus, errorThrown ){
                        console.log( 'The following error occured: ' + textStatus, errorThrown );
                    },
                    complete: function( jqXHR, textStatus ){
                    }
                });
            }
        });
    }

    var toggle_filter_sidebar_shop_page = function () {

        $(document).on('click','.post-type-archive-product .sidebar-inner .widget .widget-title',function(e){
            e.preventDefault();
            $(this).siblings().slideToggle();
            $(this).parents('.widget').toggleClass('active-dropdown-filter');
        })

        
    }

    var render_sidebar_filter_page_shop = function (e) {
        $( document ).on( "ajaxSuccess", function( event, xhr, settings ) {
            let url_ajax = settings.url;
            if(url_ajax) {
                if(url_ajax.includes('min_price') || url_ajax.includes('max_price')) {
                    $('.widget_price_filter').addClass('active-dropdown-filter');
                    $('.widget_price_filter form').show();
                }
                if(url_ajax.includes('filter_cat')) {
                    $('.widget_klb_product_categories').addClass('active-dropdown-filter');
                    $('.widget_klb_product_categories .widget-body').show();
                }

                if(url_ajax.includes('stock_status') || url_ajax.includes('on_sale')) {
                    $('.widget_product_status').addClass('active-dropdown-filter');
                    $('.widget_product_status .widget-body').show();
                }
                
                if(url_ajax.includes('filter_brands')) {
                    $('.woocommerce-widget-layered-nav').addClass('active-dropdown-filter');
                    $('.woocommerce-widget-layered-nav .woocommerce-widget-layered-nav-list').show();
                }
            }
        } );
    }

    var active_sidebar_filter_page_shop = function (e) {
        let url_filter = window.location.href;
        if(url_filter) {
            if(url_filter.includes('min_price') || url_filter.includes('max_price')) {
                $('.widget_price_filter').addClass('active-dropdown-filter');
                $('.widget_price_filter form').show();
            }
            if(url_filter.includes('filter_cat')) {
                $('.widget_klb_product_categories').addClass('active-dropdown-filter');
                $('.widget_klb_product_categories .widget-body').show();
            }

            if(url_filter.includes('stock_status') || url_filter.includes('on_sale')) {
                $('.widget_product_status').addClass('active-dropdown-filter');
                $('.widget_product_status .widget-body').show();
            }
            
            if(url_filter.includes('filter_brands')) {
                $('.woocommerce-widget-layered-nav').addClass('active-dropdown-filter');
                $('.woocommerce-widget-layered-nav .woocommerce-widget-layered-nav-list').show();
            }
        }
    }

    var URLToArray = function(url) {
        var request = {};
        var pairs = url.substring(url.indexOf('?') + 1).split('&');
        for (var i = 0; i < pairs.length; i++) {
            if(!pairs[i])
                continue;
            var pair = pairs[i].split('=');
            request[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
         }
         return request;
    }

    var save_data_filter_popular = function () {

        $('body').on('click','.widget_klb_product_categories .widget-body .product_cat',function(e){
            let id_cat = $(this).find('input').val();
            let is_checked = $(this).find('input').is(":checked");
            if(!is_checked) {
                if(id_cat) {
                    $.ajax({
                        cache: false,
                        timeout: 8000,
                        url: hch_array_ajaxp.admin_ajax,
                        type: "POST",
                        data: ({ 
                            action			:	'SaveDataPopularFilter', 
                            filter_cat		:	id_cat,
                        }),
                        beforeSend: function() {
                        },
                        success: function( data, textStatus, jqXHR ){	
                        },
                        error: function( jqXHR, textStatus, errorThrown ){
                            console.log( 'The following error occured: ' + textStatus, errorThrown );
                        },
                        complete: function( jqXHR, textStatus ){
                        }
                    });
                }
            }
        })


        $('body').on('click','.widget_product_status .widget-body ul li a',function(e){
            let status_product = $(this).find('input').val();
            let is_checked = $(this).find('input').is(":checked");
            if(!is_checked) {
                if(status_product) {
                    $.ajax({
                        cache: false,
                        timeout: 8000,
                        url: hch_array_ajaxp.admin_ajax,
                        type: "POST",
                        data: ({ 
                            action			:	'SaveDataPopularFilter', 
                            status_product		:	status_product,
                        }),
                        beforeSend: function() {
                        },
                        success: function( data, textStatus, jqXHR ){	
                        },
                        error: function( jqXHR, textStatus, errorThrown ){
                            console.log( 'The following error occured: ' + textStatus, errorThrown );
                        },
                        complete: function( jqXHR, textStatus ){
                        }
                    });
                }
            }
        })



        $('body').on('click','.woocommerce-widget-layered-nav ul li a',function(e){
            let link = $(this).attr('href');
            $(this).parent().hasClass('chosen');

            

            if(!$(this).parent().hasClass('chosen')) {
                let param_link_filter = URLToArray(link);
                var brand_filter_old = link_brand_pre.filter_brands;
                var brand_fitler_new = param_link_filter.filter_brands;
                if(brand_filter_old) {
                    var brand_slug = brand_fitler_new.replace(brand_filter_old+',','');
                    link_brand_pre = param_link_filter;
                }else{
                    link_brand_pre = param_link_filter;
                    var brand_slug = brand_fitler_new;
                }
                
                if(brand_slug) {
                    $.ajax({
                        cache: false,
                        timeout: 8000,
                        url: hch_array_ajaxp.admin_ajax,
                        type: "POST",
                        data: ({ 
                            action			:	'SaveDataPopularFilter', 
                            brand_slug		:	brand_slug,
                        }),
                        beforeSend: function() {
                        },
                        success: function( data, textStatus, jqXHR ){	
                        },
                        error: function( jqXHR, textStatus, errorThrown ){
                            console.log( 'The following error occured: ' + textStatus, errorThrown );
                        },
                        complete: function( jqXHR, textStatus ){
                        }
                    });
                }
                

            }
           

            

            // if(!is_checked) {
            //     if(status_product) {
            //         $.ajax({
            //             cache: false,
            //             timeout: 8000,
            //             url: hch_array_ajaxp.admin_ajax,
            //             type: "POST",
            //             data: ({ 
            //                 action			:	'SaveDataPopularFilter', 
            //                 status_product		:	status_product,
            //             }),
            //             beforeSend: function() {
            //             },
            //             success: function( data, textStatus, jqXHR ){	
            //             },
            //             error: function( jqXHR, textStatus, errorThrown ){
            //                 console.log( 'The following error occured: ' + textStatus, errorThrown );
            //             },
            //             complete: function( jqXHR, textStatus ){
            //             }
            //         });
            //     }
            // }
        })



    }

    $(document).ready(function(){
        trigger_filter_price_woocommerce();
        toggle_filter_sidebar_shop_page();
        render_sidebar_filter_page_shop();
        active_sidebar_filter_page_shop();
        save_data_filter_popular();

    });

    

})(window, jQuery);