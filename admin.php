<?php

//create admin menu

function referral_admin_menu(){
    add_menu_page('Referral Plugin','Referrals','manage_options','referral','referral_admin_page');
}
add_action('admin_menu','referral_admin_menu');

//admin page

function referral_admin_page(){
    echo '<div class="wrap">';
    echo '<h1>Referral History</h1>'
    echo '</div>';

}

//Register settings

function referral_register_settings(){
    register_setting('referral-settings-group', 'join_commision');
    add_settings_section('referral-settings-section','Referral Settings',null,'referral-settings');
    add_settings_field('join_commission','Join Commission','referral_join_commission_callback','referral-settings','referral-settings-section');
}

add_action('admin_init','referral_register_settings');

function referral_join_commission_callback(){
    $val = get_option('join_commision','50');
    echo '<input type="text" name="join_commision" value="'.esc_attr($val).'"> Rs. ';
}