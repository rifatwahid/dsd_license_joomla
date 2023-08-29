import shopHelper from '../helper/index.js';

class ShopProductPrice {

    constructor() {
        this.number = 0;
    }

    update(display_price, second) {
		if(event.keyCode != 9){       
		    let percent = shopHelper.getElement('product_tax_id')[shopHelper.getElement('product_tax_id').selectedIndex].text;
			let pattern = /(\d*\.?\d*)%\)$/;
			let price, block;

			pattern.test(percent);
			percent = RegExp.$1;

			if (second) {
				price = shopHelper.getValue('product_price');
				block = shopHelper.getElement('product_price2');
			} else {
				price = shopHelper.getValue('product_price2');
				block = shopHelper.getElement('product_price');
			}

			if (display_price == 0) {
				if (second) {
					block.value = shopHelper.round(price / (1 + percent / 100), product_price_precision);
				} else {
					block.value = shopHelper.round(price * (1 + percent / 100), product_price_precision);
				}
			} else {
				if (second) {
					block.value = shopHelper.round(price * (1 + percent / 100), product_price_precision);
				} else {
					block.value = shopHelper.round(price / (1 + percent / 100), product_price_precision);
				}
			}

			this.reload();
		}
	}
	
	updateByTax(display_price, second) {
		if(event.keyCode != 9){       
		    let percent = shopHelper.getElement('product_tax_id')[shopHelper.getElement('product_tax_id').selectedIndex].text;
			let pattern = /(\d*\.?\d*)%\)$/;
			let price, block, priceEl, blockPr;

			pattern.test(percent);
			percent = RegExp.$1;

			if (second) {
				price = shopHelper.getElement('product_price');
				block = shopHelper.getValue('product_price2');
			} else {
				price = shopHelper.getElement('product_price2');
				block = shopHelper.getValue('product_price');
			}

			if (display_price == 0) {
				if (second) {
					price.value = shopHelper.round(block * (1 + percent / 100), product_price_precision);
				} else {
					price.value = shopHelper.round(block / (1 + percent / 100), product_price_precision);
				}
				
			} else {
				if (second) {
					price.value = shopHelper.round(block / (1 + percent / 100), product_price_precision);
				} else {
					price.value = shopHelper.round(block * (1 + percent / 100), product_price_precision);
				}
			}

			this.reload();
		}
	}

    add(prefix = '') {
		if (consigment_rows_number === undefined) {consigment_rows_number=0;}
        consigment_rows_number++;
        prefix = prefix || '';

        let html = `
            <tr id="${prefix}add_price_${consigment_rows_number}" data-consignment-id="${consigment_rows_number}">
                <td>
                    <input type = "text" class="small3 form-control w-50" name = "${prefix}quantity_start[]" id="${prefix}quantity_start_${consigment_rows_number}" />
                </td>
                <td>
                    <input type = "text" class="small3 form-control w-50" name = "${prefix}quantity_finish[]" id="${prefix}quantity_finish_${consigment_rows_number}" />
                </td>
                <td>
                    <input type = "text" class="small3 form-control w-50" name = "${prefix}product_add_discount[]" id="${prefix}product_add_discount_${consigment_rows_number}" onkeyup="shopPricePerConsigment.updateDiscount(${consigment_rows_number}, '${prefix}')" />
                </td>
                <td>
                    <input type = "text" class="small3 form-control w-50" id="${prefix}product_add_price_${consigment_rows_number}" name="${prefix}product_add_price[]" onkeyup="shopPricePerConsigment.updatePrice(${consigment_rows_number}, '${prefix}')" />
                    <input type = "hidden" id="${prefix}start_discount_${consigment_rows_number}" value = "" name="${prefix}start_discount[]" />
                </td>
                <td align="center">
                    <a href="#" class="btn btn-micro" onclick="shopProductPrice.delete(${consigment_rows_number}, '${prefix}');return false;">
                        <i class="icon-delete"></i>
                    </a>
                </td>
            </tr>
        `;

        document.querySelector(`#${prefix}table_add_price tbody`).insertAdjacentHTML('beforeend', html);
    }

    delete(num, prefix = '') {
        document.querySelector(`#${prefix}add_price_${num}`).remove();
    }

    updateNewPrice(num) {
        let origin = document.querySelector('#product_price').value;
        let discount = document.querySelector(`#product_add_discount_${num}`).value;

        if (origin == '' || price == '') {
            return 0;
        }

        document.querySelector(`#start_discount_${num}`).value = discount;
    }

    reload() {
        var origin = document.querySelector('#product_price');
        let attrPriceEl = document.querySelector('#attr_price');

        if (origin && attrPriceEl) {
            attrPriceEl.value = origin.value;
        }

        if (!origin || origin.value == '') {
            return 0;
        }
    }

    setNumber(number) {
		if (number === undefined) {
			number = this.number;
		}else{
			this.number = number;
		}
    }

}

export default new ShopProductPrice();