/**
 *
 * Genders extension for the phpBB Forum Software package
 *
 * @copyright (c) 2020 RMcGirr83
 * @copyright (c) 2026, phpBB Modders, https://www.phpbbmodders.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

(function () {
	'use strict';

	var select = document.getElementById('user_gender');
	var preview = document.getElementById('gender_image');

	if (select && preview) {
		select.addEventListener('change', function () {
			preview.innerHTML = select.options[select.selectedIndex].dataset.preview;
		});
	}
})();
