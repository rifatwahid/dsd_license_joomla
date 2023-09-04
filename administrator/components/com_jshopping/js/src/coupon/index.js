import shopHelper from '../helper/index.js';

class ShopCoupon {

    constructor() {
        document.addEventListener('DOMContentLoaded', function (e) {
            if (shopHelper.isCouponEditPage()) {
                let createForEachUserEl = document.querySelector('#create-for-each-user');

                if (createForEachUserEl) {
                    createForEachUserEl.addEventListener('change', function () {
                        let userRowEl = document.querySelector('.forUserIdRow');
                        let couponRowEl = document.querySelector('.couponRowEl');
                        let displayStatus = this.checked ? 'none': 'inherit';

                        if (userRowEl) {
                            userRowEl.style.display = displayStatus;
                        }

                        if (couponRowEl) {
                            couponRowEl.style.display = displayStatus;
                        }
                    });
                }
            }
        });
    }

    changeType() {
        let checkedCouponTypeEl = document.querySelector('input[name=coupon_type]:checked');
        let percentEl = document.querySelector('#ctype_percent');
        let currecnyEl = document.querySelector('#ctype_value');

        if (checkedCouponTypeEl && +checkedCouponTypeEl.value) {
            percentEl.style.display = 'none';
            currecnyEl.style.display = 'inline-block';
        } else {
            percentEl.style.display = 'inline-block';
            currecnyEl.style.display = 'none';
        }
    }
}

export default new ShopCoupon();
