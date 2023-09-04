import React, { useState, useCallback,useEffect  } from '../../../js/react/node_modules/react';
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
import shopProductForm from '../../../js/src/controllers/product/form.js';
import Prices_product from '../elements/prices_product.js';
import SprintQtyInStock from '../elements/sprintqtyinstock.js';
import Showmarkstar from '../elements/showmarkstar.js';
import Attributes from '../elements/attributes.js';
import Free_attribute from '../elements/free_attribute.js';
import Products_wishlist_btn from '../elements/products_wishlist_btn.js';
import shopHelper from '../../../js/src/common/helper/index.js';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import ListGroup from '../../../js/react/node_modules/react-bootstrap/ListGroup';
import Product_list_quantity from '../elements/product_list_quantity.js';
// import SmartshopCartModule from '../../../../../modules/mod_smartshop_cart/mod_smartshop_cart.js';


const Product = (props) => {
	let data = props.data;
	let product = props.product;
	let im = ''
	if(product.attribute_active != null && typeof product.attribute_active_data.ext_dataimage != 'undefined' ){
		im = product.image;
	}else{
		im = 'noimage.gif';
	}
	let qtyInStock;

	const getPatchProductImage = (name) => {
		var pattern = new RegExp('^(https?:\\/\\/)?'+
			'((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+
			'((\\d{1,3}\\.){3}\\d{1,3}))'+
			'(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+
			'(\\?[;&a-z\\d%_.~+=-]*)?'+
			'(\\#[-a-z\\d_]*)?$','i');
		let isUrl = pattern.test(name);
		if (name == '' || isUrl != 'true') {
			return name;
		}
		name = data.config.image_product_live_path+"/"+name;

		return name;
	}
	let [dataRedirect, setStatus] = useState('');
	let updateStatus=(value)=> {
		setStatus(value);
	}
	jQuery(document).ready(() => {
		jQuery('.cart-product__form').change(function (e) {
			shopProductForm.formHandler(e.target.form, e);
		})
	});

	function toCart(id, type) {
		event.preventDefault();
		const form = jQuery(id);
		var queryString = jQuery(id).serialize();
		let href = 'index.php?option=com_jshopping&controller=cart&task=add&to=cart&ajax=1';
		if(type == 3) {
			queryString += '&to=wishlist';
		}

		fetch(href, {
			method: "POST",
			headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			body: queryString
		}).then(res => res.json())
			.then((result) => {
				if (result.count_product > 0) {
					updateStatus(type);
				}

			});

	}
	if (dataRedirect == 1){
		return <Redirect to={data.cart_link} />;
	}else if(dataRedirect == 2){
		return <Redirect to={data.href_checkout} />;
	}else if(dataRedirect == 3){
		return <Redirect to={data.href_wishlist} />;
	}

	const element =
		<div key={props.product_id} className="col-sm-6 col-md-4 col-lg-3 card-group mb-5">
		<div className={"card product-"+product.product_id+" cart-product"} data-product-id={product.product_id}>
			{(product.label_id != 'null') ?
			<div className="product_label">
				{(product._label_image != 'null') ?
					<Image src={product._label_image}
						 alt={nl2br(product._label_name)}/>
					:
					<span className="label_name">{product._label_name}</span>
				}
			</div>
			: ''}

			<Link to={product.product_link} className="cart-product__img-link">
				<Image className="card-img-top" src={product.image} alt={nl2br(product.name)} />
			</Link>

			<div className="card-body text-body">
				<Form name="product" id={"productForm-" + product.product_id} className="cart-product__form" method="post" action={data.action} encType="multi
				t/form-data"  autoComplete="off">
					<Link to={product.product_link} className="text-body cart-product__title-link">
						<h5 className="card-title">{product.name}</h5>
					</Link>

					<div className="cart-product__short-description">
						{(data.config.product_list_show_short_description == 1 && product.short_description.length > 0) ?
						<div className="text-small text-muted short_description" dangerouslySetInnerHTML={{__html: product.short_description}} />
						: ''}
					</div>

					{(product.delivery_time.length > 0 && data.config.delivery_times_on_product_listing.length > 0) ?
					<div className="text-small text-muted card-deliveryTime cart-product__delivery-time">
						{Joomla.JText._('COM_SMARTSHOP_STOCK_DELIVERY_TIME')}: {product.delivery_time}
					</div>
					: ''}

					{(data.production_time != 'null' && product.production_time > 0) ?
					<div className="text-small text-muted cart-product__production-time">
						{Joomla.JText._('COM_SMARTSHOP_PRODUCTION_TIME') + ': ' + product.production_time + ' ' + Joomla.JText._('COM_SMARTSHOP_DAYS')}
					</div>
					: ''}

					{(product.manufacturer.name != 'null') ?
					<p className="card-text text-muted small cart-product__manufacturer-name">{product.manufacturer.name}</p>
					: ''}

					<div className="cart-product__prices">
						{(data.display_price == 1 && product._display_price != 0) ?
							<Prices_product data={data} product={product} />
                        : '' }
					</div>
					{product._tmp_individual_product_list_html_before_weight}
					{(data.config.product_list_show_weight == 1 && typeof product.weight != "undefined") ?
					<div className="cart-product__weight-data">
						<span className="cart-product__weight-text">{Joomla.JText._('COM_SMARTSHOP_WEIGHT')}</span>
						<span className="cart-product__weight-separator">: </span>
						<span className="cart-product__weight">{product.weight}</span>
					</div>
					: ''}

					{(data.config.product_list_show_product_code == 1 && product.product_ean != '') ?
					<div className="cart-product__code-data">
						<span className="cart-product__code-text">{Joomla.JText._('COM_SMARTSHOP_PRODUCT_CODE')}</span>
						<span className="cart-product__code-separator">:</span>
						<span className="cart-product__code">{(product.product_ean) ? product.product_ean : product.attribute_active_data.ean}</span>
					</div>
					: ''}

					{(data.config.stock == 1 && data.config.product_list_show_qty_stock == 1) ?
					<div className="form-group row">
						<div id="available-text" className={(!(<SprintQtyInStock qty_in_stock={product.qty_in_stock} />) <= 0) ? "col avaliable-text text-success" : "col avaliable-text text-danger" }>
							{(!(<SprintQtyInStock qty_in_stock={product.qty_in_stock}/>) <= 0) ?
								Joomla.JText._('COM_SMARTSHOP_STOCK_AVAILABLE')
								: (typeof data.config.hide_text_product_not_available == 'undefined' || data.config.hide_text_product_not_available == 'null' || data.config.hide_text_product_not_available == '') ?
									Joomla.JText._('COM_SMARTSHOP_STOCK_NOT_AVAILABLE')
								: ''
							}
						</div>

						{(product.qty_in_stock['unlimited'] == 0 && product.qty_in_stock['qty'] > 0) ?
						<div className={"col-6 text-right text-muted "}>
							{Joomla.JText._('COM_SMARTSHOP_STOCK_QUANTITY')}:
							<span id="product_qty"><SprintQtyInStock qty_in_stock={product.qty_in_stock}/></span>
						</div>
						: ''}
					</div>
					: ''}

					{(data.allow_review == 1 && product.reviews_count > 0) ?
                        <Showmarkstar data={data} rating={product.average_rating} />
                    : ''}

					{(data.config.productlist_allow_buying != 0 && typeof product.attributes != 'undefined') ?
						<div>
							<Attributes attributes={product.attributes} product={product}/>

							{product.tmp_product_inlist_html_after_attr}

							<div className="free-attributes"  id="shop_upload_btn">
								<Free_attribute product={product}/>
							</div>
						</div>
						: ''}
					{(data.config.productlist_allow_buying != 0) ?
						<div>
							{(data.config.productlist_allow_buying == 2) ?
							<div className="product_quantity_list">
								<Product_list_quantity product={product} data={data}/>
							</div>
								: ''}

							<ListGroup.Item as="li" className="border-0 p-0 list-inline-item flex-fill mb-2 shop_cart_btn btn-block" id="cart-product__cart">
								{((product.isShowCartSection > 0 || product.isShowCartSection == 'INF') &&  data.config.user_as_catalog != 1) ?
									<Button type="submit" variant="outline-primary" className="btn-block"
											onClick={(e) => {event.preventDefault();toCart('form#productForm-' + product.product_id, 1)}}>
											 {/*onClick={shopHelper.replaceFormActionText('form#productForm-' + product.product_id, data.sefLinkToCartAdd)}>*/}
										{Joomla.JText._('COM_SMARTSHOP_ADD_TO_CART')}
									</Button>
								: ''}
							</ListGroup.Item>

							<ListGroup.Item as="li" className="border-0 p-0 list-inline-item flex-fill mb-2 btn-block tmpProductListHtmlAfterAddToCart">
								{(product.isShowCartSection > 0 &&  data.config.user_as_catalog != 1) ?
								<div className="tmpProductListHtmlAfterAddToCart__wrapper">
									{product._tmp_individual_product_list_html_after_add_to_cart}
								</div>
								: ''}
							</ListGroup.Item>

							{(data.config.display_checkout_button == 1 &&  data.config.user_as_catalog != 1) ?
								<li as="li" className="mb-2 btn-block cart-product__checkout">
									<Button type="submit" onClick={(e) => {event.preventDefault();toCart('form#productForm-' + product.product_id, 2)}} variant="outline-primary" className="btn btn-outline-primary btn-block">
										{Joomla.JText._('COM_SMARTSHOP_CHECKOUT')}
									</Button>
								</li>
							: ''}
						</div>
					: ''}

					<ListGroup.Item as="li" className="border-0 p-0 list-inline-item mb-2 btn-block cart-product__wishlist">
						{(typeof product.permissions != 'undefined' && product.permissions.is_usergroup_show_price && product.permissions.is_usergroup_show_buy && data.config.enable_wishlist > 0 && data.config.show_wishlist_button > 0 &&  data.config.user_as_catalog != 1) ?
							<Button type="submit"  variant="outline-secondary" className="btn-block wishlist_btn" onClick={(e) => {event.preventDefault();toCart('form#productForm-' + product.product_id, 3)}} >
								{Joomla.JText._('COM_SMARTSHOP_ADD_TO_WISHLIST')}
							</Button>
                        : ''}
					</ListGroup.Item>

					<input type="hidden" name="product_id" value={product.product_id} />
				</Form>
			</div>
		</div>
	</div>;

	return (element);
}
export default Product;



