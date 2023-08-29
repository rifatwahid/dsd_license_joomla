import shopProductCommon from './common.js';

class ShopProductAttribute {
	
	constructor() {
        this.number = 100500;
    }
	
    addSecondValue(id, hidden) {
        let $this = this;
        let rowNumber = 0;
        let value_id = document.querySelector(`#attr_ind_id_tmp_${id}`).value;
        let existcheck = document.querySelector(`#attr_ind_${id}_${value_id}`);
    
        if (existcheck) {
            alert(lang_attribute_exist);
            return 0;
        }    
    
        if (value_id == '0') {
            alert(lang_error_attribute);
            return 0;
        }

        let attrInRowEl = document.querySelector(`#list_attr_value_ind_${id}[id*=attr_ind_row_]`);

        if (attrInRowEl) {
            rowNumber = attrInRowEl.length++;
        }

        let selectChildren = document.querySelector(`#attr_ind_id_tmp_${id}`).children;
        let selectValues = Array.from(selectChildren).map(v => +v.value);
        let attr_value_text = document.querySelector(`#attr_ind_id_tmp_${id}`).selectedOptions[0].text;
        let mod_price = document.querySelector(`#attr_price_mod_tmp_${id}`).value;
        let price = document.querySelector(`#attr_ind_price_tmp_${id}`).value;
		let expiration_dateValue = document.querySelector(`#attr_ind_expiration_date_tmp_${id}`).value;
        let weightValueEl = document.querySelector(`#attr_ind_weight_tmp_${id}`);
        let weightValue = 0;

        if (weightValueEl) {
            weightValue = weightValueEl.value;
        }
    
        if (hidden) {
            for (let val of selectValues) {
                let exist = document.querySelector(`#attr_ind_${id}_${val}`);
    
                if (exist !== null) {
                    alert(Joomla.JText._('COM_SMARTSHOP_HIDDEN_ATTR_ADD_ERROR'));
                    return;
                }
            }
        };

        let html = `<tr id='attr_ind_row_${id}_${value_id}' class='ui-sortable-handle'>`; 
        
        hidden = `<input type='hidden' id='attr_ind_${id}_${value_id}' name='attrib_ind_id[]' value='${id}'>`;
        let hidden2 = `<input type='hidden' name='attrib_ind_value_id[]' value='${value_id}'>`;
        let tmpimg = "";
    
        if (value_id != 0 && attrib_images[value_id] != "") {
            tmpimg =`<img src="${folder_image_attrib}/${attrib_images[value_id]}" style="margin-right:5px;" width="16" height="16" class="img_attrib">`;
        }
    
        html += `<td><span class='icon-menu' aria-hidden='true'></span></td>`;
        html += "<td>" + hidden + hidden2 + tmpimg + attr_value_text + "</td>";
        html += `
            <td>
                <input type='text' class='small3' name='attrib_ind_price_mod[]' value='${mod_price}'>
            </td>
            <td>
                <input type='text' class='small3' name='attrib_ind_price[]' value='${price}'>
            </td>
            <td class='priceTypeSelect'></td>
            <td>
                <input type='text' class='small3 ' name='attrib_ind_weight[]' value='${weightValue}'>
            </td>
			<td>
                <input type='date' class='small3 ' name='attrib_ind_expiration_date[]' value='${expiration_dateValue}'>
            </td>	
            <td>
                <a class='btn btn-micro' href='#' onclick="document.querySelector('#attr_ind_row_${id}_${value_id}').remove();return false;">
                    <i class="icon-delete"></i>
                </a>
                <input type='hidden' name='product_independ_attr_sorting[]' value='${rowNumber}'>
            </td>
        </tr>
        `;

        document.querySelector(`#list_attr_value_ind_${id} tbody`).insertAdjacentHTML('beforeEnd', html);
    
        shopProductCommon.triggers.secondEvents.forEach(function (handler) {
            handler.call($this, id);
        });
    }

