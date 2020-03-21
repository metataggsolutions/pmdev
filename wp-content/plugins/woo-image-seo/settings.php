<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Check if hidden nonce field is valid
// Check if user is administrator
if ( isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'nonce') && current_user_can('administrator') ) {
	
	// Clearn the $_POST variable from NONCE elements
	unset($_POST['_wpnonce']);
	unset($_POST['_wp_http_referer']);
    
	// save $_POST in JSON format
    update_option( 'woo_image_seo', json_encode($_POST, JSON_NUMERIC_CHECK) );
}

// Set default settings var
$default_settings = '{"alt":{"enable":1,"force":0,"text":{"1":"[none]","2":"[name]","3":"[none]"}},"title":{"enable":1,"force":1,"text":{"1":"[none]","2":"[name]","3":"[none]"}}}';

// Check if settings are saved in DB
if ( !get_option( 'woo_image_seo' ) ) { // if no settings are found - apply default ones
    update_option( 'woo_image_seo', $default_settings );
}

// decode JSON settings string
$settings = json_decode( get_option( 'woo_image_seo' ), true);

?>

<style>
.postbox {
    padding: 10px 20px 20px;
    margin: 20px 0;
    font-size: 16px;
    line-height: 1.5em;
}
fieldset {
    border: 1px dotted #999999;
    padding: 25px;
    margin: 25px 0;
    font-size: 20px;
}
label {
	display: inline-block;
	font-size: 17px;
	line-height: 1.5em;
	position: relative;
	padding-left: 35px;
	cursor: pointer;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}
