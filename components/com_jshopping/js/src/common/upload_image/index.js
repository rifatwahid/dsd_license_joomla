import shopHelper from '../helper/index.js';
import shopCart from '../../controllers/cart/index.js';
import shopProductImageUpload from '../../controllers/product/imageupload.js';
import UploadFile from '../upload_file/index.js';

class UploadImage {

    constructor() {
        this.afterUpload = this.afterUpload.bind(this);
    }

    isUploadActivated() {
        let uploads = document.querySelector('.nativeProgressUploads');
        let upload = document.querySelector('.nativeProgressUpload');

        return Boolean(uploads || upload);
    }

    isMultiUpload(number) {
        let element = document.querySelector(`[data-native-uploads-block-number="${number}"]`);
		let remainingBlock = '';
		if(element) remainingBlock = element.querySelector('.nativeMultiuploadProgressHeader__remainingInfo');

        return (remainingBlock) ? true : false;
    }

    _getNumberByChildren(children) {
        let element = children.closest('.nativeProgressUploads[data-native-uploads-block-number]');
        return parseInt(element.dataset.nativeUploadsBlockNumber);
    }

    checkIfZero() {
        let uploads = document.querySelectorAll('.nativeProgressUpload');
        let zero = false;
        
        uploads.forEach(u => {
            let input = u.querySelector('input.nativeProgressUpload__fileInput').value;
            
            if (input.length) {
                let qty = +u.querySelector('.nativeProgressUpload-imageInfo__qtyInput');
                if (qty.value === 0) zero = true;
            }
        });

        return zero;
    }

    isUsedAllQuantityById(id) {
        let joomlaUploadData = Joomla.getOptions('uploadData')[id] || Joomla.getOptions('uploadData');
        let result = true;

        let element = document.querySelector(`[data-native-uploads-block-number="${id}"]`);
        let isIndependFromQty = joomlaUploadData.is_upload_independ_from_qty;
        let remainingCurrentQty = element.querySelector('.nativeMultiuploadProgressHeader input.remainingCurrentQty')

        if (remainingCurrentQty && !isIndependFromQty) {
            result = (remainingCurrentQty.value > 0 || remainingCurrentQty.value < 0) ? false : true;

        }
		
		var els = document.querySelectorAll('.nativeProgressUpload__fileInput_is_required_upload');
		if(els){
		els.forEach(function(){
			if (!this.value){
				result = false;
			}	
		});
		}
		
        return result;
    }

    isUsedAllQuantity() {
        let result = false;
        let element = document.querySelector('.nativeMultiuploadProgressHeader input.remainingCurrentQty');

        if (element) {
            result = (element.value != 0) ? false : true;
        }
        
        return result;
    }

    isRemainingQuantityNegative() {
        let result = false;
        let element = document.querySelector('.nativeMultiuploadProgressHeader input.remainingCurrentQty');

        if (element) {
            result = (element.value < 0) ? true : false;
        }
        
        return result;
    }

    _getUploadFilesCount(selector) {
        let element = document.querySelector(selector);
        let count = -1;

        if (element) {
            //count = 1;
            count = element.querySelectorAll('.nativeProgressUpload').length;
        }

        return count;
    }

    getUploadImagesCount(number) {
        let elements = document.querySelector(`[data-native-uploads-block-number="${number}"]`);
        let count = 0;

        if (elements) {
            let inputs = elements.querySelectorAll('.nativeProgressUpload__fileInput');
            
            inputs.forEach(i => {
                
                if (i.value.length > 0) {
                    count++;
                }
            });
        }
        
        return count;
    }

    afterUpload(data, that) {
        if (data.status && data.status == 'success' && that.parentBlockElement) {

            let imgInfo = that.parentBlockElement.querySelector('.nativeProgressUpload-imageInfo');
            let imgDescription = imgInfo.querySelector('.nativeProgressUpload-imageInfo__description');
            let hiddenInput = imgInfo.querySelector('.nativeProgressUpload__fileInput');
            let uploadsParentElement = that.parentBlockElement.closest('.nativeProgressUploads');
            let remainingQty = uploadsParentElement.querySelector('.remainingTotalQty');
            let qtyInput = imgInfo.querySelector('.nativeProgressUpload-imageInfo__qtyInput');
            let imgHiddenInput = imgInfo.querySelector('.nativeProgressUpload__imageInput');
            let imgElement = imgInfo.querySelector('.nativeProgressUpload-imageInfo__img');
            let imgLink = imgInfo.querySelector('.nativeProgressUpload-imageInfo__link');
            let uploadBlockNumber = uploadsParentElement.dataset.nativeUploadsBlockNumber;
    
            if (this.isMultiUpload(uploadBlockNumber)) {
                if (remainingQty && this.getUploadImagesCount(uploadBlockNumber) == 0) {
                   // qtyInput.value = remainingQty.value;
                    qtyInput.value = 1;
                } else {
                    qtyInput.value = 1;
                }
            }

            that.parentBlockElement.classList.remove('nativeProgressUpload--nouploaded');
            that.parentBlockElement.classList.add('nativeProgressUpload--uploaded');
            imgElement.src = data.previewImg;
            imgLink.href = data.pathToFile;
            imgHiddenInput.value = data.previewName; 
            imgDescription.innerHTML = `<a href="${data.pathToFile}" class="nativeProgressUpload-imageInfo__description-link" target="_blank">${data.fileName}</a>`;
            hiddenInput.value = data.fileName;

            if (shopHelper.isCartPage() || shopHelper.isOrderPage()) {
                shopCart.afterUploadFile(uploadBlockNumber, that);
            } else {
                shopProductImageUpload.afterUpload(uploadBlockNumber);
            }

            imgInfo.classList.remove('display--none');
            this.updateQuantity(this._getNumberByChildren(imgHiddenInput));
            
            if (!shopHelper.isCartPage()) {
                shopProductAttributes.reloadSelectAndPrice();
            }

            return true;
        } 
            
        console.error('File success uploaded, but the condition did not work!');
        return false;
    }

