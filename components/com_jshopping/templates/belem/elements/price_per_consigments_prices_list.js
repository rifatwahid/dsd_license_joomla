import React, { useState } from '../../../js/react/node_modules/react';
import Parser from '../../../js/react/node_modules/html-react-parser';
import ListGroup from '../../../js/react/node_modules/react-bootstrap/ListGroup';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import Formatprice from '../elements/formatprice.js';

const Price_per_consigments_prices_list = (props) => {
	let data = props.data;
	function setPrice(price_wp, price){
		if(price_wp > 0){
			return price_wp;
		}else{
			return price;
		}
	}
	let unitName;
	let unitId;
	const element = props.product_add_prices.map((add_price, k) =>
		<div className="productBulkPrice" key={k}>
			<span className="productBulkPriceQty">

            <span className="productBulkPriceQtyFrom">
				{(add_price.product_quantity_finish == 0) ?
                    <span className="productBulkPriceQtyFrom__from-text">
                        {Joomla.JText._('COM_SMARTSHOP_BULK_FROM') + ' ' }
                    </span>
                : ''}

                <span className="productBulkPriceQtyFrom__start">
                    {" "+add_price.unitNumberFormatStar+" "}
                </span>
                <span className="productBulkPriceQtyFrom__unit">
                    {(add_price.unit_name == '' || add_price.unit_name == null) ?
						data.product.product_add_price_unit
                    :
                        add_price.unit_name
                }
                </span>
            </span>

			{(add_price.product_quantity_finish > 0) ?
                <span className="productBulkPriceQtyTo">
                    <span className="productBulkPriceQtyTo__delimeter">
						{" - "}
                    </span>
                    <span className="productBulkPriceQtyTo__finish">
						{" "+add_price.unitNumberFormatFinish+" "}
                        {/*<?php echo getUnitNumberFormat($this->product->add_price_unit_id, $add_price->product_quantity_finish); ?>*/}
                    </span>

                    <span className="productBulkPriceQtyTo__unit">

						{(add_price.unit_name == '' || add_price.unit_name == null) ?
							' ' + data.product.product_add_price_unit
							:
							' ' + add_price.unit_name
						}

                    </span>
                </span>
            : ''}
        </span>

		<span className="float-right productBulkPrice" id={"pricelist_from_" + add_price.idForElement}>
            <i className="productBulkPrice__price">
				<Formatprice price={setPrice(add_price.price_wp, add_price.price)} data={data} /> {' '}
            </i>
            <i className="productBulkPrice__delimeter">
                /
            </i>
            <i className="productBulkPrice__unit">

				{(add_price.unit_name == '' || add_price.unit_name == null) ?
                    ' ' + data.product.product_add_price_unit
                    :
                    ' ' + add_price.unit_name
                }
            </i>
        </span>
	</div>
	);
	return (element);
	}

	export default Price_per_consigments_prices_list;