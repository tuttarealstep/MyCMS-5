<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

class MY_Users{

    public function __construct ()
    {

    }

    public function login($email, $password, $remember = 0){

        if($this->is_user_banned()){
            return array("login" => 0, "error"=>"user_banned");
        }

        $validate_email = $this->validate("email", $email);
        $validate_password = $this->validate("password", $password);

        if($validate_email["valid"] == 0){
            return array("login" => 0, "error"=>"error_email_password");
        }elseif($validate_password["valid"] == 0){
            return array("login" => 0, "error"=>"error_email_password");
        }

        $user_id = $this->get_user_id($email);

        if(!$user_id){
            return array("login" => 0, "error"=>"error_email_password");
        }

        $user_data = $this->get_user_data($user_id);

        if (!password_verify($password, $user_data['password'])) {
            return array("login" => 0, "error"=>"error_email_password");
        }

        $validate_cookie = $this->add_session($user_id, $remember);

        if($validate_cookie["valid"] == false){
            MY_Error::error_die("DATABASE ERROR", "LOGIN SYSTEM ERROR");
        }

        $_SESSION['user']['id'] = $user_id;
        $_SESSION['user']['hash'] == "";

        if($validate_cookie["expire_time"] != 0){
            $_SESSION['user']['hash'] = $validate_cookie["hash"];
            if(!isset($_COOKIE['remember_me'])) {
                unset($_COOKIE['remember_me']);
                setcookie('remember_me', $_SESSION['user']['hash'], $validate_cookie["expire_time"]);
            }

        }

        $data_last_access = date("Y-m-d H:i:s", time());
        $user_ip = $this->user_ip();
        global $my_db;
        $my_db->query("UPDATE my_users SET ip = :ip WHERE id = :k", array("ip"=>$user_ip,"k"=>$user_id));
        $my_db->query("UPDATE my_users SET last_access = :last_access WHERE id = :k", array("last_access"=>$data_last_access,"k"=>$user_id));


        $this->set_user_tag();
        return array("login" => 1, "error"=>"");

    }

    public function login_admin($email, $password, $remember = 0){


        if($this->is_user_banned()){
            return array("login" => 0, "error"=>"user_banned");
        }

        $validate_email = $this->validate("email", $email);
        $validate_password = $this->validate("password", $password);

        if($validate_email["valid"] == 0){
            return array("login" => 0, "error"=>"error_email_password");
        }elseif($validate_password["valid"] == 0){
            return array("login" => 0, "error"=>"error_email_password");
        }

        $user_id = $this->get_user_id($email);

        if(!$user_id){
            return array("login" => 0, "error"=>"error_email_password");
        }

        $user_data = $this->get_user_data($user_id);

        if (!password_verify($password, $user_data['password'])) {
            return array("login" => 0, "error"=>"error_email_password");
        }

        if($user_data['rank'] < 2 ){
            return array("login" => 0, "error"=>"error_email_password");
        }

        $validate_cookie = $this->add_session_admin($user_id, $remember);

        if($validate_cookie["valid"] == false){
            MY_Error::error_die("DATABASE ERROR", "LOGIN SYSTEM ERROR");
        }

        $_SESSION['staff']['id'] = $user_id;
        $_SESSION['staff']['hash'] == "";

        if($validate_cookie["expire_time"] != 0){
            $_SESSION['staff']['hash'] = $validate_cookie["hash"];
            if(!isset($_COOKIE['remember_me_admin'])) {
                unset($_COOKIE['remember_me_admin']);
                setcookie('remember_me_admin', $_SESSION['staff']['hash'], $validate_cookie["expire_time"]);
            }

        }

        $data_last_access = date("Y-m-d H:i:s", time());
        $user_ip = $this->user_ip();
        global $my_db;
        $my_db->query("UPDATE my_users SET ip = :ip WHERE id = :k", array("ip"=>$user_ip,"k"=>$user_id));
        $my_db->query("UPDATE my_users SET last_access = :last_access WHERE id = :k", array("last_access"=>$data_last_access,"k"=>$user_id));


        $this->set_user_tag();
        return array("login" => 1, "error"=>"");
    }

