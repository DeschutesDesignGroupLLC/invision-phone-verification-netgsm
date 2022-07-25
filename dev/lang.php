<?php

$lang = array(
	'__app_netgsm'	=> 'Phone Integration By NETGSM',

    // ACP Menu
    'menutab__netgsm' => 'NETGSM',
    'menutab__netgsm_icon' => 'phone',
	'menu__netgsm_system' => 'System',
	'menu__netgsm_system_settings' => 'Settings',

	// System Module

	// Settings Controller
	'netgsm_system_tab' => 'System',
	'netgsm_system_header' => 'NETGSM Settings',
	'netgsm_system_message' => 'This application supports international phone numbers. In order to properly validate a phone number, the country needs to be specified when saving a phone number. You can set the default country below. A member can always change the country when entering their phone number.',
	'netgsm_usercode' => 'Usercode',
	'netgsm_password' => 'Password',
	'netgsm_sender_name' => 'Sender Name',
	'netgsm_default_country_code' => 'Default Country Code',
	'netgsm_default_country_code_desc' => 'The default selected country code when a member is entering their phone number.',
	'netgsm_registration_tab' => 'Registration',
	'netgsm_registration_header' => 'Verification',
	'netgsm_registration_enabled' => 'Enabled',
	'netgsm_registration_enabled_desc' => 'Check to enable phone verification when registering. This will replace the default email verification used by Invision Community.',
	'netgsm_registration_text_message' => 'Text Message',
	'netgsm_registration_text_message_desc' => 'The message that will be sent to their phone. The variable {code} will be replace with the actual validation code.',
	'netgsm_birthday_tab' => 'Birthday Text',
	'netgsm_birthday_header' => 'Automatic Text',
	'netgsm_birthday_enabled' => 'Enabled',
	'netgsm_birthday_enabled_desc' => 'Send a text to member\'s on their birthday.',
	'netgsm_birthday_text_message' => 'Text Message',
	'netgsm_birthday_text_message_desc' => 'The message to send to a member on their birthday.',

	// Validation
	'netgsm_confirm_phone' => 'Please confirm your phone number',
	'netgsm_confirm_phone_desc' => 'We will send you a text message with a validation code to the phone number you enter. Standard text messaging rates apply.',
	'netgsm_confirm_phone_sent_desc' => 'We sent a text message to <strong>%s</strong>. Please enter the code you received using the button below.',
	'netgsm_phone' => 'Phone Number',
	'netgsm_phone_desc' => 'The phone number used with NETGSM.',
	'netgsm_verified' => 'Verified',
	'netgsm_phone_country' => 'Country',
	'netgsm_change_phone' => 'Change Phone Number',
	'netgsm_send_text' => 'Send Text Message',
	'netgsm_resend_text' => 'Resend Text Message',
	'netgsm_confirm_code' => 'Confirm Validation Code',
	'netgsm_code' => 'Code',
	'netgsm_code_resent' => 'The validation code has been resent.',
	'netgsm_code_invalid' => 'The code you entered is not valid. Please try again.',
	'netgsm_code_verified' => 'You account has been successfully verified.',

	// Extensions
	'member__netgsm_PhoneNumber' => 'Phone Number',
	'profile_netgsm_PhoneNumber' => 'Phone Number',
	'profile_netgsm_PhoneNumber_header' => 'Manage Phone Number',
	'profile_netgsm_PhoneNumber_message' => 'Manage the phone number you have saved to your profile. You will be sent a text message to verify any changes.'
);
