<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */
hide_if_staff_not_logged();

global $my_date, $my_db, $my_users, $my_blog;
define('PAGE_ID', 'admin_ranks');
define('PAGE_NAME', ea('page_ranks_page_name', '1'));

add_style_script_admin('css', '{@siteURL@}/my-admin/css/plugins/dataTables.bootstrap.css');

get_file_admin('header'); 
get_page_admin('topbar');


if(isset($_POST['rankutente']))
{
	$user_rank = $my_users->getInfo($_SESSION['staff']['id'], 'rank');
	if($user_rank >= 3){
	if(!empty($_POST['email_rank'])){
					$email_rank = $_POST['email_rank'];
		if($my_users->control_mail($email_rank)){
					$rank_id = $_POST['rank_id'];
					$my_db->query("UPDATE my_users SET rank = '".$rank_id."' WHERE mail = '".$email_rank."' LIMIT 1");
					$info = '<div class="alert alert-success">'.ea('page_ranks_error_1', '1').'</div>';
					$username_rank = '';
					$rank_id = '';	
		} else {
			$info = '<div class="alert alert-danger">'.ea('page_ranks_error_2', '1').'</div>';
			$email_rank = $_POST['email_rank'];
			$rank_id = $_POST['rank_id'];			
		}	
	} else {
		$info = '<div class="alert alert-danger">'.ea('page_ranks_error_3', '1').'</div>';
		$email_rank = $_POST['email_rank'];
		$rank_id = $_POST['rank_id'];			
	}
	} else {
		$info = '<div class="alert alert-danger">'.ea('page_ranks_error_4', '1').'</div>';
		$email_rank = $_POST['email_rank'];
		$rank_id = $_POST['rank_id'];			
	}
} 
?>
	<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                <?php echo '<br>'.$info.'<br>'; ?>
                    <h1 class="page-header"><?php ea('page_ranks_header'); ?></h1>
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
                                            <th><?php ea('page_ranks_table_user'); ?></th>
                                            <th><?php ea('page_ranks_table_rank'); ?></th>
                                            <th><?php ea('page_ranks_table_name_rank'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
										global $my_db;
											$ranks = $my_db->query("SELECT * from my_users WHERE rank >= '2' ORDER BY rank DESC");
											$i = 0; foreach($ranks as $ranksinfo){ $i++; 
											
											if($ranksinfo['rank'] == 2){
												$rank_name = ea('page_ranks_2', '1');
											} 
											elseif($ranksinfo['rank'] == 3){
												$rank_name = ea('page_ranks_3', '1');
											}
											elseif($ranksinfo['rank'] == 1){
												$rank_name = ea('page_ranks_1', '1');
											}
											
										?>
											<tr>
                                            	<td><?php echo $ranksinfo['name'].' '.$ranksinfo['surname']; ?></td>
												<td><?php echo $ranksinfo['rank']; ?></td>
                                                <td><?php echo $rank_name; ?></td>
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
                		<h1 class="panel-title"><?php ea('page_ranks_give_user_title'); ?></h1>
              		</div>
                      <form action="" method="post">
                      <div class="panel-body">
                                <span class="label label-success"><?php ea('page_ranks_give_user_email'); ?></span>
                                <br />
                                <br />
                                <input type="text" name="email_rank" class="form-control"  maxlength="100" value="<?php echo $email_rank ?>">
                                <br />
                                <span class="label label-success"><?php ea('page_ranks_table_name_rank'); ?></span>
                                <br />
                                <br />
                               <select name='rank_id'  class='dropdown'>
									<option value='2'><?php ea('page_ranks_2'); ?></option>
                                    <option value='3'><?php ea('page_ranks_3'); ?></option>
								   <option value='1'><?php ea('page_ranks_1'); ?></option>
								</select>
                                <br />
                          
                      </div>
                      <div class="panel-footer"><button type="submit" name="rankutente" class="btn btn-info"><?php ea('page_ranks_button_promote'); ?></button></div>
                   
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

