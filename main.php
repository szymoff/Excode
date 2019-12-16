<?php

/*
Plugin Name: Exhibitors List NEW
Description: Wtyczka umożliwiająca tworzenie oraz wyświetlanie listy wystawców. 
Version: 2.0
Author: Szymon Kaluga
Author URI: http://skaluga.pl/
*/



// Including Plugin JS & CSS

function exhibitors_list_styles_and_scripts() {

    wp_enqueue_style( 'exhibitors_list_css', plugins_url( 'exhibitors_list.css', __FILE__ ) );

    wp_enqueue_script( 'exhibitors_list_js', plugins_url( 'exhibitors_list.js', __FILE__ ) , array( 'jquery' ) );

}

add_action('wp_enqueue_scripts', 'exhibitors_list_styles_and_scripts'); 





// INLCUDE Exhibitor Class

include( plugin_dir_path( __FILE__ ) . 'exhibitor_class.php');



// Instantiate the Exhibitor class if no exist

if ( class_exists('ExhibitorsCustomFields') ) {

    $var = new ExhibitorsCustomFields();

}



// INLCUDE CV COMPONENT

include( plugin_dir_path( __FILE__ ) . 'vc_component.php');



// Register Custom Post Type Exhibitor

function create_exhibitor_cpt() {

	$labels = array(

		'name' => _x( 'Exhibitors', 'Post Type General Name', 'ex-list' ),

		'singular_name' => _x( 'Exhibitor', 'Post Type Singular Name', 'ex-list' ),

		'menu_name' => _x( 'Exhibitors', 'Admin Menu text', 'ex-list' ),

		'name_admin_bar' => _x( 'Exhibitor', 'Add New on Toolbar', 'ex-list' ),

		'archives' => __( 'Exhibitor Archives', 'ex-list' ),

		'attributes' => __( 'Exhibitor Attributes', 'ex-list' ),

		'parent_item_colon' => __( 'Parent Exhibitor:', 'ex-list' ),

		'all_items' => __( 'All Exhibitors', 'ex-list' ),

		'add_new_item' => __( 'Add New Exhibitor', 'ex-list' ),

		'add_new' => __( 'Add New', 'ex-list' ),

		'new_item' => __( 'New Exhibitor', 'ex-list' ),

		'edit_item' => __( 'Edit Exhibitor', 'ex-list' ),

		'update_item' => __( 'Update Exhibitor', 'ex-list' ),

		'view_item' => __( 'View Exhibitor', 'ex-list' ),

		'view_items' => __( 'View Exhibitors', 'ex-list' ),

		'search_items' => __( 'Search Exhibitor', 'ex-list' ),

		'not_found' => __( 'Not found', 'ex-list' ),

		'not_found_in_trash' => __( 'Not found in Trash', 'ex-list' ),

		'featured_image' => __( 'Featured Image', 'ex-list' ),

		'set_featured_image' => __( 'Set featured image', 'ex-list' ),

		'remove_featured_image' => __( 'Remove featured image', 'ex-list' ),

		'use_featured_image' => __( 'Use as featured image', 'ex-list' ),

		'insert_into_item' => __( 'Insert into Exhibitor', 'ex-list' ),

		'uploaded_to_this_item' => __( 'Uploaded to this Exhibitor', 'ex-list' ),

		'items_list' => __( 'Exhibitors list', 'ex-list' ),

		'items_list_navigation' => __( 'Exhibitors list navigation', 'ex-list' ),

		'filter_items_list' => __( 'Filter Exhibitors list', 'ex-list' ),

	);

	$args = array(

		'label' => __( 'Exhibitor', 'ex-list' ),

		'description' => __( 'Wystawcy, którzy biorą udział w targach', 'ex-list' ),

		'labels' => $labels,

		'menu_icon' => 'dashicons-universal-access',

		'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),

		'public' => true,

		'show_ui' => true,

		'show_in_menu' => true,

		'menu_position' => 5,

		'show_in_admin_bar' => true,

		'show_in_nav_menus' => true,

		'can_export' => true,

		'has_archive' => true,

		'hierarchical' => true,

		'exclude_from_search' => false,

		'show_in_rest' => true,

		'publicly_queryable' => true,

		'capability_type' => 'post',

	);

	register_post_type( 'exhibitor', $args );

}

add_action( 'init', 'create_exhibitor_cpt', 0 ); 



// Load Exhibitor Custom Template

function load_exhibitor_template($template) {

    global $post;

    if ($post->post_type == "exhibitor" && $template !== locate_template(array("single-exhibitor.php"))){

        /* This is a "exhibitor" post 

         * AND a 'single exhibitor template' is not found on 

         * theme or child theme directories, so load it 

         * from our plugin directory

         */

        return plugin_dir_path( __FILE__ ) . "single-exhibitor.php";

    } 

    return $template;

}

add_filter('single_template', 'load_exhibitor_template');



// Load Exhibitor Custom Export Button

add_action( 'restrict_manage_posts', 'add_export_button' );

function add_export_button() {

    $screen = get_current_screen();

	// Show Export button only if we're on Exhibitors options screen

    if (isset($screen->parent_file ) && ('edit.php?post_type=exhibitor' == $screen->parent_file) ) {

		// Make simple button and then place next to #post-query-submit

		?>

        <input type="submit" name="export_all_posts" id="export_all_posts" class="button button-primary" value="Wyeksportuj">

        <script type="text/javascript">

            jQuery(function($) {

                $('#export_all_posts').insertAfter('#post-query-submit');

            });

        </script>

        <?php

    }

}

// Export Exhibitors

add_action( 'init', 'func_export_all_posts' );

function func_export_all_posts() {

	// If Export Button is clicked

    if(isset($_GET['export_all_posts'])) {

		// Array of Exhibitors Post Type - Set what to Export

        $arg = array(

                'post_type' => 'exhibitor',

                'post_status' => 'publish',

                'posts_per_page' => -1,

            );

 

        global $post;

        $arr_post = get_posts($arg);

        if ($arr_post) {

			// Set .csv file options

            header('Content-type: text/csv');

            header('Content-Disposition: attachment; filename="exhibitors.csv"');

            header('Pragma: no-cache');

            header('Expires: 0');

 

            $file = fopen('php://output', 'w');

			// Set .csv table names

            fputcsv($file, array('Exhibitor Name', 'Exhibitor URL', 'E-mail', 'Hall', 'Stand', 'Phone', 'Website', 'Logo URL', 'Kraj'));

			// Print .csv table values for each Exhibitor

            foreach ($arr_post as $post) {

                setup_postdata($post);

                fputcsv($file, array(get_the_title(), get_the_permalink(), get_post_meta( get_the_ID(), 'email', true ), get_post_meta( get_the_ID(), 'hall', true ), get_post_meta( get_the_ID(), 'stand', true ), get_post_meta( get_the_ID(), 'phone', true ), get_post_meta( get_the_ID(), 'website', true ), get_the_post_thumbnail_url(), get_post_meta( get_the_ID(), 'country', true ) ));

            }

			// End Script

            exit();

        }

    }

}





?>