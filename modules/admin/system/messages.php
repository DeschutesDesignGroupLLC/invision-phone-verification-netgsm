<?php


namespace IPS\netgsm\modules\admin\system;

/* To prevent PHP errors (extending class does not exist) revealing path */

use libphonenumber\PhoneNumberFormat;

if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * messages
 */
class _messages extends \IPS\Dispatcher\Controller
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
		\IPS\Dispatcher::i()->checkAcpPermission( 'messages_manage' );
		parent::execute();
	}
	
	/**
	 * Manage
	 *
	 * @return	void
	 */
	protected function manage()
	{
		$table = new \IPS\Helpers\Table\Db('netgsm_messages', \IPS\Http\Url::internal('app=netgsm&module=system&controller=messages'));
		$table->sortBy = $table->sortBy ?: 'message_sent_at';
		$table->sortDirection = $table->sortDirection ?: 'desc';
		$table->quickSearch = [['message'], 'message'];

		$table->include = [
			'id',
			'message',
			'message_sent_at',
			'phone_number',
		];

		$table->parsers = [
			'message_sent_at' => function ($value) {
				return $value ? \IPS\DateTime::ts($value)->html() : null;
			},
			'phone_number' => function ($value) {
				$netgsmManager = new \IPS\netgsm\Manager\Netgsm();
				$parsedPhoneNumber = $netgsmManager->parsePhoneNumber($value);
				return $value ? (new \IPS\netgsm\Manager\Netgsm())->formatPhoneNumber($parsedPhoneNumber, PhoneNumberFormat::INTERNATIONAL) : null;
			},
		];

		$table->rowButtons = function ($row) {
			return [
				'delete' => [
					'icon' => 'times-circle',
					'title' => 'netgsm_messages_delete',
					'link' => \IPS\Http\Url::internal('app=netgsm&module=system&controller=messages&do=delete')->setQueryString(['id' => $row['id']])
				]
			];
		};

		\IPS\Output::i()->sidebar['actions']['add'] = [
			'primary' => true,
			'icon' => 'plus',
			'title' => 'netgsm_messages_new',
			'link' => \IPS\Http\Url::internal('app=netgsm&module=system&controller=messages&do=new')
		];

		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('menu__netgsm_system_messages');
		\IPS\Output::i()->output = \IPS\Theme::i()->getTemplate( 'global', 'core' )->block('title', (string) $table);
	}

	/**
	 * New message
	 */
	public function new()
	{
		$form = new \IPS\Helpers\Form();

		$form->add(new \IPS\Helpers\Form\Member('recipient_ids', null, false, [
			'multiple' => null,
			'nullLang' => 'netgsm_messages_send_all_members'
		]));
		$form->add(new \IPS\Helpers\Form\Textarea('message', null, true));

		if ($values = $form->values()) {

			$netgsmManager = new \IPS\netgsm\Manager\Netgsm();

			if (!$values['recipient_ids']) {
				$values['recipient_ids'] = iterator_to_array(new \IPS\Patterns\ActiveRecordIterator(\IPS\Db::i()->select('*', 'core_members'), 'IPS\Member'));
			}

			$phoneNumbers = collect($values['recipient_ids'])->filter(function ($member) {
				return $member->phone_number && $member->phone_number_verified;
			})->map(function ($member) use ($netgsmManager) {
				return $netgsmManager->parsePhoneNumber($member->phone_number, $member->phone_number_country_code);
			});

			if ($phoneNumbers->isNotEmpty()) {
				$netgsmManager->sendSms($phoneNumbers->toArray(), $values['message']);
				\IPS\Output::i()->redirect(\IPS\Http\Url::internal('app=netgsm&module=system&controller=messages', 'admin'), 'netgsm_new_text_sent');
			}

			\IPS\Output::i()->redirect(\IPS\Http\Url::internal('app=netgsm&module=system&controller=messages', 'admin'), 'netgsm_new_text_sent_empty');
		}

		$form->actionButtons = [
			\IPS\Theme::i()->getTemplate('forms', 'core', 'global')->button('netgsm_messages_send', 'submit', null, 'ipsButton ipsButton_primary', array('tabindex' => '2', 'accesskey' => 's'))
		];

		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('netgsm_messages_new');
		\IPS\Output::i()->output = \IPS\Theme::i()->getTemplate( 'global', 'core' )->block('title', (string) $form);
	}

	/**
	 * Delete message
	 */
	public function delete()
	{
		if (\IPS\Request::i()->id) {
			\IPS\Request::i()->confirmedDelete();

			$message = \IPS\netgsm\Phone\Message::load(\IPS\Request::i()->id);
			$message->delete();

			\IPS\Output::i()->redirect(\IPS\Http\Url::internal('app=netgsm&module=system&controller=messages', 'admin'), 'netgsm_messages_deleted');
		}
	}
}