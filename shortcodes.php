<?php

//Register shortcode

function referral_register_shortcode(){
    add_shortcode('referral_registration','referral_registration_form');
}
add_action('init','referral_register_shortcode');

// shortcode call back function

function referral_registration_form(){

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']);
        $email = sanitize_email($_POST['email']);
        $password = sanitize_text_field($_POST['password']);
        $referral_code = sanitize_text_field($_POST['referral_code']);

        // Check if referral code is valid
        $is_valid_referral_code = validate_referral_code_backend($referral_code);

        if ($is_valid_referral_code) {
            $username = $first_name . ' ' . $last_name;
            $referral_username = get_referral_username_by_code($referral_code);
            $join_commission = get_option('join_commission', '50');

            global $wpdb;
            $table_name = $wpdb->prefix . 'referrals';

            $wpdb->insert($table_name, array(
                'username' => $username,
                'email' => $email,
                'referral_username' => $referral_username,
                'join_commission' => $join_commission,
                'referral_code' => generate_unique_referral_code()
            ));
            echo 'Registration successful!';
        } else {
            echo 'Invalid referral code!';
        }
    }
    ob_start();
    ?>

    <form id="referral-registration-form" method="post">
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="referral_code" id="referral_code" placeholder="Referral Code" required>
        <span id="referral-code-status"></span>
        <button type="submit">Register</button>
    </form>
   
    <?php
    return ob_get_clean();


}
//Ajax handler

function validate_referral_code(){
    $referralcode = $_POST['referral_code'];
    $response = array('valid' => true);
    wp_send_json($response);

}

add_action('wp_ajax_validate_referral code','validate_referral_code');
add_action('wp_ajax_nopriv_validate_referral_code','validate_referral_code');

// Backend referral code
function validate_referral_code_backend($referral_code) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'referrals';
    $result = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE referral_code = %s", $referral_code));
    return $result > 0;
}

function get_referral_username_by_code($referral_code) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'referrals';
    return $wpdb->get_var($wpdb->prepare("SELECT username FROM $table_name WHERE referral_code = %s", $referral_code));
}
