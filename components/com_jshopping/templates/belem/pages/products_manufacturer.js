import React, {useEffect, useState} from '../../../js/react/node_modules/react';
import nl2br from '../../../js/react/node_modules/react-nl2br';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';
import Parser from '../../../js/react/node_modules/html-react-parser';
import Category_products from '../elements/category_products.js';
import List_products from '../elements/list_products.js';
import No_products from '../elements/no_products.js';
import Block_pagination from '../elements/block_pagination.js';
import {	getManufacturerProductsData as getManufacturerProductsDataAction} from '../../../js/react/src/redux/modules/pageData';
import {connect} from "../../../js/react/node_modules/react-redux";

const Products_manufacturer = ({manufacturerProductsData, getManufacturerProductsData}) => {
	let data = manufacturerProductsData;
	useEffect(() => {
		getManufacturerProductsData(window.location.href + '?ajax=1&ajax=1');
	}, []);
	let element = <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;

	if(data.component) {
		element = <div className="shop manufacturer-products">
			<h1 className="manufacturer-products__page-title">{data.manufacturer.name}</h1>

			{(typeof data.manufacturer != 'undefined' && data.manufacturer.description.length > 0) ?
				Parser(data.manufacturer.description) : ''
			}

			<div className="row">
				{(typeof data.rows != 'undefined' && data.rows.length > 0) ?
					<List_products data={data}/>
					:
					<No_products data={data}/>
				}
			</div>

			{(dataJson.display_pagination == 1) ?
				<Block_pagination/>
				: ''}

		</div>;

	}
	return (element);
}
export default  connect(
	({ manufacturerProductsData }) => ({ manufacturerProductsData: manufacturerProductsData.manufacturerProductsData }),
	{
		getManufacturerProductsData: getManufacturerProductsDataAction
	}
)(Products_manufacturer);
