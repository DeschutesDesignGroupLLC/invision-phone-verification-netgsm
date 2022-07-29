<?php

namespace IPS\netgsm\Phone;

class _Registration extends \IPS\Node\Model
{
	/**
	 * [ActiveRecord] Multiton Store
	 *
	 * @var string
	 */
	protected static $multitons;

	/**
	 * [ActiveRecord] Database Table
	 *
	 * @var string
	 */
	public static $databaseTable = 'netgsm_registrations';

	/**
	 * [ActiveRecord] Database Prefix
	 *
	 * @var string
	 */
	public static $databaseColumnId = 'id';

	/**
	 * [Node] Node Title
	 *
	 * @var string
	 */
	public static $nodeTitle = 'Registrations';

	/**
	 * [Node] Get title
	 *
	 * @return string
	 */
	protected function get__title()
	{
		$member =  \IPS\Member::load($this->member_id);
		return $member->member_id ? "{$member->name} ({$this->phone_number})" : $this->phone_number;
	}
}