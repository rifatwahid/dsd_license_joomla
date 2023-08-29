import React, { useState } from '../../../js/react/node_modules/react';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import {
	BrowserRouter as Router,
	Route,
	Link,
	Redirect
} from '../../../js/react/node_modules/react-router-dom';

const Checkout_button_product = (props) => {
	let data = props.data;
	let [dataRedirect, setStatus] = useState('');
	let updateStatus=(value)=> {
		setStatus(value);
	}
	function toCart(id, type) {
		event.preventDefault();
		const form = jQuery(id);
		var queryString = jQuery(id).serialize();
		let href = 'index.php?option=com_jshopping&controller=cart&task=add&to=cart&ajax=1';

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
	if (dataRedirect == 2){
		return <Redirect to={data.href_checkout} />;
	}
	const element =
		((typeof data.hide_buy == 'undefined' || data.hide_buy == 0) && data.config.display_checkout_button == 1) ?
			<li className="btn-block" id="checkout_button__product">
				<Button type="submit" variant="outline-primary" className="btn btn-block btn-add-product-to-checkout" onClick={(e) => {event.preventDefault();toCart('form#productForm', 2)}}>
					{Joomla.JText._('COM_SMARTSHOP_CHECKOUT')}
				</Button>
			</li>
			: '';

	return (element);
}

export default Checkout_button_product;