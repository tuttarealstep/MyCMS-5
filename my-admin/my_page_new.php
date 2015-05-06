<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

global  $my_db, $my_users, $my_blog;
hide_if_staff_not_logged();

define('PAGE_ID', 'admin_pages_new');
define('PAGE_NAME', ea('page_pages_new', '1'));

add_style_script_admin('css', '{@siteURL@}/my-admin/css/plugins/dataTables.bootstrap.css');
add_style_script_admin('script', '//tinymce.cachefly.net/4.0/tinymce.min.js');

get_file_admin('header');
get_page_admin('topbar');


if(isset($_POST['pages_new_create'])) {
    if(!empty($_POST['pages_title'])){
            $pages_title = addslashes($_POST['pages_title']);
            $pages_content = addslashes($_POST['pages_content']);
            $pages_menu_id = my_generate_random(5).$pages_title;

            $page_url = "{@siteURL@}/".$pages_title;

                $find_url = $my_db->single("SELECT COUNT(*) FROM my_page WHERE pageURL = :simulate_url", array("simulate_url" => $page_url));
                if($find_url > 0){
                    $page_url = "{@siteURL@}/".my_generate_random(5).$pages_title;
                } else {
                }


            $my_db->query("INSERT INTO my_page (pageTITLE,pageURL,pageHTML, pageID_MENU) VALUES ('$pages_title', '$page_url', '$pages_content', '$pages_menu_id')");
            $info = '<div class="row"><div class="alert alert-success">'.ea('page_pages_new_success_created', '1').' <a href="'.$page_url.'">'.ea('page_pages_new_success_show', '1').'</a></div>';

    } else {
        $pages['content'] = $_POST['pages_content'];
        define("INDEX_ERROR", ea('page_pages_new_error_title', '1'));

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
            <h1 class="page-header"><?php ea('page_pages_new_header'); ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <form role="form" method="post" action="">
        <div class="row">
            <div class="col-lg-8">
                <div class="form-group">
                    <label><?php ea('page_pages_new_title'); ?></label>
                    <input type="text" name="pages_title" id="title" class="form-control" maxlength="100" value="<?php echo $pages['title']; ?>">
                </div>
                <br />
                <div class="form-group">
                    <textarea name="pages_content" style="height:300px;"><?php echo $pages['content']; ?></textarea>
                </div>
            </div>
            <!-- /.col-lg-8 -->
            <div class="col-lg-4">
                <div class="form-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="accordion-toggle"><?php ea('page_pages_new_publish'); ?></a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in" style="">
                            <div class="panel-body">
                                <?php ea('page_pages_new_info'); ?>
                            </div>
                        </div>
                        <div class="panel-footer"><button type="submit" name="pages_new_create" class="btn btn-info"><?php ea('page_pages_new_publish_button'); ?></button></div>
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


</body>

</html>

