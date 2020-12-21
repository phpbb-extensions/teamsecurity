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

class failed_logins_test extends listener_base
{
	/**
	 * Data set for log_failed_login_attempts
	 *
	 * @return array Array of test data
	 */
	public function log_failed_login_attempts_data()
	{
		return array(
			array(true, true, array('user_row' => array('user_id' => 2))),
			array(false, true, array()),
			array(true, false, array()),
			array(false, false, array()),
		);
	}

	/**
	 * Test failed login attempt information is being logged
	 *
	 * @dataProvider log_failed_login_attempts_data
	 */
	public function test_log_failed_login_attempts($enabled, $in_watch_group, $result)
	{
		$this->config = new \phpbb\config\config(array(
			'sec_login_attempts' => $enabled,
		));

		$this->set_listener();

		$this->listener->expects(self::atMost(1))
			->method('in_watch_group')
			->willReturn($in_watch_group);

		// Check log->add is called once with expected data if enabled and in_watch_group are true,
		// otherwise check that it is never called.
		$this->log->expects(($enabled && $in_watch_group) ? self::once() : self::never())
			->method('add')
			->with('user', $result['user_row']['user_id'], $this->user->ip, 'LOG_TEAM_AUTH_FAIL', time(), array('reportee_id' => $result['user_row']['user_id']));

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.login_box_failed', array($this->listener, 'log_failed_login_attempts'));

		$event_data = array('result');
		$event = new \phpbb\event\data(compact($event_data));
		$dispatcher->dispatch('core.login_box_failed', $event);
	}
}
