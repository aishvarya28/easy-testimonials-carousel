<?php
/**
 * Plugin Name: Easy Testimonials Carousel
 * Description: This plugin enables you to generate multiple testimonials categorized by type. You can create separate shortcodes for each category and effortlessly integrate them into your templates.   
 * Plugin URI: 
 * Version: 1.0
 * Author: Zluck Solutions
 * Author URI: https://zluck.com
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: easy-testimonials-carousel
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
//  view details link at plugin author area starts
function zl_add_view_details_link($plugin_meta, $plugin_file) {
    if (plugin_basename(__FILE__) === $plugin_file) {
        $zl_plugin_slug = 'easy-testimonials-carousel'; 
        $view_details_link = '<a href="' . admin_url('plugin-install.php?tab=plugin-information&plugin=' . $zl_plugin_slug . '&TB_iframe=true&width=600&height=550') . '" class="thickbox" aria-label="View plugin details">View Details</a>';
        // Add the link next to the Author
        array_splice($plugin_meta, 2, 3, $view_details_link);
    }
    return $plugin_meta;
}
add_filter('plugin_row_meta', 'zl_add_view_details_link', 10, 2);
//  view details link at plugin author area ends

 function zl_enqueue_custom_scripts($post_id) {
     // Enqueue jQuery and jQuery Migrate
     wp_enqueue_script('jquery');
     wp_enqueue_script('jquery-migrate', plugin_dir_url(__FILE__) . '/assets/js/jquery-migrate.js', array('jquery'), '1.2.1', true);
     
    if (is_page() || is_single()) {
        // Enqueue slick slider styles
        wp_enqueue_style('slick-css', plugin_dir_url(__FILE__) . '/assets/slick/slick.css',array(),'1.0.0');

        wp_enqueue_style('my-plugin-style', plugin_dir_url(__FILE__) . '/assets/css/testimonial-style.css',array(),'1.0.0');
        // Enqueue font-awesome styles
        wp_enqueue_style('font-awesome', plugin_dir_url(__FILE__) . '/assets/css/font-awesome.css', array(),'6.4.0');
        wp_enqueue_style('slick-theme-css', plugin_dir_url(__FILE__) . '/assets/slick/slick-theme.css',array(),'1.0.0');

        // Enqueue slick slider script
        wp_enqueue_script('slick-js', plugin_dir_url(__FILE__) . '/assets/slick/slick.min.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('solid-js', plugin_dir_url(__FILE__) . '/assets/js/solid.min.js', array('jquery'), '6.4.2', true);
        // Enqueue extraSlickOptions script
        wp_enqueue_script('extra-slick-options', plugin_dir_url(__FILE__) . '/assets/js/extraSlickOptions.js', array('jquery', 'slick-js'), '1.0.0', true);
        // Enqueue the CSS and slider js file 
        wp_enqueue_script('custom-slider', plugin_dir_url(__FILE__) . '/assets/js/testimonial-slider.js', array('jquery'), '1.0.0', true);
    }

    // Retrieve the saved font-family values for the three post meta fields
    $select_titlefont_family = get_post_meta($post_id, 'testimo_title_font_family', true);
    $select_contentfont_family = get_post_meta($post_id, 'testimo_content_font_family', true);
    $select_designationfont_family = get_post_meta($post_id, 'testimo_designation_font_family', true);

    // Create an array to hold the font families
    $font_family = array();

    // Add font families to the array if they are not empty
    if (!empty($select_titlefont_family)) {
        $font_family[] = urlencode($select_titlefont_family);
    }

    if (!empty($select_contentfont_family)) {
        $font_family[] = urlencode($select_contentfont_family);
    }

    if (!empty($select_designationfont_family)) {
        $font_family[] = urlencode($select_designationfont_family);
    }
    //Check if there are any font families to enqueue
    if (!empty($font_family)) {
        // Enqueue the Google Font stylesheet for the combined font families
        $font_family_string = implode('|', $font_family);
        $local_google_fonts_url = plugin_dir_url(__FILE__) . '/assets/css/font-family-css/'.$font_family_string.'.css';
        $style_version = '1.0.0'; 
        wp_enqueue_style('google-fonts', $local_google_fonts_url, array(), $style_version);
    }
}
add_action('wp_enqueue_scripts', 'zl_enqueue_custom_scripts'); 


function zl_enqueue_admin_scripts() {
    // Enqueue your custom stylesheet
    wp_enqueue_script('jquery'); // Enqueue jQuery
     wp_enqueue_style('my-plugin-style', plugin_dir_url(__FILE__) . '/assets/css/testimonial-admin.css','1.0.0',true);  

    // Enqueue toaster library 
    wp_enqueue_script('toastr', plugin_dir_url(__FILE__). '/assets/js/toaster-js.js', array('jquery'), '2.1.4', true);
    wp_enqueue_style('toastr', plugin_dir_url(__FILE__) . '/assets/css/toaster-css.css', array(), '1.0.0');
    wp_enqueue_script('custom-script', plugin_dir_url(__FILE__) . '/assets/js/custom.js', array(), '1.0.0', true);

    // Enqueue jQuery UI CSS
    wp_enqueue_style('jquery-ui-css', plugin_dir_url(__FILE__). '/assets/css/jquery-ui.css',array(),'1.12.1');
    wp_enqueue_script('jquery-ui', plugin_dir_url(__FILE__) . '/assets/js/jquery-ui.js', array('jquery'), '1.12.1', true);

    // Enqueue the script
    wp_enqueue_script('design-custom-script', plugin_dir_url(__FILE__) . 'assets/js/zl-script.js', array('jquery'), '1.0.0', true);

    // Pass PHP variables to JavaScript
    $post_id = get_the_id(); 
    $active_tab = get_post_meta($post_id, 'active_tab', true);
    $selected_design = get_post_meta($post_id, 'testimonial_design', true); // Default to 'design1'
    $design1 = plugin_dir_url(__FILE__) . 'assets/Images/slickslideone.png';
    $design2 = plugin_dir_url(__FILE__) . 'assets/Images/slickslidetwo.png';
    $design3 = plugin_dir_url(__FILE__) . 'assets/Images/slickslidethree.png';
    $design_options = array(
        'design1' => array(
            'label' => 'Design 1',
            'image' => $design1,
        ),
        'design2' => array(
            'label' => 'Design 2',
            'image' => $design2,
        ),
        'design3' => array(
            'label' => 'Design 3',
            'image' => $design3,
        ),
    );
    $script_data = array(
        'designOptions' => $design_options,
        'postId' => $post_id,
        'activeTab' => $active_tab,
        'security' => wp_create_nonce('save_active_tab_nonce'),
    );
    wp_localize_script('design-custom-script', 'scriptData', $script_data);
}
add_action('admin_enqueue_scripts', 'zl_enqueue_admin_scripts');

 // This is a function used to create custom post types and taxonomy.
function zl_custom_post_types_and_taxonomy() {
    // Register the Testimonials custom post type
    $labels = array(
        'name'               => _x( 'Testimonial', 'post type general name' , 'easy-testimonials-carousel'),
        'singular_name'      => _x( 'Testimonial', 'post type singular name', 'easy-testimonials-carousel'),
        'add_new'            => _x( 'Add New Testimonial', 'Testimonial','easy-testimonials-carousel'),
        'add_new_item'       => __( 'Add New Testimonial', 'easy-testimonials-carousel'),
        'edit_item'          => __( 'Edit Testimonial', 'easy-testimonials-carousel'),
        'new_item'           => __( 'New Testimonial', 'easy-testimonials-carousel'),
        'all_items'          => __( 'All Testimonials', 'easy-testimonials-carousel'),
        'view_item'          => __( 'View Testimonial', 'easy-testimonials-carousel'),
        'search_items'       => __( 'Search Testimonial', 'easy-testimonials-carousel'),
        'not_found'          => __( 'No Testimonial found', 'easy-testimonials-carousel'),
        'not_found_in_trash' => __( 'No Testimonial found in the Trash', 'easy-testimonials-carousel'),
        'menu_name'          => 'Easy Testimonials Carousel'
    );
    $args = array(
        'labels'        => $labels,
        'description'   => 'Holds our testimonials and testimonials specific data',
        'public'        => true,
        'menu_position' => 5,
        'menu_icon'     => 'dashicons-format-quote',
        'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'has_archive'   => true,
    );
    register_post_type( 'zl_testimonials', $args );

    // Register the Testimonial Category custom taxonomy
    $taxonomy_labels = array(
        'name'              => _x( 'Testimonial Category', 'taxonomy general name', 'easy-testimonials-carousel' ),
        'singular_name'     => _x( 'Testimonial Category', 'taxonomy singular name', 'easy-testimonials-carousel' ),
        'search_items'      => __( 'Search Testimonial', 'easy-testimonials-carousel' ),
        'all_items'         => __( 'All Testimonial', 'easy-testimonials-carousel' ),
        'view_item'         => __( 'View Testimonial', 'easy-testimonials-carousel' ),
        'parent_item'       => __( 'Parent Testimonial', 'easy-testimonials-carousel' ),
        'parent_item_colon' => __( 'Parent Testimonial:', 'easy-testimonials-carousel' ),
        'edit_item'         => __( 'Edit Testimonial', 'easy-testimonials-carousel' ),
        'update_item'       => __( 'Update Testimonial', 'easy-testimonials-carousel' ),
        'add_new_item'      => __( 'Add New Testimonial', 'easy-testimonials-carousel' ),
        'new_item_name'     => __( 'New Testimonial Name', 'easy-testimonials-carousel' ),
        'not_found'         => __( 'No Testimonial Found', 'easy-testimonials-carousel' ),
        'back_to_items'     => __( 'Back to Testimonial', 'easy-testimonials-carousel' ),
        'menu_name'         => __( 'Testimonials Category', 'easy-testimonials-carousel' ),
    );
    $taxonomy_args = array(
        'labels'            => $taxonomy_labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'testimonial-category' ),
        'show_in_rest'      => true,
    );
    register_taxonomy( 'testimonial-category', 'zl_testimonials', $taxonomy_args );

    // Register the Shortcode Settings custom post type
    $shortcode_labels = array(
        'name'               => _x( 'Add Shortcode', 'post type general name', 'easy-testimonials-carousel'),
        'singular_name'      => _x( 'Create Shortcode', 'post type singular name', 'easy-testimonials-carousel'),
        'add_new'            => _x( 'Add New', 'My Custom CPT', 'easy-testimonials-carousel'),
        'add_new_item'       => __( 'Add Shortcode', 'easy-testimonials-carousel'),
        'edit_item'          => __( 'Edit Shortcode', 'easy-testimonials-carousel'),
        'new_item'           => __( 'New My Shortcode', 'easy-testimonials-carousel'),
        'all_items'          => __( 'All Shortcodes', 'easy-testimonials-carousel'),
        'search_items'       => __( 'Search Shortcode', 'easy-testimonials-carousel'),
        'not_found'          => __( 'No Shortcode Found', 'easy-testimonials-carousel'),
        'not_found_in_trash' => __( 'No Shortcode Found', 'easy-testimonials-carousel'),
        'menu_name'          => 'Create Shortcode'
    );
    $shortcode_args = array(
        'labels'        => $shortcode_labels,
        'description'   => 'Holds your shortcode data',
        'public'        => true,
        'menu_position' => 6, // You can change the menu position as needed
        'supports'      => array( 'title' ),
        'has_archive'   => true,
        'show_in_menu'  => 'edit.php?post_type=zl_testimonials', // Display cpt under "zl_testimonials" in the admin menu
    );
    register_post_type( 'zl_shortc_setting', $shortcode_args );
}
add_action( 'init', 'zl_custom_post_types_and_taxonomy' );

// create meta boxes for both cpt starts
function zl_add_testimonial_design_meta_box() {
    // meta box for post type zl_shortc_setting and in backend postype identify as all shortcode
    add_meta_box(
        'testimonial_position_meta_boxes', // Unique ID (should match the callback function name)
        'Testimonials Carousel Settings', // Box title
        'zl_render_testimonial_design_panel', // Callback function (should match the function name)
        'zl_shortc_setting', // Post type
        'normal', // Position
        'high' // Priority
    );
    add_meta_box(
    // meta box for post type zl_testimonials and in backend postype identify as easy testimonial carousel
        'testimonial_position_meta_box', 
        'Testimonial Information', 
        'zl_testimonial_title_meta_box', 
        'zl_testimonials', // Post type
        'normal', 
        'high'
    );
}
add_action('add_meta_boxes', 'zl_add_testimonial_design_meta_box');
// create meta boxes for both CPT ends.

// Callback function for "zl_shortc_setting"(Shortcode Post type) Function starts.
function zl_render_testimonial_design_panel() {
    $post_id = get_the_id(); // Get the current post ID
    $selected_post_limit = get_post_meta($post_id, 'testimonial_post_limit', true); 
    $selected_category = get_post_meta($post_id, 'testimonial_category', true);
    $selected_slick_slides = get_post_meta($post_id, 'testimonial_slick_slides', true); 
    $selected_bg_color = get_post_meta($post_id, 'testimonial_bg_color', true); 
    $selected_content_color = get_post_meta($post_id, 'testimonial_text_color', true); 
    $selected_title_color = get_post_meta($post_id, 'testimonial_title_color', true); 
    $selected_designation_color = get_post_meta($post_id, 'testimo_designa_color', true);
    $testimo_ratingicon_color = get_post_meta($post_id, 'testimo_ratingicon_color', true);
    $selected_feature_imagestyle = get_post_meta($post_id, 'testimoo_pic', true);
    $slidedelayvalue = get_post_meta($post_id, 'sliderange_field', true);
    $select_order_option = get_post_meta($post_id, 'testimopost_orderoption', true);
    $select_titlefont_family = get_post_meta($post_id, 'testimo_title_font_family', true);
    $select_contentfont_family = get_post_meta($post_id, 'testimo_content_font_family', true);
    $select_titlefont_style = get_post_meta($post_id, 'testimo_title_font_style', true);
    $select_contentfont_style = get_post_meta($post_id, 'testimo_content_font_style', true);
    $select_designationfont_style = get_post_meta($post_id, 'testimo_designation_font_style', true);
    $select_designationfont_family = get_post_meta($post_id, 'testimo_designation_font_family', true);
    $select_titlefont_size = get_post_meta($post_id, 'testimonial_fontsize_limit', true);

    $toggle_value = get_post_meta($post_id, 'autoplaytoggle_field', true);
    // Set the 'checked' attribute based on the value
    $checked_autoplay_attribute = $toggle_value === 'true' ? 'checked' : '';

    $slickslidesarrow = get_post_meta($post_id, 'slickslidesarrow', true);
    // Set the 'checked' attribute based on the value
    $checked_arrow_attributes = $slickslidesarrow === 'true' ? 'checked' : '';

    $slickslidesdots = get_post_meta($post_id, 'slickslidesdots', true);
    // Set the 'checked' attribute based on the value
    $checked_dots_attributes = $slickslidesdots === 'true' ? 'checked' : '';
    
    echo '<div class="wrap testimonial-wrap">'; ?>
    <?php
    // Retrieve the current testimonial design from the post meta (if available)
    $selected_design = get_post_meta($post_id, 'testimonial_design', true); // Default to 'design1'
    $design1 = plugin_dir_url(__FILE__) . 'assets/Images/slickslideone.png';
    $design2 = plugin_dir_url(__FILE__) . 'assets/Images/slickslidetwo.png';
    $design3 = plugin_dir_url(__FILE__) . 'assets/Images/slickslidethree.png';

    // Define the available designs
    $design_options = array(
        'design1' => array(
            'label' => 'Design 1',
            'image' => $design1,
        ),
        'design2' => array(
            'label' => 'Design 2',
            'image' => $design2,
        ),
        'design3' => array(
            'label' => 'Design 3',
            'image' => $design3,
        ),
    ); ?>

    <!-- Settings tabs and posts meta fields-->
    <div id="tabs">
        <ul>
            <li><a href="#tab-post-limit"><?php esc_html_e('Post Settings', 'easy-testimonials-carousel'); ?></a></li>
            <li><a href="#tab-color-picker"><?php esc_html_e('Color Settings', 'easy-testimonials-carousel'); ?></a></li>
            <li><a href="#tab-category"><?php esc_html_e('Category', 'easy-testimonials-carousel'); ?></a></li>
            <li><a href="#tab-post-design"><?php esc_html_e('Slider Designs', 'easy-testimonials-carousel'); ?></a></li>
            <li><a href="#tab-slick-slides"><?php esc_html_e('Slider Settings', 'easy-testimonials-carousel'); ?></a></li>
        </ul>

        <div id="tab-post-limit">
            <label for="testimonial_post_limit"><?php esc_html_e('Posts Limit:', 'easy-testimonials-carousel'); ?></label>
            <input type="number" name="testimonial_post_limit" id="testimonial_post_limit" value="<?php echo esc_attr($selected_post_limit); ?>" min="-1" max="50" />
            <p class="shortnoteforall"><?php esc_html_e('Display number of posts', 'easy-testimonials-carousel'); ?></p><br>
            
            <div class="testimo-post-orderstyle">
            <label for="custom-order-option"><?php esc_html_e('Order By:', 'easy-testimonials-carousel'); ?></label>
            <?php wp_nonce_field('zlorderoption_nonce_action', 'zlorderoption_nonce'); ?>
            <select name="testimopost_orderoption" id="custom-order-option">
                <option value="latest" <?php selected($select_order_option, 'latest'); ?>><?php esc_html_e('Latest', 'easy-testimonials-carousel'); ?></option>
                <option value="asc" <?php selected($select_order_option, 'asc'); ?>><?php esc_html_e('Ascending', 'easy-testimonials-carousel'); ?></option>
                <option value="desc" <?php selected($select_order_option, 'desc'); ?>><?php esc_html_e('Descending', 'easy-testimonials-carousel'); ?></option>
                <option value="modified" <?php selected($select_order_option, 'modified'); ?>><?php esc_html_e('Modified', 'easy-testimonials-carousel'); ?></option>
                <option value="date-wise" <?php selected($select_order_option, 'date-wise'); ?>><?php esc_html_e('Date', 'easy-testimonials-carousel'); ?></option>
                <option value="random" <?php selected($select_order_option, 'random'); ?>><?php esc_html_e('Random', 'easy-testimonials-carousel'); ?></option>
            </select>
            <p class="shortnoteforall"><?php esc_html_e('Display posts by order wise', 'easy-testimonials-carousel'); ?></p><br>
            </div><hr>

            <?php
            $font_families = array(
                'inherited',
                'Arial',
                'Times New Roman',
                'Verdana',
                'Georgia',
                'Courier New',
                'Tahoma',
                'Trebuchet MS',
                'Arial Black',
                'Impact',
                'Comic Sans MS',
                'Palatino',
                'Garamond',
                'Book Antiqua',
                'Lucida Sans Unicode',
                'Lucida Console',
                'Helvetica',
                'Geneva',
                'Monaco',
                'Cursive',
                'fantasy',
                'serif',
                'sans-serif',
                'monospace',
                'Open+Sans', // Google Font with a space
                'Another+Google+Font', // Replace spaces with plus (+)
                'Roboto',
                'Lato',
                'Montserrat',
                'Oswald',
                'Raleway',
                'Poppins',
                'Ubuntu',
                'Noto+Sans',
                'Playfair+Display',
                'Source+Sans+Pro',
                'Merriweather',
                'Droid+Sans',
                'Cabin',
                'Quicksand',
                'Pacifico',
                'Alegreya',
                'Crimson+Text',
                'Inconsolata',
                'Fira+Sans',
                'PT+Sans',
                'Dosis',
                'Bitter',
                'Lobster',
                'Exo',
                'Dancing+Script',
                'Karla',
                'Muli',
                'Rubik',
                'Julius+Sans+One',
                'Varela+Round',
                'Rokkitt',
                'Nunito',
                'Cairo',
                'Catamaran',
                'Maven+Pro',
                'Cinzel',
                'Archivo',
                'Asap',
                'Abril+Fatface',
                'Coda',
                'Pacifico',
                'Fjalla+One',
                'Bungee',
                'Comfortaa',
                'Gloria+Hallelujah',
                'Great+Vibes',
                'Indie+Flower',
                'Kaushan+Script',
                'Lobster+Two',
                'Lora',
                'Marmelad',
                'Merriweather+Sans',
                'Orbitron',
                'Overpass',
                'Pangolin',
                'Piedra',
                'Quicksand',
                'Rakkas',
                'Righteous',
                'Roboto+Condensed',
                'Shadows+Into+Light',
                'Sunflower',
                'Syncopate',
                'Viga',
                'Zilla+Slab',
                // Add more Google Fonts here
            ); ?>

            <?php
            $font_styles = array(
               'normal',
               'italic',
               'oblique',
               'initial',
               'inherit',
            ); ?>

            <div class="testimo-title_font_style">
                <label for="title_font_style"><?php esc_html_e('Font Style (Title):', 'easy-testimonials-carousel'); ?></label>
                <select name="testimo_title_font_style" id="title_font_style">
                    <?php foreach ($font_styles as $family) : ?>
                        <option value="<?php echo esc_attr($family); ?>" <?php selected($select_titlefont_style, $family); ?>><?php echo esc_html($family); ?>
                         <!-- translators: Font style option in a dropdown -->   
                        </option>
                        <?php endforeach; ?>
                </select>
            <p class="shortnoteforall"><?php esc_html_e('Set the font style of title', 'easy-testimonials-carousel'); ?></p><br>
            </div>

            <div class="testimo-content_font_style">
                <label for="content_font_style"><?php esc_html_e('Font Style (Content):', 'easy-testimonials-carousel'); ?></label>
                <select name="testimo_content_font_style" id="content_font_style">
                    <?php foreach ($font_styles as $family) : ?>
                        <option value="<?php echo esc_attr($family); ?>" <?php selected($select_contentfont_style, $family); ?>><?php echo esc_html($family); ?></option>
                        <?php endforeach; ?>
                </select>
            <p class="shortnoteforall"><?php esc_html_e('Set the font style of content', 'easy-testimonials-carousel'); ?></p><br>
            </div>
            
            <div class="testimo-designation_font_style">
                <label for="designation_font_style"><?php esc_html_e('Font Style (Designation):', 'easy-testimonials-carousel'); ?></label>
                <select name="testimo_designation_font_style" id="designation_font_style">
                    <?php foreach ($font_styles as $family) : ?>
                        <option value="<?php echo esc_attr($family); ?>" <?php selected($select_designationfont_style, $family); ?>><?php echo esc_html($family); ?></option>
                        <?php endforeach; ?>
                </select>
            <p class="shortnoteforall"><?php esc_html_e('Set the font style of designation', 'easy-testimonials-carousel'); ?></p><br>
            </div><br>
            <hr>

            <div class="testimo-title_font_family-style">
                <label for="title_font_family"><?php esc_html_e('Font Family (Title):', 'easy-testimonials-carousel'); ?></label>
                <select name="testimo_title_font_family" id="title_font_family">
                    <?php foreach ($font_families as $family) : ?>
                        <option value="<?php echo esc_attr($family); ?>" <?php selected($select_titlefont_family, $family); ?>><?php echo esc_html($family); ?></option>
                    <?php endforeach; ?>
                </select>
            <p class="shortnoteforall"><?php esc_html_e('Set the font family of title', 'easy-testimonials-carousel'); ?></p><br>
            </div>

            <div class="testimo-content_font_family-style">
                <label for="content_font_family"><?php esc_html_e('Font Family (Content):', 'easy-testimonials-carousel'); ?></label>
                <select name="testimo_content_font_family" id="content_font_family">
                    <?php foreach ($font_families as $family) : ?>
                        <option value="<?php echo esc_attr($family); ?>" <?php selected($select_contentfont_family, $family); ?>><?php echo esc_html($family); ?></option>
                    <?php endforeach; ?>
                </select>
            <p class="shortnoteforall"><?php esc_html_e('Set the font family of content', 'easy-testimonials-carousel'); ?></p><br>
            </div>

            <div class="testimo-designation_font_family-style">
                <label for="designation_font_family"><?php esc_html_e('Font Family (Designation):', 'easy-testimonials-carousel'); ?></label>
                <select name="testimo_designation_font_family" id="designation_font_family">
                    <?php foreach ($font_families as $family) : ?>
                        <option value="<?php echo esc_attr($family); ?>" <?php selected($select_designationfont_family, $family); ?>><?php echo esc_html($family);?>  
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <p class="shortnoteforall"><?php esc_html_e('Set the font family of designation', 'easy-testimonials-carousel'); ?></p><br>
            <br>
            <hr>

            <div class="testimo-tile_font_size-style">
                <label for="title_font_size"><?php esc_html_e('Font Size (Title):', 'easy-testimonials-carousel'); ?></label>
                 <?php wp_nonce_field('zltestimonialfontsizelimit_nonce', 'testimonial_nonce_fontsizelimit'); ?>
                <input type="text" name="testimonial_fontsize_limit" id="testimonial_fontsize_limit" value="<?php echo esc_attr($select_titlefont_size); ?>" /><br>
            <p class="shortnoteforall"><?php esc_html_e('Set the font size of title within px i.e 15px', 'easy-testimonials-carousel'); ?></p><br>
            </div>
            <hr>

            <div class="testimo-image-style">
            <label for="testimoo_pic"><?php esc_html_e('Image Style:', 'easy-testimonials-carousel'); ?></label>
            <select id="testimoo_pic" name="testimoo_pic">
                <option value="0"   <?php echo selected($selected_feature_imagestyle,'0'); ?>><?php esc_html_e('Square Image', 'easy-testimonials-carousel'); ?></option>
                <option value="50" <?php echo selected($selected_feature_imagestyle,'50'); ?>><?php esc_html_e('Round Image', 'easy-testimonials-carousel'); ?></option>
            </select>
            <p class="shortnoteforall"><?php esc_html_e('select the image style', 'easy-testimonials-carousel'); ?></p><br>
            </div>
        </div>
    
        <div id="tab-color-picker">
            <div class="testimonial_title_color">
                <label for="testimonial_title_color"><?php esc_html_e('Change the Title Color:', 'easy-testimonials-carousel'); ?></label>
                <input type="text" name="testimonial_title_color" id="testimonial_title_color"
                value="<?php echo esc_attr($selected_title_color); ?>" class="my-color-field testimonial_title_color" data-default-color="#effeff"/><br>
            </div>

            <div class="testimonial_content_color">
                <label for="testimonial_content_color"><?php esc_html_e('Change the Content Text Color:', 'easy-testimonials-carousel'); ?></label>
                <input type="text" name="testimonial_text_color" id="testimonial_content_color"
                value="<?php echo esc_attr($selected_content_color); ?>" class="my-color-field" data-default-color="#effeff"/><br>
            </div>

            <div class="testimonial_bg_color">
                <label for="testimonial_bg_color"><?php esc_html_e('Change the Background Color:', 'easy-testimonials-carousel'); ?></label>
                <input type="text" name="testimonial_bg_color" id="testimonial_bg_color"
                value="<?php echo esc_attr($selected_bg_color); ?>" class="my-color-field" data-default-color="#effeff"/><br>
            </div>

            <div class="testimo_designa_color">
                <label for="testimo_designa_color"><?php esc_html_e('Change the Designation Color:', 'easy-testimonials-carousel'); ?></label>
                <input type="text" name="testimo_designa_color" id="testimo_designa_color"
                value="<?php echo esc_attr($selected_designation_color); ?>" class="my-color-field" data-default-color="#effeff"/><br>
            </div>

            <div class="testimo_ratingicon_color">
                <label for="testimo_ratingicon_color"><?php esc_html_e('Change the Rating icon Color:', 'easy-testimonials-carousel'); ?></label>
                <input type="text" name="testimo_ratingicon_color" id="testimo_ratingicon_color"
                value="<?php echo esc_attr($testimo_ratingicon_color); ?>" class="my-color-field" data-default-color="#effeff"/>
            </div>
        </div>

        <div id="tab-category">
            <label for="testimonial_category"><?php esc_html_e('Select Category:', 'easy-testimonials-carousel'); ?></label>
            <select name="testimonial_category" id="testimonial_category">
                <option value=""><?php esc_html_e('All', 'easy-testimonials-carousel'); ?></option>
                <?php $categories = get_terms(array('taxonomy' => 'testimonial-category', 'hide_empty' => false));
                foreach ($categories as $category) {
                    $selected = ($selected_category == $category->term_id) ? 'selected' : '';
                    echo '<option value="' . esc_attr($category->term_id) . '" ' . esc_attr($selected) . '>' . esc_html($category->name) . '</option>';
                } ?>
            </select>
            <p class="shortnoteforall-category"><?php esc_html_e('Display posts by testimonial category wise', 'easy-testimonials-carousel'); ?></p>
        </div>

        <div id="tab-post-design">
            <div class="image-select-container">
                <?php
                $first_option_selected = empty($selected_design) ? 'selected' : ''; // Check if none is selected
                 $first_option = true;
                foreach ($design_options as $value => $label_info) : ?>
                    <?php
                    $label = $label_info['label'];
                    $selected = ($selected_design === $value || ($first_option && empty($selected_design))) ? 'selected' : '';
        
                    // Unset the first_option flag after the first option is processed
                    if ($first_option) {
                        $first_option = false;
                    }
                    ?>
                    <label class="image-select-label <?php echo $selected ? 'selected' : ''; ?>">
                        <input type="radio" name="testimonial_design" value="<?php echo esc_attr($value); ?>"
                            <?php echo esc_attr($selected); ?> style="display: none;" />
                        <?php echo esc_attr($label_info['label']); ?>
                    </label>
                <?php endforeach; ?>
                <p class="shortnoteforall-designimage"><?php esc_html_e('Choose Design to display Testimonials Accordingly', 'easy-testimonials-carousel'); ?></p>
            </div>
        
            <div id="selected_design_image">
            <?php
            // Display the selected design image
            $selected_design_image_url = '';

            if (isset($design_options[$selected_design]['image'])) {
                $selected_design_image_url = sanitize_text_field($design_options[$selected_design]['image']);
            }
            if (empty($selected_design_image_url)) {
                // Use a static image URL if $selected_design_image_url is empty
                $static_image_url = plugin_dir_url(__FILE__) . 'assets/Images/slickslideone.png';
                echo '<img src="' . esc_url($static_image_url) . '" alt="Design 1" height="auto" width="700px"/>';
            } else {
                // Display the selected design image if available
                echo '<img src="' . esc_url($selected_design_image_url) . '" alt="'.esc_attr($label_info['label']).'" height="auto" width="700px"/>';
            }
            ?>
        </div>

        </div>

        <div id="tab-slick-slides">
            <label for="testimonial_slick_slides"><?php esc_html_e('Slides to Show:', 'easy-testimonials-carousel'); ?></label>
            <input type="number" name="testimonial_slick_slides" id="testimonial_slick_slides" value="<?php echo esc_attr($selected_slick_slides); ?>" min="1" max="4" /><br><br>
            <p class="shortnoteforallslider"><?php esc_html_e('display number of slides to show in slider', 'easy-testimonials-carousel'); ?></p><br>

            <div class="slickslide-delay">  
                <label for="range-field"><?php esc_html_e('Slide Delay:', 'easy-testimonials-carousel'); ?></label>
                <input type="range" id="range-field" name="sliderange_field" value="<?php echo esc_attr($slidedelayvalue); ?>" min="100" max="5000" step="100" style="width:200px;" /><br>
                <input type="text" id="range-value" name="sliderange_value" value="<?php echo esc_attr($slidedelayvalue); ?>" style="width: 69px;" readonly /><br>
            <p class="shortnoteforall"><?php esc_html_e('slides show in mili seconds', 'easy-testimonials-carousel'); ?></p><br>
            </div> 

            <!-- Add the toggle field inside the same div -->
            <div class="toggle-container">
                <label for="toggle_field"><?php esc_html_e('Slide Autoplay:', 'easy-testimonials-carousel'); ?></label>
                <label class="switch autoplayswitch">
                <input type="checkbox" name="autoplaytoggle_field" id="toggle_field" <?php echo esc_attr($checked_autoplay_attribute); ?>>
                    <span class="slider"></span>
                </label>
            </div>
            <p class="shortnoteforall"><?php esc_html_e('slides will be auto play, if yes', 'easy-testimonials-carousel'); ?></p><br>

            <div class="toggle-container">
                <label for="toggle_field"><?php esc_html_e('Slide Arrow:', 'easy-testimonials-carousel'); ?></label>
                <label class="switch arrowswitch">
                    <input type="checkbox" name="slickslidesarrow" id="toggle_field_arrow" <?php echo esc_attr($checked_arrow_attributes); ?>>
                    <span class="slider"></span>
                </label>
            </div>
            <p class="shortnoteforall"><?php esc_html_e('slides arrows will display, if yes', 'easy-testimonials-carousel'); ?></p><br>

            <div class="toggle-container">
                <label for="toggle_field"><?php esc_html_e('Slides Dots:', 'zl-easy-testimonials-carousel'); ?></label>
                <label class="switch slidedotswitch">
                    <input type="checkbox" name="slickslidesdots" id="toggle_field_dots" <?php echo esc_attr($checked_dots_attributes); ?>>
                    <span class="slider"></span>
                </label>
            </div>
            <p class="shortnoteforall"><?php esc_html_e('slides dots will display, if yes', 'easy-testimonials-carousel'); ?></p><br>
        </div>
    </div>

    <?php
    echo '</div>';
}
// Callback function for "zl_shortc_setting"(Shortcode Post type) Function ends

// tab selected after post published, post updated or page refresh  
add_action('wp_ajax_save_active_tab', 'zl_save_active_tab_callback');
add_action('wp_ajax_nopriv_save_active_tab', 'zl_save_active_tab_callback');

function zl_save_active_tab_callback() {
    check_ajax_referer('save_active_tab_nonce', 'security');
        // if (isset($_POST['post_id']) && isset($_POST['active_tab'])) {
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $active_tab = isset($_POST['active_tab']) ? sanitize_text_field($_POST['active_tab']) : '';
        // Save the active tab as post meta
        update_post_meta($post_id, 'active_tab', $active_tab);
  
    wp_die();
}

 // For backend Color Picker fields starts
 add_action('admin_head', 'zl_enqueue_color_picker');
 function zl_enqueue_color_picker() {
 // For backend Color Picker fields ends
     wp_enqueue_style('wp-color-picker');
     wp_enqueue_script('wp-color-picker'); 
}

// for save Shortcode meta fields save_post hook starts
function zl_save_testimonial_design_post_meta($post_id) {
    // Check if this is the right post type
    if (get_post_type($post_id) !== 'zl_shortc_setting') {
        return;
    }
    // Save the values as post meta
    if (isset($_POST['testimonial_design'])) {
        $selected_design = sanitize_text_field($_POST['testimonial_design']);
        update_post_meta($post_id, 'testimonial_design', $selected_design);
    }
    if (isset($_POST['testimonial_post_limit'])) {
        $selected_post_limit = intval($_POST['testimonial_post_limit']);
        update_post_meta($post_id, 'testimonial_post_limit', $selected_post_limit);
    }
    if (isset($_POST['testimonial_category'])) {
        $selected_category = isset($_POST['testimonial_category']) ? sanitize_text_field($_POST['testimonial_category']) : '';
        update_post_meta($post_id, 'testimonial_category', $selected_category);
    }
    if (isset($_POST['testimonial_slick_slides'])) {
        $selected_slick_slides = intval($_POST['testimonial_slick_slides']);
        update_post_meta($post_id,'testimonial_slick_slides', $selected_slick_slides);
    }
    // Save the color picker value
    if (isset($_POST['testimonial_bg_color'])) {
        $selected_color = sanitize_hex_color($_POST['testimonial_bg_color']);
        update_post_meta($post_id, 'testimonial_bg_color', $selected_color);
    }
    if (isset($_POST['testimonial_text_color'])) {
        $selected_color = sanitize_hex_color($_POST['testimonial_text_color']);
        update_post_meta($post_id, 'testimonial_text_color', $selected_color);
    }
    if (isset($_POST['testimonial_title_color'])) {
        $selected_color = sanitize_hex_color($_POST['testimonial_title_color']);
        update_post_meta($post_id, 'testimonial_title_color', $selected_color);
    }
    if (isset($_POST['testimo_designa_color'])) {
        $selected_color = sanitize_hex_color($_POST['testimo_designa_color']);
        update_post_meta($post_id, 'testimo_designa_color', $selected_color);
    }
    if (isset($_POST['testimo_ratingicon_color'])) {
        $selected_color = sanitize_hex_color($_POST['testimo_ratingicon_color']);
        update_post_meta($post_id, 'testimo_ratingicon_color', $selected_color);
    }
    if (isset($_POST['testimoo_pic'])) {
        $selected_color = sanitize_text_field($_POST['testimoo_pic']);
        update_post_meta($post_id, 'testimoo_pic', $selected_color);
    }
    // Check if the slider autoplay field is set and save it accordingly
    if (isset($_POST['autoplaytoggle_field'])) {
        $autoplay_toggle = 'true';
    } else {
        $autoplay_toggle = 'false';
    }
    update_post_meta($post_id, 'autoplaytoggle_field', $autoplay_toggle);

    // Check if the slider arrow field is set and save it accordingly
    if (isset($_POST['slickslidesarrow'])) {
        $arrow_toggle = 'true';
    } else {
        $arrow_toggle = 'false';
    }
    update_post_meta($post_id, 'slickslidesarrow', $arrow_toggle);

    // Check if the slider dots field is set and save it accordingly
    if (isset($_POST['slickslidesdots'])) {
        $dots_toggle = 'true';
    } else {
        $dots_toggle = 'false';
    }
    update_post_meta($post_id, 'slickslidesdots', $dots_toggle);

    // Check if the slide delay field is set and save it accordingly
    if (isset($_POST['sliderange_field'])) {
        $value = sanitize_text_field($_POST['sliderange_field']);
        update_post_meta($post_id, 'sliderange_field', $value);
    }
    // save sort post order meta
    if (isset($_POST['testimopost_orderoption']) && isset($_POST['zlorderoption_nonce'])) {
         $nonce = sanitize_text_field($_POST['zlorderoption_nonce']);
        if (wp_verify_nonce($nonce, 'zlorderoption_nonce_action')) {
        // Nonce is valid, proceed with processing the form data
        update_post_meta($post_id, 'testimopost_orderoption', sanitize_text_field($_POST['testimopost_orderoption']));
    } else {
        // Nonce verification failed, handle the error or reject the form submission
        echo 'Nonce verification failed. Form submission rejected.';
    }
    }
    // save title font family as post meta
    if (isset($_POST['testimo_title_font_family'])) {
        update_post_meta($post_id, 'testimo_title_font_family', sanitize_text_field($_POST['testimo_title_font_family']));
    }
    // save content font family as post meta
    if (isset($_POST['testimo_content_font_family'])) {
        update_post_meta($post_id, 'testimo_content_font_family', sanitize_text_field($_POST['testimo_content_font_family']));
    }
    // save title font style as post meta
    if (isset($_POST['testimo_title_font_style'])) {
        update_post_meta($post_id, 'testimo_title_font_style', sanitize_text_field($_POST['testimo_title_font_style']));
    }
    // save content font style as post meta
    if (isset($_POST['testimo_content_font_style'])) {
        update_post_meta($post_id, 'testimo_content_font_style', sanitize_text_field($_POST['testimo_content_font_style']));
    }
    // save designation font style as post meta
    if (isset($_POST['testimo_designation_font_style'])) {
        update_post_meta($post_id, 'testimo_designation_font_style', sanitize_text_field($_POST['testimo_designation_font_style']));
    }
     // save designation font family as post meta
     if (isset($_POST['testimo_designation_font_family'])) {
        update_post_meta($post_id, 'testimo_designation_font_family', sanitize_text_field($_POST['testimo_designation_font_family']));
    }
    // save title font size as post meta
    if (isset($_POST['testimonial_fontsize_limit']) && isset($_POST['testimonial_nonce_fontsizelimit'])) {
        $nonce = $_POST['testimonial_nonce_fontsizelimit'];
        if ( ! wp_verify_nonce($nonce, 'zltestimonialfontsizelimit_nonce')) {
            wp_die('Security check failed. Please try again or contact support.');
        } else {
            update_post_meta($post_id, 'testimonial_fontsize_limit', sanitize_text_field($_POST['testimonial_fontsize_limit']));
        }
    }
}
// Hook into save post meta when a post is published or updated
add_action('save_post', 'zl_save_testimonial_design_post_meta');
// for save Shortcode meta fields save_post hook ends

// callback function of Testimonial post type's meta box starts
function zl_testimonial_title_meta_box($post) {
    wp_nonce_field('zl_testimonial_save_meta', 'zl_testimonial_nonce'); 

    // Retrieve meta values of testimonial position & star rating field
    $position = sanitize_text_field(get_post_meta($post->ID, 'testimonial_positions', true)); 
    $dropdown_value = get_post_meta($post->ID, 'star_rating', true); ?>

    <!--The HTML for the designation input.  -->
    <div>
        <label for="identity-position"><?php esc_html_e('Position :', 'easy-testimonials-carousel'); ?></label>
       <?php $zl_positions_nonce = wp_create_nonce('zl_positions_nonce_action');
        echo '<input type="hidden" name="zl_positions_nonce" value="' . esc_attr($zl_positions_nonce) . '" />'; ?>
        <input type="text" name="testimonial_positions" value="<?php echo esc_html($position); ?>" style="width: 50%; margin: 10px 5px;">
    </div>
    
    <!-- The HTML for the rating field. -->
    <label for="star_rating"><?php esc_html_e('Rating :', 'easy-testimonials-carousel'); ?></label>
    <?php $my_custom_nonce = wp_create_nonce('zl_rating_nonce_action');
    echo '<input type="hidden" name="zl_rating_nonce" value="' . esc_attr($my_custom_nonce) . '" />'; ?>
    <select id="star_rating" name="star_rating" style="width: 5%; margin:10px 10px;">
      <option value="0:0" <?php selected($dropdown_value, '0:0'); ?>><?php esc_html_e('0 star', 'easy-testimonials-carousel'); ?></option>
      <option value="1:1" <?php selected($dropdown_value, '1:1'); ?>><?php esc_html_e('1 star', 'easy-testimonials-carousel'); ?></option>
      <option value="2:2" <?php selected($dropdown_value, '2:2'); ?>><?php esc_html_e('2 star', 'easy-testimonials-carousel'); ?></option>
      <option value="3:3" <?php selected($dropdown_value, '3:3'); ?>><?php esc_html_e('3 star', 'easy-testimonials-carousel'); ?></option>
      <option value="4:4" <?php selected($dropdown_value, '4:4'); ?>><?php esc_html_e('4 star', 'easy-testimonials-carousel'); ?></option>
      <option value="5:5" <?php selected($dropdown_value, '5:5'); ?>><?php esc_html_e('5 star', 'easy-testimonials-carousel'); ?></option>
    </select>
    <?php
  }
// callback function of Testimonial post type's meta box ends

// save testimonial's post type's meta fields starts
function zl_save_testimonial_data($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // Save position post meta
    if (isset($_POST['testimonial_positions']) && isset($_POST['zl_positions_nonce']) && wp_verify_nonce($_POST['zl_positions_nonce'], 'zl_positions_nonce_action')) {
        update_post_meta($post_id, 'testimonial_positions', sanitize_text_field($_POST['testimonial_positions']));
    } else {
        delete_post_meta($post_id, 'testimonial_positions');
    }

    // Save star rating post meta
    if (isset($_POST['star_rating']) && isset($_POST['zl_rating_nonce']) && wp_verify_nonce($_POST['zl_rating_nonce'], 'zl_rating_nonce_action')) {
        update_post_meta($post_id, 'star_rating', sanitize_text_field($_POST['star_rating']));
    } else {
        // Nonce verification failed or 'star_rating' not set
        delete_post_meta($post_id, 'star_rating');
    }

}
add_action('save_post_zl_testimonials', 'zl_save_testimonial_data');
// save testimonial's post type's meta fields ends


// for adding extra column in shortcode post type starts
function zl_add_shortcode_column($columns) {
    // Create a new array to store column names
    $new_columns = array();
    // Add the existing columns (Title) first
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    // Add the Shortcode column
    $new_columns['shortcode'] = 'Shortcode';
    // Add the existing columns (date) last
    $new_columns['date'] = $columns['date'];
    return $new_columns;
}
add_filter('manage_zl_shortc_setting_posts_columns', 'zl_add_shortcode_column');

// for display shortcodes in backend starts
function zl_custom_shortcode_column($column, $post_id) {
    if ($column === 'shortcode') {
        echo esc_html("[easy_testimonials id=\"$post_id\"]");
    } }
add_action('manage_zl_shortc_setting_posts_custom_column', 'zl_custom_shortcode_column', 10, 2);
// for display shortcodes in backend ends

// for make shortcode copyable starts
function zl_add_shortcode_column_styles() {
    echo '<style>.column-shortcode { user-select: all; }</style>';
}
add_action('admin_head', 'zl_add_shortcode_column_styles');
// for make shortcode copyable ends
// for adding extra column in shortcode post type ends


// shortcode for display testimonials starts.
function zl_testimonial_design_shortcode($atts) {
    global $post;
    $post_id = $post->ID;

    // Extract the 'id' attribute from the shortcode
    $atts = shortcode_atts(
        array('id' => '', // Default value is empty
            ), $atts,
        'testimonial_design' );

    $post_id = intval($atts['id']);
    // Check if the 'id' parameter is provided in the shortcode
    if (!empty($post_id)) {
        $post = get_post($post_id);

        // Check if a valid post is found
        if ($post) {
            // You can access the post content, title, custom fields, etc., using $post
            $selected_design = get_post_meta($post_id, 'testimonial_design', true);
            $selected_category = get_post_meta($post_id, 'testimonial_category', true);
            $backend_posts_per_page = intval(get_post_meta($post_id, 'testimonial_post_limit', true));
            $selected_slick_slides = get_post_meta($post_id, 'testimonial_slick_slides', true);
            $selected_bg_color = get_post_meta($post_id, 'testimonial_bg_color', true);
            $selected_content_color = get_post_meta($post_id, 'testimonial_text_color', true);
            $selected_title_color = get_post_meta($post_id, 'testimonial_title_color', true);
            $selected_designation_color = get_post_meta($post_id, 'testimo_designa_color', true); 
            $selected_ratingicon_color = get_post_meta($post_id, 'testimo_ratingicon_color', true);
            $selected_sort_postorder_option = get_post_meta($post_id, 'testimopost_orderoption', true);
            $selected_feature_imagestyle = get_post_meta($post_id, 'testimoo_pic', true); // Default to true
            $data_a_attribute = get_post_meta($post_id, 'autoplaytoggle_field', true);
            $slickslidesarrow = get_post_meta($post_id, 'slickslidesarrow', true);
            $slickslidesdots = get_post_meta($post_id, 'slickslidesdots', true);
            $slidedelayvalue = get_post_meta($post_id, 'sliderange_field', true); // Default to true
            $select_titlefont_family = get_post_meta($post_id, 'testimo_title_font_family', true);
            $select_contentfont_family = get_post_meta($post_id, 'testimo_content_font_family', true);
            $select_titlefont_style = get_post_meta($post_id, 'testimo_title_font_style', true);
            $select_contentfont_style = get_post_meta($post_id, 'testimo_content_font_style', true);
            $select_designationfont_style = get_post_meta($post_id, 'testimo_designation_font_style', true);
            $select_designationfont_family = get_post_meta($post_id, 'testimo_designation_font_family', true);
            $select_titlefont_size = get_post_meta($post_id, 'testimonial_fontsize_limit', true);

            if (empty($selected_design)) {
                // Set a default design here (you can change 'design1' to your preferred default)
                $selected_design = 'design1';
            }
            // Create WP_Query args based on retrieved post meta values
            $args = array(
                'post_type' => 'zl_testimonials',
                'posts_per_page' => $backend_posts_per_page,
            );

            if (!empty($selected_category)) {
                $args['category_query'] = array(
                    array(
                        'taxonomy' => 'testimonial-category',
                        'field' => 'id',
                        'terms' => $selected_category,
                    ),
                );
            }
     // Add the order and orderby parameters based on the selected option
     switch ($selected_sort_postorder_option){
        case 'latest':
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;

        case 'random':
            $args['orderby'] = 'rand';
            break;

        case 'asc':
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
            break;

        case 'desc':
            $args['orderby'] = 'title';
            $args['order'] = 'DESC';
            break;

        case 'modified':
            $args['orderby'] = 'modified';
            $args['order'] = 'DESC';
            break;

        case 'date-wise':
            $args['orderby'] = 'date';
            $args['order'] = 'ASC';
            break;

        default:
            // Default to 'latest' if no valid option is selected
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;
    }
            $query = new WP_Query($args);
            ob_start();

            if ($query->have_posts()) {
                echo '<div class="testimonial-slider">';
                echo '<div class="slider-container">'; // Apply inline styles

                // Separate loops for each design section
                $design1_posts = array();
                $design2_posts = array();
                $design3_posts = array();

                while ($query->have_posts()) {
                    $query->the_post();
                    $designation = get_post_meta(get_the_ID(), 'testimonial_positions', true);
                    $countss = floatval(get_post_meta(get_the_ID(), 'star_rating', true)); // Retrieve the fractional rating
                        
                    $post_data = array(
                        'post_thumbnail' => get_the_post_thumbnail(get_the_ID(), 'thumbnail', array('class' => 'testimonial-post-feature', 'style' => "border-radius: $selected_feature_imagestyle;")),
                        'title' => get_the_title(),
                        'excerpt' => get_the_excerpt(),
                        'designation' => $designation,
                        'rating' => $countss
                    );

                    switch ($selected_design) {
                        case 'design1':
                            $design1_posts[] = $post_data;
                            break;

                        case 'design2':
                            $design2_posts[] = $post_data;
                            break;

                        case 'design3':
                            $design3_posts[] = $post_data;
                            break;
                    }
                } ?>
                <style>
                    .fa-star:before{
                        content: '★' !important;
                    }
                    .testimonial-star{
                        display:contents !important;
                    }
                </style>
                <?php // Output Slick sliders for each design section
                if ($selected_design === 'design1' && !empty($design1_posts)) {
                    
                    echo '<div class="slick-design-1" data-id="' . esc_attr(get_the_ID()) . '" data-x="' . esc_url($selected_slick_slides) . '" data-a="'.esc_attr($data_a_attribute).'" data-p="'.esc_attr($slickslidesarrow).'" data-m="'.esc_attr($slickslidesdots).'" data-e="'.esc_attr($slidedelayvalue).'">';
                    foreach ($design1_posts as $index =>  $post_data) {
                        // Output each post data with the appropriate structure for Design 1
                        ?>
                        <div class="testimonial-design-1 slider-post" style="background-color:<?php echo esc_attr($selected_bg_color); ?>">
                            <div class="testimonial-post-thumbnail">
                                <?php echo wp_kses_post($post_data['post_thumbnail']); ?>
                            </div>
                            <div class="testimonial-post-content">
                                
                                <h2 class="testimonial-post-title" style="color:<?php echo esc_attr($selected_title_color); ?>; font-family:<?php echo esc_attr($select_titlefont_family); ?>; font-style:<?php echo esc_attr($select_titlefont_style); ?>; font-size:<?php echo esc_attr($select_titlefont_size); ?>"><?php echo esc_attr($post_data['title']); ?></h2>
                                <p class="testimonial-post-excerpt" style="color:<?php echo esc_attr($selected_content_color); ?>; font-family:<?php echo esc_attr($select_contentfont_family); ?>; font-style:<?php echo esc_attr($select_contentfont_style); ?>"><?php echo wp_kses_post(wp_trim_words($post_data['excerpt'], 60)); ?></p>

                                <?php if (!empty($post_data['designation'])) { ?>
                                    <div class="testimonial-designation" style="color:<?php echo esc_attr($selected_designation_color); ?>; font-style:<?php echo esc_attr($select_designationfont_style); ?>; font-family:<?php echo esc_attr($select_designationfont_family); ?>">Designation: <?php echo esc_attr($post_data['designation']); ?></div>
                                <?php } ?>
                                <?php if (!empty($post_data['rating'])) { ?>
                                        <div class="testimonial-rating testimonial-designation" style="color:<?php echo esc_attr($selected_ratingicon_color); ?>">
                                            <?php for ($i = 0; $i < $post_data['rating']; $i++) { ?>
                                                <div class="testimonial-star">
                                                    <i class="fa fa-star" style="color:<?php echo esc_attr($selected_ratingicon_color); ?>"></i>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>

                            </div>
                        </div>
                        <?php
                    }
                    echo '</div>'; // Close Design 1 slider
                } elseif ($selected_design === 'design2' && !empty($design2_posts)) {
                    // Output Slick slider for Design 2
                    echo '<div class="slick-design-2" data-id="' . esc_attr(get_the_ID()) . '" data-y="' . esc_url($selected_slick_slides) . '" data-b="'.esc_attr($data_a_attribute).'" data-q="'.esc_attr($slickslidesarrow).'" data-n="'.esc_attr($slickslidesdots).'" data-f="'.esc_attr($slidedelayvalue).'">';
                    foreach ($design2_posts as $index => $post_data) {
                        // Output each post data with the appropriate structure for Design 2
                        ?>
                        <div class="testimonial-design-2 slider-post">
                            <div class="testimonial-post-thumbnail" style="background-color:<?php echo esc_attr($selected_bg_color); ?>">
                                <?php echo wp_kses_post($post_data['post_thumbnail']); ?>
                                <div class="testimonial-post-content">
                                    <h2 class="testimonial-post-title" style="color:<?php echo esc_attr($selected_title_color); ?>; font-family:<?php echo esc_attr($select_titlefont_family); ?>; font-style:<?php echo esc_attr($select_titlefont_style); ?>; font-size:<?php echo esc_attr($select_titlefont_size); ?>"><?php echo esc_attr($post_data['title']); ?></h2>

                                    <?php if (!empty($post_data['designation'])) { ?>
                                        <div class="testimonial-designation" style="color:<?php echo esc_attr($selected_designation_color); ?>; font-style:<?php echo esc_attr($select_designationfont_style); ?>; font-family:<?php echo esc_attr($select_designationfont_family); ?>">Designation: <?php echo esc_attr($post_data['designation']); ?></div>
                                    <?php } ?>

                                    <?php if (!empty($post_data['rating'])) { ?>
                                        <div class="testimonial-rating testimonial-designation" style="color:<?php echo esc_attr($selected_ratingicon_color); ?>">
                                            <?php for ($i = 0; $i < $post_data['rating']; $i++) { ?>
                                                <div class="testimonial-star">
                                                    <i class="fa fa-star" style="color:<?php echo esc_attr($selected_ratingicon_color); ?>"></i>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>

                                    <div class="testimonial-double-qoute">
                                        <p class="testimonial-post-excerpt" style="color:<?php echo esc_attr($selected_content_color); ?>; font-family:<?php echo esc_attr($select_contentfont_family); ?>; font-style:<?php echo esc_attr($select_contentfont_style); ?>"><?php echo wp_kses_post(wp_trim_words($post_data['excerpt'], 60)); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    echo '</div>'; // Close Design 2 slider
                } elseif ($selected_design === 'design3' && !empty($design3_posts)) {
                    // Output Slick slider for Design 3
                    echo '<div class="slick-design-3" data-id="' . esc_attr(get_the_ID()) . '" data-z="' . esc_url($selected_slick_slides) . '" data-c="'.esc_attr($data_a_attribute).'" data-r="'.esc_attr($slickslidesarrow).'" data-o="'.esc_attr($slickslidesdots).'" data-g="'.esc_attr($slidedelayvalue).'">';
                    foreach ($design3_posts as $index => $post_data) {
                        // Output each post data with the appropriate structure for Design 3
                        ?>
                        <div class="testimonial-design-3 slider-post">
                            <div class="testimonial-post-content" style="background-color:<?php echo esc_attr($selected_bg_color); ?>">
                                <p class="testimonial-content" style="color:<?php echo esc_attr($selected_content_color); ?>; font-family:<?php echo esc_attr($select_contentfont_family); ?>; font-style:<?php echo esc_attr($select_contentfont_style); ?>"><?php echo wp_kses_post(wp_trim_words($post_data['excerpt'], 60)); ?></p>
                                <hr class="inner-horizontal-line">
                                <div class="testimonial-content">
                                    <?php echo wp_kses_post($post_data['post_thumbnail']); ?>
                                </div>
                                <div class="testimonial-text">
                                    <h2 class="testimonial-post-title" style="color:<?php echo esc_attr($selected_title_color); ?>; font-family:<?php echo esc_attr($select_titlefont_family); ?>; font-style:<?php echo esc_attr($select_titlefont_style); ?>; font-size:<?php echo esc_attr($select_titlefont_size); ?>"><?php echo esc_attr($post_data['title']);
                                    ?></h2>

                                    <?php if (!empty($post_data['designation'])) { ?>
                                        <div class="testimonial-designation" style="color:<?php echo esc_attr($selected_designation_color); ?>; font-style:<?php echo esc_attr($select_designationfont_style); ?>; font-family:<?php echo esc_attr($select_designationfont_family); ?>">Designation: <?php echo esc_attr($post_data['designation']); ?>
                                        </div>
                                    <?php } ?>

                                    <?php if (!empty($post_data['rating'])) { ?>
                                        <div class="testimonial-rating testimonial-designation" style="color:<?php echo esc_attr($selected_ratingicon_color); ?>">
                                            <?php for ($i = 0; $i < $post_data['rating']; $i++) { ?>
                                                <div class="testimonial-star">
                                                    <i class="fa fa-star" style="color:<?php echo esc_attr($selected_ratingicon_color); ?>"></i>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    echo '</div>'; // Close Design 3 slider
                }
                echo '</div>'; // Close slider container
                echo '</div>'; // Close testimonial-slider div
            }
            wp_reset_postdata();
            return ob_get_clean();
        } else {
            // If the post with the provided ID is not found, return an error message
            return '<p>Error: Testimonial not found.</p>';
        }
    } else {
        // If the 'id' parameter is not provided or is empty, return empty content
        return '';
    }
}
add_shortcode('easy_testimonials', 'zl_testimonial_design_shortcode');
// shortcode for display testimonials ends.
