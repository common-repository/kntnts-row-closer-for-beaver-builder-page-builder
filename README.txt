=== Kntnt's Row Closer for Beaver Builder Page Builder ===
Contributors: TBarregren
Tags: beaver builder
Requires at least: 4.4
Tested up to: 4.9
Requires PHP: 7.0
Stable tag: 1.0.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

WordPress plugin that allows a row in a layout created with Beaver Builder's Page Builder to be visible for a visitor until she clicks on a configured element (e.g. a link, button, icon or the row itself) upon which the row closes and remain closed for a configured time (e.g. the session, number of days or "for ever").  Examples of applications: marketing message, hello-bar and EU cookie consent notices.

== Description ==

This WordPress plugin extends the functionality of both the [free](https://wordpress.org/plugins/beaver-builder-lite-version/) and the [paid](https://www.wpbeaverbuilder.com/) versions of the *Beaver Builder Page Builder*.

= How to use the plugin =

To illustrate how the plugin works, let's suppose that you shall create a row with a message and a button. The row should be visible to each new visitor until she presses the button. When the visitor presses the button, the row will be closed smoothly. The row shall then remain hidden for that particular visitor for a period of time. When the time period is over, the message will be displayed again to the visitor.

Start by creating the row with as you usually do in Beaver Builder's Page Builder. Add two columns. Put a *Text* module with the message in the column to the left. Put a *Button* module in the column to the right. Enter `javascript:void(0)` as link in the settings of the button; this prevents the button from doing anything at all.

Next, open the settings page of the row, click on the *Advanced* tab and scroll down to the *Visibility* section. Click on the drop-down menu *Display*. Provided you have installed this plugin, you should now see an option named `Row has not been closed`. It means te row will be visible if it has not been closed. Choose that option. 

Below the *Display* drop-down should more settings appear now appear. Fill them out as follows:

* **Closing trigger selector:** Enter a [JQuery selector](https://www.w3schools.com/jquery/jquery_ref_selectors.asp) (or a [CSS selector](https://www.w3schools.com/cssref/css_selectors.asp)) that targets the button. To assist you, this plugin adds the class `.closing-row` the row, so it is easier to target elements in the row. For this example, `.closing-row .fl_button` will do.
* **Close time:** Enter the number of milliseconds the closing animation should take. For a short row, you should probably choose a relative small number, e.g. `500`. For a tall row, you should consider to increase the number to get a smooth transition.
* **Cookie expiration:** Enter the number of days you want the message to be hidden. Enter `0` to just hide it during the current session. to hide it "for ever", enter a big number, e.g. `36525` that corresponds to 100 years.
* **Cookie domain:** Enter the domain of your site. If the home page of your site has the address `https://www.example.com/`, then enter `www.example.com`. If you enter `.example.com` (with a dot at the beginning), you allow all subdomains (e.g. `dev.example.com`). You can also leave the field completely blank, which all common browsers interpret as the same domain as the visitor are visiting.

Finally, save the row and publish the layout.

= Technical description =

This plugin prevents Beaver Builder's page builder to render a row that has the visibility set to `Row has not been closed` if the visitors browser are returning a previous set cookie named `row_5967cca712431_closed` with `5967cca712431` replaced with the node id of the row.

If rendered, the row is initially hidden by the CSS property `display` set to `none`. This property will be changed to display the row if the cookie is not set. This last check is carried out by JavaScript.

The reason for this two-step check is caching.

== Installation ==

Install the plugin the [usually way](https://codex.wordpress.org/Managing_Plugins#Installing_Plugins).

== Frequently Asked Questions ==

= How can I make a row visible again? =

When developing your site, you probably want to make a hidden row visible again. To do that, you have to delete the cookie set by this plugin for that row.

The most brutal way is of course to delete all cookies. :-) But as a developer you should instead use the web development tool in Firefox or Chrome. Open the tool. In the menubar look for *[Storage Inspector](https://developer.mozilla.org/en-US/docs/Tools/Storage_Inspector#Cookies)* in Firefox (or just press <kbd>CTRL</kbd> + <kbd>F9</kbd>) and *[Application](https://developers.google.com/web/tools/chrome-devtools/manage-data/cookies)* in Chrome. Locate your site's cookie storage and click on it. You should now see all cookies that your site has put on your computer.

Find a cookie named `row_5967cca712431_closed` where `5967cca712431` is replaced with the node id of the closed row. Right-click on it, choose delete and reload the page. The row should now appear again.

= Where is the setting page? =

There is no setting page.

= Does it work with Beaver Builder 1.x and 2.x? =

Yes! It works both with Beaver Builder 1.x and 2.x.

= Does it work with PowerPack Addon for Beaver Builder? =

It should, but I have not test it with either the [free](https://wordpress.org/plugins/ultimate-addons-for-beaver-builder-lite/) or [paid](https://wpbeaveraddons.com/) version of *PowerPack Addon for Beaver Builder*. If you test, please let me know if it works or not.

= Does it work with Ultimate Addons for Beaver Builder? =

It should, but I have not test it with either the [free](https://wordpress.org/plugins/powerpack-addon-for-beaver-builder/) or [paid](https://www.ultimatebeaver.com/) version of *Ultimate Addons for Beaver Builder*. If you test, please let me know if it works or not.

= Does it work with PHP 5? =

I don't know. I have not test it with PHP 5. If you test, please let me know if it works or not.

= How can I get help? =

If you have a questions about the plugin, and cannot find an answer here, start by [issues](https://github.com/Kntnt/kntnt-bb-any-term/issues) and [pull requests](https://github.com/Kntnt/kntnt-bb-any-term/pulls). If you still cannot find the answer, feel free to ask in the the plugin's issue tracker at Github: [https://github.com/Kntnt/kntnt-bb-any-term/issues](https://github.com/Kntnt/kntnt-bb-any-term/issues).

= How can I report a bug? =

If you have found a potential bug, please report it on the plugin's issue tracker at Github: [https://github.com/Kntnt/kntnt-bb-any-term/issues](https://github.com/Kntnt/kntnt-bb-any-term/issues).

= How can I contribute? =

Contributions to the code or documentation are much appreciated.

If you are unfamiliar with Git, please post it as a new issue on the plugin's issue tracker at Github: [https://github.com/Kntnt/kntnt-bb-any-term/issues](https://github.com/Kntnt/kntnt-bb-any-term/issues).

If you are familiar with Git, please do a pull request.

== Screenshots ==

1. This row will close and remain closed for 1 day for a visitor clicking on the button target by the CSS selector `.closing-row .fl-button`.

== Changelog ==

= 1.0.0 =

Initial release. Fully functional plugin.

== Credits ==

The plugin uses Scott Hamper's JavaScript library [Cookies](https://github.com/ScottHamper/Cookies).