    addValue() {
        attr_tmp_row_num++;
        let id = 0;
        let ide = 0;
        let html = "";
        let hidden = "";
        let field = "";
        let count_attr_sel = 0;
        let tmpmass = {};
        let tmpimg = "";
        let selectedval = {};
        let num = 0;
        let current_index_list = [];
        let max_index_list = [];
        let combination = 1;
        let count_attributs = attrib_ids.length;
        let index = 0;
        let option = {};
        let $this = this;

        for (let i = 0; i < count_attributs; i++) {
            current_index_list[i] = 0;
            id = attrib_ids[i];
            ide = "value_id" + id;
            selectedval[id] = [];
            num = 0;

            let options = document.querySelector(`#${ide}`).selectedOptions;

            if (options) {
                for (let selected of options) {
                    let value = selected.value; 
                    let text = selected.text;

                    if (value) {
                        selectedval[id][num] = {
                            text, 
                            value
                        };
                        num++;
                    }
                }
            }

            if (selectedval[id].length == 0) {
                selectedval[id][0] = {text: "-", value: "0"};
            } else {
                count_attr_sel++;    
            }

            max_index_list[i] = selectedval[id].length;
            combination = combination * max_index_list[i];
        }

        let first_attr = document.querySelectorAll('#list_attr_value tbody tr:nth-child(1) input[type=hidden]');
        
        if (first_attr.length > 0) {
            for (let k = 0; k < count_attributs; k++) {
                id = attrib_ids[k];

                if (first_attr[k].value == 0) {
                    if (selectedval[id][0].value != 0)  {
                        alert(lang_error_attribute);
                        return 0;
                    }
                }

                if (first_attr[k].value !=0 )  {
                    if (selectedval[id][0].value == 0) {
                        alert(lang_error_attribute);
                        return 0;
                    }
                }
            }
        }

        if (count_attr_sel == 0) {
            alert(lang_error_attribute);
            return 0;
        }

        let only_one_hidden = document.querySelector(`#only_one_hidden_${id}`);
        
        if (only_one_hidden !== null) {

            if (selectedval[id].length > 1) {
                alert(Joomla.JText._('COM_SMARTSHOP_HIDDEN_ATTR_ADD_ERROR'));
                return;
            }

            for (let attr in attrib_exist) {
                if (attrib_exist[attr][id]) {
                    alert(Joomla.JText._('COM_SMARTSHOP_HIDDEN_ATTR_ADD_ERROR'));
                    return;
                }
            }
        }

        let list_key = [];
        for (let j = 0; j < combination; j++) {
            list_key[j] = [];

            for (let i = 0; i < count_attributs; i++) {
                id = attrib_ids[i];
                num = current_index_list[i];
                list_key[j][i] = num;
            }

            index = 0;
            for (let i = 0; i < count_attributs; i++) {
                if (i == index) {
                    current_index_list[index]++;

                    if (current_index_list[index] >= max_index_list[index]) {
                        current_index_list[index] = 0;
                        index++;
                    }
                }
            }
        }

        /* Price */
        let price = document.querySelector('#attr_price') ? document.querySelector('#attr_price').value : null;
        let old_price = document.querySelector('#attr_old_price') ? document.querySelector('#attr_old_price').value : null;
        let productPriceType = document.querySelector('#attr_product_price_type') ? document.querySelector('#attr_product_price_type').value : null;
        let qtyDiscount = document.querySelector('input[class^="qtydiscount"]:checked') ? document.querySelector('input[class^="qtydiscount"]:checked').value : null;
        let weight_units = document.querySelector('#attr_weight_volume_units') ? document.querySelector('#attr_weight_volume_units').value : null;
        let basic_price_unit_id = document.querySelector('#attr_basic_price_unit_id') ? document.querySelector('#attr_basic_price_unit_id').value : null;
        let lowStockAttrNotifyNumberEl = document.querySelector('#low_stock_attr_notify_number');
        let attrProdTaxIdEl = document.querySelector('#attr_product_tax_id');
        let attrsLabelsEl = document.querySelector('#attr_labels');
        let attrNoReturnEl = document.querySelector('#attr_no_return');
        let pricePerConsignmentUnitIdEl = document.querySelector('#attr_price_per_consignment_basic_price_unit_id');
        let attrRowsEl = document.querySelectorAll('#list_attr_value tr[id*="attr_row_"]');

        /* Details */
        //let attrProductPackingType = document.querySelector('#attr_product_packing_type').value;
		let publish_editor_pdf = document.querySelector('input[class^="publish_editor_pdf"]:checked') ? document.querySelector('input[class^="publish_editor_pdf"]:checked').value : null;
        let weight = document.querySelector('#attr_weight') ? document.querySelector('#attr_weight').value : null;
        let expiration_date = document.querySelector('#attr_expiration_date') ? document.querySelector('#attr_expiration_date').value : null;
        let ean = document.querySelector('#attr_ean') ? document.querySelector('#attr_ean').value : null;
        let count = document.querySelector('#attr_count') ? document.querySelector('#attr_count').value : null;
        let attr_unlimited = (document.querySelector('#attr_unlimited') && document.querySelector('#attr_unlimited').checked) ? 'checked' : '';
        let style = (attr_unlimited) ? 'style="display:none;"' : '';
        let notify_number =  (lowStockAttrNotifyNumberEl && parseInt(lowStockAttrNotifyNumberEl.value) ) ? parseInt(lowStockAttrNotifyNumberEl.value) : 0;
        let notify_status = (document.querySelector('#low_stock_attr_notify_status') && document.querySelector('#low_stock_attr_notify_status').checked) ? 'checked' : '';      
        let attrFactory = document.querySelector('#attr_factory') ? document.querySelector('#attr_factory').value : null;
        let attrStorage = document.querySelector('#attr_storage') ? document.querySelector('#attr_storage').value : null;
        let attrProductTaxId = 0;
        if (attrProdTaxIdEl) {
            attrProductTaxId = attrProdTaxIdEl.value;
        }
        let attrProductManufacturerId = document.querySelector('#attr_product_manufacturer_id') ? document.querySelector('#attr_product_manufacturer_id').value : null;
        let apt = document.querySelector('#attr_production_time') ? document.querySelector('#attr_production_time').value : null;
        let attrDeliveryTimesId = document.querySelector('#attr_delivery_times_id') ? document.querySelector('#attr_delivery_times_id').value : null;
        let attrLabels = 0; 
        if(attrsLabelsEl) {
            attrLabels = attrsLabelsEl.value;
        }
        let attrNoReturn = (attrNoReturnEl && attrNoReturnEl.checked) ? 1 : 0;
        let attrQuantitySelect = document.querySelector('#attr_quantity_select') ? document.querySelector('#attr_quantity_select').value : null;
        let attrMaxCountProduct = document.querySelector('#attr_max_count_product') ? document.querySelector('#attr_max_count_product').value : null;
        let attrMinCountProduct = document.querySelector('#attr_min_count_product') ? document.querySelector('#attr_min_count_product').value : null;
        let attrConsignmentProductIsAddPrice = (document.querySelector('#attr_consignment_product_is_add_price') && document.querySelector('#attr_consignment_product_is_add_price').checked) ? 1 : 0;
        let attrConsignmentProductUploadIsAddPrice = (document.querySelector('#attr_is_activated_price_per_consignment_upload') && document.querySelector('#attr_is_activated_price_per_consignment_upload').checked) ? 1 : 0;
        let attrPricePerConsignmentBasicPriceUnitId = 0;
        if (pricePerConsignmentUnitIdEl) {
            attrPricePerConsignmentBasicPriceUnitId = pricePerConsignmentUnitIdEl.value;
        }

        let buy_price = 0;
        let attrBuyPriceEl = document.querySelector('#attr_buy_price');

        if (attrBuyPriceEl) {
            buy_price = attrBuyPriceEl.value;
        }

        let attrsRowNumber = attrRowsEl.length - 1;
        let loopRowNumber = attrsRowNumber;
        let count_added_rows = 0;
        let pricePerConsignmentDataOfDependAttrs = this._getPricePerConsigmentDataOfDependAttr();
        let equalSteps = parseInt(document.querySelector('[name="attr_equal_steps"]:checked') ? document.querySelector('[name="attr_equal_steps"]:checked').value : false);
        let attr_media_title = document.querySelector('#attr_media_title') ? document.querySelector('#attr_media_title').value : null;
        let attr_media_link = document.querySelector('#attr_media_link') ? document.querySelector('#attr_media_link').value : null;
        let attr_media_preview = document.querySelector('#attr_media_preview') ? document.querySelector('#attr_media_preview').value : null;
        let attr_media_file = document.querySelector('#attr_media_file') ? document.querySelector('#attr_media_file').value : null;
        
		var _style = '';
        for(let j = 0; j < combination; j++) {
            tmpmass = {};
            html = `<tr id='attr_row_${attr_tmp_row_num}'><td><span class='icon-menu' aria-hidden='true'></span></td>`;
            let htmlOfPricePerConsigmentDataOfDependAttr = this._getHtmlPricePerConsigmentDataOfDependAttr(pricePerConsignmentDataOfDependAttrs, loopRowNumber);
            let pricePerConsignmentUploadDataOfDependAttrs = this._getPricePerConsigmentUploadDataOfDependAttr(loopRowNumber);
            let htmlOfPricePerConsigmentUploadDataOfDependAttr = this._getHtmlPricePerConsigmentDataOfDependAttr(pricePerConsignmentUploadDataOfDependAttrs, null);
            let htmlOfUsergroupDataOfDependAttr =  this._getAttrPriceUsergroup(loopRowNumber);
    
            for (let i = 0; i < count_attributs; i++){

                id = attrib_ids[i];
                num = list_key[j][i];
                option = selectedval[id][num];
                hidden = `<input type='hidden' name='attrib_id[${id}][]' value='${option.value}'>`;
                tmpimg="";
				_style = '';
				 
				if (option.value != 0 && attrib_images[option.value] != "") {					
                    tmpimg =`<img src="${folder_image_attrib}/${attrib_images[option.value]}" style="margin-right:5px;" width="16" height="16" class="img_attrib">`;
                }
				
				if (option.value != 0){
					if(document.querySelector('.col_attr_'+id) && document.querySelector('.col_attr_'+id).style.display == "none"){
						document.querySelectorAll('.col_attr_'+id).forEach(function(item){
							item.style.display = 'revert';
						});
					}
				}else{
					if(document.querySelector('.col_attr_'+id) && document.querySelector('.col_attr_'+id).style.display == "none"){
						_style = "display:none;"
					}
				}

                html += "<td class='col_attr_"+id+"' style='"+_style+"'>" + hidden + tmpimg + option.text + "</td>";
                tmpmass[id] = option.value;
            }

            field= `<input type='text' class="form-control" name='attrib_price[]' value='${price}'>`;
            html += `<td>${field}</td>`;
            html += shopProductCommon.triggers.html;

            if (use_stock) {
                field = `<div id='block_enter_attr_qty_${attr_tmp_row_num}' ${style}><input type='text' class="form-control" name='attr_count[]' value='${count}'></div>
				<div><input type='hidden'  name='attr_unlimited[${loopRowNumber}]' value='0' />
				    <input type='checkbox' class="form-check-input" name='attr_unlimited[${loopRowNumber}]' ${attr_unlimited} value='1' onclick='shopProductCommon.toggleAttrQuantityAdd(this.checked, ${attr_tmp_row_num})'  />Unlimited</div>`;
				
                html += `<td>${field}</td>`;
                field = `
                    <input type='hidden' name='low_stock_attr_notify_status[${loopRowNumber}]' value='0'>
                    <input type='checkbox' class="form-check-input" style='width: 20px; margin-bottom: 14px;' ${notify_status} name='low_stock_attr_notify_status[${loopRowNumber}]' value='1'>
                    <input type='number' class="form-control" name='low_stock_attr_notify_number[${loopRowNumber}]' value='${notify_number}'>`;
                html += `<td>${field}</td>`;             
            }

            field = `<input type='text' class="form-control" name='attr_ean[]' value='${ean}'>`;
            html += `<td>${field}</td>`;

            field = `<input type='text' class="form-control" name='attr_weight[]' value='${weight}'>`;
            html += `<td>${field}</td>`;
			
			field = `<input type='date' class="form-control" name='attr_expiration_date[]' value='${expiration_date}'>`;
            html += `<td>${field}</td>`;
			
			html+=`<td><input type='number' class="form-control" name='attr_production_time[]' value='${apt}'></td>`;
            
			if (use_basic_price == "1") {
                field = `<input type='text' class="form-control" name='attr_weight_volume_units[]' value='${weight_units}'>`;
                html += `<td>${field}</td>`;
            }

            field = `<input type='text' class="form-control" name='attrib_old_price[]' value='${old_price}'>`;
            html += `<td>${field}</td>`;

            if (use_bay_price == "1") {
                field = `<input type='text' class="form-control" name='attrib_buy_price[]' value='${buy_price}'>`;
                html += `<td>${field}</td>`;
            }
            //<input type='hidden' name='attr_product_packing_type[]' value='${attrProductPackingType}'>
            html += `
                    <td></td>
                    <td>
                        <input type='checkbox' class='ch_attr_delete form-check-input' value='${attr_tmp_row_num}'>
                        <input type='hidden' name='product_attr_sorting[]' value='${attr_tmp_row_num}'>
                        <input type='hidden' name='product_attr_id[]' value='0'>
                        
                        <input type='hidden' name='attr_product_price_type[]' value='${productPriceType}'>
                        <input type='hidden' name='attr_qtydiscount[]' value='${qtyDiscount}'>
                        <input type='hidden' name='attr_factory[]' value='${attrFactory}'>
                        <input type='hidden' name='attr_storage[]' value='${attrStorage}'>
                        <input type='hidden' name='attr_product_tax_id[]' value='${attrProductTaxId}'>
                        <input type='hidden' name='attr_product_manufacturer_id[]' value='${attrProductManufacturerId}'>
                        <input type='hidden' name='attr_delivery_times_id[]' value='${attrDeliveryTimesId}'>
                        <input type='hidden' name='attr_labels[]' value='${attrLabels}'>
                        <input type='hidden' name='attr_no_return[]' value='${attrNoReturn}'>
                        <input type='hidden' name='attr_quantity_select[]' value='${attrQuantitySelect}'>
                        <input type='hidden' name='attr_max_count_product[]' value='${attrMaxCountProduct}'>
                        <input type='hidden' name='attr_min_count_product[]' value='${attrMinCountProduct}'>
                        <input type='hidden' name='attr_basic_price_unit_id[]' value='${basic_price_unit_id}'>
                        <input type='hidden' name='attr_add_price_unit_id[]' value='${attrPricePerConsignmentBasicPriceUnitId}'>
                        <input type='hidden' name='attr__equal_steps[]' value='${equalSteps}'>
						<input type='hidden' name='attr_publish_editor_pdf[]' value='${publish_editor_pdf}'>

                        <input type='hidden' name='attr__consignment_product_is_add_price[]' value='${attrConsignmentProductIsAddPrice}'>
                        <input type='hidden' name='attr__is_activated_price_per_consignment_upload[]' value='${attrConsignmentProductUploadIsAddPrice}'>
                        <input type='hidden' name='attr_media[${loopRowNumber}][media][][title]' value='${attr_media_title}'>
                        <input type='hidden' name='attr_media[${loopRowNumber}][media][][link]' value='${attr_media_link}'>
                        <input type='hidden' name='attr_media[${loopRowNumber}][media][][preview]' value='${attr_media_preview}'>
                        <input type='hidden' name='attr_media[${loopRowNumber}][media][][file]' value='${attr_media_file}'>
                        <input type='hidden' name='attr_media[${loopRowNumber}][media][][is_main]' value='1'>
                        ${htmlOfPricePerConsigmentDataOfDependAttr.join('')}
                        ${htmlOfPricePerConsigmentUploadDataOfDependAttr.join('')}
                        ${htmlOfUsergroupDataOfDependAttr.join('')}
                    </td>
                </tr>
            `;
            let existcheck = 0;
            for ( let k in attrib_exist ) {
                let exist = 1; 

                for(let i = 0; i < count_attributs; i++) {
                    id = attrib_ids[i];
                    if (attrib_exist[k][id]!=tmpmass[id]) exist=0;
                }

                if (exist) {
                    existcheck = 1;
                    break;
                }
            }

            if (!existcheck) {
                let listAttrValBodyEl = document.querySelector('#list_attr_value tbody');
                if (listAttrValBodyEl) {
                    listAttrValBodyEl.insertAdjacentHTML('beforeend', html);
                }

                attrib_exist[attr_tmp_row_num] = tmpmass;
                attr_tmp_row_num++;
                count_added_rows++;
            }

            ++loopRowNumber;
        }
        if (count_added_rows == 0) {
            alert(lang_attribute_exist);
            return 0;
        }

        shopProductCommon.triggers.events.forEach(function (handler) {
            handler.call($this, count_added_rows);
        });

        return 1;
    }

