<?php
/**
*
* @package phpBB Extension - Newest members
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\newestmembers\migrations;

class newestmembers_schema extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		return array(
			// Add config
			array('config.add', array('newestmembers_value', 5)),
		);
	}
}
