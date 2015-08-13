<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

hide_if_staff_not_logged();

define('PAGE_ID', 'admin_home');
define('PAGE_NAME', ea('page_home_page_name', '1'));

get_file_admin('header');
get_page_admin('topbar');

global $my_db, $my_theme;
?>
	<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php ea('page_home_page_header'); ?></h1>
                </div>
                <!-- /.col-lg-12 -->
                <?php
                $info = $my_theme->there_is_new_update(false);
                if($info[0] == true){
                    switch  ($info[1])
                    {
                        case 'all_update':
                            $update_text = ea('page_home_general_info_update_all', true);
                            break;
                        case 'core_update':
                            $update_text = ea('page_home_general_info_core_update', true);
                            break;
                        case 'db_update':
                            $update_text = ea('page_home_general_info_db_update', true);
                            break;
                    }
                    echo '<div class="col-lg-12"><div class="alert alert-danger"><span class="badge" style="background-color: red">!</span> <b>' . $update_text . '</b> <a href="{@siteURL@}/my-admin/update" class="btn btn-info" style="float: right; margin-top: -6px;">'. ea('page_home_general_info_button_update', true) .'</a></div></div>';
                }
                ?>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-info fa-fw"></i> <?php ea('page_home_general_info'); ?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                        
                        <ul class="list-group">
                          <li class="list-group-item">
                            <span class="badge"><?php echo $my_db->single("SELECT count(*) FROM my_blog WHERE postPOSTED = '1'"); ?></span>
                            <i class="fa fa-thumb-tack fa-fw"></i> <a href="{@siteURL@}/my-admin/posts"><?php ea('page_home_general_info_post'); ?></a>
                          </li>
                           <li class="list-group-item">
                            <span class="badge"><?php echo $my_db->single("SELECT count(*) FROM my_blog_post_comments WHERE enable = '1'"); ?></span>
                            <i class="fa fa-comment fa-fw"></i> <a href="{@siteURL@}/my-admin/comments"><?php ea('page_home_general_info_comments'); ?></a>
                          </li>
                          <li class="list-group-item">
                            <span class="badge"><?php echo $my_db->single("SELECT count(*) FROM my_blog_category"); ?></span>
                            <i class="fa fa-cubes fa-fw"></i> <a href="{@siteURL@}/my-admin/category"><?php ea('page_home_general_info_category'); ?></a>
                          </li>
                        </ul>
                        
                
                        <i><p class="pull-left"><?php ea('page_home_info_in_use'); ?> MyCMS {@my_cms_version@}, <?php ea('page_home_info_theme_in_use'); ?> {@templateNAME@} {@templateVERSION@}<br /><?php ea('page_home_info_theme_created_by'); ?> {@templateAUTHOR@}<br>PHP: <?php echo phpversion(); ?></p></i>
                        
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                   <div class="alert alert-info"><?php ea('page_home_danger_info'); ?></div>
                </div>
                <!-- /.col-lg-6 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<?php get_file_admin('footer'); ?>

</body>

</html>

