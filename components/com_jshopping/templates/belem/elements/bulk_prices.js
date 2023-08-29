import React, { useState } from '../../../js/react/node_modules/react';
import Price_per_consigments_prices_list from '../elements/price_per_consigments_prices_list.js';

const Bulk_prices = (props) => {
	let data = props.data;
	const element = (data.product._display_price == 1 && data.product.is_show_bulk_prices == 1 && data.product.product_is_add_price !== 1 && data.add_prices_with_user_discount != null) ?
	<div id="productBulkPrices">
		<span className="productBulkPrices__title">
			{Joomla.JText._('COM_SMARTSHOP_BULK_PRICES')}
		</span>

		<div id="productBulkPrices__list">
			<Price_per_consigments_prices_list data={data} product_add_prices={data.add_prices_with_user_discount}/>
		</div>
	</div>
  	: '';
	return (element);
	}

	export default Bulk_prices;