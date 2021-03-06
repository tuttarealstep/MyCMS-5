<?php
/**
 * MyCMS(TProgram) - Project
 * Date: 31/07/2015 Time: 14:15
 */

no_robots();

hide_if_staff_not_logged();

global $my_db, $my_users, $my_theme;

if(!$my_theme->there_is_new_update()){
    header("location: ".HOST."/my-admin/home");
}

define('PAGE_ID', 'admin_update_page');
define('PAGE_NAME', ea('page_update_page_name', '1'));

get_file_admin('header');

get_style_script_admin('script');

    $user_rank = $my_users->getInfo($_SESSION['staff']['id'], 'rank');
    if($user_rank < 3){
        header("location: ".HOST."/my-admin/home");
    }

//PHP SETTINGS
ini_set('max_execution_time', 3000);
set_time_limit(0);
ignore_user_abort(true);


$progress = 0;
$download_mode = false;


function folder_copy($src, $dst) {
    if (is_dir ( $src )) {
        @mkdir ( $dst, 0777, true );
        $files = scandir ( $src );
        foreach ( $files as $file )
            if ($file != "." && $file != ".." && $file != "tmp" && $file != ".idea" )
                   folder_copy ( "$src/$file", "$dst/$file" );
    } else if (file_exists ( $src ))
        copy ( $src, $dst );
}

function delete_dir($path){
    return is_file($path) ? unlink($path) : array_map(__FUNCTION__, glob($path.'/*')) == @rmdir($path);
}

function back_up_cms_folder(){

    $copy_path = P_PATH_S . "/tmp/" . "my_cms_backup";
    folder_copy(P_PATH_S, $copy_path);

    $zip = new ZipArchive();
    $zip->open(P_PATH_S . '/tmp/my_cms_backup_'.date('Y-m-d__H-i-s', time()).'_'.SECRET_KEY.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($copy_path), RecursiveIteratorIterator::LEAVES_ONLY);
    foreach($files as $name => $file){
        if(!$file->isDir()){
            $file_path = $file->getRealPath();
            $relative_path = substr($file_path, strlen($copy_path) + 1);
            $zip->addFile($file_path, $relative_path);
        }
    }
    $zip->close();

    unlink(P_PATH_S . "/tmp/my_cms_backup/.htaccess");
    delete_dir(P_PATH_S . "/tmp/my_cms_backup");

}

function remove_dir_for_update($dir) {
            if (is_dir($dir)) {
                $files = scandir($dir);
                foreach ($files as $file)
                    if ($file != "." && $file != "..") remove_dir("$dir/$file");
                rmdir($dir);
            }
}

function remove_for_update($src = P_PATH_S){
        if($src != P_PATH_S . "/app/configuration" && $src != P_PATH_S . "/app/content/theme" ) {
            if ($src != P_PATH_S . "/test") { //TMP
                if (is_dir($src)) {
                    $files = scandir($src);
                    foreach ($files as $file)
                        if ($file != "." && $file != ".." && $file != "tmp" && $file != ".idea")
                            remove_for_update("$src/$file");
                } else if (file_exists($src))
                    unlink($src);
            }
        }
}

function download_update(){
    global $my_theme;
    $info = $my_theme->there_is_new_update(false);
    if($info[2] != '' && $info[3] != ''){
        if($info[1] == 'all_update'){
            $filename_cms = "my_cms_".$info[2];
            $filename_db = "my_cms_database_".$info[3];
        }
    }
    if($info[2] != '' && $info[3] == ''){
        if($info[1] == 'core_update'){
            $filename_cms = "my_cms_".$info[2];
            $filename_db = '';
        }
    }
    if($info[2] == '' && $info[3] != ''){
        if($info[1] == 'db_update'){
            $filename_cms = '';
            $filename_db = "my_cms_database_".$info[3];
        }
    }

    $tmp_path = P_PATH_S . "/tmp";
    $download_url =  MY_CMS_WEBSITE . "/update/";
    $remove_folder = false;

    if($filename_cms != '' && $filename_db != ''){
        $info_cms = file_get_contents($download_url . $filename_cms . '.zip' );
        $info_db = file_get_contents($download_url . $filename_db . '.zip' );
        file_put_contents($tmp_path . '/' . $filename_cms. '.zip', $info_cms);
        file_put_contents($tmp_path . '/' . $filename_db . '.zip' , $info_db);

        $zip_extract = new ZipArchive;
        if ($zip_extract->open($tmp_path . '/' . $filename_cms . '.zip') === TRUE) {
            $zip_extract->extractTo($tmp_path . '/' . $filename_cms);
            $zip_extract->close();
            $remove_folder_1 = true;
        } else {
            $remove_folder_1 = false;
            //Error
        }
        if ($zip_extract->open($tmp_path . '/' . $filename_db . '.zip') === TRUE) {
            $zip_extract->extractTo($tmp_path . '/' . $filename_db);
            $zip_extract->close();
            $remove_folder_2 = true;
        } else {
            $remove_folder_2 = false;
            //Error
        }
        if($remove_folder_1 == true && $remove_folder_2 == true){
            unlink($tmp_path . '/' . $filename_db . '.zip');
            unlink($tmp_path . '/' . $filename_cms . '.zip');
            $remove_folder = true;
        }

    }
    if($filename_cms != '' && $filename_db == ''){
        $info_cms = file_get_contents($download_url . $filename_cms . '.zip' );
        file_put_contents($tmp_path . '/' . $filename_cms . '.zip' , $info_cms);
        $zip_extract = new ZipArchive;
        if ($zip_extract->open($tmp_path . '/' . $filename_cms . '.zip') === TRUE) {
            $zip_extract->extractTo($tmp_path . '/' . $filename_cms);
            $zip_extract->close();
            $remove_folder = true;
            unlink($tmp_path . '/' . $filename_cms . '.zip');
        } else {
            //Error
        }

    }
    if($filename_cms == '' && $filename_db != ''){
        $info_db = file_get_contents($download_url . $filename_db . '.zip' );
        file_put_contents($tmp_path . '/' . $filename_db . '.zip' , $info_db);
        $zip_extract = new ZipArchive;
        if ($zip_extract->open($tmp_path . '/' . $filename_db . '.zip' ) === TRUE) {
            $zip_extract->extractTo($tmp_path . '/' . $filename_db);
            $zip_extract->close();
            $remove_folder = true;
            unlink($tmp_path . '/' . $filename_db . '.zip');
        } else {
            //Error
        }

    }

    if($remove_folder){
        remove_for_update(P_PATH_S . "/my-admin");
        remove_for_update(P_PATH_S . "/app/includes");
        remove_for_update(P_PATH_S . "/app/content/ajax");

        remove_dir_for_update(P_PATH_S . "/my-admin");
        remove_dir_for_update(P_PATH_S . "/app/includes");
        remove_dir_for_update(P_PATH_S . "/app/content/ajax");
        folder_copy($tmp_path . '/' . $filename_cms, P_PATH_S);

        remove_for_update($tmp_path . '/' . $filename_cms);
        remove_dir_for_update($tmp_path . '/' . $filename_cms);

    }


}

