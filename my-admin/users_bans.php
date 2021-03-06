<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */
hide_if_staff_not_logged();

global $my_db, $my_users, $my_blog;
define('PAGE_ID', 'admin_users_bans');
define('PAGE_NAME', ea('page_users_bans_page_name', '1'));

add_style_script_admin('css', '{@siteURL@}/my-admin/css/plugins/dataTables.bootstrap.css');

get_file_admin('header');
get_page_admin('topbar');


if(isset($_POST['banuser']))
{
    $user_rank = $my_users->getInfo($_SESSION['staff']['id'], 'rank');
    if($user_rank >= 2){
            $ban_user_email = $_POST['ban_user_email'];
            $ban_ip = $_POST['ban_ip'];
            $expire_date = $_POST['expire_date'];
            $converted_date = date('Y-m-d H:i:s', $expire_date);
            if(!empty($ban_user_email)){
                if($my_users->control_mail($ban_user_email)){
                    $user_banned_id = $my_users->get_user_id($ban_user_email);
                    $user_banned_ip = $my_users->getInfo($user_banned_id, "ip");
                    $my_db->query("INSERT INTO my_users_banned (user_ip,expire_date) VALUES (:user_ip, :user_expire_date)", array("user_ip"=>$user_banned_ip,"user_expire_date"=>$converted_date));
                }else{
                    if(!empty($ban_ip)){
                        $my_db->query("INSERT INTO my_users_banned (user_ip,expire_date) VALUES (:user_ip, :user_expire_date)", array("user_ip"=>$ban_ip,"user_expire_date"=>$converted_date));
                    }
                }
            }

    } else {
        $info = '<div class="alert alert-danger">'.ea('page_ranks_error_4', '1').'</div>';
    }
}
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <?php echo '<br>'.$info.'<br>'; ?>
            <h1 class="page-header"><?php ea('page_users_bans_page_name'); ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-8">
            <div class="table-responsive">
                <form action="" method="post">
                    <table class="table table-striped table-bordered table-hover" id="tables_posts">
                        <thead>
                        <tr>
                            <th><?php ea('page_ban_ip'); ?></th>
                            <th><?php ea('page_ban_expire_date'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        global $my_db;
                        $bans = $my_db->query("SELECT * from my_users_banned ORDER BY id DESC");
                        $i = 0; foreach($bans as $bans_info){ $i++;
                            ?>
                            <tr>
                                <td><?php echo $bans_info['user_ip']; ?> (<a href="https://who.is/whois-ip/ip-address/<?php echo $bans_info['user_ip']; ?>">Who is?</a>)</td>
                                <td><?php echo $bans_info['expire_date']; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
            </div>
            <!-- /.table-responsive -->
        </div>
        <!-- /.col-lg-12 -->

        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1 class="panel-title"><?php ea('page_ban_user_title'); ?></h1>
                </div>
                <form action="" method="post">
                    <div class="panel-body">
                        <span class="label label-success"><?php ea('page_ban_user_email'); ?></span>
                        <br />
                        <br />
                        <input type="text" name="ban_user_email" class="form-control"  maxlength="100" value="">
                        <br />
                        <span class="label label-success"><?php ea('page_ban_or_ip'); ?></span>
                        <br />
                        <br />
                        <input type="text" name="ban_ip" class="form-control"  maxlength="100" value="">
                        <br />
                        <span class="label label-success"><?php ea('page_ban_expire_date_select'); ?></span>
                        <br />
                        <br />
                        <select name="expire_date">
                            <option value="<?php echo strtotime('+2 hours', time()); ?>"><?php ea('page_ban_select_2_hours'); ?></option>
                            <option value="<?php echo strtotime('+1 day', time()); ?>"><?php ea('page_ban_select_1_day'); ?></option>
                            <option value="<?php echo strtotime('+1 month', time()); ?>"><?php ea('page_ban_select_1_month'); ?></option>
                            <option value="<?php echo strtotime('+1 year', time()); ?>"><?php ea('page_ban_select_1_year'); ?></option>
                        </select>

                    </div>
                    <div class="panel-footer"><button type="submit" name="banuser" class="btn btn-info"><?php ea('page_ban_button_ban'); ?></button></div>

            </div>
        </div>

        </form>
    </div>
</div>
<!-- /.row -->
</div>
<!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->
<?php get_file_admin('footer'); ?>
<script>
    $(document).ready(function() {
        $('#tables_posts').dataTable({
            language: {
                "sEmptyTable":     "<?php ea('_table_sEmptyTable'); ?>",
                "sInfo":           "<?php ea('_table_sInfo'); ?>",
                "sInfoEmpty":      "<?php ea('_table_sInfoEmpty'); ?>",
                "sInfoFiltered":   "<?php ea('_table_sInfoFiltered'); ?>",
                "sInfoPostFix":    "",
                "sInfoThousands":  ",",
                "sLengthMenu":     "<?php ea('_table_sLengthMenu'); ?>",
                "sLoadingRecords": "<?php ea('_table_sLoadingRecords'); ?>",
                "sProcessing":     "<?php ea('_table_sProcessing'); ?>",
                "sSearch":         "<?php ea('_table_sSearch'); ?>",
                "sZeroRecords":    "<?php ea('_table_sZeroRecords'); ?>",
                "oPaginate": {
                    "sFirst":      "<?php ea('_table_sFirst'); ?>",
                    "sPrevious":   "<?php ea('_table_sPrevious'); ?>",

                    "sNext":       "<?php ea('_table_sNext'); ?>",
                    "sLast":       "<?php ea('_table_sLast'); ?>"
                },
                "oAria": {
                    "sSortAscending":  "<?php ea('_table_sSortAscending'); ?>",
                    "sSortDescending": "<?php ea('_table_sSortDescending'); ?>"
                }
            }
        });
    });
</script>

</body>

</html>

