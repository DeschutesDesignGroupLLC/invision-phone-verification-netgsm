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
		$phoneNumber = null;
		$verified = false;
		try {
			$verification = \IPS\Db::i()->select('*', 'netgsm_verifications', [
				'member_id=?', $member->member_id
			])->first();
			$phoneNumber = $verification['phone_number'];
			$verified = $verification['verified'];
		} catch (\UnderflowException) {}

		$form->add(new \IPS\Helpers\Form\Select('netgsm_phone_country', \IPS\Settings::i()->netgsm_default_country_code ?? null, false, [
			'options' => \IPS\netgsm\Manager\Netgsm::$countryCodes
		]));
		$form->add(new \IPS\Helpers\Form\Tel('netgsm_phone', $phoneNumber));
		$form->add(new \IPS\Helpers\Form\YesNo('netgsm_verified', $verified));
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
		$validatedPhoneNumber = $netgsmManager->validatePhoneNumber($values['netgsm_phone'], $values['netgsm_phone_country']);
		if (!$validatedPhoneNumber) {
			throw new \DomainException('The phone number you entered is not valid. Please try again.');
		}

		try {
			$verification = \IPS\Db::i()->select('*', 'netgsm_verifications', [
				'member_id=?', $member->member_id
			])->first();

			if ($verification['verified'] && !$values['netgsm_verified']) {
				$netgsmManager->setMemberAsUnverified($member);
			}
			if (!$verification['verified'] && $values['netgsm_verified']) {
				$netgsmManager->setMemberAsVerified($member);
			}
		} catch (\UnderflowException) {}

		$netgsmManager->updateVerificationStatus($member, [
			'phone_number' => $netgsmManager->formatPhoneNumber($validatedPhoneNumber)
		]);
	}
}