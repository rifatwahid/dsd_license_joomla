import React, { useState } from '../../../js/react/node_modules/react';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import ListGroup from '../../../js/react/node_modules/react-bootstrap/ListGroup';


const Availability_delivery_info = (props) => {
    let data = props.data;
    const renderPrintQty = ()=>{
        if((typeof data.product.qty_in_stock != 'undefined' && (data.product.qty_in_stock > 0 || data.product.qty_in_stock == 'INF')) || (typeof data.product.unlimited != 'undefined' && data.product.unlimited == 1))  {
            return Joomla.JText._('COM_SMARTSHOP_STOCK_AVAILABLE');

        }else if(data.config.hide_text_product_not_available == 0)
        {
            return Joomla.JText._('COM_SMARTSHOP_STOCK_NOT_AVAILABLE');
        }
    }

    const element =
        <ul variant="list-unstyled" className="list-unstyled">
            {(data.config.stock == 1 && data.config.product_show_qty_stock == 1) ?
            <li id="available-text" className={(!data.sprintQtyInStock <= 0) ? 'text-small avaliable-text text-success' : 'text-small avaliable-text text-danger'}>
                {renderPrintQty()}
            </li>

                : ''}
            {(data.config.stock > 0 && data.config.product_show_qty_stock > 0 && data.product.unlimited == 0) ?
                <li className={(data.sprintQtyInStock <= 0 || data.sprintQtyInStock == 'INF') ? 'text-small text-muted hidden' : 'text-small text-muted'}>
                    {Joomla.JText._('COM_SMARTSHOP_STOCK_QUANTITY')+': '}<span id="product_qty">{data.sprintQtyInStock}</span>
                </li>
            : ''}

            {(data.product.delivery_time.length > 0 && data.product.hide_delivery_time == 0 && data.config.delivery_times_on_product_page.length > 0) ?
            <li className="text-small text-muted">
                {Joomla.JText._('COM_SMARTSHOP_STOCK_DELIVERY_TIME') + ': '}{data.product.delivery_time}
            </li>
            : ''}

            {(data.production_time == 1) ?
                <li className={(data.product.production_time <= 0) ? "text-small text-muted hidden" : "text-small text-muted"}  >
                    {Joomla.JText._('COM_SMARTSHOP_PRODUCTION_TIME') + ' : '}<span id="production_time">{data.product.production_time + ' '}</span>{Joomla.JText._('COM_SMARTSHOP_DAYS')}
                </li>
            : ''}
        </ul>;

    return (element);
}

export default Availability_delivery_info;