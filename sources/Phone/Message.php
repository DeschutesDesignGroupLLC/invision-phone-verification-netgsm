<?php

namespace IPS\netgsm\Phone;

class _Message extends \IPS\Node\Model
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
	public static $databaseTable = 'netgsm_messages';

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
	public static $nodeTitle = 'Messages';

	/**
	 * [Node] Get title
	 *
	 * @return string
	 */
	protected function get__title()
	{
		return $this->message;
	}

	protected function sendSms()
	{
		$netgsmManager = new \IPS\netgsm\Manager\_Netgsm();
		$netgsmManager->sendSms(collect($this->recpient_ids)->map(function () {

		}), $this);
	}
}