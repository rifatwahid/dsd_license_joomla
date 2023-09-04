class ShopHelper {

    getElement(id) {
        return document.getElementById(id);
    }

    getValue(id) {
        let element = this.getElement(id);
        
        switch(element.type) {
            case 'select-one':
                return element.options[element.selectedIndex].value;
            case 'radio':
            case 'checkbox':
                return element.checked;
            case 'text':
            case 'password':
            case 'textarea':
            case 'hidden':
                return element.value;
            default:
                return element.innerHTML;
        }
    }

    checkExistBlock(name) {
        return (document.querySelector(name)) ? true : false;
    }

    sendAjax(method, url, data, callback) {
		callback.beforeSend;
		if(method == 'POST'){
			return fetch(url, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
				},
				body: shopHelper.dataTransform(data),
				cache: 'no-cache',
			})
			.then(response => response.json())
			.then(data => callback.success)
			.catch(error => callback.error)
			.finally(function () {
				callback.complete;
			});
		}else{
			return fetch(url+'&'+shopHelper.dataTransform(data), {
				method: 'GET',
				cache: 'no-cache',
			})
			.then(response => response.json())
			.then(data => callback.success)
			.catch(error => callback.error)
			.finally(function () {
				callback.complete;
			});
		}
		 
    }

    scrollTo({ direction, speed, duration }) {
        if (!speed) speed = 'slow';
        if (!duration || duration < 0) duration = 0;

        let scroll = (direction == 'top') ? 'scrollTop': 'scrollDown';
        let obj = {};

        obj[scroll] = duration;
        document.querySelector('html, body').animate(obj, speed);
    }

    toggleFirm(type) {
		let tr_field_firma_code = document.querySelector("#tr_field_firma_code");
		let tr_field_tax_number = document.querySelector("#tr_field_tax_number");
        if (type == "2") {
            if(tr_field_firma_code) shopHelper.show(tr_field_firma_code);
            if(tr_field_tax_number) shopHelper.show(tr_field_tax_number);
        } else {
            if(tr_field_firma_code) shopHelper.hide(tr_field_firma_code);
            if(tr_field_tax_number) shopHelper.hide(tr_field_tax_number);
        }
		shopQuickCheckout.updateClientType(type);
    }

    toggleDFirm(type) {
		let tr_field_firma_code = document.querySelector("#tr_field_d_firma_code");
		let tr_field_tax_number = document.querySelector("#tr_field_d_tax_number");
        if (type == "2") {
            if(tr_field_firma_code) shopHelper.show(tr_field_firma_code);
            if(tr_field_tax_number) shopHelper.show(tr_field_tax_number);
        } else {
            if(tr_field_firma_code) shopHelper.hide(tr_field_firma_code);
            if(tr_field_tax_number) shopHelper.hide(tr_field_tax_number);
        }
		shopQuickCheckout.updateClientType(type);
    }

    submitFilter() {
        this.getElement('sort_count').submit();
    }

    isCartPage() {
        if (this.checkExistBlock('input[name=rabatt]') && 
            this.checkExistBlock('form[name=updateCart]') && 
            this.checkExistBlock('#comjshop') 
        ) {
            return true;
        }

        return false;
    }

    isOrderPage() {
        if (this.checkExistBlock('.order-details') &&
            this.checkExistBlock('form[name=updateCart]') &&
            this.checkExistBlock('input[name=order_id]')
        ) {
            return true;
        }

        return false;
    }

    isProductPage() {
        return Boolean(document.querySelector('.product-details form#productForm[name="product"]'));
    }

    isBelemTemplate() {
        return Boolean(document.querySelector('#belem .product-details form#productForm[name="product"]'));
    }

    isProductList() {
        var isProductListCategory = Boolean(document.querySelector('.category-products'));
        var isProductList = Boolean(document.querySelector('.list-products'));

        return (isProductListCategory || isProductList);
    }

    getFormData(form) {
        var result = {};
        var formElement = null;
        var typeOfForm = typeof(form);

        if (typeOfForm == 'element' || (typeOfForm == 'object' && form.className == 'cart-product__form')) {
            formElement = form;
        } else if (typeOfForm == 'string') {
            formElement = document.querySelector(form);
        } else if (typeOfForm == 'object' && (form.id || form.name)) {
			if(form.id){
				formElement = document.querySelector('form#' + form.id);
			}else{
				formElement = document.querySelector('form[name="' + form.name + '"]');
			}
        }

        var formElements = formElement.querySelectorAll('[name]');
        if (formElements) {
            formElements.forEach(function (loopEl, index) {
                if(loopEl && loopEl.name && loopEl.value && (loopEl.type == 'radio' || loopEl.type == 'checkbox')){
                    if(loopEl.checked){
                        var name = loopEl.name.replace(/jshop_attr_id/, 'attr');
                        name = name.replace(/freeattribut/, 'freeattr');
                        result[name] = loopEl.value;
                    }
                }else if (loopEl && loopEl.name && loopEl.value) {
                    var name = loopEl.name.replace(/jshop_attr_id/, 'attr');
                        name = name.replace(/freeattribut/, 'freeattr');

                    result[name] = loopEl.value;
                }
            });
        }
        
        return result;
    }

    capitalizeFirstLetter(text) {
        return text.charAt(0).toUpperCase() + text.slice(1);
    }

    // Note: isEmpty([]) == true, isEmpty({}) == true, isEmpty([{0:false},"",0]) == true, isEmpty({0:1}) == false
    isEmpty(value) {
        var isEmptyObject = function(a) {
            if (typeof a.length === 'undefined') {
                var hasNonempty = Object.keys(a).some(function nonEmpty(element){
                return !isEmpty(a[element]);
                });
                return hasNonempty ? false : isEmptyObject(Object.keys(a));
            }
        
            return !a.some(function nonEmpty(element) {
                return !isEmpty(element);
            });
        };

        var isEmpty = (value == false || typeof value === 'undefined' || value == null || (typeof value === 'object' && isEmptyObject(value)));
        return isEmpty;
    }

    pushTextToFormAction(formSelector, text) {
        if (text) {
            var form = document.querySelector(formSelector);

            if (form && form.action) {
                form.action += text;
            }
        }
    }

    replaceFormActionText(formSelector, text) {
        if (text) {
            var form = document.querySelector(formSelector);

            if (form && form.action) {
                form.action = text;
            }
        }
    }

    isCheckoutPage() {
        let element = document.querySelector('[name^="quickCheckout"]#payment_form');
        let isIsset = (element && element.length > 0);

        return isIsset;
    }
	
	isRegisterPage() {
        let element = document.querySelector('[name^="loginForm"]');
        let isIsset = (element && element.length > 0);

        return isIsset;
    }

    isCartPage() {
        let element = document.querySelector('[name^="updateCart"]');
        let isIsset = (element && element.length > 0);

        return isIsset;
    }
	
	isOrderPage() {
        let element = document.querySelector('[id^="editUserAddressForm"]');
        let isIsset = (element && element.length > 0);

        return isIsset;
    }
	
	objToStr(obj){
		var str = [];
	   for(var p in obj){
		   if (obj.hasOwnProperty(p)) {
			   str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
		   }
	   }
	   return str.join("&");
	}
	
	show(elem) {
		if(elem) {elem.style.display = 'block !important';elem.style.display = 'block';elem.classList.remove('display--none');elem.classList.remove('d-none');}
	}

	hide(elem) {
		if(elem) {elem.style.display = 'none !important'; elem.style.display = 'none';elem.classList.add('display--none');elem.classList.add('d-none');}
	}
	
	serializeArray (form) {
	    const formData = new FormData(form);
		const pairs = [];
		const pairs1 = [];
		for (const [name, value] of formData) {
			pairs.push({ name, value });
			pairs1[name] = value ;
		}

		return pairs;
	}
	_scrollTo(element, to, duration) {
		if (duration <= 0) return;
		var difference = to - element.scrollTop;
		var perTick = difference / duration * 10;

		setTimeout(() => {
			element.scrollTop = element.scrollTop + perTick;
			if (element.scrollTop === to) return;
			this._scrollTo(element, to, duration - 10);
		}, 10);
	}
	
	getParents(el, parentSelector) {
		if (parentSelector === undefined) {
			parentSelector = document;
		}

		var parents = [];
		var p = el.parentNode;
		while (p !== parentSelector && p != null) {
			var o = p;
			parents.push(o);
			p = o.parentNode;
		}
		parents.push(parentSelector); 
		
		return parents;
	}
	
	dataTransform(data){
		var formBody = []; 
		
		for (var property in data) {
		  var encodedKey = encodeURIComponent(property);
		  var encodedValue = encodeURIComponent(data[property]);
		  formBody.push(encodedKey + "=" + encodedValue);
		}
		formBody = formBody.join("&");
		
		return formBody;
	}
	
	gatherFormData(form) {
		var formData = {};
		for (var i = 0; i < form.elements.length; i++) {
		  var element = form.elements[i];
		  if (element.name && element.value) {
			formData[element.name] = element.value;
		  }
		}
		formData['ajax']=1;
		formData['ajax_mod_cart']=1;
		return formData;
	  }
	  
	sendAjax2(method, url, data, callback) {
    callback.beforeSend(); // Виклик функції перед відправкою запиту

    if (method === 'POST') {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
            },
            body: shopHelper.dataTransform(data),
            cache: 'no-cache',
        })
        .then(response => response.json())
        .then(data => callback.success(data)) // Виклик функції успішного завершення з даними відповіді
        .catch(error => callback.error(error)) // Виклик функції у випадку помилки
        .finally(function () {
            callback.complete(); // Виклик функції після завершення запиту
        });
    } else {
        return fetch(url + '&' + shopHelper.dataTransform(data), {
            method: 'GET',
            cache: 'no-cache',
        })
        .then(response => response.json())
        .then(data => callback.success(data)) // Виклик функції успішного завершення з даними відповіді
        .catch(error => callback.error(error)) // Виклик функції у випадку помилки
        .finally(function () {
            callback.complete(); // Виклик функції після завершення запиту
        });
    }
}

}

export default new ShopHelper();