    editExtendParams(id) {
        window.open('index.php?option=com_jshopping&controller=products&task=edit&product_attr_id='+ id, 'windowae','width=1000, height=760, scrollbars=yes,status=no,toolbar=no,menubar=no,resizable=yes,location=yes');
    }

    deleteList() {
        document.querySelector('#ch_attr_delete_all').checked = false;
        let attrCheckboxDelsEls = document.querySelectorAll('.ch_attr_delete');
        if (attrCheckboxDelsEls) {
            attrCheckboxDelsEls.forEach(function (item) {
                if (item.checked) {
                    let num = item.value;
                    if(document.querySelector(`#attr_row_${num}`)) document.querySelector(`#attr_row_${num}`).remove()
                    delete attrib_exist[num];
                }
            });
        }
    }

    selectList(checked) {
        let delAttrs = document.querySelectorAll('.ch_attr_delete');

        if (delAttrs) {
            delAttrs.forEach(function (item) {
                item.checked = checked;
            });
        }
    }

    batchEdit(selectorWhereSearch) {
        let productsId = [];
        let selectedDependAttrs = document.querySelector(selectorWhereSearch).querySelectorAll('.ch_attr_delete:checked');

        if (selectedDependAttrs.length <= 0) {
            return alert(Joomla.JText._('COM_SMARTSHOP_NO_DEPENDENT_ATTRIBUTE_SELECTED'));
        }

        Array.prototype.forEach.call(selectedDependAttrs, (selectedDependAttrEl) => {
            let parentEl = selectedDependAttrEl.parentElement;
            let productAttrIdEl = parentEl.querySelector('[name^=product_attr_id]');
            let extAttrProductIdEl = parentEl.querySelector('[name^=ext_attribute_product_id]');

            if (productAttrIdEl && extAttrProductIdEl) {
                if (productAttrIdEl.value > 0 && extAttrProductIdEl.value > 0) {
                    productsId.push({
                        productAttrId: productAttrIdEl.value,
                        extAttrProductId: extAttrProductIdEl.value,
                    });
                }
            }
        });

        if (productsId.length >= 1) {
            window.open('/administrator/index.php?option=com_jshopping&controller=products&task=displayDependAttrEditList&ids=' + encodeURIComponent(JSON.stringify(productsId)), '_blank').focus();
        } else {
            return alert(Joomla.JText._('COM_SMARTSHOP_NO_DEPENDENT_ATTRIBUTE_SELECTED'));
        }
    }

