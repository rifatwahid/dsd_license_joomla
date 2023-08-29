function $_(idElement) {
    console.warn("This method is deprecated please use shopHelper.getElement()");
    return shopHelper.getElement(idElement);
}

function $F_(idElement) {
    console.warn("This method is deprecated please use shopHelper.getValue()");
    return shopHelper.getValue(idElement);
}

function checkIfBlockExists(name) {
    console.warn("This method is deprecated please use shopHelper.checkExistBlock()");
    return shopHelper.checkExistBlock(name);
}

function sendAjax(method, url, data, functions) {
    console.warn("This method is deprecated please use shopHelper.sendAjax()");
    return shopHelper.sendAjax(method, url, data, functions);
}

function scrollTo(direction, speed, duration) {
    console.warn("This method is deprecated please use shopHelper.scrollTo()");
    return shopHelper.scrollTo({direction, speed, duration});
}

function showHideFieldFirm(type) {
    console.warn("This method is deprecated please use shopHelper.toggleFirm()");
    return shopHelper.toggleFirm(type);
}

function submitListProductFilters() {
    console.warn("This method is deprecated please use shopHelper.submitFilter()");
    return shopHelper.submitFilter(type);
}

function isCartPage() {
    console.warn("This method is deprecated please use shopHelper.isCartPage()");
    return shopHelper.isCartPage();
}

function isProductPage() {
    console.warn("This method is deprecated please use shopHelper.isProductPage()");
    return shopHelper.isProductPage();
}

function isBelemTemplate() {
    console.warn("This method is deprecated please use shopHelper.isBelemTemplate()");
    return shopHelper.isBelemTemplate();
}

function isImageUploadActivated() {
    console.warn("This method is deprecated please use uploadImage.isUploadActivated()");
    return uploadImage.isUploadActivated();
}

function isMultiUploadBlock(number) {
    console.warn("This method is deprecated please use uploadImage.isMultiUpload()");
    return uploadImage.isMultiUpload(number);
}

function thereIsZeroQuantityForUploadImage() {
    console.warn("This method is deprecated please use uploadImage.checkIfZero()");
    return uploadImage.checkIfZero();
}

function isUsedAllQuantityForUpload() {
    console.warn("This method is deprecated please use uploadImage.isUsedAllQuantity()");
    return uploadImage.isUsedAllQuantity();
}

function countOfUploadedImages(number) {
    console.warn("This method is deprecated please use uploadImage.getUploadImagesCount()");
    return uploadImage.getUploadImagesCount(number);
}

function afterUploadedImage(data, that) {
    console.warn("This method is deprecated please use uploadImage.afterUpload()");
    return uploadImage.afterUpload(data, that);
}

function updateRemainingQtyUploadImages(blockNumber) {
    console.warn("This method is deprecated please use uploadImage.updateQuantity()");
    return uploadImage.updateQuantity(blockNumber);
}

function onUploadImageBlockClick(url, callback, element, event) {
    console.warn("This method is deprecated please use uploadImage.startUpload()");
    return uploadImage.startUpload(url, callback, element, event);
}

function addNewUploadImageBlock(blockName, evt) {
    console.warn("This method is deprecated please use uploadImage.addNewUpload()");
    return uploadImage.addNewUpload(blockName, evt);
}

function updateRemainingQtyWhenChangeProductQty(number, qtyElement) {
    console.warn("This method is deprecated please use uploadImage.updateQuantityWhenChangeProductQuantity()");
    return uploadImage.updateQuantityWhenChangeProductQuantity(number, qtyElement);
}

function deleteUploadedImage(blockName, number, evt) {
    console.warn("This method is deprecated please use uploadImage.deleteUpload()");
    return uploadImage.deleteUpload(blockName, number, evt);
}

function deleteUploadedImageInCart(blockName, number, evt) {
    console.warn("This method is deprecated please use uploadImage.deleteUploadInCart()");
    return uploadImage.deleteUploadInCart(blockName, number, evt);
}

