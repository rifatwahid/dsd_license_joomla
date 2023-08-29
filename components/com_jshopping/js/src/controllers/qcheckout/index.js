import shopHelper from '../../common/helper/index.js';
import shopUser from '../user/index.js';
import ValidateForm from '../../common/validate_form/index.js';

class ShopQuickCheckout {

    constructor() {

        this.showErrors = false;
        this.haveAjax = null;
		this.haveAjax1 = null;
        this.ajaxRequest = null;
        this.ajaxRequest1 = null;
        this.payment_class = null;
        this.activePaymentMethod = '';
        this.options = {};
        this.isHideShippingStep = false;
        this.beforeSubmitCheckoutTriggers = [];
        this.isSubmitFormEnabled = true;

        document.addEventListener("DOMContentLoaded", () => {

            if (this.payment_class) {
                this.showPayment(this.payment_class);
            }

            if (Joomla.getOptions('qCheckout')) {
                const options = Joomla.getOptions('qCheckout');
                this.payment_class = options.payment_class;
                this.options = options;
                this.isHideShippingStep = (options.jshopConfig.hide_shipping_step == 1);

                shopUser.setFields(options.register_field_require);
            }

            this._addRequired();
			let err = document.querySelector('#qc_error');
			if(err){
				shopHelper.hide(err);
			}

            this._afterAjax = this._afterAjax.bind(this);
           // this._afterCalculate1 = this._afterCalculate1.bind(this);
            this._setObserver();
        });

    }

    checkForm(isUserAuthorized = false) {
        let error = false;
		
        if (!isUserAuthorized) {
            if (!shopUser.validateAccount('quickCheckout')) {
                error = true;
            }
    
            if (!this._checkRequired()) {
                error = true;
                this._addClassError();
            }
        } else {
            var isFillAddressHandling  = this._isFillAddressHandling();
            error = !isFillAddressHandling;
        }

		if (this.options.jshopConfig)
        if (this.options.jshopConfig.display_agb) {
            if (!this._checkAGB()) {
                error = true;
            }
        }

        if (document.querySelector('#no_return')) {
            if (!this._checkNoReturn()) {
                error = true;
            }
        }

		if (this.options.jshopConfig)
        if (this.options.jshopConfig.hide_shipping_step && this.options.jshopConfig.without_shipping) {
            if (!this._validateShipping()) {
                error = true;
            }
        }

        //if (this.options.jshopConfig.hide_payment_step && this.options.jshopConfig.without_payment) {
            if (!error) {
                if (!this._checkPayment()) {
                    error = true;
                    return false;
                }
            }
        //}

        if (error) {
            return false;
        }

        return true;
    }

    onSubmitForm(formElement) {
        var errorsBlockEl = document.querySelector('#qc_error');
        var submitCheckoutFormEl = formElement.querySelector('#submitCheckout');
        this.isSubmitFormEnabled = true;
        this.isRefreshFormData = true;
        this.isEnableDisableSubmit = true;

        errorsBlockEl.innerHTML = '';
       // submitCheckoutFormEl.disabled = true;
        this.isSuccessCheckedForm = this.checkForm();
        if (!this.isSuccessCheckedForm) {
            this.showErrors = true;

            if (this.isRefreshFormData) {
                this._refreshData();
            }

            if (this.qcheckoutErrors) {
				submitCheckoutFormEl.disabled = true;
            }

            if (this.isEnableDisableSubmit) {
                submitCheckoutFormEl.disabled = false;
            }
            
            return this.isSuccessCheckedForm;
        }

        if (this.beforeSubmitCheckoutTriggers.length >= 1) {
            this.beforeSubmitCheckoutTriggers.forEach(function (trigger) {
                funcElem(trigger);
            });
        }

        if (this.isSubmitFormEnabled) {
			/*if (typeof check_pm_authorize === "function") {
				if (check_pm_authorize()){					
					formElement.submit();
				}else{return false;}
			} else*/ {			
				formElement.submit();
			}
        }

       // this._sendAjax(this.options.qc_ajax_link, 'json', data, this._afterAjax);
        return this.isSuccessCheckedForm;
    }

