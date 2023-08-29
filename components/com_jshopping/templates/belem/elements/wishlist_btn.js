import React, { useState } from '../../../js/react/node_modules/react';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import {
	BrowserRouter as Router,
	Route,
	Link,
	Redirect
} from '../../../js/react/node_modules/react-router-dom';

const Wishlist_btn = (props) => {
	let data = props.data;
	let [dataRedirect, setStatus] = useState('');
	let updateStatus=(value)=> {
		setStatus(value);
	}
	function toCart(id, type) {
		event.preventDefault();
		const form = jQuery(id);
		var queryString = jQuery(id).serialize();
		queryString += '&to=wishlist';
		let href = 'index.php?option=com_jshopping&controller=cart&task=add&to=wishlist&ajax=1';

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
	if (dataRedirect == 3){
		return <Redirect to={data.href_wishlist} />;
	}
	const element = (data.enable_wishlist == 1 && (data.productUsergroupPermissions.is_usergroup_show_buy == 1 || data.productUsergroupPermissions.is_usergroup_show_buy == 'INF') ) ?
		<Button type="submit" variant="outline-secondary" className="btn-block mb-0" onClick={(e) => {event.preventDefault();toCart('form#productForm', 3)}}>
			{Joomla.JText._('COM_SMARTSHOP_ADD_TO_WISHLIST')}
		</Button>
	: '';
	return (element);
	}

	export default Wishlist_btn;