<?php

namespace IPS\netgsm\modules\front\system;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * register
 */
class _register extends \IPS\Dispatcher\Controller
{
	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		parent::execute();
	}

	/**
	 * Send Text
	 *
	 * @return	void
	 */
	protected function sendTextMessage()
	{
		$form = new \IPS\Helpers\Form;
		$form->class = 'ipsForm_vertical';
		$form->add(new \IPS\Helpers\Form\Select('netgsm_phone_country', \IPS\Settings::i()->netgsm_default_country_code ?? null, true, [
			'options' => \IPS\netgsm\Manager\Netgsm::$countryCodes
		]));
		$form->add(new \IPS\Helpers\Form\Tel('netgsm_phone', null, true));

		if ($values = $form->values()) {
			$netgsmManager = new \IPS\netgsm\Manager\Netgsm();
			$parsedPhoneNumber = $netgsmManager->parsePhoneNumber($values['netgsm_phone'], $values['netgsm_phone_country']);
			$netgsmManager->validatePhoneNumber($parsedPhoneNumber);
			$formattedPhoneNumber = $netgsmManager->formatPhoneNumber($parsedPhoneNumber);
			$code = $netgsmManager->generateRandomCode();
			$netgsmManager->sendSms($parsedPhoneNumber, $netgsmManager->composeCodeValidationTextMessage($code));
			$netgsmManager->updateRegistration(\IPS\Member::loggedIn(), [
				'code' => $code,
				'code_sent_at' => time(),
				'phone_number' => $formattedPhoneNumber,
				'country_code' => $values['netgsm_phone_country']
			]);

			\IPS\Output::i()->redirect(\IPS\Http\Url::internal(''));
		}

		\IPS\Output::i()->output = $form->customTemplate(array(\IPS\Theme::i()->getTemplate('forms', 'core'), 'popupTemplate'));
	}

	/**
	 * Confirm Text
	 *
	 * @return	void
	 */
	protected function confirmTextMessage()
	{
		$form = new \IPS\Helpers\Form;
		$form->class = 'ipsForm_vertical';
		$form->add(new \IPS\Helpers\Form\Text('netgsm_code', '', true, [], function ($code) {
			$netgsmManager = new \IPS\netgsm\Manager\Netgsm();
			if (!$netgsmManager->confirmCode(\IPS\Member::loggedIn(), $code)) {
				throw new \DomainException('netgsm_code_invalid');
			}
		}));

		if ($values = $form->values()) {
			$netgsmManager = new \IPS\netgsm\Manager\Netgsm();
			$netgsmManager->setMemberAsVerified(\IPS\Member::loggedIn());

			\IPS\Output::i()->redirect(\IPS\Http\Url::internal(''), 'netgsm_code_verified');
		}

		\IPS\Output::i()->output = $form->customTemplate(array(\IPS\Theme::i()->getTemplate('forms', 'core'), 'popupTemplate'));
	}

	/**
	 * Resend Text
	 *
	 * @return	void
	 */
	protected function resendTextMessage()
	{
		if ($phoneNumber = \IPS\Member::loggedIn()->phone_number) {
			$netgsmManager = new \IPS\netgsm\Manager\Netgsm();
			$code = $netgsmManager->generateRandomCode();
			$parsedPhoneNumber = $netgsmManager->parsePhoneNumber($phoneNumber, \IPS\Member::loggedIn()->phone_number_country_code);
			$response = $netgsmManager->sendSms($parsedPhoneNumber, $netgsmManager->composeCodeValidationTextMessage($code));
			$netgsmManager->updateRegistration(\IPS\Member::loggedIn(), [
				'code' => $code,
				'code_sent_at' => time()
			]);

			\IPS\Output::i()->redirect(\IPS\Http\Url::internal(''), 'netgsm_code_resent');
		}
	}
}