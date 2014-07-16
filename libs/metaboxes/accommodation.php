<?php
/**
 * Create a front-end submission form for CMB which creates new posts/post-type entries.
 *
 * @package  Custom Metaboxes and Fields for WordPress
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

class AccommodationForm {

    // Set prefix
    public $prefix = ''; // Change this to your prefix


    /**
     * Construct the class.
     */
    public function __construct() {
        add_filter( 'cmb_meta_boxes', array( $this, 'cmb_metaboxes' ) );
        add_shortcode( 'cmb-form', array( $this, 'do_frontend_form' ) );
        add_action( 'init', array( $this, 'initialize_cmb_meta_boxes' ), 9 );
        add_action( 'cmb_save_post_fields', array( $this, 'save_featured_image' ), 10, 4 );
    }


    /**
     * Define the metabox and field configurations.
     */
    public function cmb_metaboxes( array $meta_boxes ) {

        /**
         * Metabox for the "request" front-end submission form
         */
        $meta_boxes['accommodations_metabox'] = array(
            'id'         => 'details',
            'title'      => __( 'Accommodation details', 'cmb' ),
            'pages'      => array( 'accommodation' ), // Post type
            'context'    => 'normal',
            'priority'   => 'high',
            'show_names' => true, // Show field names on the left
            'fields'     => array(
                array(
                    'name' => __( 'Place Name', 'cmb' ),
                    'desc' => __( '', 'cmb' ),
                    'id'   => $this->prefix . 'place_name',
                    'type' => 'text_medium',
                ),
                array(
                    'name' => __( 'Address', 'cmb' ),
                    'desc' => __( '', 'cmb' ),
                    'id'   => $this->prefix . 'address',
                    'type' => 'text_medium',
                    'class'=> 'pippo'
                ),

                array(
                    'name' => __( 'Lat', 'cmb' ),
                    'desc' => __( '', 'cmb' ),
                    'id'   => $this->prefix . 'lat',
                    'type' => 'text_small',
                    'class'=> 'pippo'
                ),

                array(
                    'name' => __( 'Long', 'cmb' ),
                    'desc' => __( '', 'cmb' ),
                    'id'   => $this->prefix . 'lng',
                    'type' => 'text_small',
                    'class'=> 'pippo'
                ),

                array(
                    'name' => __( 'Formatted Address', 'cmb' ),
                    'desc' => __( '', 'cmb' ),
                    'id'   => $this->prefix . 'formatted_address',
                    'type' => 'text_medium',
                    'class'=> 'pippo'
                ),

                array(
				    'name' => 'Your Email',
				    'id'   => $prefix . 'email',
				    'type' => 'text_email',
				),
				
				array(
				    'name' => 'Image',
				    'desc' => 'Upload an image or enter an URL.',
				    'id' => $prefix . 'test_image',
				    'type' => 'file',
				    'allow' => array( 'url', 'attachment' ) // limit to just attachments with array( 'attachment' )
				),
								
 
                array(
                    'name' => 'Describe your place',
                    'desc' => 'Your notes',
                    'default' => 'standard value (optional)',
                    'id' => $prefix . 'place_notes',
                    'type' => 'textarea_small'
                )

            )
        );

        return $meta_boxes;
    }


    /**
     * Shortcode to display a CMB form for a post ID.
     */
    public function do_frontend_form() {

        // Default metabox ID
        $metabox_id = 'accommodations_metabox';

        // Get all metaboxes
        $meta_boxes = apply_filters( 'cmb_meta_boxes', array() );

        // If the metabox specified doesn't exist, yell about it.
        if ( ! isset( $meta_boxes[ $metabox_id ] ) ) {
            return __( "A metabox with the specified 'metabox_id' doesn't exist.", 'cmb' );
        }

        // This is the WordPress post ID where the data should be stored/displayed.
        $post_id = 0;

        if ( $new_id = $this->intercept_post_id() ) {
            $post_id = $new_id;
            echo 'Thank You for your submission.';
        }

        // Shortcodes need to return their data, not echo it.
        $echo = false;

        // Get our form
        $form = cmb_metabox_form( $meta_boxes[ $metabox_id ], $post_id, $echo );

        return $form;
    }





    /**
     * Get data before saving to CMB.
     */
    public function intercept_post_id() {

        // Check for $_POST data
        if ( empty( $_POST ) ) {
            return false;
        }

        // Check nonce
        if ( ! ( isset( $_POST['submit-cmb'], $_POST['wp_meta_box_nonce'] ) && wp_verify_nonce( $_POST['wp_meta_box_nonce'], cmb_Meta_Box::nonce() ) ) ) {
            return;
        }

        // Setup and sanitize data
        if ( isset( $_POST[ $this->prefix . 'place_name' ] ) ) {
            $this->new_submission = wp_insert_post( array(
                'post_title'            => sanitize_text_field( $_POST[ $this->prefix . 'place_name' ] . ' ' . $_POST[ $this->prefix . 'place_name' ] ),
                'post_author'           => get_current_user_id(),
                'post_status'           => 'draft', // Set to draft so we can review first
                'post_type'             => 'accommodations',
                'post_content_filtered' => wp_kses( $_POST[ $this->prefix . 'place_notes' ], '<b><strong><i><em><h1><h2><h3><h4><h5><h6><pre><code><span>' ),
            ), true );

            // If no errors, save the data into a new post draft
            if ( ! is_wp_error( $this->new_submission ) ) {
                return $this->new_submission;



            }

        }

        return false;
    }


    /**
     * Save featured image.
     */
    public function save_featured_image( $object_id, $meta_box_id, $updated, $meta_box ) {

        if ( isset( $updated ) && in_array( '_example_memorial_image', $updated ) ) {
            set_post_thumbnail( $object_id, get_post_meta( $object_id, '_example_memorial_image_id', 1 ) );
        }

    }


    /**
     * Initialize CMB.
     */
    public function initialize_cmb_meta_boxes() {

        if ( ! class_exists( 'cmb_Meta_Box' ) ) {
            require_once 'Custom-Metaboxes-and-Fields-for-WordPress-master/init.php';
        }

    }


} // end class

$FrontendForm = new AccommodationForm();
?>