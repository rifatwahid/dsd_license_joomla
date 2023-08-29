import React, { useState } from '../../../js/react/node_modules/react';

const Created_offer_and_order = () => {

	const element = <div className="shop offer-saved">
		<h1 className="offer-saved__page-title">{Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_SAVED')}</h1>

		<p className="my-4">{Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_SAVED_TEXT_EXPLANATION')}</p>

		<div className="offer_and_order_created offer-open-pdf-lightbox-container" data-order-id={dataJson.order.order_id} data-user-id={dataJson.order.user_id}>
			<a className="btn btn-outline-secondary offer-open-pdf-lightbox" target='_blank' data-med={dataJson.url} href={dataJson.url} data-med-size="650x650" data-size="650x650">
				<span>{Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_OPEN_OFFER_AND_ORDER')}</span>
			</a>
		</div>
	</div>;

	return (element);
}

export default Created_offer_and_order;