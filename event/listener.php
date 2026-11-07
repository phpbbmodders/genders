<?php
/**
 *
 * Genders extension for the phpBB Forum Software package
 *
 * @copyright (c) 2015 Rich McGirr (RMcGirr83)
 * @copyright (c) 2026, phpBB Modders, https://www.phpbbmodders.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbbmodders\genders\event;

/**
* @ignore
*/
use phpbbmodders\genders\core\genders;
use phpbb\request\request;
use phpbb\request\request_interface;
use phpbb\template\template;
use phpbb\user;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{

	/** @var request $request */
	protected $request;

	/** @var template $template */
	protected $template;

	/** @var user $user */
	protected $user;

	/** @var genders */
	private $genders;

	public function __construct(
		request $request,
		template $template,
		user $user,
		genders $genders
	)
	{
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->genders = $genders;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return [
			'core.ucp_profile_modify_profile_info'		=> 'user_gender_profile',
			'core.ucp_profile_info_modify_sql_ary'		=> 'user_gender_profile_sql',
			'core.acp_users_modify_profile'				=> 'user_gender_profile',
			'core.acp_users_profile_modify_sql_ary'		=> 'user_gender_profile_sql',
			'core.viewtopic_cache_user_data'			=> 'viewtopic_cache_user_data',
			'core.viewtopic_cache_guest_data'			=> 'viewtopic_cache_guest_data',
			'core.viewtopic_modify_post_row'			=> 'viewtopic_modify_post_row',
			'core.memberlist_view_profile'				=> 'memberlist_view_profile',
			'core.search_get_posts_data'				=> 'search_get_posts_data',
			'core.search_modify_tpl_ary'				=> 'search_modify_tpl_ary',
			'core.ucp_register_data_before'				=> 'user_gender_profile',
			'core.ucp_register_user_row_after'			=> 'user_gender_registration_sql',
		];
	}

	/**
	* Allow users to change their gender
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function user_gender_profile($event)
	{
		$stored = (int) ($event['user_row']['user_gender'] ?? $this->user->data['user_gender'] ?? 0);
		$requested = $this->request->variable('user_gender', $stored, false, request_interface::POST);
		$gender_id = $this->genders->selection($requested, $stored);
		$event['data'] = array_merge($event['data'], ['user_gender' => $gender_id]);

		$available = $this->genders->available_ids();
		$choices = $this->genders->choices();
		// Retain an unknown historical value when the profile is saved unchanged.
		$choices[$stored] ??= $choices[0];
		$options = '';
		foreach ($choices as $id => $label)
		{
			if ($id !== $stored && !in_array($id, $available, true))
			{
				continue;
			}

			$selected = $id === $gender_id ? ' selected="selected"' : '';
			$preview = utf8_htmlspecialchars($this->genders->render($id, 18));
			$options .= '<option value="' . $id . '"' . $selected . ' data-preview="' . $preview . '">'
				. utf8_htmlspecialchars($label) . '</option>';
		}

		$this->template->assign_vars([
			'USER_GENDER' => $this->genders->render($gender_id, 18),
			'S_GENDER_OPTIONS' => $options,
			'GENDERS_PREVIEW_JS' => $this->genders->asset_url('styles/all/template/js/genders.js'),
		]);
	}

	/**
	* User changed their gender so update the database
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function user_gender_profile_sql($event)
	{
		$event['sql_ary'] = array_merge($event['sql_ary'], [
				'user_gender' => $event['data']['user_gender'],
		]);
	}

	/**
	* Update viewtopic user data
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_cache_user_data($event)
	{
		$array = $event['user_cache_data'];
		$array['user_gender'] = $event['row']['user_gender'];
		$event['user_cache_data'] = $array;
	}

	/**
	* Update viewtopic guest data
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_cache_guest_data($event)
	{
		$array = $event['user_cache_data'];
		$array['user_gender'] = '';
		$event['user_cache_data'] = $array;
	}

	/**
	* Modify the viewtopic post row
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_modify_post_row($event)
	{
		$gender = '';
		if ($event['user_poster_data']['user_type'] != USER_IGNORE)
		{
			$gender = $this->genders->render((int) $event['user_poster_data']['user_gender']);
		}

		$event['post_row'] = array_merge($event['post_row'],[
			'USER_GENDER' => $gender,
		]);
	}

	/**
	* Display gender on viewing user profile
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function memberlist_view_profile($event)
	{
		$gender = '';
		if ($event['member']['user_type'] != USER_IGNORE)
		{
			$gender = $this->genders->render((int) $event['member']['user_gender']);
		}

		$this->template->assign_vars([
			'USER_GENDER'	=> $gender,
			'S_GENDERS_PROFILE_DETAILS' => $this->genders->in_profile_details(),
		]);
	}

	/**
	* Display gender on search
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function search_get_posts_data($event)
	{
		$array = $event['sql_array'];
		$array['SELECT'] .= ', u.user_gender, u.user_type';
		$event['sql_array'] = $array;
	}

	/**
	* Display gender on search
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function search_modify_tpl_ary($event)
	{
		if ($event['show_results'] == 'topics')
		{
			return;
		}

		$array = $event['tpl_ary'];
		$gender = '';
		if ($event['row']['user_type'] != USER_IGNORE)
		{
			$gender = $this->genders->render((int) $event['row']['user_gender']);
		}
		$array = array_merge($array, [
			'USER_GENDER'	=> $gender,
		]);

		$event['tpl_ary'] = $array;
	}

	/**
	* Update registration data
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function user_gender_registration_sql($event)
	{
		$requested = $this->request->variable('user_gender', 0, false, request_interface::POST);
		$event['user_row'] = array_merge($event['user_row'], [
			'user_gender' => $this->genders->selection($requested, 0),
		]);
	}

}
