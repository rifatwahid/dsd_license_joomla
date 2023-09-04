import React, { useState, useCallback, useEffect  } from '../../../js/react/node_modules/react';
import {
	BrowserRouter as Router,
	Switch,
	Route,
	Link,
	Redirect,
	useParams,
	useLocation, browserHistory, useHistory
} from '../../../js/react/node_modules/react-router-dom';
import nl2br from '../../../js/react/node_modules/react-nl2br';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Parser from '../../../js/react/node_modules/html-react-parser';
import ErrorBoundary from '../../../js/react/node_modules/@vlsergey/react-bootstrap-error-boundary';
import Attributes from '../elements/attributes.js';
import Free_attribute from '../elements/free_attribute.js';
import Product_info from '../elements/product_info.js';
import Product_quantity from '../elements/product_quantity.js';
import Media_product_block from '../elements/media_product_block.js';
import Smart_link_btn from '../elements/smart_link_btn.js';
import Editor_button_product from '../elements/editor_button_product.js';
import Wishlist_btn from '../elements/wishlist_btn.js';
import Default_prod_upload from '../elements/default_prod_upload.js';
import Cart_product from '../elements/cart_product.js';
import After_add_to_cart_product from '../elements/after_add_to_cart_product.js';
import Offer_and_order_prooduct from '../elements/offer_and_order_prooduct.js';
import Availability_delivery_info from '../elements/availability_delivery_info.js';
import Bulk_prices from '../elements/bulk_prices.js';
import Default_prod_tablist from '../elements/default_prod_tablist.js';
import Related from '../elements/related.js';
import Demofiles from '../elements/demofiles.js';
import Review from '../elements/review.js';
import Checkout_button_product from '../elements/checkout_button_product.js';
import uploadImage from '../../../js/src/common/upload_image/index.js';
import {
	getProductDefaultData as getProductDefaultDataAction
} from '../../../js/react/src/redux/modules/pageData';
import {connect} from "../../../js/react/node_modules/react-redux";

