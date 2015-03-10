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

class email_change_notification_test extends listener_base
{
	/**
	 * Data set for test_email_change_notification
	 *
	 * @return array Array of test data
	 */
	public function email_change_notification_data()
	{
		return array(
			array(
				'core.acp_users_overview_modify_data',
				true,
				true,
				array('user_id' => 1, 'user_email' => 'old@mail.tld'),
				array('email' => 'new@mail.tld'),
				'admin@mail.tld',
				'1:1:1',
				array(
					'USERNAME'		=> '',
					'NEW_EMAIL'		=> 'new@mail.tld',
					'OLD_EMAIL'		=> 'old@mail.tld',
					'IP_ADDRESS'	=> '1:1:1',
					'CONTACT'		=> 'admin@mail.tld',
				)
			),
			array(
				'core.acp_users_overview_modify_data',
				true,
				true,
				array('user_id' => 1, 'user_email' => 'old@mail.tld'),
				array('email' => 'old@mail.tld'),
				'admin@mail.tld',
				'1:1:1',
				array()
			),
			array(
				'core.acp_users_overview_modify_data',
				false,
				true,
				array('user_id' => 1, 'user_email' => 'old@mail.tld'),
				array('email' => 'new@mail.tld'),
				'admin@mail.tld',
				'1:1:1',
				array()
			),
			array(
				'core.acp_users_overview_modify_data',
				true,
				false,
				array('user_id' => 1, 'user_email' => 'old@mail.tld'),
				array('email' => 'new@mail.tld'),
				'admin@mail.tld',
				'1:1:1',
				array()
			),
			array(
				'core.acp_users_overview_modify_data',
				false,
				false,
				array('user_id' => 1, 'user_email' => 'old@mail.tld'),
				array('email' => 'new@mail.tld'),
				'admin@mail.tld',
				'1:1:1',
				array()
			),
			array(
				'core.ucp_profile_reg_details_sql_ary',
				true,
				true,
				array('user_id' => 1, 'user_email' => 'old@mail.tld'),
				array('email' => 'new@mail.tld'),
				'admin@mail.tld',
				'1:1:1',
				array(
					'USERNAME'		=> '',
					'NEW_EMAIL'		=> 'new@mail.tld',
					'OLD_EMAIL'		=> 'old@mail.tld',
					'IP_ADDRESS'	=> '1:1:1',
					'CONTACT'		=> 'admin@mail.tld',
				)
			),
			array(
				'core.ucp_profile_reg_details_sql_ary',
				true,
				true,
				array('user_id' => 1, 'user_email' => 'old@mail.tld'),
				array('email' => 'old@mail.tld'),
				'admin@mail.tld',
				'1:1:1',
				array()
			),
			array(
				'core.ucp_profile_reg_details_sql_ary',
				false,
				true,
				array('user_id' => 1, 'user_email' => 'old@mail.tld'),
				array('email' => 'new@mail.tld'),
				'admin@mail.tld',
				'1:1:1',
				array()
			),
			array(
				'core.ucp_profile_reg_details_sql_ary',
				true,
				false,
				array('user_id' => 1, 'user_email' => 'old@mail.tld'),
				array('email' => 'new@mail.tld'),
				'admin@mail.tld',
				'1:1:1',
				array()
			),
			array(
				'core.ucp_profile_reg_details_sql_ary',
				false,
				false,
				array('user_id' => 1, 'user_email' => 'old@mail.tld'),
				array('email' => 'new@mail.tld'),
				'admin@mail.tld',
				'1:1:1',
				array()
			),
		);
	}

	/**
	 * Test email change information is being sent
	 *
	 * @dataProvider email_change_notification_data
	 */
	public function test_email_change_notification($listener, $enabled, $in_watch_group, $user_row, $data, $contact, $ip, $expected)
	{
		$this->config = new \phpbb\config\config(array(
			'sec_email_changes' => $enabled,
			'sec_contact_name'	=> $contact,
		));

		$this->user->data['user_id'] = $user_row['user_id'];
		$this->user->data['user_email'] = $user_row['user_email'];
		$this->user->ip = $ip;

		$this->set_listener();

		$this->listener->expects($this->any())
			->method('in_watch_group')
			->will($this->returnValue($in_watch_group)
		);

		// Check send_message once if conditions are true,
		// otherwise check that it is never called.
		$this->listener->expects(($enabled && $this->user->data['user_email'] != $data['email'] && $in_watch_group) ? $this->once() : $this->never())
			->method('send_message')
			->with($expected);

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener($listener, array($this->listener, 'email_change_notification'));

		$event_data = array('user_row', 'data');
		$event = new \phpbb\event\data(compact($event_data));
		$dispatcher->dispatch($listener, $event);
	}
}
