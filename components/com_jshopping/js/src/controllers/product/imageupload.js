import uploadImage from '../../common/upload_image/index.js';

class ShopProductImageUpload {

    constructor() {

        document.addEventListener('DOMContentLoaded', (e) => {
            var formElement = document.querySelector('form[name="product"]');
            var $this = this;

            if (formElement) {
                var btnCartElement = formElement.querySelector('.btn-add-product-to-cart');
                var btnCheckoutElement = formElement.querySelector('.btn-add-product-to-checkout');
                var btnOneClickElement = formElement.querySelector('.btn-one-click-checkout');

                if (btnCartElement) {
                    btnCartElement.addEventListener('click', function (e) {
                        $this.isAddProductToCartBtn = true;
                    });
                }
                if (btnCheckoutElement) {
                    btnCheckoutElement.addEventListener('click', function (e) {
                        $this.isAddProductToCartBtn = true;
                    });
                }
                if (btnOneClickElement) {
                    btnOneClickElement.addEventListener('click', function (e) {
                        $this.isAddProductToCartBtn = true;
                    });
                }

				formElement.addEventListener('submit', function(e) {
					let butsubmit =e.submitter.getAttribute('class');
					
					if (uploadImage.isUploadActivated() && (butsubmit.indexOf('btn-add-product-to-cart') > 0 || butsubmit.indexOf('btn-add-product-to-checkout') > 0)) {
						
						let result = true;
						let msgValidate = {error: []};
						let uploadedBlockId = 0
						var uploadOptions = Joomla.getOptions('uploadData');
                        var maxAllowUploads = Joomla.getOptions('max_allow_uploads');
						var isUploadRequired = (uploadOptions.is_required_upload && uploadOptions.is_required_upload == 1);
						let maxUploads = document.querySelector('.numbOfMaxUploadsFiles').dataset.maxUploadFiles;
                        var qtyError = false;
						
						let isIndependFromQty = uploadOptions.is_upload_independ_from_qty;
						
						if (maxUploads == 'INF' && uploadImage.isUsedAllQuantity()) {
							return true;
						}

				if (!uploadImage.isUsedAllQuantity() && maxAllowUploads > 1 && (!isIndependFromQty || isIndependFromQty == 0) && uploadImage.getUploadImagesCount(uploadedBlockId) > 0 || (uploadImage.getUploadImagesCount(uploadedBlockId) == 0 && isUploadRequired)) {
							msgValidate.error.push(Joomla.JText._('COM_SMARTSHOP_NATIVE_UPLOAD_REMAINING_QUANTITY'));
                            qtyError = true;
                            result = false;
						}
			  
						if (uploadImage.getUploadImagesCount(uploadedBlockId) > 0  ) {
							if (!uploadImage.isUsedAllQuantity() && (!isIndependFromQty || isIndependFromQty == 0) && maxAllowUploads > 1) {
								if(!qtyError){
                                    msgValidate.error.push(Joomla.JText._('COM_SMARTSHOP_NATIVE_UPLOAD_REMAINING_QUANTITY'));
                                }
                                result = false;
							}
				
							if (uploadImage.checkIfZero()) {
								msgValidate.error.push(Joomla.JText._('COM_SMARTSHOP_NATIVE_UPLOAD_EXIST_ZERO_QUANTITY_IN_ROW'));
								result = false;
							}

							if (uploadImage.isRemainingQuantityNegative() && (!isIndependFromQty || isIndependFromQty == 0)) {
								msgValidate.error.push(Joomla.JText._('COM_SMARTSHOP_UPLOAD_FILES_REMAINING_QTY_CANNOT_BENEGATIVE'));
								result = false;
							}
						}
				
						if (!result) {
							Joomla.renderMessages(msgValidate);
							e.preventDefault()
						}
						
				return result;
					}
				});
            }

        });
    }

    beforeAddUpload(element, currentNumber, parentName, number) {
        let qtyElement    = element.querySelector('.nativeProgressUpload-imageInfo__qtyInput');
        let removeElement = element.querySelector('.nativeProgressUpload-imageInfo__removeFileLink');
    
        qtyElement.setAttribute('onchange', `uploadImage.updateQuantity(${number}, this);shopProductFreeAttributes.setData();`);
        removeElement.setAttribute('onclick', `shopProductFreeAttributes.setData();uploadImage.deleteUpload("${parentName}", '${currentNumber}', event)`);
    
        return element;
    }

    afterUpload(number) {
        if (!uploadImage.isMultiUpload(number)) {
            let productQty = document.querySelector('#quantity');
            let uploadQty = document.querySelector(`[data-native-uploads-block-number="${number}"] input.nativeProgressUpload-imageInfo__qtyInput`);
    
            uploadQty.value = productQty.value;
            window.shopJsTrigger.trigger("ShopProductImageUpload", "afterUpload", [number, productQty, uploadQty]);
        }
    }
}

export default new ShopProductImageUpload();