const Product_default = ( {productDefaultData, getProductDefaultData}) => {

	let data1 = productDefaultData;
	let [data, setData] = useState('');
	let updateData=(value)=> {
		setData(value);
	};
	if(typeof data.component == 'undefined'){
		data = data1;
	}
	const [historyHref, setHref] = useState('');
	const updateHref = (value) => {
		setHref(value);
	}
	if (historyHref != window.location.href) {
		//useEffect(() => {
			getProductDefaultData(window.location.href + '?ajax=1&ajax=1');
			updateHref(window.location.href);
		//}, []);
	}

	if(typeof data != 'undefined' && typeof data.jshopConfig != 'undefined' && data.jshopConfig.shop_mode == 0) {
		window.shopProductAttributes.__proto__.ajaxReloadPageData = function reactAjaxReloadPageData(d) {
			d['amountOfUploads'] = window.uploadImage.getAmountOfUploads('.nativeProgressUploads--0');

			var productForm = document.querySelector('#productForm');
			var productDetails = document.querySelector('.product-details');

			if (!productForm) {
				return;
			}
			d['if_react'] = 1;
			//console.log(data.product.product_id);
			this.ajax = jQuery.getJSON('/index.php?option=com_jshopping&controller=product&task=ajax_attrib_select_and_price&product_id=' + data.product.product_id + '&ajax=1', d,
				(json) => {
					updateData(json);
				}
			);
		};
		window.shopProductForm.__proto__.formHandler = function reactFormHandler(form, e) {
			var formData = shopHelper.getFormData(form);
			var isProductPage = shopHelper.isProductPage();
			var isAttrChanged = e.target && e.target.name && /jshop_attr_id/.test(e.target.name);
			var isFreeAttrChanged = e.target && e.target.name && /freeattribut\[\d*\]/.test(e.target.name);
			var isQuantityChanged = e.target && e.target.name && /quantity/.test(e.target.name);

			formData.qty = (!shopHelper.isEmpty(formData.quantity) && formData.quantity > 0) ? formData.quantity : 0;
			formData.change_attr = 0;

			// Set id of attr (if attr changed).
			var attrId = e.target.name.match(/jshop_attr_id\[(\d*)\]/);
			if (isAttrChanged && !shopHelper.isEmpty(attrId[1])) {
				formData.change_attr = attrId[1];
			}

			if (afterParseDataForReloadAttribEvents) {
				jQuery.each(afterParseDataForReloadAttribEvents, function (key, handler) {
					handler.call(formData, isProductPage, this);
				});
			}

			// else - product list
			if (isProductPage) {
				if (isFreeAttrChanged) {
					window.shopProductAttributes.ajaxReloadPageData(formData);
					updateQuantityWhenChangeProductQuantity(0, this);
				} else if (isAttrChanged && !shopHelper.isEmpty(formData.change_attr)) {
					window.shopProductAttributes.ajaxReloadPageData(formData);
					shopProductAttributes.reloadImage(formData.change_attr, e.target.value);
					shopProduct.setUrlUpdatePrice(data.urlupdateprice);
				} else if (isQuantityChanged) {
					window.shopProductAttributes.ajaxReloadPageData(formData);
					window.uploadImage.updateQuantityWhenChangeProductQuantity(0, this);
					shopProduct.setUrlUpdatePrice(data.urlupdateprice);

				}
			} else {
				formData.fromPage = 'product_list';
				shopProductAttributes.ajaxReloadProductList(formData);
			}
		};
	}
	jQuery(document).ready(() => {
		var belem = document.querySelector('#belem');

		jQuery('.jshop_prod_attributes select').change(function(e){
			shopProductForm.formHandler(e.target.form, e);
		});
		if(belem) {
			if(typeof data != 'undefined' && typeof data.jshopConfig != 'undefined' && data.jshopConfig.shop_mode == 1) {
				window.shopProductAttributes.__proto__.ajaxReloadPageData = function reactAjaxReloadPageData(d) {

					d['amountOfUploads'] = window.uploadImage.getAmountOfUploads('.nativeProgressUploads--0');

					var productForm = document.querySelector('#productForm');
					var productDetails = document.querySelector('.product-details');

					if (!productForm) {
						return;
					}
					d['if_react'] = 1;

					this.ajax = jQuery.getJSON('/index.php?option=com_jshopping&controller=product&task=ajax_attrib_select_and_price&product_id=' + data.product.product_id + '&ajax=1', d,
						(json) => {
							updateData(json);
						}
					);
				};

				window.shopProductForm.__proto__.formHandler = function reactFormHandler(form, e) {
					var formData = shopHelper.getFormData(form);
					var isProductPage = shopHelper.isProductPage();
					var isAttrChanged = e.target && e.target.name && /jshop_attr_id/.test(e.target.name);
					var isFreeAttrChanged = e.target && e.target.name && /freeattribut\[\d*\]/.test(e.target.name);
					var isQuantityChanged = e.target && e.target.name && /quantity/.test(e.target.name);

					formData.qty = (!shopHelper.isEmpty(formData.quantity) && formData.quantity > 0) ? formData.quantity : 0;
					formData.change_attr = 0;

					// Set id of attr (if attr changed).
					var attrId = e.target.name.match(/jshop_attr_id\[(\d*)\]/);
					if (isAttrChanged && !shopHelper.isEmpty(attrId[1])) {
						formData.change_attr = attrId[1];
					}

					if (afterParseDataForReloadAttribEvents) {
						jQuery.each(afterParseDataForReloadAttribEvents, function (key, handler) {
							handler.call(formData, isProductPage, this);
						});
					}

					// else - product list
					if (isProductPage) {
						if (isFreeAttrChanged) {
							window.shopProductAttributes.ajaxReloadPageData(formData);
							updateQuantityWhenChangeProductQuantity(0, e.target);
						} else if (isAttrChanged && !shopHelper.isEmpty(formData.change_attr)) {
							window.shopProductAttributes.ajaxReloadPageData(formData);
							shopProductAttributes.reloadImage(formData.change_attr, e.target.value);
							shopProduct.setUrlUpdatePrice(data.urlupdateprice);
						} else if (isQuantityChanged) {
							window.shopProductAttributes.ajaxReloadPageData(formData);
							window.uploadImage.updateQuantityWhenChangeProductQuantity(0, e.target);
							shopProduct.setUrlUpdatePrice(data.urlupdateprice);

						}
					} else {
						formData.fromPage = 'product_list';
						shopProductAttributes.ajaxReloadProductList(formData);
					}
				};
			}
		}
	});
	let element = <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;
	jQuery(function($){ $("#a.modal").modal({"backdrop": true,"keyboard": true,"show": false,"remote": ""}); });
	

	// Add extra modal close functionality for tinyMCE-based editors
	document.onreadystatechange = function () {
		if (document.readyState == 'interactive' && typeof tinyMCE != 'undefined' && tinyMCE)
		{
			if (typeof window.jModalClose_no_tinyMCE === 'undefined')
			{
				window.jModalClose_no_tinyMCE = typeof(jModalClose) == 'function'  ?  jModalClose  :  false;

				jModalClose = function () {
					if (window.jModalClose_no_tinyMCE) window.jModalClose_no_tinyMCE.apply(this, arguments);
					tinyMCE.activeEditor.windowManager.close();
				};
			}

			/*if (typeof window.SqueezeBoxClose_no_tinyMCE === 'undefined')
			{
				if (typeof(SqueezeBox) == 'undefined')  SqueezeBox = {};
				window.SqueezeBoxClose_no_tinyMCE = typeof(SqueezeBox.close) == 'function'  ?  SqueezeBox.close  :  false;

				SqueezeBox.close = function () {
					if (window.SqueezeBoxClose_no_tinyMCE)  window.SqueezeBoxClose_no_tinyMCE.apply(this, arguments);
					tinyMCE.activeEditor.windowManager.close();
				};
			}*/
		}
	};


	const onChange = useCallback(e => shopProductForm.formHandler(e.target.form, e),[]);
	if(data.component == 'Product_default') {
		if(data.href_add){
			 window.href_add = data.href_add;
			 window.href_view = data.href_view;
			 window.href_close = data.href_close;
			 window.href_address_data = data.href_address_data;
			 window.href_refresh = data.href_refresh;
			 window.href_discount = data.href_discount;
			 window.href_product = data.href_product;
			var href_error_attr = data.href_error_attr;
			var confirm_remove = data.confirm_remove;
			var base_url = data.base_url;
		}
		element =
			<div className="shop product-details">
				<div className="row">
					<div className="col-md-6 col-lg-7" id="image-video-block">
						<Media_product_block data={data} product={data.product}/>
					</div>
					<div className="col-md-6 col-lg-5 pl-lg-4">
						<Product_info product={data.product} datas={data}/>
						<Form name="product" id="productForm" method="post" action={data.action}
							  encType="multipart/form-data" autoComplete="off" onChange={onChange}>

							<div className="free-attributes" id="shop_upload_btn">
								<Free_attribute product={data.product}/>
							</div>
							<div>
								<ErrorBoundary>
									<Attributes attributes={data.attributes} product={data.product}/>
								</ErrorBoundary>
							</div>
							<div className="form-group">
								<Product_quantity data={data}/>
							</div>

							{(data._tmp_product_html_before_buttons) ? Parser(data._tmp_product_html_before_buttons) : ''}

							<ul className="list-inline flex-wrap my-4">
								<li className="list-inline-item flex-fill btn-block mx-0 mb-2"
									id="product-details__uploads">
									{(typeof data.jshopConfig != 'undefined' && (typeof data.jshopConfig.user_as_catalog == 'undefined' || data.jshopConfig.user_as_catalog != 1)) ?
										<Default_prod_upload __data={data}/>
										: ''
									}
								</li>

								<li className="list-inline-item flex-fill btn-block mx-0 mb-2">
									<Smart_link_btn data={data}/>
								</li>

								<Editor_button_product data={data}/>

								<li className="list-inline-item flex-fill btn-block mx-0 mb-2"
									id="product-details__wishlist">
									{(typeof data.jshopConfig != 'undefined' && (typeof data.jshopConfig.user_as_catalog == 'undefined' || data.jshopConfig.user_as_catalog != 1)) ?
										<Wishlist_btn data={data}/>
										: ''
									}
								</li>

								<li className="list-inline-item flex-fill mb-2 btn-block mx-0 shop_cart_btn"
									id="product-details__cart">
									{(typeof data.jshopConfig != 'undefined' && (typeof data.jshopConfig.user_as_catalog == 'undefined' || data.jshopConfig.user_as_catalog != 1)) ?
										<Cart_product data={data}/>
										: ''
									}
								</li>

								<li className="list-inline-item flex-fill mb-2 btn-block btn-block tmpProductHtmlAfterAddToCart">
									<After_add_to_cart_product data={data}/>
								</li>
								{(typeof data.jshopConfig != 'undefined' && (typeof data.jshopConfig.user_as_catalog == 'undefined' || data.jshopConfig.user_as_catalog != 1)) ?
									<Checkout_button_product data={data}/>
									: ''
								}
							</ul>

							<Offer_and_order_prooduct data={data}/>
							<Availability_delivery_info data={data}/>

							<div id="productBulkPrices" className="list-unstyled text-muted">
								<Bulk_prices data={data}/>
							</div>

							{data._tmp_product_html_after_buttons}

							<input type="hidden" name="to" id='to' value="cart"/>
							<input type="hidden" name="product_id" id="product_id"
								   value={data.product.product_id}/>
							<input type="hidden" name="category_id" id="category_id" value={data.category_id}/>
						</Form>

					</div>
				</div>
				<Default_prod_tablist data={data}/>
				<div id="product-details__demofiles">
					<Demofiles  data={data} demofiles={data.demofiles}/>
				</div>
				{data._tmp_product_html_before_related}
				<div id="product__relateds">
					<Related data={data}/>
				</div>
				<div id="product__reviews">
					<Review data={data}/>
				</div>
			</div>;

	}
	return (element);
}
export default  connect(
	({ productDefaultData }) => ({ productDefaultData: productDefaultData.productDefaultData }),
	{
		getProductDefaultData: getProductDefaultDataAction
	}
)(Product_default);
// export default Product_default;



