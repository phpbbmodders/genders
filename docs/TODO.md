# TODO

## Gender Display

Source: [Include Gender text before the gender image](https://github.com/phpbbmodders/phpbb-3.1-ext-genders/issues/1).
Old MOD reference: [Genders 1.0.2 for phpBB 3.0.8](https://www.phpbb.com/customise/db/mod/genders/). The official archive was verified against its catalogue checksum. All eight icon sets are included as separate ACP choices.

- [x] Add an ACP switch to display the gender text before the icon in profiles, posts, search results and form previews.
- [x] Include every icon set from the phpBB 3.0 MOD as a separate ACP choice. Other genders retain their Font Awesome symbols, including the compatible Intersex symbol on phpBB 4.0.

## Requests from the extension audit

### Move gender information to the profile area

- [x] Add an ACP choice between User Statistics and the main profile details. [Source](https://www.phpbb.com/customise/db/extension/phpbb_3.1_genders/support/topic/239486)

### Allow boards to choose available gender options

- [x] Add ACP controls for available choices. Preserve stored selections and identifiers, reject newly selected disabled values, and keep None Specified available. [Source](https://www.phpbb.com/customise/db/extension/phpbb_3.1_genders/support/topic/252088)

## Gender-specific language strings

Source: [leschek's request](https://www.phpbb.com/community/viewtopic.php?p=16144225#p16144225) in the development topic.

- [x] Apply per-gender override files (`language/<iso>/gendered/<gender>.php`) to the reader's own wording, kept in place for language files loaded later in the page. [Wiki](https://github.com/phpbbmodders/genders/wiki/Gender-specific-language-strings)
- Wording about another member (for example "Posted" next to someone else's post) is out of scope: phpBB uses one string per page.
