--
-- extra form fields for basket
--
INSERT INTO `core_form_field` (`Field_ID`, `type`, `label`, `fieldkey`, `tab`, `form`, `class`, `url_view`, `url_list`, `show_widget_left`, `allow_clear`, `readonly`, `tbl_cached_name`, `tbl_class`, `tbl_permission`) VALUES
(NULL, 'textarea', 'Comment of Customer', 'comment', 'basket-base', 'basket-single', 'col-md-12', '', '', '0', '1', '0', '', '', ''),
(NULL, 'select', 'Paymentmethod', 'paymentmethod_idfs', 'basket-base', 'basket-single', 'col-md-12', '', '/tag/api/list/basket-single/paymentmethod', 1, 1, 0, 'entitytag-single', 'OnePlace\\Tag\\Model\\EntityTagTable', 'add-OnePlace\\Tag\\Controller\\PaymentmethodController'),
(NULL, 'select', 'Deliverymethod', 'deliverymethod_idfs', 'basket-base', 'basket-single', 'col-md-12', '', '/tag/api/list/basket-single/deliverymethod', 1, 1, 0, 'entitytag-single', 'OnePlace\\Tag\\Model\\EntityTagTable', 'add-OnePlace\\Tag\\Controller\\DeliverymethodController'),
(NULL, 'select', 'State', 'state_idfs', 'basket-base', 'basket-single', 'col-md-12', '', '/tag/api/list/basket-single/state', 0, 1, 0, 'entitytag-single', 'OnePlace\\Tag\\Model\\EntityTagTable', 'add-OnePlace\\Basket\\Controller\\StateController'),
(NULL, 'text', 'Shop Session ID', 'shop_session_id', 'basket-base', 'basket-single', 'col-md-3', '/basket/view/##ID##', '', 1, 1, 0, '', '', '');

INSERT INTO `core_form_field` (`Field_ID`, `type`, `label`, `fieldkey`, `tab`, `form`, `class`, `url_view`, `url_list`, `tag_key`, `show_widget_left`, `allow_clear`, `readonly`, `tbl_cached_name`, `tbl_class`, `tbl_permission`) VALUES
(NULL, 'datetime', 'Payment started', 'payment_started', 'basket-payment', 'basket-single', 'col-md-2', '/basket/view/##ID##', '', '', '1', '1', '1', '', '', ''),
(NULL, 'datetime', 'Payment received', 'payment_received', 'basket-payment', 'basket-single', 'col-md-2', '/basket/view/##ID##', '', '', '0', '1', '1', '', '', '');
--
-- add new tags
--
INSERT IGNORE INTO `core_tag` (`Tag_ID`, `tag_key`, `tag_label`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
(NULL, 'deliverymethod', 'Deliverymethod', '1', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00'),
(NULL, 'paymentmethod', 'Paymentmethod', '1', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00');

COMMIT;

--
-- quicksearch
--
INSERT INTO `settings` (`settings_key`, `settings_value`) VALUES
('quicksearch-basket-customlabel', 'shop_session_id'),
('quicksearch-basket-customresultattribute', 'created_date');
COMMIT;



--
-- Shop Tags
--
INSERT INTO `core_entity_tag` (`Entitytag_ID`, `entity_form_idfs`, `tag_idfs`, `tag_value`, `tag_key`, `tag_color`, `tag_icon`, `parent_tag_idfs`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
(NULL, 'basket-single', (select `Tag_ID` from `core_tag` where `tag_key`='state'), 'new', 'new', '', '', 0, 1, '2020-03-10 14:54:33', 1, '2020-03-10 14:54:33'),
(NULL, 'basket-single', (select `Tag_ID` from `core_tag` where `tag_key`='state'), 'checkout', 'checkout', '', '', 0, 1, '2020-03-10 14:54:33', 1, '2020-03-10 14:54:33'),
(NULL, 'basket-single', (select `Tag_ID` from `core_tag` where `tag_key`='state'), 'payment', 'payment', '', '', 0, 1, '2020-03-10 14:54:33', 1, '2020-03-10 14:54:33'),
(NULL, 'basket-single', (select `Tag_ID` from `core_tag` where `tag_key`='state'), 'done', 'done', '', '', 0, 1, '2020-03-10 14:54:33', 1, '2020-03-10 14:54:33'),
(NULL, 'basket-single', (select `Tag_ID` from `core_tag` where `tag_key`='deliverymethod'), 'E-Mail', 'email', '', '', 0, 1, '2020-03-10 14:54:33', 1, '2020-03-10 14:54:33'),
(NULL, 'basket-single', (select `Tag_ID` from `core_tag` where `tag_key`='deliverymethod'), 'Mail', 'mail', '', '', 0, 1, '2020-03-10 14:54:33', 1, '2020-03-10 14:54:33'),
(NULL, 'basket-single', (select `Tag_ID` from `core_tag` where `tag_key`='paymentmethod'), 'Prepayment', 'prepay', '', 'fas fa-university fa-3x', 0, 1, '2020-03-10 14:54:33', 1, '2020-03-10 14:54:33'),
(NULL, 'basket-single', (select `Tag_ID` from `core_tag` where `tag_key`='paymentmethod'), 'Paypal', 'paypal', '', 'fab fa-paypal fa-3x', 0, 1, '2020-03-10 14:54:33', 1, '2020-03-10 14:54:33'),
(NULL, 'basket-single', (select `Tag_ID` from `core_tag` where `tag_key`='paymentmethod'), 'Stripe', 'stripe', '', 'fab fa-cc-stripe fa-3x', 0, 1, '2020-03-10 14:54:33', 1, '2020-03-10 14:54:33');