class ValidateForm {

    constructor(name, {
        ids,
        params,
        type,
        errors = []
    }, typeShowError, errorClass) {
        this.name = name;
        this.idEl = ids;
        this.type = type;
        this.params = params;
        this.errors = errors;
        this.end = ids.length;
        this.typeShowError = typeShowError;
        this.errorClass = (errorClass) ? errorClass : 'is-invalid';
        this.validClass = 'is-valid';
        this.errorsMsg = [];
        this.errorId = [];
        this.current = 0;
    }

    addError() {
        if (this.getType() == 'eqne' || this.getType() == 'eq') this.errorId.push(this.getParam());
        this.errorId.push(this.getId());
        this.errorsMsg.push(this.getMessage());
    }

    checkFloat() {
        let value = parseFloat(this.getValue(0));
        if (isNaN(value) || value <= 0) this.addError();
    }

    checkFloatOrEmpty() {
        if (!this.checkNotEmptyFunc(this.getValue())) {
            return true;
        } else {
            this.checkFloat();
        }
    }

    checkFloatOrEmptyOrZero() {
        if (this.getValue(0) == 0) {
            return true;
        } else {
            this.checkFloatOrEmpty();
        }
    }

    checkNotNull() {
        if (this.getValue(0) == 0) this.addError();
    }

    checkEqualNotEmpty(notEmpty) {
        let element2 = this.getValue(0);
        let element1 = this.getValue(this.getParam());

        if (element1 != element2) {
            this.addError();
        } else if (notEmpty && !this.checkNotEmptyFunc(element1)) {
            this.addError();
        }
    }

    checkNotEmptyFunc(value) {
        let pattern = /\S/;
        return (value) ? (pattern.test(value)) : (pattern.test(this.getValue(0)));
    }

    checkNotEmpty() {
        if (!this.checkNotEmptyFunc(this.getValue())) this.addError();
    }

