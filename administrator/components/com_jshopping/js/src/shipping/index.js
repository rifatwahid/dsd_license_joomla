class ShopShipping {

    constructor() {
        this.number = 0;
    }

    addPrice() {
        this.number++;

        let html = `
            <tr id="shipping_weight_price_row_${this.number}">
                <td>`
                    + document.querySelector('.hidden #condition').parentNode.innerHTML +
					`
                </td>
                <td>
                    <input type="text" class="inputbox form-control" name="shipping_price[]" />
                </td>
                <td>
                    <input type="text" class="inputbox form-control" name="shipping_package_price[]" />
                </td>
                <td style="text-align:center">
                    <a class="btn btn-micro" href="#" onclick="shopShipping.deletePrice(${this.number});return false;">
                        <i class="icon-delete"></i>
                    </a>
                </td>
            </tr>
        `;
        
        document.querySelector('#table_shipping_weight_price tbody').insertAdjacentHTML('beforeend', html);
    }

    deletePrice(num) {
        document.querySelector(`#shipping_weight_price_row_${num}`).remove();
    }

    setNumber(number) {
        this.number = number;
    }

}

export default new ShopShipping();