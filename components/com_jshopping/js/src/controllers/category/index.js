import shopHelper from '../../common/helper/index.js';

class ShopCategory {

    clearFilter() {
        if(document.getElementById("manufacturers") != null) shopHelper.getElement("manufacturers").value = "0";
        if(document.getElementById("price_from") != null) shopHelper.getElement("price_from").value = "";
        if(document.getElementById("categorys") != null) shopHelper.getElement("categorys").value = "0";
        if(document.getElementById("price_to") != null) shopHelper.getElement("price_to").value = "";
        shopHelper.getElement('sort_count').submit();
    }

    changeSorting() {
        shopHelper.getElement('orderby').value = shopHelper.getElement('orderby').value ^ 1;
        shopHelper.getElement('sort_count').submit();
    }

}

export default new ShopCategory();