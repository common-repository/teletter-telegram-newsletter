=== Plugin Name ===
Contributors: websima
Donate link: http://websima.com
Tags: Telegram, Telegram Bot, Telegram Newsletter, Newsletter, translate ready
Requires at least: 3.0.1
Tested up to: 4.4
Stable tag: 1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Send Newsletter from Telegram Bot, user can subscribe to your site from Telegram Bot.

== Description ==

Bots are special Telegram accounts designed to handle messages automatically. Users can interact with bots by sending them command messages in private or group chats.

Teletter (Telegram Newsletter) helps you to integrate Telegram bot to your Wordpress site and send newsletter to subscribers.

first of all you have to <a href="https://core.telegram.org/bots#botfather">Create a New Bot</a> and get your Token API, then install the plugin, configure settings and send newsletters to subscribers.
Persian Tutorial : <a href="http://websima.com/%D8%B1%D8%A8%D8%A7%D8%AA-%D8%AA%D9%84%DA%AF%D8%B1%D8%A7%D9%85/">ربات تلگرام چیست</a>

Features:

*   Using getUpdates method, no need to https
*   Add Subscribers automatic to your subscribers list 
*   Send newsletter by posting or updating new articles
*   Sending options including: URL, custom message or both of them
*   Limit number of new updates from Telegram API 
*   Set currency time for get new updates (Hourly, Twice a day,Daily)  
*   Get Updates Manually
*   Supporting Custom post types 
*   Showing de active users (unsubscribers)
*   Translate Ready
*   Send Newsletter to all users or active users
*   Define admin users to send newsletter from your telegram account (Supporting Text or Image)
*   Send Image with Captions to All Users from Admin panel
*   Log all Messages Sent or Received on Admin panel
*   Admin users receive new subscribers and unsubscribers alarm on their telegram account 

Note: this plugin is not supporting Webhook method now, we are working on it.
Version 1.2 was Updated by using some codes from <a href="https://wordpress.org/plugins/telegram-bot/">Telegram Bot</a> Plugin.

Visit Tutorials in Persian Language: <a href="http://websima.com/teletter" title="افزونه تلگرام">افزونه تلگرام</a>

== Installation ==
From your WordPress dashboard
1. Visit 'Plugins > Add New'

2. Search for 'Telegram Bot Newsletter'

3. Activate Telegram Newsletter from your Plugins page. (You'll be greeted with a Welcome page.)

From WordPress.org
1. Download Telegram Newsletter.

2. Upload the 'Telegram-Newsletter' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
Activate Telegram Newsletter from your Plugins page.

Extra
Visit 'Settings > Telegram API' and adjust your configuration.

a new Page Template is added as 'Telegram API', make a new page, use this template and view the page for manual updates.

== Frequently Asked Questions ==

= Does it work with webhook method? =

Not now, we are working on it. webhook method needs ssl.

= Does it send the newsletter with every new post or update post? =

Yes you can send newsletter whenever you publish or update a post, but the newsletter will be sent if you click the check box named as "Send to All Subscribers".

== Screenshots ==

1. Telegram API Settings Page
2. Telegram API Meta Box on posts, pages or custom post types
3. List of Subscribers showing details.  (User id, first name, last name, Telegram Username, Activation Status, Date of Subscription)

== Changelog ==

= 1.3 =
* Check Compatibility by Wordpress 4.4 and fix bugs

= 1.2 =
* Fix Some Bugs.
* Add Messages Log.
* Change Menu and Submenus
* Add Ability of Send Photo to users from Wordpress panel
* Add Ability of Send Photo to users from Telegram Admin user

= 1.1 =
* Fix Some Bugs.
* Add admin user to send newsletter directly from telegram.
* Send Newsletter to all users or just active users

= 1.0 =
* Fix Some Bugs.
* Indicating deactivated subscribers.
* Supporting Custom Post Types

= 0.9 =
* First Release.

== Upgrade Notice ==
= 1.3 =
Check Compatibility by Wordpress 4.4 and fix bugs

= 1.2 =
Send photos to subscribers and view Log History

= 1.1 =
Admin users can send newsletter from their telegram account.

= 1.0 =
indicate deactivated subscribers and not sending messages to them any more.

= 0.9 =
Use free telegram API for sending newsletters.
