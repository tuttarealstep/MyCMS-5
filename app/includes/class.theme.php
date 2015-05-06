<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

class MY_theme {

    public $extension_array = array(".html", ".php");
    public $tag = array();
    public $functions_tag = array();
    public $css = array();
    public $script = array();
    public $meta_tag = array();
    public $index_error_style_array = array();

    public $script_admin_panel = array();
    public $css_admin_panel = array();

    function __construct() {

    }

    function get_theme_path(){

        if(file_exists (C_PATH."/theme/".MY_THEME)) {
            $complete_path =  $complete_path = C_PATH."/theme/".MY_THEME;
        } else {
            $complete_path =  $complete_path = C_PATH."/theme/"."default";
        }

        return $complete_path;
    }

    function load_theme_functions(){

        $path_file = self::get_theme_path()."/inc/functions.php";

        if(!file_exists($path_file))
            return false;

        require_once($path_file);
    }

    function load_admin_functions(){

        $path_file = MY_ADMIN_PATH."/inc/functions.php";

        if(!file_exists($path_file))
            return false;

        require_once($path_file);
    }

    function load_theme($file, $param){

            $theme_path = self::get_theme_path();
            if(!file_exists ($theme_path)) {
                MY_Error::error_die("00STYLE", "Template not found!");
            }


            foreach ($this->extension_array as $file_ext) {
                if (file_exists($theme_path . "/" . $file . $file_ext)) {
                    $file_found = true;
                    $file_found_ext = $file_ext;
                    break;
                }
            }
            if ($file_found) {
                ob_start();
                if (!empty($param)) {
                    foreach ($param as $key => $value) {
                        $_GET[$key] = my_sql_secure($value);
                    }
                }
                include($theme_path . "/" . $file . $file_found_ext);
                $page_loaded = ob_get_contents();
                ob_end_clean();
                self::set_page($page_loaded, $file, false);
            } else {
                if ($file = "404") {
                    My_Error::error_die("404", "FILE NOT FOUND");
                }
                header('Location: ' . HOST . '/404');
                return false;
            }
    }

    function set_page($page, $url, $admin){

        $page = self::set_TAG($page);
        $page = self::set_TAG_FUNCTIONS($page);

        if($admin == false){
        }
        echo $page;
    }

    public function index_error_style($start_tag, $finish_tag){

        $array = array("start_tag" => $start_tag, "finish_tag" => $finish_tag);
        $this->index_error_style_array = array_merge($this->index_error_style_array, $array);

    }

    public function set_TAG($page){

        if(defined('INDEX_ERROR')){
            $errors = array('INDEX_ERROR' => $this->index_error_style_array["start_tag"].INDEX_ERROR.$this->index_error_style_array["finish_tag"]);
        } else {
            $errors = array('INDEX_ERROR' => '');
        }

        foreach($errors as $error => $value)
        {
            $page = str_ireplace('{@'.$error.'@}', $value, $page);
        }

        foreach($this->tag as $tag => $value)
        {
            $page = str_ireplace('{@'.$tag.'@}', $value, $page);
        }


        return $page;

    }

    public function set_TAG_FUNCTIONS($page){

        if(empty($this->functions_tag))
            return $page;

        $matches_f_f = array();
        $found = array();

        for($i = 0; $i <= count($this->functions_tag)-1; $i++){
            if(preg_match("/".$this->functions_tag[$i]["start"]."(.*)".$this->functions_tag[$i]["end"]."/s", $page)) {
                preg_match("/".$this->functions_tag[$i]["start"]."(.*)".$this->functions_tag[$i]["end"]."/s",$page, $matches);
                $matches_f_f[] = $matches[1];
                if(!empty($this->functions_tag[$i]["param"])) {
                    $user_func = call_user_func($this->functions_tag[$i]["function"], $this->functions_tag[$i]["param"]);
                } else {
                    $user_func = call_user_func($this->functions_tag[$i]["function"]);
                }
                $found = array_merge($found, array(array("value" => $matches_f_f[$i], "function_return" => $user_func)));
                if ($found[$i]["function_return"] == true) {

                } else {
                        $page = str_ireplace($found[$i]["value"], "", $page);
                }
            }
        }

        //Remove function tag
        for($i = 0; $i <= count($this->functions_tag)-1; $i++){

            $page = str_ireplace($this->functions_tag[$i]["start"], "", $page);
            $page = str_ireplace($this->functions_tag[$i]["end"], "", $page);

        }

        return $page;

    }

    public function add_tag($tag, $value){

        $array_complete = array($tag => $value);
        $this->tag = array_merge($this->tag, $array_complete);

    }

    public function add_functions_tag($start, $end, $function, $param = null){

        $array_complete = array(array("start" => $start, "end" =>  $end, "function" =>$function, "param" =>$param));
        $this->functions_tag = array_merge($this->functions_tag, $array_complete);

    }

