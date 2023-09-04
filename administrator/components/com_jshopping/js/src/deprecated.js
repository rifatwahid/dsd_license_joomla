function changeCategory() {
    console.warn("This method is deprecated please use shopCategory.changeOrder()");
    return shopCategory.changeOrder();
}

function toggleCategories() {
    console.warn("This method is deprecated please use shopCategory.toggle()");
    return shopCategory.toggle();
}

function select_content(id, title) {
    console.warn("This method is deprecated please use shopConfig.selectContent()");
    return shopConfig.selectContent(id, title);
}

function restartContentRequest() {
    console.warn("This method is deprecated please use shopConfig.restartContent()");
    return shopConfig.restartContent();
}

function contentRequest() {
    console.warn("This method is deprecated please use shopConfig._contentRequest()");
    return shopConfig._contentRequest();
}

function reloadPage(page) {
    console.warn("This method is deprecated please use shopConfig.reloadPage()");
    return shopConfig.reloadPage(page);
}

function reloadLanguage(language) {
    console.warn("This method is deprecated please use shopConfig.reloadLanguage()");
    return shopConfig.reloadLanguage(language);
}

function changeCouponType() {
    console.warn("This method is deprecated please use shopCoupon.changeType()");
    return shopCoupon.changeType();   
}

function template_width_change(t) {
    console.warn("This method is deprecated please use shopEmailHub.changeWidth()");
    return shopEmailHub.changeWidth(t);
}

function template_width_pluss(v) {
    console.warn("This method is deprecated please use shopEmailHub.widthPlus()");
    return shopEmailHub.widthPlus(v);
}

function template_padding_change(t) {
    console.warn("This method is deprecated please use shopEmailHub.changePadding()");
    return shopEmailHub.changePadding(t);
}

function template_padding_pluss(v) {
    console.warn("This method is deprecated please use shopEmailHub.paddingPlus()");
    return shopEmailHub.paddingPlus(v);
}

function addrow_pluss(v) {
    console.warn("This method is deprecated please use shopEmailHub.plusRow()");
    return shopEmailHub.plusRow(v);
}

function add_blocks(blocks, block_height) {
    console.warn("This method is deprecated please use shopEmailHub.addBlock()");
    return shopEmailHub.addBlock(blocks, block_height);
}

function select_block(t) {
    console.warn("This method is deprecated please use shopEmailHub.selectBlock()");
    return shopEmailHub.selectBlock(t);
}

function $_(id) {
    console.warn("This method is deprecated please use shopHelper.getElement()");
    return shopHelper.getElement(id);
}

function $F_(idElement) {
    console.warn("This method is deprecated please use shopHelper.getValue()");
    return shopHelper.getValue(idElement);
}

function Round(value, num) {
    console.warn("This method is deprecated please use shopHelper.round()");
    return shopHelper.round(value, num);
}

function isEmpty(value) {
    console.warn("This method is deprecated please use shopHelper.isEmpty()");
    return shopHelper.isEmpty(value);
}

function isExistsExcludeAttrInEditAttr() {
    console.warn("This method is deprecated please use shopHelper.isExistsExcludeAttributes()");
    return shopHelper.isExistsExcludeAttributes();
}

function isCouponEditPage() {
    console.warn("This method is deprecated please use shopHelper.isCouponEditPage()");
    return shopHelper.isCouponEditPage();
}

function setDefaultSize(width, height, param) {
    console.warn("This method is deprecated please use shopImage.setDefaultSize()");
    return shopImage.setDefaultSize(width, height, param);
}

function setOriginalSize(param) {
    console.warn("This method is deprecated please use shopImage.setOriginalSize()");
    return shopImage.setOriginalSize(param);
}

function setManualSize(param) {
    console.warn("This method is deprecated please use shopImage.setManualSize()");
    return shopImage.setManualSize(param);
}

function deleteFoto(id, type) {
    console.warn("This method is deprecated please use shopImage.delete()");
    return shopImage.delete(id, type);
}

