/**
*
* @package Genders
* @copyright (c) 2020 RMcGirr83
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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