    _getHtmlPricePerConsigmentDataOfDependAttr(data, rowNumber) {
        let result = [];

        if (data) {
            Object.keys(data).forEach(function(inputName) {
                let modifiedInputName = inputName;

                if (rowNumber !== null) {
                    modifiedInputName += '[' + rowNumber + '][]';
                } else {
                    modifiedInputName += '[]';
                }

                let htmlTmpl = `<input type='hidden' name="${modifiedInputName}" value="~~">`;
                let elData = data[inputName];
                
                if (elData !== null) {
                    Object.keys(elData).forEach(function(value) {
                        let html = htmlTmpl.replace('~~', elData[value]);
                        result.push(html);
                    });
                } else {
                    let html = htmlTmpl.replace('~~', '');
                    result.push(html);
                }
            });
        }

        return result;
    }

    _getAttrPriceUsergroup(loopRowNumber) {
        let result = {};
        let result2 = [];
        let attrUsergroup = document.querySelector('#attribs-page');
        let partOfName = `attrDependUsergroup[${loopRowNumber}]`;
		result.add_usergroups_prices_usergroup = [];
		result.add_usergroups_prices_product_price = [];
		result.add_usergroups_prices_product_price2 = [];
		result.add_usergroups_prices_product_old_price = [];
        
		var rows = document.querySelectorAll('.usergroup_price');
		if( rows.length > 0 ){			
			result.add_usergroup_price = 1;
		}else{
			result.add_usergroup_price = null;
		}
       if(result.add_usergroup_price){
			var blocks = attrUsergroup.querySelectorAll('#attr_div_hidden_add_new_usergroup_price .usergroup_price');
			var k = 0;
			blocks.forEach(function(item){
				result.add_usergroups_prices_usergroup[k] = item.querySelector('[id^=attr_depend_add_usergroups_prices_usergroup]').value || '';
				result.add_usergroups_prices_product_price[k] = item.querySelector('[id^=attr_add_usergroups_prices_product_price_list]').value || '';
				result.add_usergroups_prices_product_price2[k] = item.querySelector('[id^=attr_add_usergroups_prices_product_price2_list]') && item.querySelector('[id^=attr_add_usergroups_prices_product_price2_list]').value || '';
				result.add_usergroups_prices_product_old_price[k] = item.querySelector('[id^=attr_add_usergroups_prices_product_old_price]').value || '';

				result2.push(`<input type="hidden" name="${partOfName}[${k}][add_usergroup_price]" value="${result.add_usergroup_price[k]}">`);
				result2.push(`<input type="hidden" name="${partOfName}[${k}][add_usergroups_prices_usergroup]" value="${result.add_usergroups_prices_usergroup[k]}">`);
				result2.push(`<input type="hidden" name="${partOfName}[${k}][add_usergroups_prices_product_price]" value="${result.add_usergroups_prices_product_price[k]}">`);
				result2.push(`<input type="hidden" name="${partOfName}[${k}][add_usergroups_prices_product_price2]" value="${result.add_usergroups_prices_product_price2[k]}">`);
				result2.push(`<input type="hidden" name="${partOfName}[${k}][add_usergroups_prices_product_old_price]" value="${result.add_usergroups_prices_product_old_price[k]}">`);

				let findedEl = {};
				var attrPage = document.querySelector('#attribs-page');
				let nameOfIsAddPrice = `${partOfName}[${k}][add_usergroups_prices_product_is_add_price]`;
				let nameOfAddDiscount = `${partOfName}[${k}][add_usergroups_prices_product_add_discount][]`;
				let nameOfQtyStart = `${partOfName}[${k}][add_usergroups_prices_quantity_start][]`;
				let nameOfQtyFinish = `${partOfName}[${k}][add_usergroups_prices_quantity_finish][]`;
				let nameOfAddPrice = `${partOfName}[${k}][add_usergroups_prices_product_add_price][]`;
				let nameOfStartDiscount = `${partOfName}[${k}][add_usergroups_prices_start_discount][]`;
				let nameOfPricesUsergroupUnitId = `${partOfName}[${k}][add_usergroups_prices_add_price_unit_id]`;
				let priceUnitID = item.querySelector('[id^=attr_add_usergroups_prices_add_price_unit_id]').value || 0;
				let productIsAddPriceValue = item.querySelector('[id^=attr_add_usergroups_prices_product_is_add_price]').value || 0;

				findedEl[nameOfAddDiscount] = item.querySelectorAll('[name^=attr_add_usergroups_prices_product_add_discount]') || '';
				findedEl[nameOfQtyStart] = item.querySelectorAll('[name^=attr_add_usergroups_prices_quantity_start]') || '';
				findedEl[nameOfQtyFinish] = item.querySelectorAll('[name^=attr_add_usergroups_prices_quantity_finish]') || '';
				findedEl[nameOfAddPrice] = item.querySelectorAll('[name^=attr_add_usergroups_prices_product_add_price]') || '';
				findedEl[nameOfStartDiscount] = item.querySelectorAll('[name^=attr_add_usergroups_prices_start_discount]') || '';
				

				Object.keys(findedEl).forEach(function(inputName) {
					let elements = findedEl[inputName];
					
					if (elements !== '') {
						Object.keys(elements).forEach(function(key) {
							let domEl = elements[key];
							let value = domEl.value || '';

							result2.push(`<input type="hidden" name="${inputName}" value="${value}">`);
						});
					} else {
						result2.push(`<input type="hidden" name="${inputName}" value="''">`);
					}
				});

				result2.push(`<input type="hidden" name="${nameOfPricesUsergroupUnitId}" value="${priceUnitID}">`);
				result2.push(`<input type="hidden" name="${nameOfIsAddPrice}" value="${productIsAddPriceValue}">`);				
				
				k++;
			});
		}
        return result2;
    }