function verifyStatus(orderStatus, orderId, message, extended, limit) {
    console.warn("This method is deprecated please use shopOrderAndOffer.verifyStatus()");
    return shopOrderAndOffer.verifyStatus(orderStatus, orderId, message, extended, limit);
}

function updateOrderSubtotalValue() {
    console.warn("This method is deprecated please use shopOrderAndOffer.updateOrderSubtotal()");
    return shopOrderAndOffer.updateOrderSubtotal();
}

function updateOrderTotalValue() {
    console.warn("This method is deprecated please use shopOrderAndOffer.updateOrderTotal()");
    return shopOrderAndOffer.updateOrderTotal();
}

function order_tax_calculate() {
    console.warn("This method is deprecated please use shopOrderAndOffer.calculateTax()");
    return shopOrderAndOffer.calculateTax();
}

function getOrderData() {
    console.warn("This method is deprecated please use shopOrderAndOffer.getData()");
    return shopOrderAndOffer.getData();
}

function getListOrderItems() {
    console.warn("This method is deprecated please use shopOrderAndOffer.getList()");
    return shopOrderAndOffer.getList();
}

function addOrderItemRow() {
    console.warn("This method is deprecated please use shopOrderAndOffer.addItemRow()");
    return shopOrderAndOffer.addItemRow();
}

function addOrderTaxRow() {
    console.warn("This method is deprecated please use shopOrderAndOffer.addTaxRow()");
    return shopOrderAndOffer.addTaxRow();
}

function loadProductInfoRowOrderItem(pid, num, currency_id) {
    console.warn("This method is deprecated please use shopOrderAndOffer.loadProductInfo()");
    return shopOrderAndOffer.loadProductInfo(pid, num, currency_id);
}

function updateBillingShippingForUser(user_id) {
    console.warn("This method is deprecated please use shopOrderAndOffer.updateShippingForUser()");
    return shopOrderAndOffer.updateShippingForUser(user_id);
}

function setBillingShippingFields(user) {
    console.warn("This method is deprecated please use shopOrderAndOffer._setShipping()");
    return shopOrderAndOffer._setShipping(user);
}

function addAttributValue2(id, hidden) {
    console.warn("This method is deprecated please use shopProductAttribute.addSecondValue()");
    return shopProductAttribute.addSecondValue(id, hidden);
}

function addAttributValue() {
    console.warn("This method is deprecated please use shopProductAttribute.addValue()");
    return shopProductAttribute.addValue();
}

function editAttributeExtendParams(id) {
    console.warn("This method is deprecated please use shopProductAttribute.editExtendParams()");
    return shopProductAttribute.editExtendParams(id);
}

function deleteListAttr() {
    console.warn("This method is deprecated please use shopProductAttribute.deleteList()");
    return shopProductAttribute.deleteList();
}

function selectAllListAttr(checked) {
    console.warn("This method is deprecated please use shopProductAttribute.selectList()");
    return shopProductAttribute.selectList(checked);
}

function updateEanForAttrib() {
    console.warn("This method is deprecated please use shopProductCommon.updateEan()");
    return shopProductCommon.updateEan();
}

function reloadProductExtraField(product_id) {
    console.warn("This method is deprecated please use shopProductCommon.reloadExtraField()");
    return shopProductCommon.reloadExtraField(product_id);
}

function ShowHideEnterProdQty(checked) {
    console.warn("This method is deprecated please use shopProductCommon.toggleQuantity()");
    return shopProductCommon.toggleQuantity(checked);
}

function deleteFileProduct(id, type) {
    console.warn("This method is deprecated please use shopProductCommon.deleteFile()");
    return shopProductCommon.deleteFile(id, type);
}

function price_types_add_new_option() {
	console.warn("This method is deprecated please use shopProductFreeAttribute.addOption()");
	return shopProductFreeAttribute.addOption();
}

function facp_delete_adv_option(key) {
    console.warn("This method is deprecated please use shopProductFreeAttribute.deleteOption()");
	return shopProductFreeAttribute.deleteOption(key);
}

