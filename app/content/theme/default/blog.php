<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */
define('PAGE_ID', 'blog');
global $my_blog,$my_db,$my_users;


if(isset($_GET['id'])){
	
	global $my_db;
	$postID = my_sql_secure($_GET['id']);
	$filter_id = filter_var($postID, FILTER_SANITIZE_NUMBER_INT);  
	if (filter_var($filter_id, FILTER_VALIDATE_INT)) {  
		if ($my_db->single("SELECT * FROM my_blog WHERE postID = :post_id LIMIT 1", array("post_id"=>$postID))> 0) {
					
			$blog = $my_db->single("SELECT postID FROM my_blog WHERE postID = :post_id LIMIT 1", array("post_id"=>$postID));
			$blogid = $blog;
			$archive = "0";
			define('PAGE_NAME', $my_blog->gets('title', $blogid));
		} else {
	
			$archive = "1";
			define('PAGE_NAME', e('blog_archive_page_name', '1'));
						
		}
	}
} elseif(isset($_GET['category'])){
				$archive = "2";
				$cat = my_sql_secure($_GET['category']);
				define('PAGE_NAME', $cat);
				
} elseif(isset($_GET['author'])){
				$archive = "3";
				$autorepost = remove_space(my_sql_secure($_GET['author']));
				$autorepostspace = add_space(my_sql_secure($_GET['author']));
				define('PAGE_NAME', $autorepost);
				
} elseif(isset($_GET['search'])){
				$archive = "4";
				$stringsearh = remove_space(my_sql_secure($_GET['search']));
				define('PAGE_NAME', $stringsearh);
				
} elseif(isset($_GET['year'])){
						$titolo = my_sql_secure($_GET['title']);
						$anno = my_sql_secure($_GET['year']);
						$mese = my_sql_secure($_GET['month']);
						
						$postIDPER = $my_blog->gets('idFROMpermalink', '/blog/'.$anno.'/'.$mese.'/'.$titolo);
						$searchPOST = $my_db->single("SELECT count(*) FROM my_blog WHERE postID = :postIDPER LIMIT 1", array("postIDPER"=>$postIDPER));
						if ($searchPOST > 0) {
								$blogrow_1 = $my_db->single("SELECT postID FROM my_blog WHERE postID = :postIDPER LIMIT 1", array("postIDPER"=>$postIDPER));
								$blogid = $blogrow_1;
								$archive = "0";
								define('PAGE_NAME', $my_blog->gets('title', $blogid));
						} else {

							$archive = "1";
							define('PAGE_NAME', e('blog_archive_page_name', '1'));

						}

} else {
	
	$archive = "1";	
	define('PAGE_NAME', e('blog_archive_page_name', '1'));
			
}
get_file('header'); //Puoi usare questa funzione anche così: get_file('header', 'nome'); lui caricherà il file header-nome.php

