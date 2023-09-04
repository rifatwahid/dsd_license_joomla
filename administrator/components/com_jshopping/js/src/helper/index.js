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

    round(val, num) {
        let ret = parseFloat(Math.round(val * Math.pow(10, num)) / Math.pow(10, num)).toString();
        return (isNaN(ret)) ? (0) : (ret);
    }

    isEmpty(val) {
        let pattern = /\S/;
        return (pattern.test(val)) ? (0) : (1);
    }

    isExistsExcludeAttributes() {
        return (document.querySelector('#eafa_attr_ids')) ? true : false;
    }

    isCouponEditPage() {
        let coupon = document.querySelector('input#coupon_code');
        let finished = document.querySelector('input[name="finished_after_used"]');

        return (coupon && finished) ? true : false;
    }

    delete(selector) {
        let element = document.querySelector(selector);

        if (element) {
            element.remove();
            return true;
        }

        return false;
    }

    showHideByChecked(elemCheckerData, selectorElemToHide, visibleStatus = 'block') {
        let elementToHide = document.querySelector(selectorElemToHide);

        if (elementToHide) {
            elementToHide.style.display = (elemCheckerData.checked) ? (visibleStatus) : ('none');
            return true;
        }

        return false;
    }

    showAlertMessage(value, value1, message) {
        if (value && value1 && (value == value1)) {
            alert(message);
        }
    }

    sendAjax(url, data = null, callbackSucess = null) {
        if (this.haveAjax) {
            this.ajaxRequest.abort();
        }

        this.ajaxRequest = fetch(url, {
            method: 'POST',
            body: JSON.stringify(data),
            cache: 'no-cache',
        })
        .then(response => response.json())
        .then(data => callbackSucess);

        this.haveAjax = 1;
        return this.ajaxRequest;
    }

    deleteMarkUp(markupSelector) {
        if (markupSelector) {
            try {
                document.querySelector(markupSelector).remove();
                
                return true;
            } catch(error) {
                console.error('Can`t find markupSelector - ' + markupSelector);
            }
        }

        return false;
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
	
	saveorder( n, task, evt) {
		
		if(this.checkAll_button( n, task , evt)){
		
		window.stop();
		}
	};
	checkAll_button( n, task, evt ) {
		console.warn('window.checkAll_button() is deprecated without a replacement!');

		task = task ? task : 'saveorder';

		var j, box;
		for ( j = 0; j <= n; j++ ) {
			box = document.adminForm[ 'cb' + j ];

			if ( box ) {
				box.checked = true;
			} 
		}
		
		Joomla.submitform( task );
	};
	_afterAjax(){
		
	}
	getFormData(form) {
        var result = {};
        var formElement = null;
        var typeOfForm = typeof(form);

        if (typeOfForm == 'element' || (typeOfForm == 'object' && form.className == 'cart-product__form')) {
            formElement = form;
        } else if (typeOfForm == 'string') {
            formElement = document.querySelector(form);
        } else if (typeOfForm == 'object' && form.name) {
            formElement = document.querySelector('form[name="' + form.name + '"]');
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


}

export default new ShopHelper();