function free_attributte_recalcule(hide_message) {
	console.warn("This method is deprecated please use shopProductFreeAttribute.recalculate()");
	return shopProductFreeAttribute.recalculate(hide_message);
}

const input_keyup = (hide_message) => {
	console.warn("This method is deprecated please use shopProductFreeAttribute.keyUp()");
	return shopProductFreeAttribute.keyUp(hide_message);
}

function showHideParamForPriceTypeQTYSelect() {
	console.warn("Method togglePriceType(for functional `Price per consignment type`) was deleted.");
}

function getSelectView() {
	console.warn("This method is deprecated please use shopProductFreeAttribute.getSelectView()");
	return shopProductFreeAttribute.getSelectView();
}

function facpAddLastNewRow(el)  {
	console.warn("This method is deprecated please use shopProductFreeAttribute.addLastRow()");
	return shopProductFreeAttribute.addLastRow();
}

function facpHideTexts(el) {
	console.warn("This method is deprecated please use shopProductFreeAttribute.hideText()");
	return shopProductFreeAttribute.hideText(el);
}

function facpShowTexts(el) {
	console.warn("This method is deprecated please use shopProductFreeAttribute.showText()");
	return shopProductFreeAttribute.showText(el);
}

function updateVariablesNames(rowNumber, variableName) {
	console.warn("This method is deprecated please use shopProductFreeAttribute.updateName()");
	return shopProductFreeAttribute.updateName(rowNumber, variableName);
}

function getCountOfVariables()  {
	console.warn("This method is deprecated please use shopProductFreeAttribute.getVariablesCount()");
	return shopProductFreeAttribute.getVariablesCount();
}

function product_images_request(position, url, filter) {
    console.warn("This method is deprecated please use shopProductImage.productRequest()");
    return shopProductImage.productRequest(position, url, filter);
}

function setImageFromFolder(position, filename) {
    console.warn("This method is deprecated please use shopProductImage.setImageFromFolder()");
    return shopProductImage.setImageFromFolder(position, filename);
}

function SqueezeBox_init(widht, height) {
    console.warn("This method is deprecated please use shopProductImage.squeezeBoxInit()");
    return shopProductImage.squeezeBoxInit(widht, height);
}

function updatePrice(display_price, second) {
    console.warn("This method is deprecated please use shopProductPrice.update()");
    return shopProductPrice.update(display_price, second);
}

function addNewPrice() {
    console.warn("This method is deprecated please use shopProductPrice.add()");
    return shopProductPrice.add();
}

function delete_add_price(num) {
    console.warn("This method is deprecated please use shopProductPrice.delete()");
    return shopProductPrice.delete(num);
}

function productAddPriceupdateValue(num) {
    console.warn("This method is deprecated please use shopProductPrice.update()");
    return shopProductPrice.updateNewPrice(num);
}

function reloadAddPriceValue() {
    console.warn("This method is deprecated please use shopProductPrice.reload()");
    return shopProductPrice.reload();
}

function add_to_list_relatad(id) {
    console.warn("This method is deprecated please use shopProductRelated.add()");
    return shopProductRelated.add(id);
}

function delete_related(id) {
    console.warn("This method is deprecated please use shopProductRelated.delete()");
    return shopProductRelated.delete(id);
}

function releted_product_search(start, no_id) {
    console.warn("This method is deprecated please use shopProductRelated.search()");
    return shopProductRelated.search(start, no_id);
}

function add_usergroups_prices_addNewPrice_list(usergroup) {
    console.warn("This method is deprecated please use shopProductUserGroup.addPriceList()");
    return shopProductUserGroup.addPriceList(usergroup);
}

function add_usergroups_prices_delete_add_price_list(num, usergroup) {
    console.warn("This method is deprecated please use shopProductUserGroup.deletePriceList()");
    return shopProductUserGroup.deletePriceList(num, usergroup);
}

function add_usergroups_prices_productAddPriceupdateValue_list(num, usergroup) {
    console.warn("This method is deprecated please use shopProductUserGroup.updatePriceValueList()");
    return shopProductUserGroup.updatePriceValueList(num, usergroup);
}

