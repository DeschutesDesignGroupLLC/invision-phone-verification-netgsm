//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class netgsm_hook_register extends _HOOK_CLASS_
{
	/**
	 * Awaiting Validation
	 *
	 * @return	void
	 */
	protected function validating()
	{
	    if (\IPS\Settings::i()->netgsm_enabled && \IPS\Member::loggedIn()->member_id)
		{
		    try {
			    $validation = \IPS\Db::i()->select('*', 'netgsm_verifications', [
                    'member_id=?', \IPS\Member::loggedIn()->member_id,
                ])->first();
		    } catch (\UnderflowException $exception) {}

            $codeSent = isset($validation['code'], $validation['code_sent_at'], $validation['phone_number']);

			if (!$validation['verified']) {
				\IPS\Output::i()->output = \IPS\Theme::i()->getTemplate('system', 'netgsm', 'front')->awaitingPhoneValidation($codeSent, $validation['phone_number'] ?? null);
				\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('reg_awaiting_validation');

				return;
			}
		}

		parent::validating();
	}
}
