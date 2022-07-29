<?php

namespace IPS\netgsm\extensions\core\MemberForm;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Admin CP Member Form
 */
class _PhoneNumber
{
	/**
	 * Process Form
	 *
	 * @param	\IPS\Helpers\Form		$form	The form
	 * @param	\IPS\Member				$member	Existing Member
	 * @return	void
	 */
	public function process( &$form, $member )
	{
		$form->add(new \IPS\Helpers\Form\Select('netgsm_phone_country', $member->phone_number_country_code ?? \IPS\Settings::i()->netgsm_default_country_code ?? null, false, [
			'options' => \IPS\netgsm\Manager\Netgsm::$countryCodes
		]));
		$form->add(new \IPS\Helpers\Form\Tel('netgsm_phone', $member->phone_number));
		$form->add(new \IPS\Helpers\Form\YesNo('netgsm_verified', $member->phone_number_verified));
	}
	
	/**
	 * Save
	 *
	 * @param	array				$values	Values from form
	 * @param	\IPS\Member			$member	The member
	 * @return	void
	 */
	public function save( $values, &$member )
	{
		$netgsmManager = new \IPS\netgsm\Manager\Netgsm();
		$parsedPhoneNumber = $netgsmManager->parsePhoneNumber($values['netgsm_phone'], $values['netgsm_phone_country']);
		$netgsmManager->validatePhoneNumber($parsedPhoneNumber);

		$verified = $member->phone_number_verified;

		if ($verified && !$values['netgsm_verified']) {
			$netgsmManager->setMemberAsUnverified($member);
		}
		if (!$verified && $values['netgsm_verified']) {
			$netgsmManager->setMemberAsVerified($member);
		}

		$netgsmManager->updateRegistration($member, [
			'phone_number' => $netgsmManager->formatPhoneNumber($parsedPhoneNumber),
			'country_code' => $values['netgsm_phone_country']
		]);
	}
}