    _isFillAddressHandling() {
        var isFill = false;
         
        try {
            var qCheckoutEl = document.querySelector('[name="quickCheckout"]');
            var billingAddressId = qCheckoutEl.querySelector('input[name="billingAddress_id"]').value;
            var shippingAddressId = qCheckoutEl.querySelector('input[name="shippingAddress_id"]').value;
        } catch (e) {
            isFill = false;
        }

        if (billingAddressId && shippingAddressId) {
            isFill = true;
        }

        return isFill;
    }

    _addRequired() {
        let password1 = document.querySelector('form[name="quickCheckout"] input[name="password"]');
        let password2 = document.querySelector('form[name="quickCheckout"] input[name="password2"]');
        let readPrivacy = document.querySelector('form[name="quickCheckout"] input[name="readPrivacy"]');

        if (document.querySelector('#qcheckout__create-account')) {
            document.querySelector('form[name="quickCheckout"] input[name="password"], form[name="quickCheckout"] input[name="password2"]').addEventListener('input change', () => {

                if (password1.value != '' || password2.value != '') {
                    password1.required = true;
                    password2.required = true;
                    readPrivacy.required = true;
                } else {
                    password1.required = false;
                    password2.required = false;
                    readPrivacy.required = false;
                }
            });
        } else {
			if(document.querySelector('input#switch-reg')){
				document.querySelector('input#switch-reg').addEventListener('change', function () {
					if (this.checked) {
						readPrivacy.required = true;
					} else {
						readPrivacy.required = false;
					}

				});
			}
        }
    }

    _checkRequired() {
        let password1 = document.querySelector('form[name="quickCheckout"] input[name="password"]');
        let password2 = document.querySelector('form[name="quickCheckout"] input[name="password2"]');
        let readPrivacy = document.querySelector('form[name="quickCheckout"] input[name="readPrivacy"]');
        let email = document.querySelector('form[name="quickCheckout"] input[name="email"]');

        if (document.querySelector('#qcheckout__create-account')) {
            if ((password1 && password2 && readPrivacy) && (password1.required || password2.required || readPrivacy.required)) {
                if (password1.value == '') {
                    return false;
                } else if (password2.value == '') {
                    return false;
                } else if (readPrivacy.checked == false) {
                    return false;
                }
            }
			const fields = {
				ids: ['email'],
				type: ['em'],
				params: ['']
			}
			
			const form = new ValidateForm('quickCheckout', fields, 2);
			let isValidForm = form.validate();
			var reviewLangs = Joomla.getOptions('reviewLangs');
			if (!isValidForm && reviewLangs && form.errorId) {
				containerErrorsEl.innerHTML = '';

				if (containerErrorsEl) {
					containerErrorsEl.style.display = 'block';
					for (var idName of form.errorId) {
						if (reviewLangs[idName]) {
							containerErrorsEl.innerHTML += `<p class="product-review-alerts__alert">${reviewLangs[idName]}</p>`;
						}
					}
				}
			}
        } else {
            if (readPrivacy && readPrivacy.prop('required')) {
                if (!readPrivacy.prop('checked')) {
                    return false;
                }
            }
        }

        return true;
    }

    _addClassError() {
        let createAccount = document.querySelector('#qcheckout__create-account');
        let inputs = createAccount.querySelectorAll('input');

        if (createAccount) {
            inputs.forEach(function (ind, el) {
                if (el.getAttribute('type') == 'password') {
                    if (el.value == '') {
                        el.classList.add('fielderror');
                    }
                } else {
                    if (!document.querySelector(el).prop('checked')) {
                        document.querySelector(el).parentNode().classList.add('fielderror');
                    }
                }

            });
        }
    }

    _setObserver() {
        this._deleteObserver();
        let selectors = this._getSelectorsForBinding();
		let el = document.querySelector(
            selectors.join(',')
        );
		if(el){
        document.querySelector(
            selectors.join(',')
        ).addEventListener('change', (target) => this._refreshData(target));
		}
    }
    
