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
		return [
			['admin', LOG_ADMIN, [], false],
			['admin', LOG_ADMIN, ['keywords' => ['test']], false],
			['admin', LOG_ADMIN, ['log_id' => ['IN' => []]], false],
			['mod', LOG_MOD, [], false],
			['user', LOG_USER, [], false],
			['users', LOG_USERS, [], false],
			['', LOG_CRITICAL, [], true],
			['', false, [], false],
		];
	}

	/**
	 * Test the delete logs security event
	 *
	 * @dataProvider delete_logs_security_data
	 */
	public function test_delete_logs_security($mode, $log_type, $conditions, $expected_log_type)
	{
		// Set some user DateTime options
		$this->user->timezone = new \DateTimeZone('UTC');
		$this->lang->lang('datetime', []);

		$this->set_listener();

		$this->listener->expects(self::exactly(count($conditions)))
			->method('send_message');

		$dispatcher = new \phpbb\event\dispatcher();
		$dispatcher->addListener('core.delete_log', [$this->listener, 'delete_logs_security']);

		$event_data = ['mode', 'log_type', 'conditions'];
		$event_data_after = $dispatcher->trigger_event('core.delete_log', compact($event_data));
		extract($event_data_after, EXTR_OVERWRITE);

		self::assertEquals($expected_log_type, $log_type);
	}
}
