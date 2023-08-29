import React, {useEffect, useState} from '../../../js/react/node_modules/react';
import { Redirect, Link } from '../../../js/react/node_modules/react-router-dom';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import ListGroup from '../../../js/react/node_modules/react-bootstrap/ListGroup';
import nl2br from '../../../js/react/node_modules/react-nl2br';
import Parser from '../../../js/react/node_modules/html-react-parser';
import {  getFormSearchData as getFormSearchDataAction } from '../../../js/react/src/redux/modules/pageData';
import {connect} from "../../../js/react/node_modules/react-redux";
import Products_search from '../pages/products_search.js';
import Noresult_search  from '../pages/noresult_search.js';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';

const Form_search = ({formSearchData, getFormSearchData}) => {
    let data = formSearchData;
    useEffect(() => {
        getFormSearchData(window.location.href + '?ajax=1&ajax=1');
    }, []);
    let [dataSend, setData] = useState({});
    let updateData=(value)=> {
        setData(value);
    }
    const handleSubmit = (event) => {
        const form = event.currentTarget;
            event.preventDefault();
            var queryString = jQuery('#search-form').serialize();
            fetch(data.action + '?ajax=1&ajax=1', {
                method: "POST",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: queryString
            }).then(res => res.json())
                .then((result) => {
                    updateData(result);
                })
    };
    if(dataSend.component == "Products_search"){
        return <Products_search data={dataSend} />;
    }else if(dataSend.component == "Noresult_search"){
         return <Noresult_search data={dataSend} />;
    }
    let element = <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;

    if(data.component) {
        element = <div className="shop search-form">

            <h1 className="search-form__page-title">
                {Joomla.JText._('COM_SMARTSHOP_SEARCH')}
            </h1>

            <Form action={data.action} name="form_ad_search" method="post"
                  id="search-form" onSubmit={handleSubmit}
                  className="form-horizontal">
                <input type="hidden" name="setsearchdata" value="1"/>

                <div className="form-group row">
                    <label htmlFor="search" className="col-sm-5 col-md-4 col-lg-3 col-form-label">
                        {Joomla.JText._('COM_SMARTSHOP_SEARCH_TERM')}
                    </label>

                    <div className="col-sm-7 col-md-8 col-lg-9">
                        <input type="text" name="search" id="search"
                               placeholder={Joomla.JText._('COM_SMARTSHOP_SEARCH_TERM')} className="input"/>
                    </div>
                </div>

                <div className="form-group row">
                    <div className="col-sm-5 col-md-4 col-lg-3">
                        {Joomla.JText._('COM_SMARTSHOP_SEARCH_TYPE')}
                    </div>

                    <div className="col-sm-7 col-md-8 col-lg-9">
                        <div className="form-check form-check-inline">
                            <Form.Check
                                custom
                                type='radio'
                                name="search_type" id="search_type_any"
                                defaultValue="any" defaultChecked="checked"
                                label={Joomla.JText._('COM_SMARTSHOP_SEARCH_ANY')}
                            />
                        </div>

                        <div className="form-check form-check-inline">
                            <Form.Check
                                custom
                                type='radio'
                                name="search_type" id="search_type_all"
                                defaultValue="all" defaultChecked="checked"
                                label={Joomla.JText._('COM_SMARTSHOP_SEARCH_ALL')}
                            />
                        </div>

                        <div className="form-check form-check-inline">
                            <Form.Check
                                custom
                                type='radio'
                                name="search_type" id="search_type_exact"
                                defaultValue="exact" defaultChecked="checked"
                                label={Joomla.JText._('COM_SMARTSHOP_SEARCH_EXACT')}
                            />
                        </div>
                    </div>
                </div>

                <div className="form-group row">
                    <label htmlFor="category_id" className="col-sm-5 col-md-4 col-lg-3 col-form-label">
                        {Joomla.JText._('COM_SMARTSHOP_CATEGORIES')}
                    </label>

                    <div className="col-sm-7 col-md-8 col-lg-9">
                        <input type="checkbox" name="include_subcat" id="include_subcat" defaultValue="1"
                               className="input"/>
                        <label htmlFor="include_subcat">

                        </label>
                    </div>
                </div>

                <div className="form-group row">
                    <label htmlFor="manufacturer_id" className="col-sm-5 col-md-4 col-lg-3 col-form-label">
                        {Joomla.JText._('COM_SMARTSHOP_MANUFACTURERS')}
                    </label>

                    <div className="col-sm-7 col-md-8 col-lg-9"
                         dangerouslySetInnerHTML={{__html: data.list_manufacturers}}/>

                </div>

                <div className="form-group row">
                    <label htmlFor="price_from" className="col-sm-5 col-md-4 col-lg-3 col-form-label">
                        {Joomla.JText._('COM_SMARTSHOP_SEARCH_PRICE_START')}
                        {'(' + data.config.currency_code + ')'}
                    </label>

                    <div className="col-sm-7 col-md-8 col-lg-9">
                        <input type="text" name="price_from" id="price_from"
                               placeholder={Joomla.JText._('COM_SMARTSHOP_SEARCH_PRICE_START')}
                               className="input"/>
                    </div>
                </div>

                <div className="form-group row">
                    <label htmlFor="price_to" className="col-sm-5 col-md-4 col-lg-3 col-form-label">
                        {Joomla.JText._('COM_SMARTSHOP_SEARCH_PRICE_END')}
                        {'(' + data.config.currency_code + ')'}
                    </label>

                    <div className="col-sm-7 col-md-8 col-lg-9">
                        <input type="text" name="price_to" id="price_to"
                               placeholder={Joomla.JText._('COM_SMARTSHOP_SEARCH_PRICE_END')}
                               className="input"/>
                    </div>
                </div>

                <div className="form-group row">
                    <div className="col-sm-7 col-md-8 col-lg-9 offset-sm-5 offset-md-4 offset-lg-3">
                        <Button type="submit" variant="outline-primary"
                                className="btn-block col-md-6 float-right">{Joomla.JText._('COM_SMARTSHOP_SEARCHING')}
                        </Button>
                        {/*<Link className="btn-block col-md-6 float-right" to={() => <Products_search data={jQuery('#search-form').serialize()} />} >*/}
                        {/*    {Joomla.JText._('COM_SMARTSHOP_SEARCHING')}*/}
                        {/*</Link>*/}
                    </div>
                </div>

            </Form>
        </div>;

        return (element);
    }else{
        return '';
    }
}
export default  connect(
    ({ formSearchData }) => ({ formSearchData: formSearchData.formSearchData }),
    {
        getFormSearchData: getFormSearchDataAction
    }
)(Form_search);
