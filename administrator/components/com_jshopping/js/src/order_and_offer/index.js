import shopHelper from '../helper/index.js';

class ShopOrderAndOffer {

    constructor() {
        this.ajax = null;
    }

    verifyStatus(orderStatus, orderId, message, extended, limit) {
        if (extended == 0) {
            let statusNewId = shopHelper.getValue('select_status_id' + orderId);

            if (statusNewId == orderStatus) {
                alert(message);
                return;
            } else {
                let isChecked = (shopHelper.getElement('order_check_id_' + orderId).checked) ? ('&notify=1') : ('');
                location.href = `index.php?option=com_jshopping&controller=orders&task=update_status&js_nolang=1&order_id=${orderId}&order_status=` + statusNewId + limit + isChecked;
            }
        } else {
            let statusNewId = shopHelper.getValue('order_status');

            if (statusNewId == orderStatus) {
                alert(message);
                return;
            } else {
                let isChecked = (shopHelper.getElement('notify').checked) ? ('&notify=1') : ('&notify=0');
                let includeComment = (shopHelper.getElement('include').checked) ? ('&include=1') : ('&include=0');

                location.href = `index.php?option=com_jshopping&controller=orders&task=update_one_status&js_nolang=1&order_id=${orderId}&order_status=` + statusNewId + isChecked + includeComment + '&comments=' + encodeURIComponent(shopHelper.getValue('comments'));
            }
        }
    }

    updateOrderSubtotal() {
        let result = 0;
        let regExp = /product_item_price\[(\d+)\]/i;
        let productItemPrices = document.querySelectorAll('input[name^=product_item_price]');

        if (productItemPrices) {
            productItemPrices.forEach(function (item) {
                //let one_time_price = 0;
                let myArray = regExp.exec(item.getAttribute('name'));
                let value = myArray[1];
                let price = parseFloat(item.value.replace(',', '.'));    
    
                let prodQtyEl = document.querySelector(`input[name="product_quantity[${value}]"]`);
                let quantity = parseFloat(prodQtyEl.value.replace(',', '.'));
    
                let oneTimePriceEl = document.querySelector(`input[name="product_one_time_price[${value}]"]`);

                if (oneTimePriceEl) {
                    var one_time_price = parseFloat(oneTimePriceEl.value.replace(',', '.'));
                }else{
					var one_time_price = 0;
				}
    
                if (isNaN(price)) price = 0;
                if (isNaN(quantity)) quantity = 0;
    
                result += price * quantity + one_time_price;
            });
        }

        let orderSubtotalEl = document.querySelector('input[name=order_subtotal]');
        if (orderSubtotalEl) {
            orderSubtotalEl.value = result;
        }

        this.updateOrderTotal();
        this.calculateTax();
    }

    updateOrderTotal() {
        let result = 0;
        let subtotal = parseFloat(document.querySelector('input[name=order_subtotal]').value.replace(',', '.'));
        let discount = parseFloat(document.querySelector('input[name=order_discount]').value.replace(',', '.'));
        let shipping = parseFloat(document.querySelector('input[name=order_shipping]').value.replace(',', '.'));
        let opackage = parseFloat(document.querySelector('input[name=order_package]').value.replace(',', '.'));
        let payment = parseFloat(document.querySelector('input[name=order_payment]').value.replace(',', '.'));

        if (isNaN(subtotal)) subtotal = 0;
        if (isNaN(discount)) discount = 0;
        if (isNaN(shipping)) shipping = 0;
        if (isNaN(opackage)) opackage = 0;
        if (isNaN(payment)) payment = 0;

        result = subtotal - discount + shipping + opackage + payment;
        let selectedDisplayOption = document.querySelector('#display_price');

        if (selectedDisplayOption && selectedDisplayOption.value == 1) {
            let taxValueEl = document.querySelectorAll('input[name^=tax_value]');

            if (taxValueEl) {
                taxValueEl.forEach(function (item) {
                    let tax_value = parseFloat(item.value.replace(',', '.'));
                    if (isNaN(tax_value)) tax_value = 0;
                    result += tax_value;
                });
            }
        }

        document.querySelector('input[name=order_total]').value = result;
    }

