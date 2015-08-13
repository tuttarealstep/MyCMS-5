<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

global $my_blog,$my_users;

if(isset($_POST['admin-login'])) {
    $mail = htmlentities(my_sql_secure($_POST['email']));
    $password = htmlentities(my_sql_secure($_POST['password']));
    $remember = htmlentities(my_sql_secure($_POST['remember']));

    if( $remember == "remember_t" ){
        $login = $my_users->login_admin($mail,$password, true);
        if( $login["login"] == 1 ){
                header("location: ".HOST."/my-admin/index");
                exit;
        } else {
            define("INDEX_ERROR", ea($login["error"], '1'));
        }
    } else {
        $login = $my_users->login_admin($mail,$password, false);
        if( $login["login"] == 1 ){
                header("location: ".HOST."/my-admin/index");
                exit;
        } else {
            define("INDEX_ERROR", ea($login["error"], '1'));
        }
    }
}

if(isset($_POST['login'])) {
    $mail = htmlentities(my_sql_secure($_POST['mail']));
    $password = htmlentities(my_sql_secure($_POST['password']));
    $return_url = htmlentities(my_sql_secure($_POST['return_url']));
    $remember = htmlentities(my_sql_secure($_POST['remember']));


    if( $remember == "remember_t" ){

        $login = $my_users->login($mail,$password, true);


        if( $login["login"] == 1 ){
            if(empty($return_url)){
                header("location: ".HOST."/index");
                exit;
            }else{
                header("location: ".$return_url);
                exit;
            }
        } else {
            define("INDEX_ERROR", e($login["error"], '1'));
        }

    } else {
        $login = $my_users->login($mail,$password, false);
        if( $login["login"] == 1 ){
            if(empty($return_url)){
                header("location: ".HOST."/index");
                exit;
            }else{
                header("location: ".$return_url);
                exit;
            }
        } else {
            define("INDEX_ERROR", e($login["error"], '1'));
        }
    }
}
if(isset($_POST['register']))
{
    // Dati Inviati dal modulo
    $return_url = (isset($_POST['return_url'])) ? trim($_POST['return_url']) : '';
    $name = (isset($_POST['name'])) ? trim($_POST['name']) : '';
    $surname = (isset($_POST['surname'])) ? trim($_POST['surname']) : '';
    $password = (isset($_POST['password'])) ? trim($_POST['password']) : '';
    $email = htmlentities($_POST['mail']);

    // Filtro i dati inviati se i magic_quotes del server sono disabilitati per motivi di sicurezza
    if (!get_magic_quotes_gpc()) {
        $name = addslashes($name);
        $surname = addslashes($surname);
        $password = addslashes($password);
        $email = addslashes($email);
    }
        $register = $my_users->register(my_sql_secure($email), my_sql_secure($password), my_sql_secure($name), my_sql_secure($surname));
        if( $register["register"] == 1 ){
            if(empty($return_url)){
                header("location: ".HOST."/index");
                exit;
            }else{
                header("location: ".$return_url);
                exit;
            }
        } else {
            define("INDEX_ERROR", e($register["error"], '1'));
        }
}
if(isset($_POST['postCOMMENT'])) {
    if(user_logged_in()){
        $comments = htmlentities($_POST['commento']);
        $blogid = htmlentities($_POST['post_id']);
        $my_blog->addcomments($blogid, $comments);
        header("location: ".HOST."/blog/id/".$blogid);
        exit;
    }
}
if(isset($_POST['search'])) {

    $searchstring = my_sql_secure(htmlentities(add_space($_POST['searchform'])));

    if(empty($searchstring)){
        header("location: ".HOST."/blog");
        exit;
    }

    header("location: ".HOST."/blog/search/".$searchstring."");
    exit;
}

