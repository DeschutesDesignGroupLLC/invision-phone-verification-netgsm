<?php

namespace IPS\netgsm\modules\admin\system;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * settings
 */
class _settings extends \IPS\Dispatcher\Controller
{
	/**
	 * @brief Has been CSRF-protected
	 */
	public static $csrfProtected = true;

	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		\IPS\Dispatcher::i()->checkAcpPermission( 'settings_manage' );
		parent::execute();
	}

	/**
	 * ...
	 *
	 * @return	void
	 */
	protected function manage()
	{
		$form = new \IPS\Helpers\Form;

		$usercode = null;
		if (\IPS\Settings::i()->netgsm_usercode) {
			$usercode = \IPS\Text\Encrypt::fromTag(\IPS\Settings::i()->netgsm_usercode)->decrypt();
		}

		$password = null;
		if (\IPS\Settings::i()->netgsm_password) {
			$password = \IPS\Text\Encrypt::fromTag(\IPS\Settings::i()->netgsm_password)->decrypt();
		}

		$form->addTab('netgsm_system_tab');
		$form->addHeader('netgsm_system_header');
		$form->addMessage('netgsm_system_message');
		$form->add(new \IPS\Helpers\Form\Password('netgsm_usercode', $usercode, true));
		$form->add(new \IPS\Helpers\Form\Password('netgsm_password', $password, true));
		$form->add(new \IPS\Helpers\Form\Text('netgsm_sender_name', \IPS\Settings::i()->netgsm_sender_name, true));
		$form->add(new \IPS\Helpers\Form\Select('netgsm_default_country_code', \IPS\Settings::i()->netgsm_default_country_code ?? null, false, [
			'options' => \IPS\netgsm\Manager\Netgsm::$countryCodes
		]));

		$form->addHeader('netgsm_rate_limiter_header');
		$form->addMessage('netgsm_rate_limiter_message');
		$form->add(new \IPS\Helpers\Form\Number('netgsm_rate_limiter_per_minute', \IPS\Settings::i()->netgsm_rate_limiter_per_minute, true));

		$form->addTab('netgsm_registration_tab');
		$form->addHeader('netgsm_registration_header');
		$form->add(new \IPS\Helpers\Form\YesNo('netgsm_registration_enabled', \IPS\Settings::i()->netgsm_registration_enabled, true));
		$form->add(new \IPS\Helpers\Form\Text('netgsm_registration_text_message', \IPS\Settings::i()->netgsm_registration_text_message, true));

		$form->addTab('netgsm_birthday_tab');
		$form->addHeader('netgsm_birthday_header');
		$form->add(new \IPS\Helpers\Form\YesNo('netgsm_birthday_enabled', \IPS\Settings::i()->netgsm_birthday_enabled, true));
		$form->add(new \IPS\Helpers\Form\Text('netgsm_birthday_text_message', \IPS\Settings::i()->netgsm_birthday_text_message, false));

		if ($values = $form->values()) {
			if ($values['netgsm_usercode']) {
				$values['netgsm_usercode'] = \IPS\Text\Encrypt::fromPlaintext($values['netgsm_usercode'])->tag();
			}
			if ($values['netgsm_password']) {
				$values['netgsm_password'] = \IPS\Text\Encrypt::fromPlaintext($values['netgsm_password'])->tag();
			}

			$form->saveAsSettings($values);
		}

		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('menu__netgsm_system_settings');
		\IPS\Output::i()->output = $form;
	}
}