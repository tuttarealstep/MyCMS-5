<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

function get_settings_id($name){
	
	global $my_db;
	$filter_name = filter_var($name, FILTER_SANITIZE_STRING);  
	$my_db->bind("settings_name", $filter_name);
	$information = $my_db->single("SELECT id FROM my_cms_settings WHERE settings_name = :settings_name LIMIT 1");
	return $information;

}
function get_settings_name($id){
	
	global $my_db;
	$filter_id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);  
	if (filter_var($filter_id, FILTER_VALIDATE_INT)) {  
		$my_db->bind("settings_id", $filter_id);
		$information = $my_db->single("SELECT settings_name FROM my_cms_settings WHERE settings_id = :settings_id LIMIT 1");
		return $information;
	} else {
		die();
	}

}
function get_settings_value($setting_name = ""){
	
	global $my_db;
	$setting_name = filter_var($setting_name, FILTER_SANITIZE_STRING);  
	$my_db->bind("filter_setting_name", $setting_name);
	$information = $my_db->single("SELECT settings_value FROM my_cms_settings WHERE settings_name = :filter_setting_name LIMIT 1");
	if(empty($information)){
	} else {
		return $information;
	}
}
function add_settings_value($settings_name = "", $settings_value = ""){

	global $my_db;
	$setting_name = filter_var($settings_name, FILTER_SANITIZE_STRING);
	$settings_value = filter_var($settings_value, FILTER_SANITIZE_STRING);  
	$my_db->bind("filter_settings_name", $settings_name);
	$controllo = $my_db->single("SELECT COUNT(*) FROM my_cms_settings WHERE settings_name = :filter_settings_name LIMIT 1");
	if($controllo < 0){
		$insert = $my_db->query("INSERT INTO my_cms_settings(settings_name,settings_value) VALUES(:setting_name_new,:setting_value_new)", array("setting_name_new"=>$settings_name,"setting_value_new"=>$settings_value));
	}

}
function save_settings($settings_name = "", $settings_value = ""){
	
	global $my_db;
	$settings_name = filter_var($settings_name, FILTER_SANITIZE_STRING);
	$settings_value = filter_var($settings_value, FILTER_SANITIZE_STRING); 
	$controllo = $my_db->single("SELECT COUNT(*) FROM my_cms_settings WHERE settings_name = :filter_settings_name", array("filter_settings_name" => $settings_name));
	if($controllo > 0){
		$insert = $my_db->query("UPDATE my_cms_settings SET settings_value = :setting_value_new WHERE settings_name = :setting_name_new", array("setting_value_new" => $settings_value, "setting_name_new" => $settings_name));
	}
	
	if($insert > 0 ) {
  		return true;
	} else {
		return false;
	}

}