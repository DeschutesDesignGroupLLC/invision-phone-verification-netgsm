<?php

$lang = array(
	'__app_netgsm'	=> 'Phone Integration By NETGSM',

    // ACP Menu
    'menutab__netgsm' => 'NETGSM',
    'menutab__netgsm_icon' => 'phone',
	'menu__netgsm_system' => 'System',
	'menu__netgsm_system_messages' => 'Messages',
	'menu__netgsm_system_registrations' => 'Registrations',
	'menu__netgsm_system_settings' => 'Settings',

	// Modules
	'module__netgsm_system' => 'System',

	// Database Columns
	'code' => 'Code',
	'code_sent_at' => 'Code Sent At',
	'verified' => 'Verified',
	'verified_at' => 'Verified At',
	'phone_number' => 'Phone Number',
	'country_code' => 'Country',
	'message' => 'Message',
	'message_desc' => 'A message will only be sent to those with a phone number on file that is verified.',
	'message_sent_at' => 'Message Sent At',
	'recipient_ids' => 'Recipients',
	'recipient_ids_desc' => 'Leaving blank will send a message to all members.',

	// Messages Controller
	'netgsm_messages_new' => 'New Message',
	'netgsm_messages_delete' => 'Delete Message',
	'netgsm_messages_send' => 'Send Message',
	'netgsm_messages_send_all_members' => 'Send Message to All Members',
	'netgsm_messages_deleted' => 'The text message has been successfully deleted.',
	'netgsm_new_text_recipients' => 'Recipients',
	'netgsm_new_text_message' => 'Message',
	'netgsm_new_text_sent' => 'The text message has been successfully sent.',
	'netgsm_new_text_sent_empty' => 'There were no members with saved phone numbers in your last request.',

	// Registration Controller
	'netgsm_registration_profile' => 'Member Admin Profile',
	'netgsm_registration_verify' => 'Verify Registration',
	'netgsm_registration_verified' => 'The registration has been successfully verified.',
	'netgsm_registration_unverify' => 'Unverify Registration',
	'netgsm_registration_unverified' => 'The registration has been successfully unverified.',
	'netgsm_registration_delete' => 'Delete Registration',
	'netgsm_registration_deleted' => 'The registration has been successfully deleted.',

	// Lost Password
	'netgsm_lost_password_email' => 'Or reset your password using your email',

	// Settings Controller
	'netgsm_system_tab' => 'System',
	'netgsm_system_header' => 'NETGSM Settings',
	'netgsm_system_message' => 'This application supports international phone numbers. In order to properly validate a phone number, the country needs to be specified when saving a phone number. You can set the default country below. A member can always change the country when entering their phone number.',
	'netgsm_usercode' => 'Usercode',
	'netgsm_password' => 'Password',
	'netgsm_sender_name' => 'Sender Name',
	'netgsm_rate_limiter_header' => 'Rate Limiter',
	'netgsm_rate_limiter_message' => 'Calls to the NETGSM API are limited to prevent fraudlent use and race conditions that could endanger your reputation with the API. You must have configured your community to use Redis via the Data Storage Method setting and have "Use Redis to reduce MySQL overhead" enabled.',
	'netgsm_rate_limiter_per_minute' => 'Per Minute',
	'netgsm_rate_limiter_per_minute_desc' => 'How many calls to the API per minute will be allowed?',
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
	'netgsm_password_tab' => 'Password',
	'netgsm_password_header' => 'Change Password',
	'netgsm_password_enabled' => 'Enabled',
	'netgsm_password_enabled_desc' => 'Force the member to revalidate with their phone number once they change their password from within the User Control Panel.',
	'netgsm_lost_password_enabled' => 'Enabled',
	'netgsm_lost_password_enabled_desc' => 'Enable password reset using the phone number attached to a member\'s account. A text containing a URL to reset their password will be sent to their phone.',
	'netgsm_lost_password_header' => 'Lost Password',
	'netgsm_lost_password_text_message' => 'Text Message',
	'netgsm_lost_password_text_message_desc' => 'The message that will be sent to their phone containing the URL to reset their password. The variable {url} will be replace with the actual URL.',

	// Validation
	'netgsm_confirm_phone' => 'Please confirm your phone number',
	'netgsm_confirm_phone_desc' => 'We will send you a text message with a validation code to the phone number you enter. Standard text messaging rates apply.',
	'netgsm_confirm_phone_sent_desc' => 'We sent a text message to <strong>%s</strong>. Please enter the code you received using the button below.',
	'netgsm_phone' => 'Phone Number',
	'netgsm_verified' => 'Verified',
	'netgsm_phone_country' => 'Country',
	'netgsm_change_phone' => 'Change Phone Number',
	'netgsm_send_text' => 'Send Text Message',
	'netgsm_resend_text' => 'Resend Text Message',
	'netgsm_confirm_code' => 'Confirm Validation Code',
	'netgsm_code' => 'Code',
	'netgsm_code_resent' => 'The validation code has been resent.',
	'netgsm_code_invalid' => 'The code you entered is not valid. Please try again.',
	'netgsm_code_verified' => 'Your account has been successfully verified.',
	'netgsm_phone_number_saved' => 'Your phone number has been successfully saved.',
	'netgsm_phone_number_must_validate' => 'You must validate your new phone number.',
	'netgsm_lost_pass_sent' => 'We\'ve sent a link to your phone to help reset your password.',
	'netgsm_lost_pass_not_found' => 'We could not find a member associated with that phone number.',

	// Extensions
	'member__netgsm_PhoneNumber' => 'Phone Number',
	'profile_netgsm_PhoneNumber' => 'Phone Number',
	'profile_netgsm_PhoneNumber_header' => 'Manage Phone Number',
	'profile_netgsm_PhoneNumber_message' => 'Manage the phone number you have saved to your profile. You will be sent a text message to verify any changes.'
);
