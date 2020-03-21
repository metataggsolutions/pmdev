(function($) {
    /**
     * Add blog functionality
     */
    $(function() {
        /*
         * Fetch list of taxonomy from post type
         */
        $('.psfw-product-type').on('change', function() {
            var value = $(this).val();
            var cart = $("<option value='product_add_cart'>Add to Cart</option>");
            if (value === 'product'){
                $('.psfw-inbuilt-buy-detail').hide();
                $('.psfw-woocommerce-wrap').show();
                $('.psfw-price-wrapper').show();
                $(".psfw-button-one-link-type option[value='product_add_cart']").remove();
                $(".psfw-button-two-link-type option[value='product_add_cart']").remove();
                $(".psfw-button-one-link-type").append("<option value='product_add_cart'>Add to Cart</option>");
                $(".psfw-button-two-link-type").append("<option value='product_add_cart'>Add to Cart</option>");
            } 
            var select_post = $(this).val();
            $.ajax({
                url: psfw_backend_js_params.ajax_url,
                data: {
                    select_post: select_post,
                    _wpnonce: psfw_backend_js_params.ajax_nonce,
                    action: 'psfw_selected_post_taxonomy',
                    beforeSend: function() {
                        $(".psfw-loader-preview").show();
                    }
                },
                type: "POST",
                success: function(response) {
                    $(".psfw-select-taxonomy").html(response);
                    $(".psfw-filter-taxonomy").html(response);
                    $(".psfw-loader-preview").hide();
                }
            });
        });
        var selected_product = $(".psfw-product-type option:selected").val();
        if (selected_product === 'product'){
            $('.psfw-inbuilt-buy-detail').hide();
            $('.psfw-woocommerce-wrap').show();
        } 
        /*
         * Fetch list of terms from taxonomy
         */
        $('.psfw-select-taxonomy').on('change', function() {
            var select_tax = $(this).val();
            var tax_type = $('.psfw-taxonomy-queries-type').val();
            $.ajax({
                url: psfw_backend_js_params.ajax_url,
                data: {
                    select_tax: select_tax,
                    tax_type: tax_type,
                    _wpnonce: psfw_backend_js_params.ajax_nonce,
                    action: 'psfw_selected_taxonomy_terms',
                    beforeSend: function() {
                        $(".psfw-taxonomy-preview").show();
                    }
                },
                type: "POST",
                success: function(response) {
                    console.log(response);
                    $(".psfw-taxonomy-preview").hide();
                    if (tax_type == 'multiple-taxonomy') {
                        $(".psfw-multiple-taxonomy-term").html(response);
                    } else if (tax_type == 'simple-taxonomy') {
                        $(".psfw-simple-taxonomy-term").html(response);
                    }
                }
            });
        });
        /*
         * Fetch list of terms for multiple taxonomy condition
         */
        $('body').on('change', '.psfw-multiple-taxonomy', function() {
            var select_tax = $(this).val();
            var nam = $(this);
            //alert(select_tax);
            $.ajax({
                url: psfw_backend_js_params.ajax_url,
                data: {
                    select_tax: select_tax,
                    _wpnonce: psfw_backend_js_params.ajax_nonce,
                    action: 'psfw_hierarchy_terms',
                    beforeSend: function() {
                    }
                },
                type: "POST",
                success: function(response) {
                    $(nam).closest('.psfw-each-taxonomy-wrap').find(".psfw-hierarchy-taxonomy-term").html(response);
                }
            });
        });
        /*
         * Insert meta condition for
         *  multiple custom field query
         */
        $('.psfw-add-meta-query').click(function() {
            $.ajax({
                url: psfw_backend_js_params.ajax_url,
                data: {
                    _wpnonce: psfw_backend_js_params.ajax_nonce,
                    action: 'psfw_add_meta_condition',
                    beforeSend: function() {
                    }
                },
                type: "POST",
                success: function(response) {
                    $(".psfw-custom-field-inner-wrap").append(response);
                }
            });
        });
        /*
         * Insert multiple taxonomy condition
         */
        $('.psfw-add-tax-condition').click(function() {
            var post_type = $('.psfw-product-type').val();
            $.ajax({
                url: psfw_backend_js_params.ajax_url,
                data: {
                    _wpnonce: psfw_backend_js_params.ajax_nonce,
                    action: 'psfw_add_tax_condition',
                    post_type: post_type
                },
                type: "POST",
                success: function(response) {
                    $(".psfw-tax-inner-wrap").append(response);
                }
            });
        });

        //radio button show and hide for post type's post
        $('.psfw-select-post-type').click(function() {
            var value = $(this).val();
            if (value == 'single_post_type') {
                $('.psfw-single-post-type-wrap').show();
                $('.psfw-multiple-post-type-wrap').hide();
            } else {
                $('.psfw-single-post-type-wrap').hide();
                $('.psfw-multiple-post-type-wrap').show();
            }
        });

        /*
         * Uplaod slider image
         */
        $('body').on('click', '.psfw-upload-slider-button', function(e) {
            e.preventDefault();
            var btnClicked = $(this);
            var image = wp.media({
                title: 'Insert Gallery Images',
                button: {text: 'Insert Gallery Images'},
                library: {type: 'image'},
                multiple: "toggle"
            }).open()
                    .on('select', function() {
                        var uploaded_image = image.state().get('selection');
                        uploaded_image.map(function(attachment) {
                            attachment = attachment.toJSON();
                            var image_url = attachment.url;
                            var data = {
                                'action': 'psfw_slider_images',
                                '_wpnonce': psfw_backend_js_params.ajax_nonce,
                                'image_url': image_url
                            };
                            $.ajax({
                                url: psfw_backend_js_params.ajax_url,
                                data: data,
                                type: "POST",
                                success: function(response) {
                                    $('.psfw-slider-image-collection').append(response);
                                    $('.psfw-slider-image-collection').sortable({
                                        handle: ".psfw-sort-slider-image",
                                        containment: "parent"
                                    });
                                }
                            });
                        });
                    });
        });
        $('.psfw-slider-image-collection').sortable({
            handle: ".psfw-sort-slider-image",
            containment: "parent"
        });
        /*
         * Show && hide custom field query
         */
        $('.psfw-custom-field-type').change(function() {
            if ($(this).val() === "single_field") {
                $(".psfw-single-custom-wrapper").show();
                $(".psfw-multiple-custom-field-wrap").hide();
            } else {
                $(".psfw-multiple-custom-field-wrap").show();
                $(".psfw-single-custom-wrapper").hide();
            }
        }
        );
        var selected_field = $(".psfw-custom-field-type option:selected").val();
        if (selected_field === "single_field") {
            $(".psfw-multiple-custom-field-wrap").hide();
            $(".psfw-single-custom-wrapper").show();
        } else {
            $(".psfw-multiple-custom-field-wrap").show();
            $(".psfw-single-custom-wrapper").hide();
        }
        /*
         * Show && hide meta value type
         */
        $('.psfw-meta-value-type').change(function() {
            if ($(this).val() === "string") {
                $('.psfw-meta-value-wrap').show();
                $(".psfw-meta-number-wrap").hide();
            } else {
                $(".psfw-meta-number-wrap").show();
                $('.psfw-meta-value-wrap').hide();
            }
        }
        );
        var selected_meta = $(".psfw-meta-value-type option:selected").val();
        if (selected_meta === "string") {
            $('.psfw-meta-value-wrap').show();
            $(".psfw-meta-number-wrap").hide();
        } else {
            $(".psfw-meta-number-wrap").show();
            $('.psfw-meta-value-wrap').hide();
        }
        /*
         * Menu Tab
         */
        $('.psfw-tab-tigger').click(function() {
            $('.psfw-tab-tigger').removeClass('psfw-active');
            $(this).addClass('psfw-active');
            var active_tab_key = $('.psfw-tab-tigger.psfw-active').data('menu');
            $('.psfw-settings-wrap').removeClass('psfw-active-container');
            $('.psfw-settings-wrap[data-menu-ref="' + active_tab_key + '"]').addClass('psfw-active-container');
        });
        /*
         * Post Menu Tab
         */
        $('.psfw-query-tigger').click(function() {
            $('.psfw-query-tigger').removeClass('psfw-query-active');
            $(this).addClass('psfw-query-active');
            var active_post_key = $('.psfw-query-tigger.psfw-query-active').data('menu');
            $('.psfw-query-setting-wrap').removeClass('psfw-active-field');
            $('.psfw-query-setting-wrap[data-menu-ref="' + active_post_key + '"]').addClass('psfw-active-field');
        });
        /*
         * Usage Tab
         */
        $('.psfw-usage-trigger').click(function() {
            $('.psfw-usage-trigger').removeClass('psfw-usage-active');
            $(this).addClass('psfw-usage-active');
            var active_tab_key = $('.psfw-usage-trigger.psfw-usage-active').data('usage');
            $('.psfw-usage-post').hide();
            $('.psfw-usage-post[data-usage-ref="' + active_tab_key + '"]').show();
        });
        /*
         * Checked button for metadata
         */

        $('.psfw-show-category').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });
        $('.psfw-show-tag').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });
        $('.psfw-show-author').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });
        $('.psfw-show-comment').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });
        $('.psfw-show-custom-relation').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
                $('.psfw-relation-main-wrap').show();
            } else {
                $(this).val('0');
                $('.psfw-relation-main-wrap').hide();
            }
        });
        $('.psfw-show-taxonomy-relation').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
                $('.psfw-taxonomy-main-wrap').show();
            } else {
                $(this).val('0');
                $('.psfw-taxonomy-main-wrap').hide();
            }
        });
        $('.psfw-display-filter').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
                $('.psfw-filter-enable-wrap').show();
            } else {
                $(this).val('0');
                $('.psfw-filter-enable-wrap').hide();
            }
        });
        $('.psfw-display-pagination').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
                $('.psfw-pagination-wrapper').show();
            } else {
                $(this).val('0');
                $('.psfw-pagination-wrapper').hide();
            }
        });
        $('.psfw-fetch-custom-field-post').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
                $('.psfw-custom-field-wrapper').show();
            } else {
                $(this).val('0');
                $('.psfw-custom-field-wrapper').hide();
            }
        });
        $('.psfw-show-popular-relation').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
                $('.psfw-popular-inner-wrap').show();
            } else {
                $(this).val('0');
                $('.psfw-popular-inner-wrap').hide();
            }
        });
        $('.psfw-show-keyword-relation').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
                $('.psfw-keyword-inner-wrap').show();
            } else {
                $(this).val('0');
                $('.psfw-keyword-inner-wrap').hide();
            }
        });
        $('body').on('click', '.psfw-show-type-filter', function() {
            if ($(this).is(':checked')){
                $(this).closest('.psfw-post-field-wrap').find('.psfw-show-type-filter').val('1');
                $(this).closest('.psfw-post-field-wrap').find('.psfw-type-filter-wrap').show();
            } else {
                $(this).closest('.psfw-post-field-wrap').find('.psfw-show-type-filter').val('0');
                $(this).closest('.psfw-post-field-wrap').find('.psfw-type-filter-wrap').hide();
            }

        });
        $('body').on('click', '.psfw-show-operator', function() {
            if ($(this).is(':checked')){
                $(this).closest('.psfw-post-field-wrap').find('.psfw-show-operator').val('1');
                $(this).closest('.psfw-post-field-wrap').find('.psfw-operator-inner-wrap').show();
            } else {
                $(this).closest('.psfw-post-field-wrap').find('.psfw-show-operator').val('0');
                $(this).closest('.psfw-post-field-wrap').find('.psfw-operator-inner-wrap').hide();
            }
        });

        /*
         *  Social media share checked value
         */
        $('.psfw-show-facebook-share').click(function() {
            if ($(this).is(':checked')){
                $('.psfw-show-facebook-value').val('1');
            } else {
                $('.psfw-show-facebook-value').val('0');
            }
        });
        $('.psfw-show-twitter-share').click(function() {
            if ($(this).is(':checked')){
                $('.psfw-show-twitter-value').val('1');
            } else {
                $('.psfw-show-twitter-value').val('0');
            }
        });
        $('.psfw-show-google-share').click(function() {
            if ($(this).is(':checked')){
                $('.psfw-show-google-value').val('1');
            } else {
                $('.psfw-show-google-value').val('0');
            }
        });
        $('.psfw-show-linkedin-share').click(function() {
            if ($(this).is(':checked')){
                $('.psfw-show-linkedin-value').val('1');
            } else {
                $('.psfw-show-linkedin-value').val('0');
            }
        });
        $('.psfw-show-mail-share').click(function() {
            if ($(this).is(':checked')){
                $('.psfw-show-mail-value').val('1');
            } else {
                $('.psfw-show-mail-value').val('0');
            }
        });
        /*
         * Show && hide layout settings
         */
        $('.psfw-select-layout').change(function() {
            if ($(this).val() === "carousel") {
                $('.psfw-list-setting-wrap').hide();
                $('.psfw-masonry-setting-wrap').hide();
                $('.psfw-slider-setting-wrap').hide();
                $('.psfw-carousel-setting-wrap').show();
                $('.psfw-grid-setting-wrap').hide();
                $('.psfw-background-image-setting').hide();
                $('.psfw-slider-option-block').show();
                $('.psfw-column-settings-wrap').hide();
            } else{
                $('.psfw-list-setting-wrap').hide();
                $('.psfw-masonry-setting-wrap').hide();
                $('.psfw-slider-setting-wrap').show();
                $('.psfw-grid-setting-wrap').hide();
                $('.psfw-carousel-setting-wrap').hide();
                $('.psfw-background-image-setting').hide();
                $('.psfw-slider-option-block').show();
                $('.psfw-column-settings-wrap').hide();
            }
        });
        var layout_type = $(".psfw-select-layout option:selected").val();
        if (layout_type === "carousel") {
            $('.psfw-list-setting-wrap').hide();
            $('.psfw-masonry-setting-wrap').hide();
            $('.psfw-slider-setting-wrap').hide();
            $('.psfw-grid-setting-wrap').hide();
            $('.psfw-carousel-setting-wrap').show();
            $('.psfw-background-image-setting').hide();
            $('.psfw-slider-option-block').show();
            $('.psfw-column-settings-wrap').hide();
        }else {
            $('.psfw-list-setting-wrap').hide();
            $('.psfw-masonry-setting-wrap').hide();
            $('.psfw-slider-setting-wrap').show();
            $('.psfw-grid-setting-wrap').hide();
            $('.psfw-carousel-setting-wrap').hide();
            $('.psfw-background-image-setting').hide();
            $('.psfw-slider-option-block').show();
            $('.psfw-column-settings-wrap').hide();
        }
        /*
         * Show && hide orderby meta keys
         */
        $('.psfw-select-orderby').change(function() {
            if ($(this).val() === "meta_value" || $(this).val() === "meta_value_num"){
                $('.psfw-orderby-meta-warp').show();
            } else {
                $('.psfw-orderby-meta-warp').hide();
            }
        });
        var orderby_type = $(".psfw-select-orderby option:selected").val();
        if (orderby_type === "meta_value" || orderby_type === "meta_value_num") {
            $('.psfw-orderby-meta-warp').show();
        } else {
            $('.psfw-orderby-meta-warp').hide();
        }

        var query_type = $(".psfw-taxonomy-queries-type").val();
        if (query_type === "simple-taxonomy") {
            $('.psfw-terms-wrap').show();
            $('.psfw-multiple-terms-wrap').hide();
            $('.psfw-simple-terms-wrap').show();
        } else {
            $('.psfw-terms-wrap').show();
            $('.psfw-multiple-terms-wrap').show();
            $('.psfw-simple-terms-wrap').hide();
        }

        /**
         * blog query remove
         *
         */

        $('body').on('click', '.psfw-delete-query', function() {
            var delete_term = confirm('Are you sure you want to delete this taxonomy condition?');
            if (delete_term) {
                $(this).closest('.psfw-each-taxonomy-wrap').fadeOut(500, function() {
                    $(this).remove();
                });
            }
        });
        $('body').on('click', '.psfw-delete-meta-query', function() {
            var delete_term = confirm('Are you sure you want to delete this meta condition?');
            if (delete_term) {
                $(this).closest('.psfw-each-meta-container-wrap').fadeOut(500, function() {
                    $(this).remove();
                });
            }
        });
        $('body').on('click', '.psfw-multiple-meta-keys', function() {
            var value = $(this).val();
            if (value === 'pre-available') {
                $(this).closest('.psfw-post-field-wrap').find('.psfw-pre-multiple-key-wrap').show();
                $(this).closest('.psfw-post-field-wrap').find('.psfw-multiple-other-key-wrap').hide();
            } else {
                $(this).closest('.psfw-post-field-wrap').find('.psfw-pre-multiple-key-wrap').hide();
                $(this).closest('.psfw-post-field-wrap').find('.psfw-multiple-other-key-wrap').show();
            }
        });
        //radio button show and hide for meta keys
        $('.psfw-meta-key-type').click(function() {
            var value = $(this).val();
            if (value === 'pre-available') {
                $('.psfw-pre-meta-key-wrap').show();
                $('.psfw-other-meta-wrap').hide();
            } else {
                $('.psfw-pre-meta-key-wrap').hide();
                $('.psfw-other-meta-wrap').show();
            }
        });

        //radio button show and hide for filter terms
        $('.psfw-filter-terms-type').click(function() {
            var value = $(this).val();
            if (value === 'all') {
                $('.psfw-specific-wrap').hide();
            } else {
                $('.psfw-specific-wrap').show();
            }
        });

        //ajax call in post type thickbox
        $('.psfw-filter-taxonomy').change(function() {
            var select_type = $(this).val();
            $.ajax({
                url: psfw_backend_js_params.ajax_url,
                data: {
                    select_type: select_type,
                    _wpnonce: psfw_backend_js_params.ajax_nonce,
                    action: 'psfw_filter_tax_terms'
                },
                type: "POST",
                success: function(response) {
                    $(".psfw-specific-wrap").html(response);
                }
            });
        });
        /**
         * Jquery UI Slider initialization
         *
         * @since 1.0.0
         */
        //radio button show and hide for post type's post
        $('.psfw-post-link').click(function() {
            var value = $(this).val();
            if (value === 'post_link') {
                $('.psfw-custom-link-wrap').hide();
            } else {
                $('.psfw-custom-link-wrap').show();
            }
        });
        $('.psfw-show-social-share').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
                $('.psfw-social-container').show();
            } else {
                $(this).val('0');
                $('.psfw-social-container').hide();
            }
        });
        $('.psfw-show-facebook-share').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });
        $('.psfw-show-twitter-share').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });
        $('.psfw-show-google-share').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });
        $('.psfw-show-linkedin-share').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });
        $('.psfw-show-mail-share').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });
        $('.psfw-show-pinterest-share').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });
        $('.psfw-show-vk-share').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });
        /**
         * logo Item Remove
         *
         */
        $('body').on('click', '.psfw-delete-slider-image', function() {
            var delete_image = confirm('Are you sure you want to delete this image?');
            if (delete_image) {
                $(this).closest('.psfw-slider-wrap').fadeOut(500, function() {
                    $(this).remove();
                });
            }
        });
        /*
         * Slide Content show & hide
         */
        $('.psfw-content-template').change(function() {
            if ($(this).val() === "template-5" || $(this).val() === "template-9") {
                $('.psfw-content-slides-container').hide();
            } else {
                $('.psfw-content-slides-container').show();
            }
        });
        var template_type = $(".psfw-content-template option:selected").val();
        if (template_type === "template-5" || template_type === "template-9") {
            $('.psfw-content-slides-container').hide();
        } else {
            $('.psfw-content-slides-container').show();
        }
        /*
         * Template preview
         */
        //lightbox template preview
        $(".psfw-lightbox-common").first().addClass("lightbox-active");
        $('.psfw-lightbox-template').on('change', function() {
            var template_value = $(this).val();
            var array_break = template_value.split('-');
            var current_id = array_break[1];
            $('.psfw-lightbox-common').hide();
            $('#psfw-lightbox-demo-' + current_id).show();
        });
        if ($(".psfw-lightbox-template option:selected").length > 0) {
            var grid_view = $(".psfw-lightbox-template option:selected").val();
            var array_break = grid_view.split('-');
            var current_id1 = array_break[1];
            $('.psfw-lightbox-common').hide();
            $('#psfw-lightbox-demo-' + current_id1).show();
        }

        //Slider preview
        $(".psfw-slider-common").first().addClass("slider-active");
        $('.psfw-slider-template').on('change', function() {
            var template_value = $(this).val();
            var array_break = template_value.split('-');
            var current_id = array_break[1];
            $('.psfw-slider-common').hide();
            $('#psfw-slider-demo-' + current_id).show();
        });

        if ($(".psfw-slider-template option:selected").length > 0) {
            var list_view = $(".psfw-slider-template option:selected").val();
            var array_break = list_view.split('-');
            var current_id1 = array_break[1];
            $('.psfw-slider-common').hide();
            $('#psfw-slider-demo-' + current_id1).show();
        }

        //Carousel preview
        $(".psfw-carousel-common").first().addClass("carousel-active");
        $('.psfw-carousel-template').on('change', function() {
            var template_value = $(this).val();
            var array_break = template_value.split('-');
            var current_id = array_break[1];
            $('.psfw-carousel-common').hide();
            $('#psfw-carousel-demo-' + current_id).show();
        });

        if ($(".psfw-carousel-template option:selected").length > 0) {
            var carousel_view = $(".psfw-carousel-template option:selected").val();
            var array_break = carousel_view.split('-');
            var current_id1 = array_break[1];
            $('.psfw-carousel-common').hide();
            $('#psfw-carousel-demo-' + current_id1).show();
        }

        //Filter preview
        $(".psfw-filter-common").first().addClass("filter-active");
        $('.psfw-filter-template').on('change', function() {
            var template_value = $(this).val();
            var array_break = template_value.split('-');
            var current_id = array_break[1];
            $('.psfw-filter-common').hide();
            $('#psfw-filter-demo-' + current_id).show();
        });

        if ($(".psfw-filter-template option:selected").length > 0) {
            var carousel_view = $(".psfw-filter-template option:selected").val();
            var array_break = carousel_view.split('-');
            var current_id1 = array_break[1];
            $('.psfw-filter-common').hide();
            $('#psfw-filter-demo-' + current_id1).show();
        }

        $('.psfw-show-price').click(function() {
            var product = $('.psfw-product-type').val();
            if ($(this).is(':checked')){
                if (product === 'download') {
                    $(this).val('1');
                    $('.psfw-price-wrapper').hide();
                } else {
                    $(this).val('1');
                    $('.psfw-price-wrapper').show();
                }
            } else{
                $(this).val('0');
                $('.psfw-price-wrapper').hide();
            }
        });

        $('.psfw-show-review').click(function() {
            var product = $('.psfw-product-type').val();
            if ($(this).is(':checked')) {
                $(this).val('1');
            } else{
                $(this).val('0');
            }
        });
        
        $('.psfw-show-cart').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
                $('.psfw-cart-wrapper').show();
            } else{
                $(this).val('0');
                $('.psfw-cart-wrapper').hide();
            }
        });

        $('.psfw-show-button-two').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
                $('.psfw-detail-link-wrapper').show();
            } else {
                $(this).val('0');
                $('.psfw-detail-link-wrapper').hide();
            }
        });

        //Banner image upload
        $('body').on('click', '.psfw-banner-url-button', function() {
        //e.preventDefault();
            var btnClicked = $(this);
            var image = wp.media({
                title: 'Insert Banner Image',
                button: {text: 'Insert Banner Image'},
                library: {type: 'image'},
                multiple: false
            }).open()
                    .on('select', function() {
                        var uploaded_image = image.state().get('selection').first();
                        var image_url = uploaded_image.toJSON().url;
                        $(btnClicked).closest('.psfw-post-option-wrap').find('.psfw-banner-image').attr('src', image_url);
                        $(btnClicked).closest('.psfw-post-option-wrap').find('.psfw-banner-image-url').val(image_url);
                        if ($(btnClicked).closest('.psfw-post-option-wrap').find('.psfw-banner-image-url').val(image_url) != '') {
                            $(btnClicked).closest('.psfw-post-option-wrap').find('.psfw-image-preview').show();
                        } else {
                            $(btnClicked).closest('.psfw-post-option-wrap').find('.psfw-image-preview').hide();
                        }
                    });
        });
        //Show Title
        $('.psfw-show-title').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });
        //Show content
        $('.psfw-show-content').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
                $('.psfw-product-desc-wrapper').show();
            } else {
                $(this).val('0');
                $('.psfw-product-desc-wrapper').hide();
            }
        });
        //Dispaly product link
        $('.psfw-show-link-title').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });
        //Dispaly product link
        $('.psfw-show-link-image').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });
        //Dispaly rating
        $('.psfw-show-rating').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });
        //Dispaly wishlist
        $('.psfw-show-wishlist').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });
        $('.psfw-show-button-one').click(function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
                $('.psfw-buy-wrapper').show();
            } else {
                $(this).val('0');
                $('.psfw-buy-wrapper').hide();
            }
        });
        /*
         * Common Custom link Button show & hide
         */
        $('.psfw-button-one-link-type').change(function() {
            if ($(this).val() === 'common_custom_link') {
                $('.psfw-common-link-wrapper').show();
                $('.psfw-button-one-text-wrap').show();
            } else if ($(this).val() === 'product_add_cart') {
                $('.psfw-common-link-wrapper').hide();
                $('.psfw-button-one-text-wrap').hide();
            } else {
                $('.psfw-common-link-wrapper').hide();
                $('.psfw-button-one-text-wrap').show();
            }
        });
        var button_type = $(".psfw-button-one-link-type option:selected").val();
        if (button_type === 'common_custom_link') {
            $('.psfw-common-link-wrapper').show();
            $('.psfw-button-one-text-wrap').show();
        } else if (button_type === 'product_add_cart') {
            $('.psfw-common-link-wrapper').hide();
            $('.psfw-button-one-text-wrap').hide();
        } else {
            $('.psfw-common-link-wrapper').hide();
            $('.psfw-button-one-text-wrap').show();
        }
        /*
         * Common Custom link Button show & hide
         */
        $('.psfw-button-two-link-type').change(function() {
            if ($(this).val() === 'common_custom_link') {
                $('.psfw-common-two-link-wrapper').show();
                $('.psfw-button-two-text-wrap').show();
            } else if ($(this).val() === 'product_add_cart') {
                $('.psfw-common-two-link-wrapper').hide();
                $('.psfw-button-two-text-wrap').hide();
            } else {
                $('.psfw-common-two-link-wrapper').hide();
                $('.psfw-button-two-text-wrap').show();
            }

        });
        var button_type_two = $(".psfw-button-two-link-type option:selected").val();
        if (button_type_two === 'common_custom_link') {
            $('.psfw-common-two-link-wrapper').show();
            $('.psfw-button-two-text-wrap').show();
        } else if (button_type_two === 'product_add_cart') {
            $('.psfw-common-two-link-wrapper').hide();
            $('.psfw-button-two-text-wrap').hide();
        } else {
            $('.psfw-common-two-link-wrapper').hide();
            $('.psfw-button-two-text-wrap').show();
        }

        //Icon Picker
        $('.psfw-icon-picker').iconPicker();
        //radio button show and hide for ribbon type
        $('body').on('click', '.psfw-ribbon-type', function() {
            var value = $(this).val();
            if (value === 'text') {
                $(this).closest('.psfw-post-option-wrap').find('.psfw-ribbon-text-wrap').show();
                $(this).closest('.psfw-post-option-wrap').find('.psfw-ribbon-icon-wrap').hide();
            } else if (value === 'icon') {
                $(this).closest('.psfw-post-option-wrap').find('.psfw-ribbon-text-wrap').hide();
                $(this).closest('.psfw-post-option-wrap').find('.psfw-ribbon-icon-wrap').show();
            } else {
                $(this).closest('.psfw-post-option-wrap').find('.psfw-ribbon-text-wrap').hide();
                $(this).closest('.psfw-post-option-wrap').find('.psfw-ribbon-icon-wrap').hide();
            }
        });
        /*
         * Checked button for ribbon
         */
        $('body').on('click', '.psfw-show-ribbon', function() {
            if ($(this).is(':checked')) {
                $(this).closest('.psfw-post-field-wrap').find('.psfw-show-ribbon').val('1');
                $(this).closest('.psfw-ribbon-outer-wrap').find('.psfw-ribbon-settings').show();
            } else {
                $(this).closest('.psfw-post-field-wrap').find('.psfw-show-ribbon').val('0');
                $(this).closest('.psfw-ribbon-outer-wrap').find('.psfw-ribbon-settings').hide();
            }
        });
        /*
         * Checked button for lightbox
         */
        $('.psfw-show-lightbox').click(function() {

            if ($(this).is(':checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });
        /*
         * Ribbon template preview
         */
        $('body').on('change', '.psfw-ribbon-template', function() {
            var template_value = $(this).val();
            var array_break = template_value.split('-');
            var current_id = array_break[1];
            $(this).closest('.psfw-post-field-wrap').find('.psfw-ribbon-common').hide();
            $(this).closest('.psfw-post-field-wrap').find('#psfw-ribbon-demo-' + current_id).show();
        });
        /*
         * Individual Ribbon template preview
         */
        $('body').on('change', '.psfw-indi-ribbon-template', function() {
            var template_value = $(this).val();
            if (template_value === 'default' || template_value === 'none') {
                $(this).closest('.psfw-post-field-wrap').find('.psfw-ribbon-common').hide();
                $(this).closest('.psfw-ribbon-outer-wrap').find('.psfw-ribbon-individual-settings').hide();
            } else {
                var array_break = template_value.split('-');
                var current_id = array_break[1];
                $(this).closest('.psfw-post-field-wrap').find('.psfw-ribbon-common').hide();
                $(this).closest('.psfw-post-field-wrap').find('#psfw-ribbon-demo-' + current_id).show();
                $(this).closest('.psfw-ribbon-outer-wrap').find('.psfw-ribbon-individual-settings').show();
            }
        });

        if ($('.psfw-ribbon-outer-wrap').closest(".psfw-indi-ribbon-template").find("option:selected").length > 0) {
            var indi_ribbion = $('.psfw-ribbon-outer-wrap').closest(".psfw-indi-ribbon-template").find("option:selected").val();

            if (indi_ribbion !== 'default' || indi_ribbion !== 'none') {
                var array_break = indi_ribbion.split('-');
                var current_id = array_break[1];
                $('.psfw-indi-ribbon-template').closest('.psfw-post-field-wrap').find('.psfw-ribbon-common').hide();
                $('.psfw-indi-ribbon-template').closest('.psfw-post-field-wrap').find('#psfw-ribbon-demo-' + current_id).show();
                $('.psfw-indi-ribbon-template').closest('.psfw-ribbon-outer-wrap').find('.psfw-ribbon-individual-settings').show();
            }
        }

        /*
         * Upload Swap imgae
         *
         */
        //image upload
        $('.psfw-swap-image-url-button').on('click', function(e) {
            e.preventDefault();
            var btnClicked = $(this);
            var image = wp.media({
                title: 'Insert Product Image For Swap',
                button: {text: 'Insert Product Image For Swap'},
                library: {type: 'image'},
                multiple: false
            }).open()
                    .on('select', function(e) {
                        var uploaded_image = image.state().get('selection').first();
                        //console.log(uploaded_image);
                        var image_url = uploaded_image.toJSON().url;
                        $('.psfw-swap-image').attr('src', image_url);
                        $('.psfw-swap-image-url').val(image_url);
                        if ($('.psfw-swap-image-url').val(image_url) !== '') {
                            $('.psfw-image-preview').show();
                        } else {
                            $('.psfw-image-preview').hide();
                        }
                    });
        });

        $('body').on('change', '.psfw-ribbon-template-ofs', function() {
            var template_value = $(this).val();
            var array_break = template_value.split('-');
            var current_id = array_break[1];
            $(this).closest('.psfw-post-field-wrap').find('.psfw-ribbon-common-ofs').hide();
            $(this).closest('.psfw-post-field-wrap').find('#psfw-ribbon-demo-' + current_id+'-ofs').show();
        });

        $('body').on('change', '.psfw-ofs_position', function() {
            var position_value = $(this).val();
            var current_id = position_value;
            $(this).closest('.psfw-post-field-wrap').find('.psfw-ribbon-position-common-ofs').hide();
            $(this).closest('.psfw-post-field-wrap').find('#psfw-ribbon-position-demo-' + current_id+'-ofs').show();
        });

        //Banner for out of stock product
       
        $('body').on('click', '.psfw_label_ofsp', function() {
            if ($(this).is(':checked')){
                $(this).val('1');
                $('.psfw-ofs').show();
            } else{
                $(this).val('0');
                $('.psfw-ofs').hide();
            }
        });

    //$('.psfw-slider-template').change(function(){
    $('.psfw-slider-template').on('change', function() {
        var template_number = $('.psfw-slider-template').val();
        //alert(template_number );
        if(template_number=="template-3" || template_number=="template-5" || template_number=="template-7" || template_number=="template-14"){
            $('.psfw-content-align-wrapper').hide();
        }else{
            $('.psfw-content-align-wrapper').show();
        }
    });

    });
}(jQuery));