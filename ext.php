<?php
/**
 *
 * Team Security Measures extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2015 phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbb\teamsecurity;

/**
* Extension class for custom enable/disable/purge actions
*/
class ext extends \phpbb\extension\base
{
	/**
	* Error and prevent attempts to disable this extension
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return null
	*/
	function disable_step($old_state)
	{
		// Use hardcoded language here since the $user is not available
		trigger_error('Please remove ext.php and try again.', E_USER_WARNING);
	}
}
