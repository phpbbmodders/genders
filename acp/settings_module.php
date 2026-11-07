<?php

namespace rmcgirr83\genders\acp;

use phpbb\request\request_interface;
use rmcgirr83\genders\core\genders;

class settings_module
{
	public $u_action;
	public $tpl_name;
	public $page_title;

	public function main($id, $mode)
	{
		global $config, $language, $phpbb_container, $request, $template;

		$this->tpl_name = 'acp_genders_settings';
		$this->page_title = 'ACP_GENDERS_SETTINGS';
		$language->add_lang('info_acp_genders', 'rmcgirr83/genders');
		add_form_key('acp_genders_settings');

		$genders = $phpbb_container->get('rmcgirr83.genders.core.genders');
		$choices = $genders->choices();
		unset($choices[0]);

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('acp_genders_settings'))
			{
				trigger_error($language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			$icon_set = $request->variable('icon_set', 'current', false, request_interface::POST);
			if (!isset(genders::ICON_SETS[$icon_set]))
			{
				trigger_error($language->lang('GENDERS_INVALID_ICON_SET') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			$requested = $request->variable('available', [0], false, request_interface::POST);
			$available = array_intersect(array_keys($choices), $requested);
			$config->set('genders_available', count($available) === count($choices) ? '*' : implode(',', $available));
			$config->set('genders_icon_set', $icon_set);
			$config->set('genders_show_text', (int) $request->variable('show_text', false, false, request_interface::POST));
			$config->set('genders_profile_details', (int) $request->variable('profile_details', false, false, request_interface::POST));

			trigger_error($language->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
		}

		$template->assign_vars([
			'U_ACTION' => $this->u_action,
			'S_SHOW_TEXT' => (bool) $config['genders_show_text'],
			'S_PROFILE_DETAILS' => $genders->in_profile_details(),
		]);

		foreach (genders::ICON_SETS as $set => $label)
		{
			$template->assign_block_vars('icon_sets', [
				'VALUE' => $set,
				'LABEL' => $language->lang($label),
				'S_SELECTED' => $config['genders_icon_set'] === $set,
			]);
		}

		$available = $genders->available_ids();
		foreach ($choices as $id => $label)
		{
			$template->assign_block_vars('choices', [
				'ID' => $id,
				'LABEL' => $label,
				'S_CHECKED' => in_array($id, $available, true),
			]);
		}
	}
}
