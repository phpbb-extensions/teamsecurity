<?php
/**
 *
 * phpBB Team Security Measures
 *
 * @copyright (c) 2015 phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbb\teamsecurity\migrations;

class m3_email_changes extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('phpbb\teamsecurity\migrations\m1_initial');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('sec_email_changes', 0)),
			array('config.add', array('sec_contact_name', '')),
		);
	}
}
