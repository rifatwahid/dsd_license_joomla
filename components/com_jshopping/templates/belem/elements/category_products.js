import React, { useState } from '../../../js/react/node_modules/react';
import nl2br from '../../../js/react/node_modules/react-nl2br';
import List_products from '../elements/list_products.js';
import No_products from '../elements/no_products.js';
import Block_pagination from '../elements/block_pagination.js';

const Category_products = (props) => {
    let data = props.data;
    const element = <div className="shop category-products">
        <div className="row" >
            {(data.rows.length > 0) ?
                <List_products data={data} />
                :
                <No_products data={data} />
            }
        </div>

        {(data.display_pagination == 1) ?
            <Block_pagination data={data} />
        : ''}
    </div>;

    return (element);
}
export default Category_products;