    _deleteObserver() {
        let selectors = this._getSelectorsForBinding();
		let el = document.querySelector(
            selectors.join(',')
        );
		if(el){
			el.addEventListener('change', (event) => { event.preventDefault(); });
		}
    }

    _getSelectorsForBinding() {
        let selectors = [
            '#payment_form #qc_address input',
            '#payment_form #qc_payments_methods input[name^="payment_method"]',
            '#payment_form #qc_shippings_methods input',
            '#payment_form .checkout-cart input',
            '#payment_form .row_agb input',
            '#country',
            '#d_country'
        ];

        return selectors;
    }

    _refreshData(target, additionalData) {
        var loadingDiv = document.getElementById('dsd-spinner_loading_block');
        if(loadingDiv) loadingDiv.style.visibility = 'visible';
        let data = {
            type: 'all',
            params: []
        };
		if(document.querySelectorAll('form[name="quickCheckout"] [name^="params"]')){
			document.querySelectorAll('form[name="quickCheckout"] [name^="params"]').forEach(input => {
				var param = {};
				param.name = input.getAttribute('name');
				param.value = input.value;
				data.params.push(param);
			});
		}

        //Payment
        let checkedPayment = document.querySelector('form[name="quickCheckout"] input[name="payment_method"]:checked');
        if(checkedPayment) data.payment_method = checkedPayment.value;

        //Shipping
        let checkedShipping = document.querySelector('form[name="quickCheckout"] input[name="sh_pr_method_id"]:checked');
        if(checkedShipping) data.sh_pr_method_id = checkedShipping.value;

        let shippingFormActiveEl = document.querySelector('form[name="quickCheckout"] .shipping_form.shipping_form_active');
        if (shippingFormActiveEl) {
            let shippingFormActiveSelected = shippingFormActiveEl.querySelector('*:checked');

            if (shippingFormActiveSelected) {
                data['shipping_form_active_value'] = shippingFormActiveSelected.value;
            }
        }

		let el = document.querySelectorAll('div#qc_address input[type="text"], div#qc_address input[type="hidden"], div#qc_address input[type="radio"]:checked, div#qc_address input[type="checkbox"], div#qc_address select, div#qc_address input[type="number"], .row_agb ~ input[type=hidden]');
        if(el){
			el.forEach(input => {
			   data[input.name] = input.value;
			});	
		}		

		let recapcha = document.querySelector("#g-recaptcha-response");
		if(recapcha){			
			data['g-recaptcha-response'] = recapcha.value;
			grecaptcha.execute();
		}
		data['isChangedPayment'] = 0;
        
        if (target && target.target && target.target.id) {
            if (target.target.id.indexOf('payment_method_') !== -1 ) {
                data['isChangedPayment'] = 1;
            }
        }
        data['isChangedPayment'] = (additionalData && additionalData.isChangedPayment !== undefined) ? additionalData.isChangedPayment : data['isChangedPayment'];

        if (document.querySelector('#qcheckout__create-account')) {
            document.querySelectorAll('#qcheckout__create-account input[name="password"], #qcheckout__create-account input[name="password2"], #qcheckout__create-account input[name="readPrivacy"]').forEach(input => {
                if (input && input.getAttribute("name") == 'readPrivacy') {
                    let checkedStatus = Number(document.querySelector('#qcheckout__create-account input[name="' + input.getAttribute("name") + '"]').checked);
                    data[input.getAttribute("name")] = checkedStatus;
                } else {
                    data[input.getAttribute("name")] = input.value;
                }

            });
        }
		if(this.options.qc_ajax_link != undefined){
            this._sendAjax(this.options.qc_ajax_link, 'json', data, this._afterAjax);
        }else{
            if(loadingDiv) loadingDiv.style.visibility = 'hidden';
        }
    }

