# Genders for phpBB3

phpBB extension that allows users to be able to choose a gender.  Gender displays on memberlist and viewtopic.

[![Build Status](https://github.com/rmcgirr83/genders/workflows/Tests/badge.svg)](https://github.com/rmcgirr83/genders/actions)

## Installation

### 1. clone
Clone (or download and move) the repository into the folder ext/rmcgirr83/genders:

```
cd phpBB3
git clone https://github.com/rmcgirr83/phpBB-3.1-genders.git ext/rmcgirr83/genders/
```

### 2. activate
Go to admin panel -> tab customise -> Manage extensions -> enable Genders

## Update instructions:
1. Go to you phpBB-Board > Admin Control Panel > Customise > Manage extensions > Genders: disable
2. Delete all files of the extension from ext/rmcgirr83/genders
3. Upload all the new files to the same locations
4. Go to you phpBB-Board > Admin Control Panel > Customise > Manage extensions > Genders: enable
5. Purge the board cache

## Genders settings

Open **ACP > Extensions > Genders > Settings** to show the gender name before
the icon, choose an icon set, or move gender from User Statistics to the main
profile details. The default is icon-only display in User Statistics.

The icon selector includes the original prosilver and subsilver2 sets, the
(fe)male-images, mini, minigender and planetary sets, and both prosilver
alternates from [Genders 1.0.2 for phpBB 3.0](https://www.phpbb.com/customise/db/mod/genders/)
by Kailey and bonelifer (GPL-2.0). These sets supply Male and Female icons.
Other genders use the current icons.

All choices are available by default. When a choice is disabled, members who
already selected it keep it and can still switch to an available choice. None
Specified is always available.

## TODO

Ideas not yet built, practical and speculative alike: [`docs/TODO.md`](docs/TODO.md).
