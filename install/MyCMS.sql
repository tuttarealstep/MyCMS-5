SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `my_blog` (
`postID` int(11) NOT NULL,
  `postTITLE` varchar(255) NOT NULL,
  `postCONT` text NOT NULL,
  `postDATE` datetime NOT NULL,
  `postAUTHOR` varchar(255) NOT NULL,
  `postCATEGORY` varchar(200) NOT NULL,
  `postPOSTED` enum('0','1') NOT NULL,
  `postPERMALINK` varchar(200) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `my_blog_category` (
`catID` int(11) NOT NULL,
  `catNAME` varchar(100) NOT NULL,
  `catDESCRIPTION` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `my_blog_category` (`catID`, `catNAME`, `catDESCRIPTION`) VALUES
(1, 'MyCMS', 'Mycms');

CREATE TABLE IF NOT EXISTS `my_blog_post_comments` (
`id` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `comments` varchar(250) NOT NULL,
  `postid` int(11) NOT NULL,
  `date` varchar(100) NOT NULL,
  `enable` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `my_cms_settings` (
`settings_id` int(11) NOT NULL,
  `settings_name` varchar(100) NOT NULL,
  `settings_value` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `my_language` (
`language_id` int(11) NOT NULL,
  `language_name` varchar(100) NOT NULL,
  `language_language` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `my_language` (`language_id`, `language_name`, `language_language`) VALUES
(1, 'Italiano - Italian', 'it_IT'),
(2, 'English - English', 'en_US');

CREATE TABLE IF NOT EXISTS `my_menu` (
`menu_id` int(11) NOT NULL,
  `menu_name` varchar(20) NOT NULL,
  `menu_page_id` varchar(50) NOT NULL,
  `menu_link` varchar(255) NOT NULL,
  `menu_icon` enum('fa','glyphicon','false') NOT NULL DEFAULT 'false',
  `menu_icon_image` varchar(100) NOT NULL,
  `menu_dropdown` enum('0','1') NOT NULL DEFAULT '0',
  `menu_dropdown_parent` int(11) NOT NULL DEFAULT '0',
  `menu_sort` int(11) NOT NULL,
  `menu_enabled` enum('1','0') NOT NULL DEFAULT '1',
  `menu_can_delete` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `my_menu` (`menu_id`, `menu_name`, `menu_page_id`, `menu_link`, `menu_icon`, `menu_icon_image`, `menu_dropdown`, `menu_dropdown_parent`, `menu_sort`, `menu_enabled`, `menu_can_delete`) VALUES
(1, 'Home', 'index', '{@siteURL@}', 'glyphicon', 'home', '0', 0, 0, '1', '1'),
(2, 'Blog', 'blog', '{@siteURL@}/blog', 'glyphicon', 'comment', '0', 0, 1, '1', '0');

CREATE TABLE IF NOT EXISTS `my_page` (
`pageID` int(11) NOT NULL,
  `pageTITLE` varchar(200) NOT NULL,
  `pageURL` varchar(255) NOT NULL,
  `pagePUBLIC` enum('0','1') NOT NULL DEFAULT '1',
  `pageID_MENU` varchar(200) NOT NULL,
  `pageINTHEME` enum('0','1') NOT NULL DEFAULT '0',
  `pageHTML` text,
  `pageCANDELETE` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `my_page`
--

INSERT INTO `my_page` (`pageID`, `pageTITLE`, `pageURL`, `pagePUBLIC`, `pageID_MENU`, `pageINTHEME`, `pageHTML`, `pageCANDELETE`) VALUES
(1, 'Index', '{@siteURL@}/index', '1', 'index', '0', '<div class="container"  style="color: #ffffff">          <!-- Heading Row -->         <div class="row">             <div class="col-md-12">                 <h1 style="font-size: 50px; text-align: center;">{@my_cms_welcome_h1@}</h1>             </div>             <!-- /.col-md-12 -->         </div>         <!-- /.row -->     </div>', '1'),
(2, 'Blog', '{@siteURL@}/blog', '1', 'blog', '1', NULL, '0');

CREATE TABLE IF NOT EXISTS `my_security_cookie` (
`cookie_id` int(11) NOT NULL,
  `cookie_name` varchar(100) NOT NULL,
  `cookie_value` varchar(300) NOT NULL,
  `cookie_user` int(11) NOT NULL,
  `cookie_expire` varchar(100) NOT NULL,
  `cookie_agent` varchar(200) NOT NULL,
  `cookie_ip` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `my_style` (
`style_id` int(11) NOT NULL,
  `style_name` varchar(100) NOT NULL,
  `style_author` varchar(200) NOT NULL,
  `style_path` varchar(200) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `my_style` (`style_id`, `style_name`, `style_author`, `style_path`) VALUES
(1, 'default', 'MyCMS', 'default');

CREATE TABLE IF NOT EXISTS `my_users` (
`id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `rank` int(10) NOT NULL DEFAULT '1',
  `last_access` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `my_users_banned` (
`id` int(11) NOT NULL,
  `user_ip` varchar(100) NOT NULL,
  `expire_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `my_blog`
 ADD PRIMARY KEY (`postID`);

ALTER TABLE `my_blog_category`
 ADD PRIMARY KEY (`catID`);

ALTER TABLE `my_blog_post_comments`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `my_cms_settings`
 ADD PRIMARY KEY (`settings_id`);

ALTER TABLE `my_language`
 ADD PRIMARY KEY (`language_id`);

ALTER TABLE `my_menu`
 ADD PRIMARY KEY (`menu_id`);

ALTER TABLE `my_page`
 ADD PRIMARY KEY (`pageID`);

ALTER TABLE `my_security_cookie`
 ADD PRIMARY KEY (`cookie_id`);

ALTER TABLE `my_style`
 ADD PRIMARY KEY (`style_id`);

ALTER TABLE `my_users`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `my_users_banned`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `my_blog`
MODIFY `postID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `my_blog_category`
MODIFY `catID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;

ALTER TABLE `my_blog_post_comments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `my_cms_settings`
MODIFY `settings_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;

ALTER TABLE `my_language`
MODIFY `language_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;

ALTER TABLE `my_menu`
MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;

ALTER TABLE `my_page`
MODIFY `pageID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;

ALTER TABLE `my_security_cookie`
MODIFY `cookie_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `my_style`
MODIFY `style_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;

ALTER TABLE `my_users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `my_users_banned`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;