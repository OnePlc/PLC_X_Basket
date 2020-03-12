--
-- Base Table
--
CREATE TABLE `basket` (
  `Basket_ID` int(11) NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `basket`
  ADD PRIMARY KEY (`Basket_ID`);

ALTER TABLE `basket`
  MODIFY `Basket_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Permissions
--
INSERT INTO `permission` (`permission_key`, `module`, `label`, `nav_label`, `nav_href`, `show_in_menu`) VALUES
('add', 'OnePlace\\Basket\\Controller\\BasketController', 'Add', '', '', 0),
('edit', 'OnePlace\\Basket\\Controller\\BasketController', 'Edit', '', '', 0),
('index', 'OnePlace\\Basket\\Controller\\BasketController', 'Index', 'Baskets', '/basket', 1),
('list', 'OnePlace\\Basket\\Controller\\ApiController', 'List', '', '', 1),
('view', 'OnePlace\\Basket\\Controller\\BasketController', 'View', '', '', 0),
('dump', 'OnePlace\\Basket\\Controller\\ExportController', 'Excel Dump', '', '', 0),
('index', 'OnePlace\\Basket\\Controller\\SearchController', 'Search', '', '', 0);

--
-- Form
--
INSERT INTO `core_form` (`form_key`, `label`, `entity_class`, `entity_tbl_class`) VALUES
('basket-single', 'Basket', 'OnePlace\\Basket\\Model\\Basket', 'OnePlace\\Basket\\Model\\BasketTable');

--
-- Index List
--
INSERT INTO `core_index_table` (`table_name`, `form`, `label`) VALUES
('basket-index', 'basket-single', 'Basket Index');

--
-- Tabs
--
INSERT INTO `core_form_tab` (`Tab_ID`, `form`, `title`, `subtitle`, `icon`, `counter`, `sort_id`, `filter_check`, `filter_value`) VALUES ('basket-base', 'basket-single', 'Basket', 'Base', 'fas fa-cogs', '', '0', '', '');

--
-- Buttons
--
INSERT INTO `core_form_button` (`Button_ID`, `label`, `icon`, `title`, `href`, `class`, `append`, `form`, `mode`, `filter_check`, `filter_value`) VALUES
(NULL, 'Save Basket', 'fas fa-save', 'Save Basket', '#', 'primary saveForm', '', 'basket-single', 'link', '', ''),
(NULL, 'Edit Basket', 'fas fa-edit', 'Edit Basket', '/basket/edit/##ID##', 'primary', '', 'basket-view', 'link', '', ''),
(NULL, 'Export Baskets', 'fas fa-file-excel', 'Export Baskets', '/basket/export', 'primary', '', 'basket-index', 'link', '', ''),
(NULL, 'Find Baskets', 'fas fa-search', 'Find Baskets', '/basket/search', 'primary', '', 'basket-index', 'link', '', ''),
(NULL, 'Export Baskets', 'fas fa-file-excel', 'Export Baskets', '#', 'primary initExcelDump', '', 'basket-search', 'link', '', ''),
(NULL, 'New Search', 'fas fa-search', 'New Search', '/basket/search', 'primary', '', 'basket-search', 'link', '', '');

--
-- Fields
--
INSERT INTO `core_form_field` (`Field_ID`, `type`, `label`, `fieldkey`, `tab`, `form`, `class`, `url_view`, `url_list`, `show_widget_left`, `allow_clear`, `readonly`, `tbl_cached_name`, `tbl_class`, `tbl_permission`) VALUES
(NULL, 'text', 'Name', 'label', 'basket-base', 'basket-single', 'col-md-3', '/basket/view/##ID##', '', 0, 1, 0, '', '', '');

--
-- User XP Activity
--
INSERT INTO `user_xp_activity` (`Activity_ID`, `xp_key`, `label`, `xp_base`) VALUES
(NULL, 'basket-add', 'Add New Basket', '50'),
(NULL, 'basket-edit', 'Edit Basket', '5'),
(NULL, 'basket-export', 'Edit Basket', '5');

--
-- module icon
--
INSERT INTO `settings` (`settings_key`, `settings_value`) VALUES ('basket-icon', 'fas fa-shopping-cart');

--
-- widgets
--
INSERT INTO `core_widget` (`Widget_ID`, `widget_name`, `label`, `permission`) VALUES
(NULL, 'basket_manager', 'Basket - Shop Manager', 'index-Basket\\Controller\\BasketController');


COMMIT;