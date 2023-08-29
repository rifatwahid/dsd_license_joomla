import shopHelper from '../helper/index.js';
import shopProductPrice from './price.js';
import shopPricePerConsigment from './priceperconsigment.js';

class ShopProductUserGroup {

    constructor() {
        this.number = 100500;
    }

    addRowsPrice(obj){
        let row_id = obj.closest('.new_pr_but');
        let ar_id = row_id.getAttribute('id').split('but_row_');
        this.addPriceList(ar_id['1']);
    }

    addPriceList(usergroup) {
        this.number++;

        let html = `
            <tr id="add_price_${this.number}">
                <td>
                    <input type="text" class="small3 form-control w-50" name="add_usergroups_prices_quantity_start_list[${usergroup}][]" id="add_usergroups_prices_quantity_start_list_${usergroup}_${this.number}" />
                </td>
                <td>
                    <input type="text" class="small3 form-control w-50" name="add_usergroups_prices_quantity_finish_list[${usergroup}][]" id="add_usergroups_prices_quantity_finish_list_${usergroup}_${this.number}" />
                </td>
                <td>
                    <input type="text" class="small3 form-control w-50" name="add_usergroups_prices_product_add_discount_list[${usergroup}][]" id="add_usergroups_prices_product_add_discount_list_${usergroup}_${this.number}" onkeyup="shopPricePerConsigment.updateUserGroupDiscountList(${usergroup}, ${this.number})" />
                </td>
                <td>
                    <input type="text" class="small3 form-control w-50" name="add_usergroups_prices_product_add_price_list[${usergroup}][]" id="add_usergroups_prices_product_add_price_list_${usergroup}_${this.number}" onkeyup="shopPricePerConsigment.updateUserGroupPriceList(${usergroup},${this.number})" />
                    <input type="hidden" name="add_usergroups_prices_start_discount_list[${usergroup}][]" id="add_usergroups_prices_start_discount_list_${usergroup}_${this.number}" />
                </td>
                <td align="center">
                    <a href="#" class="btn btn-micro" onclick="shopProductUserGroup.deletePriceList(${this.number}); return false;">
                        <i class="icon-delete"></i>
                    </a>
                </td>
            </tr>
        `;

        let userGroupPriceEl = document.querySelector(`#add_usergroups_prices_table_add_price_list_${usergroup} tbody`);
        if (userGroupPriceEl) {
            userGroupPriceEl.insertAdjacentHTML('beforeend', html);
        }
    }

    deletePriceList(num, usergroup) {

        if ((num || num == 0) && (usergroup || usergroup == 0)) {
            document.querySelector(`#add_usergroups_prices_add_price_list_${num}_${usergroup}`).remove();
        } else {
            document.querySelector(`#add_price_${num}`).remove();
        }
    }

    updatePriceList(display_price_admin, rowNumber, isNetto, prefix = '') {

        if(event.keyCode != 9 && event.keyCode != 16) {
			this.updatePrice(display_price_admin, !isNetto, rowNumber, prefix);
		}
    }

    deletePrice(num, prefix = '') {
        document.querySelector(`#${prefix}add_usergroups_prices_add_price_${num}`).remove();
    }

    addPrice(appendToSelector = '#add_usergroups_prices_table_add_price tbody', prefix = '') {
        this.number++;

        let html = `
            <tr id="${prefix}add_usergroups_prices_add_price_${this.number}">
                <td>
                    <input type="text" class="small3 form-control w-50" name="${prefix}add_usergroups_prices_quantity_start[]" id="${prefix}add_usergroups_prices_quantity_start_${this.number}" />
                </td>
                <td>
                    <input type="text" class="small3 form-control w-50" name="${prefix}add_usergroups_prices_quantity_finish[]" id="${prefix}add_usergroups_prices_quantity_finish_${this.number}"/>
                </td>
                <td>
                    <input type="text" class="small3 form-control w-50" name="${prefix}add_usergroups_prices_product_add_discount[]" id="${prefix}add_usergroups_prices_product_add_discount_${this.number}" onkeyup="shopPricePerConsigment.updateUserGroupDiscountSingle(${this.number}, '${prefix}')" />
                </td>
                <td>
                    <input type="text" class="small3 form-control w-50" id="${prefix}add_usergroups_prices_product_add_price_${this.number}" name="${prefix}add_usergroups_prices_product_add_price[]" onkeyup="shopPricePerConsigment.updateUserGroupPriceSingle(${this.number}, '${prefix}');"/>
                    <input type="hidden" id="${prefix}add_usergroups_prices_start_discount_${this.number}" name="${prefix}add_usergroups_prices_start_discount[]" />
                </td>
                <td align="center">
                    <a href="#" class="btn btn-micro" onclick="shopProductUserGroup.deletePrice(${this.number}, '${prefix}');return false;">
                        <i class="icon-delete"></i>
                    </a>
                </td>
            </tr>
        `;

        let el = document.querySelector(appendToSelector);
        if (el) {
            el.insertAdjacentHTML('beforeend', html);
        }
    }

