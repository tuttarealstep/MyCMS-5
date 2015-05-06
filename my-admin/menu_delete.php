<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

hide_if_staff_not_logged();

if(staff_logged_in()){
	global $my_db;
	if(isset($_GET['id'])){
		
		if(is_numeric($_GET['id'])){
				$main_sql = $my_db->single("SELECT COUNT(*) FROM my_menu WHERE menu_id = '".$_GET['id']."' AND menu_can_delete = '1' LIMIT 1");
				if($main_sql > 0) {
					$my_db->query("DELETE FROM my_menu WHERE menu_id = '".$_GET['id']."' ");
					header('Location: '.HOST.'/my-admin/menu');
					exit();
				}
		} else {
			header('Location: '.HOST.'/my-admin/menu');
			exit();
		}
		
	} else {
		
	header('Location: '.HOST.'/my-admin/menu');
	exit();
	
	}
}
?>