<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

define('FILE', dirname( __FILE__ ));
define('MY_INSTALL', '/install/');

if(file_exists(FILE.MY_INSTALL.'index.php')):
    header("location: ../install/index.php");
    exit;
else:
    require_once( dirname( __FILE__ ) . '/loader.php' );
endif;