function getNumberOfUploadImageByChildren(children) {
    console.warn("This method is deprecated please use uploadImage._getNumberByChildren()");
    return uploadImage._getNumberByChildren(children);
}

function getCountOfExistsUploadFiles(selector) {
    console.warn("This method is deprecated please use uploadImage._getUploadFilesCount()");
    return uploadImage._getUploadFilesCount(selector);
}

function recalculateRemainingQty(number, qty) {
    console.warn("This method is deprecated please use uploadImage._recalculateQuantity()");
    return uploadImage._recalculateQuantity(number, qty);
}

function getLastNumberOfUploadImage(blockName) {
    console.warn("This method is deprecated please use uploadImage.getLastNumber()");
    return uploadImage.getLastNumber(blockName);
}

function onNativeUploadProgressBlockClick(url, callback, element, event) {
    console.warn("This method is deprecated please use uploadImage.uploadImage.startUpload()");
    return uploadImage.startUpload(url, callback, element, event);
}

function callbackAfterNativeProgressFileUploaded(data, that) {
    console.warn("This method is deprecated please use uploadImage.uploadImage.startafterUploadUpload()");
    return uploadImage.afterUpload(data, that);
}

function updateNativeProgressUploadRemainingQtyOnChangeUploadQty(number) {
    console.warn("This method is deprecated please use uploadImage.uploadImage.updateQuantity()");
    return uploadImage.updateQuantity(number);
}

function deleteNativeProgressUploadsFilesRow(blockName, number, evt) {
    console.warn("This method is deprecated please use uploadImage.uploadImage.deleteUpload()");
    return uploadImage.deleteUpload(blockName, number, evt);
}

function addNewNativeProgressUploadRow(blockName, event) {
    console.warn("This method is deprecated please use uploadImage.uploadImage.addNewUpload()");
    return uploadImage.addNewUpload(blockName, event);
}

function validateUploadedImagesInCart() {
    console.warn("This method is deprecated please use shopCart.validateUploadImage()");
    return shopCart.validateUploadImage();
}

function prepareTemplateForAddNewUploadImageBlockInCart(element, currentNumber, parentName, number) {
    console.warn("This method is deprecated please use shopCart.beforeAddUploadImageBlock()");
    return shopCart.beforeAddUploadImageBlock(element, currentNumber, parentName, number);
}

function ajaxUpdateDescribeUploadImageInCart(number, productsCount) {
    console.warn("This method is deprecated please use shopCart.updateDescribeUploadImage()");
    return shopCart.updateDescribeUploadImage(number, productsCount);
}

function ajaxUpdateUploadImagesInCart(selector, productsCount) {
    console.warn("This method is deprecated please use shopCart.updateUploadImages()");
    return shopCart.updateUploadImages(selector, productsCount);
}

function updateRemainingQtyUploadImagesInCart(number) {
    console.warn("This method is deprecated please use shopCart.updateUploadImageQuantity()");
    return shopCart.updateUploadImageQuantity(number);
}

function afterUploadedNativeProgressFileinCart(number, that) {
    console.warn("This method is deprecated please use shopCart.afterUploadFile()");
    return shopCart.afterUploadFile(number, that);
}

function getUploadedImageInfoFromCart(selector, productsCount) {
    console.warn("This method is deprecated please use shopCart.getUploadedImageInfo()");
    return shopCart.getUploadedImageInfo(selector, productsCount);
}

function startUpdateCartEvents() {
    console.warn("This method is deprecated please use shopCart._updateEvents()");
    return shopCart._updateEvents();
}

function updateEventsOnCartPage(url, data = '', method = 'post', timeout= 100) {
    console.warn("This method is deprecated please use shopCart._updateEvents()");
    return shopCart._updateEvents();
}

function clearProductListFilter() {
    console.warn("This method is deprecated please use shopCategory.clearFilter()");
    return shopCategory.clearFilter();
}

