import ValidateForm from '../../common/validate_form/index.js';
// import shopHelper from '../../common/helper/index.js';

class ShopUser {

    constructor() {
        this.fields = [];
				
        document.addEventListener("readystatechange", () => {
			var thouse = this;
			var selectors = [];
			selectors = ['.inputbox', '.input', '.form-control', 'select', '.password', 'input[type=text]'];
			
			selectors.forEach(selector => {
				var elems = document.querySelectorAll(selector);
				if(elems){
					elems.forEach(el => {
						if(el){
							el.addEventListener('focusout', function(){
								var form = el.closest('form');
								thouse.validateAccountField(form.name, el.getAttribute('name'));
							});
						}
					});
				}
			});
			
			selectors = [];
			selectors = ["form[name=jlogin] .form-control", "input[type=password]"];
			
			selectors.forEach(selector => {
				var elems = document.querySelectorAll(selector);
				if(elems){
					elems.forEach(el => {
						if(el){
							el.addEventListener('focusout', function(){						
								if((!el.value || el.value.length == 0) || (el.getAttribute('name') == 'password_2' && document.querySelector('#password').value != document.querySelector('#password_2').value)|| (el.getAttribute('name') == 'password2' && document.querySelector('#password').value != document.querySelector('#password2').value)){
									el.classList.add('is-invalid');
									el.classList.remove('is-valid');
								}else{					
									el.classList.remove('is-invalid');
									el.classList.add('is-valid');		
									document.querySelector('.'+el.getAttribute('name')+'_error').innerHTML = '';
								}
							});
						}
					});
				}
			
			});
		});
	}

    moveOrderAndOfferLinkForGuest() {
        document.addEventListener("DOMContentLoaded", () => {
            let el = document.querySelector('#block_anfrage_guest');
            let html = '';
			if(el) html = el.innerHTML;
            if(document.querySelector('table.table_login')) document.querySelector('table.table_login').insertAdjacentHTML('afterend', html);
            if(document.querySelector('#block_anfrage_guest')) document.querySelector('#block_anfrage_guest').innerHTML = '';
        });
    }

    _validateFirmaAndTax(formName, fields) {
        this.__validateFirmaAndTax(formName, fields);        
        this.__validateFirmaAndTax(formName, fields, 'd_');
    }

    __validateFirmaAndTax(formName, fields, prefix = '') {
        let form = document.forms[formName];

        if (form) {
            let clientType = prefix + 'client_type';
            let taxNumberWithPrefix = prefix + 'tax_number';
            let clientTypeValue = null;
            
            if (form[clientType]) {
                let firmaCodeWithPrefix = prefix + 'firma_code';
                clientTypeValue = form[clientType].value;
    
                if (clientTypeValue == '2') {
                    if (document.querySelector('#' + firmaCodeWithPrefix)) {
                        fields.ids.push(firmaCodeWithPrefix);
                        fields.type.push('nem');
                        fields.params.push('');
                    }
    
                    if (document.querySelector('#' + taxNumberWithPrefix) && fields.ids[taxNumberWithPrefix]) {
                        fields.ids.push(taxNumberWithPrefix);
                        fields.type.push('nem');
                        fields.params.push('');
                    }
                }
            }
           
		   if ((clientTypeValue != '2' || !clientTypeValue) && fields.ids != undefined) {
                fields.ids.forEach(function (el, index) {
                    if (el == taxNumberWithPrefix) {
                        delete fields.ids[index];
                        delete fields.params[index];
                        delete fields.type[index];
                        delete fields.errors[index];
                    }
                });
            }
        }
    }

    validateRegistration(url, name) {
        var fields = JSON.parse(JSON.stringify(this.fields));

        this._validateFirmaAndTax(name, fields);
        const form = new ValidateForm(name, fields, 3);

        if (!form.validate()) { 
            return false;
        } else {
	    var g_captcha_response = 0;
		if(document.querySelector("#g-recaptcha-response")){
			g_captcha_response = document.querySelector("#g-recaptcha-response").value;
			grecaptcha.execute();
		}
            let data = {
                username: document.querySelector('#email').value,
                email: document.querySelector('#email').value,
                'g-recaptcha-response': g_captcha_response
            };
			
            this.ajaxRequest = fetch(url+'&'+shopHelper.dataTransform(data), {
               method: 'GET'
            })

			.then(response => response.json())

			.then(request => {
	
				if (request != 1) {
                    alert(request);
                    return false;
                } else {
                    document.forms[name].submit();
                }
			});
			
            return false;
        }
    }

