# Bird.com integration

This allows to have WhatsApp chats directly in Live Helper Chat. Bot is also supported.

# Requirements

Min 4.45 Live Helper Chat version.

# Install

Install database
```
php cron.php -s site_admin -e mbird -c cron/update_structure
```
or `doc/install.sql`

Go to Left menu `Modules` -> `Bird.com` and fill your settings.

# Bot sample

Bot sample can be found at [bot-sample.json](https://github.com/LiveHelperChat/mbird/blob/main/doc/bot-sample.json)

Just import a bot and set it for your chosen department.

# Limitations

* Templates are not supported, but coming.
* Only WhatsApp chats are supported at the moment.
