<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );

class psfw_Widget extends WP_Widget{

    public function __construct(){
        parent::__construct( 'psfw_Widget', 'Product Slider For WooCommerce Lite', array( 'description' => __( 'Product Slider For WooCommerce Lite Widget', PSFWL_TD ) ) );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ){

        echo $args[ 'before_widget' ];
        echo '<div class="psfw-widget-wrap">';
        if ( ! empty( $instance[ 'title' ] ) ) {
            echo $args[ 'before_title' ] . apply_filters( 'widget_title', $instance[ 'title' ] ) . $args[ 'after_title' ];
        }
        $psfw_slug = isset( $instance[ 'slug' ] ) ? $instance[ 'slug' ] : '';
        echo do_shortcode( "[psfw slug='" . $psfw_slug . "']" );
        echo '</div>';
        echo $args[ 'after_widget' ];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ){
        global $post;
        $title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : '';
        $slug = isset( $instance[ 'psfw_slug' ] ) ? $instance[ 'psfw_slug' ] : '';
        ?>
        <p>
            <label for="<?php echo $this -> get_field_id( 'title' ); ?>"><?php _e( 'Title:', PSFWL_TD ); ?></label>
            <input class="widefat" id="<?php echo $this -> get_field_id( 'title' ); ?>" name="<?php echo $this -> get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this -> get_field_id( 'psfw_id' ); ?>"><?php _e( 'Select Product Slider For WooCommerce Lite:', PSFWL_TD ); ?></label>
            <select name="<?php echo $this -> get_field_name( 'psfw_id' ); ?>" class='widefat' id="<?php echo $this -> get_field_id( 'psfw_id' ); ?>" type="text">
                <?php
                $psfw_id = get_terms( 'psfw_id', array( 'order' => 'ASC', 'orderby' => 'id' ) );
                $args = array(
                    'post_type' => 'productsliderwoo',
                    'post_status' => 'publish',
                    'posts_per_page' => -1
                );
                $posts = get_posts( $args );
                if ( ! empty( $posts ) ) {

                    foreach ( $posts as $post ) {
                        ?>

                        <option value="<?php echo $post -> post_name; ?>" <?php if ( $post -> post_name == $slug ) { ?>selected="selected"<?php } ?>><?php echo $post -> post_title; ?></option>

                        <?php
                    }
                }$post
                ?>
            </select>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ){
        $instance = $old_instance;
        $instance[ 'title' ] = isset( $new_instance[ 'title' ] ) ? strip_tags( $new_instance[ 'title' ] ) : '';
        $instance[ 'slug' ] = isset( $new_instance[ 'psfw_id' ] ) ? strip_tags( $new_instance[ 'psfw_id' ] ) : '';
        return $instance;
    }
}