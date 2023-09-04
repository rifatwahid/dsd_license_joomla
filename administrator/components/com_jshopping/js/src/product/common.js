class ShopProductCommon {

    constructor() {
        this.triggers = {
            html: '',
            secondHtml: '',
            events: [],
            secondEvents: []
        };
    }

    updateEan() {
        let attrEanEl = document.querySelector('#attr_ean');
        let productEanEl = document.querySelector('#product_ean');

        if (attrEanEl && productEanEl) {
            attrEanEl.value = productEanEl.value;
        }
    }

    reloadExtraField(product_id) {
        let cat_url = "";
        let selectedCatgsEl = document.querySelector('#category_id').selectedOptions;

        if (selectedCatgsEl) {
            for (let item of selectedCatgsEl) {
                if (item.value) {
                    cat_url += "&cat_id[]=" + item.value;
                }
            }
        }

        let url = `index.php?option=com_jshopping&controller=products&task=product_extra_fields&product_id=${product_id + cat_url}&ajax=1`;

        fetch(url, {
            method: 'GET',
        })
        .then(response => response.text())
        .then(data => {
            let extraFieldsSpaceEl = document.querySelector('#extra_fields_space');
            if (extraFieldsSpaceEl) {
                extraFieldsSpaceEl.innerHTML = data;
            }
        });
    }
	
	toggleAttrQuantityAdd(e, row){
        let displayStatus = e ? 'none': 'inherit';
        let itemEl = document.querySelector(`#block_enter_attr_qty_${row}`);

        if (itemEl) {
            itemEl.style.display = displayStatus;
        }
	}
	
	toggleAttrQuantity(e){
        let displayStatus = e ? 'none': 'inherit';
        let itemEl = document.querySelector('#block_enter_attr_qty');

        if (itemEl) {
            itemEl.style.display = displayStatus;
        }
	}

    toggleQuantity(e) {
        let displayStatus = e ? 'none': 'inherit';
        let itemEl = document.querySelector('#block_enter_prod_qty');

        if (itemEl) {
            itemEl.style.display = displayStatus;
        }
    }

    deleteFile(id, type) {
        let url = `index.php?option=com_jshopping&controller=products&task=delete_file&id=${id}&type=${type}`;

        fetch(url, {
            method: 'GET'
        })
        .then(response => response.text())
        .then(data => {
            if (type == 'demo') {
                let productDemoEl = document.querySelector(`#product_demo_${id}`);

                if (productDemoEl) {
                    productDemoEl.innerHTML = '';
                }
            }

            if (type == 'file') {
                let productFileEl = document.querySelector(`#product_file_${id}`);

                if (productFileEl) {
                    productFileEl.innerHTML = '';
                }
            }

            if (data == '1') {
                let rowFileProdEl = document.querySelector(`.rows_file_prod_${id}`);
                rowFileProdEl.style.display = 'none';
            }
        });
    }

}

export default new ShopProductCommon();