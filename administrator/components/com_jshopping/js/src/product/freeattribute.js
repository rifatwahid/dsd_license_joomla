import shopProductCommon from './common.js';

class ShopProductFreeAttribute {

	constructor() {
		this.html = null;
	}

	addOption() {
		let tmplBlockEl = document.querySelector('.formula_name_empty_block');
		let btnNewOptionEl = document.querySelector('.formula_name_empty_block ~ .buttonAdd');

		if (btnNewOptionEl && tmplBlockEl) {
			btnNewOptionEl.insertAdjacentHTML('beforebegin', tmplBlockEl.innerHTML);
		}
	}

	deleteOption(id) {
		let advOptionEl = document.querySelector(`#adv_options_${id}`);
		
		if (advOptionEl) {
			advOptionEl.remove();
		}
	}

	keyUp(hide_message) {
		if (window.id_time_out != undefined) clearTimeout(window.id_time_out);

		window.id_time_out = setTimeout(() => {
			this.recalculate(hide_message);
		}, 750);
	}

	togglePriceType() {
		console.warn("Method togglePriceType(for functional `Price per consignment type`) was deleted.");
	}

	getSelectView() {
		let $this = this;
		let url = 'index.php?option=com_jshopping&controller=free_attribute_calcule_price&task=getProductAttrPriceTypeSelect';

		fetch(url, {
			method: 'POST'
		})
		.then(response => response.text())
		.then(html => {
			if (!$this.html) {
				shopProductCommon.triggers.secondHtml += html;
				$this.html = html;
			}

			shopProductCommon.triggers.secondEvents[shopProductCommon.triggers.secondEvents.length] = (id) => {
				let priceTypeEl = document.querySelector(`#attrib_ind_price_type_tmp_${id}`);
				let idTmpEl = document.querySelector(`#attr_ind_id_tmp_${id}`);

				if (priceTypeEl && idTmpEl) {
					let valueId = idTmpEl.selectedOptions[0].value;
					let priceTypeSelectEl = document.querySelector(`#attr_ind_row_${id}_${valueId} .priceTypeSelect`);

					if (priceTypeSelectEl) {
						priceTypeSelectEl.innerHTML = html;
					}

					let selectPriceType = document.querySelector(`#attr_ind_row_${id}_${valueId} select[name^="attrib_ind_price_type"]`);

					if (selectPriceType) {
						selectPriceType.value = priceTypeEl.value;
					}
				}
			}
		});
	}

	addLastRow() {
		let countOfVariables = this.getVariablesCount();

		for (let i = 1; i <= 5; i++) {
			let column = document.querySelector(`#facp_free_attr_column_${i}`);
			let childrensOfColumn = column.children;			
			let lastChild = childrensOfColumn[childrensOfColumn.length - 1].cloneNode(true);
			let rowNumber = countOfVariables.length + 1;
			let lastChildHtml = lastChild.innerHTML;
			let strForRegExp = 'var_' + countOfVariables.length;
			let regExp = new RegExp(strForRegExp, 'g');
			let variableName = document.querySelector('#adminForm .addon-params').dataset.variableDefaultName + ' ' + rowNumber;

			lastChildHtml = lastChildHtml.replace(regExp, 'var_' + rowNumber);
			lastChild.innerHTML = lastChildHtml;

			column.insertAdjacentHTML('beforeend', `<div class="facp_free_attr_row-${rowNumber}">${lastChild.innerHTML}</div>`);
			let row = document.querySelector(`#adminForm .facp_free_attr_row-${rowNumber}`);
			row.querySelector('.facp-variableName-edit__input').value = variableName;
			row.querySelector('select.facp_input').value = 0;
			let inputVarEl = column.querySelector(`input[name*=var_${rowNumber}]`);
			inputVarEl.value = '';
			inputVarEl.setAttribute('placeholder', '');

			row.querySelector('.facp_row_label__variable-name').innerHTML = variableName;
			row.querySelector('.var_descr__name').innerHTML = '$var' + rowNumber;
			
			var removeBlock = row.querySelector('.fa-xmark').outerHTML;
			console.log(row.querySelector('.fa-xmark'));
			console.log(removeBlock);
			removeBlock = removeBlock.replace('removeRow(' + countOfVariables.length + ')','removeRow(' + rowNumber + ')');
			row.querySelector('.fa-xmark').outerHTML = removeBlock;
			
			let facpRowLabelNameEl = column.querySelector(`.facp_free_attr_row-${rowNumber} .facp_row_label`);
			if (facpRowLabelNameEl) {
				facpRowLabelNameEl.innerHTML = facpRowLabelNameEl.innerHTML.replace(countOfVariables.length, rowNumber);
			}

			let varMinEl = column.querySelector(`.variable_${rowNumber}_minimum`);
			let varDefaultEl = column.querySelector(`.variable_${rowNumber}_default`);
			let varMaxEl = column.querySelector(`.variable_${rowNumber}_maximum`);

			if (varMinEl) {
				varMinEl.innerText = variableName + ' ';
			}

			if (varDefaultEl) {
				varDefaultEl.innerText = variableName + ' ';
			}

			if (varMaxEl) {
				varMaxEl.innerText = variableName + ' ';
			}
		}
	}

	hideText(el) {
		let parentEl = el.parentElement;
		parentEl.querySelector('.facp_row_label__variable-name').style.display = 'none';
		parentEl.querySelector('.facp-variableName-edit__input').style.display = 'block';
		parentEl.querySelector('.facp-variableName-edit').style.display = 'block';
		parentEl.querySelector('.var_descr').style.display = 'none';
	}

	showText(el) {
		let parentRowLabelEl = el.closest('.facp_row_label');
		let variableNameEl = parentRowLabelEl.querySelector('.facp_row_label__variable-name');
		let inputVal = parentRowLabelEl.querySelector('.facp-variableName-edit__input').value;

		parentRowLabelEl.querySelector('.facp-variableName-edit').style.display = 'block';
		parentRowLabelEl.querySelector('.icon-pencil').style.display = 'block';
		parentRowLabelEl.querySelector('.var_descr').style.display = 'block';
		variableNameEl.innerHTML = inputVal;
		variableNameEl.style.display = 'block';
		el.style.display = 'none';
		let rowNumber = (el.getAttribute('name').match(/\[var\_(\w+)\]/))[1];

		this.updateName(rowNumber, inputVal);
	}

	updateName(rowNumber, html) {
		let adminFormEl = document.querySelector('#adminForm');
		adminFormEl.querySelector(`.variable_${rowNumber}_default`).innerHTML = html;
		adminFormEl.querySelector(`.variable_${rowNumber}_minimum`).innerHTML = html;
		adminFormEl.querySelector(`.variable_${rowNumber}_maximum`).innerHTML = html;
	}

	getVariablesCount() {
		let findedElements = document.querySelectorAll('#facp_free_attr_column_1 select[id*=paramsvariablesvar_]');

		return {
			findedElems: findedElements,
			length: findedElements.length
		};
	}

	removeRow(num) {
		document.querySelector('.facp_free_attr_row-' + num).remove();
		document.querySelector('.variable_'+num+'_default').parentElement.parentElement.remove();
		document.querySelector('.variable_'+num+'_minimum').parentElement.parentElement.remove();
		document.querySelector('.variable_'+num+'_maximum').parentElement.parentElement.remove();
		if(document.querySelector('.facp_free_attr_row-' + num)) document.querySelector('.facp_free_attr_row-' + num).remove();

		
	}

}

export default new ShopProductFreeAttribute();