    _afterAjax(data) {
		this._enableSubmitButton();
        this.haveAjax = null;
		el = document.querySelector('div#qc_payments_methods');
        if (data.payments && el) {
            el.innerHTML = data.payments;
        }

		el = document.querySelector('div#qc_shippings_methods');
        if (el && data.shippings) {
            el.innerHTML = data.shippings;
        } else if (el && data.shippings1) {
            el.innerHTML = data.shippings1;
        }

        if (data.active_payment_class && document.querySelector('#qc_payment_method_class')) {
            document.querySelector('#qc_payment_method_class').value = data.active_payment_class;
        } else if(document.querySelector('#qc_payment_method_class')) {
            document.querySelector('#qc_payment_method_class').value = '';
        }

        this._setObserver();

        if (data.active_sh_pr_method_id && document.querySelector('#qc_sh_pr_method_id')) {
            document.querySelector('#qc_sh_pr_method_id').value = data.active_sh_pr_method_id;
        } else if(document.querySelector('#qc_sh_pr_method_id')) {
            document.querySelector('#qc_sh_pr_method_id').value = '';
        }

        if (data.newViewSmallCart != null && document.querySelector('#shop-qcheckout .checkout-cart')) {
            document.querySelector('#shop-qcheckout .checkout-cart').innerHTML = data.newViewSmallCart;
        }

        if(document.querySelector('#qc_error')) shopHelper.hide(document.querySelector('#qc_error'));

        this.updateCart(data);

        if (data.tax_list) {
			var el = document.querySelector('.tax_list_value');
			if(document.querySelectorAll('.tax_list_value')){
				document.querySelectorAll('.tax_list_value').forEach(tax => {
					tax.remove();
				});
			}
			var tax_list_value='';
			var i=0;
			for (i; i < data.tax_list_name.length; i++) {
				let percent = i+1;
				if (data.tax_list.hasOwnProperty(percent)) {
					let value = data.tax_list[percent];
					tax_list_value=tax_list_value+'<li class="list-group-item tax_list_value">' + data.tax_list_name[i];
					tax_list_value=tax_list_value+" "+this._formattax(percent,data.jshopConfig.decimal_symbol)+'%:';
					tax_list_value=tax_list_value+'<span class="float-end">' + value + '</span></li>';
					if(el) el.style.display = 'block';
				}
			}
			let _el = document.querySelector('.fullsumm');
			if(_el){
				_el.insertAdjacentHTML('beforebegin', tax_list_value);
			}
			
        } else {
            el.style.display = 'none';
        }
		
		
		var price_products = document.querySelector('.price_products span');
		var fullsumm = document.querySelector('.fullsumm span');
		var summ_delivery = document.querySelector('.summ_delivery span');
		
        if(price_products && data.price_product) price_products.innerHTML = data.price_product;
        if(fullsumm) fullsumm.innerHTML = data.fullsumm;
        if(summ_delivery) summ_delivery.innerHTML = data.summ_delivery;
		if (document.querySelector('.summ_package span')!= undefined){
			var summ_package = document.querySelector('.summ_package span');
			if(typeof data.summ_package != undefined && typeof summ_package != undefined && summ_package && data.summ_package && data.summ_package != undefined){
				shopHelper.show(document.querySelector('.summ_package'));
				summ_package.innerHTML = data.summ_package;
			} else {
				shopHelper.hide(document.querySelector('.summ_package'));
			}
		}
		
        if(document.querySelector('.one_click_checkout #delivery_block .shipping_name')){
            document.querySelector('.one_click_checkout #delivery_block .shipping_name').innerText = data.shipping_name;
            document.querySelector('.one_click_checkout #fullsumm').innerText = data.fullsumm;
            document.querySelector('.one_click_checkout #payment_name').innerText = data.payment_name;
            document.querySelector('.summ_payment #active_payment_name').innerText = data.payment_name;
            document.querySelector('.summ_payment .summ_pay').innerText = data.summ_payment.formatprice;
        }
        if(summ_delivery) summ_delivery.innerHtml = data.summ_delivery;

        this.qcheckoutErrors = '';
        if (data.error) {
            this.qcheckoutErrors = data.error;
        }

        if (data.error || document.querySelector('form[name=quickCheckout] .fielderror')) {
            if (this.showErrors) {
                document.querySelector('#qc_error').innerHTML = data.error;
				shopHelper.show(document.querySelector('#qc_error'));
            }
        }
        if(document.querySelector('#checkout_address_step')){
            shopOneClickCheckout.closeNav('checkout_address_step');
        }
        var loadingDiv = document.getElementById('dsd-spinner_loading_block');
        if(loadingDiv) loadingDiv.style.visibility = 'hidden';

    }

