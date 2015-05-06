<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

/**
 * Class MY_Api
 */
class MY_Api
{

    public function __construct ()
    {
    }


    function is_api($url = NULL, $return_array = false){

        if(empty($url)){
            $url = fix_text(htmlspecialchars(substr("$_SERVER[REQUEST_URI]", 1)));
        } else {
            $url = fix_text(htmlspecialchars($url));
        }

        if($return_array){
            if(preg_match("/_api_\//i", $url)) {
                return array("return" => "true", "url" => $url);
            }
            return array("return" => "false", "url" => $url);
        } else {
            if(preg_match("/_api_\//i", $url)) {
                return true;
            }
            return false;
        }

        return false;
    }

    function show_api($return = false){

        $is_api = $this->is_api(null, true);

        if ($is_api["return"] == "true"){
            $show_api = $this->type_api($is_api["url"]);

            if(empty($show_api)){
                $show_api = $this->api_error();
            } elseif($show_api == "null"){
                $show_api = $this->api_error();
            }

            header('Content-Type: application/json');
            if($return) {
                return json_encode($show_api, JSON_PRETTY_PRINT);
            } else {
                die(json_encode($show_api, JSON_PRETTY_PRINT));

            }
        } else {

        }
    }

    function type_api($url){

        global $my_db;
        $preg = "_api_\/";
        $banned_settings_id = array("6", "8", "9", "10", "11", "12");

        if(preg_match("/_api_\/users\//i", $url)) {


        } elseif(preg_match("/_api_\/web_site\//i", $url)) {

            preg_match("/_api_\/web_site\/(.*)/i", $url, $match);

            if(is_numeric($match["1"])){
                $count = $my_db->single("SELECT COUNT(settings_name) FROM my_cms_settings WHERE settings_id = :settings_id", array("settings_id" => my_sql_secure($match['1'])));
            } else {
                $count = $my_db->single("SELECT COUNT(settings_name) FROM my_cms_settings WHERE settings_name = :settings_name", array("settings_name" => my_sql_secure($match['1'])));
            }

             if($count > 0){
                 if(is_numeric($match["1"])){
                     $settings = $my_db->query("SELECT * FROM my_cms_settings WHERE settings_id = :settings_id", array("settings_id" => my_sql_secure($match['1'])));
                 } else {
                     $settings = $my_db->query("SELECT * FROM my_cms_settings WHERE settings_name = :settings_name", array("settings_name" => my_sql_secure($match['1'])));
                 }
               foreach($settings as $type){
                    if(!in_array($type["settings_id"], $banned_settings_id)){
                        if(is_numeric($match["1"])){
                            if(preg_match("/".$preg."web_site\/".$type["settings_id"]."/i", $url)){
                                $info = array("settings_id" => $type["settings_id"], "settings_name" => $type["settings_name"], "settings_value" => $type["settings_value"]);
                            }
                        } else {
                            if(preg_match("/".$preg."web_site\/".$type["settings_name"]."/i", $url)){
                                $info = array("settings_id" => $type["settings_id"], "settings_name" => $type["settings_name"], "settings_value" => $type["settings_value"]);
                            }
                        }
                    } else {
                        return $this->api_error("Private setting. This setting is private for the security of the website or is a useless setting for you.", "PrivateWebSiteSetting", "00111");
                    }
                }
                 if(empty($info)){
                     return $this->api_error();
                 } elseif($info == "null"){
                     return $this->api_error();
                 }
                return $info;
            } else {
                    return $this->api_error("Requested setting not found. ", "NoWebSiteSettingFound", "00110");
            }
        } elseif(preg_match("/_api_\/blog\//i", $url)) {

        } else {
            return $this->api_error();
        }

    }

    function api_error($message = "Request type not found.", $type = "NoApiTypeFound", $code = "0010"){
        return array("error" => array("message" => $message, "type" => $type, "code" => $code));
    }


}