    public function add_meta_tag($page_name, $tag){

        $this->meta_tag = [$page_name => $tag];

    }

    public function get_meta_tag($page_name){

        foreach($this->meta_tag as $key => $value)
        {
            if($key == $page_name){
                echo $value;
            }

        }

    }

    public function add_style_script($type, $link){
        switch($type)
        {
            case 'css':

                $css = '<link href="'.$link.'" rel="stylesheet">'."\n";
                $this->css[] = $css;

                break;
            case 'script':

                $script = '<script src="'.$link.'"></script>'."\n";
                $this->script[] = $script;

                break;
        }
    }
    public function get_style_script($type, $return = false){

        switch($type)
        {
            case 'css':
                $final_css = "";
                foreach($this->css as $css)
                {
                    $final_css = $final_css . $css;
                }
                if($return == true){
                    return $final_css;
                } else {
                    echo $final_css;
                }
                break;
            case 'script':
                $final_script = "";
                foreach($this->script as $script)
                {
                    $final_script = $final_script . $script;
                }
                if($return == true){
                    return $final_script;
                } else {
                    echo $final_script;
                }
                break;
        }
    }

    public function add_style_script_admin($type, $link){
        switch($type)
        {
            case 'css':

                $css = '<link href="'.$link.'" rel="stylesheet">'."\n";
                $this->css_admin_panel[] = $css;

                break;
            case 'script':

                $script = '<script src="'.$link.'"></script>'."\n";
                $this->script_admin_panel[] = $script;

                break;
        }
    }
    public function get_style_script_admin($type, $return = false){

        switch($type)
        {
            case 'css':
                $final_css = "";
                foreach($this->css_admin_panel as $css)
                {
                    $final_css = $final_css . $css;
                }
                if($return == true){
                    return $final_css;
                } else {
                    echo $final_css;
                }
                break;
            case 'script':
                $final_script = "";
                foreach($this->script_admin_panel as $script)
                {
                    $final_script = $final_script . $script;
                }
                if($return == true){
                    return $final_script;
                } else {
                    echo $final_script;
                }
                break;
        }
    }


    public function get_style_info($info){

        $path_file = self::get_theme_path().'/inc/info.php';

        @include($path_file);
        if(!empty($info))
        {
            return $template[$info];
        }

    }

    public function get_file($page, $name = NULL, $page_loader = true){

        global $my_plugins;

        $theme_path = self::get_theme_path();
        if(!file_exists ($theme_path)) {
            MY_Error::error_die("00STYLE", "Template not found!");
        }

        if(empty($page))
            return;

        switch($page)
        {
            case 'header':
                if(!empty($name)){
                    $load_file = 'header-'.$name.'.php';
                }
                $load_file = 'header.php';
                if($page_loader == true){
                    ob_start();
                        include($theme_path . '/'.$load_file);
                        $set = ob_get_contents();
                    ob_end_clean();
                    return $set;
                } else {
                    require_once($theme_path . '/'.$load_file);
                    echo "\n\n<!-- START Plugin -->\n\n";
                        $my_plugins->include_plugins("header");
                    echo "\n\n<!-- FINISH Plugin -->\n\n";
                }
                break;
            case 'footer':
                echo "\n\n<!-- START Plugin -->\n\n";
                    $my_plugins->include_plugins("footer");
                echo "\n\n<!-- FINISH Plugin -->\n\n";
                if(!empty($name)){
                    $load_file = 'footer-'.$name.'.php';
                }
                $load_file = 'footer.php';
                if($page_loader == true){
                    ob_start();
                    include($theme_path . '/'.$load_file);
                    $set = ob_get_contents();
                    ob_end_clean();
                    return $set;
                } else {
                    require_once($theme_path . '/'.$load_file);
                }
                break;
        }
    }

    public function get_page($page, $name){

        $theme_path = self::get_theme_path();
        if(!file_exists ($theme_path)) {
            MY_Error::error_die("00STYLE", "Template not found!");
        }

        if(empty($page))
            return;

        $load_file = array();
        if(!empty($name)){
            $load_file[] = $page.'-'.$name.'.php';
        }

        $load_file[] = $page.'.php';
        foreach($load_file as $page_load)
        {
            require_once($theme_path . '/'.$page_load);
        }
    }

