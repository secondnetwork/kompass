<?php

/*
 * These are some default authentication settings
 */
return [
    'webtitle' => 'Kompass',
    'description' => '',
    'supline'  => 'Unter',
    'image_src' => '',
    'footer_textarea' => 'adadas',
    'email_address' => 'www@www.de',
    'phone' => '0515724658',
    'copyright' => 'Acme Inc.',

    'redirect_after_auth' => '/',
    'registration_can_user' => false,
    'registration_show_password_same_screen' => true,
    
    'registration_include_password_confirmation_field' => false,
    'registration_require_email_verification' => false,
    'enable_branding' => true,
    'dev_mode' => false,
    'enable_2fa' => false, // Enable or disable 2FA functionality globally
    'login_show_social_providers' => true,
    'center_align_social_provider_button_content' => false,
    'social_providers_location' => 'bottom',
];
