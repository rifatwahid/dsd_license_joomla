import React, { useState } from '../../../js/react/node_modules/react';
import Parser from '../../../js/react/node_modules/html-react-parser';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import shopHelper from '../../../js/src/common/helper/index.js';

const Products_wishlist_btn = (data) => {
    var wishlistProductId = (typeof data.product.product_id != 'undefined') ? data.product.product_id : data.product.product_id;
    var wishlistSefLinkToWishlistAdd = data.sefLinkToWishlistAdd ? data.sefLinkToWishlistAdd : '';
    jQuery('.wishlist_btn').click(function(){
       var form = jQuery(this).closest("form");
       jQuery(form).attr('action', wishlistSefLinkToWishlistAdd);
       jQuery(form).submit();
    });

    const element =
        <Button type="button"  variant="outline-secondary" className="btn-block wishlist_btn" dateT={'"form#productForm-' + wishlistSefLinkToWishlistAdd + '"'} onClick={(e) =>{setFormAction(wishlistSefLinkToWishlistAdd)}} >
            {Joomla.JText._('COM_SMARTSHOP_ADD_TO_WISHLIST')}
        </Button>;

    wishlistProductId = null;
    wishlistSefLinkToWishlistAdd = null;

    return (element);
}

export default Products_wishlist_btn;