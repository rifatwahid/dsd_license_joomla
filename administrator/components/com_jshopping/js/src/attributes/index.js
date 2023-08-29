import shopHelper from '../helper/index.js';

class ShopAttributes {

    constructor() {
        document.addEventListener('DOMContentLoaded', function () {
            if (shopHelper.isExistsExcludeAttributes()) {
                let attrsIdsEl = document.querySelector('#eafa_attr_ids');

                if (attrsIdsEl) {
                    attrsIdsEl.addEventListener('change', function (e) {
                        let value = [this.value];
                        let attrsVals = document.querySelectorAll('#eafa_tr .attr_values');

                        if (attrsVals) {
                            attrsVals.forEach(function (attrVal) {
                                let id = attrVal.dataset.id;
                                
                                if (id) {
                                    let show = value.includes(id) ? true: false;
                                    (show) ? attrVal.classList.remove('hide') : attrVal.classList.add('hide');
                                }
                            });
                        }
                    });
                }
            }
        });
    }
}

export default new ShopAttributes();