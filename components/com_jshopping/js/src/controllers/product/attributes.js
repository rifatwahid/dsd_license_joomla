import shopProduct from './index.js';

class ShopProductAttributes {
	
    constructor() {
        this.ajax = null;
        this.events = [];
        this.free_attr = {};

        document.addEventListener('DOMContentLoaded', () => {
            const jshEAFA = {
                attrHideIds: {},
                attrEach: () => {
					let el = document.querySelectorAll('#comjshop .jshop_prod_attributes [name^=jshop_attr_id]');
					if(el){
						el.forEach(function () {
							let elem = this;
							let $row = elem.parentNode.querySelectorAll('.jshop_prod_attributes');
							if ($row.length) {
								let id = elem.attr('name').replace(/[^0-9]/g, '');

								(jshEAFA.attrHideIds[id]) ? $row.hide(): $row.show();
							}
						});
					}
                }
            };

            jshEAFA.attrHideIds = Joomla.getOptions('attrHideIds');
            jshEAFA.attrEach();

            this.events[this.events.length] = (json) => {
                if (json.eafa_attr_hide) {
                    jshEAFA.attrHideIds = json.eafa_attr_hide;
                    jshEAFA.attrEach();
                }
            };
		
        });

    }

    reloadSelectAndPrice(id_select) {
        if (this.ajax) this.ajax.abort();
        var loadingDiv = document.getElementById('dsd-spinner_loading_block');
        loadingDiv.style.visibility = 'visible';

        let qty = document.querySelector("#productForm #quantity").value;
        let data = {
            change_attr: id_select,
            qty
        };
		var arr;
		var attributeValues = shopProduct.attributeValue;
		for (var index in attributeValues) {
			if(typeof attributeValues[index] !== "undefined" && attributeValues[index] != 0 && index.indexOf('_') < 0){
				data[`attr[${index}]`] = attributeValues[index];
			}else if(index.indexOf('_') > 0){
				let arr = index.split('_');
				data[`attr[${arr[0]}]`] = {};
				var values = document.querySelectorAll('.jshop_attr_id'+arr[0]);
				values.forEach(function(){					
					data[`attr[${arr[0]}]`][`${arr[1]}`] = attributeValues[index];						
				});
			}
		}
        for (let freeAttr in this.free_attr) {
            data[freeAttr] = this.free_attr[freeAttr];
        }

        this.ajaxReloadPageData(data);
    }

