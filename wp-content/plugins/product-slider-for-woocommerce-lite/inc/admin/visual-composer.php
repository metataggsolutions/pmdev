<?php defined('ABSPATH') or die('No script kiddies please!!');
if (!class_exists('PSFW_VISUAL_COMPOSER')) {

    /**
     * Visual Composer Create Elements For Tab Widget
     * Example: http://www.wpelixir.com/how-to-create-new-element-in-visual-composer/
     */
    class PSFWL_VISUAL_COMPOSER extends PSFWL_Class{

        function __construct() {
            add_action('vc_before_init', array($this, 'psfw_vc_integrate_widget'));
            add_shortcode('psfw', array($this, 'psfw_vc_widget_html'));
        }

       public function psfw_vc_integrate_widget() {
        // Require new custom Element
            $args = array(
                        'post_type' => 'productsliderwoo',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'order' => 'ASC', 
                        'orderby' => 'id'
                    );
                    $posts = get_posts($args);
                   // $this->print_array($posts);
            if (!empty($posts)) {
                foreach ($posts as $post) {
                    $psfw_post_types[$post-> post_title] = $post-> ID;
                }
            }else{
                $psfw_post_types[ __('No Tab Data found.', PSFWL_TD)] = '';
            }

          vc_map(array(
            'name' => 'Product Slider For WooCommerce Lite Widget',
            'base' => 'psfw',
            'description' => esc_html__( 'Advanced Slider For WooCommerce Products', PSFWL_TD ),
            'category' => 'Product Slider For WooCommerce Lite',
            'icon' => '',
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__( 'Title', PSFWL_TD ),
                    'param_name' => 'title',
                    'holder' => 'h3'
                ),
              array(
                    'type' => 'dropdown',
                    'heading' => esc_html__( 'Product Slider For WooCommerce Lite', PSFWL_TD ),
                    'param_name' => 'id',
                    'class' => 'psfw-lists',
                    'save_always' => true,
                    'value' => $psfw_post_types,
                    'description' => esc_html__( 'Select any tab post type to add it to your post or page.', PSFWL_TD ),
                )
            )
            ));
        }

        // Element HTML
        public function psfw_vc_widget_html($atts){
             // Params extraction
            extract(
                shortcode_atts(
                    array(
                        'title'   => '',
                        'id' => '',
                    ), 
                    $atts
                )
            );
            ob_start();
            include( PSFWL_PATH . '/inc/frontend/psfw-frontend.php' );
            $productsliderwoo = ob_get_contents();
            ob_end_clean();
            return $productsliderwoo;
        } 
    }
    new PSFWL_VISUAL_COMPOSER();
}