    _getPricePerConsigmentUploadDataOfDependAttr(loopRowNumber) {
        let fromItemName = `attr__nativeProgressUploads[prices][updates][${loopRowNumber}][from_item]`;
        let toItemName = `attr__nativeProgressUploads[prices][updates][${loopRowNumber}][to_item]`;
        let percentItemName = `attr__nativeProgressUploads[prices][updates][${loopRowNumber}][percent]`;
        let priceItemName = `attr__nativeProgressUploads[prices][updates][${loopRowNumber}][price]`;
        let calcPriceItemName = `attr__nativeProgressUploads[prices][updates][${loopRowNumber}][calculated_price]`;

        let findedEl = {};
        let result = {};
        result[fromItemName] = [];
        result[toItemName] = [];
        result[percentItemName] = [];
        result[priceItemName] = [];
        result[calcPriceItemName] = [];
        var attrPage = document.querySelector('#attribs-page #attrnativeProgressUploadsAddPrices');

        findedEl[fromItemName] = attrPage.querySelectorAll('input.attrnativeProgressUploadsAddPrices__from-el') || null;
        findedEl[toItemName] = attrPage.querySelectorAll('input.attrnativeProgressUploadsAddPrices__to-el') || null;
        findedEl[percentItemName] = attrPage.querySelectorAll('input.attrnativeProgressUploadsAddPrices__percent-el') || null;
        findedEl[priceItemName] = attrPage.querySelectorAll('input.attrnativeProgressUploadsAddPrices__price-el') || null;
        findedEl[calcPriceItemName] = attrPage.querySelectorAll('input.attrnativeProgressUploadsAddPrices__calculated-price-el') || null;

        Object.keys(findedEl).forEach(function(inputName) {
            let elements = findedEl[inputName];
            
            if (elements !== null) {
                Object.keys(elements).forEach(function(key) {
                    let domEl = elements[key];
                    let value = domEl.value || null;

                    result[inputName].push(value);
                });
            } else {
                result[inputName].push(null);
            }
        });

        return result;
    }

