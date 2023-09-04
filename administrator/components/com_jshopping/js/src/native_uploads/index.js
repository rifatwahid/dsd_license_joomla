import shopHelper  from '../helper/index.js';

class AdminShopNativeUploads {

    constructor() {
        this.countOfPricesRows = 1;
        this._updateVariableCountOfPricesRows();
    }

    deletePriceRow(rowNumber, prefix = '') {
        let selector = `.${prefix}nativeProgressUploadsAddPrices__row[data-row-id="${rowNumber}"]`;
        return shopHelper.delete(selector);
    }

    getCountOfPricesRows() {
        return document.querySelectorAll('#nativeProgressUploadsAddPrices .nativeProgressUploadsAddPrices__row').length;
    }

    addPriceRow(selector = '#nativeProgressUploadsAddPrices .nativeProgressUploadsAddPrices__body', prefix = '') {
        this.countOfPricesRows++;
        let el = document.querySelector(selector);

        if (el) {
            let row = `
            <tr class="${prefix}nativeProgressUploadsAddPrices__row" data-row-id="${this.countOfPricesRows}">
                <td class="${prefix}nativeProgressUploadsAddPrices__row-from">
                    <input type="text" name="${prefix}nativeProgressUploads[prices][updates][${this.countOfPricesRows}][from_item]" value="0" class="small3 form-control w-50 ${prefix}nativeProgressUploadsAddPrices__row-item ${prefix}nativeProgressUploadsAddPrices__from-el">
                </td>
                <td class="${prefix}nativeProgressUploadsAddPrices__row-to">
                    <input type="text" name="${prefix}nativeProgressUploads[prices][updates][${this.countOfPricesRows}][to_item]" value="0" class="small3 form-control w-50 ${prefix}nativeProgressUploadsAddPrices__row-item ${prefix}nativeProgressUploadsAddPrices__to-el">
                </td>
                <td class="${prefix}nativeProgressUploadsAddPrices__row-percent">
                    <input type="text" name="${prefix}nativeProgressUploads[prices][updates][${this.countOfPricesRows}][percent]" value="0" class="small3 form-control w-50 ${prefix}nativeProgressUploadsAddPrices__row-item ${prefix}nativeProgressUploadsAddPrices__percent-el" onchange="AdminShopNativeUploads.clearPriceOrPercent(${this.countOfPricesRows}, true, '${prefix}'); AdminShopNativeUploads.updateCalculatedPrice(${this.countOfPricesRows}, this.value, '${prefix}');" value="0">
                </td>
                <td class="${prefix}nativeProgressUploadsAddPrices__row-price">
                    <input type="text" name="${prefix}nativeProgressUploads[prices][updates][${this.countOfPricesRows}][price]" value="0" class="small3 form-control w-50 ${prefix}nativeProgressUploadsAddPrices__row-item ${prefix}nativeProgressUploadsAddPrices__price-el"  onchange="AdminShopNativeUploads.clearPriceOrPercent(${this.countOfPricesRows}, false, '${prefix}'); AdminShopNativeUploads.updateCalculatedPrice(${this.countOfPricesRows}, this.value, '${prefix}');" value="0">
                </td>
                <td class="${prefix}nativeProgressUploadsAddPrices__row-calculated-price display--none">
                    <input type="text" name="${prefix}nativeProgressUploads[prices][updates][${this.countOfPricesRows}][calculated_price]" value="0" class="small3 form-control w-50 ${prefix}nativeProgressUploadsAddPrices__row-item ${prefix}nativeProgressUploadsAddPrices__calculated-price-el" value="0">
                </td>
                <td class="${prefix}nativeProgressUploadsAddPrices__row-delete">
                    <a href="#" class="btn btn-micro" onclick="AdminShopNativeUploads.deletePriceRow(${this.countOfPricesRows}, '${prefix}'); return false;">
                        <i class="icon-delete"></i>
                    </a>
                </td>
            </tr>`;

            el.insertAdjacentHTML('beforeend', row);
        }
    }
    
    getRowElementById(idOfPriceRow, prefix = '') {
        var selector = `.${prefix}nativeProgressUploadsAddPrices__row[data-row-id="${idOfPriceRow}"]`;
        var priceRowElem = document.querySelector(selector);

        if (priceRowElem) {
            return priceRowElem;
        }
    }

    clearPriceOrPercent(idOfPriceRow, isClearPrice = true, prefix = '') {
        var priceRowElem = this.getRowElementById(idOfPriceRow, prefix);
        var selectorOfElToClear = `.${prefix}nativeProgressUploadsAddPrices__price-el`;

        if (!isClearPrice) {
            selectorOfElToClear = `.${prefix}nativeProgressUploadsAddPrices__percent-el`;
        }

        if (priceRowElem) {            
            var calcPriceEl = priceRowElem.querySelector(selectorOfElToClear);

            if (calcPriceEl) {
                calcPriceEl.value = 0;
            }
        }
    }
    
    updateCalculatedPrice(idOfPriceRow, value, prefix = '') {
        var priceRowElem = this.getRowElementById(idOfPriceRow, prefix);

        if (priceRowElem && value) {
            var calcPriceElem = priceRowElem.querySelector(`.${prefix}nativeProgressUploadsAddPrices__calculated-price-el`);
            calcPriceElem.value = value;
        }

        return false;
    }

    _updateVariableCountOfPricesRows() {
        var countOfPricesRows = this.getCountOfPricesRows();

        if (countOfPricesRows) {
            this.countOfPricesRows = countOfPricesRows;
            return true;
        }

        return false;
    }
}

export default new AdminShopNativeUploads();