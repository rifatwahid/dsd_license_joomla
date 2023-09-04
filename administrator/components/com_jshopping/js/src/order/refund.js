class ShopRefund {

    constructor() {
        this.number = 100;
    }

    /**
     * uploadId - #__jshopping_order_items_native_uploads_files
     */
    updateRefundSubtotal(index) {
        let result = 0;
		
        let regExp = /refund[\index/][product_item_price]\[(\d+)\]/i;
        let productItemPrices = document.querySelectorAll(`input[name^='refund[${index}][product_item_price]']`);

		regExp = new RegExp(regExp);
        if (productItemPrices) {
            productItemPrices.forEach(function (item) {
                let one_time_price = 0;
                let myArray = regExp.exec(item.getAttribute('name'));
				 
                let value = item.name.substring(item.name.lastIndexOf('[') + 1, item.name.length - 1);
                
		        let price = parseFloat(item.value.replace(',', '.'));    
    
                let prodQtyEl = document.querySelector(`input[name="refund[${index}][product_quantity][${value}]"]`);
                let quantity = parseFloat(prodQtyEl.value.replace(',', '.'));
                let oneTimePriceEl = document.querySelector(`input[name="refund[${index}][product_one_time_price][${value}]"]`);

                if (oneTimePriceEl) {
                    one_time_price = parseFloat(oneTimePriceEl.value.replace(',', '.'));
                }
    
                if (isNaN(price)) price = 0;
                if (isNaN(quantity)) quantity = 0;
    
                result += price * quantity + one_time_price;
			 });
        }

        let orderSubtotalEl = document.querySelector(`input[name='refund[${index}][subtotal]']`);
        if (orderSubtotalEl) {
            orderSubtotalEl.value = result;
        }

        this.updateRefundTotal(index);
        this.calculateTax(index);
    }
	
	updateRefundTotal(index) {
		console.log('index');
        let result = 0;
        let subtotal = parseFloat(document.querySelector(`input[name='refund[${index}][subtotal]']`).value.replace(',', '.'));
        let discount = parseFloat(document.querySelector(`input[name='refund[${index}][discount]']`).value.replace(',', '.'));
        let shipping = parseFloat(document.querySelector(`input[name='refund[${index}][shipping]']`).value.replace(',', '.'));
        let opackage = parseFloat(document.querySelector(`input[name='refund[${index}][package]']`).value.replace(',', '.'));
        let payment = parseFloat(document.querySelector(`input[name='refund[${index}][payment]']`).value.replace(',', '.'));

        if (isNaN(subtotal)) subtotal = 0;
        if (isNaN(discount)) discount = 0;
        if (isNaN(shipping)) shipping = 0;
        if (isNaN(opackage)) opackage = 0;
        if (isNaN(payment)) payment = 0;

        result = subtotal - discount + shipping + opackage + payment;

        let selectedDisplayOption = document.querySelector('#display_price');

        if (selectedDisplayOption && selectedDisplayOption.value == 1) {
            let taxValueEl = document.querySelectorAll(`input[name^='refund[${index}][tax_value]']`);

            if (taxValueEl) {
                taxValueEl.forEach(function (item) {
                    let tax_value = parseFloat(item.value.replace(',', '.'));
                    if (isNaN(tax_value)) tax_value = 0;
                    result += tax_value;
                });
            }
        }

        document.querySelector(`input[name='refund[${index}][total]']`).value = result;
    }

    calculateTax(index) {
        var $this = window.shopOrderAndOffer;
        let user_id = document.querySelector('#user_id');
        let product = $this.getList(index);
        let data_order = $this.getData(index);
        let url = 'index.php?option=com_jshopping&controller=orders&task=loadtaxorder';
        data_order['product'] = product;
        let data = {
            data_order
        };
        JSON.stringify(data)

        if (user_id && user_id.value) url += `&admin_load_user_id=${user_id.value}`;

        fetch(url, {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(json => {
            let taxPercentEl = document.querySelector(`input[name="refund[${index}][tax_percent][]"]`);

            if (taxPercentEl) {
                taxPercentEl.parentElement.parentElement.remove();
            }

            for (let i = 0; i < json.length; i++) {
                let html = `
                    <tr class='bold'>
                        <td class="right">
                            <input type="text" class="small3" name="refund[${index}][tax_percent][]" value="${json[i]['tax']}"/> %
                        </td>
                        <td class="left">
                            <input type="text" class="small3" name="refund[${index}][tax_value][]" onkeyup="shopRefund.updateRefundTotal(index);" value="${json[i]['value']}"/>
                        </td>
                    </tr>
                `;

                document.querySelector('#row_button_add_tax').innerHTML += html;
            }

            shopRefund.updateRefundTotal(index);
        });
    }
	

    getList(index) {
        let max = end_number_order_item + 1;
        let product = {};

        for (let a = 1; a <= max; a++) {
            let productEl = document.querySelector(`input[name="refund[${index}][product_id][${a}]"]`);
            if (!productEl) continue;
            let product_id = productEl.value;
            if (!product_id) continue;
            let detal_product = {
                product_id
            };

            let productFreeAttrEl = document.querySelector(`input[name="refund[${index}][product_freeattributes][${a}]"]`);
            let productItemPriceEl = document.querySelector(`input[name="refund[${index}][product_item_price][${a}]"]`);
            let oneTimeCostEl = document.querySelector(`input[name="refund[${index}][product_one_time_price][${a}]"]`);
            let productAttrsEl = document.querySelector(`input[name="refund[${index}][product_attributes][${a}]"]`);
            let deliveryTimeIdEl = document.querySelector(`input[name="refund[${index}][delivery_times_id][${a}]"]`);
            let productQtyEl = document.querySelector(`input[name="refund[${index}][product_quantity][${a}]"]`);
            let orderItemIdEl = document.querySelector(`input[name="refund[${index}][order_item_id][${a}]"]`);
            let productNameEl =  document.querySelector(`input[name="refund[${index}][product_name][${a}]"]`);
            let productEanEl = document.querySelector(`input[name="refund[${index}][product_ean][${a}]"]`);
            let thumbImageEl = document.querySelector(`input[name="refund[${index}][thumb_image][${a}]"]`);
            let vendorIdEl = document.querySelector(`input[name="refund[${index}][vendor_id][${a}]"]`);
            let weightEl = document.querySelector(`input[name="refund[${index}][weight][${a}]"]`);

            if (productFreeAttrEl) {
                detal_product['product_freeattributes'] = productFreeAttrEl.value;
            }

            if (productItemPriceEl) {
                detal_product['product_item_price'] = productItemPriceEl.value.replace(',', '.');
            }

            if (oneTimeCostEl) {
                detal_product['one_time_cost'] = oneTimeCostEl.value.replace(',', '.');
            }

            if (productAttrsEl) {
                detal_product['product_attributes'] = productAttrsEl.value;
            }

            if (deliveryTimeIdEl) {
                detal_product['delivery_times_id'] = deliveryTimeIdEl.value;
            }

            if (productQtyEl) {
                detal_product['product_quantity'] = productQtyEl.value.replace(',', '.');
            }

            if (orderItemIdEl) {
                detal_product['order_item_id'] = orderItemIdEl.value;
            }

            if (productNameEl) {
                detal_product['product_name'] = productNameEl.value;
            }

            if (productEanEl) {
                detal_product['product_ean'] = productEanEl.value;
            }

            if (thumbImageEl) {
                detal_product['thumb_image'] = thumbImageEl.value;
            }

            if (vendorIdEl) {
                detal_product['vendor_id'] = vendorIdEl.value;
            }

            if (weightEl) {
                detal_product['weight'] = weightEl.value;
            }

            product[a] = detal_product;
        }

        return product;
    }
	 
    addTaxRow(index) {
        let html = '<tr><td class="right"><input type="text" class="small3 form-control" name="refund['+index+'][tax_percent][]"/> %</td><td class="left"><input type="text" class="small3 form-control" name="refund['+index+'][tax_value][]" onkeyup="shopRefund.updateRefundTotal('+index+');"/></td></tr>';

        let rowBtnAddTax = document.querySelector('#refund_row_button_add_tax_'+index);
        if (rowBtnAddTax) {
            rowBtnAddTax.insertAdjacentHTML('beforebegin', html);
        }
    }
	
	start_refund(){
		refund_index++;
		var template = document.querySelector('#template_refund_block');
		var templateEl = template.content.cloneNode(true);
		var new_package = templateEl.querySelector('#refund_block');
		var html = new_package.innerHTML.replaceAll("refund[0]", "refund["+refund_index+"]");
		html = html.replaceAll("index", refund_index);
		new_package.innerHTML = html;
		 document.querySelector('#refunds_block').appendChild(new_package);
		 var new_index = refund_index + 1;
		 document.querySelector('.refund_butt').value = 'Start refund  ' + new_index; 
	}
}

export default new ShopRefund();