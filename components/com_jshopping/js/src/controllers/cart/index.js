import shopHelper from '../../common/helper/index.js';

class ShopCart {

    constructor() {
        this.ajax = null;
		
		document.addEventListener('DOMContentLoaded', () => {
            if (shopHelper.isCartPage()) {

                this._updateEvents();

                const observer = new MutationObserver(() => this._updateEvents());
				if(document.getElementById('comjshop')){
					observer.observe(document.getElementById('comjshop'), {
						attributes: true,
						childList: true,
						characterData: true
					});
				}
				
				if(document.getElementById('save_upload_btn')){
					document.getElementById('save_upload_btn').addEventListener('click',function(e){
						if(!shopCart.validateUploadImage()){					
							e.preventDefault();
						}
					});
				}
            }
			
			if(document.querySelector('#country.country_cart')){
				var state_val = 0;
				if(document.getElementById('state')){
					state_val = document.getElementById('state').value;
				}
				document.querySelector('#country.country_cart').setAttribute("onchange", "shopCart.getShippingPrice(this.id, this.value, "+state_val+");");
			}      
	  });
    }

    validateUploadImage() {
        if (uploadImage.isUploadActivated()) {
			
            let msgValidate = {error: []};
			
            let result = true;
            let errors = {
                error: []
            };
            let images = this.getUploadedImageInfo('form[name=updateCart] .nativeProgressUploads[data-native-uploads-block-number]');

            images.forEach(img => {
                let id = parseInt(img.productArrayKey);

                if (uploadImage.getUploadImagesCount(id) > 0) {

                    if (!uploadImage.isUsedAllQuantityById(id) &&
                        errors.error.indexOf(Joomla.JText._('COM_SMARTSHOP_NATIVE_UPLOAD_REMAINING_QUANTITY')) === -1
                    ) {
                        
                        errors.error.push(Joomla.JText._('COM_SMARTSHOP_NATIVE_UPLOAD_REMAINING_QUANTITY'));
                        result = false;
                    }

                    if (uploadImage.checkIfZero() &&
                        errors.error.indexOf(Joomla.JText._('COM_SMARTSHOP_NATIVE_UPLOAD_EXIST_ZERO_QUANTITY_IN_ROW')) === -1
                    ) {
                        errors.error.push(Joomla.JText._('COM_SMARTSHOP_NATIVE_UPLOAD_EXIST_ZERO_QUANTITY_IN_ROW'));
                        result = false;
                    }
					
                }
            });
			var inputs = document.querySelectorAll('.nativeProgressUpload__fileInput');
			if(inputs){
				inputs.forEach(function(input){ 
					if(input.value.length > 0){
						var this_parent = input.parentNode;
						var _qtyInput = this_parent.querySelector('.nativeProgressUpload-imageInfo__qtyInput').value;
						if(_qtyInput <= 0){
							errors.error.push(Joomla.JText._('COM_SMARTSHOP_NATIVE_UPLOAD_EXIST_ZERO_QUANTITY_IN_ROW'));
							
							scrollTo('top');
							Joomla.renderMessages(errors);
							result = false;
						}
					}
				});
			}

            if (!result) {
                Joomla.renderMessages(errors);
                scrollTo('top');
            }
			
			
            return result;
        }

        return true;
    }

    beforeAddUploadImageBlock(element, currentNumber, parentName, number) {
        let describe = element.querySelector('.nativeProgressUpload-imageInfo__describeInput');

        element.querySelector('.nativeProgressUpload-imageInfo__qtyInput').setAttribute('onchange', `shopCart.updateUploadImageQuantity('${number}');`);
        element.querySelector('.nativeProgressUpload-imageInfo__removeFileLink').setAttribute('onclick', `uploadImage.deleteUploadInCart("${parentName}", ${currentNumber}, event)`);

        if (describe) {
            element.querySelector('.nativeProgressUpload-imageInfo__describeInput')
                .setAttribute('onclick', `shopCart.updateDescribeUploadImage('${number}', event)`);
        }

        return element;
    }

