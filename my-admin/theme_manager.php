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

if(isset($_GET['remove'])) {
    if(is_numeric($_GET['remove'])){
        $user_rank = $my_users->getInfo($_SESSION['staff']['id'], 'rank');
        if($user_rank >= 3) {
            $info = $my_db->row("SELECT * FROM my_style WHERE style_id = :style_id LIMIT 1", ['style_id' => my_sql_secure($_GET['remove'])]);
            $style_path_name = $info['style_path_name'];

            if ($info['style_enable_remove'] == '1') {

                remove_dir(FILE . '/app/content/theme/' . $style_path_name);
                $my_db->query('DELETE FROM my_style WHERE style_id = :style_id LIMIT 1', array('style_id' => my_sql_secure($_GET['remove'])));


                $site_language = get_settings_value('site_language');

                if (save_settings('site_template', 'my_cms_default') == false) {
                    define("INDEX_ERROR", ea('error_page_settings_general_save', '1'));
                };
                if (save_settings('site_template_language', $site_language) == false) {
                    define("INDEX_ERROR", ea('error_page_settings_general_save', '1'));
                };

            }

            header("location: ".HOST."/my-admin/theme_manager");

        }
    }
}

$info_page = false;

if(isset($_GET['info'])) {
    if (is_numeric($_GET['info'])) {
        $info_page = true;
        $info_id = my_sql_secure($_GET['info']);
    }
}

if(isset($_POST['set_theme'])) {

    if(staff_logged_in()){
        $user_rank = $my_users->getInfo($_SESSION['staff']['id'], 'rank');
        if($user_rank >= 3){
            if(isset($_POST['style_path_name'])) {
                $style_path_name = my_sql_secure($_POST['style_path_name']);
                    if(save_settings('site_template', $style_path_name) == false){ define("INDEX_ERROR", ea('error_page_settings_general_save', '1')); };
                }
        }
    }

}

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

