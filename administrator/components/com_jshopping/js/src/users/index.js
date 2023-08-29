class ShopUser {

    anotherDeliveryAddress(value) {
        let endesEl = document.querySelector('.endes');

        if (endesEl) {
            if (value) {
                endesEl.removeAttribute("disabled");
            } else {
                endesEl.setAttribute('disabled', 'disabled');
            }
        }

        
    }

}

export default new ShopUser();