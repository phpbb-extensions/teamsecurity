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
	 * Check whether or not the extension can be enabled.
	 * The current phpBB version should meet or exceed
	 * the minimum version required by this extension:
	 *
	 * Requires phpBB 3.1.3 due to newly added core events.
	 *
	 * @return bool
	 * @access public
	 */
	public function is_enableable()
	{
		$config = $this->container->get('config');
		return phpbb_version_compare($config['version'], '3.1.3', '>=');
	}

	/**
	* Error and prevent attempts to disable this extension
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return null
	* @access public
	*/
	public function disable_step($old_state)
	{
		// Use hardcoded language here since the $user is not available
		trigger_error('Please remove ext.php from the filesystem, purge the board cache, and try again.', E_USER_WARNING);
	}
}
