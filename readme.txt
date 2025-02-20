To use a custom post type (CPT) named jobs with fields Job Title, Company Name, Location, Salary, and Description

write the code for the CPT Jobs plugin

create a php file by the name joblistings
1.Register the custom post type
add_action('init', 'jobs_register_cpt')

2.add custom fields
function jobs_add_meta_boxes() {
}
function jobs_meta_box_callback($post) {
}
function jobs_save_meta_data($post_id) {
}
add_action('save_post', 'jobs_save_meta_data');

3.add short code
function jobs_shortcode($atts) {
}
add_shortcode('jobs_list', 'jobs_shortcode');

the shortcode to be used is [jobs_list]
===========================================
can find the code for Modification WooCommerce Checkout Page in modify-wooCommerce-checkout-page.php file
===========================================
can find the code for API Integration in api-integration.php
=========================================================================================================

the above 2 php file codes have been integrated in functions.php

The link https://codefire.chandu-port.in/ contains the cpt(jobs),woocommerce checkout page and api integration

it is 100% responsive.
checked the speed in web and it is 92 
====================================================
Access the dashboard
https://codefire.chandu-port.in/wp-login.php
chandu.christ@gmail.com
@#ZuZTMvx96UQjhbN3UB3W$b