    _getPricePerConsigmentDataOfDependAttr() {
        let findedEl = {};
        let result = {
            attr__consignment_quantity_start: [],
            attr__consignment_quantity_finish: [],
            attr__consignment_product_add_discount: [],
            attr__consignment_product_add_price: [],
            attr__consignment_start_discount: []
        };
        var attrPage = document.querySelector('#attribs-page #attr_consignment_table_add_price');

        findedEl.attr__consignment_quantity_start = attrPage.querySelectorAll('input[name^="attr_consignment_quantity_start"]') || null;
        findedEl.attr__consignment_quantity_finish = attrPage.querySelectorAll('input[name^="attr_consignment_quantity_finish"]') || null;
        findedEl.attr__consignment_product_add_discount = attrPage.querySelectorAll('input[name^="attr_consignment_product_add_discount"]') || null;
        findedEl.attr__consignment_product_add_price = attrPage.querySelectorAll('input[name^="attr_consignment_product_add_price"]') || null;
        findedEl.attr__consignment_start_discount = attrPage.querySelectorAll('input[name^="attr_consignment_start_discount"]') || null;

        Object.keys(findedEl).forEach(function(inputName) {
            let elements = findedEl[inputName];
            
            if (elements !== null) {
                Object.keys(elements).forEach(function(key) {
                    let domEl = elements[key];
                    let value = domEl.value || null;

                    result[inputName].push(value);
                });
            } else {
                result[inputName].push(null);
            }
        });

        return result;
    }