    updatePrice(display_price_admin, isBrutto, rowNumber = null, prefix = '') {
        let elem, price;
        let percent = shopHelper.getElement('product_tax_id')[shopHelper.getElement('product_tax_id').selectedIndex].text;
        let pattern = /(\d*\.?\d*)%\)$/

        pattern.test(percent);
        percent = RegExp.$1;

        var suffix = (rowNumber || rowNumber === 0) ? ('_list_' + rowNumber) : '';

        if (isBrutto) {
            elem = shopHelper.getElement(prefix + 'add_usergroups_prices_product_price' + suffix);
            price = shopHelper.getValue(prefix + 'add_usergroups_prices_product_price2' + suffix);
        } else {
            elem = shopHelper.getElement(prefix + 'add_usergroups_prices_product_price2' + suffix);
            price = shopHelper.getValue(prefix + 'add_usergroups_prices_product_price' + suffix);
        }

        var bruttoPriceId = 0;
        if (display_price_admin == bruttoPriceId) {
            if (isBrutto) {
                elem.value = shopHelper.round(price * (1 + percent / 100), product_price_precision);
            } else {
                elem.value = shopHelper.round(price / (1 + percent / 100), product_price_precision);
            }
        } else {
            if (isBrutto) {
                elem.value = shopHelper.round(price / (1 + percent / 100), product_price_precision);
            } else {
                elem.value = shopHelper.round(price * (1 + percent / 100), product_price_precision);
            }
        }

        shopProductPrice.reload();
    }

    updateAllPrices(userGroupId) {
        var consignmentsRows = document.querySelectorAll(`#add_usergroups_prices_table_add_price_list_${userGroupId} [data-group-id='${userGroupId}']`);

        if (consignmentsRows) {
            consignmentsRows.forEach(function (item) {
                var discountInputEl = item.querySelector(`#add_usergroups_prices_product_add_discount_list_${userGroupId}_${item.dataset.rowNumber}`);
                
                if (discountInputEl) {
                    if (discountInputEl.value * 1 != 0) {
                        shopPricePerConsigment.updateUserGroupDiscountList(userGroupId, item.dataset.rowNumber);
                    } else {
                        shopPricePerConsigment.updateUserGroupPriceList(userGroupId, item.dataset.rowNumber);
                    }
                }
            });
        }
    }

    updatePriceValueList(num, usergroup) {
        let origin = document.querySelector(`#add_usergroups_prices_product_price_list_${usergroup}`).value;
        let discount = document.querySelector(`#add_usergroups_prices_product_add_discount_list_${usergroup}_${num}`).value;

        if (origin == '' || discount == '') {
            return 0;
        }

        document.querySelector(`#add_usergroups_prices_start_discount_list${usergroup}_${num}`).value = discount;
    }

    updatePriceValue(num) {
        let origin = document.querySelector(`#add_usergroups_prices_product_price`).value;
        let discount = document.querySelector(`#add_usergroups_prices_product_add_discount_${num}`).value;

        if (origin == '' || discount == '') {
            return 0;
        }

        document.querySelector(`#add_usergroups_prices_start_discount_${num}`).value = discount;
    }

    addUserGroupPrice() {
        this.number++;
        let templateElement = document.querySelector(' #div_hidden_add_new_usergroup_price').content.cloneNode(true);

        var div = document.createElement('div');
        div.appendChild( templateElement );
        var html = div.innerHTML;

        html = html.replaceAll('100500',this.number);

        const template = document.createElement('template');
        template.innerHTML = html;
        let parentBlock = document.querySelector('#main-price');
        let parentElement = parentBlock.querySelector('.usergoup_price_block');
        parentElement.appendChild(document.importNode(template.content, true));
    }


}

export default new ShopProductUserGroup();