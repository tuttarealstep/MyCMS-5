<?php
ini_set('max_execution_time', 300);
set_time_limit(0);
/*                     *\
|	MYCMS - TProgram    |
\*                     */

if(isset($_GET['step'])){ $step =  $_GET['step']; } else { $step = '1'; }

session_start();
error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING );
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name='robots' content='noindex,follow' />
<title>MyCMS - Step[<?php echo $step; ?>]</title>
<link href="./css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="navbar navbar-inverse navbar-static-top" role="navigation" style="color:#222">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" style= "color: #fff;" href="index.php">MyCMS</a>
        </div>
	</div>
</div>
<?php if($step == "1"){ ?>
<div class="container">

        <!-- Heading Row -->
        <div class="row">
            <div class="col-md-8">
            <h1>Welcome to the installation of	<b>MyCMS</b>!</h1><br>
            You will need to complete simple steps, Everything else does MyCMS!
            </div>
            <!-- /.col-md-4 -->
        </div>
        <!-- /.row -->
        <hr>
        
        <div class="row">
            <div class="col-lg-12">
                <div class="well">
                   <p class="pull-left">This step will be simple --></p><a class="btn btn-primary pull-right" href="index.php?step=2">Next Step</a><br><br>
                </div>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        
</div>
<?php }elseif($step == "2"){ ?>
<?php

if(isset($_POST['config_button'])){
	
	$config_host = htmlentities($_POST['config_host']); 
	$config_user = htmlentities($_POST['config_user']); 
	$config_password = htmlentities($_POST['config_password']); 
	$config_database = htmlentities($_POST['config_database']); 
	$config_connection = htmlentities($_POST['config_connection']); 
	$site_url_db = htmlentities($_POST['site_url_db']); 
	
	
					try {
                    	$connection1 = new PDO("mysql:host=".$config_host."", $config_user, $config_password);
						$connection1->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
						$sql = "CREATE DATABASE ".$config_database."";
						$connection1->exec($sql);
					} catch (PDOException $e) {
    					$info = '<div class="alert alert-danger">'.$e->getMessage().'</div>';
					}
					$connection1 = NULL;
					try {
                    	$connection = new PDO("mysql:host=".$config_host.";dbname=".$config_database."", $config_user, $config_password);
						$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
					} catch (PDOException $e) {
    					$info = '<div class="alert alert-danger">'.$e->getMessage().'</div>';
					}
 
       
	if($connection){

        $filename = 'MyCMS.sql';
        $templine = '';
        $lines = file($filename);
        foreach ($lines as $line)
        {
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;
            $templine .= $line;
            if (substr(trim($line), -1, 1) == ';')
            {
                try
                {
                    $connection->exec($templine);
                }
                catch (PDOException $e)
                {
                    $info = '<div class="alert alert-danger">'.$e->getMessage().'</div>';
                }
                $templine = '';
            }
        }

        $query =    "INSERT INTO my_cms_settings (settings_id, settings_name, settings_value) VALUES
                    (1, 'site_name', 'MyCMS 5'),
                    (2, 'site_url', '".$site_url_db."'),
                    (3, 'site_template', 'default'),
                    (4, 'site_timezone', 'Europe/Rome'),
                    (5, 'site_language', 'en_US'),
                    (6, 'blog_post_control_comments', '0'),
                    (7, 'site_description', 'Welcome in MyCMS 5'),
                    (8, 'site_maintenance', 'false'),
                    (9, 'blog_private', 'false'),
                    (10, 'blog_comments_active', 'true'),
                    (11, 'blog_comments_approve', 'false'),
                    (12, 'site_use_cache', 'false'),
                    (13, 'site_template_language', 'en_US');";

        try
        {
            $connection->exec($query);
        }
        catch (PDOException $e)
        {
            $info = '<div class="alert alert-danger">'.$e->getMessage().'</div>';
        }

		require('../app/includes/class.security.php');
		
		$file_config = '../app/configuration/my_config.php';
		$file_folder = '../app/configuration/';
		
		
		
		$file_complete = "<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

	//HOSTNAME
	define(\"C_HOST\", \"".$config_host."\");
	
	//DATABASE USER
	define(\"C_USER\", \"".$config_user."\"); 
	
	//DATABASE PASSWORD
	define(\"C_PASSWORD\", \"".$config_password."\"); 
	
	//DATABASE NAME
	define(\"C_DATABASE\", \"".$config_database."\");

//OTHERS
	//MODALITY
	define(\"MY_M_DEBUG\", false);  //If true show all errors

//KEY
	
	define('SESSION_KEY_GENERATE', true);
	define('SESSION_KEY', 'MYCMS_".my_generate_random(6)."');
	define('SECRET_KEY', '".my_generate_random(50)."');
	define('CRYPT_KEY', '".my_generate_random(50)."');
";


		$file_complete_htaccess = '<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule . index.php [L]
</IfModule>
';
		$file_config_htaccess = '../.htaccess';
		$file_folder_htaccess = '../';

        $file_complete_webconfig = '<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>
        <rewrite>
            <rules>
                <rule name="MyCMS Rule" stopProcessing="true">
                    <match url="." ignoreCase="false" />
                    <conditions logicalGrouping="MatchAll">
                        <add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="false" negate="true" />
                    </conditions>
                    <action type="Rewrite" url="/index.php" />
                </rule>
            </rules>
        </rewrite>
    </system.webServer>
</configuration>
';
        $file_config_webconfig = '../web.config';
        $file_folder_webconfig = '../';

		if(file_exists($file_config_htaccess) && is_writable($file_folder_htaccess) || is_writable($file_config_htaccess)){
			$file_1 = @fopen($file_config_htaccess, 'w');
			fwrite($file_1, $file_complete_htaccess);
		}

       if(file_exists($file_config_webconfig) && is_writable($file_folder_webconfig) || is_writable($file_config_webconfig)){
            $file_2 = @fopen($file_config_webconfig, 'w');
            fwrite($file_2, $file_complete_webconfig);
		}

		
		if(file_exists($file_config) && is_writable($file_folder) || is_writable($file_config)){
			
			$file = @fopen($file_config, 'w');
			fwrite($file, $file_complete);
			$_SESSION['step2complete'] = true;
			
			echo("<script>location.href = 'index.php?step=3&config_host=".base64_encode($config_host)."&config_user=".base64_encode($config_user)."&config_password=".base64_encode($config_password)."&config_database=".base64_encode($config_database)."';</script>");
			
		}
		
	}
	
}

?>
<div class="container">

        <!-- Heading Row -->
        <div class="row">
        <?php echo $info; ?>
            <div class="col-md-8">
            <h1><b>Basic</b> Settings!</h1><br>
           	Basic settings to connect to the database!*<b>This file needs permission to read and write, and in next time the cms need this permission for download update/themes!</b>
            </div>
            <!-- /.col-md-4 -->
        </div>
        <!-- /.row -->
        <hr>
        
         <div class="col-md-4">
                <form role="form" method="post" action="">
                <div class="form-group">
                        <label>Host</label>
                        <input type="text" name="config_host" id="config_host" class="form-control" value="localhost" required>
               	</div>
                <div class="form-group">
                        <label>User</label>
                        <input type="text" name="config_user" id="config_user" class="form-control" value="root" required>
               	</div>
                <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="config_password" id="config_password" class="form-control" value="">
               	</div>
                <div class="form-group">
                        <label>Database Name</label>
                        <input type="text" name="config_database" id="config_database" class="form-control" value="my_cms" required>
               	</div>
                 <div class="form-group">
                        <label>Site Url</label>
                        <small>*Enter the address of your website without "/" final.<br> Ex: http://localhost</small>
                        <input type="text" name="site_url_db" id="site_url_db" class="form-control" value="http://localhost" required>
               	</div>
                <br>
         </div>
        
        <hr>
        <div class="row">
            <div class="col-lg-12">
                <div class="well">
                   <p class="pull-left">Next --></p><button type="submit" name="config_button" class="btn btn-primary pull-right">Next Step</button><br><br>
                </div>
            </div>
            <!-- /.col-lg-12 -->
            </form>
        </div>
        
</div>
<?php }elseif($step == "3"){ ?>
<?php if($_SESSION['step2complete'] == true){ ?>
<?php
if(isset($_POST['user_button'])) {

    $config_host = base64_decode($_GET['config_host']);
    $config_user = base64_decode($_GET['config_user']);
    $config_password = base64_decode($_GET['config_password']);
    $config_database = base64_decode($_GET['config_database']);

    $user_name = $_POST['user_name'];
    $user_surname = $_POST['user_surname'];

    if (version_compare(phpversion(), '5.5.0', '>=')){

        $options = [
            'cost' => 8,
            'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
        ];
        
        $user_password = password_hash($_POST['user_password'], PASSWORD_BCRYPT, $options);

    } else {
        $user_password = md5($_POST['user_password']);
    }


	$user_mail = $_POST['user_mail'];
	$ip = $_SERVER['REMOTE_ADDR'];
	
					try {
                    	$connection = new PDO("mysql:host=".$config_host.";dbname=".$config_database."", $config_user, $config_password);
						$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
					} catch (PDOException $e) {
    					$info = '<div class="alert alert-danger">'.$e->getMessage().'</div>';
					}
	if($connection){
		
		
		$query = "INSERT INTO `my_users` (`name`, `surname`, `password`, `mail`, `ip`, `rank`) VALUES ('".$user_name."', '".$user_surname."', '".$user_password."', '".$user_mail."', '".$ip."', '3')";
		
		try
		{	
			$connection->exec($query);
			$_SESSION['step3complete'] = true;
			echo("<script>location.href = 'index.php?step=4';</script>");
			
		}
		catch (PDOException $e)
		{
		 $info = '<div class="alert alert-danger">'.$e->getMessage().'</div>';
		}
	
	}
}
?>
<div class="container">

        <!-- Heading Row -->
        <div class="row">
        <?php echo $info; ?>
            <div class="col-md-8">
            <h1><b>Your Account</b> !</h1><br>
           	<b>This account will be inserted as administrator</b>
            </div>
            <!-- /.col-md-4 -->
        </div>
        <!-- /.row -->
        <hr>
        
         <div class="col-md-4">
                <form role="form" method="post" action="">
                <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="user_name" id="user_name" class="form-control" value="" required>
               	</div>
                <div class="form-group">
                        <label>Surname</label>
                        <input type="text" name="user_surname" id="user_surname" class="form-control" value="" required>
               	</div>
                <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="user_password" id="user_password" class="form-control" value="" required>
               	</div>
                <div class="form-group">
                        <label>Mail</label>
                        <input type="email" name="user_mail" id="user_mail" class="form-control" value="" required>
               	</div>
                <br>
         </div>
        
        <hr>
        <div class="row">
            <div class="col-lg-12">
                <div class="well">
                   <p class="pull-left">Next --></p><button type="submit" name="user_button" class="btn btn-primary pull-right">Finish</button><br><br>
                </div>
            </div>
            <!-- /.col-lg-12 -->
            </form>
        </div>
        
</div>  
<?php } else {
		echo("<script>location.href = 'index.php?step=2';</script>");
	  }  ?>
      
<?php }elseif($step == "4"){ ?>
	<?php if($_SESSION['step2complete'] == true){ ?>
        <?php if($_SESSION['step3complete'] == true){ 
		session_unset();
		session_destroy();
		?>
        	<div class="container">

                <!-- Heading Row -->
                <div class="row">
                <?php echo $info; ?>
                    <div class="col-md-8">
                    <h1><b>Finished</b> !</h1><br>
                    </div>
                    <!-- /.col-md-4 -->
                </div>
                <!-- /.row -->
                <hr>
               	<p>You've completed all of the steps you can now delete the folder: <b><?php echo  dirname( __FILE__ ); ?></b>* !</p>
                <br>
				<small>* Do not rename it, delete the folder with all the files for the security of your web site.</small>
                <hr>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="well">
                           <p class="pull-left">If you have deleted the install folder, press finish ---></p><a class="btn btn-primary pull-right" href="../index">Finish</a><br><br>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                    </form>
                </div>
                
        </div>  
        <?php } else {
				echo("<script>location.href = 'index.php?step=3';</script>");
			  }?> 
    <?php } else {
			echo("<script>location.href = 'index.php?step=2';</script>");
	  	  }  ?> 
<?php }else{ ?>
<div class="container">

        <!-- Heading Row -->
        <div class="row">
            <div class="col-md-4">
            Step not found! 
            </div>
            <!-- /.col-md-4 -->
        </div>
        <!-- /.row -->
        
</div>
<?php } ?>

<script src="./js/jquery-1.10.2.js"></script>
<script src="./js/bootstrap.js"></script>
</body>
</html>
