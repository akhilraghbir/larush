ALTER TABLE `tbl_leads` ADD `visible` ENUM('Self','Everyone') NULL DEFAULT NULL AFTER `purpose`;