    _updateEvents() {
        let url = `${location.origin}/index.php?option=com_jshopping&controller=cart&task=refresh`;
	
        document.querySelectorAll('#comjshop form[name=updateCart] input[name^=quantity], #comjshop form[name=updateCart] select')
            .forEach((el) => {
			el.addEventListener('focusout', () => {

                let ajaxData = shopHelper.serializeArray(document.querySelector('form[name=updateCart]'));
				let uploadedImages = this.getUploadedImageInfo('form[name=updateCart]  .nativeProgressUploads[data-native-uploads-block-number]');

                if (uploadedImages) {
                    ajaxData.push({
                        name: 'updatedForCartUploads',
                        value: JSON.stringify(uploadedImages)
                    });
                }
                setTimeout(() => {
					this.ajax = fetch(url, {
						method: 'POST',
						headers: {
							'Content-Type': 'text/html'
						},
						body: JSON.stringify(ajaxData),
						cache: 'no-cache',
					})
					
					.then(response => response.text())

					.then(data => {
						var parser = new DOMParser();
						var doc = parser.parseFromString(data, "text/html");
						let html = doc.querySelector('#comjshop');

						if (html) {
							document.querySelector('#comjshop').innerHTML = html.innerHTML;
						}
					});
				
                }, 100);

            });
			});
    }

    getUploadedImageInfo(selector, productsCount) {
        let blocks = document.querySelectorAll(selector);
        let data = [];

        blocks.forEach((block) => {
            let uploadNumber = block.dataset.nativeUploadsBlockNumber;
            let uploads = block.querySelectorAll('[data-native-upload-row-number]');

            if (uploads) {
                uploads.forEach((upload) => {
                    let describe = upload.querySelector('.nativeProgressUpload-imageInfo__describeInput');
                    let rowNumber = upload.dataset.nativeUploadRowNumber;
                    let qty = upload.querySelector('.nativeProgressUpload-imageInfo__qtyInput').value;
                    let descriptionText = (describe) ? describe.value : '';
                    let imagePreviewName = upload.querySelector('.nativeProgressUpload__imageInput').value;
                    let fileName = upload.querySelector('.nativeProgressUpload__fileInput').value;

                    let dataTempToSend = {
                        productArrayKey: uploadNumber,
                        uploadNumber: rowNumber,
                        qty,
                        descriptionText,
                        imagePreviewName,
                        fileName
                    };

                    if (productsCount) {
                        dataTempToSend.totalCountOfProducts = productsCount;
                    }

                    data.push(dataTempToSend);
                });

            }
        });

        return data;
    }

    updateDescribeUploadImage(number, productsCount) {
	
        let ajaxData = this.getUploadedImageInfo(`.nativeProgressUploads[data-native-uploads-block-number="${number}"]`, productsCount);

        if (ajaxData) {
            let data = {
                updatedForCartUploads: JSON.stringify(ajaxData),
                isAjax: true
            };

			this.ajax = fetch('/index.php?option=com_jshopping&controller=cart&task=refresh', {
				method: 'POST',
				headers: {
					'Content-Type': 'text/html'
				},
				body: JSON.stringify(data),
				cache: 'no-cache',
			})
			
			.then(response => response.text())

			.then();
        }
    }

    updateUploadImages(selector, productsCount, reloadPage = true) {
        let ajaxData = [];

        if (typeof selector === 'number') {
            ajaxData = this.getUploadedImageInfo(`.nativeProgressUploads[data-native-uploads-block-number="${selector}"]`, productsCount);
        } else {
            ajaxData = this.getUploadedImageInfo(selector, productsCount);
        }

        if (ajaxData) {
            let data = {
                updatedForCartUploads: JSON.stringify(ajaxData),
                isAjax: true
            };
            data = this.setProductsQty(data);

			this.ajax = fetch('/index.php?option=com_jshopping&controller=cart&task=refresh', {
				method: 'POST',
				headers: {
					'Content-Type': 'text/html'
				},
				body: JSON.stringify(data),
				cache: 'no-cache',
			})
			
			.then(response => response.text())

			.then(data => {
				if (reloadPage) {
					var parser = new DOMParser();
					var doc = parser.parseFromString(data, "text/html");
					let html = doc.querySelector('#comjshop');

					if (html) {
						document.querySelector('#comjshop').innerHTML = html.innerHTML;
						this._updateEvents();
					}
				}
			});            
        }
    }

