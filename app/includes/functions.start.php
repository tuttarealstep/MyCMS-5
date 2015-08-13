<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

function my_loader_set(){

    if(!defined('MY_TIMEZONE')){
        $site_timezone = get_settings_value('site_timezone');
        if(!empty($site_timezone)){
            define('MY_TIMEZONE', $site_timezone);
        }else{
            define('MY_TIMEZONE', 'UTC');
        }
    }
    date_default_timezone_set(MY_TIMEZONE);

    if(!defined('HOST')){
        $site_url = get_settings_value('site_url');
        if(!empty($site_url)){
            define('HOST', $site_url);
        }else{
            define('HOST', 'http://'.$_SERVER['SERVER_NAME']);
        }
    }

    if(!defined('MY_THEME')) {
        define('MY_THEME', get_settings_value('site_template'));
    }

    if(!defined("MY_BASE_PATH")) {
        define("MY_BASE_PATH", "");
    }

    if(!defined('MY_M_DEBUG')){
        define('MY_M_DEBUG', false);
    }

    if(!defined('MY_MEMORY_LIMIT')) {
        define('MY_MEMORY_LIMIT', '64M');
    }
    @ini_set('memory_limit', MY_MEMORY_LIMIT);


}
function my_set_session_key(){
    if(SESSION_KEY_GENERATE == true){
        $_SESSION['security']['session_key'] = SESSION_KEY;
    }
}
function is_in_console(){
    if(php_sapi_name() == 'cli'){
        return true;
    }
    return false;
}