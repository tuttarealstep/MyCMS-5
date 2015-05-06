<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */


define('PAGE_ID', 'registration');
define('PAGE_NAME', e('registration_page_title', '1'));


//PERSONALIZZAZIONE

hide_if_logged();

$url_crypted = base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

if(!isset($_GET['return_url'])){ $return_url = ""; } else { $return_url = my_sql_secure(base64_decode($_GET['return_url'])); $return_url_und = my_sql_secure($_GET['return_url']);}
get_file('header');
?>

<br />
<div class="container" >
    <div class="row">
        <form role="form" method="post" action="" >
            <div class="col-lg-5">
                <div class="well well-sm"><strong><span class="glyphicon glyphicon-asterisk"></span> <?php e('registration_obbligatory'); ?></strong></div>
                <div class="form-group" style="color: #ffffff">
                    <label for="Input"><?php e('registration_name'); ?></label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="name" id="name" placeholder="<?php e('registration_insert'); ?> <?php e('registration_name'); ?>" required>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    </div>
                </div>
                <div class="form-group"style="color: #ffffff">
                    <label for="Input"><?php e('registration_surname'); ?></label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="surname" name="surname" placeholder="<?php e('registration_insert'); ?> <?php e('registration_surname'); ?>" required>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    </div>
                </div>
                <div class="form-group"style="color: #ffffff">
                    <label for="Input"><?php e('registration_email'); ?></label>
                    <div class="input-group">
                        <input type="email" class="form-control" id="mail" name="mail" placeholder="<?php e('registration_insert'); ?> <?php e('registration_email'); ?>" required>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    </div>
                </div>
                <div class="form-group"style="color: #ffffff">
                    <label for="Input"><?php e('registration_password'); ?></label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="<?php e('registration_insert'); ?> <?php e('registration_password'); ?>" >
                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                    </div>
                </div>
            </div>
        <div class="col-lg-6 col-md-push-1">
            <div class="col-md-12">
                
                <input type="hidden" name="return_url" value="<?php echo $return_url?>"/>
                
				     <h5><b><textarea cols="60" rows="3" style="margin: 0px; height: 228px; width: 520px;"readonly><?php e('site_privacy_info'); ?></textarea></div></fieldset><?php e('registration_info_description'); ?></b></h5>
					 <?php e('registration_info_description'); ?><br />
                <input type="submit" name="register" id="submit" value="<?php e('registration_button_register'); ?>" class="btn btn-info pull-right">
            </div>
        </div>
        </form>
    </div>
</div>