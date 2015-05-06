<?php
if(empty(PAGE_NAME)){
    $page_title = "{@siteNAME@}";
} else {
    $page_title = "{@siteNAME@} : ".PAGE_NAME;
}

$url_crypted = base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
if(!isset($_GET['return_url'])){ $return_url = ""; } else { $return_url = my_sql_secure(base64_decode($_GET['return_url'])); $return_url_und = my_sql_secure($_GET['return_url']);}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <META name="description" content="{@siteDESCRIPTION@}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $page_title; ?></title>
    {@getSTYLE=css@}
    <?php get_meta_tag(PAGE_ID); ?>
    <link rel="icon" href="{@siteURL@}/app/includes/MyCMS.ico">
</head>

<body>
<div class="container" style="margin-bottom: 50px;">

    <nav class="navbar navbar-default navbar-fixed-bottom">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{@siteURL@}">{@siteNAME@}</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <?php get_menu(); ?>
                </ul>
                <?php if(user_logged_in()){ ?>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" style="color:#E90000" data-toggle="dropdown"><i class="fa fa-user" style="color:#fff"></i><b>{@user_name@} {@user_surname@}</b></a>
                        <ul class="dropdown-menu">
                            <?php if(isStaff()){ ?>
                                <li><a href="{@siteURL@}/my-admin/index"><b><?php e('header_admin_a'); ?></b></a></li>
                            <?php } ?>
                            <li><a href="{@siteURL@}/logout/r/<?php echo $url_crypted?>"><i class="fa fa-sign-out" style="color:#000"></i><b> <?php e('header_logout_a'); ?></b></a></li>
                        </ul>
                    </li>
                </ul>
                <?php } else { ?>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{@siteURL@}/login" style="color:#E90000"><i class="fa fa-user" style="color:#fff"></i><b> <?php e('header_login/registration_button'); ?></b></a></li>
                </ul>
                <?php } ?>
            </div>
        </div>
    </nav>

</div>

{@INDEX_ERROR@}