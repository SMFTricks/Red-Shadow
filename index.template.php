<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0.19
 */

/*	This template is, perhaps, the most important template in the theme. It
	contains the main template layer that displays the header and footer of
	the forum, namely with main_above and main_below. It also contains the
	menu sub template, which appropriately displays the menu; the init sub
	template, which is there to set the theme up; (init can be missing.) and
	the linktree sub template, which sorts out the link tree.

	The init sub template should load any data and set any hardcoded options.

	The main_above sub template is what is shown above the main content, and
	should contain anything that should be shown up there.

	The main_below sub template, conversely, is shown after the main content.
	It should probably contain the copyright statement and some other things.

	The linktree sub template should display the link tree, using the data
	in the $context['linktree'] variable.

	The menu sub template should display all the relevant buttons the user
	wants and or needs.

	For more information on the templating system, please see the site at:
	http://www.simplemachines.org/
*/

// Initialize the template... mainly little settings.
function template_init()
{
	global $context, $settings, $options, $txt;

	/* Use images from default theme when using templates from the default theme?
		if this is 'always', images from the default theme will be used.
		if this is 'defaults', images from the default theme will only be used with default templates.
		if this is 'never' or isn't set at all, images from the default theme will not be used. */
	$settings['use_default_images'] = 'never';

	/* What document type definition is being used? (for font size and other issues.)
		'xhtml' for an XHTML 1.0 document type definition.
		'html' for an HTML 4.01 document type definition. */
	$settings['doctype'] = 'xhtml';

	/* The version this template/theme is for.
		This should probably be the version of SMF it was created for. */
	$settings['theme_version'] = '2.0';

	/* Set a setting that tells the theme that it can render the tabs. */
	$settings['use_tabs'] = true;

	/* Use plain buttons - as opposed to text buttons? */
	$settings['use_buttons'] = true;

	/* Show sticky and lock status separate from topic icons? */
	$settings['separate_sticky_lock'] = true;

	/* Does this theme use the strict doctype? */
	$settings['strict_doctype'] = false;

	/* Does this theme use post previews on the message index? */
	$settings['message_index_preview'] = false;

	/* Set the following variable to true if this theme requires the optional theme strings file to be loaded. */
	$settings['require_theme_strings'] = true;
}

// The main sub template above the content.
function template_html_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	// Show right to left and the character set for ease of translating.
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
<head>';

	// The ?fin20 part of this link is just here to make sure browsers don't cache it wrongly.
	echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index', $context['theme_variant'], '.css?fin20" />';

	// Some browsers need an extra stylesheet due to bugs/compatibility issues.
	foreach (array('ie7', 'ie6', 'webkit') as $cssfix)
		if ($context['browser']['is_' . $cssfix])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/', $cssfix, '.css" />';

	// RTL languages require an additional stylesheet.
	if ($context['right_to_left'])
		echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/rtl.css" />';

	// Here comes the JavaScript bits!
	echo '
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/globaljs.js"></script>
	<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/script.js?fin20"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/theme.js?fin20"></script>
	<script type="text/javascript"><!-- // --><![CDATA[ 
		var smf_theme_url = "', $settings['theme_url'], '";
		var smf_default_theme_url = "', $settings['default_theme_url'], '";
		var smf_images_url = "', $settings['images_url'], '";
		var smf_scripturl = "', $scripturl, '";
		var smf_iso_case_folding = ', $context['server']['iso_case_folding'] ? 'true' : 'false', ';
		var smf_charset = "', $context['character_set'], '";', $context['show_pm_popup'] ? '
		var fPmPopup = function ()
		{
			if (confirm("' . $txt['show_personal_messages'] . '"))
				window.open(smf_prepareScriptUrl(smf_scripturl) + "action=pm");
		}
		addLoadEvent(fPmPopup);' : '', '
		var ajax_notification_text = "', $txt['ajax_in_progress'], '";
		var ajax_notification_cancel_text = "', $txt['modify_cancel'], '";
	// ]]></script>';

	echo '
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
	<meta name="description" content="', $context['page_title_html_safe'], '" />', !empty($context['meta_keywords']) ? '
	<meta name="keywords" content="' . $context['meta_keywords'] . '" />' : '', '
	<title>', $context['page_title_html_safe'], '</title>';

	// Please don't index these Mr Robot.
	if (!empty($context['robot_no_index']))
		echo '
	<meta name="robots" content="noindex" />';

	// Present a canonical url for search engines to prevent duplicate content in their indices.
	if (!empty($context['canonical_url']))
		echo '
	<link rel="canonical" href="', $context['canonical_url'], '" />';

	// Show all the relative links, such as help, search, contents, and the like.
	echo '
	<link rel="help" href="', $scripturl, '?action=help" />
	<link rel="search" href="', $scripturl, '?action=search" />
	<link rel="contents" href="', $scripturl, '" />';

	// If RSS feeds are enabled, advertise the presence of one.
	if (!empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']))
		echo '
	<link rel="alternate" type="application/rss+xml" title="', $context['forum_name_html_safe'], ' - ', $txt['rss'], '" href="', $scripturl, '?type=rss;action=.xml" />';

	// If we're in a board, or a topic for that matter, the index will be the board's index.
	if (!empty($context['current_board']))
		echo '
	<link rel="index" href="', $scripturl, '?board=', $context['current_board'], '.0" />';

	// Output any remaining HTML headers. (from mods, maybe?)
	echo $context['html_headers'];

	echo '
</head>
<body>';
}

