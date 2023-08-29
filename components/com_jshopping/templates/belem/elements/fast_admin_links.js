import React, { useState } from '../../../js/react/node_modules/react';

const Fast_admin_links = (data) => {
	const element =	 (dataJson.offer_and_order_admin_user_id != null && typeof dataJson.offer_and_order_admin_user_id != 'undefined' ) ?
		<div className="btns">
			<a className="link_to_checkout button" href={dataJson.create_offer_cart_link}>
				{Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_OAO_CREATE_OFFER')}
			</a>

			<a className="link_to_checkout button" href={dataJson.create_order_cart_link}>
				{Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_OAO_CREATE_ORDER')}
			</a>
		</div>

	 : '';
return (element);
}
export default Fast_admin_links;
