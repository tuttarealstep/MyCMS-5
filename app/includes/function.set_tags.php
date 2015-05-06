<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

function set_tags(){
	global $my_cms_version, $my_php_version, $my_mysql_version, $my_theme;
	add_tag('siteNAME', get_settings_value('site_name')); //use {@siteNAME@} in your page
	add_tag('my_cms_version', $my_cms_version);
	add_tag('my_php_version', $my_php_version);
	add_tag('my_mysql_version', $my_mysql_version);
	add_tag('siteURL', get_settings_value('site_url'));
	add_tag('siteTEMPLATE', fix_theme(get_settings_value('site_template')));
	add_tag('siteTIMEZONE', get_settings_value('site_timezone'));
	add_tag('siteLANGUAGE', get_settings_value('site_language'));
	add_tag('siteDESCRIPTION', get_settings_value('site_description'));
	add_tag('templateNAME', $my_theme->get_style_info('name'));
	add_tag('templateVERSION', $my_theme->get_style_info('version'));
	add_tag('templateAUTHOR', $my_theme->get_style_info('author'));
	add_tag('templateCMS_VERSION', $my_theme->get_style_info('cms_version'));
}