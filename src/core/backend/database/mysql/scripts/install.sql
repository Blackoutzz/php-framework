SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `views`;
DROP TABLE IF EXISTS `actions`;
DROP TABLE IF EXISTS `controllers`;
DROP TABLE IF EXISTS `controller_views`;
DROP TABLE IF EXISTS `user_permissions`;
DROP TABLE IF EXISTS `user_states`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `options`;
DROP TABLE IF EXISTS `permissions`;
DROP TABLE IF EXISTS `user_options`;
DROP TABLE IF EXISTS `post_categories`;
DROP TABLE IF EXISTS `post_states`;
DROP TABLE IF EXISTS `post_options`;
DROP TABLE IF EXISTS `user_groups`;
DROP TABLE IF EXISTS `user_group_permissions`;
DROP TABLE IF EXISTS `user_group_options`;
DROP TABLE IF EXISTS `user_posts`;
DROP TABLE IF EXISTS `user_post_comments`;
DROP TABLE IF EXISTS `user_post_comment_states`;
DROP TABLE IF EXISTS `user_post_comment_options`;
DROP TABLE IF EXISTS `user_post_options`;
DROP TABLE IF EXISTS `user_actions`;
DROP TABLE IF EXISTS `user_controller_views`;
DROP TABLE IF EXISTS `user_group_controller_views`;
DROP TABLE IF EXISTS `plugins`;
DROP TABLE IF EXISTS `menu_buttons`;
DROP TABLE IF EXISTS `menu_categories`;
DROP TABLE IF EXISTS `menu_button_options`;
DROP TABLE IF EXISTS `menu_category_options`;
DROP TABLE IF EXISTS `permission_controller_views`;
DROP TABLE IF EXISTS `plugin_options`;
DROP TABLE IF EXISTS `app_options`;

