//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class netgsm_hook_phone extends _HOOK_CLASS_
{
	/**
	 * Phone
	 *
	 * @return	string
	 */
	protected function _phone()
	{
	    $form = new \IPS\Helpers\Form;
		$form->class = 'ipsForm_collapseTablet';

		$form->add(new \IPS\Helpers\Form\Select('netgsm_phone_country', \IPS\Settings::i()->netgsm_default_country_code ?? null, true, [
			'options' => \IPS\netgsm\Manager\Netgsm::$countryCodes
		]));
		$form->add(new \IPS\Helpers\Form\Tel('netgsm_phone', \IPS\Member::loggedIn()->phone_number, true));

		if ($values = $form->values()) {
			$netgsmManager = new \IPS\netgsm\Manager\Netgsm();
			$parsedPhoneNumber = $netgsmManager->parsePhoneNumber($values['netgsm_phone'], $values['netgsm_phone_country']);
			$netgsmManager->validatePhoneNumber($parsedPhoneNumber);
			$formattedPhoneNumber = $netgsmManager->formatPhoneNumber($parsedPhoneNumber);
			if ($formattedPhoneNumber !== \IPS\Member::loggedIn()->phone_number) {

				$code = $netgsmManager->generateRandomCode();
				$netgsmManager->sendSms($formattedPhoneNumber, $netgsmManager->composeCodeValidationTextMessage($code));
				$netgsmManager->setMemberAsUnverified(\IPS\Member::loggedIn());
				$netgsmManager->updateVerificationStatus(\IPS\Member::loggedIn(), [
					'code' => $code,
					'code_sent_at' => time(),
					'phone_number' => $formattedPhoneNumber
				]);

				\IPS\Output::i()->redirect(\IPS\Http\Url::internal(''), 'netgsm_phone_number_must_validate');
			}

			\IPS\Output::i()->redirect(\IPS\Http\Url::internal(''), 'netgsm_phone_number_saved');
		}

		return \IPS\Theme::i()->getTemplate('settings', 'netgsm', 'front')->changePhoneNumber($form);
	}
}