    calculateTax() {
        var $this = window.shopOrderAndOffer;
        let user_id = document.querySelector('#user_id');
        let product = $this.getList();
        let data_order = $this.getData();
        let url = 'index.php?option=com_jshopping&controller=offer_and_order&task=loadtaxorder';
        let data = {
            data_order: JSON.stringify(data_order),
            product: JSON.stringify(product),
        };
        if (user_id && user_id.value) url += `&admin_load_user_id=${user_id.value}`;

        fetch(url, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
				},
				body: shopHelper.dataTransform(data),
				cache: 'no-cache',
			})
        .then(response => response.json())
        .then(json => {
            let taxPercentEl = document.querySelectorAll('input[name="tax_percent[]"]');

            if (taxPercentEl.length > 0) {
				taxPercentEl.forEach(function(el){	
					el.parentElement.parentElement.remove();
				});
            }

            for (let i = 0; i < json.length; i++) {
                let html = `
                    <tr class='bold'>
                        <td class="right">
                           ` + Joomla.JText._('COM_SMARTSHOP_PLUS_TAX') + ` <input type="text" class="small3 form-control" name="tax_percent[]" value="${json[i]['tax']}"/> %
                        </td>
                        <td class="left">
                            <input type="text" class="small3 form-control" name="tax_value[]" onkeyup="shopOrderAndOffer.updateOrderTotal();" value="${json[i]['value']}"/> € 
                        </td>
                    </tr>
                `;
				document.querySelector('#row_button_add_tax').insertAdjacentHTML('beforebegin', html);
            }

            $this.updateOrderTotal();
        });
    }

    getData() {
        let data = {};

        document.querySelectorAll('.jshop_address input, .jshop_address select').forEach(function (item) {
            let name = item.getAttribute('name');
            data[name] = item.value;
        });

        let orderDeliveryTimeIdEl = document.querySelector('select[name="order_delivery_times_id"]');
        let shippingMethodIdEl = document.querySelector('select[name="shipping_method_id"]');
        let paymentMethodId = document.querySelector('select[name="payment_method_id"]');
        let orderShippingEl = document.querySelector('input[name="order_shipping"]');
        let orderDiscountEl = document.querySelector('input[name="order_discount"]');
        let displayPriceEl = document.querySelector('select[name="display_price"]');
        let orderPaymentEl = document.querySelector('input[name="order_payment"]');
        let orderPackageEl = document.querySelector('input[name="order_package"]');
        let currencyIdEl = document.querySelector('input[name="currency_id"]');
        let couponCodeEl = document.querySelector('input[name="coupon_code"]');
        let userIdEl = document.querySelector('#user_id');
        let langEl = document.querySelector('select[name="lang"]');

        if (orderDeliveryTimeIdEl) {
            data['order_delivery_times_id'] = orderDeliveryTimeIdEl.value;
        }

        if (shippingMethodIdEl) {
            data['shipping_method_id'] = shippingMethodIdEl.value;
        }

        if (paymentMethodId) {
            data['payment_method_id'] = paymentMethodId.value;
        }

        if (orderShippingEl) {
            data['order_shipping'] = orderShippingEl.value.replace(',', '.');
        }
        
        if (orderDiscountEl) {
            data['order_discount'] = orderDiscountEl.value.replace(',', '.');
        }

        if (displayPriceEl) {
            data['display_price'] = displayPriceEl.value.replace(',', '.');
        }
        
        if (orderPaymentEl) {
            data['order_payment'] = orderPaymentEl.value.replace(',', '.');
        }

        if (orderPackageEl) {
            data['order_package'] = orderPackageEl.value.replace(',', '.');
        }
        
        if (currencyIdEl) {
            data['currency_id'] = currencyIdEl.value;
        }
        
        if (couponCodeEl) {
            data['coupon_code'] = couponCodeEl.value;
        }
        
        if (userIdEl) {
            data['user_id'] = userIdEl.value;
        }
        
        if (langEl) {
            data['lang'] = langEl.value;
        }
        
        return data;
    }

    getList() {
        let max = end_number_order_item + 1;
        let product = {};

        for (let a = 1; a <= max; a++) {
            let productEl = document.querySelector(`input[name="product_id[${a}]"]`);
            if (!productEl) continue;
            let product_id = productEl.value;
            if (!product_id) continue;
            let detal_product = {
                product_id
            };

            let productFreeAttrEl = document.querySelector(`input[name="product_freeattributes[${a}]"]`);
            let productItemPriceEl = document.querySelector(`input[name="product_item_price[${a}]"]`);
            let oneTimeCostEl = document.querySelector(`input[name="product_one_time_price[${a}]"]`);
            let productAttrsEl = document.querySelector(`input[name="product_attributes[${a}]"]`);
            let deliveryTimeIdEl = document.querySelector(`input[name="delivery_times_id[${a}]"]`);
            let productQtyEl = document.querySelector(`input[name="product_quantity[${a}]"]`);
            let orderItemIdEl = document.querySelector(`input[name="order_item_id[${a}]"]`);
            let productNameEl =  document.querySelector(`input[name="product_name[${a}]"]`);
            let productEanEl = document.querySelector(`input[name="product_ean[${a}]"]`);
            let thumbImageEl = document.querySelector(`input[name="thumb_image[${a}]"]`);
            let vendorIdEl = document.querySelector(`input[name="vendor_id[${a}]"]`);
            let weightEl = document.querySelector(`input[name="weight[${a}]"]`);
            let product_tax = document.querySelector(`input[name="product_tax[${a}]"]`);

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

            if (product_tax) {
                detal_product['product_tax'] = product_tax.value;
            }
			
            if (weightEl) {
                detal_product['weight'] = weightEl.value;
            }

            product[a] = detal_product;
        }

        return product;
    }

    addItemRow() {
        end_number_order_item++;

        let i = end_number_order_item;
        let url = 'index.php?option=com_jshopping&controller=offer_and_order&task=addItemRow&id=' + i;
		
		if (this.ajax) this.ajax.abort();

        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.text())
        .then(data => {
            let el = document.querySelector('#list_order_items tbody');
            if (el) {
                el.innerHTML += data;
            }
        });        
    }

    addTaxRow() {
        let html = `
            <tr>
                <td class="right">
                    <input type="text" class="small3 form-control" name="tax_percent[]"/> %
                </td>
                <td class="left">
                    <input type="text" class="small3 form-control" name="tax_value[]" onkeyup="shopOrderAndOffer.updateOrderTotal();"/>
                </td>
            </tr>
        `;

        let rowBtnAddTax = document.querySelector('#row_button_add_tax');
        if (rowBtnAddTax) {
            rowBtnAddTax.insertAdjacentHTML('beforebegin', html);
        }
    }

    loadProductInfo(pid, num, currency_id) {
        let url = `index.php?option=com_jshopping&controller=products&task=loadproductinfo&product_id=${pid}&currency_id=${currency_id}&ajax=1`;

        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(reponse => reponse.json())
        .then(json => {
            let productItemPriceEl = document.querySelector(`input[name="product_item_price[${num}]"]`);
            let deliveryTimeIdEl = document.querySelector(`input[name="delivery_times_id[${num}]"]`);
            let productQtyEl = document.querySelector(`input[name="product_quantity[${num}]"]`);
            let productNameEl = document.querySelector(`input[name="product_name[${num}]"]`);
            let productTaxEl = document.querySelector(`input[name="product_tax[${num}]"]`);
            let productEatEl = document.querySelector(`input[name="product_ean[${num}]"]`);
            let thumbImageEl = document.querySelector(`input[name="thumb_image[${num}]"]`);
            let productIdEl = document.querySelector(`input[name="product_id[${num}]"]`);
            let vendorIdEl = document.querySelector(`input[name="vendor_id[${num}]"]`);
            let weightEl = document.querySelector(`input[name="weight[${num}]"]`);
            let oneTimePrice = document.querySelector(`input[name="product_one_time_price[${num}]"]`);


            if (productItemPriceEl) {
                productItemPriceEl.value = json.product_price;
            }

            if (deliveryTimeIdEl) {
                deliveryTimeIdEl.value = json.delivery_times_id;
            }

            if (productQtyEl) {
                productQtyEl.value = '1.00';
            }
            
            if (productNameEl) {
                productNameEl.value = json.product_name;
            }
            
            if (productTaxEl) {
                productTaxEl.value = json.product_tax;
            }

            if (productEatEl) {
                productEatEl.value = json.product_ean;
            }

            if (thumbImageEl) {
                thumbImageEl.value = json.thumb_image;
            }
            
            if (productIdEl) {
                productIdEl.value = json.product_id;
            }

            if (vendorIdEl) {
                vendorIdEl.value = json.vendor_id;
            }

            if (weightEl) {
                weightEl.value = json.product_weight;
            }

            if (oneTimePrice) {
                oneTimePrice.value = json.one_time_cost;
            }
            
            this.updateOrderSubtotal();
        });
    }

    updateShippingForUser(user_id) {
        if (user_id) {
            let data = {
                user_id
            };

            if (this.ajax) this.ajax.abort();

            this.ajax = fetch(userinfo_link, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(json => {
                this._setShipping(json)
            });
        } else {
            this._setShipping(userinfo_fields);
        }
    }

    _setShipping(user) {
        for (let field in user) {
            document.querySelector(`.jshop_address [name="${field}"]`).value = user[field];
        }
    }

}

export default new ShopOrderAndOffer();