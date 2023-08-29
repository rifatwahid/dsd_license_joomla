import ValidateForm from '../../common/validate_form/index.js';
import shopHelper from '../../common/helper/index.js';

class ShopProductForm {

    validate(name, isShowErrors = true) {
        const containerErrorsName = '#product-review-alerts';
        const fields = {
            ids: ['review_user_name', 'review_user_email', 'review_review'],
            type: ['nem', 'em', 'nem'],
            params: ['', '', '']
        }
        
        const form = new ValidateForm(name, fields, 2);
        let isValidForm = form.validate();
        var containerErrorsEl = document.querySelector(containerErrorsName);
        var reviewLangs = Joomla.getOptions('reviewLangs');

        containerErrorsEl.style.display = 'none';
       
        if (!isValidForm && isShowErrors && reviewLangs && form.errorId) {
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

        return isValidForm;
    }

    changeAction(form, newActionAddress) {
		 let _form = document.querySelector(form);
        if (form !== '' && newActionAddress !== '' && _form) {
            return _form.setAttribute('action', newActionAddress);
        } else {
            return false;
        }
    }

    addProjectName() {
        let productForm = document.querySelector('form[name=product]');
        let projectName = document.querySelector('#project-name-input');
  
        if (productForm && projectName) {
            productForm.innerHTML += `<input type="hidden" name="projectname" value="${projectName.value}" />`;
        }
    }

    formHandler(form, e) { 
        var formData = shopHelper.getFormData(form);
        var isProductPage = (form.id == 'productForm');
        var isAttrChanged = e.target && e.target.name && /jshop_attr_id/.test(e.target.name);
        var isFreeAttrChanged = e.target && e.target.name && /freeattribut\[\d*\]/.test(e.target.name);
        formData.qty = (!shopHelper.isEmpty(formData.quantity) && formData.quantity > 0) ? formData.quantity : 0;
        formData.change_attr = 0;
        
        // Set id of attr (if attr changed).
        var attrId = e.target.name.match(/jshop_attr_id\[(\d*)\]/);
        if (isAttrChanged && !shopHelper.isEmpty(attrId[1])) {
            formData.change_attr = attrId[1];
        }

        if (afterParseDataForReloadAttribEvents) {
			for(var i=0; i<afterParseDataForReloadAttribEvents.length; i++){
				afterParseDataForReloadAttribEvents[i](isProductPage, formData);
			}
        }

        // else - product list
        if (isProductPage) {
            if (isFreeAttrChanged) {
                shopProductAttributes.ajaxReloadPageData(formData);
            } else if (isAttrChanged && !shopHelper.isEmpty(formData.change_attr)) {
                shopProductAttributes.ajaxReloadPageData(formData);
				shopProductAttributes.reloadImage(formData.change_attr, e.target.value);
            }
        } else {
            formData.fromPage = 'product_list';
            shopProductAttributes.ajaxReloadProductList(formData);
        }
	}
}

export default new ShopProductForm();