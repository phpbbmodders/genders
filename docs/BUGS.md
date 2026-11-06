# Bugs

## Reports from the extension audit

### Gender controls missing on a local board

**Status: Not reproduced locally with prosilver.** The UCP selector rendered
and saved a gender on disposable phpBB 3.3.19 and 4.0.0-a3-dev boards. The
saved choice appeared on the member profile, and the preview endpoint and ACP
profile selector worked. The affected local board's phpBB and extension
versions, active style and template inheritance, and error logs are still
needed to diagnose the original report. [Source](https://www.phpbb.com/customise/db/extension/phpbb_3.1_genders/support/topic/252382)