    _getPricePerGropDataOfDependAttr() {
        let findedEl = {};
        let result = {
            attr__usergroup_price: [],
            attr__usergroups_prices_usergroup: [],
            attr__usergroups_prices_product_price: [],
            attr__usergroups_prices_product_price2: [],
            attr__usergroups_prices_product_old_price: [],
            attr__usergroups_prices_add_price_unit_id: [],
            attr__usergroups_prices_product_is_add_price: []
        };
        var attrPage = document.querySelector('#attribs-page #attr_div_hidden_add_new_usergroup_price');

        findedEl.attr__usergroups_prices_usergroup = attrPage.querySelectorAll('input[name^="attr_depend_add_usergroups_prices_usergroup"]') || null;
        findedEl.attr__usergroup_price = attrPage.querySelectorAll('input[name^="attr_add_usergroups_prices_product_price"]') || null;
        findedEl.attr__consignment_product_add_discount = attrPage.querySelectorAll('input[name^="attr_consignment_product_add_discount"]') || null;
        findedEl.attr__consignment_product_add_price = attrPage.querySelectorAll('input[name^="attr_consignment_product_add_price"]') || null;
        findedEl.attr__consignment_start_discount = attrPage.querySelectorAll('input[name^="attr_consignment_start_discount"]') || null;

        Object.keys(findedEl).forEach(function(inputName) {
            let elements = findedEl[inputName];
            
            if (elements !== null) {
                Object.keys(elements).forEach(function(key) {
                    let domEl = elements[key];
                    let value = domEl.value || null;

                    result[inputName].push(value);
                });
            } else {
                result[inputName].push(null);
            }
        });

        return result;
    }
	
