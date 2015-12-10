<?php
/**
*
* @package phpBB Extension - Newest members
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\newestmembers\event;

/**
* Event listener
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class acp_listener implements EventSubscriberInterface
{
	static public function getSubscribedEvents()
	{
		return array(
			'core.acp_board_config_edit_add'	=> 'add_options',
		);
	}

	public function add_options($event)
	{
		global $user;
		if (($event['mode'] == 'features' || $event['mode'] == 'load') && isset($event['display_vars']['vars']['load_jumpbox']))
		{
			// Store display_vars event in a local variable
			$display_vars = $event['display_vars'];

			// Define config vars
			$config_vars = array(
				'newestmembers_value'	=> array('lang'	=> 'NEWEST_MEMBERS_SETTING',	'validate' => 'int:1',	'type' => 'custom:1:255', 'function' => array($this, 'newmems_length'), 'explain' => true),
			);

			$display_vars['vars'] = phpbb_insert_config_array($display_vars['vars'], $config_vars, array('after' => 'load_jumpbox'));

			// Update the display_vars	event with the new array
			$event['display_vars'] = array('title' => $display_vars['title'], 'vars' => $display_vars['vars']);
		}
	}
	/**
	* Maximum number of Newest members allowed
	*/
	function newmems_length($value, $key = '')
	{
		global $user;
		return '<input id="' . $key . '" type="number" size="3" maxlength="3" min="2" max="255" name="config[newestmembers_value]" value="' . $value . '" />';
	}
}
