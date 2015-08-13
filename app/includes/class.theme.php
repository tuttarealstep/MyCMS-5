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

    public $small_page = false;

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

    function remove_space($page){
        $output = str_replace(array("\r\n", "\r"), "\n", $page);
        $lines = explode("\n", $output);
        $new_lines = array();

        foreach ($lines as $i => $line) {
            if(!empty($line))
                $new_lines[] = trim($line);
        }
        return implode($new_lines);
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

                /*$config = array('indent' => true, 'input-xml'=>true, 'output-xhtml' => true, 'wrap' => 200);
                $tidy = new tidy;
                $tidy->parseString($page_loaded, $config, 'utf8');*/

                //Remove Space
                if($this->small_page == true){
                    $page_loaded = $this->remove_space($page_loaded);
                }
                self::set_page($page_loaded, $file, false);
            } else {
                $style_info = $this->style_info(MY_THEME);
                if ($file == $style_info["style_error_file"]) {
                    My_Error::error_die("404", "FILE NOT FOUND");
                }
                header('Location: ' . HOST . '/'.$style_info["style_error_file"]);
                return false;
            }
    }

    function set_page($page, $url, $admin){

        $timer_start = microtime(true);
        $page = self::set_TAG($page);
        $page = self::set_TAG_FUNCTIONS($page);

        if($admin == false){
        }

        $finished = number_format(microtime(true) - $timer_start, 3);
        $page = $page . "\n<!-- MyCMS Page Loader - Page loaded in " . $finished . " sec. -->";
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

        //NO TAGS
        $page = str_ireplace('{@no_siteURL@}', "{@siteURL@}" , $page);
        $page = str_ireplace('{@no_siteNAME@}', "{@siteNAME@}" , $page);
        $page = str_ireplace('{@no_siteTEMPLATE@}', "{@siteTEMPLATE@}" , $page);
        $page = str_ireplace('{@no_siteLANGUAGE@}', "{@siteLANGUAGE@}" , $page);
        $page = str_ireplace('{@no_siteDESCRIPTION@}', "{@siteDESCRIPTION@}" , $page);
        $page = str_ireplace('{@no_my_cms_welcome_h1@}', "{@my_cms_welcome_h1@}" , $page);

        return $page;

    }

    public function no_tags($page){
        $page = str_ireplace('{@siteURL@}', "{@no_siteURL@}" , $page);
        $page = str_ireplace('{@siteNAME@}', "{@no_siteNAME@}" , $page);
        $page = str_ireplace('{@siteTEMPLATE@}', "{@no_siteTEMPLATE@}" , $page);
        $page = str_ireplace('{@siteLANGUAGE@}', "{@no_siteLANGUAGE@}" , $page);
        $page = str_ireplace('{@siteDESCRIPTION@}', "{@no_siteDESCRIPTION@}" , $page);
        $page = str_ireplace('{@my_cms_welcome_h1@}', "{@no_my_cms_welcome_h1@}" , $page);
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
                    echo "\n\n<!-- START Plugin -->\n\n";
                    $my_plugins->include_plugins("footer");
                    echo "\n\n<!-- FINISH Plugin -->\n\n";
                    require_once($theme_path . '/'.$load_file);
                }
                break;
            case 'page_loader_top':
                $load_file = 'page_loader_top.php';
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
            case 'page_loader_bottom':
                $load_file = 'page_loader_bottom.php';
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

        $info = $this->style_info(MY_THEME);
        if($url !== $info['style_maintenance_page']){
                if($maintenance == true){
                    header('Location: '.HOST.'/'.$info['style_maintenance_page']);
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

    function theme_update($version, $url){
        global $my_cms_version;
        $download_url = $url;
        $get_info = @json_decode(file_get_contents($download_url), true);

        $theme_version = @$get_info["theme_version"];
        $theme_my_cms_version = @$get_info["my_cms_version"];

        if(version_compare($version, $theme_version, '<')){
            if( version_compare($theme_my_cms_version, $my_cms_version, '<=') ){
                return [true, $theme_version, true, $theme_my_cms_version];
            } else {
                return [true, $theme_version, false, $theme_my_cms_version];
            }
        } else {
                return [false, $theme_version, false, $theme_my_cms_version];
        }

    }

    function remove_dir($dir) {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file)
                if ($file != "." && $file != "..") remove_dir("$dir/$file");
            rmdir($dir);
        }
        else if (file_exists($dir)) unlink($dir);
    }

    function folder_copy($src, $dst) {
        if (file_exists ( $dst ))
            remove_dir ( $dst );
        if (is_dir ( $src )) {
            mkdir ( $dst );
            $files = scandir ( $src );
            foreach ( $files as $file )
                if ($file != "." && $file != "..")
                    $this->folder_copy ( "$src/$file", "$dst/$file" );
        } else if (file_exists ( $src ))
            copy ( $src, $dst );
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
        $theme_error_page = @$get_info["theme_error_page"];
        $theme_maintenance_page = @$get_info["theme_maintenance_page"];
        $theme_version = @$get_info["theme_version"];
        $theme_languages = @$get_info["theme_languages"];

        if (empty($theme_name)) {
            return '<div class="alert alert-danger"> Error Download 1</div>';
        }
        if (empty($theme_author)) {
            return '<div class="alert alert-danger"> Error Download 2</div>';
        }
        if (empty($theme_zip_file_name)) {
            return '<div class="alert alert-danger"> Error Download 3</div>';
        }
        if (empty($my_cms_version_cms)) {
            return '<div class="alert alert-danger"> Error Download 4</div>';
        }
        if (empty($theme_error_page)) {
            return '<div class="alert alert-danger"> Error Download 5</div>';
        }
        if (empty($theme_maintenance_page)) {
            return '<div class="alert alert-danger"> Error Download 6</div>';
        }
        if (empty($theme_version)) {
            return '<div class="alert alert-danger"> Error Download 7</div>';
        }
        if (empty($theme_languages)) {
            return '<div class="alert alert-danger"> Error Download 8</div>';
        }
        if ($my_cms_version_cms != $my_cms_version) {
            return '<div class="alert alert-danger"> Error Download - <b>Wrong MyCMS Version</b> </div>';
        }

        $zipname = $theme_zip_file_name . '.zip';

        if (file_exists( P_PATH_S . "/app/content/theme/" . $theme_zip_file_name)) {
            return '<div class="alert alert-danger"> Error Download - <b>Another Theme with this name</b> </div>';
        }

        ignore_user_abort(true);
        set_time_limit(0);

        $download_path = str_replace('/info.json', '', $download_url);

        $dir = ".".MY_BASE_PATH."/tmp/" . $theme_zip_file_name;
        $real_path = ".".MY_BASE_PATH."/app/content/theme/" . $theme_zip_file_name;

        if (!mkdir($dir, 0755, true)) {
            return '<div class="alert alert-danger"> Error Download - <b>Can\'t create tmp folder, check permission!</b> </div>';
        }
        if (!mkdir($real_path, 0755, true)) {
            return '<div class="alert alert-danger"> Error Download - <b>Can\'t create theme folder, check permission!</b> </div>';
        }

        $info = file_get_contents($download_path . "/" . $zipname);
        file_put_contents($dir . '/' . $zipname, $info);

        $zip_extract = new ZipArchive;
        if ($zip_extract->open($dir . '/' . $zipname) === TRUE) {
            $zip_extract->extractTo($dir . '/');
            $zip_extract->close();
        } else {
            return '<div class="alert alert-danger"> Error ZIP - <b>Can\'t Open, check Extension!</b> </div>';
        }

        unlink($dir . '/' . $zipname);


        $source = $dir . '/';
        $destination = $real_path . "/";
        $this->folder_copy($source, $destination);

        remove_dir(".".MY_BASE_PATH."/tmp");
        $my_db->query("INSERT INTO my_style (style_name,style_author,style_path_name,style_error_file,style_maintenance_page,style_json_file_url,style_version,style_languages) VALUES (:style_name,:style_author,:style_path_name,:style_error_file,:style_maintenance_page,:style_json_file_url,:style_version,:style_languages)", array('style_name'=>$theme_name,'style_author'=>$theme_author,'style_path_name'=>$theme_zip_file_name, 'style_error_file'=>$theme_error_page, 'style_maintenance_page'=>$theme_maintenance_page, 'style_json_file_url'=>$download_url, 'style_version'=>$theme_version, 'style_languages'=>$theme_languages));
    }

    public function style_info($style){
        global $my_db;
        if(!empty($style)){
            $test = $my_db->iftrue("SELECT style_id FROM my_style WHERE style_path_name = :style_path_name", array("style_path_name" => my_sql_secure($style)));
            if($test){
                $style_info =$my_db->row("SELECT * FROM my_style WHERE style_path_name = :style_path_name", array("style_path_name" => my_sql_secure($style)));
                return $style_info;
            }
        }
        return false;
    }

    public function there_is_new_update($check = true){

        global $my_cms_version, $my_cms_db_version;

        $get_info = @json_decode(file_get_contents( MY_CMS_WEBSITE . "/update/update.json"), true);

        $my_cms_core_update = @$get_info["my_cms_core_update"];
        $my_cms_db_update = @$get_info["my_cms_db_update"];
        $my_cms_changelog_array = @$get_info["my_cms_changelog_array"];
        $my_cms_db_changelog_array = @$get_info["my_cms_db_changelog_array"];

        if($my_cms_core_update != '' && $my_cms_db_update != ''){
            if(version_compare($my_cms_core_update,$my_cms_version, '>') && version_compare($my_cms_db_update, $my_cms_db_version, '>')) {
                $info = 'all_update';
                $cms_version = $my_cms_core_update;
                $db_version = $my_cms_db_update;
                $changelog_array = $my_cms_changelog_array;
                $changelog_array = array_merge($changelog_array, $my_cms_db_changelog_array);
                $return = true;
            } elseif(version_compare($my_cms_core_update,$my_cms_version, '>')) {
                $info = 'core_update';
                $cms_version = $my_cms_core_update;
                $db_version = '';
                $changelog_array = $my_cms_changelog_array;
                $return = true;
            } elseif(version_compare($my_cms_db_update, $my_cms_db_version, '>')){
                $info = 'db_update';
                $cms_version = '';
                $db_version = $my_cms_db_update;
                $changelog_array = $my_cms_db_changelog_array;
                $return = true;
            } else {
                $info = '';
                $return = false;
            }
        } else {
            $info = '';
            $return = false;
        }

        if($check){
            return $return;
        } else {
            return [$return, $info, $cms_version, $db_version, $changelog_array];
        }
    }

    function console_first_text(){
        global $my_cms_version;
        $string = "  __  __        _____ __  __  _____ \n"; $string .= " |  \/  |      / ____|  \/  |/ ____|\n";$string .= " | \  / |_   _| |    | \  / | (___  \n";$string .= " | |\/| | | | | |    | |\/| |\___ \ \n";$string .= " | |  | | |_| | |____| |  | |____) |\n";$string .= " |_|  |_|\__, |\_____|_|  |_|_____/ \n";$string .= "          __/ |                     \n";$string .= "         |___/                      \n"; $string .= "               Version $my_cms_version\n"; $string .= "               Console Mode\n";
        return $string;
    }


    function show_status($done, $total, $size=30) {

        static $start_time;

        if($done > $total) return;

        if(empty($start_time)) $start_time=time();
        $now = time();

        $perc=(double)($done/$total);

        $bar=floor($perc*$size);

        $status_bar="\r [";
        $status_bar.=str_repeat('*', $bar);
        if($bar<$size){
            $status_bar.='*';
            $status_bar.=str_repeat('.', $size-$bar);
        } else {
            $status_bar.='*';
        }

        $disp=number_format($perc*100, 0);

        $status_bar.="] $disp%  $done/$total";

        $rate = ($now-$start_time)/$done;
        $left = $total - $done;
        $eta = round($rate * $left, 2);

        $elapsed = $now - $start_time;

        echo " $status_bar  ";

        flush();

        // when done, send a newline
        if($done == $total) {
            echo "\n";
        }

    }


    function progress_bar($start, $max){
        for($done=$start;$done<=$max;$done++){
            $this->show_status($done, $max);
            usleep(100000);
        }
    }

    function console_a_w($str){
        if(MY_CMS_CONSOLE_MODE == true){
            echo "[MY-ADMIN] ".$str;
        }
    }

    function start_console_mode(){
        echo $this->console_first_text();
        define("MY_CMS_CONSOLE_MODE", true);
        //Start Loading
        //$this->progress_bar(1, 100);
        //Show menu
        echo "Welcome to MyCMS Console Mode\n";
        echo "Type a command ('help' for list of commands)'\n";
        while(true){
            $command = trim(fgets(fopen("php://stdin", "r")));
            switch ($command){
                case 'help':
                    echo "This is the list of all commands you can do:\n";
                    echo "  help ( list of command with hint )\n";
                    echo "  exit ( exit from my-cms console mode )\n";
                    echo "  mycms (-v for version, -dbv for database version )\n";
                    echo "  my-admin-mode ( enter in my-admin mode for manage the website)\n";
                    break;
                case 'exit':
                    echo "Exit from MyCMS Console Mode...\n";
                    sleep(1);
                    exit();
                    break;
                case 'mycms':
                    echo "Please use mycms [-v | -dbv] only one for time\n";
                    break;
                case 'mycms -v':
                    global $my_cms_version;
					echo "-------------------------------\n";
                    echo "MyCMS Version $my_cms_version\n";
					echo "-------------------------------\n";
                    break;
                case 'mycms -dbv':
                    global $my_cms_db_version;
					echo "-------------------------------\n";
                    echo "MyCMS Database Version $my_cms_db_version\n";
					echo "-------------------------------\n";
                    break;
                case 'my-admin-mode':
                    global $my_users;
                    echo "For use this mod please login with admin account\n";
                    $try = true;
                    $success = false;
                    while($try == true) {
                        echo "Email:\n";
                        $email = trim(fgets(fopen("php://stdin", "r")));
                        echo "Password:\n";
                        $password  = trim(fgets(fopen("php://stdin", "r")));

                        $mail = htmlentities(my_sql_secure($email));
                        $password = htmlentities(my_sql_secure($password));
                        $login = $my_users->login_admin($mail,$password, false);
                        if( $login["login"] == 1 ){
                            $success = true;
                            $try = false;
                        } else {
                            echo "\n";
                            echo ea($login["error"], '1')."\n";
                            echo "\n";
                            $try = false;
                        }

                        if($success == true && $try == false){
                            echo "Success...!\n";
                            echo "\n";
                            $complete_name = $my_users->getInfo($_SESSION['staff']['id'], 'name').' '.$my_users->getInfo($_SESSION['staff']['id'], 'surname');
                            $user_rank = $my_users->getInfo($_SESSION['staff']['id'], 'rank');
                            if($user_rank >= 3) {
                                $this->console_a_w("Welcome $complete_name\n");
                                $this->console_a_w("Type a command ('help' for list of commands)'\n");
                                $admin_mode = true;
                                while ($admin_mode == true) {
                                    $command_admin = trim(fgets(fopen("php://stdin", "r")));
                                    switch ($command_admin) {
                                        case 'help':
                                            $this->console_a_w("This is the list of all commands you can do:\n");
                                            $this->console_a_w("    exit mode ( Exit from admin mode )\n");
                                            $this->console_a_w("    enable | disable ( -maintenance )\n");
                                            $this->console_a_w("    set ( -site_name | -site_description | -site_url )\n");
                                            break;
                                        case 'set -site_name':
                                            $this->console_a_w("Write name for website:\n");
                                            $site_name = htmlentities(fgets(fopen("php://stdin", "r")));
                                            if(save_settings('site_name', $site_name) == false){
                                                $this->console_a_w(ea('error_page_settings_general_save', '1'));
                                            } else {
                                                $this->console_a_w("Site Name changed in $site_name !\n");
                                            }
                                            break;
                                        case 'set -site_description':
                                            $this->console_a_w("Write description for website:\n");
                                            $site_description = htmlentities(fgets(fopen("php://stdin", "r")));
                                            if(save_settings('site_description', $site_description) == false){
                                                $this->console_a_w(ea('error_page_settings_general_save', '1'));
                                            } else {
                                                $this->console_a_w("Site Description changed in $site_description !\n");
                                            }
                                            break;
                                        case 'set -site_url':
                                            $this->console_a_w("Write url for website (Warning!!!):\n");
                                            $site_url = htmlentities(fgets(fopen("php://stdin", "r")));
                                            if(save_settings('site_url', $site_url) == false){
                                                $this->console_a_w(ea('error_page_settings_general_save', '1'));
                                            } else {
                                                $this->console_a_w("Site Url changed in $site_url !\n");
                                            }
                                            break;
                                        case 'enable -maintenance':
                                            if(save_settings('site_maintenance', "true") == false){
                                                $this->console_a_w(ea('error_page_settings_general_save', '1'));
                                            } else {
                                                $this->console_a_w("Maintenance enabled!\n");
                                            }
                                            break;
                                        case 'disable -maintenance':
                                            if(save_settings('site_maintenance', "false") == false){
                                                $this->console_a_w(ea('error_page_settings_general_save', '1'));
                                            } else {
                                                $this->console_a_w("Maintenance disabled!\n");
                                            }
                                            break;
                                        case 'exit mode':
                                            $this->console_a_w("Exit from MyAdmin Mode..\n");
                                            sleep(1);
                                            $admin_mode = false;
                                            echo "Bye...\n";
                                            echo "\n";
                                            break;
                                        default:
                                            $this->console_a_w("Command not found! Please type 'help'\n");
                                    }
                                }
                            } else {
                                $this->console_a_w("You are not admin (rank 3)\n");
                                sleep(1);
                                echo "Bye...\n";
                                echo "\n";
                                break;
                            }
                        } else {
                            echo "Do you want retry? (y | n)\n";
                            $retry  = trim(fgets(fopen("php://stdin", "r")));
                            if($retry == 'y'){
                                $try = true;
                            } else {
                                echo "Bye...\n";
                                sleep(1);
                                echo "\n";
                            }
                        }

                    }
                    break;
                default:
                    echo "Command not found! Please type 'help'\n";
            }
        }


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
