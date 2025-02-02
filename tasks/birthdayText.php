<?php
/**
 * @brief		birthdayText Task
 * @author		<a href='https://www.invisioncommunity.com'>Invision Power Services, Inc.</a>
 * @copyright	(c) Invision Power Services, Inc.
 * @license		https://www.invisioncommunity.com/legal/standards/
 * @package		Invision Community
 * @subpackage	netgsm
 * @since		25 Jul 2022
 */

namespace IPS\netgsm\tasks;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * birthdayText Task
 */
class _birthdayText extends \IPS\Task
{
	/**
	 * Execute
	 *
	 * If ran successfully, should return anything worth logging. Only log something
	 * worth mentioning (don't log "task ran successfully"). Return NULL (actual NULL, not '' or 0) to not log (which will be most cases).
	 * If an error occurs which means the task could not finish running, throw an \IPS\Task\Exception - do not log an error as a normal log.
	 * Tasks should execute within the time of a normal HTTP request.
	 *
	 * @return	mixed	Message to log or NULL
	 * @throws	\IPS\Task\Exception
	 */
	public function execute()
	{
		if (\IPS\Settings::i()->netgsm_registration_enabled &&
			\IPS\Settings::i()->netgsm_birthday_enabled &&
			$textMessage = \IPS\Settings::i()->netgsm_birthday_text_message) {

			$todaysMonth = date('m');
			$todaysDay = date('d');

			foreach (new \IPS\Patterns\ActiveRecordIterator(\IPS\Db::i()->select('*', 'core_members', [
				['bday_month=?', $todaysMonth],
				['bday_day=?', $todaysDay]
			]), 'IPS\Member') as $member) {

				if ($member->phone_number_verified && $phoneNumber = $member->phone_number) {
					$netgsmManager = new \IPS\netgsm\Manager\Netgsm();
					$parsedPhoneNumber = $netgsmManager->parsePhoneNumber($phoneNumber, $member->phone_number_country_code);
					$netgsmManager->sendSms($parsedPhoneNumber, $textMessage);
				}
			}
		}

		return NULL;
	}
	
	/**
	 * Cleanup
	 *
	 * If your task takes longer than 15 minutes to run, this method
	 * will be called before execute(). Use it to clean up anything which
	 * may not have been done
	 *
	 * @return	void
	 */
	public function cleanup()
	{
		
	}
}