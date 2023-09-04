import React, { useState } from '../../../js/react/node_modules/react';
import Product from '../pages/product.js';

const List_products = (props) => {
    let data = props.data
    const result = Object.keys(data.rows).map((key) => data.rows[key]);
    const element =
        result.map((product) =>
            <Product key={product.product_id} data={data} product={product} />
    );

    return (element);
}
export default List_products;