    updateCart(data) {
		let summ_payment_row = document.querySelector('#qc_summ_payment_row');
		let discount_row = document.querySelector('#qc_discount_row');
        if (data.discount.price != 0) {
            if(discount_row) shopHelper.show(discount_row);
            if(document.querySelector('#qc_discount')) document.querySelector('#qc_discount').innerText = '-' + data.discount.formatprice;
        } else {
            if(discount_row) shopHelper.show(discount_row);
        }
		
        if (data.summ_payment.price != 0) {
            if(summ_payment_row) shopHelper.show(summ_payment_row);
            if(document.querySelector('#qc_payment_price')) document.querySelector('#qc_payment_price').innerText = data.summ_payment.formatprice;
            if(document.querySelector('#qc_payment_name')) document.querySelector('#qc_payment_name').innerText = data.payment_name;
        } else {
            if(summ_payment_row) shopHelper.show(summ_payment_row);
        }
		
		var qc_free_discount_row = document.querySelector('#qc_free_discount');
        if (data.free_discount.price != 0) {
            if(document.querySelector('#qc_free_discount_row')) shopHelper.show(document.querySelector('#qc_free_discount_row'));
            if(qc_free_discount_row) qc_free_discount_row.innerText = data.free_discount.formatprice;
        } else {
            if(qc_free_discount_row) shopHelper.hide(qc_free_discount_row);
        }

		var qc_shipping_price_row = document.querySelector('#qc_shipping_price_row');
        if (data.summ_delivery !== undefined) {
            if(qc_shipping_price_row) shopHelper.show(qc_shipping_price_row);
            if(document.querySelector('#qc_shipping_price')) document.querySelector('#qc_shipping_price').innerText = data.summ_delivery;
        } else {
            if(qc_shipping_price_row) shopHelper.hide(qc_shipping_price_row);
        }
		var qc_shipping_package_price_row = document.querySelector('#qc_shipping_package_price_row');
        if (typeof data.summ_package !== undefined) {
            shopHelper.show(qc_shipping_package_price_row);
            if(document.querySelector('#qc_shipping_package_price')) document.querySelector('#qc_shipping_package_price').innerText = data.summ_package;
        } else {
            shopHelper.hide(qc_shipping_package_price_row);
        }

        if(document.querySelector('#qc_total')) document.querySelector('#qc_total').innerText = data.fullsumm;

        if (data.tax_list !== undefined && data.tax_list.length) {
            for (var percent in data.tax_list) {
                if (data.tax_list.hasOwnProperty(percent)) {
                    var value = data.tax_list[percent];
                    if(document.querySelector('#qc_tax_' + percent)) document.querySelector('#qc_tax_' + percent).innerText = value;
                }
            }
        }

		var qc_delivery_time_block = document.querySelector('#qc_delivery_time_block');
        if (data.delivery_time) {
            if(qc_delivery_time_block) shopHelper.show(qc_delivery_time_block);
            if(document.querySelector('#qc_delivery_time')) document.querySelector('#qc_delivery_time').innerText = data.delivery_time;
        } else {
            if(qc_delivery_time_block) shopHelper.hide(qc_delivery_time_block);
        }

		var qc_delivery_date_block = document.querySelector('#qc_delivery_date_block');
        if (data.delivery_date) {
            if(qc_delivery_date_block) shopHelper.show(qc_delivery_date_block);
            if(document.querySelector('#qc_delivery_date')) document.querySelector('#qc_delivery_date').innerText = data.delivery_date;
        } else {
            if(qc_delivery_date_block) shopHelper.hide(qc_delivery_date_block);
        }

        if (data.shipping_name) {
            shopHelper.show(document.querySelector('#qc_active_shipping_block'));
            if(document.querySelector('#qc_active_shipping_name')) document.querySelector('#qc_active_shipping_name').innerText = data.shipping_name;
        } else {
            shopHelper.hide(document.querySelector('#qc_active_shipping_block'));
        }

        if (data.payment_name) {
            shopHelper.show(document.querySelector('#qc_active_payment_block'));
            if(document.querySelector('#qc_active_payment_name')) document.querySelector('#qc_active_payment_name').innerText = data.payment_name;
        } else {
            shopHelper.hide(document.querySelector('#qc_active_payment_block'));
        }
    }

