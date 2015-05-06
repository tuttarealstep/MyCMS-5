<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

if(staff_logged_in()){
	header("location: " . HOST . "/my-admin/home");
	exit;
} else {
	header("location: " . HOST . "/my-admin/login");
	exit;
}
?>