label:first-of-type {
    margin-bottom: 20px;
}
input[type="checkbox"] {
	display:none;
}
.checkmark {
	position: absolute;
	top: 0;
	left: 0;
	height: 25px;
	width: 25px;
	background-color: #eee;
}
label:hover input[type="checkbox"] ~ .checkmark {
  background-color: #ccc;
}
input[type="checkbox"]:checked ~ .checkmark {
  background-color: #0073aa !important;
}
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}
input[type="checkbox"]:checked ~ .checkmark:after {
  display: block;
}
.checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
input[type="submit"],
input[type="button"] {
    cursor: pointer;
    color: white;
    border: none;
    padding: 5px 10px;
    margin-top: 40px;
    box-shadow: 0 1px 1px rgba(0,0,0,.7);
}
input[type="submit"]:hover,
input[type="button"]:hover {
    box-shadow: 0 1px 2px black;
}
input[type="submit"] {
    font-size: 16px;
    background: #0073aa;
    margin-right: 20px;
	width: 125px;
}
#reset-settings {
    font-size: 14px;
    background: #aa0000;
}
input[disabled] {
    cursor: wait !important;
    background: #6d6d6d !important;
    box-shadow: inset 0 0px 2px black !important;
}
input[type=checkbox]:checked:before,
input[type=radio]:checked:before {
    display:none;
}
.text-select {
    margin-top: 20px;
	padding: 0;
}
label span {
    vertical-align: middle;
}
#woo_image_seo_form select {
    font-size: 17px;
    line-height: 3em;
    height: unset;
	padding: 3px;
}
#post-success {
    background: #0cad21;
    color: white;
    font-size: 20px;
    padding: 20px;
    box-shadow: 0 0 1px rgba(0,0,0,.75);
}
#woo_image_seo_form .dashicons-editor-help {
    vertical-align: middle;
    cursor: pointer;
    color: #333;
}
#woo_image_seo_form .dashicons-editor-help:hover {
    transform: scale(1.2);
}
</style>
<div class="wrap">
    <div class="postbox">
        <h1>Woo Image SEO</h1>
        <form action="admin.php?page=woo_image_seo" method="post" id="woo_image_seo_form">
            <div class="wrap">
                <fieldset>
                    <legend>Alt attribute settings</legend>
                    <input type="checkbox" class="hidden" name="alt[enable]" value="0" checked>
                    <input type="checkbox" class="hidden" name="alt[force]" value="0" checked>
                    <label>
                        <input type="checkbox" name="alt[enable]" value="1" <?php if ($settings['alt']['enable'] === 1) echo "checked"; ?>> Enable automatic alt attribute?
                        <span class="checkmark"></span>
                    </label>
					<br>
                    <label>
                        <input type="checkbox" name="alt[force]" value="1" <?php if ($settings['alt']['force'] === 1) echo "checked"; ?>> Force alt attribute?
                        <span class="checkmark"></span>
						<a href="#force-help" class="dashicons dashicons-editor-help"></a>
                    </label>
					<br>
                    <label class="text-select">
						<span>Attribute builder: </span>
                        <select name="alt[text][1]">
							<option value="[none]" <?php if ($settings['alt']['text'][1] === '[none]') echo "selected"; ?>>Empty</option>
                            <option value="[name]" <?php if ($settings['alt']['text'][1] === '[name]') echo "selected"; ?>>Product Name</option>
							<option value="[category]" <?php if ($settings['alt']['text'][1] === '[category]') echo "selected"; ?>>First Category</option>
							<option value="[tag]" <?php if ($settings['alt']['text'][1] === '[tag]') echo "selected"; ?>>First Tag</option>
                        </select>
						<select name="alt[text][2]">
							<option value="[none]" <?php if ($settings['alt']['text'][2] === '[none]') echo "selected"; ?>>Empty</option>
                            <option value="[name]" <?php if ($settings['alt']['text'][2] === '[name]') echo "selected"; ?>>Product Name</option>
							<option value="[category]" <?php if ($settings['alt']['text'][2] === '[category]') echo "selected"; ?>>First Category</option>
							<option value="[tag]" <?php if ($settings['alt']['text'][2] === '[tag]') echo "selected"; ?>>First Tag</option>
                        </select>
						<select name="alt[text][3]">
							<option value="[none]" <?php if ($settings['alt']['text'][3] === '[none]') echo "selected"; ?>>Empty</option>
                            <option value="[name]" <?php if ($settings['alt']['text'][3] === '[name]') echo "selected"; ?>>Product Name</option>
							<option value="[category]" <?php if ($settings['alt']['text'][3] === '[category]') echo "selected"; ?>>First Category</option>
							<option value="[tag]" <?php if ($settings['alt']['text'][3] === '[tag]') echo "selected"; ?>>First Tag</option>
                        </select>
						<a href="#attribute-builder-help" class="dashicons dashicons-editor-help"></a>
                    </label>
                </fieldset>
                <fieldset>
                    <legend>Title attribute settings</legend>
                    <input type="checkbox" class="hidden" name="title[enable]" value="0" checked>
                    <input type="checkbox" class="hidden" name="title[force]" value="0" checked>
                    <label>
                        <input type="checkbox" name="title[enable]" value="1" <?php if ($settings['title']['enable'] === 1) echo "checked"; ?>> Enable automatic title attribute?
                        <span class="checkmark"></span>
                    </label>
					<br>
                    <label>
                        <input type="checkbox" name="title[force]" value="1" <?php if ($settings['title']['force'] === 1) echo "checked"; ?>> Force title attribute? (recommended)
                        <span class="checkmark"></span>
						<a href="#force-help" class="dashicons dashicons-editor-help"></a>
                    </label>
					<br>
                    <label class="text-select">
						<span>Attribute builder: </span>
                        <select name="title[text][1]">
							<option value="[none]" <?php if ($settings['title']['text'][1] === '[none]') echo "selected"; ?>>Empty</option>
                            <option value="[name]" <?php if ($settings['title']['text'][1] === '[name]') echo "selected"; ?>>Product Name</option>
							<option value="[category]" <?php if ($settings['title']['text'][1] === '[category]') echo "selected"; ?>>First Category</option>
							<option value="[tag]" <?php if ($settings['title']['text'][1] === '[tag]') echo "selected"; ?>>First Tag</option>
                        </select>
						<select name="title[text][2]">
							<option value="[none]" <?php if ($settings['title']['text'][2] === '[none]') echo "selected"; ?>>Empty</option>
                            <option value="[name]" <?php if ($settings['title']['text'][2] === '[name]') echo "selected"; ?>>Product Name</option>
							<option value="[category]" <?php if ($settings['title']['text'][2] === '[category]') echo "selected"; ?>>First Category</option>
							<option value="[tag]" <?php if ($settings['title']['text'][2] === '[tag]') echo "selected"; ?>>First Tag</option>
                        </select>
						<select name="title[text][3]">
							<option value="[none]" <?php if ($settings['title']['text'][3] === '[none]') echo "selected"; ?>>Empty</option>
                            <option value="[name]" <?php if ($settings['title']['text'][3] === '[name]') echo "selected"; ?>>Product Name</option>
							<option value="[category]" <?php if ($settings['title']['text'][3] === '[category]') echo "selected"; ?>>First Category</option>
							<option value="[tag]" <?php if ($settings['title']['text'][3] === '[tag]') echo "selected"; ?>>First Tag</option>
                        </select>
						<a href="#attribute-builder-help" class="dashicons dashicons-editor-help"></a>
                    </label>
                </fieldset>
            </div>
            <input type="submit" value="Save Settings">
            <input type="button" value="Reset to Default" id="reset-settings">
			<?php wp_nonce_field( 'nonce' ); ?>
        </form>
    </div>
    <div id="post-success" class="hidden">Settings Saved!</div>
    <div id="force-help" class="postbox">
        <h2>How does "Force attribute" work?</h2>
        <strong>If the setting is disabled:</strong><br>
        The plugin will only set the attribute to images that don't have one.<br>
        This is useful if you wish to set your own attributes for individual images.<br>
        <hr>
        <strong>If the setting is enabled:</strong><br>
        The plugin will set the attribute to all images, even if they already have one.<br>
        This is especially useful for the "title" attribute because WordPress generates title attributes automatically using the file name.<br>
        Example:<br>
        You upload an image with the file name "pic3.jpg".<br>
        WordPress will automatically set a title attribute of "pic3".<br>
        <img src="<?php echo plugin_dir_url( __FILE__ ) . 'force-help.png'; ?>"><br>
        This isn't great if you have not optimized your image file names.<br>
        However, if you have manually set proper titles you may want to disable the setting.
    </div>
	<div id="attribute-builder-help" class="postbox">
        <h2>How does the Attribute builder work?</h2>
        The attribute builder let's you customize the attributes that the plugin will set to your product images.<br>
		Example:<br>
		You have a product called "Amazing Avengers Shirt", with main category called "Movie-Inspired Clothing" and two tags "men's clothing" and "avengers".<br>
		By default, the plugin will build the image attribute using only the Product Name, so your images will have "Amazing Avengers Shirt" as attributes.<br>
		If you wish, you can choose to change this by using the drop-down options.<br>
		There are three drop-down positions that allows you to order the way your attribute is formed.<br>
		Let's say you want to include the product's category before its name.<br>
		You would need to change the first option to "First Category", so the setting look like this:<br>
		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'attribute-builder-help.png'; ?>"><br>
		This will result in the following attribute: "Movie-Inspired Clothing Amazing Avengers Shirt".<br>
		You can also choose to include the first tag in the end, resulting in: "Movie-Inspired Clothing Amazing Avengers Shirt men's clothing".
    </div>
