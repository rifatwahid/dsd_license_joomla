import React, { useState } from '../../../js/react/node_modules/react';
import Parser from '../../../js/react/node_modules/html-react-parser';
import ListGroup from '../../../js/react/node_modules/react-bootstrap/ListGroup';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import Product from '../pages/product.js';

const Related = (data) => {

	const element = (data.data.related_prod.length > 0) ?
	<section className="my-4">
			<div className="row">
				{data.data.related_prod.map((pr, k) =>
					<Product key={pr.product_id} product={pr} data={data.data}/>
				)}
			</div>
	</section>
	: '';

	return (element);
}

export default Related;