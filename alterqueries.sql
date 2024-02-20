ALTER TABLE `tbl_leads` ADD `visible` ENUM('Self','Everyone') NULL DEFAULT NULL AFTER `purpose`;
ALTER TABLE `tbl_dispatch_items` ADD `created_on` DATETIME NULL DEFAULT NULL AFTER `skid`;
ALTER TABLE `tbl_purchase_items` ADD `created_on` DATETIME NULL DEFAULT NULL AFTER `total`;
ALTER TABLE `tbl_dispatch` ADD `updated_on` DATETIME NULL DEFAULT NULL AFTER `created_on`;
ALTER TABLE `tbl_dispatch_items` ADD `updated_on` DATETIME NULL DEFAULT NULL AFTER `created_on`;
