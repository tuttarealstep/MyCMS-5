<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

require_once('info.php');

//CONTROLLO VERSIONE CMS

if(version_compare($GLOBALS['my_cms_version'], $template['cms_version'], '<' )) {

	$message =  $template['name'].' template requires at least MyCMS '.$template['cms_version'].', Please upgrade!';
	My_Error::error_die('template_admin_001', $message);

}
global $my_router, $my_theme;
//STYLE

add_style_script_admin('css', '{@siteURL@}/my-admin/css/bootstrap.min.css');
add_style_script_admin('css', '{@siteURL@}/my-admin/css/plugins/metisMenu/metisMenu.min.css');
add_style_script_admin('css', '{@siteURL@}/my-admin/css/sb-admin-2.css');
add_style_script_admin('css', '{@siteURL@}/my-admin/font-awesome-4.1.0/css/font-awesome.min.css');


add_style_script_admin('script', '{@siteURL@}/my-admin/js/jquery-1.11.0.js');
add_style_script_admin('script', '{@siteURL@}/my-admin/js/bootstrap.min.js');
add_style_script_admin('script', '{@siteURL@}/my-admin/js/plugins/metisMenu/metisMenu.min.js');
add_style_script_admin('script', '{@siteURL@}/my-admin/js/plugins/dataTables/jquery.dataTables.js');
add_style_script_admin('script', '{@siteURL@}/my-admin/js/plugins/dataTables/dataTables.bootstrap.js');
add_style_script_admin('script', '{@siteURL@}/my-admin/js/sb-admin-2.js');

?>