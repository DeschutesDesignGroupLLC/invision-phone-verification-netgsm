//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class netgsm_hook_lostpass extends _HOOK_CLASS_
{
	/**
	 * Manage
	 *
	 * @return	void
	 */
	protected function manage()
	{
	    if (\IPS\Request::i()->use === 'email' ||
		    !\IPS\Settings::i()->netgsm_registration_enabled ||
            (!\IPS\Settings::i()->netgsm_lost_password_enabled && \IPS\Settings::i()->netgsm_registration_enabled)) {
	        return parent::manage();
        }

		$form = new \IPS\Helpers\Form('lostpass', 'request_password');
		$captcha = new \IPS\Helpers\Form\Captcha;
		if ( (string) $captcha !== '' ) {
			$form->add( $captcha );
		}

		$form->add(new \IPS\Helpers\Form\Select('netgsm_phone_country', \IPS\Settings::i()->netgsm_default_country_code ?? null, true, [
			'options' => \IPS\netgsm\Manager\Netgsm::$countryCodes
		]));
		$form->add(new \IPS\Helpers\Form\Tel('netgsm_phone', \IPS\Member::loggedIn()->phone_number, true));

		if ($values = $form->values()) {
			$netgsmManager = new \IPS\netgsm\Manager\Netgsm();
			$parsedPhoneNumber = $netgsmManager->parsePhoneNumber($values['netgsm_phone'], $values['netgsm_phone_country']);
			$netgsmManager->validatePhoneNumber($parsedPhoneNumber);
			$formattedPhoneNumber = $netgsmManager->formatPhoneNumber($parsedPhoneNumber);

			try {
				$registration = \IPS\Db::i()->select('*', 'netgsm_registrations', ['phone_number=?', $formattedPhoneNumber])->first();
				$member = \IPS\Member::load($registration['member_id']);
 			} catch (\UnderflowException) {
				\IPS\Output::i()->redirect(\IPS\Http\Url::internal('index.php?app=core&module=system&controller=lostpass', 'lostpassword'), 'netgsm_lost_pass_not_found');
			}

			$vid = $netgsmManager->setMemberAsUnverified($member, false, true);
			$netgsmManager->sendSms($parsedPhoneNumber, $netgsmManager->composeLostPasswordTextMessage($member, $vid));

			\IPS\Output::i()->redirect(\IPS\Http\Url::internal(''), 'netgsm_lost_pass_sent');
        }

		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('lost_password');
		\IPS\Output::i()->sidebar['enabled'] = FALSE;
		\IPS\Output::i()->bodyClasses[] = 'ipsLayout_minimal';
		\IPS\Output::i()->output = \IPS\Theme::i()->getTemplate('system', 'netgsm', 'front')->lostPass($form);
	}
}
