<?php
/*
Plugin Name: Referral Plugin
Description: A plugin to manage user referrals.
Version: 1.0
Author: Sony
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include required files
include(plugin_dir_path(__FILE__) . 'admin.php');
include(plugin_dir_path(__FILE__) . 'shortcodes.php');

// Enqueue scripts
function referral_enqueue_scripts() {
    wp_enqueue_script('referral-ajax', plugin_dir_url(__FILE__) . 'referral-ajax.js', array('jquery'), null, true);
    wp_localize_script('referral-ajax', 'referralAjax', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'referral_enqueue_scripts');

// Activation hook to create database table
function referral_plugin_activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'referrals';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        username varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        referral_username varchar(255) NOT NULL,
        join_commission float NOT NULL,
        referral_code varchar(50) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    echo 'true';
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'referral_plugin_activate');

// Deactivation hook to clean up the database table
function referral_plugin_deactivate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'referrals';
    $sql = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query($sql);
}
register_deactivation_hook(__FILE__, 'referral_plugin_deactivate');

// Function to generate unique referral code
function generate_unique_referral_code() {
    return substr(md5(uniqid(mt_rand(), true)), 0, 8);
}
?>
