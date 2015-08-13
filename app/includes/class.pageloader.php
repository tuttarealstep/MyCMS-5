<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

//Pages Creator v1 Relase 0.0.5.0
function load_database_page($get_url){

    $timer_start = microtime(true);

    global $my_theme;
    $no_footer_tag = "{@###-no-footer-###@}";

    if(isset($get_url)){
        $url = $get_url;
        $style_info = $my_theme->style_info(MY_THEME);
        if($get_url == $style_info["style_error_file"]){
            $url = fix_text(htmlspecialchars(substr("$_SERVER[REQUEST_URI]", 1)));
        }
    } else {
        $url = fix_text(htmlspecialchars(substr("$_SERVER[REQUEST_URI]", 1)));
    }

    $base_path = str_replace('/', '', MY_BASE_PATH);
    $url = str_replace($base_path, '', $url);
    $url = str_replace('/', '', $url);

    global $my_db;
    if(empty($url))
    return false;

    if(!$my_db->iftrue("SELECT pageID FROM my_page WHERE pageURL = :page_url OR pageURL = :page_url_two AND pageINTHEME = '0' LIMIT 1", array("page_url" => $url,"page_url_two" => "{@siteURL@}/".$url))){
        return false;
    }
    $info = $my_db->row("SELECT * FROM my_page WHERE pageURL = :page_url OR pageURL = :page_url_two AND pageINTHEME = '0' LIMIT 1", array("page_url" => $url,"page_url_two" => "{@siteURL@}/".$url));
    if(!isset($info)){
        return false;
    }

    $page = '';

    //MAKE PAGE
    /*                     *\
    |	MYCMS - TProgram    |
    \*                     */
    define('PAGE_ID', $info["pageID_MENU"]);
    define('PAGE_NAME', $info["pageTITLE"]);

    $page .= set_TAG($my_theme->get_file('header', "", true))."\n";
    $page .= set_TAG($my_theme->get_file('page_loader_top', "", true))."\n";

    if(preg_match("/".$no_footer_tag."/s", $info["pageHTML"])) {
        $no_footer = true;
    }

        $info["pageHTML"] = str_ireplace($no_footer_tag, "", $info["pageHTML"]);


    $page .= set_TAG($info["pageHTML"])."\n";


    $page .= set_TAG($my_theme->get_file('page_loader_bottom', "", true))."\n";

    if($no_footer == true) {
        $page .= get_style_script('script');
    } else {
        $page .= set_TAG($my_theme->get_file('footer', "", true))."\n";
    }

    $finished = number_format(microtime(true) - $timer_start, 3);
    $page .= "\n<!-- MyCMS Page Loader - Page loaded in " . $finished . " sec. -->";

    if($my_theme->small_page == true){
        $page = $my_theme->remove_space($page);
    }
    echo $page;
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



