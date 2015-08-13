<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */
$my_admin_folder_name = "{-@my-admin@-}";

global $my_router;

$my_router->map( 'GET', '/my-admin/', $my_admin_folder_name.'index');
$my_router->map( 'GET', '/my-admin/index', $my_admin_folder_name.'index');
$my_router->map( 'GET', '/my-admin/login', $my_admin_folder_name.'login');
$my_router->map( 'POST', '/my-admin/login', $my_admin_folder_name.'login');

$my_router->map( 'GET', '/my-admin/logout', $my_admin_folder_name.'logout');
$my_router->map( 'POST', '/my-admin/logout', $my_admin_folder_name.'logout');

$my_router->map( 'GET', '/my-admin/ranks', $my_admin_folder_name.'ranks');
$my_router->map( 'POST', '/my-admin/ranks', $my_admin_folder_name.'ranks');
$my_router->map( 'POST', '/my-admin/menu', $my_admin_folder_name.'menu');
$my_router->map( 'GET', '/my-admin/delete-menu/[*:id]', $my_admin_folder_name.'menu_delete');
$my_router->map( 'POST', '/my-admin/delete-menu/[*:id]', $my_admin_folder_name.'menu_delete');
$my_router->map( 'POST', '/my-admin/category', $my_admin_folder_name.'category');
$my_router->map( 'POST', '/my-admin/comments', $my_admin_folder_name.'comments');
$my_router->map( 'POST', '/my-admin/posts', $my_admin_folder_name.'posts');
$my_router->map( 'POST', '/my-admin/posts_new', $my_admin_folder_name.'posts_new');
$my_router->map( 'GET', '/my-admin/users_bans', $my_admin_folder_name.'users_bans');
$my_router->map( 'POST', '/my-admin/users_bans', $my_admin_folder_name.'users_bans');

$my_router->map( 'GET', '/my-admin/my_page', $my_admin_folder_name.'my_page');
$my_router->map( 'POST', '/my-admin/my_page', $my_admin_folder_name.'my_page');

$my_router->map( 'GET', '/my-admin/my_page_new', $my_admin_folder_name.'my_page_new');
$my_router->map( 'POST', '/my-admin/my_page_new', $my_admin_folder_name.'my_page_new');

$my_router->map( 'GET', '/my-admin/page_edit/[i:id]', $my_admin_folder_name.'my_page_edit');
$my_router->map( 'POST', '/my-admin/page_edit/[i:id]', $my_admin_folder_name.'my_page_edit');

$my_router->map( 'GET', '/my-admin/posts_edit/[i:id]', $my_admin_folder_name.'posts_edit');
$my_router->map( 'POST', '/my-admin/posts_edit/[i:id]', $my_admin_folder_name.'posts_edit');

$my_router->map( 'GET', '/my-admin/theme_manager', $my_admin_folder_name.'theme_manager');
$my_router->map( 'POST', '/my-admin/theme_manager', $my_admin_folder_name.'theme_manager');

$my_router->map( 'GET', '/my-admin/theme_manager/remove/[i:remove]', $my_admin_folder_name.'theme_manager');
$my_router->map( 'POST', '/my-admin/theme_manager/remove/[i:remove]', $my_admin_folder_name.'theme_manager');


$my_router->map( 'GET', '/my-admin/theme_manager/info/[i:info]', $my_admin_folder_name.'theme_manager');
$my_router->map( 'POST', '/my-admin/theme_manager/info/[i:info]', $my_admin_folder_name.'theme_manager');

$my_router->map( 'GET', '/my-admin/update', $my_admin_folder_name.'update');
$my_router->map( 'POST', '/my-admin/update', $my_admin_folder_name.'update');

$my_router->map( 'GET', '/my-admin/[*:page]', $my_admin_folder_name.'page');


if(isAdmin()){
    global $my_theme;
    if(!isset($_SESSION['my-admin']['notification']['dashboard'])){
       if($my_theme->there_is_new_update()){
           $_SESSION['my-admin']['notification']['dashboard'] = $my_theme->there_is_new_update(false);
       } else {
           unset($_SESSION['my-admin']['notification']['dashboard']);
       }
    }
}