</div>

<script>
jQuery(document).ready(function() {

    // AJAX form submission
    jQuery('#woo_image_seo_form').submit(function(e){
        e.preventDefault();
        var data = jQuery(this).serializeArray();

        jQuery.ajax({
                    type: 'POST',
					data: data,
                    beforeSend: function() {
                        jQuery('#woo_image_seo_form input').attr('disabled', 'disabled');
                        jQuery('input[type="submit"], input[type="button"]').attr('value', 'Please wait...');
                    },
                    success: function(){
                        jQuery('#woo_image_seo_form input').removeAttr('disabled');
                        jQuery('input[type="submit"]').attr('value', 'Save Settings');
						jQuery("#reset-settings").attr('value', 'Reset to Default');
                        jQuery('#post-success').text('Settings Saved!').removeClass('hidden');
                        setTimeout(function(){ jQuery('#post-success').addClass('hidden'); }, 3000);
                    },
                    error: function( jqXhr, textStatus, errorThrown ){
                        console.log( errorThrown );
                    }
        });
        
    });
    
    
    // AJAX Reset Settings
    jQuery("#reset-settings").click(function() {
		// Prepare the default settings by adding WP NONCE fields
		var defaultSettings = JSON.parse('<?php echo $default_settings; ?>');
		defaultSettings['_wpnonce'] = jQuery('[name="_wpnonce"]').val();
		defaultSettings['_wp_http_referer'] = jQuery('[name="_wp_http_referer"]').val();
		
        jQuery.ajax({
                    type: 'POST',
                    data: defaultSettings,
                    beforeSend: function() {
                        jQuery('#woo_image_seo_form input').attr('disabled', 'disabled');
                        jQuery('input[type="submit"], input[type="button"]').attr('value', 'Please wait...');
                    },
                    success: function(data){
						// Replace the form with the new one
						jQuery("#woo_image_seo_form .wrap").html(jQuery("#woo_image_seo_form .wrap", data));
                        jQuery('#woo_image_seo_form input').removeAttr('disabled');
                        jQuery('input[type="submit"]').attr('value', 'Save Settings');
						jQuery("#reset-settings").attr('value', 'Reset to Default');
                        jQuery('#post-success').text('Default settings applied!').removeClass('hidden');
                        setTimeout(function(){ jQuery('#post-success').addClass('hidden'); }, 3000);
                    },
                    error: function( jqXhr, textStatus, errorThrown ){
                        console.log( errorThrown );
                    }
        });
    })
    
    

});

</script>