if(isset($_POST['save_settings_general'])) {

    if(staff_logged_in()){

        $user_rank = $my_users->getInfo($_SESSION['staff']['id'], 'rank');
        if($user_rank >= 3){
            $settings_site_name = htmlentities($_POST['settings_site_name']);
            $settings_site_description = htmlentities($_POST['settings_site_description']);
            $settings_site_url = htmlentities($_POST['settings_site_url']);
            $settings_site_timezone = htmlentities($_POST['settings_site_timezone']);
            $settings_site_mainteinance = htmlentities($_POST['settings_site_mainteinance']);

            if(empty($settings_site_name)):
                define("INDEX_ERROR", ea('error_page_settings_general_name', '1'));
            endif;
            if (empty($settings_site_url)):
                define("INDEX_ERROR", ea('error_page_settings_general_url', '1'));
            endif;

            if(save_settings('site_name', $settings_site_name) == false){ define("INDEX_ERROR", ea('error_page_settings_general_save', '1')); };
            if(save_settings('site_description', $settings_site_description) == false){ define("INDEX_ERROR", ea('error_page_settings_general_save', '1')); };
            if(save_settings('site_url', $settings_site_url) == false){ define("INDEX_ERROR", ea('error_page_settings_general_save', '1')); };
            if(save_settings('site_timezone', $settings_site_timezone) == false){ define("INDEX_ERROR", ea('error_page_settings_general_save', '1')); };
            if(save_settings('site_maintenance', $settings_site_mainteinance) == false){ define("INDEX_ERROR", ea('error_page_settings_general_save', '1')); };

            header("location: ".HOST."/my-admin/settings_general");
            exit;
        }
    }

}
if(isset($_POST['save_settings_blog'])) {

    global $my_blog;
    if(staff_logged_in()){

        $user_rank = $my_users->getInfo($_SESSION['staff']['id'], 'rank');
        if($user_rank >= 3){

            $settings_blog_private = htmlentities($_POST['settings_blog_private']);
            $settings_blog_comments_active = htmlentities($_POST['settings_blog_comments_active']);
            $settings_blog_comments_approve = htmlentities($_POST['settings_blog_comments_approve']);

            if(save_settings('blog_private', $settings_blog_private) == false){ define("INDEX_ERROR", ea('error_page_settings_general_save', '1')); };
            if(save_settings('blog_comments_active', $settings_blog_comments_active) == false){ define("INDEX_ERROR", ea('error_page_settings_general_save', '1')); };
            if(save_settings('blog_comments_approve', $settings_blog_comments_approve) == false){ define("INDEX_ERROR", ea('error_page_settings_general_save', '1')); };

            if($settings_blog_private == 'true'){
                $my_blog->set_private(true);
            } else {
                $my_blog->set_private(false);
            }

            header("location: ".HOST."/my-admin/settings_blog");
            exit;
        }

    }

}
if(isset($_POST['save_settings_style'])) {


    if(staff_logged_in()){
        $user_rank = $my_users->getInfo($_SESSION['staff']['id'], 'rank');
        if($user_rank >= 3){
            $settings_style_language = htmlentities($_POST['settings_style_language']);
            $settings_style_template = htmlentities($_POST['settings_style_template']);
            $settings_style_template_language = htmlentities($_POST['settings_style_template_language']);

            if(save_settings('site_language', $settings_style_language) == false){ define("INDEX_ERROR", ea('error_page_settings_general_save', '1')); };
            if(save_settings('site_template', $settings_style_template) == false){ define("INDEX_ERROR", ea('error_page_settings_general_save', '1')); };
            if(save_settings('site_template_language', $settings_style_template_language) == false){ define("INDEX_ERROR", ea('error_page_settings_general_save', '1')); };

            header("location: ".HOST."/my-admin/settings_style");
            exit;
        }}



}

if(isset($_POST['save_settings_xml_commands'])) {

    global $my_db;

    if (staff_logged_in()) {
        $user_rank = $my_users->getInfo($_SESSION['staff']['id'], 'rank');
        if ($user_rank >= 3) {
            $xml_command_code = $_POST['xml_command_code'];
            $mycms_xml = simplexml_load_string($xml_command_code);
            if (my_cms_xml_command($mycms_xml->command['value'])){
                if ($mycms_xml->command['value'] == "add_new_language") {
                    if(empty($mycms_xml->command->language_name)){
                    } else {
                        if(empty($mycms_xml->command->language_language)){
                        } else {
                            $my_db->query("INSERT INTO my_language (language_name,language_language) VALUES (:language_name, :language_language)", array('language_name'=>$mycms_xml->command->language_name,'language_language'=>$mycms_xml->command->language_language));
                        }
                    }
                }elseif($mycms_xml->command['value'] == "remove_language") {
                    if(empty($mycms_xml->command->language_language)){
                    } else {
                        $my_db->query('DELETE FROM my_language WHERE language_language = :language_language LIMIT 1', array('language_language'=>$mycms_xml->command->language_language));
                    }
                }elseif($mycms_xml->command['value'] == "add_new_style") {
                    if(empty($mycms_xml->command->style_name)){
                    } else {
                        $my_db->query("INSERT INTO my_style (style_name) VALUES (:style_name)", array('style_name'=>$mycms_xml->command->style_name));
                    }
                }elseif($mycms_xml->command['value'] == "remove_style") {
                    if(empty($mycms_xml->command->style_name)){
                    } else {
                        $my_db->query('DELETE FROM my_style WHERE style_path_name = :style_path_name LIMIT 1', array('style_path_name'=>$mycms_xml->command->style_path_name));
                    }
                }
            }
        }
    }
    header("location: ".HOST."/my-admin/xml_command");
    exit;

}