function add_usergroups_prices_updatePrice_list(display_price_admin, usergroup, second) {
    console.warn("This method is deprecated please use shopProductUserGroup.updatePriceList()");
    return shopProductUserGroup.updatePriceList(display_price_admin, second);
}

function add_usergroups_prices_productAddPriceupdateValue(num) {
    console.warn("This method is deprecated please use shopProductUserGroup.updatePriceValue()");
    return shopProductUserGroup.updatePriceValue(num);
}

function add_usergroups_prices_delete_add_price(num) {
    console.warn("This method is deprecated please use shopProductUserGroup.deletePrice()");
    return shopProductUserGroup.deletePrice(num);
}

function add_usergroups_prices_addNewPrice() {
    console.warn("This method is deprecated please use shopProductUserGroup.addPrice()");
    return shopProductUserGroup.addPrice();
}

function add_usergroups_prices_updatePrice(display_price_admin, type) {
    console.warn("This method is deprecated please use shopProductUserGroup.updatePrice()");
    return shopProductUserGroup.updatePrice(display_price_admin, type);
}

function updateAllVideoFileField() {
    console.warn("This method is deprecated please use shopProductVideo.updateAll()");
    return shopProductVideo.updateAll();
}

function changeVideoFileField(obj) {
    console.warn("This method is deprecated please use shopProductVideo.changeVideo()");
    return shopProductVideo.changeVideo(obj);
}

function deleteVideoProduct(id) {
    console.warn("This method is deprecated please use shopProductVideo.delete()");
    return shopProductVideo.delete(id);
}

function deleteFotoLabel(id, lang) {
    console.warn("This method is deprecated please use shopProductLabel.delete()");
    return shopProductLabel.delete(id, lang);
}

function addShippingPrice() {
    console.warn("This method is deprecated please use shopShipping.addPrice()");
    return shopShipping.addPrice();
}

function deleteShippingPrice(num) {
    console.warn("This method is deprecated please use shopShipping.deletePrice()");
    return shopShipping.deletePrice(num);
}

function delete_shipping_weight_price_row(num) {
    console.warn("This method is deprecated please use shopShipping.deletePrice()");
    return shopShipping.deletePrice(num);
}

function otherDeliveryAddress(val) {
    console.warn("This method is deprecated please use shopUser.anotherDeliveryAddress()");
    return shopUser.anotherDeliveryAddress(val);
}

function getState() {
    console.warn("This method is deprecated please use shopStates.getState()");
    return shopStates.getState();
}

function see_states() {
    console.warn("This method is deprecated please use shopStates.see_states()");
    return shopStates.see_states();
}

function getState2() {
    console.warn("This method is deprecated please use shopStates.getState2()");
    return shopStates.getState2();
}

function delete_from_package(t) {
    console.warn("This method is deprecated please use shopOrder.delete_from_package()");
    return shopOrder.delete_from_package(t);
}

function getPackagesProductsJson() {
    console.warn("This method is deprecated please use shopOrder.getPackagesProductsJson()");
    return shopOrder.getPackagesProductsJson();
}

function setSavedPackages(e) {
    console.warn("This method is deprecated please use shopOrder.setSavedPackages()");
    return shopOrder.setSavedPackages(e);
}

function orderedit_package_add(txt) {
    console.warn("This method is deprecated please use shopOrder.orderedit_package_add()");
    return shopOrder.orderedit_package_add(txt);
}

function productPackage_dragover_handler(ev) {
    console.warn("This method is deprecated please use shopOrder.productPackage_dragover_handler()");
    return shopOrder.productPackage_dragover_handler(ev);
}

function productPackage_dragstart_handler(ev) {
    console.warn("This method is deprecated please use shopOrder.productPackage_dragstart_handler()");
    return shopOrder.productPackage_dragstart_handler(ev);
}

function productPackage_drop_handler(ev) {
    console.warn("This method is deprecated please use shopOrder.productPackage_drop_handler()");
    return shopOrder.productPackage_drop_handler(ev);
}