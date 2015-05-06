<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

hide_if_staff_logged();

define('PAGE_ID', 'admin_login');
define('PAGE_NAME', ea('page_login_page_name', '1'));

get_file_admin('header');
?>
<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php ea('page_login_panel-title') ?></h3>
                    </div>
                    <div class="panel-body">
                       <form role="form" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="<?php ea('page_login_placeholder_email') ?>" name="email" type="email" value="" autofocus required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="<?php ea('page_login_placeholder_password') ?>" name="password" type="password" value="" required>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="remember_t"><?php ea('page_login_remember') ?>
                                    </label>
                                </div>
                                <input type="submit" class="btn btn-lg btn-success btn-block" name="admin-login" value="<?php ea('page_login_button') ?>" />
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_file_admin('footer'); ?>

</body>

</html>