if(get_settings_value('blog_private') == 'false'){
?>
<?php if($archive == "0"){?>

        <div class="container" style="color: #ffffff !important;">

        <div class="row">
            <div class="col-lg-8">

                <h1><?php $my_blog->get('title', $blogid);?></h1>
                <p><?php e('blog_created_by');?> <a href="{@siteURL@}/blog/author/<?php $my_blog->get('authorspace', $blogid);?>"><?php $my_blog->get('author', $blogid);?></a>, <?php e('blog_category');?> <a href="{@siteURL@}/blog/category/<?php $my_blog->get('category', $blogid);?>"><?php $my_blog->get('category', $blogid);?></a>
                </p>
                <h6>
                    <span class="glyphicon glyphicon-time"></span> <?php e('blog_posted');?> <?php $my_blog->get('date', $blogid);?></h6>
                <hr>
             	<?php $my_blog->get('content', $blogid);?>
                
				<?php 
				if(get_settings_value('blog_comments_active') == 'true'){
					
				if(user_logged_in()){ ?>
                <hr>
                <div class="well">
                    <h4 style="color: #000000"><?php e('blog_post_title_comment');?></h4>
                    <form role="form" method="post" action="">
                        <div class="form-group">
                        	<input type = "hidden" name="post_id" value = "<?php echo $blogid;?>" />
                            <textarea name="commento" style=" width:100%;min-width:100%;max-width:100%;height:100px;min-height:100px;max-height:100px;" maxlength="250" class="form-control" rows="3"></textarea>
                        </div>
                         <div class="section-colored text-center">  
                        <button type="submit" name="postCOMMENT" class="btn btn-primary"><?php e('blog_post_comment_button_send');?></button>
                    	</div>
                    </form>
                </div>
                

                <hr>
                <?php } ?>
                <?php
				$commenticount = $my_db->single("SELECT COUNT(*) FROM my_blog_post_comments WHERE postid = :blogid AND enable = '1'", array('blogid'=>$blogid)); 
				if($commenticount > 0){
					
				?>
                <h4><?php e('blog_post_last_25_comments');?></h4>
                <hr>
                <?php
					$postinfo = $my_db->query("SELECT * FROM my_blog_post_comments WHERE postid = :blogid AND enable = '1' ORDER BY id DESC LIMIT 25", array('blogid'=>$blogid));
					$i = 0; foreach($postinfo as $postrow){ $i++; 
					
					$name = $my_users->getInfo($postrow['author'], 'name').' '.$my_users->getInfo($postrow['author'], 'surname');
				?>
                <div class="well well-sm" style="color: #000000 !important;">
                <h4><?php echo $name; ?>:
                    <small><?php echo $postrow['data']; ?></small>
                </h4>
                <p><?php echo $postrow['comments']; ?></p>
				</div>
                <?php
					} 
				} else {
				?>
                    <div class="well well-sm">
                    	<p style="color: #000000"><?php e('blog_post_0_comments');?></p>
                    </div>
                <?php
					}
				?>
                <?php 
				} else {
					
				
				}
				?>
                 

                

            </div>
            
     <div class="section-colored text-center">       
			<?php get_page('blog-bar'); ?>
        </div>

    </div>
        <!-- /.container -->

    </div>
<?php } elseif($archive == "1") { ?>
<div class="section-colored text-center">

        <div class="container">

        <div class="row">
            <div class="col-lg-8">

                <h1><?php e('blog_title_archive'); ?></h1>
               	<?php
					global $my_db;
					$get_archive = $my_db->query("SELECT * FROM my_blog ORDER BY DATE(postDATE) DESC");
					$count = $my_db->single("SELECT * FROM my_blog");
						if($count > 0){
							foreach($get_archive as $row) {
								echo "<li class='list-group-item'><p class='pull-left'>&bull;&nbsp;</p><a class='pull-left' href='{@siteURL@}".add_space($row['postPERMALINK'])."'>".fix_text(htmlspecialchars($row['postTITLE']))."</a> &nbsp;<p class='pull-left'>&raquo;</p> <span class='pull-right' style='color: #808080;'>".$row['postDATE']."</span></li>";
							}
						} else {
							echo "<h5>".e('blog_a_no_posts', '1')."</h5>";
						}
				?>
            </div>
			<?php get_page('blog-bar'); ?>
        </div>

    	</div>
        <!-- /.container -->

</div>
<?php } elseif($archive == "2") { ?>
<div class="section-colored text-center">

        <div class="container">

        <div class="row">
            <div class="col-lg-8">

                <h3 style="color: #ffffff !important;"><?php e('blog_all_posts_in_category');?></h3><h1 style="color: #ffffff !important;"> <?php echo $cat; ?></h1>
                <hr />
                <?php
					$get_archive = $my_db->query("SELECT * FROM my_blog WHERE postCATEGORY = :cat ORDER BY DATE(postDATE) DESC", array('cat'=>$cat));
					$count = $my_db->single("SELECT * FROM my_blog WHERE postCATEGORY = :cat", array('cat'=>$cat));
						if($count > 0){
							foreach ($get_archive as $row) {
								echo "<li class='list-group-item'><p class='pull-left'>&bull;&nbsp;</p><a class='pull-left' href='{@siteURL@}".add_space($row['postPERMALINK'])."'>".fix_text(htmlspecialchars($row['postTITLE']))."</a> &nbsp;<p class='pull-left'>&raquo;</p> <span class='pull-right' style='color: #808080;'>".$row['postDATE']."</span></li>";
							}
						} else {
							echo "<h5>".e('blog_a_no_posts', '1')."</h5>";
						}
				?>
            </div>
			<?php get_page('blog-bar'); ?>
        </div>

    </div>

    </div>
<?php } elseif($archive == "3") { ?>
<div class="section-colored text-center">

        <div class="container">

        <div class="row">
            <div class="col-lg-8">

                <h3 style="color: #ffffff !important;"><?php e('blog_all_posts_by_author');?></h3><h1> <?php echo $autorepost; ?></h1>
                <hr />
                <?php
					$get_archive = $my_db->query("SELECT * FROM my_blog WHERE postAUTHOR = :autorepostspace ORDER BY DATE(postDATE) DESC", array('autorepostspace'=>$autorepostspace));
					$count = $my_db->single("SELECT * FROM my_blog");
						if($count > 0){
							foreach ($get_archive as $row) {
								echo "<li class='list-group-item'><p class='pull-left'>&bull;&nbsp;</p><a class='pull-left' href='{@siteURL@}".add_space($row['postPERMALINK'])."'>".fix_text(htmlspecialchars($row['postTITLE']))."</a> &nbsp;<p class='pull-left'>&raquo;</p> <span class='pull-right' style='color: #808080;'>".$row['postDATE']."</span></li>";
							}
						} else {
							echo "<h5>".e('blog_a_no_posts', '1')."</h5>";
						}
				?>
            </div>
			<?php get_page('blog-bar'); ?>
        </div>

    </div>

    </div>
<?php } elseif($archive == "4") { ?>
<div class="section-colored text-center">

        <div class="container">

        <div class="row">
            <div class="col-lg-8">

                <h3 style="color: #ffffff !important;"><?php e('blog_you_search');?></h3><h1> <?php echo $stringsearh; ?></h1>
                <hr />
                <?php
					$newsearch = $my_db->query("SELECT * FROM my_blog WHERE postTITLE LIKE :stringsearh ORDER BY DATE(postDATE) DESC", array("stringsearh"=>'%'.$stringsearh.'%'));
							foreach ($newsearch as $row) {
								echo "<li class='list-group-item'><p class='pull-left'>&bull;&nbsp;</p><a class='pull-left' href='{@siteURL@}".add_space($row['postPERMALINK'])."'>".fix_text(htmlspecialchars($row['postTITLE']))."</a> &nbsp;<p class='pull-left'>&raquo;</p> <span class='pull-right' style='color: #808080;'>".$row['postDATE']."</span></li>";
							}
				?>
            </div>
			<?php get_page('blog-bar'); ?>
        </div>

    </div>

    </div>
<?php } ?>
<?php } else {?>
<?php } ?>
<?php get_file('footer'); ?>