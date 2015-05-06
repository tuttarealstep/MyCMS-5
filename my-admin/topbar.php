<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */

hide_if_staff_not_logged();

define('PAGE_ID', 'admin_topbar');
?>
<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{@siteURL@}/my-admin/home">{@siteNAME@}</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <!-- <li><a href="{@siteURL@}/my-admin/"><i class="fa fa-user fa-fw"></i> <?php ea('topbar_li_user_profile') ?></a>
                        </li> -->
                        <li><a href="{@siteURL@}/my-admin/settings_general"><i class="fa fa-gear fa-fw"></i> <?php ea('topbar_li_settings') ?></a>
                        </li> 
                        <li><a href="{@siteURL@}"><i class="fa fa-sign-out fa-fw"></i> <?php ea('topbar_li_return_site') ?></a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="{@siteURL@}/my-admin/logout"><i class="fa fa-sign-out fa-fw"></i> <?php ea('topbar_li_logout') ?></a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a <?php if(PAGE_ID == 'admin_home'){ echo 'class="active"'; } ?> href="{@siteURL@}/my-admin/home"><i class="fa fa-dashboard fa-fw"></i> <?php ea('page_home_page_name'); ?></a>
                        </li>
                        <li <?php if(PAGE_ID == 'admin_posts' || PAGE_ID == 'admin_posts_new'){ echo 'class="active"'; } ?>>
                              <a href="#"><i class="fa fa-thumb-tack fa-fw"></i>  <?php ea('page_posts_name'); ?><span class="fa arrow"></span></a>
                             <ul class="nav nav-second-level">
                                <li>
                                   <a <?php if(PAGE_ID == 'admin_posts'){ echo 'class="active"'; } ?> href="{@siteURL@}/my-admin/posts"><?php ea('page_posts_all'); ?></a>
                                </li>
                                 <li>
                                   <a <?php if(PAGE_ID == 'admin_posts_new'){ echo 'class="active"'; } ?> href="{@siteURL@}/my-admin/posts_new"><?php ea('page_posts_new'); ?></a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a <?php if(PAGE_ID == 'admin_comments'){ echo 'class="active"'; } ?> href="{@siteURL@}/my-admin/comments"><i class="fa fa-comment fa-fw"></i> <?php ea('page_comments_page_name'); ?></a>
                        </li>
                        <li>
                            <a <?php if(PAGE_ID == 'admin_category'){ echo 'class="active"'; } ?> href="{@siteURL@}/my-admin/category"><i class="fa fa-cubes fa-fw"></i> <?php ea('page_category_name'); ?></a>
                        </li>
                        <li>
                            <a <?php if(PAGE_ID == 'admin_menu'){ echo 'class="active"'; } ?> href="{@siteURL@}/my-admin/menu"><i class="fa fa-link fa-fw"></i> <?php ea('page_menu_page_name'); ?></a>
                        </li>
                        <li>
                            <a <?php if(PAGE_ID == 'admin_pages'){ echo 'class="active"'; } ?> href="{@siteURL@}/my-admin/my_page"><i class="fa fa-file fa-fw"></i> <?php ea('page_pages_page_name'); ?></a>
                        </li>
                        <li>
                            <a <?php if(PAGE_ID == 'admin_ranks'){ echo 'class="active"'; } ?> href="{@siteURL@}/my-admin/ranks"><i class="fa fa-user fa-fw"></i> <?php ea('page_menu_page_ranks'); ?></a>
                        </li>
                        <li>
                            <a <?php if(PAGE_ID == 'admin_users_bans'){ echo 'class="active"'; } ?> href="{@siteURL@}/my-admin/users_bans"><i class="fa fa-user fa-fw"></i> <?php ea('page_users_bans_page_name'); ?></a>
                        </li>
                        <li>
                            <a <?php if(PAGE_ID == 'theme_manager'){ echo 'class="active"'; } ?> href="{@siteURL@}/my-admin/theme_manager"><i class="fa fa-picture-o fa-fw"></i> <?php ea('page_theme_manager'); ?></a>
                        </li>
                        <li <?php if(PAGE_ID == 'admin_settings_general' || PAGE_ID == 'admin_settings_blog' || PAGE_ID == 'admin_xml_command' || PAGE_ID == 'admin_settings_style'){ echo 'class="active"'; } ?>>
                            <a href="#"><i class="fa fa-gear fa-fw"></i>  <?php ea('page_settings_page_name'); ?><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a <?php if(PAGE_ID == 'admin_settings_general'){ echo 'class="active"'; } ?> href="{@siteURL@}/my-admin/settings_general"><?php ea('page_settings_general'); ?></a>
                                </li>
                                <li>
                                    <a <?php if(PAGE_ID == 'admin_settings_blog'){ echo 'class="active"'; } ?> href="{@siteURL@}/my-admin/settings_blog"><?php ea('page_settings_blog'); ?></a>
                                </li>
                                <li>
                                    <a <?php if(PAGE_ID == 'admin_settings_style'){ echo 'class="active"'; } ?> href="{@siteURL@}/my-admin/settings_style"><?php ea('page_settings_style'); ?></a>
                                </li>
                                <li>
                                    <a <?php if(PAGE_ID == 'admin_xml_command'){ echo 'class="active"'; } ?> href="{@siteURL@}/my-admin/xml_command"><?php ea('page_settings_xml_command'); ?></a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>