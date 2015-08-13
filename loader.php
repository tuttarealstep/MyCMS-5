<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */
/**
 *  @author Stefano V. - Tuttarealstep
 *  @package Index/Loader
 */

//Define directory
define( 'P_PATH' , dirname( __FILE__ ) . '/' );
define( 'P_PATH_S' , dirname( __FILE__ ) );
define( 'A_PATH' , P_PATH_S . '/app' );
define( 'CONFIG_PATH' , A_PATH . '/configuration' );
define( 'I_PATH' , A_PATH . '/includes' );
define( 'C_PATH' , A_PATH . '/content' );
define( 'MY_ADMIN_PATH' , P_PATH_S . '/my-admin' );

//Load configuration file
if ( file_exists( CONFIG_PATH . '/my_config.php') ) {
    require_once( CONFIG_PATH . '/my_config.php' );
}

session_start();

if(MY_M_DEBUG === false) {
    error_reporting(E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING);
} else {
    error_reporting(E_ALL);
}

define( 'MY_CMS_WEBSITE' , 'http://tprogram.altervista.org/mycms' );

global $my_cms_version, $my_php_version, $my_mysql_version, $my_cms_db_version;
require_once( I_PATH . '/version.php' );
require_once( I_PATH . '/class.error.php' );
require_once( I_PATH . '/check.php' );
require_once( I_PATH . '/class.db.php' );
$GLOBALS['my_db'] = new MY_Db();
require_once( I_PATH . '/class.settings.php' );
require_once( I_PATH . '/functions.start.php' );

my_check_php_version();
my_check_mysql();

my_loader_set(); //Set all define

check_session_key(); //Control security session key
my_set_session_key(); //Set security session

require_once( I_PATH . '/AltoRouter.php' );
require_once( I_PATH . '/class.theme.php' );
$GLOBALS['my_router'] = new AltoRouter();
$GLOBALS['my_theme'] = new MY_theme();

require_once( I_PATH . '/class.security.php' );
require_once( I_PATH . '/class.functions.php' );
require_once( I_PATH . '/class.language.php' );
require_once( I_PATH . '/function.set_tags.php' );
require_once( I_PATH . '/class.blog.php' );
$GLOBALS['my_blog'] = new MY_Blog();
set_tags();
my_control_https();

require_once( I_PATH . '/class.users.php' );
$GLOBALS['my_users'] = new MY_Users();

$my_users->control_ban();
$my_users->control_session();
$my_users->control_session_admin();
$my_users->set_user_tag();

$my_theme->load_theme_functions();
$my_theme->load_admin_functions();
require_once( I_PATH . '/class.form.php' );

require_once( I_PATH . '/function.set_theme_tags.php' );
set_theme_tags();

require_once( I_PATH . '/class.admin.php' );
require_once( I_PATH . '/class.pageloader.php' );

page_loader_match_database_page();

require_once( I_PATH . '/class.api.php' );
$GLOBALS['my_api'] = new MY_Api();

require_once( I_PATH . '/class.plugins.php' );
$GLOBALS['my_plugins'] = new MY_Plugins();

if($my_api->is_api()){
    $my_api->show_api();
}

$my_router->setBasePath(MY_BASE_PATH);
$match = $my_router->match();
//print_r($match);
add_tag('my_cms_welcome_h1', e('my_cms_welcome_h1', 1));


if(is_in_console()){
    define(LOADER_LOAD_PAGE, false);
    $my_theme->start_console_mode();
}


if(LOADER_LOAD_PAGE){

    if(empty($match['target'])){
        $style_info = $my_theme->style_info(MY_THEME);
        $match['target'] = $style_info["style_error_file"];
    }

    if($my_theme->is_admin_url($match['target']) == false){
        $my_theme->control_maintenance($match['target']);
        $info = load_database_page($match['target']);
        if($info == false){
            $my_theme->load_theme($match['target'], $match['params']);
        }
    } else {
        $my_theme->admin_load_theme($match['target'], $match['params']);
    }

}