    public function get_file_admin($page, $name = NULL, $page_loader = true){

        $theme_path = MY_ADMIN_PATH;

        if(!file_exists ($theme_path)) {
            MY_Error::error_die("00STYLE", "Template not found!");
        }

    if(empty($page))
        return;

    switch($page)
    {
        case 'header':
            if(!empty($name)){
                $load_file = 'header-'.$name.'.php';
            }
            $load_file = 'header.php';
            if($page_loader == true){
                ob_start();
                include($theme_path . '/'.$load_file);
                $set = ob_get_contents();
                ob_end_clean();
                return $set;
            } else {
                require_once($theme_path . '/'.$load_file);
            }
            break;
        case 'footer':
            if(!empty($name)){
                $load_file = 'footer-'.$name.'.php';
            }
            $load_file = 'footer.php';
            if($page_loader == true){
                ob_start();
                include($theme_path . '/'.$load_file);
                $set = ob_get_contents();
                ob_end_clean();
                return $set;
            } else {
                require_once($theme_path . '/'.$load_file);
            }
            break;
    }
}

    public function get_page_admin($page, $name){

        $theme_path =  MY_ADMIN_PATH;
        if(!file_exists ($theme_path)) {
            MY_Error::error_die("00STYLE", "Template not found!");
        }

        if(empty($page))
            return;

        $load_file = array();
        if(!empty($name)){
            $load_file[] = $page.'-'.$name.'.php';
        }

        $load_file[] = $page.'.php';
        foreach($load_file as $page_load)
        {
            require_once($theme_path . '/'.$page_load);
        }
    }

    public function control_maintenance($url){

        $maintenance = get_settings_value('site_maintenance');

        if(staff_logged_in()){

            $maintenance = false;

        } else {

            if($maintenance == 'true'){
                $maintenance = true;
            } else {
                $maintenance = false;
            }
        }

        if($url !== 'maintenance'){
                if($maintenance == true){
                    header('Location: '.HOST.'/maintenance');
                exit;
                }
        }

    }

    public function is_admin_url($url){
        if(preg_match("/{-@my-admin@-}/i", $url)) {
            return true;
        }
        return false;
    }
    public function get_admin_url($url){

        if($this->is_admin_url($url['target'])) {
           preg_match("/{-@my-admin@-}(.*)/i", $url['target'], $url_found);
        }
        if($url_found[1] == "page"){
            return $url['params']['page'];
        } else {
            return $url_found[1];
        }

    }

    function admin_load_theme($file, $param){

        preg_match("/{-@my-admin@-}(.*)/", $file, $file_info);

        $file_name = $file_info[1];

        if($file_name == "page"){
            $file_name = $param['page'];
        }

        $my_admin_path = MY_ADMIN_PATH;

        if(!file_exists ($my_admin_path)) {
            MY_Error::error_die("00STYLE", "Admin panel not found");
        }

        if(file_exists($my_admin_path.'/'.$file_name.'.php')){

            ob_start();
                if (!empty($param)) {
                    foreach ($param as $key => $value) {
                        $_GET[$key] = my_sql_secure($value);
                    }
                }
            include($my_admin_path . "/" . $file_name . '.php');
            $page_loaded = ob_get_contents();
            ob_end_clean();
            self::set_page($page_loaded, $file_name, false);
        } else {
            header('Location: ' . HOST . '/404');
            return false;
        }
    }

    function download_theme($url)
    {
        global $my_cms_version, $my_db;

        $download_url = $url;
        $get_info = @json_decode(file_get_contents($download_url), true);

        $theme_name = @$get_info["theme_name"];
        $theme_author = @$get_info["theme_author"];
        $theme_zip_file_name = @$get_info["theme_zip_file_name"];
        $my_cms_version_cms = @$get_info["my_cms_version"];

        if (empty($theme_name)) {
            return '<div class="alert alert-danger"> Error Download </div>';
        }
        if (empty($theme_author)) {
            return '<div class="alert alert-danger"> Error Download  </div>';
        }
        if (empty($theme_zip_file_name)) {
            return '<div class="alert alert-danger"> Error Download </div>';
        }
        if (empty($my_cms_version_cms)) {
            return '<div class="alert alert-danger"> Error Download  </div>';
        }

        if ($my_cms_version_cms != $my_cms_version) {
            return '<div class="alert alert-danger"> Error Download - <b>Wrong MyCMS Version</b> </div>';
        }

        $zipname = $theme_zip_file_name . '.zip';

        if (file_exists("./app/content/theme/" . $theme_zip_file_name)) {
            return '<div class="alert alert-danger"> Error Download - <b>Another Theme with this name</b> </div>';
        }

        $download_path = str_replace('/info.json', '', $download_url);

        $dir = "./app/content/theme/" . $theme_zip_file_name;
        mkdir($dir, 0777);

        $info = file_get_contents($download_path . "/" . $zipname);
        file_put_contents($dir . '/' . $zipname, $info);

        $zip_extract = new ZipArchive;
        if ($zip_extract->open($dir . '/' . $zipname) === TRUE) {
            $zip_extract->extractTo($dir . '/');
            $zip_extract->close();
        } else {
            return '<div class="alert alert-danger"> Error ZIP - <b>Can\'t Open</b> </div>';
        }

        unlink($dir . '/' . $zipname);

        $my_db->query("INSERT INTO my_style (style_name,style_author,style_path) VALUES (:style_name,:style_author,:style_path)", array('style_name'=>$theme_name,'style_author'=>$theme_author,'style_path'=>$theme_zip_file_name));
    }
}

