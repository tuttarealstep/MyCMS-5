<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

global $my_db, $my_users, $my_blog;
hide_if_staff_not_logged();

define('PAGE_ID', 'admin_posts_edit');
define('PAGE_NAME', ea('admin_posts_edit', '1'));

add_style_script_admin('css', '{@siteURL@}/my-admin/css/plugins/dataTables.bootstrap.css');
add_style_script_admin('script', '//tinymce.cachefly.net/4.0/tinymce.min.js');

get_file_admin('header'); 
get_page_admin('topbar'); 

if(isset($_GET['id'])){
	
	if(is_numeric($_GET['id'])){
			if($my_db->single("SELECT count(*) FROM my_blog WHERE postID = '".$_GET['id']."' LIMIT 1") > 0) {
				$postid = my_sql_secure($_GET['id']);
				
				$posts['title'] = $my_blog->gets('title', $postid);
				$posts['content'] = $my_blog->gets('content', $postid);
				$posts['permalink'] = $my_blog->gets('permalink', $postid);
				$posts_category = $my_blog->gets('category', $postid);
			}
	} else {
		header('Location: '.HOST.'/my-admin/home');
		exit();
	}
	
} else {
	
header('Location: '.HOST.'/my-admin/home');
exit();

}


if(isset($_POST['posts_new_edit_button'])) { 
	if(!empty($_POST['posts_title'])){
			if(!empty($_POST['posts_content'])){
			$user_rank = $my_users->getInfo($_SESSION['staff']['id'], 'rank');
			if($user_rank >= 2){
				$posts_title = addslashes($_POST['posts_title']);
				$posts_content = addslashes($_POST['posts_content']);
				$date = date('d/m/Y H.i.s', time());
				$category = addslashes($_POST['category']);
				$author = $my_users->getInfo($_SESSION['staff']['id'], 'name').'_'.$my_users->getInfo($_SESSION['staff']['id'], 'surname');
				$permalink = $posts['permalink'];
				/*$finder = $my_blog->permalinkfinder($permalink);
				if($finder == true){
					
					$i = 1;
					while ($my_blog->permalinkfinder($permalink.'_'.$i) == true):
						
						$i++;
						
					endwhile;
					
					$permalink = $permalink.'_'.$i;
					
				} */
				
				$my_db->query("UPDATE my_blog SET postTITLE = '$posts_title', postCONT = '$posts_content', postCATEGORY = '$category', postPOSTED = '1', postPERMALINK = '$permalink' WHERE postID = '".$postid."'");;
				
				$info = '<div class="row"><div class="alert alert-success">'.ea('page_posts_edit_new_success_posted', '1').' <a href="'.$permalink.'">'.ea('page_posts_edit_new_success_show', '1').'</a></div>';
				
				$posts['title'] = $_POST['posts_title'];
				$posts['content'] = $_POST['posts_content'];
				$posts_category = $_POST['category'];
			}
			} else {
				define("INDEX_ERROR", ea('page_posts_edit_new_error_content', '1'));
				$posts['title'] = $_POST['posts_title'];
				$posts['content'] = $_POST['posts_content'];
				$posts_category = $_POST['category'];
				
			}
		} else {
			$posts['content'] = $_POST['posts_content'];
			$posts_category = $_POST['category'];
			define("INDEX_ERROR", ea('page_posts_edit_new_error_title', '1'));
			
		}
}
get_style_script_admin('script');
?>
<script type="text/javascript">
    tinymce.init({
        selector: "textarea",
        language_url : '{@siteURL@}/my-admin/languages/{@siteLANGUAGE@}.js',
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste textcolor"
        ],

        toolbar: "insertfile undo redo | styleselect forecolor backcolor |  bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        autosave_ask_before_unload: false
    });
</script>
<style>
    .panel-heading .accordion-toggle:after {
        font-family: 'Glyphicons Halflings';
        content: "\e114";
        float: right;
        color: grey;
    }
    .panel-heading .accordion-toggle.collapsed:after {
        content: "\e080";
    }
</style>
	<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                	<?php echo '<br>'.$info.'<br>'; ?>
                    <h1 class="page-header"><?php ea('page_posts_edit_new_header'); ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <form role="form" method="post" action="">
            <div class="row">
               	<div class="col-lg-8">
               		<div class="form-group">
                        <label><?php ea('page_posts_edit_new_title'); ?></label>
                        <input type="text" name="posts_title" id="title" class="form-control" maxlength="100" value="<?php echo $posts['title']; ?>">
               		</div>
                    <br />
                    <div class="form-group">
                         <textarea name="posts_content" style="height:300px;"><?php echo $posts['content']; ?></textarea>
               		</div>
                </div>
                <!-- /.col-lg-8 -->
                <div class="col-lg-4">
                       <div class="form-group">
                       		<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="accordion-toggle"><?php ea('page_posts_edit_new_publish'); ?></a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in" style="">
                                        <div class="panel-body">
                                        <span class="label label-danger"><?php ea('page_posts_edit_new_permalink'); ?></span><br />
                                        <p id="msg" style="word-wrap: break-word; ">{@siteURL@}/blog/<?php echo date('Y', time());?>/<?php echo date('m', time());?>/<?php echo $posts['title']; ?></p> <br />
                                        <small>*<?php ea('page_posts_edit_new_permalink_info'); ?></small>
                                        </div>
                                    </div>
                                     <div class="panel-footer"><button type="submit" name="posts_new_edit_button" class="btn btn-info"><?php ea('page_posts_edit_new_publish_button'); ?></button></div>
                        	</div>
               			</div>
                        <div class="form-group">
                       		<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="accordion-toggle"><?php ea('page_posts_edit_new_category'); ?></a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse in" style="">
                                        <div class="panel-body">
                                        <span class="label label-warning"><?php ea('page_posts_edit_new_select_category'); ?></span><br /><br />
                                        <select name="category" class="form-control">
											<?php
                                                $cat = $my_db->query("SELECT * FROM my_blog_category");
                                                $i = 0; foreach($cat as $category){ $i++;
                                            ?>
                                            <option <?php if($posts_category == $category['catNAME']){ echo 'selected=""'; }?> value="<?php echo $category['catNAME']; ?>"><?php echo $category['catNAME']; ?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                        </div>
                                    </div>
                        	</div>
               			</div>
                </div>

            </div>
            </form>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->


<script type="text/javascript">
    $("#title").keyup(function() {

        var text = '{@siteURL@}/blog/<?php echo date('Y', time());?>/<?php echo date('m', time());?>/';

        var replaced = $("#title").val();


        $('#msg').html(text += replaced.replace(/\s/g, '_'));

    });

</script>
</body>

</html>

