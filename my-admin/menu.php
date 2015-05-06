<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */
hide_if_staff_not_logged();

global $my_date, $my_db, $my_users, $my_blog;
define('PAGE_ID', 'admin_menu');
define('PAGE_NAME', ea('page_menu_page_name', '1'));

add_style_script_admin('css', '{@siteURL@}/my-admin/css/plugins/dataTables.bootstrap.css');
add_style_script_admin('css', '{@siteURL@}/my-admin/css/icon-picker.min.css');
add_style_script_admin('script', '{@siteURL@}/my-admin/js/iconPicker.min.js');
add_style_script_admin('script', '{@siteURL@}/my-admin/js/jquery-ui.min.js');

get_file_admin('header'); 
get_page_admin('topbar');

get_style_script_admin('script');

if(isset($_POST['newmenu']))
{
	$user_rank = $my_users->getInfo($_SESSION['staff']['id'], 'rank');
	if($user_rank >= 3){
				
	if(!empty($_POST['name'])){
		
				$name = addslashes($_POST['name']);
				$pageNAMEURL = addslashes($_POST['url']);
				$personal_url = addslashes($_POST['personal_url']);
				$selected_icon = addslashes($_POST['selected_icon']);

				if($pageNAMEURL == "empty"){
					
					if(!empty($personal_url)){

						$idpagina = $my_db->single("SELECT pageID_MENU FROM my_page WHERE pageTITLE = '".$pageNAMEURL."' LIMIT 1");

						if(!empty($selected_icon)){
							$my_db->query("INSERT INTO my_menu (menu_name, menu_page_id, menu_link, menu_sort, menu_icon, menu_icon_image) VALUES ('$name', '$idpagina', '$personal_url', '0', 'glyphicon','$selected_icon')");
						} else {
							$my_db->query("INSERT INTO my_menu (menu_name, menu_page_id, menu_link, menu_sort,menu_icon_image) VALUES ('$name', '$idpagina', '$personal_url', '0', '')");
						}
						$info = '<div class="alert alert-success">'.ea('page_menu_add_success', '1').'</div>';
						$name = '';
						$personal_url = '';
					
					} else {
						$info = '<div class="alert alert-danger">'.ea('page_menu_error_empty_personal_url', '1').'</div>';
						$name = addslashes($_POST['name']);
						$personal_url = addslashes($_POST['personal_url']);
					}
					
				} else {


					$idpagina = $my_db->single("SELECT pageID_MENU FROM my_page WHERE pageTITLE = '".$pageNAMEURL."' LIMIT 1");
					$page_url = $my_db->single("SELECT pageURL FROM my_page WHERE pageTITLE = '".$pageNAMEURL."' LIMIT 1");
					if(!empty($selected_icon)){
						$my_db->query("INSERT INTO my_menu (menu_name, menu_page_id, menu_link, menu_sort, menu_icon,menu_icon_image) VALUES ('$name', '$idpagina', '$page_url', '0', 'glyphicon','$selected_icon')");
					} else {
						$my_db->query("INSERT INTO my_menu (menu_name, menu_page_id, menu_link, menu_sort,menu_icon_image) VALUES ('$name', '$idpagina', '$page_url', '0', '')");
					}
					$info = '<div class="alert alert-success">'.ea('page_menu_add_success', '1').'</div>';
					$name = '';
					$personal_url = '';
				}
					
			} else {
				$info = '<div class="alert alert-danger">'.ea('page_menu_error_add_name', '1').'</div>';
				$name = addslashes($_POST['name']);
				$personal_url = addslashes($_POST['personal_url']);
			}
	}
}  
?>
<?php
$user_rank = $my_users->getInfo($_SESSION['staff']['id'], 'rank');
if($user_rank > 2){
?>
<script>
$(document).ready(
function() {
	$("#menuedit").sortable({
		update : function () {
			menulist = $('#menuedit').sortable('serialize');
				$.ajax({
				url: "{@siteURL@}/app/content/ajax/menu_update.php",
				type: "post",
				data: menulist,
					error: function(){
					alert("AJAX ERROR");
					}
				});
			}
		});
	}
);
$(function () {
	$(".icon-picker").iconPicker();
});
</script>
<?php
}
?>
	<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                <?php echo '<br>'.$info.'<br>'; ?>
                    <h1 class="page-header"><?php ea('page_menu_header'); ?></h1> 
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-6">
                	<ol id="menuedit">
                    <?php
						$menu = $my_db->query("SELECT * FROM `my_menu` WHERE `menu_enabled` = '1' ORDER BY `menu_sort` ASC");
						$i = 0; foreach($menu as $row){ $i++; 
						$candelete = $row['menu_can_delete'];
					?>
                    <li id="menu_<?php echo $row['menu_id']; ?>"><div class="alert alert-info"><b><?php echo $row['menu_name']; ?></b><?php if($candelete == '1'): ?> - <a style="color:#F00" href="{@siteURL@}/my-admin/delete-menu/<?php echo $row['menu_id']; ?>"><?php ea('page_menu_delete');?></a><?php endif; ?></div></li>
                    <?php
						}
					?>
					</ol>
              </ul>
                </div>
                <!-- /.col-lg-6 -->
                
                <div class="col-lg-4">
                	<div class="panel panel-default">
                	<div class="panel-heading">
                		<h1 class="panel-title"><?php ea('page_menu_add_new_menu'); ?></h1>
              		</div>
                      <form action="" method="post">
                      <div class="panel-body">
                           
                                <span class="label label-success"><?php ea('page_menu_add_new_menu_name'); ?></span><br /><br />
                                <input type="text" name="name" class="form-control"  maxlength="100" value="<?php echo $name ?>">
                                <br />
                                <span class="label label-success"><?php ea('page_menu_add_new_menu_selectpage'); ?></span>
                                <br />
                                <br />
                                <select name="url" class="form-control">
                                <option value="empty"><?php ea('page_menu_empty_page'); ?></option>
									<?php
                                        $page = $my_db->query("SELECT * FROM my_page WHERE pagePUBLIC = '1'");
                                        $i = 0; foreach($page as $pagerow){ $i++; 
                                    ?>
                                    <option value="<?php echo $pagerow['pageTITLE']; ?>"><?php echo remove_space($pagerow['pageTITLE']); ?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                                <br />
                                <span class="label label-success"><?php ea('page_menu_personal_url'); ?></span>
                                <br />
                                <br />
                                <input type="text" name="personal_url" class="form-control"  maxlength="250" value="<?php echo $personal_url ?>">
                                <br />
								  <span class="label label-success"><?php ea('page_menu_icon'); ?></span>
								  <br />
								  <br />
						  			<input type="text" name="selected_icon" class="icon-picker" />
                          
                      </div>
                      <div class="panel-footer"><button type="submit" name="newmenu" class="btn btn-info"><?php ea('page_menu_add_button'); ?></button></div>
                       </form>
            	</div>
          	</div>
                
               
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->	

</body>

</html>

