import React, { useState } from '../../../js/react/node_modules/react';

const Code = (data) => {

	const element = <div className="product-details__code-data">
		<span className="product-details__code-text">{Joomla.JText._('COM_SMARTSHOP_PRODUCT_CODE')}</span>
		<span className="product-details__code-separator">: </span>
		<span className="product-details__code">{dataJson.ean}</span>
	</div>;
	return (element);
	}

	export default Code;