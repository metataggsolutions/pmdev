jQuery(document).ready( function(){ 
jQuery('.variations_tab a').on('click', function(){
    jQuery('.woocommerce_variation h3').find('strong:first').hide();
});    
});