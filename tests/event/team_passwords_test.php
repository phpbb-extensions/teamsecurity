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

class team_passwords_test extends listener_base
{
	/**
	 * Data set for test_set_team_password_configs
	 *
	 * @return array Array of test data
	 */
	public function set_team_password_configs_data()
	{
		return array(
			array('core.acp_users_overview_before', 'reg_details',	array(), true, true, 30, 'PASS_TYPE_SYMBOL',),
			array('core.acp_users_overview_before', 'overview', 	array(), true, true, 30, 'PASS_TYPE_SYMBOL',),
			array('core.acp_users_overview_before', 'reg_details', 	array(), false, true, 0, '',),
			array('core.acp_users_overview_before', 'overview', 	array(), false, true, 0, '',),
			array('core.acp_users_overview_before', 'reg_details',	array(), true, false, 0, '',),
			array('core.acp_users_overview_before', 'overview', 	array(), true, false, 0, '',),
			array('core.ucp_display_module_before', 'reg_details', 	array(), true, true, 30, 'PASS_TYPE_SYMBOL',),
			array('core.ucp_display_module_before', 'overview', 	array(), true, true, 30, 'PASS_TYPE_SYMBOL',),
			array('core.ucp_display_module_before', 'reg_details', 	array(), false, true, 0, '',),
			array('core.ucp_display_module_before', 'overview', 	array(), false, true, 0, '',),
			array('core.ucp_display_module_before', 'reg_details', 	array(), true, false, 0, '',),
			array('core.ucp_display_module_before', 'overview', 	array(), true, false, 0, '',),
		);
	}

	/**
	 * Test team password configs are being updated
	 *
	 * @dataProvider set_team_password_configs_data
	 */
	public function test_set_team_password_configs($listener, $mode, $user_row, $sec_strong_pass, $in_watch_group, $sec_min_pass_chars, $expected)
	{
		$this->config = new \phpbb\config\config(array(
			'pass_complex' => '',
			'min_pass_chars' => 0,
			'sec_strong_pass' => $sec_strong_pass,
			'sec_min_pass_chars' => $sec_min_pass_chars,
		));

		$this->set_listener();

		$this->listener->expects(self::atMost(1))
			->method('in_watch_group')
			->willReturn($in_watch_group);

		$dispatcher = new \phpbb\event\dispatcher();
		$dispatcher->addListener($listener, array($this->listener, 'set_team_password_configs'));

		$event_data = array('mode', 'user_row');
		$dispatcher->trigger_event($listener, compact($event_data));

		self::assertEquals($expected, $this->config['pass_complex']);
		self::assertEquals($sec_min_pass_chars, $this->config['min_pass_chars']);
	}
}
