<?php
require_once( get_template_directory() . esc_attr( "/options_divi.php" ) );
global $options;
$enable_key = "name";
$enable_value = "Show Google+ Icon";
$enable_options = array (
           
	array( "name" => esc_html__( "Show Instagram Icon", $themename ),
           "id" => $shortname."_show_instagram_icon",
           "type" => "checkbox",
           "std" => "off",
           "desc" => esc_html__( "Here you can choose to display the Instagram Icon. ", $themename ) ),
    
    array( "name" => esc_html__( "Show YouTube Icon", $themename ),
           "id" => $shortname."_show_youtube_icon",
           "type" => "checkbox",
           "std" => "off",
           "desc" => esc_html__( "Here you can choose to display the Youtube Icon. ", $themename ) ),
                  
);
foreach( $options as $index => $value ) {
    if ( isset($value[$enable_key]) && $value[$enable_key] === $enable_value ) {
        foreach( $enable_options as $custom_index => $enable_option ) {
            $options = insertArrayIndex($options, $enable_option, $index+$custom_index+1);
        }
        break;
    }
}
$url_key = "name";
$url_value = "Google+ Profile Url";
$value_options = array (
		   
	array( "name" => esc_html__( "Instagram Profile Url", $themename ),
           "id" => $shortname."_instagram_url",
           "std" => "#",
           "type" => "text",
           "validation_type" => "url",
		   "desc" => esc_html__( "Enter the URL of your Instagram Profile. ", $themename ) ),
    
    array( "name" => esc_html__( "YouTube Profile Url", $themename ),
           "id" => $shortname."_youtube_url",
           "std" => "#",
           "type" => "text",
           "validation_type" => "url",
		   "desc" => esc_html__( "Enter the URL of your Youtube Profile. ", $themename ) )
		   
);
foreach( $options as $index => $value ) {
    if ( isset($value[$url_key]) && $value[$url_key] === $url_value ) {
        foreach( $value_options as $custom_index => $value_option ) {
            $options = insertArrayIndex($options, $value_option, $index+$custom_index+1);
        }
        break;
    }
}
function insertArrayIndex($array, $new_element, $index) {
	$start = array_slice($array, 0, $index);
	$end = array_slice($array, $index);
	$start[] = $new_element;
	return array_merge($start, $end);
}
return $options;
?>