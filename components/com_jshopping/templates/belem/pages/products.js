import React, { useState, useEffect } from '../../../js/react/node_modules/react';
import nl2br from '../../../js/react/node_modules/react-nl2br';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';
import Parser from '../../../js/react/node_modules/html-react-parser';
import Category_products from '../elements/category_products.js';
import List_products from '../elements/list_products.js';
import No_products from '../elements/no_products.js';
import Block_pagination from '../elements/block_pagination.js';
import { getProductsData as getProductsDataAction } from '../../../js/react/src/redux/modules/pageData';
import {connect} from "../../../js/react/node_modules/react-redux";

const Products = ({productsData, getProductsData}) => {
    var data = productsData;
    var key_id = 0;
    const [historyHref, setHref] = useState('');
    const updateHref = (value) => {
        setHref(value);
    }
    if (historyHref != window.location.href){
        getProductsData(window.location.href + '?ajax=1&ajax=1');
        updateHref(window.location.href);
    }
    let element = <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;

    if(data.component) {
        element = <div className="shop list-products">
            <h1 className="list-products__page-title">{data.title}</h1>

            <div className="row">
                {(data.rows.length > 0) ?
                   <List_products data={data} />
                    :
                    <No_products/>
                }
            </div>

            {(data.display_pagination == 1) ?
                <Block_pagination data={data}/>
                : ''}

        </div>;

    }
    return (element);
}
export default  connect(
    ({ productsData }) => ({ productsData: productsData.productsData }),
    {
        getProductsData: getProductsDataAction
    }
)(Products);