    public function register($email, $password, $name, $surname){

        if($this->is_user_banned()){
            return array("register" => 0, "error"=>"user_banned");
        }

        $validate_email = $this->validate("email", $email);
        $validate_password = $this->validate("password", $password);
        $validate_name = $this->validate("name", $name);
        $validate_surname = $this->validate("surname", $surname);

        if($validate_email["valid"] == 0){
            return array("register" => 0, "error"=>"error_email");
        }elseif($validate_password["valid"] == 0){
            return array("register" => 0, "error"=>"error_password");
        }elseif($validate_name["valid"] == 0){
            return array("register" => 0, "error"=>"error_name");
        }elseif($validate_surname["valid"] == 0) {
            return array("register" => 0, "error" => "error_surname");
        }elseif($this->control_mail($email)){
            return array("register" => 0, "error"=>"error_email_in_use");
        }

        $password = my_cms_security_create_password($password);

        global $my_db;

        $ip = $_SERVER['REMOTE_ADDR'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $surname = filter_var($surname, FILTER_SANITIZE_STRING);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $my_db->query("INSERT INTO my_users (name,surname,password,mail,ip,rank) VALUES(:name, :surname, :password, :email, :ip, '1')", array("name"=>$name, "surname"=>$surname, "password"=>$password, "email"=>$email, "ip"=>$ip));


        return array("register" => 1, "error"=>"");

    }

    public function control_mail($email)
    {
        global $my_db;
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $sql = $my_db->iftrue("SELECT id FROM my_users WHERE mail = :mail LIMIT 1", array("mail"=>$email));
        return $sql;
    }

    public function set_user_tag(){
        if(user_logged_in()){
            add_tag('user_name', my_sql_secure(self::getInfo($_SESSION['user']['id'], 'name')));
            add_tag('user_surname', my_sql_secure(self::getInfo($_SESSION['user']['id'], 'surname')));
            add_tag('user_mail', my_sql_secure(self::getInfo($_SESSION['user']['id'], 'mail')));
            add_tag('user_ip', self::getInfo($_SESSION['user']['id'], 'ip'));
            add_tag('user_rank', self::getInfo($_SESSION['user']['id'], 'rank'));
            add_tag('user_last_access', self::getInfo($_SESSION['user']['id'], 'last_access'));
        } else {
            add_tag('user_name', "");
            add_tag('user_surname', "");
            add_tag('user_mail',"");
            add_tag('user_ip', "");
            add_tag('user_rank', "");
            add_tag('user_last_access', "");
        }
        if(staff_logged_in()){
            add_tag('user_name', my_sql_secure(self::getInfo($_SESSION['staff']['id'], 'name')));
            add_tag('user_surname', my_sql_secure(self::getInfo($_SESSION['staff']['id'], 'surname')));
            add_tag('user_mail', my_sql_secure(self::getInfo($_SESSION['staff']['id'], 'mail')));
            add_tag('user_ip', self::getInfo($_SESSION['staff']['id'], 'ip'));
            add_tag('user_rank', self::getInfo($_SESSION['staff']['id'], 'rank'));
            add_tag('user_last_access', self::getInfo($_SESSION['staff']['id'], 'last_access'));
        }
    }

    public function validate($type, $string){

        switch($type){
            case "email":
                if(filter_var($string, FILTER_VALIDATE_EMAIL)){
                    return array("valid" => 1, "error"=>"");
                } else {
                    return array("valid" => 0, "error"=>"invalid_email");
                }
                break;
            case "password":
                if (strlen($string) < 6) {
                    return array("valid" => 0, "error"=>"short_password");
                } elseif (strlen($string) > 72) {
                    return array("valid" => 0, "error"=>"long_password");
               /* } elseif (!preg_match('@[A-Z]@', $string) || !preg_match('@[a-z]@', $string) || !preg_match('@[0-9]@', $string)) {
                    return array("valid" => 0, "error"=>"invalid_password"); */
                } else {
                    return array("valid" => 1, "error"=>"");
                }
                break;
            case "name":
                if(strlen($string) < 4 || strlen($string) > 20){
                    return array("valid" => 0, "error"=>"");
                } else {
                    return array("valid" => 1, "error"=>"");
                }
                break;
            case "surname":
                if(strlen($string) < 4 || strlen($string) > 20){
                    return array("valid" => 0, "error"=>"");
                } else {
                    return array("valid" => 1, "error"=>"");
                }
                break;
        }

        return false;

    }

    public function get_user_id($email){
        global $my_db;
        $my_db->bind("mail", $email);
        $id = $my_db->single("SELECT id FROM my_users WHERE mail = :mail LIMIT 1");
        if(isset($id)){
            return $id;
        }

        return false;
    }

    public function get_user_data($user_id){

        global $my_db;
        $filter_id_user = filter_var($user_id, FILTER_SANITIZE_NUMBER_INT);
        if (filter_var($filter_id_user, FILTER_VALIDATE_INT)) {
            $sql = $my_db->row("SELECT * FROM my_users WHERE id = :user_id LIMIT 1", array("user_id"=>$filter_id_user));
            return $sql;
        }

        return false;

    }

    public function getInfo($key, $string)
    {
        global $my_db;
        $filter_id_user = filter_var($key, FILTER_SANITIZE_NUMBER_INT);
        $filter_string = filter_var($string, FILTER_SANITIZE_STRING);
        if (filter_var($filter_id_user, FILTER_VALIDATE_INT)) {
            $sql = $my_db->row("SELECT * FROM my_users WHERE id = :user_id LIMIT 1", array("user_id"=>$filter_id_user));
            return $sql[$filter_string];
        }
    }

    public function add_session_admin($user_id, $remember){

        global $my_db;
        $user_ip = $this->user_ip();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $user_data = $this->get_user_data($user_id);
        if(!$user_data){
            return false;
        }


        $user_cookie['hash'] = sha1($user_data['name'] . $user_data['surname'] . $user_data['mail'] . microtime());

        if($remember == true) {
            $user_cookie['expire'] = date("Y-m-d H:i:s", strtotime("+1 month"));
            $user_cookie['expire_time'] = strtotime($user_cookie['expire']);
        } else {
            $user_cookie['expire'] = date("Y-m-d H:i:s", strtotime("+1 month"));
            $user_cookie['expire_time'] = 0;
        }
        $user_cookie['cookie_value'] = sha1($user_cookie['hash'] . SECRET_KEY);

        if($user_cookie['expire_time'] != 0){
            $my_db->query("INSERT INTO my_security_cookie (cookie_name,cookie_value,cookie_user,cookie_expire, cookie_agent, cookie_ip) VALUES(:cookie_name, :cookie_value, :user_id, :cookie_expire, :cookie_agent, :cookie_ip)", array("cookie_name"=>"remember_me_admin", "cookie_value"=>$user_cookie['cookie_value'], "user_id"=>$user_id, "cookie_expire"=>$user_cookie['expire_time'], "cookie_agent"=>$user_agent, "cookie_ip"=>$user_ip));
            $info = $my_db->single("SELECT COUNT(*) FROM my_security_cookie WHERE cookie_user = :user_id_F AND cookie_name = 'remember_me_admin' AND cookie_value=:cookie_value_F LIMIT 1", array("user_id_F"=>$user_id, "cookie_value_F"=>$user_cookie['cookie_value']));
            if($info == 0){
                return array("valid"=> false, "expire_time" => $user_cookie['expire_time']);
            }
        }
        return array("valid"=> true, "expire_time" => $user_cookie['expire_time'], "hash" => $user_cookie['cookie_value']);
    }

    public function add_session($user_id, $remember){

        global $my_db;
        $user_ip = $this->user_ip();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $user_data = $this->get_user_data($user_id);
        if(!$user_data){
            return false;
        }


        $user_cookie['hash'] = sha1($user_data['name'] . $user_data['surname'] . $user_data['mail'] . microtime());

        if($remember == true) {
            $user_cookie['expire'] = date("Y-m-d H:i:s", strtotime("+1 month"));
            $user_cookie['expire_time'] = strtotime($user_cookie['expire']);
        } else {
            $user_cookie['expire'] = date("Y-m-d H:i:s", strtotime("+1 month"));
            $user_cookie['expire_time'] = 0;
        }
        $user_cookie['cookie_value'] = sha1($user_cookie['hash'] . SECRET_KEY);

        if($user_cookie['expire_time'] != 0){
            $my_db->query("INSERT INTO my_security_cookie (cookie_name,cookie_value,cookie_user,cookie_expire, cookie_agent, cookie_ip) VALUES(:cookie_name, :cookie_value, :user_id, :cookie_expire, :cookie_agent, :cookie_ip)", array("cookie_name"=>"remember_me", "cookie_value"=>$user_cookie['cookie_value'], "user_id"=>$user_id, "cookie_expire"=>$user_cookie['expire_time'], "cookie_agent"=>$user_agent, "cookie_ip"=>$user_ip));
            $info = $my_db->single("SELECT COUNT(*) FROM my_security_cookie WHERE cookie_user = :user_id_F AND cookie_name = 'remember_me' AND cookie_value=:cookie_value_F LIMIT 1", array("user_id_F"=>$user_id, "cookie_value_F"=>$user_cookie['cookie_value']));
            if($info == 0){
                return array("valid"=> false, "expire_time" => $user_cookie['expire_time']);
            }
        }
        return array("valid"=> true, "expire_time" => $user_cookie['expire_time'], "hash" => $user_cookie['cookie_value']);
    }

    public function control_session(){


            if(isset($_COOKIE['remember_me'])) {

                if ($this->is_user_banned()) {
                    return false;
                }

                global $my_db;
                $user_ip = $this->user_ip();
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                $cookie_hash = $_COOKIE['remember_me'];
                $info = $my_db->single("SELECT COUNT(*) FROM my_security_cookie WHERE cookie_name = 'remember_me' AND cookie_value = :cookie_value", array("cookie_value" => $cookie_hash));
                if ($info == 0) {
                    unset($_COOKIE['remember_me']);
                    setcookie('remember_me', "", time() - 3600);
                    return false;
                }



                $info_data = $my_db->row("SELECT * FROM my_security_cookie WHERE cookie_name = 'remember_me' AND cookie_value = :cookie_value", array("cookie_value" => $cookie_hash));
                if ($user_agent == $info_data["cookie_agent"] || $user_ip == $info_data["cookie_ip"]) {

                    $expire_date = $info_data["cookie_expire"];
                    $current_date = strtotime(date("Y-m-d H:i:s"));
                    if ($current_date > $expire_date) {
                        $this->delete_stored_cookies($info_data["cookie_user"]);
                        return false;
                    } else {
                        if (strlen($cookie_hash) != 40) {
                            return false;
                        }
                        if(user_not_logged_in()){
                            $_SESSION['user']['id'] =  $info_data["cookie_user"];
                            $_SESSION['user']['hash'] ==  $info_data["cookie_value"];
                        }
                    }
                } else {
                    return false;
                }
            }

    }
    public function control_session_admin(){


        if(isset($_COOKIE['remember_me_admin'])) {

            if ($this->is_user_banned()) {
                return false;
            }

            global $my_db;
            $user_ip = $this->user_ip();
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $cookie_hash = $_COOKIE['remember_me_admin'];
            $info = $my_db->single("SELECT COUNT(*) FROM my_security_cookie WHERE cookie_name = 'remember_me_admin' AND cookie_value = :cookie_value", array("cookie_value" => $cookie_hash));
            if ($info == 0) {
                unset($_COOKIE['remember_me_admin']);
                setcookie('remember_me_admin', "", time() - 3600);
                return false;
            }

            $info_data = $my_db->row("SELECT * FROM my_security_cookie WHERE cookie_name = 'remember_me_admin' AND cookie_value = :cookie_value", array("cookie_value" => $cookie_hash));
            if ($user_agent == $info_data["cookie_agent"] || $user_ip == $info_data["cookie_ip"]) {

                $expire_date = $info_data["cookie_expire"];
                $current_date = strtotime(date("Y-m-d H:i:s"));
                if ($current_date > $expire_date) {
                    $this->delete_stored_cookies_admin($info_data["cookie_user"]);
                    return false;
                } else {
                    if (strlen($cookie_hash) != 40) {
                        return false;
                    }
                    if(!staff_logged_in()){
                        $_SESSION['staff']['id'] =  $info_data["cookie_user"];
                        $_SESSION['staff']['hash'] ==  $info_data["cookie_value"];
                    }
                }
            } else {
                return false;
            }
        }
        return false;

    }
    private function delete_stored_cookies_admin($user_id){

        global $my_db;
        $my_db->query("DELETE FROM my_security_cookie WHERE cookie_user = :user_id AND cookie_name = 'remember_me_admin'", array("user_id"=>$user_id));
        $info = $my_db->single("SELECT COUNT(*) FROM my_security_cookie WHERE cookie_user = :user_id_F AND cookie_name = 'remember_me_admin'", array("user_id_F"=>$user_id));
        if($info == 0){
            unset($_COOKIE['remember_me_admin']);
            setcookie('remember_me_admin', "", time() - 3600);
            return true;
        }
        return false;
    }
    private function delete_stored_cookies($user_id){

        global $my_db;
        $my_db->query("DELETE FROM my_security_cookie WHERE cookie_user = :user_id AND cookie_name = 'remember_me'", array("user_id"=>$user_id));
        $info = $my_db->single("SELECT COUNT(*) FROM my_security_cookie WHERE cookie_user = :user_id_F AND cookie_name = 'remember_me'", array("user_id_F"=>$user_id));
        if($info == 0){
            unset($_COOKIE['remember_me']);
            setcookie('remember_me', "", time() - 3600);
           return true;
        }
        return false;
    }

    public function control_ban(){
        if ($this->is_user_banned()) {
            MY_Error::error_die("Ban", "You Are Banned");
        }
    }

    private function is_user_banned(){

        global $my_db;
        $user_ip = $this->user_ip();
        $count = $my_db->rowCount("SELECT expire_date FROM my_users_banned WHERE user_ip = :ip LIMIT 1", array("ip"=>$user_ip));
        if($count == 0){
            return false;
        }

        $expire_date = strtotime($my_db->single("SELECT expire_date FROM my_users_banned WHERE user_ip = :ip LIMIT 1", array("ip"=>$user_ip)));
        $current_date = strtotime(date("Y-m-d H:i:s"));

        if ($current_date < $expire_date) {
            return true;
        }

        if ($current_date > $expire_date) {
            $my_db->query("DELETE FROM my_users_banned WHERE user_ip = :ip LIMIT 1", array("ip"=>$user_ip));
        }

        return false;

    }


    public function user_ip(){

        $user_ip = $_SERVER['REMOTE_ADDR'];
        return $user_ip;

    }

    public function logout($return_url = "")
    {
        if(user_logged_in()){

            $this->delete_stored_cookies($_SESSION['user']['id']);

          if(empty($return_url)){
                unset($_SESSION['user']);
                session_destroy();
                header("Location: ".HOST."");
            } else {
                unset($_SESSION['user']);
                session_destroy();
                header("Location: ".$return_url);
            }
        }
    }


}

function user_logged_in(){

    if(isset($_SESSION['user']['id'])):
        return true;
    else:
        return false;
    endif;

}
function user_not_logged_in(){

    if(isset($_SESSION['user']['id'])):
        return false;
    else:
        return true;
    endif;

}
function staff_logged_in(){

    if(isset($_SESSION['staff']['id'])):

        return true;

    else:

        return false;

    endif;

}

add_functions_tag("{@user_logged_in=start@}", "{@user_logged_in=end@}", "user_logged_in");
add_functions_tag("{@user_not_logged_in=start@}", "{@user_not_logged_in=end@}", "user_not_logged_in");

function hide_if_logged(){
        if (user_logged_in()) {
            header("location: " . HOST . "");
            exit;
        }

}
function hide_if_not_logged()
{
        if (!user_logged_in()) {
            header("location: " . HOST . "");
            exit;
        }

}
function hide_if_staff_logged(){
    if (staff_logged_in()) {
        header("location: " . HOST . "");
        exit;
    }

}
function hide_if_staff_not_logged()
{
    if (!staff_logged_in()) {
        header("location: " . HOST . "");
        exit;
    }

}
function isStaff(){
    global $my_users;
    if(user_logged_in())
    {
        if($my_users->getInfo($_SESSION['user']['id'], 'rank') >= 2)
        {
            return true;
        }
        else
        {
            return false;
        }

    } else {
        return false;
    }
}
add_functions_tag("{@hide_if_logged=start@}", "{@hide_if_logged=end@}", "hide_if_logged");
add_functions_tag("{@hide_if_not_logged=start@}", "{@hide_if_not_logged=end@}", "hide_if_not_logged");
