<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */
define('PAGE_ID', 'login');
define('PAGE_NAME', e('login_page_title', '1'));

hide_if_logged();

get_file('header');
//PERSONALIZZAZIONE

$url_crypted = base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

if(!isset($_GET['return_url'])){ $return_url = ""; } else { $return_url = my_sql_secure(base64_decode($_GET['return_url'])); $return_url_und = my_sql_secure($_GET['return_url']);}
?>

<br>
<div class="container">
<div class="row">
  <div class="col-lg-6">
    <div class="panel panel-default" style="height: 348px;">
      <div class="panel-heading"><b><h2><?php e('login_h2_register_title'); ?></h2></b></div>
      <div class="panel-body">
        <?php e('login_register_description'); ?>
        <a class="btn btn-danger" href="{@siteURL@}/registration<?php if(empty($return_url)){ }else{?>/r/<?php echo $return_url_und; }?>" role="button" style="position:absolute;bottom:35px;left:30px;margin:0;"><?php e('login_button_register'); ?></a>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="panel panel-default">
      <div class="panel-heading"><b><h2><?php e('login_h2_login_title'); ?></h2></b></div>
      <div class="panel-body">
        <?php e('login_login_description'); ?>
        <form role="form" method="post">
        <div class="form-group">
        <label><?php e('login_login_email'); ?></label>
        <input type="email"  class="form-control" name="mail" id="mail" value="" style="width: 260px;" required="required"><br>
        <label><?php e('login_login_password'); ?></label>
        <input type="password"  class="form-control" name="password" id="password" value="" style="width: 260px;"/>
        <input type="hidden" name="return_url" value="<?php echo $return_url?>"/>
        <br>
        <button type="submit" class="btn btn-info pull-right" name="login"><?php e('login_login_button'); ?></button>
        <label>
        <input name="remember" type="checkbox" value="remember_t"> <?php e('login_login_remember') ?>
        </label>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
<?php
//FINE PERSONALIZZAZIONE
get_file('footer');
?>