    _recalculateQuantity(number, qty) {
        let element = document.querySelector(`[data-native-uploads-block-number="${number}"]`);
        let remainingQty = element.querySelector('.nativeMultiuploadProgressHeader__remainingQty');
        let currentQty = element.querySelector('input[class="remainingCurrentQty"]');

        if (remainingQty) {
            remainingQty.textContent = qty;
        }

        currentQty.value = qty;
    }

    updateQuantity(number) {
        if (!this.isUploadActivated()) return;

        let blockUpload = document.querySelector(`[data-native-uploads-block-number="${number}"]`);
        let remainingQty = blockUpload.querySelector('input[class="remainingTotalQty"]');
        
        if (remainingQty) {
            let qtyInputs = blockUpload.querySelectorAll('input.nativeProgressUpload-imageInfo__qtyInput');
            let currentQty = blockUpload.querySelector('input[class="remainingCurrentQty"]');
            let summ = 0;
            let result = 0;
            qtyInputs.forEach((input) => {
            
				let parents = shopHelper.getParents(input, document.querySelector('.nativeProgressUpload-imageInfo'));
				
                let value = (input.value && !parents[0].classList.contains('display--none')) ? parseInt(input.value) : 0;

                if (value < 0) {
                    summ -= value;
                } else {
                    summ += value;
                }
            });

            result = remainingQty.value - summ;
            currentQty.value = result;

            this._recalculateQuantity(number, result);
        }
    }

    startUpload(url, callback, element, event) {
		var el = document.querySelector('#nativeProgressUpload_allow_files_size');
        if (event.target.classList.contains('nativeProgressUpload__btn')) {
            event.preventDefault();
			if(!el){
				var size = 0;
			}else{
				var size = el.value;
			}
			
			let allow_files_size = size*1024*1024;
            const uploadFile = new UploadFile(allow_files_size);
            uploadFile.upload(url, callback, [], element);
        }
    }

    addNewUpload(blockName, event) {
        event.preventDefault();
        let parentBlock = document.querySelector(blockName);
        let id = parentBlock.dataset.nativeUploadsBlockNumber || 0;
        let joomlaUploadData = Joomla.getOptions('uploadData')[id] || Joomla.getOptions('uploadData');
        let maxUploadFiles = joomlaUploadData.maxFilesUploads;
        let countOfExistsUploadFiles = this._getUploadFilesCount(blockName);

        if (maxUploadFiles !== 'INF' && countOfExistsUploadFiles >= maxUploadFiles ) {
            return false;
        }

        let currentRowNumber = this.getLastNumber(blockName) + 1;
        let parentElement = parentBlock.querySelector('.nativeProgressUploads__rows');
        let templateElement = document.querySelector(' #nativeProgressUploadRow').content.cloneNode(true);
        let uploadBlockNumber = this._getNumberByChildren(parentElement);

        templateElement.querySelector('[data-native-upload-row-number]').dataset.nativeUploadRowNumber = currentRowNumber;
        
        if (shopHelper.isCartPage()) {
            templateElement = shopCart.beforeAddUploadImageBlock(templateElement, currentRowNumber, blockName, uploadBlockNumber);
        } else {
            templateElement = shopProductImageUpload.beforeAddUpload(templateElement, currentRowNumber, blockName, uploadBlockNumber);
        }

        parentElement.appendChild(templateElement);

        if (shopHelper.isCartPage()) {
            shopCart.updateUploadImages('form[name="updateCart"]  .nativeProgressUploads[data-native-uploads-block-number]');
        }

    }


