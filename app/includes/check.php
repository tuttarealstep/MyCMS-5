<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

//Controllo versione php
/*====================================================*\
| Function created with
| MyCMS: 0.0.1.0
|
| This function checks the version of your php.
| 
| Updated 24/12/2014 - MyCMS 0.0.4.5
| 
| Requires variables:
| '$my_cms_version' and '$my_cms_version'
| You can find them in the 'version.php' file.
\*====================================================*/
function my_check_php_version() {

    global $my_php_version, $my_cms_version;
    $php_version = phpversion();//Versione php

    if(version_compare($my_php_version, $php_version, '>')):
        MY_Error::add('my_001', sprintf('Your server is running PHP version %1$s but MyCMS %2$s requires version at least %3$s.', $php_version, $my_cms_version, $my_php_version));
    endif;

}

//Controllo versione mysql
/*====================================================*\
| Function created with
| MyCMS: 0.0.1.0
|
| This function checks if mysql extension and pdo 
| is loaded.
| 
| Updated 24/12/2014 - MyCMS 0.0.4.5
\*====================================================*/
function my_check_mysql() {

    if (!extension_loaded('mysql') && !extension_loaded('pdo')):
        MY_Error::add('my_002', 'Missing the MySQL extension which serves to MyCMS');
    endif;

}

//Controllo modalità debug
/*====================================================*\
| Function created with
| MyCMS: 0.0.1.0
|
| This function checks if you have enabled the 
| DEBUG MODE and set file for save error.
| 
| Updated 24/12/2014 - MyCMS 0.0.4.5
|
| You can enable DEBUG MODE in:
| '../app/config/my_config.php' and changing
| 'MY_M_DEBUG' and 'MY_M_DEBUG_LOG' to true.
\*====================================================*/
function my_check_debug() {

    if (MY_M_DEBUG):
        error_reporting( E_ALL );

        if (MY_M_DEBUG_LOG):
            ini_set('log_errors', 'On');
            ini_set('error_log', MY_CONTENT.'my_debug_error.log' );
        endif;
    endif;

}

/*====================================================*\
| Security function created with
| MyCMS: 0.0.3.5
|
| This function check the session:
| ['security']['session_key']
|
| Updated 23/10/2014 - MyCMS 0.0.3.0
|
| You can change the SESSION_KEY in:
| '../app/config/my_config.php' and edit 'SESSION_KEY'
\*====================================================*/
function check_session_key() {

    if(SESSION_KEY_GENERATE == false){
        if($_SESSION['security']['session_key'] == SESSION_KEY){

        } else {

           My_error::error_die('000', 'You no have access');

        }
    }

}

/*====================================================*\
| Security function created with
| MyCMS: 0.0.4.5
\*====================================================*/
function check_php_5_6() {

    if(version_compare(phpversion(), '5.6.0', '>')):
        return true;
    else:
        return false;
    endif;

}