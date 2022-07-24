<?php
/**
 * @brief		Phone Verification By NETGSM Application Class
 * @author		<a href='https://www.deschutesdesigngroup.com'>Deschutes Design Group LLC</a>
 * @copyright	(c) 2022 Deschutes Design Group LLC
 * @package		Invision Community
 * @subpackage	Phone Verification By NETGSM
 * @since		20 Jul 2022
 * @version		
 */
 
namespace IPS\netgsm;

/**
 * Phone Verification By NETGSM Application Class
 */
class _Application extends \IPS\Application
{
	/**
	 * [Node] Get Icon for tree
	 *
	 * @note    Return the class for the icon (e.g. 'globe')
	 * @return    string|null
	 */
	protected function get__icon()
	{
		return 'phone';
	}
}