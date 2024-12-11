<?php
define('DB_SERVER_LOCAL', 'localhost');
define('DB_NAME_LOCAL', 'kpakpando_events_booking');
define('DB_USERNAME_LOCAL', 'root');
define('DB_PASSWORD_LOCAL', "");
define('DB_SERVER_LIVE', 'localhost');
define('DB_NAME_LIVE', '');
define('DB_USERNAME_LIVE', '');
define('DB_PASSWORD_LIVE', "");
define('DB_SERVER', DB_SERVER_LOCAL);
define('DB_NAME', DB_NAME_LOCAL);
define('DB_USERNAME', DB_USERNAME_LOCAL);
define('DB_PASSWORD', DB_PASSWORD_LOCAL);
define('DEF_GOOGLE_SITE_KEY_TEST', '6Ldws_UpAAAAAHLWu4kui6zWwuhaxh3oi0A_zy90');
define('DEF_GOOGLE_SITE_KEY_LIVE', '6Lc8svUpAAAAACI_SWKTrc10u3vFQhUfL0NT5yCZ');
define('DEF_GOOGLE_SITE_KEY', DEF_GOOGLE_SITE_KEY_TEST);
define('DEF_SENDGRID_API_KEY', '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');
define('DEF_ADMIN_EXPORT_PASSCODE', 'KpakpandoEventsBookingPass01@#_+');

define('DEF_COMMON_REDIRECT_URL', DEF_FULL_ROOT_PATH.'/inc/redirects');

//PAYSTACK
define('DEF_PSK_PAYMENT_URL', 'https://api.paystack.co/transaction/initialize');
define('DEF_PSK_VERIFY_PAYMENT_URL', 'https://api.paystack.co/transaction/verify');
define('PAYSTACK_SPONSORSHIP_PAGE_URL', 'https://paystack.com/pay/kpakpando-sponsorship');
//LIVE
define('DEF_PSK_SECRET_KEY_LIVE', 'sk_live_857317b1e97de7800c3035317ab3ae92dd44b858');
define('DEF_PSK_PUBLIC_KEY_LIVE', 'pk_live_aec9e8e577511064d2a8cacf1735565235790d37');
//TEST
define('DEF_PSK_SECRET_KEY_TEST', 'sk_test_6da5d6effa7edf5d53a9dd1bfc725635038627a5');
define('DEF_PSK_PUBLIC_KEY_TEST', 'pk_test_43ac194c368dfcc171d9075c9c7c6b4ede5d1daf');
//CURRENT
define('DEF_PSK_SECRET_KEY', DEF_PSK_SECRET_KEY_TEST);