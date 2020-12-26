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

class event_listener_test extends listener_base
{
	/**
	 * Test the event listener is constructed correctly
	 */
	public function test_construct()
	{
		$this->set_listener();
		self::assertInstanceOf('\Symfony\Component\EventDispatcher\EventSubscriberInterface', $this->listener);
	}

	/**
	 * Test the event listener is subscribing events
	 */
	public function test_getSubscribedEvents()
	{
		self::assertEquals(array(
			'core.user_setup',
			'core.acp_users_overview_before',
			'core.ucp_display_module_before',
			'core.delete_log',
			'core.login_box_failed',
			'core.login_box_redirect',
			'core.acp_users_overview_modify_data',
			'core.ucp_profile_reg_details_sql_ary',
		), array_keys(\phpbb\teamsecurity\event\listener::getSubscribedEvents()));
	}
}
