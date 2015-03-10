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
		$this->user->lang['datetime'] = array();

		$this->set_listener();

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.delete_log', array($this->listener, 'delete_logs_security'));

		$event_data = array('mode', 'log_type');
		$event = new \phpbb\event\data(compact($event_data));
		$dispatcher->dispatch('core.delete_log', $event);

		$new_events = $event->get_data_filtered($event_data);

		$this->assertSame($expected_log_type, $new_events['log_type']);
	}
}
