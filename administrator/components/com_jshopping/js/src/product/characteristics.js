import shopProductCommon from './common.js';

class ShopProductCharacteristics {

    /**
     * true - switch to multiple
     * false - switch to normal
     */
    switchSelectToMultButDontTouchAlreadyMult(isSwitchToMultiselect, size = 10) {
        let productFieldsEls = document.querySelectorAll('body select[name^="productfields"]');

        if (productFieldsEls) {
            productFieldsEls.forEach(function(el) {
                let elDataset = el.dataset;
                let isMultipleInitially = elDataset.isMultipleInitially;

                if (elDataset.isMultipleInitially === undefined) {
                    elDataset.isMultipleInitially = isMultipleInitially = ((el.multiple) ? 1: 0);
                }

                if (isMultipleInitially != 1) {
                    if (isSwitchToMultiselect) {
                        el.setAttribute('size', size);
                        el.setAttribute('multiple', isSwitchToMultiselect);
                    } else {
                        el.removeAttribute('size');
                        el.removeAttribute('multiple');
                    }
                }
            });
        }
    }
}

export default new ShopProductCharacteristics();