    updateUploadImageQuantity(number) {
        uploadImage.updateQuantity(number);
        this.updateUploadImages('form[name="updateCart"] .nativeProgressUploads[data-native-uploads-block-number]', true);
    }

    afterUploadFile(number, that) {
        if (!uploadImage.isMultiUpload(number)) {
            let element = that.parentBlockElement.closest('.list-group-item');

            if (element) {
                let qtyUpload = element.querySelector('input[id^="quantity"]');
               // that.parentBlockElement.querySelector('.nativeProgressUpload-imageInfo__qtyInput').value = qtyUpload.value;
                that.parentBlockElement.querySelector('.nativeProgressUpload-imageInfo__qtyInput').value = 1;
            } else {
                that.parentBlockElement.querySelector('.nativeProgressUpload-imageInfo__qtyInput').value = 1;
            }
        }

        this.updateUploadImages('form[name="updateCart"]  .nativeProgressUploads[data-native-uploads-block-number]');
    }

    setProductsQty(data) {
        var cartItems = document.querySelectorAll('form[name="updateCart"] ul.list-group > li.list-group-item');
        
        if (cartItems) {
            var iterationNumb = 0;

            for(var cartItem of cartItems) {
                var value = cartItem.querySelector('[name^="quantity["]').value;

                if (value) {
                    data[`quantity[${iterationNumb}]`] = value;
                }

                iterationNumb++;
            }
        }

        return data;
    }

    getShippingPrice(id, value, state_val){
		state_val = state_val ? state_val : 0;
        let data = {
            country_id : value,
            state : state_val,
            id,
            state_val
        };
		
		var xhr = new XMLHttpRequest();
		xhr.open("GET", Joomla.getOptions('urlGetShippingPrice') + '?' + shopHelper.objToStr(data), true);
		xhr.setRequestHeader('Content-Type', 'application/json');
		xhr.send(data);
		xhr.onload = () => {			
			var json = JSON.parse(xhr.responseText);
			if(document.querySelector('.summ_delivery span') != null) document.querySelector('.summ_delivery span').innerText = json.summ_delivery;
			if(document.querySelector('.subtotal span') != null) document.querySelector('.subtotal span').innerText = json.summ;
			if(document.querySelector('.discount span') != null) document.querySelector('.discount span').innerText = json.discount;
			if(document.querySelector('.free_discount span') != null) document.querySelector('.free_discount span').innerText = json.free_discount;
			if(document.querySelector('.fullsumm span') != null) document.querySelector('.fullsumm span').innerText = json.fullsumm;

			if (json.tax_list) {
					var i=0;
                    for (let percent in json.tax_list) {

                        if (json.tax_list.hasOwnProperty(percent)) {
                            let value = json.tax_list[percent];
                            var tax_list_value=json.tax_list_name;
                            tax_list_value=tax_list_value+" "+this._formattax(percent,json.jshopConfig.decimal_symbol)+'%:';
                            tax_list_value=tax_list_value+'<span class="float-right">' + value + '</span>';
                            jQuery('.tax_list_value').html(tax_list_value);
                            jQuery('.tax_list_value').css('display', 'block');
                        }
						i++;
                    }
					var div = document.createElement('div');
					div.innerHTML = json.smallCartMarkup;			
					var divs_prices=div.querySelectorAll('.list-group');
					var div_prices=div.querySelector('.smartshop_cart_price_tax_cell');
					var div_prices_total=divs_prices[1];				
					const e = div_prices_total.querySelector('.summ_package');				
					if (e!=null) e.parentElement.removeChild(e);
					document.querySelector('.cart-calculation-block .list-group').innerHTML=div_prices_total.innerHTML;
					document.querySelector('.smartshop_cart_price_tax_cell').innerHTML=div_prices.innerHTML;
			} else {
				if(document.querySelector('.tax_list_value') != null){
					shopHelper.hide(document.querySelector('.tax_list_value'));
				}
			}
		}
	   
    }

    _formattax(percent,decimal_symbol){
        return percent.replace('.',decimal_symbol);
    }


}

export default new ShopCart();