function template_body_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo'
<div id="wrapper">   
	<div id="header">    
		<a id="logo" href="', $scripturl, '"><img src="', $settings['images_url'], '/logo.png" alt="' . $context['forum_name'] . '" /></a>
		<div class="floatright" align="center">
	        <div id="loginContainer">
                <a href="#" id="loginButton"><span class="avatar_lu">', !empty($context['user']['avatar']['image']) ? $context['user']['avatar']['image'] : '' ,' ', $context['user']['name'], '</span></a>
                <div id="loginBox">';
                if ($context['user']['is_guest'])
                echo'				
                    <form id="loginForm"  action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '" ', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
                        <fieldset class="body">
                            <fieldset>
                                <label>'. $txt['username'] .'</label>
                                <input type="text" name="user" />
                            </fieldset>
                            <fieldset>
                                <label>'. $txt['password'] .'</label>
                                <input type="password" name="passwrd" />
                                <input type="hidden" name="hash_passwrd" value="" /><input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
                            </fieldset>
                            <input type="submit" id="login" value="', $txt['login'], '" />
                        </fieldset>
                        <span><a href="', $scripturl, '?action=reminder">', $txt['forgot_your_password'], '</a></span>  
                    </form>';
                if ($context['user']['is_logged'])
                echo'				
                    <form id="loginForm" action="">
                        <fieldset class="body">
                            <fieldset>
                                <ul class="userarea">
								    <li><a href="', $scripturl .'?action=profile">'. $txt['rs_account'] .'</a></li>
								    <li><a href="', $scripturl .'?action=profile;area=forumprofile">'. $txt['rs_profile'] .'</a></li>
									<li><a href="', $scripturl .'?action=unread">'. $txt['rs_post'] .'</a></li>
									<li><a href="', $scripturl .'?action=unreadreplies">'. $txt['rs_replies'] .'</a></li>
								</ul>
                            </fieldset>
                        </fieldset>
                    </form>';
				echo'
                </div>
            </div>
	        <div id="searchContainer">
                <a href="#" id="searchButton"><span>'. $txt['search'] .'</span></a>
                 <div id="searchBox">                
                    <form id="searchForm" action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">
                        <fieldset class="body">
                            <fieldset>
							    <input type="text" name="search" value="" />';
							if (!empty($context['current_topic']))
		                    echo '
					            <input type="hidden" name="topic" value="', $context['current_topic'], '" />';
							elseif (!empty($context['current_board']))
		                    echo '
					            <input type="hidden" name="brd[', $context['current_board'], ']" value="', $context['current_board'], '" />';
							echo'
                            </fieldset>
                        </fieldset>
                    </form>
                </div>
            </div>
		</div>
	</div>';
	
	template_menu();

	// The main content should go here.
	echo '
	<div id="content_section">
		<div id="main_content_section">';

	// Custom banners and shoutboxes should be placed here, before the linktree.

	// Show the navigation tree.
	theme_linktree();
}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
		</div>
	</div>';

	// Show the "Powered by" and "Valid" logos, as well as the copyright. Remember, the copyright must be somewhere!
	echo '
	<div id="footer_top"></div>
	<div id="footer">
	<table width="100%">
	  <tr>
	    <td width="60%">
		<ul class="reset">
			<li class="copyright">', theme_copyright(), '</li>
			<li class="copyright">Theme by <a href="https://smftricks.com">SMF Tricks</a></li>
			<li><a id="button_xhtml" href="http://validator.w3.org/check?uri=referer" target="_blank" class="new_win" title="', $txt['valid_xhtml'], '"><span>', $txt['xhtml'], '</span></a></li>
			', !empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']) ? '<li><a id="button_rss" href="' . $scripturl . '?action=.xml;type=rss" class="new_win"><span>' . $txt['rss'] . '</span></a></li>' : '', '
			<li class="last"><a id="button_wap2" href="', $scripturl , '?wap2" class="new_win"><span>', $txt['wap2'], '</span></a></li>
		</ul>';

	// Show the load time?
	if ($context['show_load_time'])
		echo '
		<p>', $txt['page_created'], $context['load_time'], $txt['seconds_with'], $context['load_queries'], $txt['queries'], '</p>';

	echo '
	    </td>
		<td width="40%">';
		if(!empty($settings['icons_check']))
		{
		echo'
		<ul class="social_icons">
		        <li>&nbsp;</li>';
		    if(!empty($settings['facebook_check']))
		    echo'
		 	    <li class="facebook"><a href="', !empty($settings['facebook_text']) ? $settings['facebook_text'] : 'https://www.facebook.com ' ,'"><img src="', $settings['images_url'], '/social_icons/facebook.png" alt="', $txt['rs_facebook'], '" /></a></li>';
		    if(!empty($settings['twitter_check']))
		    echo'
			    <li class="twitter"><a href="', !empty($settings['twitter_text']) ? $settings['twitter_text'] : 'https://www.twitter.com' ,'"><img src="', $settings['images_url'], '/social_icons/twitter.png" alt="', $txt['rs_twitter'], '" /></a></li>';
		    if(!empty($settings['youtube_check']))
		    echo'
			    <li class="youtube"><a href="', !empty($settings['youtube_text']) ? $settings['youtube_text'] : 'https://www.youtube.com' ,'"><img src="', $settings['images_url'], '/social_icons/youtube.png" alt="', $txt['rs_youtube'], '" /></a></li>';
		    if(!empty($settings['rss_check']))
		    echo'
			    <li class="rss"><a href="', !empty($settings['rss_text']) ? $settings['rss_text'] : '?action=.xml;type=rss' ,'"><img src="', $settings['images_url'], '/social_icons/rss.png" alt="', $txt['rs_rss'], '" /></a></li>';
		echo'
		</ul>';		
		}
		echo'
		</td>
	  </tr>
	</table>
	</div>
