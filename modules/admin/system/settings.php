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

		$form->addHeader('netgsm_phone_verification_settings');
		$form->add(new \IPS\Helpers\Form\YesNo('netgsm_enabled', \IPS\Settings::i()->netgsm_enabled, true));
		$form->addHeader('netgsm_settings');
		$form->addMessage('This application supports international phone numbers. In order to properly validate a phone number, the country needs to be specified when saving a phone number. You can set the default country below. A member can always change the country when entering their phone number.');
		$form->add(new \IPS\Helpers\Form\Password('netgsm_usercode', $usercode, true));
		$form->add(new \IPS\Helpers\Form\Password('netgsm_password', $password, true));
		$form->add(new \IPS\Helpers\Form\Text('netgsm_sender_name', \IPS\Settings::i()->netgsm_sender_name, true));
		$form->add(new \IPS\Helpers\Form\Text('netgsm_text_message', \IPS\Settings::i()->netgsm_text_message, true));
		$form->add(new \IPS\Helpers\Form\Select('netgsm_default_country_code', \IPS\Settings::i()->netgsm_default_country_code ?? null, false, [
			'options' => \IPS\netgsm\Manager\Netgsm::$countryCodes
		]));

		if ($values = $form->values()) {
			if ($values['netgsm_usercode']) {
				$values['netgsm_usercode'] = \IPS\Text\Encrypt::fromPlaintext($values['netgsm_usercode'])->tag();
			}
			if ($values['netgsm_password']) {
				$values['netgsm_password'] = \IPS\Text\Encrypt::fromPlaintext($values['netgsm_password'])->tag();
			}

			$form->saveAsSettings($values);
		}

		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('settings');
		\IPS\Output::i()->output = $form;
	}
}