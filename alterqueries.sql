ALTER TABLE `tbl_leads` ADD `visible` ENUM('Self','Everyone') NULL DEFAULT NULL AFTER `purpose`;
ALTER TABLE `tbl_dispatch_items` ADD `created_on` DATETIME NULL DEFAULT NULL AFTER `skid`;
ALTER TABLE `tbl_purchase_items` ADD `created_on` DATETIME NULL DEFAULT NULL AFTER `total`;
ALTER TABLE `tbl_dispatch` ADD `updated_on` DATETIME NULL DEFAULT NULL AFTER `created_on`;
ALTER TABLE `tbl_dispatch_items` ADD `updated_on` DATETIME NULL DEFAULT NULL AFTER `created_on`;
ALTER TABLE `tbl_invoice_items` ADD `created_on` DATETIME NULL DEFAULT NULL AFTER `total`;
ALTER TABLE `tbl_products` ADD `is_purchase_sale_different` ENUM('No','Yes') NULL DEFAULT 'No' AFTER `zoom_image`;
ALTER TABLE `tbl_purchase_items` ADD `is_purchase_sale_different` ENUM('No','Yes') NOT NULL AFTER `quantity`, ADD `units` DECIMAL(25,2) NULL DEFAULT NULL AFTER `is_purchase_sale_different`;
ALTER TABLE `tbl_purchases` ADD `is_qty_converted` ENUM('No','Yes') NOT NULL DEFAULT 'No' AFTER `notes`;
