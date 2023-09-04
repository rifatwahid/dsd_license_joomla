document.addEventListener("DOMContentLoaded", function () {
    let form = document.querySelector('form#item-form');
    let isJoomla4 = !!document.querySelector('joomla-tab-element');

    if (form && form.length > 0) {
        function menuProductListElEvent() {
            let parsedData = new FormData();
            parsedData.append('ajax', 1);
            parsedData.append('is_security_enabled', 0);
            parsedData.append('easy_products', true);

            let idToParse = {
                'context': 'jform_request_task',
                'category_id': 'jform_request_category_id',
                'manufacturer_id': 'jform_request_manufacturer_id',
                'label_id': 'jform_request_label_id',
                'vendor_id': 'jform_request_vendor_id',
                'price_from': 'jform_request_price_from',
                'price_to': 'jform_request_price_to',
            };

            Object.keys(idToParse).forEach(key => {
                let id = idToParse[key];
                let el = form.querySelector('#' + id);

                if (el && el.value) {
                    parsedData.append(key, el.value)
                }
            });

            fetch('/administrator/index.php?option=com_jshopping&controller=products&task=getFilteredProducts', {
                method: 'POST',
                body: parsedData
            })
            .then(response => response.json())
            .then((data) => {
                let selectOfProductListEl = document.querySelector('#jform_request_products_list');
                let selectOfProductEl = document.querySelector('#jform_request_product_id');

                if (selectOfProductListEl) {
                    selectOfProductListEl.innerHTML = '';
                }

                if (selectOfProductEl) {
                    var selectOfProductValue = selectOfProductEl.value;
                    selectOfProductEl.innerHTML = '';
                }

                if ((selectOfProductListEl || selectOfProductEl) && data && data.products) {    
                    data.products.forEach(function(product) {
                        let select = document.createElement('option');
                        select.value = product.product_id;
                        select.innerHTML = product.name;

                        if (selectOfProductListEl) {
                            selectOfProductListEl.add(select);
                        }

                        if (selectOfProductEl) {
                            selectOfProductEl.add(select);
                        }
                    });
                }
                
                if (!isJoomla4) {
                    let productListEl = document.getElementById('jform_request_products_list');
                    if (productListEl) {
                        productListEl.value = '';
                        productListEl.dispatchEvent(new Event('liszt:updated'));
                    }

                    let productidEl = document.getElementById('jform_request_product_id');
                    if (productidEl) {
                        productidEl.value = '';
                        productidEl.dispatchEvent(new Event('liszt:updated'));
                    }
                }

                if (selectOfProductEl) {
                    selectOfProductEl.value = selectOfProductValue;
                }
            });
        };

        let wathId = [
            'jform_request_task',
            'jform_request_category_id',
            'jform_request_manufacturer_id',
            'jform_request_label_id',
            'jform_request_vendor_id',
            'jform_request_price_from',
            'jform_request_price_to',
        ];

        if (!isJoomla4) {
            let watch = [
                '#jform_request_task_chzn .chzn-results',
                '#jform_request_category_id_chzn .chzn-results',
                '#jform_request_manufacturer_id_chzn .chzn-results',
                '#jform_request_label_id_chzn .chzn-results',
                '#jform_request_vendor_id_chzn .chzn-results',
            ];

            watch.forEach(function (selectorName) {
                let element = document.querySelector(selectorName);

                if (element) {
                    element.addEventListener('click', function (e) {
                        menuProductListElEvent();
                    });
                }
            });
        }
    
        form.addEventListener('change', function (e) {
            if (wathId.includes(e.target.id)  && e.target.id != 'jform_request_products_list') {
                menuProductListElEvent();
            }
        });

        menuProductListElEvent();
    }
});