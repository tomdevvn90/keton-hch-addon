jQuery.noConflict();
;(function(w, $) {
    'use strict';

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

                if(url_ajax.includes('filter_ingredient')) {
                    $('.widget_klb_product_ingredient').addClass('active-dropdown-filter');
                    $('.widget_klb_product_ingredient .widget-body').show();
                }

                if(url_ajax.includes('stock_status') || url_ajax.includes('on_sale')) {
                    $('.widget_product_status').addClass('active-dropdown-filter');
                    $('.widget_product_status .widget-body').show();
                }
                
                if(url_ajax.includes('filter_brands')) {

                    $('.woocommerce-widget-layered-nav ul li').each(function(i,e){
                        let seft = $(this);
                        if(seft.hasClass('chosen')) {
                            seft.parents('.woocommerce-widget-layered-nav').addClass('active-dropdown-filter');
                            seft.parents('.woocommerce-widget-layered-nav').find('.woocommerce-widget-layered-nav-list').show();
                        }
                        
                    })
                }

                if(url_ajax.includes('filter_amount')) {
                    $('.woocommerce-widget-layered-nav ul li').each(function(i,e){
                        let seft = $(this);
                        if(seft.hasClass('chosen')) {
                            seft.parents('.woocommerce-widget-layered-nav').addClass('active-dropdown-filter');
                            seft.parents('.woocommerce-widget-layered-nav').find('.woocommerce-widget-layered-nav-list').show();
                        }
                        
                    })
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



    var save_data_filter_popular = function () {
        $('body').on('click','.widget_klb_product_categories .widget-body .product_cat',function(e){
            let id_cat = $(this).find('input').val();
            let is_checked = $(this).find('input').is(":checked");
            let name_cat = $(this).find('input').attr('id');
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
                            name_cat        :   name_cat
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


        $('body').on('click','.widget_klb_product_ingredient .widget-body .product_cat',function(e){
            let id_gre = $(this).find('input').val();
            let is_checked = $(this).find('input').is(":checked");
            let name_gre = $(this).find('input').attr('id');
            if(!is_checked) {
                if(id_gre) {
                    $.ajax({
                        cache: false,
                        timeout: 8000,
                        url: hch_array_ajaxp.admin_ajax,
                        type: "POST",
                        data: ({ 
                            action			:	'SaveDataPopularFilter', 
                            filter_gre		:	id_gre,
                            name_gre        :   name_gre
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
            let brand_slug = $(this).parent().find('.count').data('filter-attribute');
            let product_attr = $(this).parent().find('.count').data('taxonomy');
            let brand_name = $(this).text();
            if(brand_slug) {
                if(!$(this).parent().hasClass('chosen')) {
                    
                    $.ajax({
                        cache: false,
                        timeout: 8000,
                        url: hch_array_ajaxp.admin_ajax,
                        type: "POST",
                        data: ({ 
                            action			:	'SaveDataPopularFilter', 
                            brand_slug		:	brand_slug,
                            brand_name      :   brand_name,
                            product_attr    :   product_attr
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
    }

    var sort_order_popular_filter = function () {
        if(!$('.widget-body-popular-filter .count').length>0) {
            $('.widget-be-popular-filter').removeClass('active-dropdown-filter');
        }
        $('.widget-body-popular-filter').each(function(){
            var $this = $(this);
            $this.append($this.find('.count').get().sort(function(a, b) {
                return $(b).data('count') - $(a).data('count');
            }));
        });
        
    }


    var trigger_fillter_popular = function () {
        $('body').on('change','.widget-body-popular-filter .categories input',function(){
            let id_cat = $(this).val(); 
            $('.remove-filter').remove();
            $('.wrapper-theme-product .products.column-4').remove();
            $('.wrapper-theme-product .woocommerce-pagination').remove();
            var posts_per_page = $('.ajax_pagination').attr( 'posts_per_page' );
            var post_type = $('.ajax_pagination').attr( 'post_type' );
            $.ajax({
                cache: false,
                timeout: 8000,
                url: hch_array_ajaxp.admin_ajax,
                type: "POST",
                data: ({ 
                    action			:	'filter_cat_product_shop', 
                    id_cat		:	id_cat,
                    data_page :1,
                    posts_per_page: posts_per_page,
                    post_type:post_type
                }),
                beforeSend: function() {
                $( '.loading_ajaxp' ).css( 'display','block' );
                },
                success: function( data, textStatus, jqXHR ){					
                    $( '#result_ajaxp_filter_shop' ).html( data );
                },
                error: function( jqXHR, textStatus, errorThrown ){
                    console.log( 'The following error occured: ' + textStatus, errorThrown );
                },
                complete: function( jqXHR, textStatus ){
                }
            });
        })

        $('body').on('change','.widget-body-popular-filter .ingredient input',function(){
            let id_ingre = $(this).val(); 
            $('.remove-filter').remove();
            $('.wrapper-theme-product .products.column-4').remove();
            $('.wrapper-theme-product .woocommerce-pagination').remove();
            var posts_per_page = $('.ajax_pagination').attr( 'posts_per_page' );
            var post_type = $('.ajax_pagination').attr( 'post_type' );
            $.ajax({
                cache: false,
                timeout: 8000,
                url: hch_array_ajaxp.admin_ajax,
                type: "POST",
                data: ({ 
                    action			:	'filter_cat_product_shop', 
                    id_ingre		:	id_ingre,
                    data_page :1,
                    posts_per_page: posts_per_page,
                    post_type:post_type
                }),
                beforeSend: function() {
                $( '.loading_ajaxp' ).css( 'display','block' );
                },
                success: function( data, textStatus, jqXHR ){					
                    $( '#result_ajaxp_filter_shop' ).html( data );
                },
                error: function( jqXHR, textStatus, errorThrown ){
                    console.log( 'The following error occured: ' + textStatus, errorThrown );
                },
                complete: function( jqXHR, textStatus ){
                }
            });
        })

        $('body').on('change','.widget-body-popular-filter .range_price input',function(e){
            let range_price = $(this).val();
            $('.remove-filter').remove();
            $('.wrapper-theme-product .products.column-4').remove();
            $('.wrapper-theme-product .woocommerce-pagination').remove();
            var posts_per_page = $('.ajax_pagination').attr( 'posts_per_page' );
            var post_type = $('.ajax_pagination').attr( 'post_type' );
            $.ajax({
                cache: false,
                timeout: 8000,
                url: hch_array_ajaxp.admin_ajax,
                type: "POST",
                data: ({ 
                    action			:	'filter_cat_product_shop', 
                    range_price		:	range_price,
                    data_page :1,
                    posts_per_page: posts_per_page,
                    post_type:post_type
                }),
                beforeSend: function() {
                $( '.loading_ajaxp' ).css( 'display','block' );
                },
                success: function( data, textStatus, jqXHR ){					
                    $( '#result_ajaxp_filter_shop' ).html( data );
                },
                error: function( jqXHR, textStatus, errorThrown ){
                    console.log( 'The following error occured: ' + textStatus, errorThrown );
                },
                complete: function( jqXHR, textStatus ){
                }
            });
        });

        $('body').on('change','.widget-be-popular-filter .widget-body-popular-filter .attribute-product input',function(){
            let tax = $(this).data('tax');
            let slug_attr = $(this).val();
            $('.remove-filter').remove();
            $('.wrapper-theme-product .products.column-4').remove();
            $('.wrapper-theme-product .woocommerce-pagination').remove();
            var posts_per_page = $('.ajax_pagination').attr( 'posts_per_page' );
            var post_type = $('.ajax_pagination').attr( 'post_type' );
            $.ajax({
                cache: false,
                timeout: 8000,
                url: hch_array_ajaxp.admin_ajax,
                type: "POST",
                data: ({ 
                    action			:	'filter_cat_product_shop', 
                    tax:tax,
                    slug_attr:slug_attr,
                    data_page :1,
                    posts_per_page: posts_per_page,
                    post_type:post_type
                }),
                beforeSend: function() {
                $( '.loading_ajaxp' ).css( 'display','block' );
                },
                success: function( data, textStatus, jqXHR ){					
                    $( '#result_ajaxp_filter_shop' ).html( data );
                },
                error: function( jqXHR, textStatus, errorThrown ){
                    console.log( 'The following error occured: ' + textStatus, errorThrown );
                },
                complete: function( jqXHR, textStatus ){
                }
            });
        });

        $('body').on('change','.widget-body-popular-filter .product_status input',function(e){
            let status_product = $(this).val();
            $('.remove-filter').remove();
            $('.wrapper-theme-product .products.column-4').remove();
            $('.wrapper-theme-product .woocommerce-pagination').remove();
            var posts_per_page = $('.ajax_pagination').attr( 'posts_per_page' );
            var post_type = $('.ajax_pagination').attr( 'post_type' );
            $.ajax({
                cache: false,
                timeout: 8000,
                url: hch_array_ajaxp.admin_ajax,
                type: "POST",
                data: ({ 
                    action			:	'filter_cat_product_shop', 
                    status_product:status_product,
                    data_page :1,
                    posts_per_page: posts_per_page,
                    post_type:post_type
                }),
                beforeSend: function() {
                $( '.loading_ajaxp' ).css( 'display','block' );
                },
                success: function( data, textStatus, jqXHR ){					
                    $( '#result_ajaxp_filter_shop' ).html( data );
                },
                error: function( jqXHR, textStatus, errorThrown ){
                    console.log( 'The following error occured: ' + textStatus, errorThrown );
                },
                complete: function( jqXHR, textStatus ){
                }
            });
        })

    }

    var pagination_page_shop = function () {
        $( 'body' ).on( 'click','#result_ajaxp_filter_shop ul.pagination a', function( e ) {
            e.preventDefault();
            var data_page = $(this).attr( 'data-page' );
            var posts_per_page = $('.ajax_pagination').attr( 'posts_per_page' );
            var post_type = $('.ajax_pagination').attr( 'post_type' );
            var id_cat = $('.wrapper-product-resulter-shop').attr('id_cat');
            var range_price = $('.wrapper-product-resulter-shop').attr('price_range');
            var tax = $('.wrapper-product-resulter-shop').attr('tax');
            var slug_attr = $('.wrapper-product-resulter-shop').attr('slug_attr');
            var status_product = $('.wrapper-product-resulter-shop').attr('status_product');
            var id_ingre = $('.wrapper-product-resulter-shop').attr('id_ingre');
            $.ajax({
                cache: false,
                timeout: 8000,
                url: hch_array_ajaxp.admin_ajax,
                type: "POST",
                data: ({ 
                    action			:	'filter_cat_product_shop', 
                    data_page		:	data_page,
                    posts_per_page	:	posts_per_page,
                    post_type		:	post_type,
                    id_cat	:   id_cat,
                    id_ingre:id_ingre,
                    slug_attr:slug_attr,
                    tax:tax,
                    range_price		:	range_price,
                    status_product:status_product
                    
                }),
                beforeSend: function() {
                $( '.loading_ajaxp' ).css( 'display','block' );
                },
                success: function( data, textStatus, jqXHR ){					
                    $( '#result_ajaxp_filter_shop' ).html( data );
                },
                error: function( jqXHR, textStatus, errorThrown ){
                    console.log( 'The following error occured: ' + textStatus, errorThrown );
                },
                complete: function( jqXHR, textStatus ){
                }
            });
        });
    }

    $(document).ready(function(){
        bacolaThemeModule.ajaxLinks = bacolaThemeModule.ajaxLinks + ', .widget_klb_product_ingredient a';
        bacola_settings.ajax_scroll_class = '.site-content .product-custom-reponsive-ajax';
        trigger_filter_price_woocommerce();
        toggle_filter_sidebar_shop_page();
        render_sidebar_filter_page_shop();
        active_sidebar_filter_page_shop();
        save_data_filter_popular();
        //sort_order_popular_filter();
        trigger_fillter_popular();
        pagination_page_shop();
    });

    

})(window, jQuery);