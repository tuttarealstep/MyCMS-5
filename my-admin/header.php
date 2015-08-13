<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

if(!defined('PAGE_NAME')):
$page_name = '';
else:
$page_name = ': '.PAGE_NAME;
endif;

no_robots();

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{@siteURL@}/app/includes/MyCMS.ico">
    <title>{@siteNAME@}<?php echo $page_name ?></title>
    <?php get_style_script_admin('css'); ?>
    
   	<style type="text/css">
	a { text-decoration:none }
	</style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
{@INDEX_ERROR@}