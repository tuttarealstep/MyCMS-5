<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */
hide_if_staff_not_logged();

define('PAGE_ID', 'theme_manager');
define('PAGE_NAME', ea('page_theme_manager', '1'));

global $my_theme, $my_db, $my_users, $my_blog;

get_file_admin('header');
get_page_admin('topbar');

if(isset($_POST['newtheme'])) {


    if(staff_logged_in()){
        $user_rank = $my_users->getInfo($_SESSION['staff']['id'], 'rank');
        if($user_rank >= 3){

            $jsonurl = htmlentities($_POST['jsonurl']);
            $info = $my_theme->download_theme($jsonurl);
        }
    }

}
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <?php echo '<br>'.$info.'<br>'; ?>
            <h1 class="page-header"><?php ea('page_theme_manager_header'); ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-lg-8">
                <?php
                $temp = $my_db->query("SELECT * FROM my_style");
                $i = 0; foreach($temp as $template){ $i++;
                    ?>
                     <li><div class="alert alert-info"><b><?php echo $template['style_name']; ?></b>  - <b><?php ea('page_theme_manager_theme_by'); ?></b> <label style="color:#F00"> <?php echo $template['style_author']; ?></label></a></div></li>
                <?php
                }
                ?>
            </ul>
        </div>
        <!-- /.col-lg-6 -->
        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1 class="panel-title"><?php ea('page_theme_manager_add_new_theme'); ?></h1>
                </div>
                <form action="" method="post">
                    <div class="panel-body">

                        <span class="label label-success"><?php ea('page_theme_manager_labe_json_url'); ?></span><br /><br />
                        <input type="text" name="jsonurl" class="form-control"  maxlength="100" value="<?php echo $name ?>">
                        <br />

                    </div>
                    <div class="panel-footer"><button type="submit" name="newtheme" class="btn btn-info"><?php ea('page_theme_manager_add_button'); ?></button></div>
                </form>
            </div>
        </div>


    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->
<?php get_file_admin('footer'); ?>

</body>

</html>