    ajaxReloadPageData(data) {

        if (this.ajax) this.ajax.abort();
        var loadingDiv = document.getElementById('dsd-spinner_loading_block');
        loadingDiv.style.visibility = 'visible';
        data['amountOfUploads'] = uploadImage.getAmountOfUploads('.nativeProgressUploads--0');
        var productForm = document.querySelector('#productForm');
        var productDetails = document.querySelector('.product-details');
		var scripts;

        if (!productForm) {
          //  console.warning('Can`t find product form!!');
            return;
        }
        if (beforeAjaxReloadPageDataEvents) {
			for(var i=0; i<beforeAjaxReloadPageDataEvents.length; i++){
				beforeAjaxReloadPageDataEvents[i](data);
			}
        }
		this.ajax = new XMLHttpRequest();
		this.ajax.open("GET", shopProduct.urlUpdatePrice + '&' + shopHelper.objToStr(data), true);
		this.ajax.setRequestHeader('Content-Type', 'application/json');
		this.ajax.send(data);
	
		this.ajax.onload = () => {
        
       
				var json = JSON.parse(this.ajax.responseText);
                if (typeof json.dont_update_page_data !== 'undefined' && json.dont_update_page_data == true) {
                    return;
                }
				
                // Change attrs
                for (var jsonKeyName in json) {
                    if (json.hasOwnProperty(jsonKeyName)) {
                        var attrId = jsonKeyName.match(/id_(\d*)/);

                        if (attrId && attrId[1]) {
                            var attrElement = productForm.querySelector('#block_attr_sel_' + attrId[1]);

                            if (attrElement) {
                                var attrVal = json[jsonKeyName];
                                attrElement.innerHTML = attrVal;
                                var attrElementContainer = attrElement.closest('.jshop_prod_attributes')

                                if (attrElementContainer) {
                                    if (!attrVal) {
                                        attrElementContainer.style.display = 'none';
                                    } else {
                                        attrElementContainer.style.display = 'block';
                                    }
                                }
                            }
                        }
                    }
                }

                let quantityElement = productForm.querySelector('.product__quantity');
                if (json.quantity_markup && quantityElement) {
                    if (json.quantity_markup.indexOf('product-details__prices')>0) quantityElement.innerHTML = json.quantity_markup;
                }
                
                var productCode = productDetails.querySelector('.product-details__code');

                if (productCode && json.ean) {
                    productCode.innerHTML = json.ean;
                }

                let freeAttrsElement = productForm.querySelector('.free-attributes');
                if (json.free_attrs && freeAttrsElement) {
                    freeAttrsElement.innerHTML = json.free_attrs;
                }

                var productUpload = productDetails.querySelector('#product-details__uploads');
                if (productUpload && json.upload_data !== undefined) {
                    productUpload.innerHTML = (json.upload_data.markUp) ? json.upload_data.markUp : '';

                    if (json.upload_data.jsVariables && json.upload_data.jsVariables.options) {
                        var jsVariablesOptions = json.upload_data.jsVariables.options;

                        for (var key in jsVariablesOptions) {
                            if (jsVariablesOptions.hasOwnProperty(key)) {
                                Joomla.optionsStorage[key] = jsVariablesOptions[key];
                            }
                        }
                    }
                }

                var productDemofiles = productDetails.querySelector('#ep-mail-sample-order-con');
                if (productDemofiles && json.demofiles !== undefined) {
                    productDemofiles.innerHTML = json.demofiles ? json.demofiles : '';
                }

                var productCartBtn = productDetails.querySelector('#product-details__cart');
                if (productCartBtn && json.cart_button_markup !== undefined) {
                    productCartBtn.innerHTML = json.cart_button_markup ? json.cart_button_markup : '';
                }

                var productWishlistBtn = productDetails.querySelector('#product-details__wishlist');
                if (productWishlistBtn && json.wishlistBtnMarkup !== undefined) {
                    productWishlistBtn.innerHTML = json.wishlistBtnMarkup ? json.wishlistBtnMarkup : '';
                }

                var productPriceMarkup = productDetails.querySelector('#product-details__prices');
                if (productPriceMarkup && json.productPriceMarkup !== undefined) {
                   productPriceMarkup.innerHTML = json.productPriceMarkup ? json.productPriceMarkup : '';
                }

                var productQty = productDetails.querySelector('#quantity');
                if (productQty && json.product_quantity !== undefined) {
                    productQty.value = json.product_quantity;
                }
				if(document.querySelector("#block_price")){
					document.querySelector("#block_price").innerHTML = json.price;
				}

                if (json.calculatedPrice != null) {
                    this.reloadCurrentPrice(json.calculatedPrice);
                }

                for (let key in json) {
					var el = document.querySelector(`#pricelist_from_${key.substr(3)}`);
                    if (key.includes('pq_') && el) {
                        el.innerHTML = json[key];
                    }
                }

                let available_text = document.querySelector("#available-text");
				if(available_text){
					if (json.available == "0") {
						available_text.innerHTML = json.available_text;

						if (available_text.classList.contains(document.querySelector('.text-success'))) {
							available_text.classList.toggle('text-danger text-success');
						}

					} else {
						available_text.innerHTML = json.available_text;

						if (available_text.classList.contains(document.querySelector('.text-danger'))) {
							available_text.classList.toggle('text-danger text-success');
						}
					}
				}
	
				if(document.querySelector("#production_time") != null){
					if(json.production_time>0){
						 document.querySelector("#production_time").innerHTML = json.production_time;
						 document.querySelector("#production_time").parentNode.classList.remove("hidden");
					}else{
						document.querySelector("#production_time").parentNode.classList.add("hidden");
					}
				}


				if(document.querySelector("#product_qty") != null){
					if (json.qty > 0) {
						document.querySelector("#product_qty").innerHTML = json.qty;
						document.querySelector("#product_qty").parentNode.classList.remove('hidden');
					} else {
						if (document.querySelector("#product_qty") && !document.querySelector("#product_qty").parentNode.contains(document.querySelector('hidden'))) document.querySelector("#product_qty").parentNode.classList.add('hidden');
					}
                }

                if (json.media_block) {
                    document.querySelector('#image-video-block').innerHTML = json.media_block;
                    initJSlightBox();
                }

                if (json.updated_product_weight_formated) {
                    var productWeightEl = document.querySelector('#product-weight .product-weight__weight');

                    if (productWeightEl) {
                        productWeightEl.innerHTML = json.updated_product_weight_formated;
                    }
                }

                var containerOfBuldPrices = document.querySelector('#productBulkPrices');
                if (containerOfBuldPrices) {
                    if (json.bulk_prices != undefined) {
                        containerOfBuldPrices.innerHTML = json.bulk_prices;
                    } else {
                        containerOfBuldPrices.innerHTML = '';
                    }
                }

                var productDescriptionEl = document.querySelector('#description #description__text');
                if (productDescriptionEl && json.productDescription != undefined) {
                    productDescriptionEl.innerHTML = json.productDescription;
					scripts = productDescriptionEl.getElementsByTagName('script');
					if(scripts.length){
						for (var n = 0; n < scripts.length; n++){
							eval(scripts[n].innerHTML);
						}
					}
                }

                var productShortDescriptionEl = document.querySelector('#product-details__short-description');
                if (productShortDescriptionEl && json.productShortDescription != undefined) {
                    productShortDescriptionEl.innerHTML = json.productShortDescription;
					scripts = productShortDescriptionEl.getElementsByTagName('script');
					if(scripts.length){
						for (var n = 0; n < scripts.length; n++){
							eval(scripts[n].innerHTML);
						}
					}
                }

                var productExtraFieldsEl = document.querySelector('#product-details__extra-fields');
                if (productExtraFieldsEl && json.extra_fields_markup != undefined) {
                    productExtraFieldsEl.innerHTML = json.extra_fields_markup;
                }

                var productRelatedsEl = document.querySelector('#product__relateds');
                if (productRelatedsEl && json.relatedProdsMarkup != undefined) {
                    productRelatedsEl.innerHTML = json.relatedProdsMarkup;
                }

                var productCodeEl = document.querySelector('#product_code');
                if (productCodeEl && json.productCodeMarkup != undefined) {
                    productCodeEl.innerHTML = json.productCodeMarkup;
                }

				for (var i = 0, len = this.events.length; i < len; i++) {
					this.events[i].call( this.events[i], json);
                }

                if (reloadAttribEvents) {
                    for (var i = 0, len = reloadAttribEvents.length; i < len; i++) {
                        reloadAttribEvents[i].call(reloadAttribEvents[i], json);
                    }
                }
				
				if(document.querySelector('.shop_cart_btn') != null){ if (json.show_buttons_cart=="1") {shopHelper.hide(document.querySelector('.shop_cart_btn'));}else{shopHelper.show(document.querySelector('.shop_cart_btn'));}}
				if(document.querySelector('.nativeProgressUpload__btn') != null){if (json.show_buttons_upload=="1") {shopHelper.hide(document.querySelector('.nativeProgressUpload__btn'));}else{shopHelper.show(document.querySelector('.nativeProgressUpload__btn'));}}
				if(document.querySelector('.nativeProgressUploads') != null){if (json.show_buttons_upload=="1") {shopHelper.hide(document.querySelector('.nativeProgressUploads'));}else{shopHelper.show(document.querySelector('.nativeProgressUploads'));}}
				if(document.querySelector('.shop_editor_btn') != null){if (json.show_buttons_editor=="1") {shopHelper.hide(document.querySelector('.shop_editor_btn'));}else{shopHelper.show(document.querySelector('.shop_editor_btn'));}}

                this.reloadValue();
				if(active_el){
					document.getElementsByName(active_el)[0].select();
				}        
				if (afterAjaxReloadPageDataEvents) {
					for(var i=0; i<afterAjaxReloadPageDataEvents.length; i++){
						afterAjaxReloadPageDataEvents[i](json);
					}
				} 
            }
    }

