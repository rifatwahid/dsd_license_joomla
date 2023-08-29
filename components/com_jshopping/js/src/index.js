window.reloadAttribEvents = [];
window.afterParseDataForReloadAttribEvents = [];
window.beforeAjaxReloadPageDataEvents = [];
window.afterAjaxReloadPageDataEvents = [];

import shopHelper   from './common/helper/index.js';
import shopJsTrigger   from './common/shop_js_trigger/index.js';
import uploadImage  from './common/upload_image/index.js';
import shopCart     from './controllers/cart/index.js';
import shopCategory from './controllers/category/index.js';
import shopSearch   from './controllers/search/index.js';
import shopUser     from './controllers/user/index.js';
import shopProduct  from './controllers/product/index.js';
import shopProductAttributes     from './controllers/product/attributes.js';
import shopProductCommon         from './controllers/product/common.js';
import shopProductForm           from './controllers/product/form.js';
import shopProductFreeAttributes from './controllers/product/freeattributes.js';
import shopProductImageUpload    from './controllers/product/imageupload.js';
import shopQuickCheckout         from './controllers/qcheckout/index.js';
import shopUserAddressesPopup         from './controllers/user/useraddressespopup.js';
import shopStates   from './controllers/states/index.js';
import shopOneClickCheckout   from './controllers/one_click_checkout/index.js';
import shopReturn   from './controllers/return/index.js';
import shopModal   from './controllers/modal/index.js';

/*
** Yep it's a bad practice but i don't know how to do destructuring when load window
** It's like a register pattern
*/

window.shopHelper   = shopHelper;
window.shopJsTrigger = shopJsTrigger;
window.uploadImage  = uploadImage;
window.shopCart     = shopCart;
window.shopCategory = shopCategory;
window.shopSearch   = shopSearch;
window.shopUser     = shopUser;
window.shopProduct  = shopProduct;
window.shopProductAttributes = shopProductAttributes;
window.shopProductCommon     = shopProductCommon;
window.shopProductForm       = shopProductForm;
window.shopProductFreeAttributes = shopProductFreeAttributes;
window.shopProductImageUpload    = shopProductImageUpload;
window.shopQuickCheckout         = shopQuickCheckout;
window.shopUserAddressesPopup    = shopUserAddressesPopup;
window.shopStates   = shopStates;
window.shopOneClickCheckout   = shopOneClickCheckout;
window.shopReturn   = shopReturn;
window.shopModal   = shopModal;