CREATE TABLE `views` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`name` varchar(64) NOT NULL,PRIMARY KEY (`id`),UNIQUE KEY `view unique id` (`id`),UNIQUE KEY `view unique name` (`name`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `actions` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`name` varchar(64) NOT NULL,PRIMARY KEY (`id`),UNIQUE KEY `action unique id` (`id`),UNIQUE KEY `action unique name` (`name`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `controller_views` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`controller` bigint(20) NOT NULL DEFAULT '1',`view` bigint(20) NOT NULL DEFAULT '1',PRIMARY KEY (`id`),UNIQUE KEY `controller view unique id` (`id`),UNIQUE KEY `controller view unique controller and view` (`controller`,`view`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `controllers` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`name` varchar(32) NOT NULL,PRIMARY KEY (`id`),UNIQUE KEY `controller unique id` (`id`),UNIQUE KEY `controller unique name` (`name`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `options` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`name` varchar(32) NOT NULL,PRIMARY KEY (`id`),UNIQUE KEY `option unique id` (`id`),UNIQUE KEY `option unique name` (`name`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `permissions` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`name` varchar(32) NOT NULL,`description` TEXT NULL,PRIMARY KEY (`id`),UNIQUE KEY `permission unique id` (`id`),UNIQUE KEY `permission unique name` (`name`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `permission_controller_views` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`permission` bigint(20) NOT NULL DEFAULT '1',`controller_view` bigint(20) NOT NULL DEFAULT '1',`granted` tinyint(1) NOT NULL DEFAULT '0',PRIMARY KEY (`id`),UNIQUE KEY `permission controller view unique id` (`id`),UNIQUE KEY `permission controller view unique permission and controller view` (`permission`,`controller_view`),CONSTRAINT `permission controller view existing permission` FOREIGN KEY (`permission`) REFERENCES `permissions` (`id`),CONSTRAINT `permission controller view existing controller view` FOREIGN KEY (`controller_view`) REFERENCES `controller_views` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `post_categories` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,PRIMARY KEY (`id`),UNIQUE KEY `post category unique id` (`id`),UNIQUE KEY `post category unique name` (`name`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `post_states` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,PRIMARY KEY (`id`),UNIQUE KEY `post state unique id` (`id`),UNIQUE KEY `post state unique name` (`name`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `user_groups` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`name` varchar(64) NOT NULL,PRIMARY KEY (`id`),UNIQUE KEY `user group unique id` (`id`),UNIQUE KEY `user group unique name` (`name`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `user_states` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,PRIMARY KEY (`id`),UNIQUE KEY `user state index` (`id`),UNIQUE KEY `user state name` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `users` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,`password` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,`email` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,`group` bigint(20) NOT NULL DEFAULT '1',`state` bigint(20) NOT NULL DEFAULT '1',`creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,`last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,PRIMARY KEY (`id`),UNIQUE KEY `user unique id` (`id`),UNIQUE KEY `user unique name` (`name`),UNIQUE KEY `user unique email` (`email`),CONSTRAINT `user existing user group index` FOREIGN KEY (`group`) REFERENCES `user_groups` (`id`),CONSTRAINT `user existing user state index` FOREIGN KEY (`state`) REFERENCES `user_states` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `menu_categories` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,UNIQUE KEY `menu category unique index` (`id`),UNIQUE KEY `menu category unique name` (`name`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `menu_buttons` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`name` varchar(64) NOT NULL,`controller_view` bigint(20) NOT NULL DEFAULT 0 REFERENCES `controller_views` (`id`),`category` bigint(20) NOT NULL DEFAULT 0 REFERENCES `menu_categories` (`id`),UNIQUE KEY `menu button unique index` (`id`),CONSTRAINT `menu button existing controller_view index` FOREIGN KEY (`controller_view`) REFERENCES `controller_views` (`id`),CONSTRAINT `menu button existing category index` FOREIGN KEY (`category`) REFERENCES `menu_categories` (`id`),UNIQUE KEY `menu button unique controller_view and category` (`controller_view`,`category`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `menu_button_options` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`button` bigint(20) NOT NULL REFERENCES `menu_buttons` (`id`),`option` bigint(20) NOT NULL REFERENCES `options` (`id`),`value` TEXT NOT NULL,UNIQUE KEY `menu button option unique index` (`id`),CONSTRAINT `menu button option existing menu button index` FOREIGN KEY (`button`) REFERENCES `menu_buttons` (`id`),CONSTRAINT `menu button option existing option index` FOREIGN KEY (`option`) REFERENCES `options` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `menu_category_options` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`category` bigint(20)  NOT NULL REFERENCES `menu_categories` (`id`),`option` bigint(20) NOT NULL REFERENCES `options` (`id`),`value` TEXT NOT NULL,UNIQUE KEY `menu category option unique index` (`id`),CONSTRAINT `menu category option existing option index` FOREIGN KEY (`option`) REFERENCES `options` (`id`),CONSTRAINT `menu category option existing menu category index` FOREIGN KEY (`category`) REFERENCES `menu_categories` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `post_options` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`post` bigint(20) NOT NULL,`option` bigint(20) NOT NULL,`value` TEXT NOT NULL,PRIMARY KEY (`id`),UNIQUE KEY `post option unique index` (`id`),UNIQUE KEY `post option unique post and option` (`post`,`option`),CONSTRAINT `post option existing post index` FOREIGN KEY (`post`) REFERENCES `posts` (`id`),CONSTRAINT `post option existing option index` FOREIGN KEY (`option`) REFERENCES `options` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `user_actions` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`action` bigint(20) NOT NULL,`user` bigint(20) NOT NULL,`ip` varchar(32) NOT NULL,`date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,PRIMARY KEY (`id`),UNIQUE KEY `user_action_restrictions` (`id`),CONSTRAINT `user action existing action index` FOREIGN KEY (`action`) REFERENCES `actions` (`id`),CONSTRAINT `user action existing user index` FOREIGN KEY (`user`) REFERENCES `users` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `user_options` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`user` bigint(20) NOT NULL,`option` bigint(20) NOT NULL,`value` TEXT NOT NULL,PRIMARY KEY (`id`),UNIQUE KEY `user option unique index` (`id`),UNIQUE KEY `user option unique user and option` (`user`,`option`),CONSTRAINT `user option existing user index` FOREIGN KEY (`user`) REFERENCES `users` (`id`),CONSTRAINT `user option existing option index` FOREIGN KEY (`option`) REFERENCES `options` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `user_permissions` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`user` bigint(20) NOT NULL,`permission` bigint(20) NOT NULL,`granted` tinyint(1) NOT NULL DEFAULT '0',PRIMARY KEY (`id`),UNIQUE KEY `user permission unique index` (`id`),UNIQUE KEY `user permission unique user and permission` (`user`,`permission`),CONSTRAINT `user permission permission index` FOREIGN KEY (`permission`) REFERENCES `permissions` (`id`),CONSTRAINT `user permission user index` FOREIGN KEY (`user`) REFERENCES `users` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `user_controller_views` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`user` bigint(20) NOT NULL,`controller_view` bigint(20) NOT NULL,`granted` tinyint(1) NOT NULL DEFAULT '0',PRIMARY KEY (`id`),UNIQUE KEY `user controller view unique index` (`id`),UNIQUE KEY `user controller view unique combo` (`user`,`controller_view`),CONSTRAINT `user controller view controller view index` FOREIGN KEY (`controller_view`) REFERENCES `controller_views` (`id`),CONSTRAINT `user controller view user index` FOREIGN KEY (`user`) REFERENCES `users` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `user_group_controller_views` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`user_group` bigint(20) NOT NULL DEFAULT '1',`controller_view` bigint(20) NOT NULL DEFAULT '1',`granted` tinyint(1) NOT NULL DEFAULT '0',PRIMARY KEY (`id`),UNIQUE KEY `user group controller view unique index` (`id`),UNIQUE KEY `user group controller view unique combo` (`user_group`,`controller_view`),CONSTRAINT `user group controller view controller view index` FOREIGN KEY (`controller_view`) REFERENCES `controller_views` (`id`),CONSTRAINT `user group controller view user group index` FOREIGN KEY (`user_group`) REFERENCES `user_groups` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `user_group_options` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`user_group` bigint(20) NOT NULL,`option` bigint(20) NOT NULL,`value` TEXT NOT NULL,PRIMARY KEY (`id`),UNIQUE KEY `user group option unique id` (`id`),UNIQUE KEY `user group option unique user group option` (`user_group`,`option`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `user_group_permissions` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`user_group` bigint(20) NOT NULL DEFAULT '1',`permission` bigint(20) NOT NULL,`granted` tinyint(1) NOT NULL DEFAULT '0',PRIMARY KEY (`id`),UNIQUE KEY `user group permission unique index` (`id`),UNIQUE KEY `user group permission unique combo` (`user_group`,`permission`),CONSTRAINT `user group permission existing permission index` FOREIGN KEY (`permission`) REFERENCES `permissions` (`id`),CONSTRAINT `user group permission existing user group index` FOREIGN KEY (`user_group`) REFERENCES `user_groups` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `plugins` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`name` varchar(64) NOT NULL,`slug` varchar(64) NOT NULL,`version` varchar(32) NOT NULL DEFAULT 'ALPHA',`enabled` tinyint(1) NOT NULL DEFAULT 0,PRIMARY KEY (`id`),UNIQUE KEY `plugin unique name`(`name`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `plugin_options` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`plugin` bigint(20) NOT NULL,`option` bigint(20) NOT NULL,`value` TEXT NOT NULL,PRIMARY KEY (`id`),UNIQUE KEY `user option unique index` (`id`),UNIQUE KEY `plugin option unique plugin and option` (`plugin`,`option`),CONSTRAINT `plugin option existing plugin index` FOREIGN KEY (`plugin`) REFERENCES `plugins` (`id`),CONSTRAINT `plugin option existing option index` FOREIGN KEY (`option`) REFERENCES `options` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `app_options` (`id` bigint(20) NOT NULL AUTO_INCREMENT,`option` bigint(20) NOT NULL,`value` TEXT NOT NULL,PRIMARY KEY (`id`),UNIQUE KEY `app option unique index` (`id`),CONSTRAINT `app option existing option index` FOREIGN KEY (`option`) REFERENCES `options` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `plugins` values (1,"demo plugin","demo","ALPHA-DEMO",0);

INSERT INTO `actions` values (1,"Login");
INSERT INTO `actions` values (2,"Logout"); 
INSERT INTO `actions` values (3,"Forgot Password");

INSERT INTO `user_states` values (1,"Disconnected"); 
INSERT INTO `user_states` values (2,"Connected"); 

INSERT INTO `user_groups` values (1,"Banned");
INSERT INTO `user_groups` values (2,"Guest");
INSERT INTO `user_groups` values (3,"User");
INSERT INTO `user_groups` values (4,"Moderator");
INSERT INTO `user_groups` values (5,"Admin");

INSERT INTO `permissions` values (1,"Root","Full Permissions");
INSERT INTO `permissions` values (2,"Website Access","This is the basic permission needed to access the website.");

INSERT INTO `permissions` values (3,"User Access","This is the user access point to be able to access the dashboard.");

INSERT INTO `permissions` values (4,"Moderator Access","Moderator have access to a part of the admin panel without having full access to admin panel.");

INSERT INTO `permissions` values (5,"Admin Access","Full control over the website.");

INSERT INTO `menu_categories` values (1,"Menu");
INSERT INTO `menu_categories` values (2,"Dashboard");
INSERT INTO `menu_categories` values (3,"Admin");

-- Banned user permission = No Permission at all
INSERT INTO `user_group_permissions` values (1,1,2,0); 
INSERT INTO `user_group_permissions` values (2,1,3,0); 
INSERT INTO `user_group_permissions` values (3,1,4,0); 
INSERT INTO `user_group_permissions` values (4,1,5,0); 

-- Guest/Visitor Access = Access the website only
INSERT INTO `user_group_permissions` values (5,2,2,1); 
INSERT INTO `user_group_permissions` values (6,2,3,0); 
INSERT INTO `user_group_permissions` values (7,2,4,0); 
INSERT INTO `user_group_permissions` values (8,2,5,0); 

-- User/Authenticated = Access the website and the dashboard
INSERT INTO `user_group_permissions` values (9,3,2,1); 
INSERT INTO `user_group_permissions` values (10,3,3,1); 
INSERT INTO `user_group_permissions` values (11,3,4,0); 
INSERT INTO `user_group_permissions` values (12,3,5,0); 

-- Moderator/MODO   = Access the website and the dashboard / part of admin panel
INSERT INTO `user_group_permissions` values (13,4,2,1); 
INSERT INTO `user_group_permissions` values (14,4,3,1); 
INSERT INTO `user_group_permissions` values (15,4,4,1); 
INSERT INTO `user_group_permissions` values (16,4,5,0); 

-- Admin = Access to everything
INSERT INTO `user_group_permissions` values (17,5,2,1); 
INSERT INTO `user_group_permissions` values (18,5,3,1); 
INSERT INTO `user_group_permissions` values (19,5,4,1); 
INSERT INTO `user_group_permissions` values (20,5,5,1); 

INSERT INTO `controllers` values (1,"root");
INSERT INTO `controllers` values (2,"admin");
INSERT INTO `controllers` values (3,"ajax");
INSERT INTO `controllers` values (4,"cron");
INSERT INTO `controllers` values (5,"api");
INSERT INTO `controllers` values (6,"documentation");
INSERT INTO `controllers` values (7,"rss");

INSERT INTO `views` values (1,"index");
INSERT INTO `views` values (2,"dashboard");
INSERT INTO `views` values (3,"classes");
INSERT INTO `views` values (4,"application");
INSERT INTO `views` values (5,"login");
INSERT INTO `views` values (6,"users");
INSERT INTO `views` values (7,"user");
INSERT INTO `views` values (8,"permissions");
INSERT INTO `views` values (9,"permission");
INSERT INTO `views` values (10,"pages");
INSERT INTO `views` values (11,"page");
INSERT INTO `views` values (12,"groups");
INSERT INTO `views` values (13,"group");
INSERT INTO `views` values (14,"logs");
INSERT INTO `views` values (15,"log");
INSERT INTO `views` values (16,"test");
INSERT INTO `views` values (17,"plugins");
INSERT INTO `views` values (18,"plugin");
INSERT INTO `views` values (19,"system");
INSERT INTO `views` values (20,"database");
INSERT INTO `views` values (21,"profile");

-- Basic Access
INSERT INTO `controller_views` (`id`,`controller`,`view`) VALUES (1,(SELECT `id` from `controllers` where name='root'),(SELECT `id` from `views` where name='index'));
INSERT INTO `controller_views` (`id`,`controller`,`view`) VALUES (2,(SELECT `id` from `controllers` where name='root'),(SELECT `id` from `views` where name='dashboard'));
INSERT INTO `controller_views` (`id`,`controller`,`view`) VALUES (3,(SELECT `id` from `controllers` where name='root'),(SELECT `id` from `views` where name='login'));

INSERT INTO `menu_buttons` (`id`,`name`,`controller_view`,`category`) VALUES (1,'Home',(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='root') and view=(SELECT `id` from `views` where name='index')),1);
INSERT INTO `menu_buttons` (`id`,`name`,`controller_view`,`category`) VALUES (2,'Login',(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='root') and view=(SELECT `id` from `views` where name='login')),1);

-- User Access
INSERT INTO `controller_views` (`id`,`controller`,`view`) VALUES (4,(SELECT `id` from `controllers` where name='documentation'),(SELECT `id` from `views` where name='dashboard'));
INSERT INTO `controller_views` (`id`,`controller`,`view`) VALUES (5,(SELECT `id` from `controllers` where name='root'),(SELECT `id` from `views` where name='profile'));

INSERT INTO `menu_buttons` (`id`,`name`,`controller_view`,`category`) VALUES (3,'Home',(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='root') and view=(SELECT `id` from `views` where name='dashboard')),2);
INSERT INTO `menu_buttons` (`id`,`name`,`controller_view`,`category`) VALUES (4,'Profile',(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='root') and view=(SELECT `id` from `views` where name='profile')),2);

-- Admin panel only
INSERT INTO `controller_views` (`id`,`controller`,`view`) VALUES (6,(SELECT `id` from `controllers` where name='admin'),(SELECT `id` from `views` where name='users'));
INSERT INTO `controller_views` (`id`,`controller`,`view`) VALUES (7,(SELECT `id` from `controllers` where name='admin'),(SELECT `id` from `views` where name='user'));
INSERT INTO `controller_views` (`id`,`controller`,`view`) VALUES (8,(SELECT `id` from `controllers` where name='admin'),(SELECT `id` from `views` where name='permissions'));
INSERT INTO `controller_views` (`id`,`controller`,`view`) VALUES (9,(SELECT `id` from `controllers` where name='admin'),(SELECT `id` from `views` where name='permission'));
INSERT INTO `controller_views` (`id`,`controller`,`view`) VALUES (10,(SELECT `id` from `controllers` where name='admin'),(SELECT `id` from `views` where name='pages'));
INSERT INTO `controller_views` (`id`,`controller`,`view`) VALUES (11,(SELECT `id` from `controllers` where name='admin'),(SELECT `id` from `views` where name='page'));
INSERT INTO `controller_views` (`id`,`controller`,`view`) VALUES (12,(SELECT `id` from `controllers` where name='admin'),(SELECT `id` from `views` where name='groups'));
INSERT INTO `controller_views` (`id`,`controller`,`view`) VALUES (13,(SELECT `id` from `controllers` where name='admin'),(SELECT `id` from `views` where name='group'));
INSERT INTO `controller_views` (`id`,`controller`,`view`) VALUES (14,(SELECT `id` from `controllers` where name='admin'),(SELECT `id` from `views` where name='logs'));
INSERT INTO `controller_views` (`id`,`controller`,`view`) VALUES (15,(SELECT `id` from `controllers` where name='admin'),(SELECT `id` from `views` where name='log'));
INSERT INTO `controller_views` (`id`,`controller`,`view`) VALUES (16,(SELECT `id` from `controllers` where name='admin'),(SELECT `id` from `views` where name='test'));
INSERT INTO `controller_views` (`id`,`controller`,`view`) VALUES (17,(SELECT `id` from `controllers` where name='admin'),(SELECT `id` from `views` where name='plugins'));
INSERT INTO `controller_views` (`id`,`controller`,`view`) VALUES (18,(SELECT `id` from `controllers` where name='admin'),(SELECT `id` from `views` where name='plugin'));
INSERT INTO `controller_views` (`id`,`controller`,`view`) VALUES (19,(SELECT `id` from `controllers` where name='admin'),(SELECT `id` from `views` where name='system'));
INSERT INTO `controller_views` (`id`,`controller`,`view`) VALUES (20,(SELECT `id` from `controllers` where name='admin'),(SELECT `id` from `views` where name='database'));

INSERT INTO `menu_buttons` (`id`,`name`,`controller_view`,`category`) VALUES (5,'Users',(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='users')),3);
INSERT INTO `menu_buttons` (`id`,`name`,`controller_view`,`category`) VALUES (6,'User Groups',(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='groups')),3);
INSERT INTO `menu_buttons` (`id`,`name`,`controller_view`,`category`) VALUES (7,'Permissions',(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='permissions')),3);
INSERT INTO `menu_buttons` (`id`,`name`,`controller_view`,`category`) VALUES (8,'Pages',(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='pages')),3);
INSERT INTO `menu_buttons` (`id`,`name`,`controller_view`,`category`) VALUES (9,'Logs',(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='logs')),3);
INSERT INTO `menu_buttons` (`id`,`name`,`controller_view`,`category`) VALUES (10,'Database',(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='database')),3);
INSERT INTO `menu_buttons` (`id`,`name`,`controller_view`,`category`) VALUES (11,'System',(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='system')),3);
INSERT INTO `menu_buttons` (`id`,`name`,`controller_view`,`category`) VALUES (12,'Plugins',(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='plugins')),3);


-- Guest Permissions
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Guest'),1,1); 
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Guest'),3,1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Website Access'),1,1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Website Access'),3,1); 



-- User Permissions
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='User'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='root') and view=(SELECT `id` from `views` where name='dashboard')),1);
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='User'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='root') and view=(SELECT `id` from `views` where name='profile')),1);
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='User Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='root') and view=(SELECT `id` from `views` where name='dashboard')),1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='User Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='root') and view=(SELECT `id` from `views` where name='profile')),1); 
-- Moderator
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Moderator'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='root') and view=(SELECT `id` from `views` where name='dashboard')),1);
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Moderator'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='root') and view=(SELECT `id` from `views` where name='profile')),1);
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Moderator'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='users')),1);
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Moderator'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='logs')),1);
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Moderator Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='root') and view=(SELECT `id` from `views` where name='dashboard')),1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Moderator Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='root') and view=(SELECT `id` from `views` where name='profile')),1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Moderator Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='users')),1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Moderator Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='logs')),1); 
-- Admin Panel
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Admin'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='root') and view=(SELECT `id` from `views` where name='dashboard')),1);
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Admin'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='documentation') and view=(SELECT `id` from `views` where name='dashboard')),1);
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Admin'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='root') and view=(SELECT `id` from `views` where name='profile')),1);
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Admin'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='users')),1);
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Admin'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='user')),1);
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Admin'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='permissions')),1);
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Admin'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='permission')),1);
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Admin'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='pages')),1);
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Admin'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='page')),1);
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Admin'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='groups')),1);
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Admin'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='group')),1);
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Admin'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='logs')),1);
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Admin'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='log')),1);
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Admin'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='plugins')),1);
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Admin'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='plugin')),1);
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Admin'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='system')),1);
INSERT INTO `user_group_controller_views` (`user_group`,`controller_view`,`granted`) VALUES ((SELECT `id` from `user_groups` where name='Admin'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='database')),1);

INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Admin Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='root') and view=(SELECT `id` from `views` where name='dashboard')),1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Admin Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='root') and view=(SELECT `id` from `views` where name='profile')),1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Admin Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='documentation') and view=(SELECT `id` from `views` where name='dashboard')),1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Admin Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='users')),1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Admin Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='user')),1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Admin Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='permissions')),1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Admin Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='permission')),1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Admin Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='pages')),1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Admin Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='page')),1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Admin Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='groups')),1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Admin Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='group')),1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Admin Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='logs')),1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Admin Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='log')),1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Admin Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='plugins')),1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Admin Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='plugin')),1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Admin Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='system')),1); 
INSERT INTO `permission_controller_views` (`permission`,`controller_view`,`granted`) VALUES ((SELECT `id` from `permissions` where name='Admin Access'),(SELECT `id` from `controller_views` where controller=(SELECT `id` from `controllers` where name='admin') and view=(SELECT `id` from `views` where name='database')),1); 


SET FOREIGN_KEY_CHECKS = 1;