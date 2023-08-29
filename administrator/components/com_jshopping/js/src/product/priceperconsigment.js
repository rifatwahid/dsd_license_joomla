import shopHelper from '../helper/index.js';

class ShopPricePerConsigment {

    updateAllPrices() {
        var consignmentsRows = document.querySelectorAll('#tr_add_price #table_add_price [data-consignment-id]');

        if (consignmentsRows) {
            consignmentsRows.forEach(function (item) {
                let consignmentId = item.dataset.consignmentId;
                let discountInputEl = item.querySelector(`#product_add_discount_${consignmentId}`);
    
                if ( discountInputEl.value * 1 != 0 ) {
                    shopPricePerConsigment.updateDiscount(consignmentId);
                } else {
                    shopPricePerConsigment.updatePrice(consignmentId);
                }
            });
        }
    }

    updatePrice(rowNumber, prefix = '') {
		if(event.keyCode != 9){
			let discountElId = `${prefix}product_add_discount_${rowNumber}`;
			let priceOfConsigmentElId = `${prefix}product_add_price_${rowNumber}`;
			let startDiscountElId = `${prefix}start_discount_${rowNumber}`;

			this.calcPrice(discountElId, priceOfConsigmentElId, startDiscountElId);
		}
    }

    updateDiscount(rowNumber, prefix = '') {
		if(event.keyCode != 9 && event.keyCode != 16){
			let discountElId = `${prefix}product_add_discount_${rowNumber}`;
			let priceOfConsigmentElId = `${prefix}product_add_price_${rowNumber}`;
			let startDiscountElId = `${prefix}start_discount_${rowNumber}`;

			this.calcDiscount(discountElId, priceOfConsigmentElId, startDiscountElId);
		}
    }
    
    updateUserGroupPriceList(userGroupId, rowNumber) {
		if(event.keyCode != 9){
			let rowSuffixAddress = `${userGroupId}_${rowNumber}`;
			let discountElId = `add_usergroups_prices_product_add_discount_list_${rowSuffixAddress}`;
			let priceOfConsigmentElId = `add_usergroups_prices_product_add_price_list_${rowSuffixAddress}`;
			let startDiscountElId = `add_usergroups_prices_start_discount_list_${rowSuffixAddress}`;
			let priceEl = `add_usergroups_prices_product_price_list_${userGroupId}`;

			this.calcPrice(discountElId, priceOfConsigmentElId, startDiscountElId, priceEl);
		}
    }

    updateUserGroupDiscountList(userGroupId, rowNumber) {
		if(event.keyCode != 9 && event.keyCode != 16){       
		    let rowSuffixAddress = `${userGroupId}_${rowNumber}`;
			let discountElId = `add_usergroups_prices_product_add_discount_list_${rowSuffixAddress}`;
			let priceOfConsigmentElId = `add_usergroups_prices_product_add_price_list_${rowSuffixAddress}`;
			let startDiscountElId = `add_usergroups_prices_start_discount_list_${rowSuffixAddress}`;

			this.calcDiscount(discountElId, priceOfConsigmentElId, startDiscountElId);
		}
    }

    updateUserGroupPriceSingle(rowNumber, prefix = '') {
        let discountElId = `${prefix}add_usergroups_prices_product_add_discount_${rowNumber}`;
        let priceOfConsigmentElId = `${prefix}add_usergroups_prices_product_add_price_${rowNumber}`;
        let startDiscountElId = `${prefix}add_usergroups_prices_start_discount_${rowNumber}`;

        this.calcPrice(discountElId, priceOfConsigmentElId, startDiscountElId);
    }

    updateUserGroupDiscountSingle(rowNumber, prefix = '') {
        let discountElId = `${prefix}add_usergroups_prices_product_add_discount_${rowNumber}`;
        let priceOfConsigmentElId = `${prefix}add_usergroups_prices_product_add_price_${rowNumber}`;
        let startDiscountElId = `${prefix}add_usergroups_prices_start_discount_${rowNumber}`;

        this.calcDiscount(discountElId, priceOfConsigmentElId, startDiscountElId);
    }

    calcDiscount(discountElId, priceElId, startDiscountElId) {
        var discountPriceConsigment = shopHelper.getElement(discountElId).value;
        var priceOfConsigmentElement = shopHelper.getElement(priceElId);

        if (discountPriceConsigment == '') {
            return 0;
        }

        var startDiscountElement = shopHelper.getElement(startDiscountElId);

        startDiscountElement.value = discountPriceConsigment;
        priceOfConsigmentElement.value = 0;
    }

    calcPrice(discountElId, priceOfConsigmentElId, startDiscountElId, productPriceId = 'product_price') {
        var productPrice = shopHelper.getElement(productPriceId).value;
        var priceOfConsigment = shopHelper.getElement(priceOfConsigmentElId).value;

        if (productPrice == '' || priceOfConsigment == '') {
            return 0;
        }

        var startDiscountElement = shopHelper.getElement(startDiscountElId);
        var discountConsigmentElement = shopHelper.getElement(discountElId);

        var discount = 100 - (priceOfConsigment / productPrice * 100);

        startDiscountElement.value = discount;
        discountConsigmentElement.value = 0;
    }
}

export default new ShopPricePerConsigment();