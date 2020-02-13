--
-- extra form fields for basket
--
INSERT INTO `core_form_field` (`Field_ID`, `type`, `label`, `fieldkey`, `tab`, `form`, `class`, `url_view`, `url_list`, `show_widget_left`, `allow_clear`, `readonly`, `tbl_cached_name`, `tbl_class`, `tbl_permission`) VALUES
(NULL, 'textarea', 'Comment of Customer', 'comment', 'basket-base', 'basket-single', 'col-md-12', '', '', '0', '1', '0', '', '', ''),
(NULL, 'select', 'Paymentmethod', 'paymentmethod_idfs', 'basket-base', 'basket-single', 'col-md-12', '', '/tag/api/list/basket-single/paymentmethod', 0, 1, 0, 'entitytag-single', 'OnePlace\\Tag\\Model\\EntityTagTable', 'add-OnePlace\\Tag\\Controller\\PaymentmethodController'),
(NULL, 'select', 'Deliverymethod', 'deliverymethod_idfs', 'basket-base', 'basket-single', 'col-md-12', '', '/tag/api/list/basket-single/deliverymethod', 0, 1, 0, 'entitytag-single', 'OnePlace\\Tag\\Model\\EntityTagTable', 'add-OnePlace\\Tag\\Controller\\DeliverymethodController'),
(NULL, 'select', 'State', 'state_idfs', 'basket-base', 'basket-single', 'col-md-12', '', '/tag/api/list/basket-single/state', 0, 1, 0, 'entitytag-single', 'OnePlace\\Tag\\Model\\EntityTagTable', 'add-OnePlace\\Basket\\Controller\\StateController');


--
-- add new tags
--
INSERT IGNORE INTO `core_tag` (`Tag_ID`, `tag_key`, `tag_label`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
(NULL, 'deliverymethod', 'Deliverymethod', '1', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00'),
(NULL, 'paymentmethod', 'Paymentmethod', '1', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00');


INSERT INTO `core_entity_tag` (`Entitytag_ID`, `entity_form_idfs`, `tag_idfs`, `tag_value`, `parent_tag_idfs`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES
(NULL, 'basket-single', (select `Tag_ID` from `core_tag` where `tag_key`='state'), 'new', '0', '1', CURRENT_TIME(), '1', CURRENT_TIME()),
(NULL, 'basket-single', (select `Tag_ID` from `core_tag` where `tag_key=`'state'), 'checkout', '0', '1', CURRENT_TIME(), '1', CURRENT_TIME()),
(NULL, 'basket-single',(select `Tag_ID` from `core_tag` where `tag_key`='state'), 'payment', '0', '1', CURRENT_TIME(), '1', CURRENT_TIME()),
(NULL, 'basket-single', (select `Tag_ID` from `core_tag` where `tag_key`='state'), 'done', '0', '1', CURRENT_TIME(), '1', CURRENT_TIME()),
(NULL, 'basket-single', (select `Tag_ID` from `core_tag` where `tag_key`='deliverymethod'), 'E-Mail', '0', '1', CURRENT_TIME(), '1', CURRENT_TIME()),
(NULL, 'basket-single', (select `Tag_ID` from `core_tag` where `tag_key`='deliverymethod'), 'Mail', '0', '1', CURRENT_TIME(), '1', CURRENT_TIME()),
(NULL, 'basket-single', (select `Tag_ID` from `core_tag` where `tag_key`='paymentmethod'), 'Prepayment', '0', '1', CURRENT_TIME(), '1', CURRENT_TIME()),
(NULL, 'basket-single', (select `Tag_ID` from `core_tag` where `tag_key`='paymentmethod'), 'Paypal', '0', '1', CURRENT_TIME(), '1', CURRENT_TIME()),
(NULL, 'basket-single', (select `Tag_ID` from `core_tag` where `tag_key`='paymentmethod'), 'Stripe', '0', '1', CURRENT_TIME(), '1', CURRENT_TIME());

