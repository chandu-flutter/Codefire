<?php
/*
Plugin Name: Job Listings
Plugin URI: https://example.com
Description: A simple plugin to add a 'Jobs' custom post type.
Author: Chandra Sekhar
Version: 1
Author URI: https://chandu-port.in/
License: GPLv2
Text Domain: job-listings
*/
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register Custom Post Type
function jobs_register_cpt() {
    $args = array(
        'labels' => array(
            'name' => 'Jobs',
            'singular_name' => 'Job',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'custom-fields'),
        'menu_icon' => 'dashicons-businessman',
    );
    register_post_type('jobs', $args);
}
add_action('init', 'jobs_register_cpt');

// Add custom meta fields
function jobs_add_meta_boxes() {
    add_meta_box('job_details', 'Job Details', 'jobs_meta_box_callback', 'jobs', 'normal', 'high');
}
add_action('add_meta_boxes', 'jobs_add_meta_boxes');

function jobs_meta_box_callback($post) {
    $company_name = get_post_meta($post->ID, '_company_name', true);
    $location = get_post_meta($post->ID, '_location', true);
    $salary = get_post_meta($post->ID, '_salary', true) ?: get_option('jobs_default_salary', 'Not Specified');
    ?>
    <p><label>Company Name:</label><br>
        <input type='text' name='company_name' value='<?php echo esc_attr($company_name); ?>'></p>
    <p><label>Location:</label><br>
        <input type='text' name='location' value='<?php echo esc_attr($location); ?>'></p>
    <p><label>Salary:</label><br>
        <input type='text' name='salary' value='<?php echo esc_attr($salary); ?>'></p>
    <?php
}

function jobs_save_meta_data($post_id) {
    if (isset($_POST['company_name'])) {
        update_post_meta($post_id, '_company_name', sanitize_text_field($_POST['company_name']));
    }
    if (isset($_POST['location'])) {
        update_post_meta($post_id, '_location', sanitize_text_field($_POST['location']));
    }
    if (isset($_POST['salary'])) {
        update_post_meta($post_id, '_salary', sanitize_text_field($_POST['salary']));
    }
}
add_action('save_post', 'jobs_save_meta_data');

// Shortcode to Display Jobs
function jobs_shortcode($atts) {
    $query = new WP_Query(array('post_type' => 'jobs', 'posts_per_page' => 10));
    $output = '<ul class="jobs-list">';
    while ($query->have_posts()) {
        $query->the_post();
        $company = get_post_meta(get_the_ID(), '_company_name', true);
        $location = get_post_meta(get_the_ID(), '_location', true);
        $salary = get_post_meta(get_the_ID(), '_salary', true);
        $output .= '<li><strong>' . get_the_title() . '</strong> at ' . esc_html($company) . '<br> Location: ' . esc_html($location) . '<br> Salary: ' . esc_html($salary) . '</li>';
    }
    wp_reset_postdata();
    return $output . '</ul>';
}
add_shortcode('jobs_list', 'jobs_shortcode');

// Admin Settings Page
function jobs_settings_menu() {
    add_options_page('Job Settings', 'Job Settings', 'manage_options', 'job-settings', 'jobs_settings_page');
}
add_action('admin_menu', 'jobs_settings_menu');

function jobs_settings_page() {
    ?>
    <div class="wrap">
        <h2>Job Settings</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('jobs_settings_group');
            do_settings_sections('job-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function jobs_register_settings() {
    register_setting('jobs_settings_group', 'jobs_default_salary');
    add_settings_section('jobs_main_section', 'Main Settings', null, 'job-settings');
    add_settings_field('default_salary', 'Default Salary', 'jobs_default_salary_field', 'job-settings', 'jobs_main_section');
}
add_action('admin_init', 'jobs_register_settings');

function jobs_default_salary_field() {
    $value = get_option('jobs_default_salary', 'Not Specified');
    echo '<input type="text" name="jobs_default_salary" value="' . esc_attr($value) . '" />';
}

