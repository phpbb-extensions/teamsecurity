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
	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\teamsecurity\event\listener */
	protected $listener;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var hpbb\messenger\method\email */
	protected $email_method;

	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\log\log */
	protected $log;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\language\language */
	protected $lang;

	/** @var string */
	protected $root_path;

	/** @var string */
	protected $php_ext;

	/**
	* Setup test environment
	*/
	protected function setUp(): void
	{
		parent::setUp();

		global $phpbb_dispatcher, $phpbb_root_path, $phpEx;

		// Load/Mock classes required by the event listener class
		$this->config = new \phpbb\config\config(array('default_dateformat' => 'D M d, Y H:i:s A'));
		$this->email_method = $this->getMockBuilder('\phpbb\messenger\method\email')->disableOriginalConstructor()->getMock();
		$this->log = $this->getMockBuilder('\phpbb\log\log')
			->disableOriginalConstructor()
			->getMock();
		$lang_loader = new \phpbb\language\language_file_loader($phpbb_root_path, $phpEx);
		$this->lang = new \phpbb\language\language($lang_loader);
		$this->user = new \phpbb\user($this->lang, '\phpbb\datetime');
		$this->user->data['user_id'] = 100;
		$this->user->data['username'] = '';
		$this->user->data['user_email'] = '';
		$this->root_path = $phpbb_root_path;
		$this->php_ext = $phpEx;
		$phpbb_dispatcher = new \phpbb_mock_event_dispatcher();
	}

	/**
	 * Create the event listener
	 */
	protected function set_listener()
	{
		$this->listener = $this->getMockBuilder('\phpbb\teamsecurity\event\listener')
			->onlyMethods(array(
				'in_watch_group',
				'send_message'
			))
			->setConstructorArgs(array(
				$this->config,
				$this->email_method,
				$this->lang,
				$this->log,
				$this->user,
				$this->root_path,
				$this->php_ext
			))
			->getMock();
	}
}
