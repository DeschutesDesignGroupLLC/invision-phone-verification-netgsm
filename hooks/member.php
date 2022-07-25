//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class netgsm_hook_member extends _HOOK_CLASS_
{
	/**
	 * Call after completed registration to send email for validation if required or flag for admin validation
	 *
	 * @param	bool				$noEmailValidationRequired	If the user's email is implicitly trusted (for example, provided by a third party), set this to TRUE to bypass email validation
	 * @param	bool				$doNotDelete				If TRUE, the account will not be deleted in the normal cleanup of unvalidated accounts. Used for accounts created in Commerce checkout.
	 * @param	array|NULL			$postBeforeRegister			The row from core_post_before_registering if applicable
	 * @param	\IPS\Http\Url|NULL	$refUrl						The URL the user should be redirected to after validation
	 * @return	void
	 */
	public function postRegistration($noEmailValidationRequired = FALSE, $doNotDelete = FALSE, $postBeforeRegister = NULL, $refUrl = NULL)
	{
	    if (\IPS\Settings::i()->netgsm_registration_enabled)
	    {
	        $netgsmManager = new \IPS\netgsm\Manager\Netgsm();
	        $netgsmManager->setMemberAsUnverified($this, $refUrl);
        }

	    parent::postRegistration($noEmailValidationRequired, $doNotDelete, $postBeforeRegister, $refUrl);
    }

	/**
	 * @return mixed|null
	 */
    public function get_phone_number()
    {
	    try {
		    $phone = \IPS\Db::i()->select('*', 'netgsm_verifications', [
			    'member_id=?', $this->member_id
            ])->first();

		    return $phone['phone_number'] ?? null;
	    } catch (\UnderflowException $exception) {}

        return null;
    }
}
