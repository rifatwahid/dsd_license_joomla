import React, { useState, useEffect } from '../../../js/react/node_modules/react';
import { getOfferData as getOfferDataAction } from '../../../js/react/src/redux/modules/pageData';
import {connect} from "../../../js/react/node_modules/react-redux";
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';

const Created_offer_and_order = ({offerData, getOfferData}) => {
	useEffect(() => {
		getOfferData(window.location.href + '?ajax=1&ajax=1');
	}, []);
	let element = <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;

	if(offerData.order) {
		element = <div className="shop offer-saved">
			<h1 className="offer-saved__page-title">{Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_SAVED')}</h1>

			<p className="my-4">{Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_SAVED_TEXT_EXPLANATION')}</p>

			<div className="offer_and_order_created offer-open-pdf-lightbox-container"
				 data-order-id={offerData.order.order_id} data-user-id={offerData.order.user_id}>
				<a className="btn btn-outline-secondary offer-open-pdf-lightbox" target='_blank'
				   data-med={offerData.url} href={offerData.url} data-med-size="650x650" data-size="650x650">
					<span>{Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_OPEN_OFFER_AND_ORDER')}</span>
				</a>
			</div>
		</div>;
	}

	return (element);
}
export default  connect(
	({ offerData }) => ({ offerData: offerData.offerData }),
	{
		getOfferData: getOfferDataAction
	}
)(Created_offer_and_order);