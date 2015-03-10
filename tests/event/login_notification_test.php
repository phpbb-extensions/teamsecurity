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

class login_notification_test extends listener_base
{
	/**
	 * Data set for test_acp_login_notification
	 *
	 * @return array Array of test data
	 */
	public function acp_login_notification_data()
	{
		return array(
			array(true, true, 'foo', '1:1:1', array(
				'USERNAME' => 'foo',
				'IP_ADDRESS' => '1:1:1',
				'LOGIN_TIME' => '',
			)),
			array(true, false, null, null, array()),
			array(true, false, null, null, array()),
			array(false, false,null, null, array()),
		);
	}

	/**
	 * Test ACP login information is being sent
	 *
	 * @dataProvider acp_login_notification_data
	 */
	public function test_acp_login_notification($enabled, $admin, $username, $ip, $expected)
	{
		$this->config = new \phpbb\config\config(array(
			'sec_login_email' => $enabled,
		));

		$this->user->data['username'] = $username;
		$this->user->ip = $ip;

		// Set some user DateTime options
		$this->user->timezone = new \DateTimeZone('UTC');
		$this->user->lang['datetime'] = array();

		$this->set_listener();

		// Set the LOGIN_TIME now, since we can't set it during the data array setup
		$expected['LOGIN_TIME'] = $this->user->format_date(time(), 'D M d, Y H:i:s A', true);

		// Check send_message once if enabled and admin are true,
		// otherwise check that it is never called.
		$this->listener->expects(($enabled && $admin) ? $this->once() : $this->never())
			->method('send_message')
			->with($expected);

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.login_box_redirect', array($this->listener, 'acp_login_notification'));

		$event_data = array('admin');
		$event = new \phpbb\event\data(compact($event_data));
		$dispatcher->dispatch('core.login_box_redirect', $event);
	}
}
