jQuery(document).ready(function($) {

    $('.psfw-buton-one-wrapper').each(function() {
        var text = $(this).children().html();
        $(this).children().html('<span class="psfw-span">' + text + '</span>');
    });
    $('.psfw-buton-two-wrapper').each(function() {
        var text = $(this).children().html();
        $(this).children().html('<span class="psfw-span">' + text + '</span>');
    });

    //Slider Layout
    var psfw_slider = [];
   
    $(".psfw-layout-slider-section").each(function() {
        var id = $(this).data('id');
        var selector = $(this);
        var a = 1;
        var controls = $(this).data('controls');
        var auto = $(this).data('auto');
        var speed = $(this).data('speed');
        var pager = $(this).data('pager');
        var template = $(this).data('template');
        if (template=='template-10'){
            var next_text = '<span class="lnr lnr-arrow-right" aria-hidden="true"></span>';
            var pre_text = '<span class="lnr lnr-arrow-left" aria-hidden="true"></span>';
        }else{
            var next_text = '<span class="lnr lnr-chevron-right" aria-hidden="true"></span>';
            var pre_text = '<span class="lnr lnr-chevron-left" aria-hidden="true"></span>';
        }

        if (template=='template-14'){
            custom_pager = ".psfw-bxslider-pager";
        }else{
             custom_pager = "";
        }
        
        psfw_slider[id] = $(this).bxSlider({
            auto: auto,
            autocontrols: controls,
            pause: 3000,
            speed: speed,
            pager: pager,
            infiniteLoop: false,
            nextText: next_text,
            prevText: pre_text,
            adaptiveHeight: true,
            useCSS: false,
            pagerCustom: custom_pager,
            onSliderLoad: function(currentSlideHtmlObject) {
                if (template === 'template-1') {
                    setTimeout(function() {
                        selector.closest('.psfw-layout-slider-section').find('.psfw-circle-content').removeClass('psfw-hidden');
                        selector.closest('.psfw-layout-slider-section').find('.psfw-circle-content').addClass('animated fadeIn');
                    }, 500
                            );
                    setTimeout(function() {
                        selector.closest('.psfw-layout-slider-section').find('.psfw-top-wrap').removeClass('psfw-hidden');
                        selector.closest('.psfw-layout-slider-section').find('.psfw-top-wrap').addClass('animated slideInUp');
                    }, 700
                            );
                } else if (template === 'template-2') {
                    setTimeout(function() {

                        selector.closest('.psfw-layout-slider-section').find('.psfw-detail-side-wrap').removeClass('psfw-hidden');
                        selector.closest('.psfw-layout-slider-section').find('.psfw-detail-side-wrap').addClass('animated slideInDown');
                    }, 600
                            );
                    setTimeout(function() {
                        selector.closest('.psfw-layout-slider-section').find('.psfw-image-hover-wrap').removeClass('psfw-hidden');
                        selector.closest('.psfw-layout-slider-section').find('.psfw-image-hover-wrap').addClass('animated slideInUp');
                    }, 400
                            );
                } else if (template === 'template-3' || template === 'template-4') {
                    setTimeout(function() {

                        selector.closest('.psfw-layout-slider-section').find('.psfw-detail-inner-wrap').removeClass('psfw-hidden');
                        selector.closest('.psfw-layout-slider-section').find('.psfw-detail-inner-wrap').addClass('animated zoomIn');
                    }, 400
                            );
                } 
            },
            onSlideAfter: function(currentSlideNumber, totalSlideQty, currentSlideHtmlObject) {
                var acurrent = currentSlideHtmlObject + 1;
                if (template === 'template-1') {
                    setTimeout(function() {
                        selector.closest('.psfw-layout-slider-section').find('.psfw-circle-content').removeClass('psfw-hidden');
                        selector.closest('.psfw-layout-slider-section').find('.psfw-circle-content').removeClass('animated fadeOut');
                        selector.closest('.psfw-layout-slider-section').find('.psfw-circle-content').addClass('animated fadeIn');
                    }, 500
                            );
                    setTimeout(function() {
                        selector.closest('.psfw-layout-slider-section').find('.psfw-top-wrap').removeClass('psfw-hidden');
                        selector.closest('.psfw-layout-slider-section').find('.psfw-top-wrap').removeClass('animated fadeOut');
                        selector.closest('.psfw-layout-slider-section').find('.psfw-top-wrap').addClass('animated slideInUp');
                    }, 700
                            );
                } else if (template === 'template-2') {
                    setTimeout(function() {
                        selector.closest('.psfw-layout-slider-section').find('.psfw-detail-side-wrap').removeClass('psfw-hidden');
                        selector.closest('.psfw-layout-slider-section').find('.psfw-detail-side-wrap').removeClass('animated fadeOut');
                        selector.closest('.psfw-layout-slider-section').find('.psfw-detail-side-wrap').addClass('animated slideInDown');
                    }, 600
                            );
                    setTimeout(function() {
                        selector.closest('.psfw-layout-slider-section').find('.psfw-image-hover-wrap').removeClass('psfw-hidden');
                        selector.closest('.psfw-layout-slider-section').find('.psfw-image-hover-wrap').removeClass('animated fadeOut');
                        selector.closest('.psfw-layout-slider-section').find('.psfw-image-hover-wrap').addClass('animated slideInUp');
                    }, 400
                            );
                } else if (template === 'template-3' || template === 'template-4') {
                    setTimeout(function() {

                        selector.closest('.psfw-layout-slider-section').find('.psfw-detail-inner-wrap').removeClass('psfw-hidden');
                        selector.closest('.psfw-layout-slider-section').find('.psfw-detail-inner-wrap').removeClass('animated fadeOut');
                        selector.closest('.psfw-layout-slider-section').find('.psfw-detail-inner-wrap').addClass('animated zoomIn');
                    }, 400
                            );
                } 
            },
            onSlideBefore: function(currentSlideHtmlObject) {
                if (template === 'template-1') {
                    selector.closest('.psfw-layout-slider-section').find('.psfw-circle-content').removeClass('animated fadeIn');
                    selector.closest('.psfw-layout-slider-section').find('.psfw-top-wrap').removeClass('animated slideInUp');
                    selector.closest('.psfw-layout-slider-section').find('.psfw-circle-content').addClass('animated fadeOut');
                    selector.closest('.psfw-layout-slider-section').find('.psfw-top-wrap').addClass('animated fadeOut');
                } else if (template === 'template-2') {
                    selector.closest('.psfw-layout-slider-section').find('.psfw-detail-side-wrap').removeClass('animated slideInDown');
                    selector.closest('.psfw-layout-slider-section').find('.psfw-image-hover-wrap').removeClass('animated slideInUp');
                    selector.closest('.psfw-layout-slider-section').find('.psfw-detail-side-wrap').addClass('animated fadeOut');
                    selector.closest('.psfw-layout-slider-section').find('.psfw-image-hover-wrap').addClass('animated fadeOut');
                } else if (template === 'template-3' || template === 'template-4') {
                    selector.closest('.psfw-layout-slider-section').find('.psfw-detail-inner-wrap').removeClass('animated zoomIn');
                    selector.closest('.psfw-layout-slider-section').find('.psfw-detail-inner-wrap').addClass('animated fadeOut');
                } 
            }
        });
        var clicks = 0;

        $('body').on('click', '.psfw-buttons-collection-secondary', function() {
            $(this).closest('.psfw-content-container').find('.psfw-buttons-collection').toggleClass('psfw-display-buttons');
            if ($('.psfw-buttons-collection').hasClass('psfw-display-buttons')){
                var number_of_children = $(this).closest('.psfw-content-container').find('.psfw-buttons-collection-primary').children().length;
                if(number_of_children == 4){
                    var estimated_width = 190;
                }else{
                    var estimated_width = ((number_of_children * 25) + ((number_of_children + 1) * 15));
                }
                $(this).closest('.psfw-content-container').find('.psfw-buttons-collection-primary').css("width",estimated_width);
            }
            else{
                $(this).closest('.psfw-content-container').find('.psfw-buttons-collection-primary').css('width', '0');
            }
        });
    }); 
  
    //Carousel layout
    var psfw_carousel = [];
    $(".psfw-layout-carousel-section").each(function() {
        var id = $('.psfw-main-product-wrapper').data('id');
        var column = $(this).data('column');
        var controls = $(this).data('controls');
        var auto = $(this).data('auto');
        var mode = $(this).data('orientation');
        var autoHover = $(this).data('autohover');
        var speed = $(this).data('speed');
        var pager = $(this).data('pager');
        var width = $(this).data('width');
        var template = $(this).data('template');
        var next_text = '<i class="fa fa-angle-right" aria-hidden="true"></i>';
        var pre_text = '<i class="fa fa-angle-left" aria-hidden="true"></i>';
        var margin = 15;
        if (jQuery(window).width() >= 981) {
            var itemcolumn = column;
            var slidew = width;
            var smargin = margin;
        } else if (jQuery(window).width() <= 980 && jQuery(window).width() >= 801) {
            var itemcolumn = 3;
            var slidew = 300;
            var smargin = margin;
        } else if (jQuery(window).width() <= 800 && jQuery(window).width() >= 641) {
            var itemcolumn = 2;
            var slidew = 240;
            var smargin = margin;
        } else if (jQuery(window).width() <= 640 && jQuery(window).width() >= 320) {
            var itemcolumn = 1;
            var slidew = 350;
            var smargin = 0;
        }

        psfw_carousel[id] = $(this).bxSlider({
            minSlides: 1,
            maxSlides: itemcolumn,
            moveSlides: 1,
            pager: pager,
            mode: mode,
            autoHover: autoHover, //to stop on hover
            slideWidth: slidew,
            slideMargin: smargin,
            auto: auto,
            autoControls: controls,
            speed: speed,
            nextText: next_text,
            prevText: pre_text

        });

    });
    /*
     * Add span to View Cart woocommerce
     */
    $('body').on('click', '.add_to_cart_button', function() {
        $(document).ajaxComplete(function() {
            $('.added_to_cart').each(function() {
                $('.added_to_cart').html('<span class="psfw-span">View Cart</span>');
            });
        });
    });

    var count = 0;
    $(".psfw-thumbnail-slider").each(function() {
        var id = $(this).data('id');
        var next_text = '<i class="fa fa-angle-right" aria-hidden="true"></i>';
        var pre_text = '<i class="fa fa-angle-left" aria-hidden="true"></i>';
        psfw_thumb_slider[id] = $(this).bxSlider({
            minSlides: 1,
            maxSlides: 4,
            slideWidth: 100,
            slideMargin: 10,
            moveSlides: 1,
            auto: false,
            pager: false,
            infiniteLoop: true,
            nextText: next_text,
            prevText: pre_text
        });
    });

    /*
     * Swap effect on images
     */
    $('body').on('hover', '.psfw-inner-wrap', function() {
        var swap = $(this).find('.psfw-swap-image').data('swap');
        $(this).find('.psfw-swap-image').fadeIn(100).attr('src', swap).fadeIn(200);
    });
    $('body').on('mouseleave', '.psfw-inner-wrap', function() {
        var original = $(this).find('.psfw-swap-image').data('original');
        $(this).find('.psfw-swap-image').attr('src', original).fadeIn('slow');
    });
    $('body').on('hover', '.psfw-masonry-item-inner', function() {
        var swap = $(this).find('.psfw-swap-image').data('swap');
        $(this).find('.psfw-swap-image').fadeIn(100).attr('src', swap).fadeIn(200);
    });
    $('body').on('mouseleave', '.psfw-masonry-item-inner', function() {
        var original = $(this).find('.psfw-swap-image').data('original');
        $(this).find('.psfw-swap-image').attr('src', original).fadeIn('slow');
    });
    /*
     * WooCommerce Disable link for Variable Price functionality
     *
     */
    $('body').on('click', '.product_type_variable', function() {
        event.preventDefault();
        var selector = $(this);
        var post_id = selector.closest('.add_to_cart_button').data('product_id');
        $.ajax({
            url: psfw_frontend_js_params.ajax_url,
            data: {
                post_id: post_id,
                _wpnonce: psfw_frontend_js_params.ajax_nonce,
                action: 'psfw_variable_attribute',
                beforeSend: function() {
                    $('.psfw-lightbox-loader').show();
                }
            },
            type: "POST",
            success: function(response) {
                $('.psfw-lightbox-loader').hide();
                $(".psfw-attributes-container").html(response);
            },
            complete: function() {
                loadVariationScript();
            }
        });
        event.preventDefault();
    });
    
    $('body').on('click', '.psfw-close-variation', function() {
        $(this).closest('.psfw-attributes-container').find('.psfw-variation-container').fadeOut();
    });

    // Ajax add to cart
    $(document).on('click', '.variations_form .single_add_to_cart_button', function(e) {
        e.preventDefault();
        $variation_form = $(this).closest('.variations_form');
        var var_id = $variation_form.find('input[name=variation_id]').val();
        var product_id = $variation_form.find('input[name=product_id]').val();
        var quantity = $variation_form.find('input[name=quantity]').val();
        $('.ajaxerrors').remove();
        var item = {},
            check = true;
        variations = $variation_form.find('select[name^=attribute]');
        /* Updated code to work with radio button - mantish - WC Variations Radio Buttons - 8manos */
        if (!variations.length) {
            variations = $variation_form.find('[name^=attribute]:checked');
        }
        /* Backup Code for getting input variable */
        if (!variations.length) {
            variations = $variation_form.find('input[name^=attribute]');
        }
        variations.each(function() {
            var $this = $(this),
                    attributeName = $this.attr('name'),
                    attributevalue = $this.val(),
                    index,
                    attributeTaxName;
            $this.removeClass('error');
            if (attributevalue.length === 0) {
                index = attributeName.lastIndexOf('_');
                attributeTaxName = attributeName.substring(index + 1);
                $this.addClass('required error').before('<div class="ajaxerrors"><p>Please select ' + attributeTaxName + '</p></div>')
                check = false;
            } else {
                item[attributeName] = attributevalue;
            }
        });
        if (!check) {
            return false;
        }
        var $thisbutton = $(this);
        if ($thisbutton.is('.variations_form .single_add_to_cart_button')) {
            $thisbutton.removeClass('added');
            $thisbutton.addClass('loading');
            var data = {
                action: 'woocommerce_add_to_cart_variable_rc',
                product_id: product_id,
                quantity: quantity,
                variation_id: var_id,
                variation: item
            };

            // Trigger event
            $('body').trigger('adding_to_cart', [$thisbutton, data]);

            // Ajax action
            $.post(wc_add_to_cart_params.ajax_url, data, function(response) {

                if (!response)
                    return;
                var this_page = window.location.toString();

                this_page = this_page.replace('add-to-cart', 'added-to-cart');

                if (response.error && response.product_url) {
                    window.location = response.product_url;
                    return;
                }

                if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
                    window.location = wc_add_to_cart_params.cart_url;
                    return;

                } else {
                    $thisbutton.removeClass('loading');
                    var fragments = response.fragments;
                    var cart_hash = response.cart_hash;

                    // Block fragments class
                    if (fragments) {
                        $.each(fragments, function(key) {
                            $(key).addClass('updating');
                        });
                    }

                    // Block widgets and fragments
                    $('.shop_table.cart, .updating, .cart_totals').fadeTo('400', '0.6').block({
                        message: null,
                        overlayCSS: {
                            opacity: 0.6
                        }
                    });

                    // Changes button classes
                    $thisbutton.addClass('added');

                    // View cart text
                    if (!wc_add_to_cart_params.is_cart && $thisbutton.parent().find('.added_to_cart').size() === 0) {
                        $thisbutton.after(' <a href="' + wc_add_to_cart_params.cart_url + '" class="added_to_cart wc-forward" title="' +
                                wc_add_to_cart_params.i18n_view_cart + '">' + wc_add_to_cart_params.i18n_view_cart + '</a>');
                    }
                    if (fragments) {
                        $.each(fragments, function(key, value) {
                            $(key).replaceWith(value);
                        });
                    }
                    $('.widget_shopping_cart, .updating').stop(true).css('opacity', '1').unblock();
                    $('.shop_table.cart').load(this_page + ' .shop_table.cart:eq(0) > *', function() {
                        $('.shop_table.cart').stop(true).css('opacity', '1').unblock();
                        $(document.body).trigger('cart_page_refreshed');
                    });

                    $('.cart_totals').load(this_page + ' .cart_totals:eq(0) > *', function() {
                        $('.cart_totals').stop(true).css('opacity', '1').unblock();
                    });
                    $(document.body).trigger('added_to_cart', [fragments, cart_hash, $thisbutton]);
                }
            });
            return false;
        } else {
            return true;
        }
    });

    function loadVariationScript() {
        var url = psfw_object_name.templateUrl;
        $.getScript(url + '/assets/js/frontend/add-to-cart-variation.min.js');
        $.getScript(url + '/assets/js/frontend/add-to-cart-variation.js');
    }

});