    _validateSecondAddress(fields) {
        let indexes = [];
        fields.ids.forEach((e, i) => {
            if (e.includes('d_')) {
                indexes.push(i);
            }
        });

        indexes.reverse().forEach(e => {
            fields.ids.splice(e, 1);
            fields.params.splice(e, 1);
            fields.type.splice(e, 1);

        });
    }

    validateAccount(name) {
		if (this.fields!=""){
        var fields = (typeof this.fields === 'string') ? JSON.parse(this.fields) : JSON.parse(JSON.stringify(this.fields));

        this._validateFirmaAndTax(name, fields);

        if (shopHelper.getElement('delivery_adress_2') && !shopHelper.getValue('delivery_adress_2')) {
            this._validateSecondAddress(fields);
        }
        const form = new ValidateForm(name, fields, 3);
        return form.validate();
		} else {return true;}
    }
	
    validateAccountField(name, field_name) {
		var keya;
        var fields = (typeof this.fields === 'string') ? JSON.parse(this.fields) : JSON.parse(JSON.stringify(this.fields));
		this.__validateFirmaAndTax(name, fields, '');
		
		if(field_name == 'password_2'){
			if(document.querySelector('#password_2').value.length == 0 || document.querySelector('#password').value != document.querySelector('#password_2').value){
				document.querySelector('#password_2').classList.add('is-invalid');
				document.querySelector('#password_2').classList.remove('is-valid');						
				document.querySelector('.password_2_error').innerHTML = fields.except.password_2; 
			}else{					
				document.querySelector('#password_2').classList.remove('is-invalid');
				document.querySelector('#password_2').classList.add('is-valid');						
				document.querySelector('.password_2_error').innerHTML = ''; 
			}
		}
		if(field_name == 'password2'){
			if(document.querySelector('#password2').value.length == 0 || document.querySelector('#password').value != document.querySelector('#password2').value){
				document.querySelector('#password2').classList.add('is-invalid');
				document.querySelector('#password2').classList.remove('is-valid');						
				document.querySelector('.password2_error').innerHTML = fields.except.password_2; 
			}else{					
				document.querySelector('#password2').classList.remove('is-invalid');
				document.querySelector('#password2').classList.add('is-valid');						
				document.querySelector('.password2_error').innerHTML = ''; 
			}
		}
		if(field_name == 'email'){
			let pattern = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if(document.querySelector('#email').value.length == 0 || !pattern.test(document.querySelector('#email').value)){
				document.querySelector('#email').classList.add('is-invalid');
				document.querySelector('#email').classList.remove('is-valid');						
				document.querySelector('.email_error').innerHTML = fields.except.email; 
			}else{					
				document.querySelector('#email').classList.remove('is-invalid');
				document.querySelector('#email').classList.add('is-valid');						
				document.querySelector('.email_error').innerHTML = ''; 
			}
		}
		if(field_name == 'phone'){
			let pattern = /^\+[1-9]\d{10,14}$/;
			if(document.querySelector('#phone').value.length == 0 || !pattern.test(document.querySelector('#phone').value)){
				document.querySelector('#phone').classList.add('is-invalid');
				document.querySelector('#phone').classList.remove('is-valid');						
				document.querySelector('.phone_error').innerHTML = fields.except.phone; 
			}else{					
				document.querySelector('#phone').classList.remove('is-invalid');
				document.querySelector('#phone').classList.add('is-valid');						
				document.querySelector('.phone_error').innerHTML = ''; 
			}
		}
		
		if(fields.ids && fields.ids.length > 0){
			fields.ids.forEach(function (el, index) { 
				if (el == field_name) {
					keya = index;
				}else{						
					delete fields.errors[index];
					delete fields.ids[index];
					delete fields.params[index];
					delete fields.type[index];
				}
			});
			const form = new ValidateForm(name, fields, 2);
			return form.validateFields();
		}
    }

    validateAddress(name) {
        var fields = (typeof this.fields === 'string') ? JSON.parse(this.fields) : JSON.parse(JSON.stringify(this.fields));
        const form = new ValidateForm(name, fields, 3);
        
        return form.validate();
    }

    setFields(fields) {
        this.fields = fields;
    }
}

export default new ShopUser();