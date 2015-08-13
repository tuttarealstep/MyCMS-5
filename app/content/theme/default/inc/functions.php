<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

require_once('info.php');

//CONTROLLO VERSIONE CMS

if(version_compare($GLOBALS['my_cms_version'], $template['cms_version'], '<' )) {

    $message =  $template['name'].' template requires at least MyCMS '.$template['cms_version'].', Please upgrade!';
    My_Error::error_die('template_001', $message);

}
global $my_router, $my_theme;

//NECESSARY PAGE DON'T REMOVE
//PAGE
$style_info = $my_theme->style_info(MY_THEME);
$my_router->map( 'GET', '/', 'index');
$my_router->map( 'GET', '/index', 'index');
$my_router->map( 'GET', '/404', '404');
$my_router->map( 'GET', '/maintenance', 'maintenance');
//BLOG RULE
$my_router->map( 'GET', '/blog', 'blog');
$my_router->map( 'GET', '/blog/[i:year]/[i:month]/[*:title]', 'blog');
$my_router->map( 'GET', '/blog/id/[i:id]', 'blog');
$my_router->map( 'GET', '/blog/category/[a:category]', 'blog');
$my_router->map( 'GET', '/blog/author/[:author]', 'blog');
$my_router->map( 'GET', '/blog/search/[:search]', 'blog');

//LOGIN REGISTRATION
$my_router->map( 'GET', '/logout', 'logout');
$my_router->map( 'GET', '/logout/r/[:return_url]', 'logout');
$my_router->map( 'GET', '/login/r/[:return_url]', 'login');
$my_router->map( 'GET', '/registration/r/[:return_url]', 'registration');
$my_router->map( 'GET', '/login', 'login');
$my_router->map( 'POST', '/login', 'login');
$my_router->map( 'GET', '/registration', 'registration');
$my_router->map( 'POST', '/registration', 'registration');


add_style_script('css', '{@siteURL@}/app/content/theme/{@siteTEMPLATE@}/css/style.css?fixx');
add_style_script('css', '{@siteURL@}/app/content/theme/{@siteTEMPLATE@}/css/normalize.css');
add_style_script('css', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css');
add_style_script('script', 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js');
add_style_script('script', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js');

$my_theme->index_error_style("<br><div class='container'><div class='alert alert-danger'>", "</div></div><br>");

?>