<?php
/**
 *
 * Team Security Measures extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2015 phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbb\teamsecurity\tests\event;

class delete_log_security_test extends listener_base
{
	/**
	 * Data set for test_delete_logs_security
	 *
	 * @return array Array of test data
	 */
	public function delete_logs_security_data()
	{
		return array(
			array('admin', true, false),
			array('mod', true, false),
			array('user', true, false),
			array('users', true, false),
			array('', true, true),
			array('', false, false),
		);
	}

	/**
	 * Test the delete logs security event
	 *
	 * @dataProvider delete_logs_security_data
	 */
	public function test_delete_logs_security($mode, $log_type, $expected_log_type)
	{
		// Set some user DateTime options
		$this->user->timezone = new \DateTimeZone('UTC');
		$this->lang->lang('datetime', array());

		$this->set_listener();

		$dispatcher = new \phpbb\event\dispatcher();
		$dispatcher->addListener('core.delete_log', array($this->listener, 'delete_logs_security'));

		$event_data = array('mode', 'log_type');
		$event_data_after = $dispatcher->trigger_event('core.delete_log', compact($event_data));
		extract($event_data_after, EXTR_OVERWRITE);

		self::assertSame($expected_log_type, $log_type);
	}
}
