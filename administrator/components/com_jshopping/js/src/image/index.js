class ShopImage {

    setDefaultSize(width, height, param) {
        shopHelper.getElement(param + '_width_image').value = width;
        shopHelper.getElement(param + '_height_image').value = height;
        shopHelper.getElement(param + '_width_image').disabled = true;
        shopHelper.getElement(param + '_height_image').disabled = true;
    }

    setOriginalSize(param) {
        shopHelper.getElement(param + '_width_image').disabled = true;
        shopHelper.getElement(param + '_height_image').disabled = true;
        shopHelper.getElement(param + '_width_image').value = 0;
        shopHelper.getElement(param + '_height_image').value = 0;
    }

    setManualSize(param) {
        shopHelper.getElement(param + '_width_image').disabled = false;
        shopHelper.getElement(param + '_height_image').disabled = false;
    }

    delete(id, type) {
        let url, block;

        switch (type) {
            case 'category':
                    url = 'index.php?option=com_jshopping&controller=categories&task=delete_foto&catid=' + id;
                    block = '#foto_category';
                break;
            case 'payments':
                    url = 'index.php?option=com_jshopping&controller=payments&task=delete_foto&payment_id=' + id;
                    block = '#foto_payments';
                break;
            case 'shipping':
                    url = 'index.php?option=com_jshopping&controller=shippings&task=delete_foto&shipping_id=' + id;
                    block = '#foto_shippings';
                break;
            case 'product':
                    url = 'index.php?option=com_jshopping&controller=products&task=delete_foto&id=' + id;
                    block = '#foto_product_' + id;

                    let productFotoEl = document.querySelector(block);
                    if (productFotoEl) {
                        productFotoEl.remove();
                    }
                break;
            case 'manafacturer':
                    url = 'index.php?option=com_jshopping&controller=manufacturers&task=delete_foto&id=' + id;
                    block = '#image_manufacturer';
                break;
            case 'attribut':
                    url = 'index.php?option=com_jshopping&controller=attributesvalues&task=delete_foto&id=' + id;
                    block = '#image_attrib_value';
                break;
            case 'productfield':
                    url = 'index.php?option=com_jshopping&controller=productfieldvalues&task=delete_foto&id=' + id;
                    block = '#image_productfield_value';
                break;
            default : return;
        }

        fetch(url, {
            method: 'GET'
        })
        .then(response => {
            return response.text();
        })
        .then(() => {
            let el = document.querySelector(block);

            if (el) {
                el.style.display = 'none';
            }
        });
    }

}

export default new ShopImage();
