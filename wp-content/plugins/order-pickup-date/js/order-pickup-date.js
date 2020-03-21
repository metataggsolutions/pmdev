jQuery( document ).ready(function() { 
    var show = jQuery( "#orddd_show_datepicker" ).val();
    var startDaysDisabled = [];
    var option_str = {}

    option_str[ 'beforeShowDay' ] = chd;
    option_str[ 'firstDay' ] = parseInt( jQuery( "#orddd_start_of_week" ).val() );
    if( show == "datepicker" ){
        option_str[ "showButtonPanel" ] = true; 
        option_str[ "closeText" ] = jsL10n.clearText;
    }

    option_str[ 'onClose' ] = function( dateStr, inst ) {
        if ( dateStr != "" ) {
            var monthValue = inst.selectedMonth+1;
            var dayValue = inst.selectedDay;
            var yearValue = inst.selectedYear;
            var all = dayValue + "-" + monthValue + "-" + yearValue;
            jQuery( "#h_pickupdate" ).val( all );var hourValue = jQuery( ".ui_tpicker_time" ).html();
            jQuery( "#orddd_time_settings_selected" ).val( hourValue );
            var event = arguments.callee.caller.caller.arguments[0];
            // If "Clear" gets clicked, then really clear it
            if( typeof( event ) !== "undefined" ) {
                if ( jQuery( event.delegateTarget ).hasClass( "ui-datepicker-close" ) ) {
                    jQuery( this ).val( "" ); 
                    jQuery( "#h_pickupdate" ).val( "" );
                    jQuery( "#pickup_time_slot" ).prepend( "<option value=\"select\">" + jsL10n.selectText + "</option>" );
                    jQuery( "#pickup_time_slot" ).children( "option:not(:first)" ).remove();
                    jQuery( "#pickup_time_slot" ).attr( "disabled", "disabled" );
                    jQuery( "#pickup_time_slot" ).attr( "style", "cursor: not-allowed !important" );
                    jQuery( "#pickup_time_slot_field" ).css({ opacity: "0.5" });
                }
            }
            jQuery( "body" ).trigger( "update_checkout" );
        }
        jQuery( '#' + jQuery( "#orddd_field_name" ).val() ).blur();
    };

    if ( "1" == jQuery( "#orddd_is_admin" ).val() ) {
        option_str[ 'onSelect' ] = show_admin_times;
    } else {
        option_str[ 'onSelect' ] = show_pickup_times;    
    }

    var c = get_datepicker_options();
    option_str = jsonConcat( option_str, c );

    if( show == 'datetimepicker' ) {
        jQuery( '#' + jQuery( "#orddd_field_name" ).val() ).val( "" ).datetimepicker( option_str ).focus( function ( event ) {
            jQuery(this).trigger( "blur" );
            jQuery.datepicker.afterShow( event );
        });    
    } else {
        jQuery( '#' + jQuery( "#orddd_field_name" ).val() ).val( "" ).datepicker( option_str ).focus( function ( event ) {
            jQuery(this).trigger( "blur" );
            jQuery.datepicker.afterShow( event );
        });    
    }
    
    jQuery( document ).on( "change", "#pickup_time_slot", function() {
        jQuery( "body" ).trigger( "update_checkout" );
    });

    if ( jQuery( "#orddd_field_note_text" ).val() != '' ) {
        jQuery( "#e_pickupdate_field" ).append( "<small class='orddd_field_note'>" + jQuery( "#orddd_pickup_field_note_text" ).val() + "</small>" );
    }

    if ( jQuery( "#orddd_enable_shipping_based_delivery" ).val() == 'on' ) {

        if( "yes" == jQuery( "#orddd_shipping_method_based_settings" ).val() ) {
            jQuery(document).on( "change", "input[name=\"shipping_method[0]\"]", function() {
                load_pickup_date();
            });
        
            jQuery(document).on( "change", "select[name=\"shipping_method[0]\"]", function() {
                load_pickup_date();
            });

            jQuery(document).on( "change", '#ship-to-different-address input', function() {
                load_pickup_date();
            });
        }
        
        if ( jQuery( "#orddd_enable_autofill_of_delivery_date" ).val() == 'on' ) {
            jQuery(document).on( "change", "input[name=\"shipping_method[0]\"]", function() {
                orddd_autofil_date_time();
            });
            
            jQuery(document).on( "change", "select[name=\"shipping_method[0]\"]", function() {
                orddd_autofil_date_time();
            });

            jQuery(document).on( "change", '#ship-to-different-address input', function() {
                orddd_autofil_date_time();
            });
        }
    }

    if( '1' == jQuery( "#orddd_is_admin" ).val() ) {
        jQuery( '#' + jQuery( "#orddd_field_name" ).val() ).width( "150px" );
        jQuery( '#' + jQuery( "#orddd_field_name" ).val() ).attr( "readonly", true );
    }

    var formats = ["d.m.y", "d MM, yy","MM d, yy"];
    jQuery.extend( jQuery.datepicker, { afterShow: function( event ) {
        jQuery.datepicker._getInst( event.target ).dpDiv.css( "z-index", 9999 );
            if( jQuery( "#orddd_number_of_months" ).val() == "1" && '1' == jQuery( "#orddd_is_admin" ).val() ) {
                jQuery.datepicker._getInst( event.target ).dpDiv.css( "width", "17em" );
            } else if ( jQuery( "#orddd_number_of_months" ).val() == "1" ) {
                jQuery.datepicker._getInst( event.target ).dpDiv.css( "width", "300px" );
            } else {
                jQuery.datepicker._getInst( event.target ).dpDiv.css( "width", "40em" );
            }
        }
    });
    
    jQuery(document).on( 'change', '.address-field input.input-text, .update_totals_on_change input.input-text, .address-field select', function( e ) {
        if( jQuery( "#orddd_enable_shipping_based_delivery" ).val() == "on" ) {
            jQuery( "#e_pickupdate" ).datepicker( "option", "disabled", true );    
            jQuery( "#pickup_time_slot" ).attr( "disabled", "disabled" );
        }
    } );

    var old_zone_id = "";
    jQuery(document).on( "ajaxComplete", function( event, xhr, options ) {
        var new_billing_postcode = jQuery( "#billing_postcode" ).val();
        var new_billing_country = jQuery( "#billing_country" ).val();
        var new_billing_state = jQuery( "#billing_state" ).val();

        var new_shipping_postcode = jQuery( "#shipping_postcode" ).val();
        var new_shipping_country = jQuery( "#shipping_country" ).val();
        var new_shipping_state = jQuery( "#shipping_state" ).val();

        if( options.url.indexOf( "wc-ajax=update_order_review" ) !== -1 ) {
            if( xhr.statusText != "abort" ) {
                var is_shipping_checked = jQuery( '#ship-to-different-address input' ).is( ":checked" );
                if( jQuery( "#orddd_enable_shipping_based_delivery" ).val() == "on" ) {
                    var data = {
                        action: 'orddd_get_zone_id',
                        billing_postcode: new_billing_postcode,
                        billing_country: new_billing_country,
                        billing_state: new_billing_state,
                        shipping_postcode: new_shipping_postcode,
                        shipping_country: new_shipping_country,
                        shipping_state: new_shipping_state,
                        shipping_checkbox: is_shipping_checked
                    };

                    jQuery.post( jQuery( '#orddd_admin_url' ).val() + "admin-ajax.php", data, function( response ) {    
                        var zone_id = 0;
                        if( "" != response ) {
                            var zone_shipping_details = response.split('-');
                            var zone_id = zone_shipping_details[ 0 ];
                            var orddd_shipping_id = zone_shipping_details[ 1 ];
                        }
                        jQuery( "#orddd_zone_id" ).val( zone_id );
                        jQuery( "#orddd_shipping_id" ).val( orddd_shipping_id );
                        if ( old_zone_id != zone_id ) {
                            if( "yes" == jQuery( "#orddd_shipping_method_based_settings" ).val() ) {
                                jQuery( "#e_pickupdate" ).datepicker( "option", "disabled", false );
                                jQuery( "#pickup_time_slot" ).removeAttr( "disabled", "disabled" );
                                load_pickup_date();
                                if ( jQuery( "#orddd_enable_autofill_of_delivery_date" ).val() == "on" ) {
                                    orddd_autofil_date_time();
                                }
                            } else {
                                jQuery( "#e_pickupdate" ).datepicker( "option", "disabled", false );    
                                jQuery( "#pickup_time_slot" ).removeAttr( "disabled", "disabled" );
                            }
                            old_zone_id = zone_id;
                        } else {
                            jQuery( "#e_pickupdate" ).datepicker( "option", "disabled", false );    
                            jQuery( "#pickup_time_slot" ).removeAttr( "disabled", "disabled" );
                        }
                    });
                }
            }
        }
    });
    
});