	_disableSubmitButton(){
		const button = document.querySelector('#submitCheckout');
		if (button){
			button.disabled = true;
			document.querySelector('#submitCheckout').classList.add("reloading");
		}
	}
	_enableSubmitButton(){
		const button = document.querySelector('#submitCheckout');
		{
			button.disabled = false;
			if (document.querySelector('#submitCheckout').classList.contains("reloading")) 
				document.querySelector('#submitCheckout').classList.remove("reloading");
		}

        var loadingDiv = document.getElementById('dsd-spinner_loading_block');
        if(loadingDiv) loadingDiv.style.visibility = 'hidden';
	}
	
    _sendAjax(url, dataType, data, callback) {
        /*if (this.ajaxRequest && this.ajaxRequest.value != undefined) {
            this.ajaxRequest.abort();
        }*/
		
		if(!this.ajaxRequest1 || this.ajaxRequest1 == null || typeof this.ajaxRequest1.value == 'undefined' || document.querySelector('.one_click_checkout')){
			data = shopHelper.dataTransform(this._beforeAjax(data));		
		
			this.ajaxRequest = fetch(url, {
			  method: 'POST',
			  headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
			  },
			  body: data
			})

			.then(response => response.json())

			.then(data => callback(data) );


			this.haveAjax = 1;
		}
    }
	

    _beforeAjax(data) {
		this._disableSubmitButton();		
		
        if (document.querySelector('input[name="agb"]')) {
            data.agb = Number(document.querySelector('input[name="agb"]').checked);
        }

        if (document.querySelector('input[name="no_return"]')) {
            data.no_return = Number(document.querySelector('input[name="no_return"]').checked);
        }

        if (document.querySelector('input[name="privacy_statement"]')) {
            data.privacy_statement = Number(document.querySelector('input[name="privacy_statement"]').checked);
        }

        return data;
    }

    showShipping(id) {
		var element = document.querySelector('div.shipping_form');
		var elementId = document.querySelector(`#shipping_form_${id}`);
        element.classList.remove('shipping_form_active');
        elementId.classList.add('shipping_form_active');
		this._reloadShippingPrice(id);
		
    }

    _reloadPaymentPrice(id) {
        var loadingDiv = document.getElementById('dsd-spinner_loading_block');
        if(loadingDiv) loadingDiv.style.visibility = 'visible';
        var billingAddressId = 0;
        var shippingAddressId = 0;
        var qCheckoutEl = document.querySelector('[name="quickCheckout"]');
        if(qCheckoutEl.querySelector('input[name="billingAddress_id"]')) {
            billingAddressId = qCheckoutEl.querySelector('input[name="billingAddress_id"]').value;
        }
        if(qCheckoutEl.querySelector('input[name="shippingAddress_id"]')) {
            shippingAddressId = qCheckoutEl.querySelector('input[name="shippingAddress_id"]').value;
        }
        let data = {
            payment_id: id,
            billingAddress_id: billingAddressId,
            shippingAddress_id: shippingAddressId
        }

		/*if (this.ajaxRequest1 && this.ajaxRequest1.value != undefined) {
			this.ajaxRequest1.abort();
        }*/	
		
		data = shopHelper.dataTransform(data);
		if (this.options.qc_ajax_reload_link!=undefined){
		this.ajaxRequest1 = fetch(this.options.qc_ajax_reload_link, {
		  method: 'POST',
		  headers: {
			'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
		  },
		  body: data
		})
		.then(response => response.json())
		.then(json => {
			this._afterCalculate(json);
		});

        this.haveAjax1 = 1;
        }else{
            if(loadingDiv)  loadingDiv.style.visibility = 'hidden';
        }


	}

    _reloadShippingPrice(id) {
        var loadingDiv = document.getElementById('dsd-spinner_loading_block');
        if(loadingDiv) loadingDiv.style.visibility = 'visible';
		var qCheckoutEl = document.querySelector('[name="quickCheckout"]');
		var billingAddressId = qCheckoutEl.querySelector('input[name="billingAddress_id"]') ? qCheckoutEl.querySelector('input[name="billingAddress_id"]').value : 0;
		var shippingAddressId = qCheckoutEl.querySelector('input[name="shippingAddress_id"]') ? qCheckoutEl.querySelector('input[name="shippingAddress_id"]').value : 0;
		let data = {
			shipping_id: id,
			billingAddress_id:billingAddressId,
			shippingAddress_id:shippingAddressId
		}

		/*if (this.ajaxRequest1 && this.ajaxRequest1.value != undefined) {
			this.ajaxRequest1.abort();
        }*/	
		
		data = shopHelper.dataTransform(data);
		
		this.ajaxRequest1 = fetch(this.options.qc_ajax_reload_link, {
		  method: 'POST',
		  headers: {
			'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
		  },
		  body: data
		})
		.then(response => response.json())
		.then(json => {
			this._afterCalculate(json);
            if(loadingDiv) loadingDiv.style.visibility = 'hidden';
		});
		
		
		this.haveAjax1 = 1;
	}
	

	_afterCalculate(data){
		var price_products = document.querySelector('.price_products span');
		var fullsumm = document.querySelector('.fullsumm span');
		var summ_delivery = document.querySelector('.summ_delivery span');		
		var tax_list_value_bl = document.querySelector('.tax_list_value');
        if(price_products && data.price_product) price_products.innerHTML = data.price_product;
        if(fullsumm) fullsumm.innerHTML = data.fullsumm;
        if(summ_delivery) summ_delivery.innerHTML = data.summ_delivery;
		if (document.querySelector('.summ_package span')!= undefined){
			var summ_package = document.querySelector('.summ_package span');
			if(typeof summ_package != undefined && summ_package && typeof data.summ_package != undefined && data.summ_package != undefined){
				shopHelper.show(document.querySelector('.summ_package'));
				summ_package.innerHTML = data.summ_package;	
			} else {
				shopHelper.hide(document.querySelector('.summ_package'));
			} 
		}
		if(data.not_change_payment != undefined && data.not_change_payment != 1){
			if(data.payment_name != undefined){
				shopHelper.show(document.querySelector('.summ_payment'));				
				document.querySelector('.summ_payment #active_payment_name').innerHTML = data.payment_name;
				document.querySelector('.summ_payment .summ_pay').innerHTML = data.summ_payment.formatprice;
			}else{
				shopHelper.hide(document.querySelector('.summ_payment'));
			}
		}
		
        if(document.querySelectorAll('.tax_list_value')){
			document.querySelectorAll('.tax_list_value').forEach(tax => {
				tax.remove();
			});
		}
		if (data.tax_list) {
			var tax_list_value='';
            for (let percent in data.tax_list) {

                if (data.tax_list.hasOwnProperty(percent)) {
                    let value = data.tax_list[percent];
                    tax_list_value=tax_list_value+'<li class="list-group-item tax_list_value">'+data.tax_list_name;
					tax_list_value=tax_list_value+" "+percent.replace('.',data.jshopConfig.decimal_symbol)+'%:';
                    tax_list_value=tax_list_value+'<span class="float-end">' + value + '</span></li>';
                    tax_list_value_bl.style.display = 'block';
                }
            }
			
			let _el = document.querySelector('.fullsumm');
			if(_el){
				_el.insertAdjacentHTML('beforebegin', tax_list_value);
			}
        } else {
            tax_list_value_bl.style.display = 'block';
        }
		
		if(data.payments != null){
			document.querySelector('#qc_payments_methods').innerHTML = data.payments;
		}
		
		if(data.shippings != null){
			document.querySelector('#qc_shippings_methods').innerHTML = data.shippings;
		}
		
		if(data.newViewSmallCart != null){
			document.querySelector('.cart-products').innerHTML = data.newViewSmallCart;
		}
		
		var checkout_cart = document.querySelector('#shop-qcheckout .checkout-cart');
        if (data.newViewSmallCart != null) {
            checkout_cart.innerHTML = data.newViewSmallCart;
        }
        var loadingDiv = document.getElementById('dsd-spinner_loading_block');
        if(loadingDiv) loadingDiv.style.visibility = 'hidden';
		this._enableSubmitButton();
	}

    _checkPayment() {
        if (this.activePaymentMethod) {
			if (this.options.payment_type_check)
            if (this.options.payment_type_check[this.activePaymentMethod] == '1') {
                var nameOfCheckPaymentFunc = 'check_' + this.activePaymentMethod;
                var res = eval(nameOfCheckPaymentFunc + "();");

                if (!res) {
                    return false;
                }
            }

            return true;
        }

        return true;
    }

    showPayment(paymentMethod) {
        this.activePaymentMethod = paymentMethod;
		if(document.querySelector("*[id^='tr_payment_']")) shopHelper.hide(document.querySelector("*[id^='tr_payment_']"));
        if(document.querySelector('#tr_payment_' + paymentMethod)) shopHelper.hide(document.querySelector('#tr_payment_' + paymentMethod));
		this._reloadPaymentPrice(paymentMethod);
	}

    _validateShipping() {
        let tableShip = shopHelper.getElement('table_shippings');

        if (this.isHideShippingStep || !tableShip) {
            return true;
        }

        if (tableShip) {
            let inputs = tableShip.getElementsByTagName('input');

            for (let i = 0; i < inputs.length; i++) {
                if (inputs[i].type != 'radio') {
                    continue;
                }

                if (inputs[i].checked) {
                    return true;
                }
            }
        }

        return false;
    }

    _checkAGB() {
		if (shopHelper.getElement('agb')){
			if (shopHelper.getElement('agb').checked) {
				document.querySelector('.row_agb').classList.remove('fielderror');
				return true;
			} else {
				document.querySelector('.row_agb').classList.add('fielderror');
				document.querySelector('#agb').focus();
				return false;
			}
		} else {
			return true;
		}
    }
	
    _checkNoReturn() {
        if (shopHelper.getElement('no_return').checked) {
            document.querySelector('.row_no_return').classList.remove('fielderror');
            return true;
        } else {
            document.querySelector('.row_no_return').classList.add('fielderror');
            document.querySelector('#no_return').focus();
            return false;
        }
    }
	
	updateClientType(id) {
        var loadingDiv = document.getElementById('dsd-spinner_loading_block');
        if(loadingDiv) loadingDiv.style.visibility = 'visible';
		let data = {
			client_type: id
		}
		
		if (this.haveAjax1 && this.ajaxRequest1 != null && typeof this.ajaxRequest1.value != 'undefined') {
            this.ajaxRequest1.abort();
        }

		data = shopHelper.dataTransform(this._beforeAjax(data));
		this.ajaxRequest1 = fetch(this.options.qc_ajax_reload_link, {
			  method: 'POST',
			  headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
			  },
			  body: data
			})

			.then(response => response.json())

			.then(json => this._afterCalculate(json));		
		
		this.haveAjax1 = 1;
    }
	
	updateBillingAddress(id) {
        var loadingDiv = document.getElementById('dsd-spinner_loading_block');
        if(loadingDiv) loadingDiv.style.visibility = 'visible';
		let data = {
			billing_id: id
		}
		if (this.haveAjax1 && this.ajaxRequest1 != null && typeof this.ajaxRequest1.value != 'undefined') {
            this.ajaxRequest1.abort();
        }

		data = shopHelper.dataTransform(this._beforeAjax(data));
		this.ajaxRequest1 = fetch(this.options.qc_ajax_reload_link, {
			  method: 'POST',
			  headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
			  },
			  body: data
			})

			.then(response => response.json())

			.then(json => this._afterCalculate(json));		
		
		   this.haveAjax1 = 1;

    }
	
	_formattax(percent,decimal_symbol){		
        return percent.replace('.',decimal_symbol);		
	}


}

export default new ShopQuickCheckout();