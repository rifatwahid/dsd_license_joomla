import ShopHelper     from './helper/index.js';
import ShopAttributes from './attributes/index.js';
import ShopCategory   from './category/index.js';
import ShopSearch   from './search/index.js';
import ShopConfig     from './config/index.js';
import ShopCoupon     from './coupon/index.js';
import ShopEmailHub   from './email_hub/index.js';
import ShopImage      from './image/index.js';
import ShopShipping   from './shipping/index.js';
import ShopUser       from './users/index.js';
import ShopOrderAndOffer        from './order_and_offer/index.js';
import ShopProductAttribute     from './product/attribute.js';
import ShopProductCharacteristics     from './product/characteristics.js';
import ShopProductCommon        from './product/common.js';
import ShopProductFreeAttribute from './product/freeattribute.js';
import ShopProductImage         from './product/image.js';
import ShopProductPrice         from './product/price.js';
import ShopPricePerConsigment   from './product/priceperconsigment.js';
import ShopProductRelated       from './product/related.js';
import ShopProductUserGroup     from './product/usergroup.js';
import ShopProductVideo         from './product/video.js';
import ShopProductLabel         from './productlabel/index.js';
import AdminShopNativeUploads         from './native_uploads/index.js';
import ShopPage         from './helper/shopPage.js';
import ShopReview         from './review/index.js';
import ShopOrder         from './order/index.js';
import ShopRefund         from './order/refund.js';
import ShopStates   from './states/index.js';

window.shopHelper     = ShopHelper;
window.shopAttributes = ShopAttributes;
window.shopCategory   = ShopCategory;
window.shopSearch	  = ShopSearch;
window.shopConfig     = ShopConfig;
window.shopCoupon     = ShopCoupon;
window.shopEmailHub   = ShopEmailHub;
window.shopImage      = ShopImage;
window.shopShipping   = ShopShipping;
window.shopUser       = ShopUser;
window.shopOrderAndOffer        = ShopOrderAndOffer;
window.shopProductAttribute     = ShopProductAttribute;
window.shopProductCharacteristics = ShopProductCharacteristics;
window.shopProductCommon        = ShopProductCommon;
window.shopProductFreeAttribute = ShopProductFreeAttribute;
window.shopProductImage         = ShopProductImage;
window.shopProductPrice         = ShopProductPrice;
window.shopPricePerConsigment   = ShopPricePerConsigment;
window.shopProductRelated       = ShopProductRelated;
window.shopProductUserGroup     = ShopProductUserGroup;
window.shopProductVideo         = ShopProductVideo;
window.shopProductLabel         = ShopProductLabel;
window.AdminShopNativeUploads   = AdminShopNativeUploads;
window.shopPage                 = ShopPage;
window.shopReview               = ShopReview;
window.shopOrder                = ShopOrder;
window.shopRefund                = ShopRefund ;
window.shopStates	  			= ShopStates;

// Open documentation link in a new blank tab.
window.shopPage.addToStackExecuteAfterDomReady(function () {
    let textOfSHDocLink = document.querySelectorAll('.textToSHDocBlankTarget');
    
    try {        
        if (textOfSHDocLink && textOfSHDocLink.length != 0) {
            textOfSHDocLink.forEach(function (textSHDoc) {
                let linkOfShDoc = textSHDoc.closest('a');

                if (linkOfShDoc) {
                    linkOfShDoc.setAttribute('target', '_blank');
                }
            });
        } else {
            let linkToDocumentation = document.querySelector('#sidebarmenu a[href*="task=redirectToShopDocumentation"]');
            
            if (linkToDocumentation) {
                linkToDocumentation.target = '_blank';
            }
        }
    } catch(error) {
        console.warn('I couldn`t find sidebar link to SmartShop documentation.');
    }
});

window.shopPage.executeAfterDomReady();