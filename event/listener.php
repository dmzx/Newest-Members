<?php
/**
*
* @package phpBB Extension - Newest Members
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\newestmembers\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\config\config */
	protected $config;

	/**
	* Constructor
	* @param \phpbb\template\template			$template
	* @param \phpbb\user						$user
	* @param \phpbb\db\driver\driver_interface	$db
	* @param \phpbb\config\config				$config
	*
	*/
	public function __construct(
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\config\config $config
	)
	{
		$this->template = $template;
		$this->user 	= $user;
		$this->db 		= $db;
		$this->config 	= $config;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.index_modify_page_title'	=> 'index_modify_page_title',
		);
	}

	public function index_modify_page_title($event)
	{
		$this->user->add_lang_ext('dmzx/newestmembers', 'common');
		$value = $this->config['newestmembers_value'];

		$sql = 'SELECT user_id, username, user_colour
			FROM ' . USERS_TABLE . '
			WHERE user_type IN (' . USER_NORMAL . ', ' . USER_FOUNDER . ')
			ORDER BY user_id DESC';
		$result = $this->db->sql_query_limit($sql, $value);

		if ($result)
		{
			while ($row = $this->db->sql_fetchrow($result))
			{
				$this->template->assign_block_vars('newestmembers', array(
					'NEWESTMEMBERS'	=> sprintf($this->user->lang['NEWEST_MEMBERS'], get_username_string('full', $row['user_id'], $row['username'], $row['user_colour'])),
				));
			}
		}
		$this->db->sql_freeresult($result);

		$this->template->assign_vars(array(
			'L_NEWESTMEMBERS_TEXT'	=> $this->user->lang('NEWESTMEMBERS_TEXT', $value),
		));
	}
}