    addNewUploadOrder(blockName, event) {
        event.preventDefault();
        let parentBlock = document.querySelector(blockName);
        let id = parentBlock.dataset.nativeUploadsBlockNumber || 0;
        let joomlaUploadData = Joomla.getOptions('uploadData')[id] || Joomla.getOptions('uploadData');
        let maxUploadFiles = joomlaUploadData.maxFilesUploads;
        let countOfExistsUploadFiles = this._getUploadFilesCount(blockName);

        if (maxUploadFiles !== 'INF' && countOfExistsUploadFiles >= maxUploadFiles ) {
            return false;
        }

        let currentRowNumber = this.getLastNumber(blockName) + 1;
        let parentElement = parentBlock.querySelector('.nativeProgressUploads__rows');
        let templateElement = document.querySelector(blockName+' #nativeProgressUploadRow').content.cloneNode(true);
        let uploadBlockNumber = this._getNumberByChildren(parentElement);

        templateElement.querySelector('[data-native-upload-row-number]').dataset.nativeUploadRowNumber = currentRowNumber;

        if (shopHelper.isCartPage()) {
            templateElement = shopCart.beforeAddUploadImageBlock(templateElement, currentRowNumber, blockName, uploadBlockNumber);
        } else {
            templateElement = shopProductImageUpload.beforeAddUpload(templateElement, currentRowNumber, blockName, uploadBlockNumber);
        }

        parentElement.appendChild(templateElement);

        if (shopHelper.isCartPage()) {
            shopCart.updateUploadImages('form[name="updateCart"]  .nativeProgressUploads[data-native-uploads-block-number]');
        }

    }

    getLastNumber(blockName) {
        let number = -1;
        let element = document.querySelector(blockName);
        let uploads = element.querySelectorAll('.nativeProgressUpload');
        let lastUpload = uploads[uploads.length - 1];

        if (element && uploads.length) {
            number = parseInt(lastUpload.closest('[data-native-upload-row-number]').dataset.nativeUploadRowNumber);
        }

        return number;
    }

    updateQuantityWhenChangeProductQuantity(number, qtyElement) {
        if (!this.isUploadActivated() || !this.isMultiUpload(number)) return;

        let joomlaUploadData = Joomla.getOptions('uploadData')[number] || Joomla.getOptions('uploadData');
        let element = document.querySelector(`[data-native-uploads-block-number="${number}"]`);
        let remainingCurrentQty = element.querySelector('input[class="remainingCurrentQty"]');
        let remainingTotalQty = element.querySelector('input[class="remainingTotalQty"]');
        let remainingQty = element.querySelector('.nativeMultiuploadProgressHeader__remainingQty');
        let isIndepentFromQty = joomlaUploadData.is_upload_independ_from_qty;
        let isRequiredUploaded = joomlaUploadData.is_required_upload;
        let qty = qtyElement;

        if (typeof(qtyElement) === 'object') {
            qty = qtyElement.value;
        }

        if (remainingQty) {
            remainingQty.textContent = isIndepentFromQty ? 0 : qty;
        }

         if (remainingCurrentQty) {
            remainingCurrentQty.value = isIndepentFromQty ? 0 : qty;
        }


        if (remainingTotalQty) {
            remainingTotalQty.value = isIndepentFromQty && !isRequiredUploaded ? 0 : qty;
        }

        this.updateQuantity(number);
    }

    deleteUpload(blockName, number, evt) {
        evt.preventDefault();

        let parentElement = document.querySelector(blockName);
        let blockNumber = parentElement.dataset.nativeUploadsBlockNumber;
        let uploadRowElement = parentElement.querySelector(`[data-native-upload-row-number="${number}"]`);
    
        uploadRowElement.remove();
    
        if (!this.isMultiUpload(blockNumber) && this._getUploadFilesCount(blockName) === 0) {
            this.addNewUpload(blockName, evt);
        }
    
        this.updateQuantity(blockNumber);

        if (shopHelper.isCartPage()) {
            shopCart.updateUploadImages('form[name="updateCart"]  .nativeProgressUploads[data-native-uploads-block-number]');
        } else {
            shopProductAttributes.reloadSelectAndPrice();
        }
    }

    deleteUploadInCart(blockName, number, evt) {
        evt.preventDefault();

        let parentElement = document.querySelector(blockName);
        let productNumber = parentElement.dataset.nativeUploadsBlockNumber;
    
        let data = {
            'uploadArrayKey': number,
            'productArrayKey': productNumber,
            'isAjax': 1
        };
    
		this.ajax = fetch('/index.php?option=com_jshopping&controller=cart&task=removeUploadFile', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
			},
			body: shopHelper.dataTransform(data),
			cache: 'no-cache',
		})
		
		.then(response => response.json())
		
		.then(data => {
			if(data.result){
				this.deleteUpload(blockName, number, evt);
			}
		});
    }
    deleteUploadInOrder(blockName, number, evt) {
        evt.preventDefault();

        let parentElement = document.querySelector(blockName);
        let productNumber = parentElement.dataset.nativeUploadsBlockNumber;


        this.deleteUpload(blockName, number, evt);
    }

    getAmountOfUploads(selectorWhereSearch) {
        var amount = 0;        
		var inputElemnts = document.querySelectorAll(".nativeProgressUpload--uploaded");
        if (inputElemnts) {
            for (var input of inputElemnts) {
				amount++;
            }
        }

        return amount;
    }

}

export default new UploadImage();