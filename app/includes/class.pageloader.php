<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

//Pages Creator v1 Relase 0.0.5.0
function load_database_page($get_url){

    $no_footer_tag = "{@###-no-footer-###@}";

    if(isset($get_url)){
        $url = $get_url;
        if($get_url == "404"){
            $url = fix_text(htmlspecialchars(substr("$_SERVER[REQUEST_URI]", 1)));
        }
    } else {
        $url = fix_text(htmlspecialchars(substr("$_SERVER[REQUEST_URI]", 1)));
    }
    global $my_db, $my_theme;
    if(empty($url))
    return false;

    if(!$my_db->iftrue("SELECT * FROM my_page WHERE pageURL = :page_url OR pageURL = :page_url_two AND pageINTHEME = '0' LIMIT 1", array("page_url" => $url,"page_url_two" => "{@siteURL@}/".$url))){
        return false;
    }
    $info = $my_db->row("SELECT * FROM my_page WHERE pageURL = :page_url OR pageURL = :page_url_two AND pageINTHEME = '0' LIMIT 1", array("page_url" => $url,"page_url_two" => "{@siteURL@}/".$url));
    if(!isset($info)){
        return false;
    }

    //MAKE PAGE
    /*                     *\
    |	MYCMS - TProgram    |
    \*                     */
    define('PAGE_ID', $info["pageID_MENU"]);
    define('PAGE_NAME', $info["pageTITLE"]);


    echo set_TAG($my_theme->get_file('header', "", true));

    if(preg_match("/".$no_footer_tag."/s", $info["pageHTML"])) {
        $no_footer = true;
    }

        $info["pageHTML"] = str_ireplace($no_footer_tag, "", $info["pageHTML"]);


    echo set_TAG($info["pageHTML"]);

    if($no_footer == true) {
        get_style_script('script');
    } else {
        echo set_TAG($my_theme->get_file('footer', "", true));
    }


    return true;

}

function page_loader_match_database_page(){

    global $my_db, $my_router;

    $info = $my_db->query("SELECT pageURL FROM my_page WHERE pageINTHEME = '0'");

    foreach($info as $tag_info){

        $url_info = str_replace("{@siteURL@}", "", $tag_info);
        $name_info = str_replace("{@siteURL@}/", "", $tag_info);
        $my_router->map( 'GET', $url_info, $name_info);
    }
}