    checkMail() {
        let pattern = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!pattern.test(this.getValue(0))) {
            this.addError();
        }
    }

    checkZipCode() {
        let pattern = /\S/;
        if (!pattern.test(this.getValue(0))) this.addError();
    }

    checkDate() {
        let tempDate = this.getValue(0);
        let pattern = new RegExp("^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$");

        if (pattern.test(tempDate)) {
            let year = RegExp.$1;
            let month = RegExp.$2;
            let day = RegExp.$3;
            if (month < 1 || month > 12) {
                this.addError();
            } else if (year > 2099 || year < 1900) {
                this.addError();
            } else if (day < 1 || day > 31) {
                this.addError();
            }
        } else {
            this.addError();
        }
    }

    checkDateOrEmpty() {
        if (!this.checkNotEmptyFunc(this.getValue())) {
            return true;
        } else {
            this.checkDate();
        }
    }

    checked() {
        if (!this.$().checked) this.addError();
    }

    getType() {
        return this.type[this.current];
    }

    getForm() {
        return document.forms[this.name];
    }

    getId() {
        return this.idEl[this.current];
    }

    getParam() {
        return this.params[this.current];
    }

    getMessage() {
        return this.errors[this.current];
    }

    getCurrent() {
        return this.idEl[this.current];
    }

    next() {
        this.current++;
    }

    notEnd() {
        return (this.current < this.end);
    }

    $(idElement) {
        return (idElement) ? (document.getElementById(idElement)) : (document.getElementById(this.getCurrent()));
    }

    getValue(idElement) {
        if (!idElement) {
            var element = this.$(this.getCurrent());
        } else {
            var element = this.$(idElement);
        }

        switch (element.type) {
            case 'select-one':
                return element.options[element.selectedIndex].value;
            case 'radio':
            case 'checkbox':
                return element.checked;
            case 'text':
            case 'password':
            case 'textarea':
                return element.value;
        }
    }

    validate() {
        this.unhighlightFields();

        while (this.notEnd()) {
            if (this.$(0)) {
                switch (this.getType()) {
                    case 'nem':
                        this.checkNotEmpty();
                        break;
                    case 'em':
                        this.checkMail();
                        break;
                    case 'chk':
                        this.checked();
                        break;
                    case 'fl|em':
                        this.checkFloatOrEmpty();
                        break;
                    case 'fl|em|0':
                        this.checkFloatOrEmptyOrZero();
                        break;
                    case 'notn':
                        this.checkNotNull();
                        break;
                    case 'eqne':
                        this.checkEqualNotEmpty(1);
                        break;
                    case 'date|em':
                        this.checkDateOrEmpty(this.getParam());
                        break;
                    case 'zip':
                        this.checkZipCode();
                        break;
                }
            }

            this.next();
        }

        if (this.errorsMsg.length) {

            //this.$(this.errorId[0]).focus();

            switch (this.typeShowError) {
                case 1:
                    this.showErrors();
                    break;
                case 2:
                    this.highlightFields();
                    break;
                case 3:
                    this.showErrors();
                    this.highlightFields();
                    break;
            }

            return false;
        } else {
            return true;
        }
    }

    validateFields() {
      
        //this.unhighlightField();
        while (this.notEnd()) {
            if (this.$(0)) {
				var element = this.$(this.getCurrent());
				var element_id = element.getAttribute('id');
                switch (this.getType()) {
                    case 'nem':
                        this.checkNotEmpty();
                        break;
                    case 'em':
                        this.checkMail();
                        break;
                    case 'chk':
                        this.checked();
                        break;
                    case 'fl|em':
                        this.checkFloatOrEmpty();
                        break;
                    case 'fl|em|0':
                        this.checkFloatOrEmptyOrZero();
                        break;
                    case 'notn':
                        this.checkNotNull();
                        break;
                    case 'eqne':
                        this.checkEqualNotEmpty(1);
                        break;
                    case 'date|em':
                        this.checkDateOrEmpty(this.getParam());
                        break;
                    case 'zip':
                        this.checkZipCode();
                        break;
                }
            }

            this.next();
        }
		
			if (this.errorsMsg.length) {
				switch (this.typeShowError) {
					case 1:
						this.showErrors();
						break;
					case 2:
						this.highlightFields();
						let messages = this.errorsMsg.filter(e => e !== null);
						document.querySelector('.'+element_id+'_error').innerHTML = messages.join('</br>');
						if(element){
							element.classList.remove(this.validClass);
						}
						break;
					case 3:
						this.showErrors();
						this.highlightFields();
						break;
				}

				return false;
			} else {
				if (element && element.type.length && element.type == 'checkbox') {
					element.parentNode.classList.remove(this.errorClass);
					document.querySelector('.'+element_id+'_error').innerHTML = '';
					document.querySelector('#'+element_id).closest( ".form-group" ).style.marginBottom = '1rem';
					element.classList.add(this.validClass);
				} else {
					if(element){
						element.classList.remove(this.errorClass);	
						document.querySelector('.'+element_id+'_error').innerHTML = '';
						element.classList.add(this.validClass);
					}
				}
			}
    }

    showErrors() {
        let messages = this.errorsMsg.filter(e => e !== null);
        shopHelper._scrollTo(document.querySelector('html, body'), 0, 250);
        document.querySelector('#qc_error').innerText = messages.join('\n');
        shopHelper.show(document.querySelector('#qc_error'));
    }

    unhighlightFields() {
        let form = this.getForm();

        for (let i = 0; i < form.length; i++) {
            if (form.elements[i].type == 'button' || form.elements[i].type == 'submit' || form.elements[i].type == 'hidden') {
                continue;
            } else if (form.elements[i].type == 'checkbox') {
                form.elements[i].parentNode.classList.remove(this.errorClass);
            } else {
                form.elements[i].classList.remove(this.errorClass);
            }
        }
    }

    highlightFields() {
        for (let i = 0; i < this.errorId.length; i++) {
            if (this.$(this.errorId[i]) === null) continue;

            if (this.$(this.errorId[i]).type == 'checkbox') {
                this.$(this.errorId[i]).parentNode.classList.add(this.errorClass);
            } else {
                this.$(this.errorId[i]).classList.add(this.errorClass);
            }
        }
    }
}

export default ValidateForm;