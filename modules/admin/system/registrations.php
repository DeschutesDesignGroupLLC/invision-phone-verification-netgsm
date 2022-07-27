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
 * registrations
 */
class _registrations extends \IPS\Dispatcher\Controller
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
		\IPS\Dispatcher::i()->checkAcpPermission( 'registrations_manage' );
		parent::execute();
	}
	
	/**
	 * Manage
	 *
	 * @return	void
	 */
	protected function manage()
	{
		$table = new \IPS\Helpers\Table\Db('netgsm_verifications', \IPS\Http\Url::internal( 'app=netgsm&module=system&controller=registrations'));
		$table->sortBy = $table->sortBy ?: 'verified_at';
		$table->sortDirection = $table->sortDirection ?: 'desc';
		$table->quickSearch = [['code', 'verified'], 'registration'];

		$table->joins = [
			[
				'select' => 'core_members.*',
				'from' => 'core_members',
				'where' => 'core_members.member_id=netgsm_verifications.member_id'
			]
		];

		$table->include = [
			'id',
			'member',
			'phone_number',
			'verified',
			'verified_at',
			'code',
			'code_sent_at'
		];

		$table->parsers = [
			'member' => function($value, $row) {
				return \IPS\Member::load($row['member_id'])->link();
			},
			'code_sent_at' => function ($value) {
				return $value ? \IPS\DateTime::ts($value)->html() : null;
			},
			'verified' => function($value) {
				return $value ? 'True' : 'False';
			},
			'verified_at' => function ($value) {
				return $value ? \IPS\DateTime::ts($value)->html() : null;
			},
			'phone_number' => function ($value) {
				return $value ? (new \IPS\netgsm\Manager\Netgsm())->formatPhoneNumber($value, PhoneNumberFormat::INTERNATIONAL) : null;
			},
		];

		$table->filters = [
			'verified' => '(netgsm_verifications.verified=1)'
		];

		$table->rowButtons = function ($row) {
			return [
				'profile' => [
					'icon' => 'user',
					'title' => 'netgsm_registration_profile',
					'link' => \IPS\Http\Url::internal('app=core&module=members&controller=members&do=view', 'admin')->setQueryString(['id' => $row['member_id']])
				],
				'verify' => [
					'icon' => 'check',
					'title' => 'netgsm_registration_verify',
					'link' => \IPS\Http\Url::internal('app=netgsm&module=system&controller=registrations&do=verify')->setQueryString(['id' => $row['member_id']])
				],
				'unverify' => [
					'icon' => 'ban',
					'title' => 'netgsm_registration_unverify',
					'link' => \IPS\Http\Url::internal('app=netgsm&module=system&controller=registrations&do=unverify')->setQueryString(['id' => $row['member_id']])
				],
				'delete' => [
					'icon' => 'times-circle',
					'title' => 'netgsm_registration_delete',
					'link' => \IPS\Http\Url::internal('app=netgsm&module=system&controller=registrations&do=delete')->setQueryString(['id' => $row['id']])
				]
			];
		};

		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('menu__netgsm_system_registrations');
		\IPS\Output::i()->output = \IPS\Theme::i()->getTemplate('global', 'core')->block('title', (string) $table);
	}

	/**
	 * Verify registration
	 */
	public function verify()
	{
		if (\IPS\Request::i()->id) {
			$netgsmManager = new \IPS\netgsm\Manager\Netgsm();
			$netgsmManager->setMemberAsVerified(\IPS\Member::load(\IPS\Request::i()->id));

			\IPS\Output::i()->redirect(\IPS\Http\Url::internal('app=netgsm&module=system&controller=registrations', 'admin'), 'netgsm_registration_verified');
		}
	}

	/**
	 * Unverify registration
	 */
	public function unverify()
	{
		if (\IPS\Request::i()->id) {
			$netgsmManager = new \IPS\netgsm\Manager\Netgsm();
			$netgsmManager->setMemberAsUnverified(\IPS\Member::load(\IPS\Request::i()->id));

			\IPS\Output::i()->redirect(\IPS\Http\Url::internal('app=netgsm&module=system&controller=registrations', 'admin'), 'netgsm_registration_unverified');
		}
	}

	/**
	 * Delete registration
	 */
	public function delete()
	{
		if (\IPS\Request::i()->id) {
			\IPS\Request::i()->confirmedDelete();

			$registration = \IPS\netgsm\Phone\Registration::load(\IPS\Request::i()->id);
			$registration->delete();

			\IPS\Output::i()->redirect(\IPS\Http\Url::internal('app=netgsm&module=system&controller=registrations', 'admin'), 'netgsm_registration_deleted');
		}
	}
}