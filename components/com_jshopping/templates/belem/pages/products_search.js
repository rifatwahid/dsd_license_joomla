import React, { useState } from '../../../js/react/node_modules/react';
import nl2br from '../../../js/react/node_modules/react-nl2br';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';
import Parser from '../../../js/react/node_modules/html-react-parser';
import Category_products from '../elements/category_products.js';
import List_products from '../elements/list_products.js';
import No_products from '../elements/no_products.js';
import Block_pagination from '../elements/block_pagination.js';

const Products_search = (props) => {
    let data = props.data;
    let element = <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;

    if(data.component) {
        element = <div className="shop search-results">
            <h1 className="search-results__page-title">
                {Joomla.JText._('COM_SMARTSHOP_SEARCH')}
            </h1>
            <p>
                {Joomla.JText._('COM_SMARTSHOP_SEARCH_RESULTS')}{' "' + data.search + '"'}
            </p>
            <div className="row">
                <List_products data={data}/>
            </div>

            {(data.display_pagination == 1) ?
                <div className="search-results__pagination">
                    <Block_pagination data={data}/>
                </div>
                : ''}

        </div>;
    }

    return (element);
}
export default Products_search;