if(isset($_POST['update_button'])){
       /* <script>alert("<?php ea('page_update_alert') ?>");</script> */
    $download_mode = true;
    back_up_cms_folder(); //Make backup
    download_update(); //download update
    sleep(2);
    echo '<meta http-equiv="refresh" content="5;url='.HOST.'/my-admin/home"> ';
}
?>
<body>
    <div id="wrapper">
        <div class="row" style="margin-top: 50px;">
        <div class="col-lg-6" style="float: none; margin: auto;">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form action="" method="post">
                    <div class="row" style="margin-left: 10px; margin-right: 10px;">
                        <img src="{@siteURL@}/app/includes/MyCMS_logo.png" style="height: 60px; width: 150px;"/>
                        <?php if(!$download_mode){ ?>
                            <a href="{@siteURL@}/my-admin/home" style="float: right; margin-top: 26px;"><?php ea('page_update_return_back') ?></a>
                            <input name="update_button" type="submit" style="float: right; margin-top: 20px; margin-right: 20px;" class="btn btn-info" value="<?php ea('page_update_update_button') ?>" />
                        <?php } ?>
                    </div>
                    </form>
                    <?php if($download_mode){ ?>
                    <div class="row" style="margin-left: 10px; margin-right: 10px; margin-top: 10px;">
                        <div class="col-lg-12">
                            <?php ea('page_update_info_process') ?>
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="row" style="margin-left: 10px; margin-right: 10px; margin-top: 10px;">
                        <div class="alert alert-danger"><span class="badge" style="background-color: red">!</span> <b>MyCMS Update System is in development! (This file need 777 permission)</b></div>
                        <div class="col-lg-6">
                        <?php
                            $info = $my_theme->there_is_new_update(false);
                            if($info[2] != '' && $info[3] != ''){
                                if($info[1] == 'all_update'){
                                    echo "<h2>MyCMS <b>$info[2]</b> Database <b>$info[3]</b></h2>(Database - Core)";
                                }
                            }
                            if($info[2] != '' && $info[3] == ''){
                                if($info[1] == 'core_update'){
                                    echo "<h2>MyCMS <b>$info[2]</b> </h2>(Core)";
                                }
                            }
                            if($info[2] == '' && $info[3] != ''){
                                if($info[1] == 'db_update'){
                                    echo "<h2>Database <b>$info[3]</b> </h2>(Database)";
                                }
                            }
                        ?>
                        </div>
                        <div class="col-lg-6">
                            <h2><b><?php ea('page_update_changelog') ?></b></h2>
                            <ul>
                            <?php
                            foreach($info[4] as $info_row){ ?>
                                <?php if(!empty($info_row[$info[3]])) { ?>  <li><span class="badge">DB <?php echo $info[3]; ?></span> <?php echo $info_row[$info[3]]; ?></li> <?php } ?>
                                <?php if(!empty($info_row[$info[2]])) { ?>  <li><span class="badge">CMS <?php echo $info[2]; ?></span> <?php echo $info_row[$info[2]]; ?></li> <?php } ?>
                            <?php } ?>
                            </ul>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php get_file_admin('footer'); ?>
</body>
</html>