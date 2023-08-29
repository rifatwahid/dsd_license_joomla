import React, { useState, useEffect } from '../../../js/react/node_modules/react';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import Order from '../pages/order.js';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';
import {
    BrowserRouter as Router,
        Switch,
        Route,
        Link,
        useParams,withRouter
        ,useHistory,useLocation
} from '../../../js/react/node_modules/react-router-dom';


const Listorder = () => {
    const [text_search, setTextsearch] = useState(dataJson.text_search);
    const handleChangeTextsearch = e => {
        setTextsearch(e.target.value)
    }
    let updateData=(value)=> {
        setData(value);
    }


    const handleSubmit = event => {
        event.preventDefault();
        fetch(window.location.href + '?ajax=1' + '&ajax=1&text_search='+text_search, {
            method: "GET",
        }) .then(res => res.json())
            .then((result) => {
                updateData(result)  ;
            });
    }

    let ex;
    let ind;
    let arr;
    let history = useHistory();

    let [data, setData] = useState(dataJson);

    if(typeof text_search != 'undefined' && text_search.length > 0){
        var href = window.location.href + '?ajax=1' + '&ajax=1&text_search='+text_search;
    }else{
        var href = window.location.href + '?ajax=1' + '&ajax=1';
    }
    useEffect(() => {
       // document.getElementById('belem').innerHTML = "";
        var el = document.getElementById( 'belem' );
   //  if(el == "") {
         fetch(window.location.href + '?ajax=1' + '&ajax=1', {
             method: "GET",
         }).then(res => res.json())
             .then((result) => {
                 updateData(result);

             })
     }, []);

    let element = <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;
    if(data.orders) {
        element = <div className="shop order-list">
            <div className="row-fluid row pb-2">
                <div className="col-sm-12 col-md-6 col-xl-6 col-12 ">
                    <h1 className="order-list__page-title">
                        {Joomla.JText._('COM_SMARTSHOP_MY_ORDERS')}
                    </h1>
                </div>
                <div className="col-sm-12 col-md-6 col-xl-6 col-12 text-left">
                    <Form name="adminForm" id="adminForm" className="test" onSubmit={handleSubmit} method="post" action={data.linksearch}>
                        <div className="js-stools-container-bar text_right">
                            <div className="filter-search btn-group pull-left">
                                <Form.Control type="text" name="text_search" id="text_search"
                                              placeholder={Joomla.JText._('COM_SMARTSHOP_SEARCH')}
                                              onChange={handleChangeTextsearch} value={text_search}/>

                                <div className="btn-group pull-left hidden-phone">
                                    <Button variant="secondary" className="btn hasTooltip" type="submit"
                                            title={Joomla.JText._('COM_SMARTSHOP_SEARCH')}>
                                        <i className="fas fa-search"></i>
                                    </Button>
                                    <Button variant="secondary" className="btn hasTooltip"
                                            type="submit"
                                            name="clearInput"
                                            title={Joomla.JText._('COM_SMARTSHOP_CLEAR')}
                                            onClick={() => setTextsearch('')}>
                                        <i className="fas fa-window-close"></i>
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </Form>
                </div>
            </div>
            <div className="list-group">
                <div className="list-group-item d-none d-sm-block">
                    <div className="row">

                        <div className="col-sm-3">
                            {Joomla.JText._('COM_SMARTSHOP_ORDER_NUMBER')}
                        </div>

                        <div className="col-sm-3 text-center">
                            {Joomla.JText._('COM_SMARTSHOP_ORDER_DATE')}
                        </div>

                        <div className="col-sm-3 text-center">
                            {Joomla.JText._('COM_SMARTSHOP_ORDER_STATUS')}
                        </div>

                        <div className="col-sm-3 text-right">
                            {Joomla.JText._('COM_SMARTSHOP_ORDER_AMOUNT')}
                        </div>

                    </div>
                </div>

                {data.orders.map((order, ind) =>
                    // { result.map((order, ind) =>
                    (<div key={ind} id={ind}>
                            <Link to={order.order_href} className="list-group-item list-group-item-action">
                                <div className="row">
                                    <div className="col-sm-3">
                                        <span
                                            className="d-sm-none"> {order.order_href}{Joomla.JText._('COM_SMARTSHOP_ORDER_NUMBER')}:</span>
                                        {order.order_number}
                                    </div>

                                    <div className="col-sm-3 text-sm-center">
                                        <span className="d-sm-none">{Joomla.JText._('COM_SMARTSHOP_ORDER_DATE')}:</span>
                                        {order.order_date}
                                    </div>

                                    <div className="col-sm-3 text-sm-center">
                                        <span
                                            className="d-sm-none">{Joomla.JText._('COM_SMARTSHOP_ORDER_STATUS')}:</span>
                                        {order.status_name}
                                    </div>

                                    <div className="col-sm-3 text-sm-right">
                                        <span
                                            className="d-sm-none">{Joomla.JText._('COM_SMARTSHOP_ORDER_AMOUNT')}:</span>
                                        {order.total}
                                    </div>
                                </div>

                            </Link>

                        </div>
                    )
                )
                }

            </div>

        </div>;
    }
return (element);
}

export default withRouter(Listorder);