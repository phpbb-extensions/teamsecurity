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

class listener_base extends \phpbb_test_case
{
	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $listener;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	protected $log;

	/** @var \phpbb\user */
	protected $user;

	/** @var string */
	protected $root_path;

	/** @var string */
	protected $php_ext;

	/**
	* Setup test environment
	*/
	public function setUp()
	{
		parent::setUp();

		global $phpbb_root_path, $phpEx;

		// Load/Mock classes required by the event listener class
		$this->config = new \phpbb\config\config(array());
		$this->log = $this->getMockBuilder('\phpbb\log\log')
			->disableOriginalConstructor()
			->getMock();
		$this->user = new \phpbb\user('\phpbb\datetime');
		$this->root_path = $phpbb_root_path;
		$this->php_ext = $phpEx;
	}

	/**
	 * Create the event listener
	 */
	protected function set_listener()
	{
		$this->listener = $this->getMock('\phpbb\teamsecurity\event\listener', array('in_watch_group', 'send_message'), array(
				$this->config,
				$this->log,
				$this->user,
				$this->root_path,
				$this->php_ext)
		);
	}
}
