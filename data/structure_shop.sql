ALTER TABLE `basket` ADD `shop_session_id` VARCHAR(100) NOT NULL DEFAULT '' AFTER `label`,
ADD `paymentmethod_idfs` INT(11) NOT NULL DEFAULT '0' AFTER `Basket_ID`,
ADD `deliverymethod_idfs` INT(11) NOT NULL DEFAULT '0' AFTER `Basket_ID`,
ADD `contact_idfs` INT(11) NOT NULL DEFAULT '0' AFTER `Basket_ID`,
ADD `job_idfs` INT(11) NOT NULL DEFAULT '0' AFTER `Basket_ID`,
ADD `state_idfs` INT(11) NOT NULL DEFAULT '0' AFTER `Basket_ID`,
ADD `payment_session_id` VARCHAR(255) NOT NULL DEFAULT '' AFTER `label`,
ADD `payment_id` VARCHAR(255) NOT NULL DEFAULT '' AFTER `label`,
ADD `comment` TEXT NOT NULL DEFAULT '' AFTER `label`;