function get_datepicker_options() {
    var options = jQuery( "#orddd_option_str" ).val();
    var df_arr = options.split( "dateFormat: '" );
    var df_arr2 = df_arr[1].split("'");
    var df_dateformat = df_arr2[0];
    var before_df_arr = df_arr[0].split( ', ' );
    before_df_arr[6] = "dateFormat:'" + df_dateformat + "'";
    var c = {};
    jQuery.each( before_df_arr, function( key, value ) {
        if( '' != value && 'undefined' != typeof( value ) ) {
            var split_value = value.split( ":" );
            if( split_value.length != '2' ) {
                var str = split_value[1] + ":" + split_value[2];
                c[ split_value[0] ] = str.trim().replace( /'/g, "" );
            } else if( 'hourMax' == split_value[0] || 'hourMin' == split_value[0] || 'minuteMin' == split_value[0] || 'stepMinute' == split_value[0] ) {
                c[ split_value[0] ] = parseInt( split_value[1].trim() );  
            } else if( 'beforeShow' == split_value[0] ) {
                if( "on" == jQuery( "#orddd_same_day_delivery" ).val() || "on" == jQuery( "#orddd_next_day_delivery" ).val() ) {
                    c[ split_value[0] ] = maxdt;
                } else {
                    c[ split_value[0] ] = avd;
                }  
            } else {
                c[ split_value[0] ] = split_value[1].trim().replace( /'/g, "" );    
            }    
        }
    });
    return c;
}

function load_hidden_vars( value ) {
    if( typeof value.orddd_recurring_days != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_recurring_days", name: "orddd_recurring_days", type: "hidden", value: value.orddd_recurring_days }).appendTo( "#orddd_dynamic_hidden_vars" );
    }
    if( typeof value.orddd_specific_delivery_dates != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_specific_delivery_dates", name: "orddd_specific_delivery_dates", type: "hidden", value: value.orddd_specific_delivery_dates }).appendTo( "#orddd_dynamic_hidden_vars" );
    }
    if( typeof value.orddd_weekday_0 != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_weekday_0", name: "orddd_weekday_0", type: "hidden", value: value.orddd_weekday_0  }).appendTo( "#orddd_dynamic_hidden_vars" );
    }
    if( typeof value.orddd_weekday_1 != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_weekday_1", name: "orddd_weekday_1", type: "hidden", value: value.orddd_weekday_1 }).appendTo( "#orddd_dynamic_hidden_vars" );
    }
    if( typeof value.orddd_weekday_2 != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_weekday_2", name: "orddd_weekday_2", type: "hidden", value: value.orddd_weekday_2  }).appendTo( "#orddd_dynamic_hidden_vars" );
    }
    if( typeof value.orddd_weekday_3 != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_weekday_3", name: "orddd_weekday_3", type: "hidden", value: value.orddd_weekday_3  }).appendTo( "#orddd_dynamic_hidden_vars" );
    }
    if( typeof value.orddd_weekday_4 != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_weekday_4", name: "orddd_weekday_4", type: "hidden", value: value.orddd_weekday_4  }).appendTo( "#orddd_dynamic_hidden_vars" );
    }
    if( typeof value.orddd_weekday_5 != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_weekday_5", name: "orddd_weekday_5", type: "hidden", value: value.orddd_weekday_5 }).appendTo( "#orddd_dynamic_hidden_vars" );
    }
    if( typeof value.orddd_weekday_6 != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_weekday_6", name: "orddd_weekday_6", type: "hidden", value: value.orddd_weekday_6 }).appendTo( "#orddd_dynamic_hidden_vars" );
    }
    if( typeof value.orddd_delivery_dates != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_delivery_dates", name: "orddd_delivery_dates", type: "hidden", value: value.orddd_delivery_dates }).appendTo( "#orddd_dynamic_hidden_vars" );
    }
    if( typeof value.orddd_delivery_date_holidays != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_delivery_date_holidays", name: "orddd_delivery_date_holidays", type: "hidden", value: value.orddd_delivery_date_holidays }).appendTo( "#orddd_dynamic_hidden_vars" );
    }
    if( typeof value.orddd_min_hour != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_min_hour", name: "orddd_min_hour", type: "hidden", value: value.orddd_min_hour }).appendTo( "#orddd_dynamic_hidden_vars" );
    } 
    if( typeof value.orddd_min_minute != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_min_minute", name: "orddd_min_minute", type: "hidden", value: value.orddd_min_minute }).appendTo( "#orddd_dynamic_hidden_vars" );
    }
    if( typeof value.orddd_min_hour_set != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_min_hour_set", name: "orddd_min_hour_set", type: "hidden", value: value.orddd_min_hour_set }).appendTo( "#orddd_dynamic_hidden_vars" );
    }
    if( typeof value.orddd_enable_time_slider != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_enable_time_slider", name: "orddd_enable_time_slider", type: "hidden", value: value.orddd_enable_time_slider }).appendTo( "#orddd_dynamic_hidden_vars" );
    }
    if( typeof value.orddd_current_day != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_current_day", name: "orddd_current_day", type: "hidden", value: value.orddd_current_day  }).appendTo( "#orddd_dynamic_hidden_vars" );
    }
    if( typeof value.orddd_minimumOrderDays != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_minimumOrderDays", name: "orddd_minimumOrderDays", type: "hidden", value: value.orddd_minimumOrderDays }).appendTo( "#orddd_dynamic_hidden_vars" );
    }
    if( typeof value.orddd_number_of_dates != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_number_of_dates", name: "orddd_number_of_dates", type: "hidden", value: value.orddd_number_of_dates }).appendTo( "#orddd_dynamic_hidden_vars" );
    }
    if( typeof value.orddd_lockout_days != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_lockout_days", name: "orddd_lockout_days", type: "hidden", value: value.orddd_lockout_days }).appendTo( "#orddd_dynamic_hidden_vars" );
    }
    if( typeof value.orddd_custom_based_same_day_delivery != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_custom_based_same_day_delivery", name: "orddd_custom_based_same_day_delivery", type: "hidden", value: value.orddd_custom_based_same_day_delivery }).appendTo( "#orddd_dynamic_hidden_vars" );
    }
    if( typeof value.orddd_custom_based_next_day_delivery != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_custom_based_next_day_delivery", name: "orddd_custom_based_next_day_delivery", type: "hidden", value: value.orddd_custom_based_next_day_delivery }).appendTo( "#orddd_dynamic_hidden_vars" );
    }
    if( typeof value.orddd_start_date_for_subscription != "undefined" ) {
        jQuery( "<input>" ).attr({id: "orddd_start_date_for_subscription", name: "orddd_start_date_for_subscription", type: "hidden", value: value.orddd_start_date_for_subscription }).appendTo( "#orddd_dynamic_hidden_vars" );
    }

    if( typeof( jQuery( "#orddd_common_delivery_dates_for_product_category" ).val() ) !== "undefined" ) {
        var specific_dates = jQuery( "#orddd_common_delivery_dates_for_product_category" ).val();
        for( i = 0; i < 7; i++ ) {
            jQuery( "#orddd_weekday_" + i ).remove();
        }
        jQuery( "#orddd_dynamic_hidden_vars #orddd_delivery_dates" ).val( specific_dates );
        jQuery( "#orddd_dynamic_hidden_vars #orddd_recurring_days" ).val( "" );    
        if( typeof jQuery( "#orddd_dynamic_hidden_vars #orddd_specific_delivery_dates" ).val() != 'undefined' ) {
            jQuery( "#orddd_dynamic_hidden_vars #orddd_specific_delivery_dates" ).val( "on" );   
        } else {
            jQuery( "<input>" ).attr({id: "orddd_specific_delivery_dates", name: "orddd_specific_delivery_dates", type: "hidden", value: "on" }).appendTo( "#orddd_dynamic_hidden_vars" );
        }
        
    }

    if( typeof( jQuery( "#orddd_common_delivery_days_for_product_category" ).val() ) !== "undefined" ) {
        var common_delivery_days = jQuery( "#orddd_common_delivery_days_for_product_category" ).val();
         
        var common_delivery_days = jQuery.parseJSON( common_delivery_days );
        if( common_delivery_days == null ) {
            common_delivery_days = [];
        }
        if ( typeof( common_delivery_days[ "orddd_weekday_0" ] ) !== "undefined" ) {
            jQuery( "#orddd_dynamic_hidden_vars #orddd_weekday_0" ).val( "checked" ); 
        } else {
            jQuery( "#orddd_dynamic_hidden_vars #orddd_weekday_0" ).val( "" );
        }

        if ( typeof( common_delivery_days[ "orddd_weekday_1" ] ) !== "undefined" ) {
            jQuery( "#orddd_dynamic_hidden_vars #orddd_weekday_1" ).val( "checked" );
        } else {
            jQuery( "#orddd_dynamic_hidden_vars #orddd_weekday_1" ).val( "" );
        }
        if ( typeof( common_delivery_days[ "orddd_weekday_2" ] ) !== "undefined" ) {
            jQuery( "#orddd_dynamic_hidden_vars #orddd_weekday_2" ).val( "checked" );
        } else {
            jQuery( "#orddd_dynamic_hidden_vars #orddd_weekday_2" ).val( "" );
        }
        if ( typeof( common_delivery_days[ "orddd_weekday_3" ] ) !== "undefined" ) {
            jQuery( "#orddd_dynamic_hidden_vars #orddd_weekday_3" ).val( "checked" );
        } else {
            jQuery( "#orddd_dynamic_hidden_vars #orddd_weekday_3" ).val( "" );
        }
        if ( typeof( common_delivery_days[ "orddd_weekday_4" ] ) !== "undefined" ) {
            jQuery( "#orddd_dynamic_hidden_vars #orddd_weekday_4" ).val( "checked" );
        } else {
            jQuery( "#orddd_dynamic_hidden_vars #orddd_weekday_4" ).val( "" );
        }
        if ( typeof( common_delivery_days[ "orddd_weekday_5" ] ) !== "undefined" ) {
            jQuery( "#orddd_dynamic_hidden_vars #orddd_weekday_5" ).val( "checked" );
        } else {
            jQuery( "#orddd_dynamic_hidden_vars #orddd_weekday_5" ).val( "" );
        }
        if ( typeof( common_delivery_days[ "orddd_weekday_6" ] ) !== "undefined" ) {
            jQuery( "#orddd_dynamic_hidden_vars #orddd_weekday_6" ).val( "checked" );
        } else {
            jQuery( "#orddd_dynamic_hidden_vars #orddd_weekday_6" ).val( "" );
        }
    } else if( 'no' == jQuery( "#orddd_is_days_common" ).val() ) {
        if( typeof jQuery( "#orddd_dynamic_hidden_vars #orddd_specific_delivery_dates" ).val() != 'undefined' ) {
            jQuery( "#orddd_dynamic_hidden_vars #orddd_specific_delivery_dates" ).val( "on" );   
        } else {
            jQuery( "<input>" ).attr({id: "orddd_specific_delivery_dates", name: "orddd_specific_delivery_dates", type: "hidden", value: "on" }).appendTo( "#orddd_dynamic_hidden_vars" );
        }  
        jQuery( "#orddd_dynamic_hidden_vars #orddd_recurring_days" ).val( "" );    
        for( i = 0; i < 7; i++ ) {
            jQuery( "#orddd_dynamic_hidden_vars #orddd_weekday_" + i ).remove();
        }
    }
}

function load_pickup_date() {
	startDaysDisabled = [];
	var string = "", enable_delivery_date = "";  
	var i = 0;
    var method_found = 0;
	var disabled_days_arr = [];
    var shipping_class = jQuery( "#orddd_shipping_class_settings_to_load" ).val();
    
    if ( "1" == jQuery( "#orddd_is_admin" ).val() ) {
        var shipping_method_id = jQuery( "input[name=\"shipping_method_id[]\"]" ).val();
        if( typeof shipping_method_id === "undefined" ) {
            var shipping_method_id = "";
        }
        var shipping_method = jQuery( "select[name=\"shipping_method[" + shipping_method_id + "]\"]" ).find(":selected").val();
        if( typeof shipping_method === "undefined" ) {
            var shipping_method = "";
        }
    } else if( "1" == jQuery( "#orddd_is_account_page" ).val() ) {
        var shipping_method = jQuery( "#shipping_method" ).val();
    } else {
        var shipping_method = jQuery( "input[name=\"shipping_method[0]\"]:checked" ).val();
        if( typeof shipping_method === "undefined" ) {
            var shipping_method = jQuery( "select[name=\"shipping_method[0]\"] option:selected" ).val();
        }
        if( typeof shipping_method === "undefined" ) {
            var shipping_method = jQuery( "input[name=\"shipping_method[0]\"]" ).val();                    
        }
        
        if( typeof shipping_method === "undefined" ) {
            var shipping_method = jQuery( "#orddd_shipping_id" ).val();                    
        }

        if( typeof shipping_method === "undefined" ) {
            var shipping_method = "";
        }
    }
    
    if( typeof orddd_lpp_method_func == 'function' ) {
        shipping_method = orddd_lpp_method_func( shipping_method );
    }
    
    var hidden_var_obj = jQuery( "#orddd_hidden_vars_str" ).val();
	var html_vars_obj = jQuery.parseJSON( hidden_var_obj );
    if( html_vars_obj == null ) {
        html_vars_obj = [];
    } 

    if ( shipping_method != "" || shipping_class != "" ) {
    	// hidden vars
		jQuery.each( html_vars_obj, function( key, value ) {
			if ( "1" == jQuery( "#orddd_is_admin" ).val() ) {
				jQuery( "#admin_time_slot_field" ).remove();
                jQuery( "#admin_delivery_date_field" ).remove();
			} else {
	            jQuery( "#e_pickupdate_field label[ for=\"e_pickupdate\" ] abbr" ).remove();
	            jQuery( "#e_pickupdate_field" ).fadeOut();
	            jQuery( "#pickup_time_slot_field" ).fadeOut();
	            jQuery( "#pickup_time_slot_field" ).empty();
			}
            string = "";
            enable_delivery_date = "";
            
            if( typeof value.shipping_methods !== "undefined" ) {
                var shipping_methods = value.shipping_methods.split(",");
                for( i = 0; i < shipping_methods.length; i++ ) {
                    if( shipping_method.indexOf( shipping_methods[ i ] ) !== -1 ) {
                        shipping_method = shipping_methods[ i ];
                    }
                }
            } else if( typeof value.product_categories != 'undefined') {
                var shipping_methods = value.product_categories.split(",");
                shipping_method = jQuery( "#orddd_category_settings_to_load" ).val();
                shipping_class = "";
            }

            if( typeof lpp_shipping_methods == 'function' ) {
                shipping_methods = lpp_shipping_methods( value, shipping_methods );    
            }
            
            if( shipping_method.indexOf( 'usps' ) !== -1 && ( shipping_method.split( ":" ).length ) < 3 ) {
                shipping_method = jQuery( "#orddd_zone_id" ).val() + ":" + shipping_method;
            }
            
            if ( jQuery.inArray( shipping_method, shipping_methods ) !== -1 ) {
                jQuery( "#h_pickupdate" ).val( "" );
                jQuery( "#e_pickupdate" ).datepicker( "destroy" );
                if( typeof jQuery.fn.datetimepicker !== "undefined" ) {
                    jQuery( "#e_pickupdate" ).datetimepicker( "destroy" );
                }
                
                jQuery( "#pickup_time_slot_field" ).empty();
                jQuery( ".orddd_text_block" ).hide();

                var hidden_obj = value.hidden_vars;
                var hidden_vars = jQuery.parseJSON( hidden_obj );
                if( hidden_vars == null ) {
                	hidden_vars = [];
                }
                
                jQuery( "#orddd_dynamic_hidden_vars" ).empty();
                load_hidden_vars( hidden_vars );
                
                enable_delivery_date = value.enable_delivery_date;
                jQuery( "<input>" ).attr({id: "orddd_enable_shipping_delivery_date", name: "orddd_enable_shipping_delivery_date", type: "hidden", value: value.enable_delivery_date }).appendTo( "#orddd_dynamic_hidden_vars" );
                
            	if( enable_delivery_date == "on" ) {
                    if( 'delivery_calendar' == value.orddd_delivery_checkout_options ) {
                        if ( "1" == jQuery( "#orddd_is_admin" ).val() ) {
                            jQuery( "#admin_delivery_fields tr:first" ).before( "<tr id=\"admin_delivery_date_field\" ><td><label class =\"orddd_delivery_date_field_label\"> " + jQuery( "#orddd_field_label" ).val() + "</label></td><td><input type=\"text\" id=\"e_pickupdate\" name=\"e_pickupdate\" class=\"e_pickupdate\" /><input type=\"hidden\" id=\"h_pickupdate\" name=\"h_pickupdate\" /></td></tr>");
                            jQuery( "#admin_delivery_fields tr:first" ).after( "<tr id=\"admin_time_slot_field\"><td>" + jQuery( '#orddd_timeslot_field_label' ).val() + "</td><td><select name=\"pickup_time_slot\" id=\"pickup_time_slot\" class=\"orddd_custom_time_slot\" disabled=\"disabled\" placeholder=\"\"><option value=\"select\">" + jsL10n.selectText + "</option></select></td></tr>");
                        } else {    
                            jQuery( "#e_pickupdate_field" ).fadeIn();
                            jQuery( "#pickup_time_slot_field" ).fadeIn();
                        }
                        
                        if( "1" !=  jQuery( "#orddd_is_admin" ).val() ) {
                            var date_field_mandatory = value.date_field_mandatory;
                            if( date_field_mandatory == "checked" ) {
                                jQuery( "#e_pickupdate_field label[for=\"e_pickupdate\"]").append( "<abbr class=\"required\" title=\"required\">*</abbr>" );
                                jQuery( "<input>" ).attr({id: "date_mandatory_for_shipping_method", name: "date_mandatory_for_shipping_method", type: "hidden", value: "checked"}).appendTo( "#orddd_dynamic_hidden_vars" );
                                jQuery( "#e_pickupdate_field" ).attr( "class", "form-row form-row-wide validate-required" );
                            } else {
                                jQuery( "#e_pickupdate_field label[for=\"e_pickupdate\"] abbr" ).remove();
                                jQuery( "<input>" ).attr({id: "date_mandatory_for_shipping_method", name: "date_mandatory_for_shipping_method", type: "hidden", value: ""}).appendTo( "#orddd_dynamic_hidden_vars" );
                                jQuery( "#e_pickupdate_field" ).attr( "class", "form-row form-row-wide" );
                            }
                        } else {
                            var date_field_mandatory = value.date_field_mandatory;
                            if( date_field_mandatory == "checked" ) {
                                jQuery( "<input>" ).attr({id: "date_mandatory_for_shipping_method", name: "date_mandatory_for_shipping_method", type: "hidden", value: "checked"}).appendTo( "#orddd_dynamic_hidden_vars" );
                            } else {
                                jQuery( "<input>" ).attr({id: "date_mandatory_for_shipping_method", name: "date_mandatory_for_shipping_method", type: "hidden", value: ""}).appendTo( "#orddd_dynamic_hidden_vars" );
                            }
                        }
                            
                        if ( value.time_settings != "" ) {
                        	string = value.time_settings;
        	        	    jQuery( "<input>" ).attr({id: "time_setting_enable_for_shipping_method", name: "time_setting_enable_for_shipping_method", type: "hidden", value: "on"}).appendTo( "#orddd_dynamic_hidden_vars" );						
                        } else {
                        	string = "off";
                            jQuery( "<input>" ).attr({id: "time_setting_enable_for_shipping_method", name: "time_setting_enable_for_shipping_method", type: "hidden", value: "off"}).appendTo( "#orddd_dynamic_hidden_vars" );
                        }
                        
                        if( "1" !=  jQuery( "#orddd_is_admin" ).val() ) {
                            if ( value.time_slots == "on" ) {
                                var time_slot_field_mandatory = value.timeslot_field_mandatory;
                                if( time_slot_field_mandatory == "checked" ) {
                                    jQuery( "#pickup_time_slot_field" ).append( "<label for=\"pickup_time_slot\" class=\"\">" + jQuery( '#orddd_timeslot_field_label' ).val() + "<abbr class=\"required\" title=\"required\">*</abbr></label><select name=\"pickup_time_slot\" id=\"pickup_time_slot\" class=\"orddd_custom_time_slot_mandatory\" disabled=\"disabled\" placeholder=\"\"><option value=\"select\">" + jsL10n.selectText + "</option></select>" );
                                    jQuery( "<input>" ).attr({id: "time_slot_mandatory_for_shipping_method", name: "time_slot_mandatory_for_shipping_method", type: "hidden", value: "checked"}).appendTo( "#orddd_dynamic_hidden_vars" );
                                    jQuery( "#pickup_time_slot_field" ).attr( "class", "form-row form-row-wide validate-required" );
                                    jQuery( "#pickup_time_slot_field" ).attr( "style", "opacity: 0.5;" );
                                } else {
                                    jQuery( "#pickup_time_slot_field" ).append( "<label for=\"pickup_time_slot\" class=\"\">" + jQuery( '#orddd_timeslot_field_label' ).val() + "</label><select name=\"pickup_time_slot\" id=\"pickup_time_slot\" class=\"orddd_custom_time_slot_mandatory\" disabled=\"disabled\" placeholder=\"\"><option value=\"select\">" + jsL10n.selectText + "</option></select>" );
                                    jQuery( "<input>" ).attr({id: "time_slot_mandatory_for_shipping_method", name: "time_slot_mandatory_for_shipping_method", type: "hidden", value: ""}).appendTo( "#orddd_dynamic_hidden_vars" );
                                    jQuery( "#pickup_time_slot_field" ).attr( "class", "form-row form-row-wide" );
                                    jQuery( "#pickup_time_slot_field" ).attr( "style", "opacity: 0.5;" );
                                }
                                jQuery("<input>").attr({id: "time_slot_enable_for_shipping_method", name: "time_slot_enable_for_shipping_method", type: "hidden", value: "on"}).appendTo( "#orddd_dynamic_hidden_vars" );
                            } else {
                                jQuery( "#pickup_time_slot_field" ).empty();
                                jQuery( "<input>" ).attr({id: "time_slot_enable_for_shipping_method", name: "time_slot_enable_for_shipping_method", type: "hidden", value: "off"}).appendTo( "#orddd_dynamic_hidden_vars" );
                            }
                        } else {
                        	if ( value.time_slots == "on" ) {
                                var time_slot_field_mandatory = value.timeslot_field_mandatory;
                                if( time_slot_field_mandatory == "checked" ) {
                                    jQuery( "<input>" ).attr({id: "time_slot_mandatory_for_shipping_method", name: "time_slot_mandatory_for_shipping_method", type: "hidden", value: "checked"}).appendTo( "#orddd_dynamic_hidden_vars" );
                                } else {
                                    jQuery( "<input>" ).attr({id: "time_slot_mandatory_for_shipping_method", name: "time_slot_mandatory_for_shipping_method", type: "hidden", value: ""}).appendTo( "#orddd_dynamic_hidden_vars" );   
                                }
                                jQuery("<input>").attr({id: "time_slot_enable_for_shipping_method", name: "time_slot_enable_for_shipping_method", type: "hidden", value: "on"}).appendTo( "#orddd_dynamic_hidden_vars" );
                            } else {
                                jQuery( "#admin_time_slot_field" ).remove();
                            }
                        }
                        
                        if( value.disabled_days != "" ) {
                            disabled_days_arr = eval( "[" + value.disabled_days + "]" );
                            startDaysDisabled = startDaysDisabled.concat( disabled_days_arr );
                        } else {
                            startDaysDisabled = []; 
                        }
                        var specific_dates = jQuery( "#orddd_specific_delivery_dates" ).val();
                        var recurring_weekdays = jQuery("#orddd_recurring_days").val();
                        if( specific_dates == "on" && recurring_weekdays == "" ) {        	        		   
                            for( i = 0; i < 7; i++ ) {
                                jQuery( "#orddd_weekday_" + i ).remove();
                            }
                        }
                        jQuery( "#orddd_is_shipping_text_block" ).val( "no" );
                        jQuery( ".orddd_text_block" ).hide();
                    } else if( 'text_block' == value.orddd_delivery_checkout_options ) {
                        jQuery( "#e_pickupdate_field" ).fadeOut();
                        jQuery( "#e_pickupdate" ).val( "" );
                        jQuery( "#h_pickupdate" ).val( "" );
                        jQuery( "#e_pickupdate_field label[for=\"e_pickupdate\"] abbr" ).remove();
                        jQuery( "<input>" ).attr( {id: "date_mandatory_for_shipping_method", name: "date_mandatory_for_shipping_method", type: "hidden", value: ""} ).appendTo( "#orddd_dynamic_hidden_vars" );
                        jQuery( "#pickup_time_slot_field" ).fadeOut();
                        jQuery( "<input>" ).attr( {id: "time_slot_mandatory_for_shipping_method", name: "time_slot_mandatory_for_shipping_method", type: "hidden", value: ""} ).appendTo( "#orddd_dynamic_hidden_vars" );
                        jQuery( "<input>" ).attr({id: "time_slot_enable_for_shipping_method", name: "time_slot_enable_for_shipping_method", type: "hidden", value: "off"}).appendTo( "#orddd_dynamic_hidden_vars" );
                        jQuery( "#orddd_is_shipping_text_block" ).val( "yes" );
                        jQuery( ".orddd_text_block" ).show();
                        var shipping_date = orddd_get_text_block_shipping_date( value.orddd_minimum_delivery_time );
                        var orddd_between_range = value.orddd_min_between_days + "-" + value.orddd_max_between_days;
                        jQuery( "#orddd_between_range" ).html( orddd_between_range );
                        jQuery( "#shipping_date" ).html( shipping_date[ 'shipping_date' ] );
                        jQuery( "#orddd_estimated_shipping_date" ).val( shipping_date[ 'hidden_shipping_date' ] );
                    }
            	} else {
                    if( "1" !=  jQuery( "#orddd_is_admin" ).val() ) {
                        jQuery( "#e_pickupdate_field" ).fadeOut();
                        jQuery( "#e_pickupdate" ).val( "" );
                        jQuery( "#h_pickupdate" ).val( "" );
                        jQuery( "#e_pickupdate_field label[for=\"e_pickupdate\"] abbr" ).remove();
                        jQuery( "<input>" ).attr( {id: "date_mandatory_for_shipping_method", name: "date_mandatory_for_shipping_method", type: "hidden", value: ""} ).appendTo( "#orddd_dynamic_hidden_vars" );
                        jQuery( "#pickup_time_slot_field" ).fadeOut();
                        jQuery( "<input>" ).attr( {id: "time_slot_mandatory_for_shipping_method", name: "time_slot_mandatory_for_shipping_method", type: "hidden", value: ""} ).appendTo( "#orddd_dynamic_hidden_vars" );
                        jQuery( "<input>" ).attr({id: "time_slot_enable_for_shipping_method", name: "time_slot_enable_for_shipping_method", type: "hidden", value: "off"}).appendTo( "#orddd_dynamic_hidden_vars" );
                        jQuery( ".orddd_text_block" ).hide();
                    } else {
                        jQuery( "#admin_delivery_fields" ).empty();
                        jQuery( "#is_virtual_product" ).html( "Delivery is not available for the shipping method." )
                    }
            	} 
                method_found = 1;								
                return false;
            }
            i = i + 1;
        });     

		if( method_found == 0 && shipping_class != "" ) {
			jQuery.each( html_vars_obj, function( key, value ) {
                if ( "1" == jQuery( "#orddd_is_admin" ).val() ) {
                    jQuery( "#admin_time_slot_field" ).remove();
                    jQuery( "#admin_delivery_date_field" ).remove();
                } else {
					jQuery( "#e_pickupdate_field label[ for=\"e_pickupdate\" ] abbr" ).remove();
					jQuery( "#e_pickupdate_field" ).fadeOut();
					jQuery( "#pickup_time_slot_field" ).fadeOut();
					jQuery( "#pickup_time_slot_field" ).empty();
                }
                
                var shipping_class = jQuery( "#orddd_shipping_class_settings_to_load" ).val();
                if( typeof value.shipping_methods !== "undefined" ) {
                    var shipping_methods = value.shipping_methods.split(",");
                } else {
                    var shipping_methods = [];
                }

                if ( jQuery.inArray( shipping_class, shipping_methods ) !== -1 ) {
                    jQuery( "#h_pickupdate" ).val( "" );
                    jQuery( "#e_pickupdate" ).datepicker( "destroy" );
                    if( typeof jQuery.fn.datetimepicker !== "undefined" ) {
                        jQuery( "#e_pickupdate" ).datetimepicker( "destroy" );
                    }
                    jQuery( "#pickup_time_slot_field" ).empty();
                    
                    jQuery( "#orddd_dynamic_hidden_vars" ).empty();
					var hidden_obj = value.hidden_vars;
					var hidden_vars = jQuery.parseJSON( hidden_obj );
					if( hidden_vars == null ) {
						hidden_vars = [];
					}  
						 
					load_hidden_vars( hidden_vars );
                        
                    enable_delivery_date = value.enable_delivery_date;
                    jQuery( "<input>" ).attr({id: "orddd_enable_shipping_delivery_date", name: "orddd_enable_shipping_delivery_date", type: "hidden", value: value.enable_delivery_date }).appendTo( "#orddd_dynamic_hidden_vars" );
                	if( enable_delivery_date == "on" ) {
                        if( 'delivery_calendar' == value.orddd_delivery_checkout_options ) {
                    		if ( "1" == jQuery( "#orddd_is_admin" ).val() ) {
                    			jQuery( "#admin_delivery_fields tr:first" ).before( "<tr id=\"admin_delivery_date_field\" ><td><label class =\"orddd_delivery_date_field_label\">" + jQuery( "#orddd_field_label" ).val() + ": </label></td><td><input type=\"text\" id=\"e_pickupdate\" name=\"e_pickupdate\" class=\"e_pickupdate\" /><input type=\"hidden\" id=\"h_pickupdate\" name=\"h_pickupdate\" /></td></tr>");
                                jQuery( "#admin_delivery_fields tr:first" ).after( "<tr id=\"admin_time_slot_field\"><td>" + jQuery( '#orddd_timeslot_field_label' ).val() + "</td><td><select name=\"pickup_time_slot\" id=\"pickup_time_slot\" class=\"orddd_custom_time_slot\" disabled=\"disabled\" placeholder=\"\"><option value=\"select\">" + jsL10n.selectText + "</option></select></td></tr>");
                    		} else {    
                    			jQuery( "#e_pickupdate_field" ).fadeIn();
                    			jQuery( "#pickup_time_slot_field" ).fadeIn();
                    		}
                            if( "1" !=  jQuery( "#orddd_is_admin" ).val() ) {
                                var date_field_mandatory = value.date_field_mandatory;
                                if( date_field_mandatory == "checked" ) {
                                    jQuery( "#e_pickupdate_field label[for=\"e_pickupdate\"]").append( "<abbr class=\"required\" title=\"required\">*</abbr>" );
                                    jQuery( "<input>" ).attr({id: "date_mandatory_for_shipping_method", name: "date_mandatory_for_shipping_method", type: "hidden", value: "checked"}).appendTo( "#orddd_dynamic_hidden_vars" );
                                    jQuery( "#e_pickupdate_field" ).attr( "class", "form-row form-row-wide validate-required" );
                                } else {
                                    jQuery( "#e_pickupdate_field label[for=\"e_pickupdate\"] abbr" ).remove();
                                    jQuery( "<input>" ).attr({id: "date_mandatory_for_shipping_method", name: "date_mandatory_for_shipping_method", type: "hidden", value: ""}).appendTo( "#orddd_dynamic_hidden_vars" );
                                    jQuery( "#e_pickupdate_field" ).attr( "class", "form-row form-row-wide" );
                                }
                            } else {
                                var date_field_mandatory = value.date_field_mandatory;
                                if( date_field_mandatory == "checked" ) {
                                    jQuery( "<input>" ).attr({id: "date_mandatory_for_shipping_method", name: "date_mandatory_for_shipping_method", type: "hidden", value: "checked"}).appendTo( "#orddd_dynamic_hidden_vars" );
                                } else {
                                    jQuery( "<input>" ).attr({id: "date_mandatory_for_shipping_method", name: "date_mandatory_for_shipping_method", type: "hidden", value: ""}).appendTo( "#orddd_dynamic_hidden_vars" );
                                } 
                            }
                                
                            if ( value.time_settings != "" ) {
                            	string = value.time_settings;
                                jQuery( "<input>" ).attr({id: "time_setting_enable_for_shipping_method", name: "time_setting_enable_for_shipping_method", type: "hidden", value: "on"}).appendTo( "#orddd_dynamic_hidden_vars" );						
                            } else {
                            	string = "off";
                                jQuery( "<input>" ).attr({id: "time_setting_enable_for_shipping_method", name: "time_setting_enable_for_shipping_method", type: "hidden", value: "off"}).appendTo( "#orddd_dynamic_hidden_vars" );
                            }
                            
                            if( "1" !=  jQuery( "#orddd_is_admin" ).val() ) {
                                if ( value.time_slots == "on" ) {
                                    var time_slot_field_mandatory = value.timeslot_field_mandatory;
                                    if( time_slot_field_mandatory == "checked" ) {
                                        jQuery( "#pickup_time_slot_field" ).append( "<label for=\"pickup_time_slot\" class=\"\">" + jQuery( '#orddd_timeslot_field_label' ).val() + "<abbr class=\"required\" title=\"required\">*</abbr></label><select name=\"pickup_time_slot\" id=\"pickup_time_slot\" class=\"orddd_custom_time_slot_mandatory\" disabled=\"disabled\" placeholder=\"\"><option value=\"select\">" + jsL10n.selectText + "</option></select>" );
                                        jQuery( "<input>" ).attr({id: "time_slot_mandatory_for_shipping_method", name: "time_slot_mandatory_for_shipping_method", type: "hidden", value: "checked"}).appendTo( "#orddd_dynamic_hidden_vars" );
                                        jQuery( "#pickup_time_slot_field" ).attr( "class", "form-row form-row-wide validate-required" );
                                        jQuery( "#pickup_time_slot_field" ).attr( "style", "opacity: 0.5;" );
                                    } else {
                                        jQuery( "#pickup_time_slot_field" ).append( "<label for=\"pickup_time_slot\" class=\"\">" + jQuery( '#orddd_timeslot_field_label' ).val() + "</label><select name=\"pickup_time_slot\" id=\"pickup_time_slot\" class=\"orddd_custom_time_slot_mandatory\" disabled=\"disabled\" placeholder=\"\"><option value=\"select\">" + jsL10n.selectText + "</option></select>" );
                                        jQuery( "<input>" ).attr({id: "time_slot_mandatory_for_shipping_method", name: "time_slot_mandatory_for_shipping_method", type: "hidden", value: ""}).appendTo( "#orddd_dynamic_hidden_vars" );
                                        jQuery( "#pickup_time_slot_field" ).attr( "class", "form-row form-row-wide" );
                                        jQuery( "#pickup_time_slot_field" ).attr( "style", "opacity: 0.5;" );
                                    }
                                    jQuery("<input>").attr({id: "time_slot_enable_for_shipping_method", name: "time_slot_enable_for_shipping_method", type: "hidden", value: "on"}).appendTo( "#orddd_dynamic_hidden_vars" );
                                } else {
                                    jQuery( "#pickup_time_slot_field" ).empty();
                                    jQuery( "<input>" ).attr({id: "time_slot_enable_for_shipping_method", name: "time_slot_enable_for_shipping_method", type: "hidden", value: "off"}).appendTo( "#orddd_dynamic_hidden_vars" );
                                }
                            } else {
                                if ( value.time_slots == "on" ) {
                                    var time_slot_field_mandatory = value.timeslot_field_mandatory;
                                    if( time_slot_field_mandatory == "checked" ) {
                                        jQuery( "<input>" ).attr({id: "time_slot_mandatory_for_shipping_method", name: "time_slot_mandatory_for_shipping_method", type: "hidden", value: "checked"}).appendTo( "#orddd_dynamic_hidden_vars" );
                                    } else {
                                        jQuery( "<input>" ).attr({id: "time_slot_mandatory_for_shipping_method", name: "time_slot_mandatory_for_shipping_method", type: "hidden", value: ""}).appendTo( "#orddd_dynamic_hidden_vars" );   
                                    }
                                    jQuery("<input>").attr({id: "time_slot_enable_for_shipping_method", name: "time_slot_enable_for_shipping_method", type: "hidden", value: "on"}).appendTo( "#orddd_dynamic_hidden_vars" );
                                } else {
                                    jQuery( "#admin_time_slot_field" ).remove();
                                }
                            }   
                            
                    		if( value.disabled_days != "" ) {
                                disabled_days_arr = eval( "[" + value.disabled_days + "]" );
                                startDaysDisabled = startDaysDisabled.concat( disabled_days_arr );
                            } else {
                                startDaysDisabled = []; 
                            }
                            var specific_dates = jQuery( "#orddd_specific_delivery_dates" ).val();
                            var recurring_weekdays = jQuery("#orddd_recurring_days").val();
                            if( specific_dates == "on" && recurring_weekdays == "" ) {        	        		   
                                for( i = 0; i < 7; i++ ) {
                                    jQuery( "#orddd_weekday_" + i ).remove();
                                }
                            }
                            jQuery( "#orddd_is_shipping_text_block" ).val( "no" );
                            jQuery( ".orddd_text_block" ).hide();
                        } else if ( 'text_block' == value.orddd_delivery_checkout_options ) {
                            jQuery( "#e_pickupdate_field" ).fadeOut();
                            jQuery( "#e_pickupdate" ).val( "" );
                            jQuery( "#h_pickupdate" ).val( "" );
                            jQuery( "#e_pickupdate_field label[for=\"e_pickupdate\"] abbr" ).remove();
                            jQuery( "<input>" ).attr( {id: "date_mandatory_for_shipping_method", name: "date_mandatory_for_shipping_method", type: "hidden", value: ""} ).appendTo( "#orddd_dynamic_hidden_vars" );
                            jQuery( "#pickup_time_slot_field" ).fadeOut();
                            jQuery( "<input>" ).attr( {id: "time_slot_mandatory_for_shipping_method", name: "time_slot_mandatory_for_shipping_method", type: "hidden", value: ""} ).appendTo( "#orddd_dynamic_hidden_vars" );
                            jQuery( "<input>" ).attr({id: "time_slot_enable_for_shipping_method", name: "time_slot_enable_for_shipping_method", type: "hidden", value: "off"}).appendTo( "#orddd_dynamic_hidden_vars" );
                            jQuery( "#orddd_is_shipping_text_block" ).val( "yes" );
                            jQuery( ".orddd_text_block" ).show();
                            var shipping_date = orddd_get_text_block_shipping_date( value.orddd_minimum_delivery_time );
                            var orddd_between_range = value.orddd_min_between_days + "-" + value.orddd_max_between_days;
                            jQuery( "#orddd_between_range" ).html( orddd_between_range );
                            jQuery( "#shipping_date" ).html( shipping_date[ 'shipping_date' ] );
                            jQuery( "#orddd_estimated_shipping_date" ).val( shipping_date[ 'hidden_shipping_date' ] );
                        }
                	} else {
                        if( "1" !=  jQuery( "#orddd_is_admin" ).val() ) {
                            jQuery( "#e_pickupdate_field" ).fadeOut();
                            jQuery( "#e_pickupdate" ).val( "" );
                            jQuery( "#h_pickupdate" ).val( "" );
                            jQuery( "#e_pickupdate_field label[for=\"e_pickupdate\"] abbr" ).remove();
                            jQuery( "<input>" ).attr( {id: "date_mandatory_for_shipping_method", name: "date_mandatory_for_shipping_method", type: "hidden", value: ""} ).appendTo( "#orddd_dynamic_hidden_vars" );
                            jQuery( "#pickup_time_slot_field" ).fadeOut();
                            jQuery( "<input>" ).attr( {id: "time_slot_mandatory_for_shipping_method", name: "time_slot_mandatory_for_shipping_method", type: "hidden", value: ""} ).appendTo( "#orddd_dynamic_hidden_vars" );
                            jQuery( "<input>" ).attr({id: "time_slot_enable_for_shipping_method", name: "time_slot_enable_for_shipping_method", type: "hidden", value: "off"}).appendTo( "#orddd_dynamic_hidden_vars" );
                        } else {
                            jQuery( "#admin_delivery_fields" ).empty();
                            jQuery( "#is_virtual_product" ).html( "Delivery is not available for the shipping method." )
                        }
                	} 
                    method_found = 1;								
                    return false;
                }
                i = i + 1;
			}); 
		} 

        if( method_found == 0 ) {
            jQuery( "#h_pickupdate" ).val( "" );
            jQuery( "#e_pickupdate" ).datepicker( "destroy" );
            if( typeof jQuery.fn.datetimepicker !== "undefined" ) {
                jQuery( "#e_pickupdate" ).datetimepicker( "destroy" );
            }
            jQuery( "#pickup_time_slot_field" ).empty();
            jQuery( "#e_pickupdate_field label[ for=\"e_pickupdate\" ] abbr" ).remove();

            if( 'delivery_calendar' == jQuery( "#orddd_delivery_checkout_options" ).val() ) {  
                if( "1" !=  jQuery( "#orddd_is_admin" ).val() ) {
                    jQuery( "#e_pickupdate_field" ).fadeIn();
                    jQuery( "#pickup_time_slot_field" ).fadeIn();
                } else {
                    jQuery( "#admin_delivery_fields tr:first" ).before( "<tr id=\"admin_delivery_date_field\" ><td><label class =\"orddd_delivery_date_field_label\">" + jQuery( "#orddd_field_label" ).val() + ": </label></td><td><input type=\"text\" id=\"e_pickupdate\" name=\"e_pickupdate\" class=\"e_pickupdate\" /><input type=\"hidden\" id=\"h_pickupdate\" name=\"h_pickupdate\" /></td></tr>");
                    jQuery( "#admin_delivery_fields tr:first" ).after( "<tr id=\"admin_pickup_time_slot_field\"><td>" + jQuery( '#orddd_timeslot_field_label' ).val() + "/td><td><select name=\"pickup_time_slot\" id=\"pickup_time_slot\" class=\"orddd_custom_time_slot\" disabled=\"disabled\" placeholder=\"\"><option value=\"select\">" + jsL10n.selectText + "</option></select></td></tr>");
                }
                
                jQuery( "#e_pickupdate" ).val( "" );
                jQuery( "#orddd_dynamic_hidden_vars" ).empty(); 
                var enabled_weekdays = jQuery( "#orddd_load_delivery_date_var" ).val();
                var hidden_enabled_weekdays_var = jQuery.parseJSON( enabled_weekdays );
                if( hidden_enabled_weekdays_var == null ) {
                    hidden_enabled_weekdays_var = [];
                }
                    
                jQuery( "#orddd_dynamic_hidden_vars" ).empty();
                load_hidden_vars( hidden_enabled_weekdays_var );

                var time_slot_enabled = jQuery( '#orddd_enable_time_slot' ).val();
                if( "1" !=  jQuery( "#orddd_is_admin" ).val() ) {
                    if( jQuery( "#pickup_time_slot_field" ).is(":empty") && time_slot_enabled == "on" ) { 
                        var time_slot_field_mandatory = jQuery( '#orddd_timeslot_field_mandatory' ).val();
                        if( time_slot_field_mandatory == "checked" ) {
                            jQuery( "#pickup_time_slot_field" ).append( "<label for=\"pickup_time_slot\" class=\"\">" + jQuery( '#orddd_timeslot_field_label' ).val() + "<abbr class=\"required\" title=\"required\">*</abbr></label><select name=\"pickup_time_slot\" id=\"pickup_time_slot\" class=\"orddd_custom_time_slot_mandatory\" disabled=\"disabled\" placeholder=\"\"><option value=\"select\">" + jsL10n.selectText + "</option></select>" );
                            jQuery( "<input>").attr({id: "time_slot_mandatory_for_shipping_method", name: "time_slot_mandatory_for_shipping_method", type: "hidden", value: "checked"}).appendTo( "#orddd_dynamic_hidden_vars" );
                            jQuery( "#pickup_time_slot_field" ).attr( "class", "form-row form-row-wide validate-required" );
                            jQuery( "#pickup_time_slot_field" ).attr( "style", "opacity: 0.5;" );        	        			 
                        } else {
                            jQuery( "#pickup_time_slot_field" ).append( "<label for=\"pickup_time_slot\" class=\"\">" + jQuery( '#orddd_timeslot_field_label' ).val() + "</label><select name=\"pickup_time_slot\" id=\"pickup_time_slot\" class=\"orddd_custom_time_slot_mandatory\" disabled=\"disabled\" placeholder=\"\"><option value=\"select\">" + jsL10n.selectText + "</option></select>" );
                            jQuery( "<input>").attr({id: "time_slot_mandatory_for_shipping_method", name: "time_slot_mandatory_for_shipping_method", type: "hidden", value: ""}).appendTo( "#orddd_dynamic_hidden_vars" );
                            jQuery( "#pickup_time_slot_field" ).attr( "class", "form-row form-row-wide" );
                            jQuery( "#pickup_time_slot_field" ).attr( "style", "opacity: 0.5;" );
                        }
                    }
                } else {
                    if( time_slot_enabled != "on" ) {
                        jQuery( "#admin_time_slot_field" ).remove();
                    }
                }
                
                disabled_days_arr = eval( "[" + jQuery( '#orddd_disabled_days_str' ).val() + "]" );
                startDaysDisabled = startDaysDisabled.concat( disabled_days_arr );
                if( "1" !=  jQuery( "#orddd_is_admin" ).val() ) {
                    var date_field_mandatory = jQuery( '#orddd_date_field_mandatory' ).val();
                    if( date_field_mandatory == "checked" ) {
        			    jQuery( "#e_pickupdate_field label[ for = \"e_pickupdate\" ]" ).append( "<abbr class=\"required\" title=\"required\">*</abbr>" );
        			    jQuery( "#e_pickupdate_field" ).attr( "class", "form-row form-row-wide validate-required" );
                    } else {
                        jQuery( "#e_pickupdate_field" ).attr( "class", "form-row form-row-wide" );
                    }
                }
                jQuery( "#orddd_is_shipping_text_block" ).val( "no" );
                jQuery( ".orddd_text_block" ).hide();
            } else if ( 'text_block' == jQuery( "#orddd_delivery_checkout_options" ).val() )  {
                jQuery( "#e_pickupdate_field" ).fadeOut();
                jQuery( "#e_pickupdate" ).val( "" );
                jQuery( "#h_pickupdate" ).val( "" );
                jQuery( "#e_pickupdate_field label[for=\"e_pickupdate\"] abbr" ).remove();
                jQuery( "<input>" ).attr( {id: "date_mandatory_for_shipping_method", name: "date_mandatory_for_shipping_method", type: "hidden", value: ""} ).appendTo( "#orddd_dynamic_hidden_vars" );
                jQuery( "#pickup_time_slot_field" ).fadeOut();
                jQuery( "<input>" ).attr( {id: "time_slot_mandatory_for_shipping_method", name: "time_slot_mandatory_for_shipping_method", type: "hidden", value: ""} ).appendTo( "#orddd_dynamic_hidden_vars" );
                jQuery( "<input>" ).attr({id: "time_slot_enable_for_shipping_method", name: "time_slot_enable_for_shipping_method", type: "hidden", value: "off"}).appendTo( "#orddd_dynamic_hidden_vars" );
                jQuery( "#orddd_is_shipping_text_block" ).val( "yes" );
                jQuery( ".orddd_text_block" ).show();
                var shipping_date = orddd_get_text_block_shipping_date( jQuery( "#orddd_minimum_delivery_time" ).val() );
                var orddd_between_range = jQuery( "#orddd_min_between_days" ).val() + "-" + jQuery( "#orddd_max_between_days" ).val();
                jQuery( "#orddd_between_range" ).html( orddd_between_range );
                jQuery( "#shipping_date" ).html( shipping_date[ 'shipping_date' ] );
                jQuery( "#orddd_estimated_shipping_date" ).val( shipping_date[ 'hidden_shipping_date' ] );
            }
		}                          
	} else {
		jQuery( "#h_pickupdate" ).val( "" );
        jQuery( "#e_pickupdate" ).datepicker( "destroy" );
        if( typeof jQuery.fn.datetimepicker !== "undefined" ) {
            jQuery( "#e_pickupdate" ).datetimepicker( "destroy" );
        }
        jQuery( "#e_pickupdate_field label[ for=\"e_pickupdate\" ] abbr" ).remove();
        if( 'delivery_calendar' == jQuery ( "#orddd_delivery_checkout_options" ).val() ) {
            if( "1" !=  jQuery( "#orddd_is_admin" ).val() ) {
                jQuery( "#pickup_time_slot_field" ).empty();
                jQuery( "#e_pickupdate_field" ).fadeIn();
                jQuery( "#pickup_time_slot_field" ).fadeIn();
            } else {
                if( jQuery( "#admin_delivery_date_field" ).length == 0 ) { 
                    jQuery( "#admin_delivery_fields tr:first" ).before( "<tr id=\"admin_delivery_date_field\" ><td><label class =\"orddd_delivery_date_field_label\">" + jQuery( "#orddd_field_label" ).val() + ": </label></td><td><input type=\"text\" id=\"e_pickupdate\" name=\"e_pickupdate\" class=\"e_pickupdate\" /><input type=\"hidden\" id=\"h_pickupdate\" name=\"h_pickupdate\" /></td></tr>");
                }
                if( jQuery( "#admin_time_slot_field" ).length == 0 ) { 
                    jQuery( "#admin_delivery_fields tr:first" ).after( "<tr id=\"admin_time_slot_field\"><td>" + jQuery( '#orddd_timeslot_field_label' ).val() + "</td><td><select name=\"pickup_time_slot\" id=\"pickup_time_slot\" class=\"orddd_custom_time_slot\" disabled=\"disabled\" placeholder=\"\"><option value=\"select\">" + jsL10n.selectText + "</option></select></td></tr>");
                }
                if( jQuery( "#save_delivery_date_button" ).length == 0 ) {
                    jQuery( "#admin_delivery_fields tr:second" ).after( "<tr id=\"save_delivery_date_button\"><td><input type=\"button\" value=\"Update\" id=\"save_delivery_date\" class=\"save_button\"></td></tr>" );
                }
            }
            jQuery( "#e_pickupdate" ).val( "" );
            jQuery( "#orddd_dynamic_hidden_vars" ).empty();

            var enabled_weekdays = jQuery( "#orddd_load_delivery_date_var" ).val();
            var hidden_enabled_weekdays_var = jQuery.parseJSON( enabled_weekdays );
            if( hidden_enabled_weekdays_var == null ) {
                hidden_enabled_weekdays_var = [];
            }
                
            jQuery( "#orddd_dynamic_hidden_vars" ).empty();
            load_hidden_vars( hidden_enabled_weekdays_var );

            var time_slot_enabled = jQuery( '#orddd_enable_time_slot' ).val();
            if( "1" !=  jQuery( "#orddd_is_admin" ).val() ) {
            	if( jQuery( "#pickup_time_slot_field" ).is(":empty") && time_slot_enabled == "on" ) { 
                    var time_slot_field_mandatory = jQuery( '#orddd_timeslot_field_mandatory' ).val();
                    if( time_slot_field_mandatory == "checked" ) {
                        jQuery( "#pickup_time_slot_field" ).append( "<label for=\"pickup_time_slot\" class=\"\">" + jQuery( '#orddd_timeslot_field_label' ).val() + "<abbr class=\"required\" title=\"required\">*</abbr></label><select name=\"pickup_time_slot\" id=\"pickup_time_slot\" class=\"orddd_custom_time_slot_mandatory\" disabled=\"disabled\" placeholder=\"\"><option value=\"select\">" + jsL10n.selectText + "</option></select>" );
                        jQuery( "<input>").attr({id: "time_slot_mandatory_for_shipping_method", name: "time_slot_mandatory_for_shipping_method", type: "hidden", value: "checked"}).appendTo( "#orddd_dynamic_hidden_vars" );
                        jQuery( "#pickup_time_slot_field" ).attr( "class", "form-row form-row-wide validate-required" );
                        jQuery( "#pickup_time_slot_field" ).attr( "style", "opacity: 0.5;" );        	        			 
                    } else {
                        jQuery( "#pickup_time_slot_field" ).append( "<label for=\"pickup_time_slot\" class=\"\">" + jQuery( '#orddd_timeslot_field_label' ).val() + "</label><select name=\"pickup_time_slot\" id=\"pickup_time_slot\" class=\"orddd_custom_time_slot_mandatory\" disabled=\"disabled\" placeholder=\"\"><option value=\"select\">" + jsL10n.selectText + "</option></select>" );
                        jQuery( "<input>").attr({id: "time_slot_mandatory_for_shipping_method", name: "time_slot_mandatory_for_shipping_method", type: "hidden", value: ""}).appendTo( "#orddd_dynamic_hidden_vars" );
                        jQuery( "#pickup_time_slot_field" ).attr( "class", "form-row form-row-wide" );
                        jQuery( "#pickup_time_slot_field" ).attr( "style", "opacity: 0.5;" );
                    }
            	}
            } else {
            	if( time_slot_enabled != "on" ) {
            		jQuery( "#admin_time_slot_field" ).remove();
            	}
            }
            disabled_days_arr = eval( "[" + jQuery( '#orddd_disabled_days_str' ).val() + "]" );
            startDaysDisabled = startDaysDisabled.concat( disabled_days_arr );
            if( "1" !=  jQuery( "#orddd_is_admin" ).val() ) {
            	var date_field_mandatory = jQuery( '#orddd_date_field_mandatory' ).val();
                if( date_field_mandatory == "checked" ) {
                    jQuery( "#e_pickupdate_field label[ for = \"e_pickupdate\" ]" ).append( "<abbr class=\"required\" title=\"required\">*</abbr>" );
            		jQuery( "#e_pickupdate_field" ).attr( "class", "form-row form-row-wide validate-required" );
                } else {
                    jQuery( "#e_pickupdate_field" ).attr( "class", "form-row form-row-wide" );
                }
            }
            jQuery( ".orddd_text_block" ).hide();
            jQuery( "#orddd_is_shipping_text_block" ).val( "no" );
        } else if ( 'text_block' == jQuery ( "#orddd_delivery_checkout_options" ).val() ) {
            jQuery( "#e_pickupdate_field" ).fadeOut();
            jQuery( "#e_pickupdate" ).val( "" );
            jQuery( "#h_pickupdate" ).val( "" );
            jQuery( "#e_pickupdate_field label[for=\"e_pickupdate\"] abbr" ).remove();
            jQuery( "<input>" ).attr( {id: "date_mandatory_for_shipping_method", name: "date_mandatory_for_shipping_method", type: "hidden", value: ""} ).appendTo( "#orddd_dynamic_hidden_vars" );
            jQuery( "#pickup_time_slot_field" ).fadeOut();
            jQuery( "<input>" ).attr( {id: "time_slot_mandatory_for_shipping_method", name: "time_slot_mandatory_for_shipping_method", type: "hidden", value: ""} ).appendTo( "#orddd_dynamic_hidden_vars" );
            jQuery( "<input>" ).attr({id: "time_slot_enable_for_shipping_method", name: "time_slot_enable_for_shipping_method", type: "hidden", value: "off"}).appendTo( "#orddd_dynamic_hidden_vars" );
            jQuery( "#orddd_is_shipping_text_block" ).val( "yes" );
            jQuery( ".orddd_text_block" ).show();
            var shipping_date = orddd_get_text_block_shipping_date( jQuery( "#orddd_minimum_delivery_time" ),val() );
            var orddd_between_range = jQuery( "#orddd_min_between_days" ).val() + "-" + jQuery( "#orddd_max_between_days" ).val();
            jQuery( "#orddd_between_range" ).html( orddd_between_range );
            jQuery( "#shipping_date" ).html( shipping_date[ 'shipping_date' ] );
            jQuery( "#orddd_estimated_shipping_date" ).val( shipping_date[ 'hidden_shipping_date' ] );
        }
	}
    
    var date_format = jQuery( '#orddd_delivery_date_format' ).val();
    var a = { firstDay: parseInt( jQuery( "#orddd_start_of_week" ).val() ), beforeShowDay: chd, dateFormat: date_format,
        onClose:function( dateStr, inst ) {
        if ( dateStr != "" ) {
            var monthValue = inst.selectedMonth+1;
            var dayValue = inst.selectedDay;
            var yearValue = inst.selectedYear;
            var all = dayValue + "-" + monthValue + "-" + yearValue;
            jQuery( "#h_pickupdate" ).val( all );var hourValue = jQuery( ".ui_tpicker_time" ).html();
            jQuery( "#orddd_time_settings_selected" ).val( hourValue );
            var event = arguments.callee.caller.caller.arguments[0];
            // If "Clear" gets clicked, then really clear it
            if( typeof( event ) !== "undefined" ) {
                if ( jQuery( event.delegateTarget ).hasClass( "ui-datepicker-close" )) {
                    jQuery( this ).val(""); 
                    jQuery( "#h_pickupdate" ).val( "" );
                    jQuery( "#pickup_time_slot" ).prepend( "<option value=\"select\">" + jsL10n.selectText + "</option>" );
                    jQuery( "#pickup_time_slot" ).children( "option:not(:first)" ).remove();
                    jQuery( "#pickup_time_slot" ).attr( "disabled", "disabled" );
                    jQuery( "#pickup_time_slot" ).attr( "style", "cursor: not-allowed !important" );
                    jQuery( "#pickup_time_slot_field" ).css({ opacity: "0.5" });
                }
            }
            jQuery( "body" ).trigger( "update_checkout" );
        }
        jQuery( "#e_pickupdate" ).blur();
    },
    onSelect: show_pickup_times_custom }; 

    if( jQuery( "#orddd_custom_based_same_day_delivery" ).val() == "on" || jQuery( "#orddd_custom_based_next_day_delivery" ).val() == "on" ) {
        var b = { beforeShow: maxdt };
    } else if( jQuery( "#orddd_custom_based_same_day_delivery" ).val() == "" && jQuery( "#orddd_custom_based_next_day_delivery" ).val() == "" ) {
        var b = { beforeShow: avd };
    } else if( jQuery( "#orddd_same_day_delivery" ).val() == "on" || jQuery( "#orddd_next_day_delivery" ).val() == "on" ) {
        var b = { beforeShow: maxdt };
    } else {
        var b = { beforeShow: avd };
    }
    var time_settings_enabled = jQuery( '#orddd_enable_time_slider' ).val();
    if ( string != "" && string != "off" ) {
        var clear_button_text = {};
    } else if ( string == "" && time_settings_enabled == "on" ) {
        var clear_button_text = {};
    } else {
        var clear_button_text = {showButtonPanel: true, closeText: jsL10n.clearText };
    }
    var option_str = {};
    option_str = jsonConcat( option_str, a );
    option_str = jsonConcat( option_str, b );
    option_str = jsonConcat( option_str, clear_button_text );
    if ( string != "" && string != "off" ) {
        var c = jQuery.parseJSON( string );                    
        var hour_min = parseInt( c.hourMin );
    	var hour_max = parseInt( c.hourMax );
        var minute_min = parseInt( c.minuteMin );
        var step_minute = parseInt( c.stepMinute );
      	var time_format = ( c.timeFormat );
    	option_str = jsonConcat( option_str, { hourMin: hour_min, minuteMin: minute_min, hourMax: hour_max, stepMinute: step_minute, timeFormat: time_format } );
        jQuery( "#e_pickupdate" ).val( "" ).datetimepicker( option_str ).focus( function ( event ) {
            jQuery(this).trigger( "blur" );
            jQuery.datepicker.afterShow( event );
		});
    } else if ( string == "" && time_settings_enabled == "on" ) {
    	var options = jQuery( "#orddd_option_str" ).val();
        var df_arr = options.split("dateFormat: '");
        var df_arr2 = df_arr[1].split("'");
        var df_dateformat = df_arr2[0];
        var before_df_arr = df_arr[0].split( ', ' );
        before_df_arr[6] = "dateFormat:'" + df_dateformat + "'";
        var c = {};
        jQuery.each( before_df_arr, function( key, value ) {
            if( '' != value && 'undefined' != typeof( value ) ) {
                var split_value = value.split( ":" );
                if( split_value.length != '2' ) {
                    var str = split_value[1] + ":" + split_value[2];
                    c[ split_value[0] ] = str.trim().replace( /'/g, "" );
                } else if( 'hourMax' == split_value[0] || 'hourMin' == split_value[0] || 'minuteMin' == split_value[0] || 'stepMinute' == split_value[0] ) {
                    c[ split_value[0] ] = parseInt( split_value[1].trim() );  
                } else if( 'beforeShow' == split_value[0] ) {
                    c[ split_value[0] ] = avd;  
                } else {
                    c[ split_value[0] ] = split_value[1].trim().replace( /'/g, "" );    
                }    
            }
        });
        option_str = jsonConcat( option_str, c );
		jQuery( "#e_pickupdate" ).val( "" ).datetimepicker( option_str ).focus( function ( event ) {
            jQuery(this).trigger( "blur" );
            jQuery.datepicker.afterShow( event );
		});
    } else if ( string == "" && time_settings_enabled != "on" ) {
        jQuery( "#e_pickupdate" ).val( "" ).datepicker( option_str ).focus( function ( event ) {
            jQuery(this).trigger( "blur" );
            jQuery.datepicker.afterShow( event );
        });
    } else {
        jQuery( "#e_pickupdate" ).val( "" ).datepicker( option_str ).focus( function ( event ) {
            jQuery(this).trigger( "blur" );
            jQuery.datepicker.afterShow( event );
		});
    } 
}

function orddd_get_text_block_shipping_date( delivery_time_seconds ) {
    var shipping_date = '';
    var date_format = jQuery( '#orddd_delivery_date_format' ).val();
    var js_date_format = get_js_date_formats( date_format );

    var current_date = jQuery( "#orddd_current_day" ).val();
    var split_current_date = current_date.split( '-' );
    
    var current_day = new Date( split_current_date[ 1 ] + '/' + split_current_date[ 0 ] + '/' + split_current_date[ 2 ] );
    var current_time = current_day.getTime();
    var current_weekday = current_day.getDay();
    var shipping_info = [];
    if( delivery_time_seconds != 0 && delivery_time_seconds != '' ) {
        var cut_off_timestamp = current_time + parseInt( delivery_time_seconds * 60 * 60 * 1000 );
        var cut_off_date = new Date( cut_off_timestamp );
        var cut_off_weekday = cut_off_date.getDay();

        if( 'on' == jQuery( '#orddd_enable_shipping_days' ).val() ) {
            for( i = current_weekday; current_time <= cut_off_timestamp; i++ ) {
                if( i >= 0 ) {
                    var shipping_day = 'orddd_shipping_day_' + current_weekday;
                    var shipping_day_check = jQuery( "#" + shipping_day ).val();
                    if ( shipping_day_check == '' ) {
                        current_day.setDate( current_day.getDate()+1 );
                        current_weekday = current_day.getDay();
                        current_time = current_day.getTime();
                        cut_off_date.setDate( cut_off_date.getDate()+1 );
                        cut_off_timestamp = cut_off_date.getTime();
                    } else {
                        if( current_time <= cut_off_timestamp ) {
                            current_day.setDate( current_day.getDate()+1 );
                            current_weekday = current_day.getDay();
                            current_time = current_day.getTime();
                        }
                    }
                } else {
                    break;
                }
            }
        }
        shipping_info[ 'shipping_date' ] = moment( cut_off_date ).format( js_date_format ) ;     
        shipping_info[ 'hidden_shipping_date' ] = moment( cut_off_date ).format( 'D-M-YYYY' ) ;     
    } else {
        shipping_info[ 'shipping_date' ] = moment( current_day ).format( js_date_format ) ;    
        shipping_info[ 'hidden_shipping_date' ] = moment( current_day ).format( 'D-M-YYYY' ) ;     
    }

    return shipping_info;
}

function get_js_date_formats( date_format ) {
    var date_str = '';
    var month_str = '';
    var year_str = '';
    var day_str = '';
    switch( date_format ) {
        case "mm/dd/y":
            date_str = date_format.replace( new RegExp("\\bdd\\b"), 'DD' );
            month_str = date_str.replace( new RegExp("\\bmm\\b"), 'MM' );
            year_str = month_str.replace( new RegExp("\\by\\b"), 'YY' );
            break;
        case "dd/mm/y": 
            date_str = date_format.replace( new RegExp("\\bdd\\b"), 'DD' );
            month_str = date_str.replace( new RegExp("\\bmm\\b"), 'MM' );
            year_str = month_str.replace( new RegExp("\\by\\b"), 'YY' );
            break;
        case "y/mm/dd":
            date_str = date_format.replace( new RegExp("\\bdd\\b"), 'DD' );
            month_str = date_str.replace( new RegExp("\\bmm\\b"), 'MM' );
            year_str = month_str.replace( new RegExp("\\by\\b"), 'YY' );
            break;
        case "mm/dd/y, D":
            day_str = date_format.replace( new RegExp("\\bD\\b"), 'ddd' );
            date_str = day_str.replace( new RegExp("\\bdd\\b"), 'DD' );
            month_str = date_str.replace( new RegExp("\\bmm\\b"), 'MM' );
            year_str = month_str.replace( new RegExp("\\by\\b"), 'YY' );
            break;
        case "dd.mm.y":
            date_str = date_format.replace( new RegExp("\\bdd\\b"), 'DD' );
            month_str = date_str.replace( new RegExp("\\bmm\\b"), 'MM' );
            year_str = month_str.replace( new RegExp("\\by\\b"), 'YY' );
            break;
        case "y.mm.dd":
            date_str = date_format.replace( new RegExp("\\bdd\\b"), 'DD' );
            month_str = date_str.replace( new RegExp("\\bmm\\b"), 'MM' );
            year_str = month_str.replace( new RegExp("\\by\\b"), 'YY' );
            break;
        case "yy-mm-dd":
            date_str = date_format.replace( new RegExp("\\bdd\\b"), 'DD' );
            month_str = date_str.replace( new RegExp("\\bmm\\b"), 'MM' );
            year_str = month_str.replace( new RegExp("\\byy\\b"), 'YYYY' );
            break;
        case "dd-mm-y":
            date_str = date_format.replace( new RegExp("\\bdd\\b"), 'DD' );
            month_str = date_str.replace( new RegExp("\\bmm\\b"), 'MM' );
            year_str = month_str.replace( new RegExp("\\by\\b"), 'YY' );
            break;
        case 'd M, y':
            date_str = date_format.replace( new RegExp("\\bd\\b"), 'D' );
            month_str = date_str.replace( new RegExp("\\bM\\b"), 'MMM' );
            year_str = month_str.replace( new RegExp("\\by\\b"), 'YY' );
            break;
        case 'd M, yy':
            date_str = date_format.replace( new RegExp("\\bd\\b"), 'D' );
            month_str = date_str.replace( new RegExp("\\bM\\b"), 'MMM' );
            year_str = month_str.replace( new RegExp("\\byy\\b"), 'YYYY' );
            break;
        case 'd MM, y':
            date_str = date_format.replace( new RegExp("\\bd\\b"), 'D' );
            month_str = date_str.replace( new RegExp("\\bMM\\b"), 'MMMM' );
            year_str = month_str.replace( new RegExp("\\by\\b"), 'YY' );
            break;
        case 'd MM, yy':
            date_str = date_format.replace( new RegExp("\\bd\\b"), 'D' );
            month_str = date_str.replace( new RegExp("\\bMM\\b"), 'MMMM' );
            year_str = month_str.replace( new RegExp("\\byy\\b"), 'YYYY' );
            break;
        case 'DD, d MM, yy':
            day_str = date_format.replace( new RegExp("\\bDD\\b"), 'dddd' );
            date_str = day_str.replace( new RegExp("\\bd\\b"), 'D' );
            month_str = date_str.replace( new RegExp("\\bMM\\b"), 'MMMM' );
            year_str = month_str.replace( new RegExp("\\byy\\b"), 'YYYY' );
            break;
        case 'D, M d, yy':
            day_str = date_format.replace( new RegExp("\\bD\\b"), 'ddd' );
            date_str = day_str.replace( new RegExp("\\bd\\b"), 'D' );
            month_str = date_str.replace( new RegExp("\\bM\\b"), 'MMM' );
            year_str = month_str.replace( new RegExp("\\byy\\b"), 'YYYY' );
            break;
        case 'DD, M d, yy':
            day_str = date_format.replace( new RegExp("\\bDD\\b"), 'dddd' );
            date_str = day_str.replace( new RegExp("\\bd\\b"), 'D' );
            month_str = date_str.replace( new RegExp("\\bM\\b"), 'MMM' );
            year_str = month_str.replace( new RegExp("\\byy\\b"), 'YYYY' );
            break;
        case 'DD, MM d, yy':
            day_str = date_format.replace( new RegExp("\\bDD\\b"), 'dddd' );
            date_str = day_str.replace( new RegExp("\\bd\\b"), 'D' );
            month_str = date_str.replace( new RegExp("\\bMM\\b"), 'MMMM' );
            year_str = month_str.replace( new RegExp("\\byy\\b"), 'YYYY' );
            break;
        case 'D, MM d, yy':
            day_str = date_format.replace( new RegExp("\\bD\\b"), 'ddd' );
            date_str = day_str.replace( new RegExp("\\bd\\b"), 'D' );
            month_str = date_str.replace( new RegExp("\\bMM\\b"), 'MMMM' );
            year_str = month_str.replace( new RegExp("\\byy\\b"), 'YYYY' );
            break;
    }

    return year_str;
}

function jsonConcat( o1, o2 ) {
    for ( var key in o2 ) {
        o1[ key ] = o2[ key ];
    }
    return o1;
}

function show_pickup_times_custom( date, inst ) {
    if ( "1" == jQuery( "#orddd_is_admin" ).val() ) {
        var shipping_class = "";
        var shipping_method_id = jQuery( "input[name=\"shipping_method_id[]\"]" ).val();
        if( typeof shipping_method_id === "undefined" ) {
            var shipping_method_id = "";
        }
        var shipping_method = jQuery( "select[name=\"shipping_method[" + shipping_method_id + "]\"]" ).find(":selected").val();
        if( typeof shipping_method === "undefined" ) {
            var shipping_method = "";
        }
    } else if( "1" == jQuery( "#orddd_is_account_page" ).val() ) {
        var shipping_class = jQuery( "#orddd_shipping_class_settings_to_load" ).val()
        var shipping_method = jQuery( "#shipping_method" ).val();
    } else {
        var shipping_class = jQuery( "#orddd_shipping_class_settings_to_load" ).val();
        var shipping_method = jQuery( "input[name=\"shipping_method[0]\"]:checked" ).val();
        if( typeof shipping_method === "undefined" ) {
            var shipping_method = jQuery( "select[name=\"shipping_method[0]\"] option:selected" ).val();
        }
        if( typeof shipping_method === "undefined" ) {
            var shipping_method = jQuery( "input[name=\"shipping_method[0]\"]" ).val();
        }
        if( typeof shipping_method === "undefined" ) {
            var shipping_method = "";
        }
    }
    
    var pickup_location = '';
    if( typeof orddd_lpp_method_func == 'function' ) {
        pickup_location = orddd_lpp_method_func( shipping_method );    
    }
    
    var hidden_var_obj = jQuery( "#orddd_hidden_vars_str" ).val();
    var html_vars_obj = jQuery.parseJSON( hidden_var_obj );
    if( html_vars_obj == null ) {
    	html_vars_obj = [];
    } 
    jQuery.each( html_vars_obj, function( key, value ) {
        if( typeof value.shipping_methods !== "undefined" ) {
            var shipping_methods = value.shipping_methods.split(",");
            for( i = 0; i < shipping_methods.length; i++ ) {
                if( shipping_method.indexOf( shipping_methods[ i ] ) !== -1 ) {
                    shipping_method = shipping_methods[ i ];
                }
            }
            var shipping_class = jQuery( "#orddd_shipping_class_settings_to_load" ).val();
        } else if ( typeof value.orddd_pickup_locations !== "undefined" ) {
            var shipping_methods = value.orddd_pickup_locations.split(",");
            for( i = 0; i < shipping_methods.length; i++ ) {
                if( shipping_method.indexOf( shipping_methods[ i ] ) !== -1 ) {
                    shipping_method = shipping_methods[ i ];
                }
            }
        } else {
            var shipping_methods = value.product_categories.split(",");
            shipping_method = jQuery( "#orddd_category_settings_to_load" ).val();
            shipping_class = "";
        }
    });

    if( shipping_method.indexOf( 'usps' ) !== -1 && (shipping_method.split(":").length ) < 3 ) {
        shipping_method = jQuery( "#orddd_zone_id" ).val() + ":" + shipping_method;
    }

    var monthValue = inst.selectedMonth+1;
    var dayValue = inst.selectedDay;
    var yearValue = inst.selectedYear;
    var all = dayValue + "-" + monthValue + "-" + yearValue;
    if( jQuery( "#time_slot_enable_for_shipping_method" ).val() == "on" ) {
        if( typeof( inst.id ) !== "undefined" ) {  
            var data = {
                current_date: all,
                shipping_method: shipping_method,
                pickup_location: pickup_location,
                shipping_class: shipping_class, 
                min_date: jQuery( "#orddd_min_date_set" ).val(),
                current_date_to_check: jQuery( "#orddd_current_date_set" ).val(),
                action: "check_for_time_slot_orddd"
            };
            var option_selected = jQuery( '#orddd_auto_populate_first_available_time_slot' ).val();
            jQuery( "#pickup_time_slot" ).attr("disabled", "disabled");
            jQuery( "#pickup_time_slot_field" ).attr( "style", "opacity: 0.5" );
            jQuery.post( jQuery( '#orddd_admin_url' ).val() + "admin-ajax.php", data, function( response ) {
                jQuery( "#pickup_time_slot_field" ).attr( "style" ,"opacity:1" );
                jQuery( "#pickup_time_slot" ).attr( "style", "cursor: pointer !important" );
                jQuery( "#pickup_time_slot" ).removeAttr( "disabled" ); 
                jQuery( "#pickup_time_slot" ).html( response );  
                if( option_selected == "on" ) {
                    jQuery( "body" ).trigger( "update_checkout" );
                } 
            });
        }
    } else if( jQuery( "#time_setting_enable_for_shipping_method" ).val() == "on" ) {
        if( typeof( inst.id ) !== "undefined" ) {  
            var orddd_disable_minimum_delivery_time_slider = jQuery( "#orddd_disable_minimum_delivery_time_slider" ).val();
            var tp_inst = jQuery.datepicker._get( inst, "timepicker" );
            if( "yes" != orddd_disable_minimum_delivery_time_slider ) {
                if( all == jQuery( "#orddd_current_day" ).val() || all == jQuery( "#orddd_min_date_set" ).val() ) {
                    inst.settings.hourMin = parseInt( jQuery( "#orddd_min_hour" ).val() );
                    tp_inst._defaults.hourMin = parseInt( jQuery( "#orddd_min_hour" ).val() );
                    inst.settings.minuteMin = parseInt( jQuery( "#orddd_min_minute" ).val() );
                    tp_inst._defaults.minuteMin = parseInt( jQuery( "#orddd_min_minute" ).val() );
                    tp_inst._limitMinMaxDateTime(inst, true);
                } else {
                    inst.settings.hourMin = parseInt( jQuery( "#orddd_min_hour_set" ).val() );
                    tp_inst._defaults.hourMin = parseInt( jQuery( "#orddd_min_hour_set" ).val() );
                    inst.settings.minuteMin = 0;
                    tp_inst._defaults.minuteMin = 0;
                    tp_inst._limitMinMaxDateTime(inst, true);
                }
            } else {
                inst.settings.hourMin = parseInt( jQuery( "#orddd_min_hour_set" ).val() );
                tp_inst._defaults.hourMin = parseInt( jQuery( "#orddd_min_hour_set" ).val() );
                inst.settings.minuteMin = 0;
                tp_inst._defaults.minuteMin = 0;
                tp_inst._limitMinMaxDateTime(inst, true);
            }
        } else if( typeof( inst.inst.id ) !== "undefined" )  {
            var monthValue = inst.inst.currentMonth+1;
            var dayValue = inst.inst.currentDay;
            var yearValue = inst.inst.currentYear;
            var all = dayValue + "-" + monthValue + "-" + yearValue;
            var tp_inst = jQuery.datepicker._get( inst.inst, "timepicker" );
            if( all == jQuery( "#orddd_current_day" ).val() || all == jQuery( "#orddd_min_date_set" ).val() ) {
                var time_format = jQuery( '#orddd_delivery_time_format' ).val();
                var split = inst.formattedTime.split( ":" );
                if( time_format == "12" ) {
                    var hour_time  = parseInt( split[ 0 ] ) + parseInt( 12 );
                } else {
                    var hour_time  = parseInt( split[ 0 ] );
                }
                if( hour_time == parseInt( jQuery( "#orddd_min_hour" ).val() ) ) {
                    inst._defaults.minuteMin = parseInt( jQuery( "#orddd_min_minute" ).val() );
                    inst.inst.settings.minuteMin = parseInt( jQuery( "#orddd_min_minute" ).val() );
                    tp_inst._defaults.minuteMin = parseInt( jQuery( "#orddd_min_minute" ).val() );
                    tp_inst._limitMinMaxDateTime( inst.inst, true );
                } else {
                    inst._defaults.minuteMin = 0;
                    inst.inst.settings.minuteMin = 0;
                    tp_inst._defaults.minuteMin = 0;
                    tp_inst._limitMinMaxDateTime( inst.inst, true );
                }
            }
        }
    } else if( jQuery( "#orddd_enable_time_slot" ).val() == "on"  ) {
        if( typeof( inst.id ) !== "undefined" ) {  
            var data = {
                current_date: all,
                shipping_method: shipping_method,
                pickup_location: pickup_location,
                shipping_class: shipping_class, 
                min_date: jQuery( "#orddd_min_date_set" ).val(),
                action: "check_for_time_slot_orddd"
            };
            var option_selected = jQuery( '#orddd_auto_populate_first_available_time_slot' ).val();
            jQuery( "#pickup_time_slot" ).attr("disabled", "disabled");
            jQuery( "#pickup_time_slot_field" ).attr( "style", "opacity: 0.5" );
            jQuery.post( jQuery( '#orddd_admin_url' ).val() + "admin-ajax.php", data, function( response ) {
                jQuery( "#pickup_time_slot_field" ).attr( "style" ,"opacity:1" );
                jQuery( "#pickup_time_slot" ).attr( "style", "cursor: pointer !important" );
                jQuery( "#pickup_time_slot" ).removeAttr( "disabled" ); 
                jQuery( "#pickup_time_slot" ).html( response );  
                if( option_selected == "on" ) {
                    jQuery( "body" ).trigger( "update_checkout" );
                } 
            });
        }
    } else if( jQuery( "#orddd_enable_time_slider" ).val() == "on" ) {
        if( typeof( inst.id ) !== "undefined" ) {  
            var tp_inst = jQuery.datepicker._get( inst, "timepicker" );
            if( all == jQuery( "#orddd_current_day" ).val() || all == jQuery( "#orddd_min_date_set" ).val() ) {
                inst.settings.hourMin = parseInt( jQuery( "#orddd_min_hour" ).val() );
                tp_inst._defaults.hourMin = parseInt( jQuery( "#orddd_min_hour" ).val() );
                inst.settings.minuteMin = parseInt( jQuery( "#orddd_min_minute" ).val() );
                tp_inst._defaults.minuteMin = parseInt( jQuery( "#orddd_min_minute" ).val() );
                tp_inst._limitMinMaxDateTime(inst, true);
            } else {
                inst.settings.hourMin = parseInt( jQuery( "#orddd_min_hour_set" ).val() );
                tp_inst._defaults.hourMin = parseInt( jQuery( "#orddd_min_hour_set" ).val() );
                inst.settings.minuteMin = 0;
                tp_inst._defaults.minuteMin = 0;
                tp_inst._limitMinMaxDateTime(inst, true);
            }
        } else if( typeof( inst.inst.id ) !== "undefined" )  {
            var monthValue = inst.inst.currentMonth+1;
            var dayValue = inst.inst.currentDay;
            var yearValue = inst.inst.currentYear;
            var all = dayValue + "-" + monthValue + "-" + yearValue;
            var tp_inst = jQuery.datepicker._get( inst.inst, "timepicker" );
            if( all == jQuery( "#orddd_current_day" ).val() || all == jQuery( "#orddd_min_date_set" ).val() ) {
                var time_format = jQuery( '#orddd_delivery_time_format' ).val();
                var split = inst.formattedTime.split( ":" );
                if( time_format == "12" ) {
                    var hour_time  = parseInt( split[ 0 ] ) + parseInt( 12 );
                } else {
                    var hour_time  = parseInt( split[ 0 ] );
                }
                if( hour_time == parseInt( jQuery( "#orddd_min_hour" ).val() ) ) {
                    inst._defaults.minuteMin = parseInt( jQuery( "#orddd_min_minute" ).val() );
                    inst.inst.settings.minuteMin = parseInt( jQuery( "#orddd_min_minute" ).val() );
                    tp_inst._defaults.minuteMin = parseInt( jQuery( "#orddd_min_minute" ).val() );
                    tp_inst._limitMinMaxDateTime( inst.inst, true );
                } else {
                    inst._defaults.minuteMin = 0;
                    inst.inst.settings.minuteMin = 0;
                    tp_inst._defaults.minuteMin = 0;
                    tp_inst._limitMinMaxDateTime( inst.inst, true );
                }
            }
        }
    }
}

function load_functions() {
	if( jQuery( '#orddd_enable_shipping_based_delivery' ).val() == "on" ) {
		load_pickup_date();
	}

	if( jQuery( "#orddd_enable_autofill_of_delivery_date" ).val() == "on" ) {
		orddd_autofil_date_time();
    }
}

function jsonConcat( o1, o2 ) {
    for ( var key in o2 ) {
        o1[ key ] = o2[ key ];
    }
    return o1;
}

function minimum_date_to_set( delay_days ) {
	var disabledDays = eval( "[" + jQuery( "#orddd_delivery_date_holidays" ).val() + "]" );
	var holidays = [];
	for ( i = 0; i < disabledDays.length; i++ ) {
		var holidays_array = disabledDays[ i ].split( ":" );
		holidays[i] = holidays_array[ 1 ];
	}
	
    var bookedDays = eval( "[" + jQuery( "#orddd_lockout_days" ).val() + "]" );
    
	var current_date = jQuery( "#orddd_current_day" ).val();
	var split_current_date = current_date.split( "-" );
	var current_day = new Date ( split_current_date[ 1 ] + "/" + split_current_date[ 0 ] + "/" + split_current_date[ 2 ] );
	
	var delay_time = delay_days.getTime();
    var current_time = current_day.getTime();
    var current_weekday = current_day.getDay();
    
    var delivery_day_3 = '';
	var specific_dates_sorted_array = new Array ();
	var specific_dates = jQuery( "#orddd_specific_delivery_dates" ).val();
    var deliveryDates = [];

    var is_all_past_dates = 'No';
	if ( specific_dates == "on" ) {
		var deliveryDates = eval('[' + jQuery( "#orddd_delivery_dates" ).val() + ']');
        if ( deliveryDates != '' ) {
			for ( sort = 0; sort < deliveryDates.length; sort++ ) {
				var split_delivery_date_1 = deliveryDates[sort].split( "-" );
			    var delivery_day_1 = new Date ( split_delivery_date_1[ 0 ] + "/" + split_delivery_date_1[ 1 ] + "/" + split_delivery_date_1[ 2 ] );
			    specific_dates_sorted_array[sort] = delivery_day_1.getTime();
			}
            
			specific_dates_sorted_array.sort( sortSpecificDates );
			for ( i = 0; i < specific_dates_sorted_array.length; i++ ) {
			    if ( specific_dates_sorted_array[i] >= current_day.getTime() ){
					delivery_day_3 = specific_dates_sorted_array[i];
					break;
			    }
	    	}

            var past_dates = [];
            for ( j = 0; j < deliveryDates.length; j++ ) {
                var split_delivery_date = deliveryDates[j].split( "-" );
                var delivery_date = new Date ( split_delivery_date[ 0 ] + "/" + split_delivery_date[ 1 ] + "/" + split_delivery_date[ 2 ] );
                if ( delivery_date.getTime() >= current_day.getTime() ){
                    past_dates[j] = deliveryDates[j];
                }
            }		

            if( past_dates.length == 0 ) {
                is_all_past_dates = 'Yes';
            }
	    } 
	}

	var j;
	if( 'on' == jQuery( '#orddd_enable_shipping_days' ).val() ) {
		var delay_weekday = delay_days.getDay();
		for ( j = delay_weekday ; ;j++ ) {
			day = 'orddd_weekday_' + delay_weekday;
            day_check = jQuery( "#" + day ).val();
            if ( day_check == '' ) {
                if( !jQuery.inArray( ( m+1 ) + "-" + d + "-" + y, deliveryDates ) == -1 && specific_dates == "on"  && deliveryDates.length > 0 )  {
                    delay_days.setDate( delay_days.getDate()+1 );
                    delay_weekday = delay_days.getDay();
                } else {
                    delay_days = current_day;
                    break;
                }
            } else {
                if( current_day <= delay_days ) {
                    var m = current_day.getMonth(), d = current_day.getDate(), y = current_day.getFullYear();
                    if( jQuery.inArray( ( m+1 ) + "-" + d + "-" + y, holidays ) != -1 ) {
                        delay_days.setDate( delay_days.getDate()+1 );
                        delay_time = delay_days.getTime();
                        delay_weekday = delay_days.getDay();
                    }
                    if( jQuery.inArray( ( m+1 ) + "-" + d + "-" + y, deliveryDates ) == -1 && specific_dates == "on"  && deliveryDates.length > 0 )  {
                        if ( typeof delivery_day_3 != "undefined" ) {
                            if( delivery_day_3 != current_day.getTime() && current_day.getTime() < delivery_day_3 ) {
                                delay_days.setDate( delay_days.getDate()+1 );
                                delay_time = delay_days.getTime();
                                delay_weekday = delay_days.getDay();
                            } else {
                                delay_days = current_day;
                                break;
                            }
                        } else {
                            break;
                        }
                    }
                    current_day.setDate( current_day.getDate()+1 );
                    current_time = current_day.getTime();
                    current_weekday = current_day.getDay();
                } else {
                    break;
                }
            }
		}
	} else {
		for ( j = current_weekday ; current_time <= delay_time ; j++ ) {
			if( j >= 0 ) {
				day = "orddd_weekday_" + current_weekday;
		    	day_check = jQuery( "#" + day ).val();
		    	if ( day_check == "" || typeof day_check == "undefined" ) {
		    		if ( jQuery( "#orddd_specific_delivery_dates" ).val() == "on" ) {
                        if( is_all_past_dates != 'Yes' || ( 'Yes' ==  is_all_past_dates && 'no' == jQuery( "#orddd_is_all_weekdays_disabled" ).val() ) ) {
                            if ( typeof delivery_day_3 != "undefined" ) {
                                if ( delivery_day_3 != current_day.getTime() ) {
                                    delay_days.setDate( delay_days.getDate()+1 );
                                    delay_time = delay_days.getTime();
                                    current_day.setDate( current_day.getDate()+1 );
                                    current_time = current_day.getTime();
                                    current_weekday = current_day.getDay();
                                } else {
                                    delay_days = current_day;
                                    break;
                                }
                            } else {
                                break;
                            }    
                        } else {
                            delay_days = '';
                            break;
                        }
		    		} else {
		    			delay_days.setDate( delay_days.getDate()+1 );
	    	    		delay_time = delay_days.getTime();
	    	    		current_day.setDate( current_day.getDate()+1 );
	    	    		current_time = current_day.getTime();
	    	    		current_weekday = current_day.getDay();
		    		} 	  
		    	} else {
		    		if( current_day <= delay_days ) {
		    			var m = current_day.getMonth(), d = current_day.getDate(), y = current_day.getFullYear();
		    			if ( jQuery( "#orddd_disable_for_holidays" ).val() != 'yes' ) {
                            if( jQuery.inArray( ( m+1 ) + "-" + d + "-" + y, holidays ) != -1 ) {
    		    				delay_days.setDate( delay_days.getDate()+1 );
    		    				delay_time = delay_days.getTime();
    		    			}
                        }
                        if( jQuery.inArray( ( m+1 ) + "-" + d + "-" + y, bookedDays ) != -1 ) {
                            delay_days.setDate( delay_days.getDate()+1 );
                            delay_time = delay_days.getTime();
                        }
		    			current_day.setDate( current_day.getDate()+1 );
		    			current_time = current_day.getTime();
		    			current_weekday = current_day.getDay();
		    		}
		    	}
			} else {
				break;
			}
		}
	}
	
    if( delay_days != '' ) {
        for ( i = 0; i < holidays.length; i++ ) {
            var dm = delay_days.getMonth(), dd = delay_days.getDate(), dy = delay_days.getFullYear();
            if( jQuery.inArray( ( dm+1 ) + "-" + dd + "-" + dy, holidays ) != -1 ) {
                delay_days.setDate( delay_days.getDate()+1 );
                delay_time = delay_days.getTime();
            }
        }

        var dm = delay_days.getMonth(), dd = delay_days.getDate(), dy = delay_days.getFullYear();
        if( jQuery.inArray( ( dm+1 ) + "-" + dd + "-" + dy, bookedDays ) != -1 ) {
            delay_days.setDate( delay_days.getDate()+1 );
            delay_time = delay_days.getTime();
        } 
    }
	return delay_days;
}

function same_day_next_day_to_set( current_day ) {
    var disabled_days_arr = [];
    
    if( jQuery( "#orddd_disabled_days_str" ).val() != "" && ( 'on' == jQuery( '#orddd_same_day_delivery' ).val() || 'on' == jQuery( '#orddd_next_day_delivery' ).val() ) ) {
        if( !( 'on' == jQuery( '#orddd_custom_based_same_day_delivery' ).val() || 'on' == jQuery( '#orddd_custom_based_next_day_delivery' ).val() ) ) {
            startDaysDisabled = [];
            disabled_days_arr = eval( "[" + jQuery( "#orddd_disabled_days_str" ).val() + "]" );
            startDaysDisabled = startDaysDisabled.concat( disabled_days_arr );
        }        
    } else {
        if ( !( 'on' == jQuery( '#orddd_custom_based_same_day_delivery' ).val() || 'on' == jQuery( '#orddd_custom_based_next_day_delivery' ).val() ) ) {
            startDaysDisabled = [];
        }
    }
    
	var disabledDays = eval( "[" + jQuery( "#orddd_delivery_date_holidays" ).val() + "]" );
	var holidays = [];
	for ( i = 0; i < disabledDays.length; i++ ) {
		var holidays_array = disabledDays[ i ].split( ":" );
		holidays[i] = holidays_array[ 1 ];
	}
	
    var bookedDays = eval( "[" + jQuery( "#orddd_lockout_days" ).val() + "]" );
    var delivery_day_3 = '';
	var specific_dates_sorted_array = new Array();
    var specific_dates = jQuery( "#orddd_specific_delivery_dates" ).val();
	var is_all_past_dates = 'No';
	if ( specific_dates == "on" ) {
		var deliveryDates = eval('[' + jQuery( "#orddd_delivery_dates" ).val() + ']');
		if ( deliveryDates != '' ) {
			for ( sort = 0; sort < deliveryDates.length; sort++ ) {
				var split_delivery_date_1 = deliveryDates[sort].split( "-" );
			    var delivery_day_1 = new Date ( split_delivery_date_1[ 0 ] + "/" + split_delivery_date_1[ 1 ] + "/" + split_delivery_date_1[ 2 ] );
			    specific_dates_sorted_array[sort] = delivery_day_1.getTime();
			}
			specific_dates_sorted_array.sort( sortSpecificDates );
			for ( i = 0; i < specific_dates_sorted_array.length; i++ ) {
			    if ( specific_dates_sorted_array[i] >= current_day.getTime() ){
					delivery_day_3 = specific_dates_sorted_array[i];
					break;
			    }
	    	}	

	    	var past_dates = [];
            for ( j = 0; j < deliveryDates.length; j++ ) {
                var split_delivery_date = deliveryDates[j].split( "-" );
                var delivery_date = new Date ( split_delivery_date[ 0 ] + "/" + split_delivery_date[ 1 ] + "/" + split_delivery_date[ 2 ] );
                if ( delivery_date.getTime() >= current_day.getTime() ){
                    past_dates[j] = deliveryDates[j];
                }
            }			
            if( past_dates.length == 0 ) {
                is_all_past_dates = 'Yes';
            }		
	    } 
	}
	
	var current_time = current_day.getTime();
    var current_weekday = current_day.getDay();
	var j;
    
    for ( j = current_weekday ;  j <= 6; ) {
		var m = current_day.getMonth(), d = current_day.getDate(), y = current_day.getFullYear();
		if( jQuery.inArray( ( m+1 ) + '-' + d + '-' + y, startDaysDisabled ) != -1 ) {
			current_day.setDate( current_day.getDate()+1 );
			j = current_day.getDay();
		} else if( jQuery.inArray( ( m+1 ) + '-' + d + '-' + y, bookedDays ) != -1 ) {
			current_day.setDate( current_day.getDate()+1 );
			j = current_day.getDay();
		} else if( jQuery.inArray( ( m+1 ) + "-" + d + "-" + y, holidays ) != -1 ) {
			current_day.setDate( current_day.getDate()+1 );
			j = current_day.getDay();
		} else {
			var shipping_day_check = '';
			if( jQuery( '#orddd_enable_shipping_days' ).val() == 'on' ) {
				shipping_day = 'orddd_weekday_' + j;
				shipping_day_check = jQuery( "#" + shipping_day ).val();
				if( typeof shipping_day_check == "undefined" ) {
					shipping_day_check = '';
				}
				if( ( shipping_day_check == "" || typeof shipping_day_check == "undefined" ) && jQuery( '#orddd_enable_shipping_days' ).val() == 'on' ) {
		    		if ( jQuery( "#orddd_specific_delivery_dates" ).val() == "on" ) {
		    			if( is_all_past_dates != 'Yes' || ( 'Yes' ==  is_all_past_dates && 'no' == jQuery( "#orddd_is_all_weekdays_disabled" ).val() ) ) {
                            if ( typeof delivery_day_3 != "undefined" ) {
                                if ( delivery_day_3 != current_day.getTime() && current_day.getTime() < delivery_day_3 ) {
                                    current_day.setDate( current_day.getDate()+1 );
		    						j = current_day.getDay();
                                } else {
                                    break;
                                }
                            } else {
                                break;
                            }    
                        } else {
                            current_day = '';
                            break;
                        }
		    		} else {
		    			current_day.setDate( current_day.getDate()+1 );
						j = current_day.getDay();
		    		} 	
		    	} else {
		    		break;
		    	}
			} else {
				day = "orddd_weekday_" + j;
			    day_check = jQuery( "#" + day ).val();
				if( day_check == "" || typeof day_check == "undefined" ) {
		    		if ( jQuery( "#orddd_specific_delivery_dates" ).val() == "on" ) {
		    			if( is_all_past_dates != 'Yes' || ( 'Yes' ==  is_all_past_dates && 'no' == jQuery( "#orddd_is_all_weekdays_disabled" ).val() ) ) {
                            if ( typeof delivery_day_3 != "undefined" ) {
                                if ( delivery_day_3 != current_day.getTime() && current_day.getTime() < delivery_day_3 ) {
                                    current_day.setDate( current_day.getDate()+1 );
		    						j = current_day.getDay();
                                } else {
                                    break;
                                }
                            } else {
                                break;
                            }    
                        } else {
                            current_day = '';
                            break;
                        }
		    		} else {
		    			current_day.setDate( current_day.getDate()+1 );
						j = current_day.getDay();
		    		} 	
		    	} else {
		    		break;
		    	}
			}
		}
	}

	if( current_day != '' ) {
		var current_weekday = current_day.getDay();
		var k;
		if( jQuery( "#orddd_next_day_delivery" ).val() == 'on' && ( jQuery( "#is_sameday_cutoff_reached" ).val() == 'yes' || 'undefined' == typeof jQuery( "#is_sameday_cutoff_reached" ).val() ) ) {
			for ( k = current_weekday ; k <= 6; ) {
				if( jQuery( "#is_nextday_cutoff_reached" ).val() == 'yes' )  {
					if( typeof( jQuery( '#orddd_after_cutoff_weekday' ).val() ) != "undefined" && jQuery( '#orddd_after_cutoff_weekday' ).val() != '' ) {
						var weekday = "orddd_weekday_" + current_day.getDay();
						var after_weekday = jQuery( '#orddd_after_cutoff_weekday' ).val();
						if( weekday != after_weekday ) {
							current_day.setDate( current_day.getDate()+1 );
							k = current_day.getDay();
						} else {
							break;
						}
					} else {
						break;
					}	
				} else {
					if( typeof( jQuery( '#orddd_before_cutoff_weekday' ).val() ) != "undefined" && jQuery( '#orddd_before_cutoff_weekday' ).val() != '' ) {
						var weekday = "orddd_weekday_" + current_day.getDay();
						var before_weekday = jQuery( '#orddd_before_cutoff_weekday' ).val();
						if( weekday != before_weekday ) {
							current_day.setDate( current_day.getDate()+1 );
							k = current_day.getDay();
						} else {
							break;
						}
					} else {
						break;
					}
				}
			}
		}
	}

    return current_day;
}

function show_pickup_times( date, inst ) {
    var monthValue = inst.selectedMonth+1;
    var dayValue = inst.selectedDay;
    var yearValue = inst.selectedYear;
    var all = dayValue + "-" + monthValue + "-" + yearValue;
    if( jQuery( "#orddd_enable_time_slot" ).val() == "on" ) {
        if( typeof( inst.id ) !== "undefined" ) {  
            var data = {
                current_date: all,
                order_id: jQuery( "#orddd_my_account_order_id" ).val(),
                min_date: jQuery( "#orddd_min_date_set" ).val(),
                current_date_to_check: jQuery( "#orddd_current_date_set" ).val(),
                action: "check_for_time_slot_orddd"
            };
            var option_selected = jQuery( '#orddd_auto_populate_first_available_time_slot' ).val();
            jQuery( "#pickup_time_slot" ).attr( "disabled", "disabled" );
            jQuery( "#pickup_time_slot_field" ).attr( "style", "opacity: 0.5" );
            jQuery.post( jQuery( '#orddd_admin_url' ).val() + "admin-ajax.php", data, function( response ) {
                jQuery( "#pickup_time_slot_field" ).attr( "style", "opacity: 1" );
                jQuery( "#pickup_time_slot" ).attr( "style", "cursor: pointer !important" );
                jQuery( "#pickup_time_slot" ).removeAttr( "disabled" ); 
                jQuery( "#pickup_time_slot" ).html( response );
                if( option_selected == "on" ) {
                    jQuery( "body" ).trigger( "update_checkout" );
                }  
            })
        }
    } else if( jQuery( "#orddd_enable_time_slider" ).val() == "on" ) { 
        if( typeof( inst.id ) !== "undefined" ) {  
            var tp_inst = jQuery.datepicker._get( inst, "timepicker" );
            var orddd_disable_minimum_delivery_time_slider = jQuery( "#orddd_disable_minimum_delivery_time_slider" ).val();
            if( 'yes' != orddd_disable_minimum_delivery_time_slider ) {
            	if( all == jQuery( "#orddd_current_day" ).val() || all == jQuery( "#orddd_min_date_set" ).val() ) {
                    var time_format = jQuery( '#orddd_delivery_time_format' ).val();
                    var split = tp_inst.formattedTime.split( ":" );
                    if( time_format == "12" ) {
                        var hour_time  = parseInt( split[ 0 ] ) + parseInt( 12 );
                    } else {
                        var hour_time  = parseInt( split[ 0 ] );
                    }                      
                    inst.settings.hourMin = parseInt( jQuery( "#orddd_min_hour" ).val() );
                    tp_inst._defaults.hourMin = parseInt( jQuery( "#orddd_min_hour" ).val() );
                    if( hour_time == parseInt( jQuery( "#orddd_min_hour" ).val() ) ) {
                        inst.settings.minuteMin = parseInt( jQuery( "#orddd_min_minute" ).val() );
                        tp_inst._defaults.minuteMin = parseInt( jQuery( "#orddd_min_minute" ).val() );
                    } else {
                        inst.settings.minuteMin = 0;
                        tp_inst._defaults.minuteMin = 0;
                    }
                    tp_inst._limitMinMaxDateTime(inst, true);
                    
                } else {
                    inst.settings.hourMin = parseInt( jQuery( "#orddd_delivery_from_hours" ).val() );
                    tp_inst._defaults.hourMin = parseInt( jQuery( "#orddd_delivery_from_hours" ).val() );
                    inst.settings.minuteMin = 0;
                    tp_inst._defaults.minuteMin = 0;
                    tp_inst._limitMinMaxDateTime(inst, true);
                }
            } else {
            	var time_format = jQuery( '#orddd_delivery_time_format' ).val();
                var split = tp_inst.formattedTime.split( ":" );
                if( time_format == "12" ) {
                    var hour_time  = parseInt( split[ 0 ] ) + parseInt( 12 );
                } else {
                    var hour_time  = parseInt( split[ 0 ] );
                }  
                inst.settings.hourMin = parseInt( jQuery( "#orddd_delivery_from_hours" ).val() );
                tp_inst._defaults.hourMin = parseInt( jQuery( "#orddd_delivery_from_hours" ).val() );
                inst.settings.minuteMin = 0;
                tp_inst._defaults.minuteMin = 0;
                tp_inst._limitMinMaxDateTime(inst, true);
            }
        } else if( typeof( inst.inst.id ) !== "undefined" )  {
            var monthValue = inst.inst.currentMonth+1;
            var dayValue = inst.inst.currentDay;
            var yearValue = inst.inst.currentYear;
            var all = dayValue + "-" + monthValue + "-" + yearValue;
            var tp_inst = jQuery.datepicker._get( inst.inst, "timepicker" );
            if( all == jQuery( "#orddd_current_day" ).val() || all == jQuery( "#orddd_min_date_set" ).val() ) {
                var time_format = jQuery( '#orddd_delivery_time_format' ).val();
                var split = inst.formattedTime.split( ":" );
                if( time_format == "12" ) {
                    var hour_time  = parseInt( split[ 0 ] ) + parseInt( 12 );
                } else {
                    var hour_time  = parseInt( split[ 0 ] );
                }
                if( hour_time == parseInt( jQuery( "#orddd_min_hour" ).val() ) ) {
                    inst._defaults.minuteMin = parseInt( jQuery( "#orddd_min_minute" ).val() );
                    inst.inst.settings.minuteMin = parseInt( jQuery( "#orddd_min_minute" ).val() );
                    tp_inst._defaults.minuteMin = parseInt( jQuery( "#orddd_min_minute" ).val() );
                    tp_inst._limitMinMaxDateTime( inst.inst, true );
                } else {
                    inst._defaults.minuteMin = 0;
                    inst.inst.settings.minuteMin = 0;
                    tp_inst._defaults.minuteMin = 0;
                    tp_inst._limitMinMaxDateTime( inst.inst, true );
                }
            }
        }
    }
}

function show_admin_times( date, inst ) {
    var shipping_class = "";
    var shipping_method_id = jQuery( "input[name=\"shipping_method_id[]\"]" ).val();
    if( typeof shipping_method_id === "undefined" ) {
        var shipping_method_id = "";
    }
    var shipping_method = jQuery( "select[name=\"shipping_method[" + shipping_method_id + "]\"]" ).find(":selected").val();
    if( typeof shipping_method === "undefined" ) {
        var shipping_method = "";
    }
    var hidden_var_obj = jQuery("#orddd_hidden_vars_str").val();
    var html_vars_obj = jQuery.parseJSON( hidden_var_obj );
    if( html_vars_obj == null ) {
        html_vars_obj = [];
    } 
    var time_enable = "";

    jQuery.each( html_vars_obj, function( key, value ) {
        if( typeof value.shipping_methods !== "undefined" ) {
            var shipping_methods = value.shipping_methods.split(",");
            for( i = 0; i < shipping_methods.length; i++ ) {
                if( shipping_method.indexOf( shipping_methods[ i ] ) !== -1 ) {
                    shipping_method = shipping_methods[ i ];
                }
            }
            var shipping_class = jQuery( "#orddd_shipping_class_settings_to_load" ).val(); 
        } else if ( typeof value.orddd_pickup_locations !== "undefined" ) {
            var shipping_methods = value.orddd_pickup_locations.split(",");
            for( i = 0; i < shipping_methods.length; i++ ) {
                if( shipping_method.indexOf( shipping_methods[ i ] ) !== -1 ) {
                    shipping_method = shipping_methods[ i ];
                }
            }
        } else {
            var shipping_methods = value.product_categories.split(",");
            shipping_method = jQuery( "#orddd_category_settings_to_load" ).val();
            shipping_class = "";
        }
        
        if ( jQuery.inArray( shipping_method, shipping_methods ) !== -1 || jQuery.inArray( shipping_class, shipping_methods ) !== -1 ) {
            if ( value.time_slots == "on" ) {
                time_enable = value.time_slots;    
            } 
        }
    });
    var monthValue = inst.selectedMonth+1;
    var dayValue = inst.selectedDay;
    var yearValue = inst.selectedYear;
    var all = dayValue + "-" + monthValue + "-" + yearValue;
    if( jQuery( "#orddd_enable_time_slot" ).val() == "on" || time_enable == "on" ) {
        if( typeof( inst.id ) !== "undefined" ) {  
            var data = {
                current_date: all,
                shipping_method: shipping_method,
                shipping_class: shipping_class,
                order_id: jQuery( "#orddd_order_id" ).val(),
                min_date: jQuery( "#orddd_min_date_set" ).val(),
                action: "check_for_time_slot_orddd"
            };
            jQuery( "#pickup_time_slot" ).attr("disabled", "disabled");
            jQuery( "#pickup_time_slot_field" ).attr( "style", "opacity: 0.5" );
            jQuery.post( jQuery( '#orddd_admin_url' ).val() + "admin-ajax.php", data, function( response ) {
                jQuery( "#pickup_time_slot_field" ).attr( "style" ,"opacity:1" );
                jQuery( "#pickup_time_slot" ).attr( "style", "cursor: pointer !important" );
                jQuery( "#pickup_time_slot" ).removeAttr( "disabled" ); 
                jQuery( "#pickup_time_slot" ).html( response );  
            });
        }
    } else if( jQuery( "#orddd_enable_time_slider" ).val() == "on" ) {
        if( typeof( inst.id ) !== "undefined" ) {  
            var tp_inst = jQuery.datepicker._get( inst, "timepicker" );
            if( all == jQuery( "#orddd_current_day" ).val() || all == jQuery( "#orddd_current_day" ).val() ) {
                inst.settings.hourMin = parseInt( jQuery( "#orddd_min_hour" ).val() );
                tp_inst._defaults.hourMin = parseInt( jQuery( "#orddd_min_hour" ).val() );
                inst.settings.minuteMin = parseInt( jQuery( "#orddd_min_minute" ).val() );;
                tp_inst._defaults.minuteMin = parseInt( jQuery( "#orddd_min_minute" ).val() );;
                tp_inst._limitMinMaxDateTime(inst, true);
            } else {
                inst.settings.hourMin = parseInt( jQuery( "#orddd_delivery_from_hours" ).val() );
                tp_inst._defaults.hourMin = parseInt( jQuery( "#orddd_delivery_from_hours" ).val() );
                inst.settings.minuteMin = 0;
                tp_inst._defaults.minuteMin = 0;
                tp_inst._limitMinMaxDateTime(inst, true);
            }
        } else if( typeof( inst.inst.id ) !== "undefined" )  {
            var monthValue = inst.inst.currentMonth+1;
            var dayValue = inst.inst.currentDay;
            var yearValue = inst.inst.currentYear;
            var all = dayValue + "-" + monthValue + "-" + yearValue;
            var tp_inst = jQuery.datepicker._get( inst.inst, "timepicker" );
            if( all == jQuery( "#orddd_current_day" ).val() || all == jQuery( "#orddd_min_date_set" ).val() ) {
                var time_format = jQuery( "#orddd_delivery_time_format" ).val();
                var split = inst.formattedTime.split( ":" );
                if( time_format == "12" ) {
                    var hour_time  = parseInt( split[ 0 ] ) + parseInt( 12 );
                } else {
                    var hour_time  = parseInt( split[ 0 ] );
                }
                if( hour_time == parseInt( jQuery( "#orddd_min_hour" ).val() ) ) {
                    inst._defaults.minuteMin = parseInt( jQuery( "#orddd_min_minute" ).val() );
                    inst.inst.settings.minuteMin = parseInt( jQuery( "#orddd_min_minute" ).val() );
                    tp_inst._defaults.minuteMin = parseInt( jQuery( "#orddd_min_minute" ).val() );
                    tp_inst._limitMinMaxDateTime( inst.inst, true );
                } else {
                    inst._defaults.minuteMin = 0;
                    inst.inst.settings.minuteMin = 0;
                    tp_inst._defaults.minuteMin = 0;
                    tp_inst._limitMinMaxDateTime( inst.inst, true );
                }
            }
        }
    }
}