	 addUserGroupPrice() {
        this.number++;
        let templateElement = document.querySelector('#attr_div_hidden_add_new_usergroup_price_temp').content.cloneNode(true);
        var div = document.createElement('div');
        div.appendChild( templateElement );
        var html = div.innerHTML;

        html = html.replaceAll('100500',this.number);
        const template = document.createElement('template');
        template.innerHTML = html;
        let parentBlock = document.querySelector('.attr_usergroup_price');
        let parentElement = parentBlock.querySelector('.attr_div_hidden_add_new_usergroup_price');
        parentElement.appendChild(document.importNode(template.content, true));
		document.querySelector('#attr_add_usergroup_price').value = 1;
    }
	
	removeGroupRow(row){
		document.querySelector('.row_groups_'+row).remove();
		var rows = document.querySelectorAll('.usergroup_price');
		if( rows.length > 0 ){			
			document.querySelector('#attr_add_usergroup_price').value = 1;
		}else{
			document.querySelector('#attr_add_usergroup_price').value = 0;
		}
	}
		
	separatePost(form){
		form.insertAdjacentHTML('beforeend', '<input type="hidden" name="ajax" value="1" />');		
		let values = FormDataJson.toJson(form);
		let data = {};
		form.disabled = true;
		var loadingDiv = document.getElementById('spinner_loading_block');
		loadingDiv.style.visibility = 'visible';

  
		data = {data : values };
		 let errors = {
                error: []
            };
		fetch('/administrator/index.php?option=com_jshopping&controller=products&task=save', {
			method: 'POST',
			body: JSON.stringify(data),
			headers: {
				'Content-type': 'appliation/json; charset=utf-8'
			}			
		})
			.then(response => response.json())

			.then(data_response => {
				var product_id = data_response.product_id ? data_response.product_id : 0;
				var error = data_response.error ? data_response.error : 0;
				var msg = data_response.msg ? data_response.msg : '';
				var product_attr_id = data_response.product_attr_id ? data_response.product_attr_id : 0;
				
				window.location.href = 'index.php?option=com_jshopping&controller=products&task=ajaxRedirectProductPage&product_id='+product_id+'&error='+error+'&msg='+msg+'&product_attr_id='+product_attr_id;
			}); 
	}
}

export default new ShopProductAttribute();