    ajaxReloadProductList(data) {
        if (this.ajax) this.ajax.abort();
        var ajaxUrl = '/index.php?option=com_jshopping&controller=product&task=ajax_attrib_select_and_price&ajax=1&Itemid=101';
        var xhr = new XMLHttpRequest();
		xhr.open("GET", ajaxUrl + '&' + shopHelper.objToStr(data), true);
		xhr.setRequestHeader('Content-Type', 'application/json');
		xhr.send(data);
		xhr.onload = () => {			
			var json = JSON.parse(xhr.responseText);
            if (json && json.product_id) {
                var productElement = document.querySelector('[data-product-id="' + json.product_id + '"]');

                if (productElement) {
                    var productPrice = productElement.querySelector('.cart-product__price');
                    var productImage = productElement.querySelector('img.card-img-top');

                    var cartBtnElement = productElement.querySelector('.shop_cart_btn');
                    var editorBtnElement = productElement.querySelector('.shop_editor_btn');
                    var blockOfFreeAttrs = productElement.querySelector('.free-attributes');
                    var cartProductCart = productElement.querySelector('#cart-product__cart');

                    if (cartBtnElement) {
                        cartBtnElement.style.display = (json.show_buttons_cart == '0') ? 'block' : 'none';
                    }

                    if (editorBtnElement) {
                        editorBtnElement.style.display = (json.show_buttons_editor == '0') ? 'block' : 'none';
                    }

                    let quantityElement = productElement.querySelector('.product_quantity_list');
                    if (json.quantity_markup && quantityElement) {
                        quantityElement.innerHTML = json.quantity_markup;
                    }
                    // Change price
                    if (productPrice && json.calculatedPrice) {
                        productPrice.innerHTML = json.calculatedPrice;
                    }

                    // Change main image
                    if (productImage && json.main_image_url && productImage.src != json.main_image_url) {
                        productImage.src = json.main_image_url;
                    }

                    var productCode = productElement.querySelector('.cart-product__code');
                    var productWeight = productElement.querySelector('.cart-product__weight');

                    if (productCode && json.ean) {
                        productCode.innerHTML = json.ean;
                    }

                    if (productWeight && json.weight) {
                        productWeight.innerHTML = json.weight;
                    }

                    // Change attrs
                    for (var jsonKeyName in json) {
                        if (json.hasOwnProperty(jsonKeyName)) {
                            var attrId = jsonKeyName.match(/id_(\d*)/);

                            if (attrId && attrId[1]) {
                                var attrElement = productElement.querySelector('#block_attr_sel_' + attrId[1]);

                                if (attrElement) {
                                    var attrVal = json[jsonKeyName];
                                    attrElement.innerHTML = attrVal;
                                    var attrElementContainer = attrElement.closest('.jshop_prod_attributes')

                                    if (attrElementContainer) {
                                        if (!attrVal) {
                                            attrElementContainer.style.display = 'none';
                                        } else {
                                            attrElementContainer.style.display = 'block';
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // btn cart
                    if (cartProductCart && json.cart_button_markup !== undefined) {
                        cartProductCart.innerHTML = json.cart_button_markup ? json.cart_button_markup : '';
                    }

                    // Change free attrs
					
                    var blockOfFreeAttrs = productElement.querySelector('.free-attributes');
                    if (blockOfFreeAttrs && json.free_attrs !== undefined) {
                        blockOfFreeAttrs.innerHTML = json.free_attrs ? json.free_attrs : '';
                    }
					
					if (blockOfFreeAttrs && json.jshop_facp_old_free_attribute_active !== undefined) {						
						let free_attr_active = json.jshop_facp_old_free_attribute_active;
						
						if (free_attr_active) {
							for (let key_one in free_attr_active) {
								if (free_attr_active.hasOwnProperty(key_one)) {
									let value = free_attr_active[key_one];
									productElement.querySelector('#freeattribut_' + key_one).value = value;
								}
							}
						}
                    }
                    
                    var productWishlistBtn = productElement.querySelector('.cart-product__wishlist');
                    if (productWishlistBtn && json.wishlistBtnMarkup !== undefined) {
                        productWishlistBtn.innerHTML = json.wishlistBtnMarkup ? json.wishlistBtnMarkup : '';
                    }

                    var productPriceMarkup = productElement.querySelector('.cart-product__prices');
                    if (productPriceMarkup && json.productPriceMarkup !== undefined) {
                        productPriceMarkup.innerHTML = json.productPriceMarkup ? json.productPriceMarkup : '';
                    }

                    var productShortDescriptionEl = productElement.querySelector('.cart-product__short-description');
                    if (productShortDescriptionEl && json.productShortDescription != undefined) {
                        productShortDescriptionEl.innerHTML = json.productShortDescription;
                    }
					
					if (json.qty > 0) {
						var product_qty = productElement.querySelector("#product_qty")
						 product_qty.innerHTML = json.qty;
					}
                }
                var loadingDiv = document.getElementById('dsd-spinner_loading_block');
                loadingDiv.style.visibility = 'hidden';

            }

            if (reloadAttribEvents) {
				for (var i = 0, len = reloadAttribEvents.length; i < len; i++) {
					reloadAttribEvents[i].call( reloadAttribEvents[i], json);
                }
            }
        }
    }

    reloadImage(id, value) {
        let path, img;

        if (value == "0") {
            img = "";
        } else {
            if (shopProduct.attributeImage[value]) {
                img = shopProduct.attributeImage[value];
            } else {
                img = "";
            }
        }

        if (img == "") {
            return;
        } else {
            path = shopProduct.attributePath;
        }

        document.querySelector(`#prod_attr_img_${id}`).setAttribute('src', `${path}/${img}`);
    }

    reloadValue() {
        for (let id in shopProduct.attributeValue) {
			if(document.querySelector(`#jshop_attr_id${id}`)){
				let type = document.querySelector(`#jshop_attr_id${id}`).getAttribute("type");
				
				if (type == "radio") {
					shopProduct.attributeValue[id] = document.querySelector(`input[name="jshop_attr_id[${id}]"]:checked`).value;
				} else if (type == "hidden") {
					shopProduct.attributeValue[id] = document.querySelector(`#jshop_attr_id_${id}_hidden`).value;
				}else if(type != 'checkbox'){
					 shopProduct.attributeValue[id] = document.querySelector(`#jshop_attr_id${id}`).value;
				}
			}
        }

        var loadingDiv = document.getElementById('dsd-spinner_loading_block');
        loadingDiv.style.visibility = 'hidden';
    }

    setValue(id, value) {
		
        if(document.querySelector('#jshop_attr_id'+id+'_'+value) != null){
			if(document.querySelector('#jshop_attr_id'+id+'_'+value).checked){
				shopProduct.attributeValue[id+'_'+value] = value;
			}else{
				shopProduct.attributeValue[id+'_'+value] = '';
			}
			this.reloadSelectAndPrice(id+'_'+value);
			this.reloadImage(id, value);
		}else{			
			shopProduct.attributeValue[id] = value;
			this.reloadSelectAndPrice(id);
			this.reloadImage(id, value);
		}
    }

    setNewValue(id, value, idd) {
		if(document.querySelector('#jshop_attr_id'+id+'_'+value).checked){
			shopProduct.attributeValue[id+'_'+value] = value;
		}else{
			shopProduct.attributeValue[id+'_'+value] = '';
		}
        this.reloadSelectAndPrice(id+'_'+value);
        this.reloadImage(id, value);
    }

    reloadCurrentPrice(data) {
        let currentPriceDiv = document.querySelector('#product-current-price');

        if (currentPriceDiv != null) {
           currentPriceDiv.innerHTML = data;
        }
    }

    reloadPrice() {
        if (document.querySelector("#productForm #quantity").value != "") {
            this.reloadSelectAndPrice(0);
        }
    }
	
	playLightBox() 
	{ 	  
		setTimeout(() => { 
			if(document.querySelector('video.pswp__img')){
				document.querySelector('video.pswp__img').setAttribute('autoplay',true) 
			}
		},
		800);	  
  }



}

export default new ShopProductAttributes();