import React, { useState } from '../../../js/react/node_modules/react';

const Offersent = () => {

	const element = <div className="shop offer-sent">
		<h1 className="offer-sent__page-title">{Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_SENT')}</h1>
	</div>;

	return (element);
}

export default Offersent;