<script src="<?php echo HOST; ?>/my-admin/js/codemirror.js"></script>
<link rel="stylesheet" href="<?php echo HOST; ?>/my-admin/css/codemirror.css">
<script src="<?php echo HOST; ?>/my-admin/codemirror/mode/htmlmixed/htmlmixed.js"></script>
<script src="<?php echo HOST; ?>/my-admin/codemirror/mode/xml/xml.js"></script>
<script src="<?php echo HOST; ?>/my-admin/codemirror/mode/javascript/javascript.js"></script>
<script src="<?php echo HOST; ?>/my-admin/codemirror/mode/php/php.js"></script>
<script src="http://codemirror.net/addon/edit/matchbrackets.js"></script>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <?php echo '<br>'.$info.'<br>'; ?>
            <h1 class="page-header"><?php ea('page_theme_manager_header'); ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <?php if($info_page){ ?>
            <?php
            $info = $my_db->row("SELECT * FROM my_style WHERE style_id = :style_id LIMIT 1", ['style_id' => $info_id]);

            $info_update = $my_theme->theme_update($info['style_version'], $info['style_json_file_url']);
            if($info_update[0] == true){
                if($info_update[2] == true){
                    $new_update = false;//true
                    $new_update_new_cms = false;
                } else {
                    $new_update = false;
                    $new_update_new_cms = false;//true
                }
            } else {
                $new_update = false;
            }
            ?>
        <div class="col-lg-8" id="file_editor">
                <div class="col-lg-4" id="thumb">
                    <div class="well">
                        <div class="thumbnail">
                            <img src="{@siteURL@}/app/content/theme/<?php echo $info['style_path_name'] ?>/screen.png?<?php echo time(); ?>" alt="<?php echo $info['style_name']; ?>">
                        </div>
                        <center><h3><?php echo $info['style_name']; ?></h3></center>
                    </div>
                </div>
                <script>
                    function show_folder(folder) {
                        var theme =  "<?php echo s_crypt($info['style_path_name']); ?>";
                        <?php
                            $user_rank = $my_users->getInfo($_SESSION['staff']['id'], 'rank');
                            if($user_rank >= 3){
                        ?>

                        if (folder == "") {
                            return;
                        } else {
                            if (window.XMLHttpRequest) {
                                xmlhttp = new XMLHttpRequest();
                            } else {
                                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                            }
                            xmlhttp.onreadystatechange = function() {
                                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                    if(xmlhttp.responseText == ""){

                                    } else {
                                        document.getElementById("file_manager").innerHTML = xmlhttp.responseText;
                                        document.getElementById("thumb").className = 'col-lg-4';
                                        document.getElementById("div_file").className = 'col-lg-8';
                                        document.getElementById("file_editor").className = 'col-lg-8';
                                        document.getElementById("file_button").className = 'col-lg-4';


                                        document.getElementById("info-theme").className = "list-group";
                                        document.getElementById("info-theme-button").className = "well well-sm";

                                    }
                                }
                            }
                            xmlhttp.open("GET","{@siteURL@}/app/content/ajax/theme_manager_file.php?folder="+folder+"&theme="+theme,true);
                            xmlhttp.send();
                        }
                        <?php
                            }
                        ?>
                    }
                    function go_to_folder_home() {
                        var theme =  "<?php echo s_crypt($info['style_path_name']); ?>";
                        <?php
                            $user_rank = $my_users->getInfo($_SESSION['staff']['id'], 'rank');
                            if($user_rank >= 3){
                        ?>
                            if (window.XMLHttpRequest) {
                                xmlhttp = new XMLHttpRequest();
                            } else {
                                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                            }
                            xmlhttp.onreadystatechange = function() {
                                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                    if(xmlhttp.responseText == ""){

                                    } else {
                                        document.getElementById("file_manager").innerHTML = xmlhttp.responseText;
                                        document.getElementById("thumb").className = 'col-lg-4';
                                        document.getElementById("div_file").className = 'col-lg-8';
                                        document.getElementById("file_editor").className = 'col-lg-8';
                                        document.getElementById("file_button").className = 'col-lg-4';

                                        document.getElementById("info-theme").className = "list-group";
                                        document.getElementById("info-theme-button").className = "well well-sm";

                                    }
                                }
                            }
                            xmlhttp.open("GET","{@siteURL@}/app/content/ajax/theme_manager_file.php?folder=home_folder--theme&theme="+theme,true);
                            xmlhttp.send();
                        <?php
                            }
                        ?>
                    }
                    function show_file(file, folder) {
                        var theme =  "<?php echo s_crypt($info['style_path_name']); ?>";
                        <?php
                            $user_rank = $my_users->getInfo($_SESSION['staff']['id'], 'rank');
                            if($user_rank >= 3){
                        ?>
                        if (window.XMLHttpRequest) {
                            xmlhttp = new XMLHttpRequest();
                        } else {
                            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        xmlhttp.onreadystatechange = function() {
                            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                if(xmlhttp.responseText == ""){

                                } else {
                                    document.getElementById("thumb").className = 'hidden';
                                    document.getElementById("div_file").className = 'col-lg-0';
                                    document.getElementById("file_editor").className = 'col-lg-10';
                                    document.getElementById("file_button").className = 'col-lg-2';
                                    document.getElementById("file_manager").innerHTML = xmlhttp.responseText;

                                    document.getElementById("info-theme").className = 'hidden';
                                    document.getElementById("info-theme-button").className = 'hidden';

                                    eval(document.getElementById('js_to_ex').innerHTML);
                                }
                            }
                        }
                        xmlhttp.open("GET","{@siteURL@}/app/content/ajax/theme_manager_file.php?folder="+folder+"&theme="+theme+"&file="+file,true);
                        xmlhttp.send();
                        <?php
                            }
                        ?>
                    }
                </script>
                <div class="col-lg-8" id="div_file">
                    <div class="well">
                        <ul class="list-group" id="file_manager">
                           <?php
                            $file_dir = FILE . '/app/content/theme/' . $info['style_path_name'];
                            $file_and_dir_array = array_diff(scandir($file_dir, 0), array('..', '.'));
                            $dir_array = [];
                            $file_array = [];
                            foreach($file_and_dir_array as $all_row){
                                if(is_dir($file_dir.'/'.$all_row)){
                                    $dir_array[] = $all_row;
                                } else {
                                    $file_array[] = $all_row;
                                }
                            }
                            foreach($dir_array as $dir_row){
                            ?>
                                <li class="list-group-item"><i class="fa fa-folder"></i> <a href="#<?php echo $dir_row;?>" style="color: #563d7c;" onclick="show_folder('<?php echo s_crypt($dir_row);?>')"><b><?php echo $dir_row;?></b></a></li>
                            <?php }
                            foreach($file_array as $file_row){ ?>
                                <li class="list-group-item"><i class="fa fa-file"></i>  <a href="#<?php echo $file_row;?>" onclick="show_file('<?php echo s_crypt($file_row);?>')"><?php echo $file_row;?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
        </div>
            <div class="col-lg-4" id="file_button">
                <div class="well well-sm">
                    <ul class="list-group" id="info-theme">
                        <li class="list-group-item">
                            <span class="badge"><?php echo $info['style_version']; ?></span>
                            <?php ea('page_theme_manager_version_label'); ?>
                        </li>
                        <li class="list-group-item">
                            <span class="badge"><?php echo $info['style_author']; ?></span>
                            <?php ea('page_theme_manager_author_label'); ?>
                        </li>
                    </ul>
                </div>
                <div class="well well-sm" style="height: 52px;" id="info-theme-button">
                    <?php if($info['style_enable_remove'] == '1' ) { ?> <a style="position: absolute;" href="{@siteURL@}/my-admin/theme_manager/remove/<?php echo $info['style_id'] ?>" class="btn btn-sm btn-danger" role="button"><?php ea('page_theme_manager_button_remove'); ?></a> <?php } ?>
                    <form action="" method="post">
                        <input type="hidden" name="style_path_name" value="<?php echo $info['style_path_name']; ?>" />
                        <button style="float: right" type="submit" name="set_theme" class="btn btn-info"><?php ea('page_theme_manager_set_button'); ?></button>
                    </form>
                </div>
                <?php if($new_update == true) { ?>
                <div class="well well-sm" style="height: 52px;">
                  Ciao
                </div>
                <?php } ?>

                <?php if($new_update_new_cms == true) { ?>
                    <div class="well well-sm" style="height: 52px;">
                        Ciao nuova
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
        <div class="col-lg-8">
            <ul>
                <?php
                $temp = $my_db->query("SELECT * FROM my_style ORDER BY style_id DESC");
                $i = 0; foreach($temp as $template){ $i++;
                    ?>
                    <div class="col-sm-6 col-md-4">
                        <div class="thumbnail" style="height: 320px;">
                            <img src="{@siteURL@}/app/content/theme/<?php echo $template['style_path_name'] ?>/screen.png?<?php echo time(); ?>" alt="<?php echo $template['style_name']; ?>">
                            <div class="caption">
                                <h4><?php echo $template['style_name']; ?></h4>
                                <p><?php ea('page_theme_manager_theme_by'); ?> <?php echo $template['style_author']; ?></p>
                                <p><a href="{@siteURL@}/my-admin/theme_manager/info/<?php echo $template['style_id'] ?>" class="btn btn-sm btn-primary" role="button"><?php ea('page_theme_manager_button_info'); ?></a> <?php if($template['style_enable_remove'] == '1' ) { ?> <a href="{@siteURL@}/my-admin/theme_manager/remove/<?php echo $template['style_id'] ?>" class="btn btn-sm btn-danger" role="button"><?php ea('page_theme_manager_button_remove'); ?></a> <?php } ?></p>
                            </div>
                        </div>
                    </div>
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
        <?php } ?>
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->
<?php get_file_admin('footer'); ?>

</body>

</html>