function set_TAG($page){

    global $my_theme;
    return $my_theme->set_TAG($page);

}
function add_tag($tag, $value){

	global $my_theme;
    $my_theme->add_tag($tag, $value);

}
//No robots
function no_robots(){

    echo "<meta name='robots' content='noindex,follow' />\n";

}

function get_theme_path(){
    global $my_theme;
    $path = $my_theme->get_theme_path   ();
    return $path;
}

function add_meta_tag($page_name, $tag){

    global $my_theme;
    $my_theme->add_meta_tag($page_name, $tag);

}
function get_meta_tag($page_name){

    global $my_theme;
    $my_theme->get_meta_tag($page_name);

}

function add_style_script($type, $link){

    global $my_theme;
    $my_theme->add_style_script($type, $link);

}

function get_style_script($type, $return = false){

    global $my_theme;
    if($return == false){
        $my_theme->get_style_script($type, $return);
    } else {
        return $my_theme->get_style_script($type, $return);
    }


}

function add_style_script_admin($type, $link){

    global $my_theme;
    $my_theme->add_style_script_admin($type, $link);

}

function get_style_script_admin($type, $return = false){

    global $my_theme;
    if($return == false){
        $my_theme->get_style_script_admin($type, $return);
    } else {
        return $my_theme->get_style_script_admin($type, $return);
    }


}

function fix_theme($theme){

    $theme_path = get_theme_path();

    if(file_exists ($theme_path)) {
        return $theme;
    } else {
        return "default";
    }

}

function add_functions_tag($start, $end, $function, $param = null){

    global $my_theme;
    $my_theme->add_functions_tag($start, $end, $function, $param);

}

function require_page($bool, $page){
    if($bool == true){
        if($page == PAGE_ID){

        } else {
            header('Location: '.HOST.'');
            exit;
        }
    }
}

function get_file($page, $name = NULL, $page_loader = false){

    global $my_theme;

    if(empty($name))
        $name = '';

    $my_theme->get_file($page, $name, $page_loader);

}

function get_page($page, $name = NULL){

    global $my_theme;

    if(empty($name))
        $name = '';

    $my_theme->get_page($page, $name);

}

function get_file_admin($page, $name = NULL, $page_loader = false){

    global $my_theme;

    if(empty($name))
        $name = '';

    $my_theme->get_file_admin($page, $name, $page_loader);

}

function get_page_admin($page, $name = NULL){

    global $my_theme;

    if(empty($name))
        $name = '';

    $my_theme->get_page_admin($page, $name);

}

function get_menu(){

    global $my_db;

    $menu_query = $my_db->query("SELECT * FROM my_menu WHERE menu_enabled = '1' ORDER BY menu_sort");
    foreach($menu_query as $menu_row){

        $menu_name = $menu_row['menu_name'];
        $menu_page_id = $menu_row['menu_page_id'];
        $menu_link = $menu_row['menu_link'];
        $menu_icon = $menu_row['menu_icon'];
        $menu_icon_image = $menu_row['menu_icon_image'];
        $menu_dropdown = $menu_row['menu_dropdown'];
        $menu_dropdown_parent = $menu_row['menu_dropdown_parent'];
        $menu_sort = $menu_row['menu_sort'];

        if($menu_icon == 'fa'):

            $set_icon = '<i class="fa fa-'.$menu_icon_image.'"></i> ';

        elseif($menu_icon == 'glyphicon'):

            $set_icon = '<i class="glyphicon glyphicon-'.$menu_icon_image.'"></i> ';

        else:

            $set_icon = '';

        endif;

        if($menu_dropdown == '0'):
            ?>

            <li <?php if(PAGE_ID == $menu_page_id){ echo 'class="active"';}?>><a href="<?php echo $menu_link; ?>"><?php echo $set_icon; ?><?php echo $menu_name; ?></a></li>

        <?php
        else:
            ?>

            <li <?php if(PAGE_ID == $menu_page_id){ echo 'class="active"';}?> class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $menu_name; ?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <?php
                    $menu_dropdown_query = $my_db->query("SELECT * FROM my_menu WHERE menu_dropdown_parent = '".$menu_page_id."'");
                    while ($menu_dropdown_row = $my_db->fetch($menu_dropdown_query)) {
                        ?>
                        <li><a href="<?php echo $menu_link; ?>"><?php echo $set_icon; ?><?php echo $menu_name; ?></a></li>
                    <?php } ?>
                </ul>
            </li>

        <?php
        endif;

    }

}