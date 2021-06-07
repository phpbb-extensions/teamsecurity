<?php
/**
 *
 * Team Security Measures extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2016 phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbb\teamsecurity\tests\functional;

/**
 * @group functional
 */
class functional_test extends \phpbb_functional_test_case
{
	protected static function setup_extensions()
	{
		return array('phpbb/teamsecurity');
	}

	public function test_acp_modules()
	{
		$this->add_lang_ext('phpbb/teamsecurity', 'acp_teamsecurity');

		$this->login();
		$this->admin_login();

		$crawler = self::request('GET', "adm/index.php?i=\\phpbb\\teamsecurity\\acp\\teamsecurity_module&mode=settings&sid=$this->sid");
		$this->assertContainsLang('ACP_TEAM_SECURITY_SETTINGS', $crawler->filter('#main h1')->text());
	}
}