function submitListProductFilterSortDirection() {
    console.warn("This method is deprecated please use shopCategory.changeSorting()");
    return shopCategory.changeSorting();
}

function reloadAttribSelectAndPrice(id_select) {
    console.warn("This method is deprecated please use shopProductAttributes.reloadSelectAndPrice()");
    return shopProductAttributes.reloadSelectAndPrice(id_select);
}

function reloadAttribImg(id, value) {
    console.warn("This method is deprecated please use shopProductAttributes.reloadImage()");
    return shopProductAttributes.reloadImage(id, value);
}

function reloadAttrValue() {
    console.warn("This method is deprecated please use shopProductAttributes.reloadValue()");
    return shopProductAttributes.reloadValue();
}

function setAttrValue(id, value) {
    console.warn("This method is deprecated please use shopProductAttributes.setValue()");
    return shopProductAttributes.setValue(id, value);
}

function setNewAttrsValue(id, value, idd) {
    console.warn("This method is deprecated please use shopProductAttributes.setNewValue()");
    return shopProductAttributes.setNewValue(id, value, idd);
}

function reloadProductCurrentPrice(data) {
    console.warn("This method is deprecated please use shopProductAttributes.reloadCurrentPrice()");
    return shopProductAttributes.reloadCurrentPrice(data);
}

function reloadPrices() {
    console.warn("This method is deprecated please use shopProductAttributes.reloadPrice()");
    return shopProductAttributes.reloadPrice();
}

function showVideo(idElement, width, height) {
    console.warn("This method is deprecated please use shopProductCommon.showVideo()");
    return shopProductCommon.showVideo(idElement, width, height);
}

function showVideoCode(idElement) {
    console.warn("This method is deprecated please use shopProductCommon.showVideo()");
    return shopProductCommon.showVideo(idElement);
}

function showImage(id) {
    console.warn("This method is deprecated please use shopProductCommon.showImage()");
    return shopProductCommon.showImage(id);
}

function validateReviewForm(name) {
    console.warn("This method is deprecated please use shopProductForm.validate()");
    return shopProductForm.validate(name);
}

function changeFormAction(form, newActionAddress) {
    console.warn("This method is deprecated please use shopProductForm.changeAction()");
    return shopProductForm.changeAction(form, newActionAddress);
}

function addProjectNameToProductForm() {
    console.warn("This method is deprecated please use shopProductForm.addProjectName()");
    return shopProductForm.addProjectName();
}
const input_keyup = (hide_message) => {
    console.warn("This method is deprecated please use shopProductFreeAttributes.onKeyup()");
    return shopProductFreeAttributes.onKeyup(hide_message);
}

function setExtDataUrlFreeAttr() {
    console.warn("This method is deprecated please use shopProductFreeAttributes.setData()");
    return shopProductFreeAttributes.setData();
}

function free_attributte_recalcule(hide_message) {
    console.warn("This method is deprecated please use shopProductFreeAttributes.recalculate()");
    return shopProductFreeAttributes.recalculate(hide_message);
}

function prepareTemplateForAddNewNativeUploadRow(element, currentNumber, parentName, number) {
    console.warn("This method is deprecated please use shopProductImageUpload.beforeAddUpload()");
    return shopProductImageUpload.beforeAddUpload(element, currentNumber, parentName, number);
}

function afterUploadedNativeProgressFile(number) {
    console.warn("This method is deprecated please use shopProductImageUpload.afterUpload()");
    return shopProductImageUpload.afterUpload(number);
}

function quickCheckoutCheckFormData() {
    console.warn("This method is deprecated please use shopQuickCheckout.checkForm()");
    return shopQuickCheckout.checkForm();
}

function triggeringRequiredPropertiesForInputsCreatedAccount() {
    console.warn("This method is deprecated please use shopQuickCheckout._addRequired()");
    return shopQuickCheckout._addRequired();
}