</div>';
}

function template_html_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
</body></html>';
}

// Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree($force_show = false)
{
	global $context, $settings, $options, $shown_linktree, $scripturl;

	// If linktree is empty, just return - also allow an override.
	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;

	echo '
	<div class="module"><div class="breadCrumb module">
		<ul>';
		
	echo'
			<li class="iconhome"><a href="'. $scripturl .'" /></li>';

	// Each tree item has a URL and name. Some may have extra_before and extra_after.
	foreach ($context['linktree'] as $link_num => $tree)
	{
		echo '
			<li', ($link_num == count($context['linktree']) - 1) ? ' class="last"' : '', '>';

		// Show something before the link?
		if (isset($tree['extra_before']))
			echo $tree['extra_before'];

		// Show the link, including a URL if it should have one.
		echo $settings['linktree_link'] && isset($tree['url']) ? '
				<a href="' . $tree['url'] . '"><span>' . $tree['name'] . '</span></a>' : '<span>' . $tree['name'] . '</span>';

		// Show something after the link...?
		if (isset($tree['extra_after']))
			echo $tree['extra_after'];

		echo '
			</li>';
	}
	echo '
		</ul>
	</div></div>';

	$shown_linktree = true;
}

// Show the menu up top. Something like [home] [help] [profile] [logout]...
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
    <div id="nav">
			<ul>';

	foreach ($context['menu_buttons'] as $act => $button)
	{
		if (empty($button['sub_buttons']))
		 {
		echo '
				<li id="button_', $act, '">
					<a class="', $button['active_button'] ? 'active ' : '', 'firstlevel" href="', $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '>
						', $button['title'], '
					</a>
				</li>';
		 }
	}

	echo '
			</ul>
		</div>';
		
	if($context['user']['is_logged'])
	{
	echo'	
			<ul id="dropdown_nav">';

	foreach ($context['menu_buttons'] as $act => $button)
	{
		if (!empty($button['sub_buttons']))
		{
		echo '
				<li id="button_', $act, '">
					<a class="', $button['active_button'] ? 'active ' : '', 'firstlevel" href="', $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '>
						', $button['title'], '
					</a>
				</li>';
		}
    }
    echo '
			</ul>';
	}
}

// Generate a strip of buttons.
function template_button_strip($button_strip, $direction = 'top', $strip_options = array())
{
	global $settings, $context, $txt, $scripturl;

	if (!is_array($strip_options))
		$strip_options = array();

	// List the buttons in reverse order for RTL languages.
	if ($context['right_to_left'])
		$button_strip = array_reverse($button_strip, true);

	// Create the buttons...
	$buttons = array();
	foreach ($button_strip as $key => $value)
	{
		if (!isset($value['test']) || !empty($context[$value['test']]))
			$buttons[] = '
				<li><a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' class="button_strip_' . $key . (isset($value['active']) ? ' active' : '') . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><span>' . $txt[$value['text']] . '</span></a></li>';
	}

	// No buttons? No button strip either.
	if (empty($buttons))
		return;

	// Make the last one, as easy as possible.
	$buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);

	echo '
		<div class="buttonlist', !empty($direction) ? ' float' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
			<ul>',
				implode('', $buttons), '
			</ul>
		</div>';
}

?>