<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */
/**
 *  @package Index
 */

define('FILE', dirname( __FILE__ ));
define('MY_INSTALL', '/install/');

if(file_exists(FILE.MY_INSTALL.'index.php')):
    header("location: ../install/index.php");
    exit;
else:
    //This file only loads loader.php
    require_once( dirname( __FILE__ ) . '/loader.php' );
endif;