function checkRequiredInputsCreatedAccountOnFullness() {
    console.warn("This method is deprecated please use shopQuickCheckout._checkRequired()");
    return shopQuickCheckout._checkRequired();
}

function addClassErrorForNonFullnessRequiredInputsCreatedAccount() {
    console.warn("This method is deprecated please use shopQuickCheckout._addClassError()");
    return shopQuickCheckout._addClassError();
}

function setObserver() {
    console.warn("This method is deprecated please use shopQuickCheckout._setObserver()");
    return shopQuickCheckout._setObserver();
}

function refreshDataForAllSections() {
    console.warn("This method is deprecated please use shopQuickCheckout._refreshData()");
    return shopQuickCheckout._refreshData();
}

const callbackAfterAjax = function(data) { 
    console.warn("This method is deprecated please use shopQuickCheckout._afterAjax()");
    return shopQuickCheckout._afterAjax(data);
}

const updateCart = (data) => {
    console.warn("This method is deprecated please use shopQuickCheckout.updateCart()");
    return shopQuickCheckout.updateCart(data);
}

const sendAjaxCheckout = function(url, dataType, data, callback) {
    console.warn("This method is deprecated please use shopQuickCheckout._sendAjax()");
    return shopQuickCheckout._sendAjax(url, dataType, data, callback);
}

function beforeSendData(data) {
    console.warn("This method is deprecated please use shopQuickCheckout._beforeAjax()");
    return shopQuickCheckout._beforeAjax(data);
}

function showShippingForm(id) {
    console.warn("This method is deprecated please use shopQuickCheckout.showShipping()");
    return shopQuickCheckout.showShipping(id);
}

function checkPaymentForm() {
    console.warn("This method is deprecated please use shopQuickCheckout.checkPayment()");
    return shopQuickCheckout.checkPayment();
}

function showPaymentForm(paymentMethod) {
    console.warn("This method is deprecated please use shopQuickCheckout.showPayment()");
    return shopQuickCheckout.showPayment(paymentMethod);
}

function validateShippingMethods() {
    console.warn("This method is deprecated please use shopQuickCheckout._validateShipping()");
    return shopQuickCheckout._validateShipping();
}

function checkAGB() {
    console.warn("This method is deprecated please use shopQuickCheckout._checkAGB()");
    return shopQuickCheckout._checkAGB();
}

function checkNoReturn() {
    console.warn("This method is deprecated please use shopQuickCheckout._checkNoReturn()");
    return shopQuickCheckout._checkNoReturn();
}

function validateFormAdvancedSearch(name) {
    console.warn("This method is deprecated please use shopSearch.validate()");
    return shopSearch.changeSorting(name);
}

function updateSearchCharacteristic(url, category_id) {
    console.warn("This method is deprecated please use shopSearch.updateCharacteristic()");
    return shopSearch.updateCharacteristic(url, category_id);
}
function moveBlockAnfrageGuest() {
    console.warn("This method is deprecated please use shopUser.moveOrderAndOfferLinkForGuest()");
    return shopUser.moveOrderAndOfferLinkForGuest();
}

function validateDeleteSecondAddressFileds(fields) {
    console.warn("This method is deprecated please use shopUser._validateSecondAddress()");
    return shopUser._validateSecondAddress(fields);
}

function validateAddFirmaCodeAndTaxNumber(formName, fields) {
    console.warn("This method is deprecated please use shopUser._validateFirmaAndTax()");
    return shopUser._validateFirmaAndTax(formName, fields);
}

function validateRegistrationForm(urlcheckdata, name) {
    console.warn("This method is deprecated please use shopUser.validateRegistration()");
    return shopUser.validateRegistration(urlcheckdata, name);
}

function validateAccountFields(name) {
    console.warn("This method is deprecated please use shopUser.validateAccount()");
    return shopUser.validateAccount(name);
}


function countrySelectEvents() {
    console.warn("This method is deprecated please use shopStates.countrySelectEvents()");
    return shopStates.countrySelectEvents();
}