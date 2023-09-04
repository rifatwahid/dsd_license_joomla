import React, { useState } from '../../../js/react/node_modules/react';
import Parser from '../../../js/react/node_modules/html-react-parser';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import Tabs from '../../../js/react/node_modules/react-bootstrap/Tabs';
import Tab from '../../../js/react/node_modules/react-bootstrap/Tab';
import nl2br from '../../../js/react/node_modules/react-nl2br';
import Image from '../../../js/react/node_modules/react-bootstrap/Image';
import Code from '../elements/code.js';
import Extra_fields from '../elements/extra_fields.js';


const Default_prod_tablist = (props) => {
    let data = props.data;
    const element =<Tabs defaultActiveKey="description">
            <Tab eventKey="description" title={Joomla.JText._('COM_SMARTSHOP_DESCRIPTION')}>
                <div id="description" className="tab-pane active show py-4">
                    <div id="description__text">
                        {Parser(data.product.description)}
                    </div>

                    <div id="description__readmore">
                        {(data.product.product_url.length > 0) ?
                        <a target="_blank" href={data.product.product_url}>
                            {Joomla.JText._('COM_SMARTSHOP_READ_MORE')}
                        </a>
                        : ''}
                    </div>
                </div>
            </Tab>
            <Tab eventKey="additional-information" title={Joomla.JText._('COM_SMARTSHOP_ADDITIONAL_INFORMATIONS')}>
                <div id="additional-information" className="py-4">
                    <ul className="list-unstyled">

                        <li id="product_code">
                            {(data.config.show_product_code == 1 && data.ean != '') ?
                                <Code />
                            : ''}
                        </li>

                        {(data.config.product_show_manufacturer == 1 && data.product.manufacturer_info.name.length > 0) ?
                        <li className="manufacturer_name">
                            {Joomla.JText._('COM_SMARTSHOP_MANUFACTURER') + ': '}<span>{data.product.manufacturer_info.name}</span>
                        </li>
                        : ''}

                        {(data.config.product_show_manufacturer_logo == 1 && data.product.manufacturer_info.manufacturer_logo != "") ?
                            <li className="manufacturer_logo">
                                <a href={data.manufacturer_link}>
                                    <Image src={data.config.image_manufs_live_path + "/" + data.product.manufacturer_info.manufacturer_logo}
                                         alt={nl2br(data.product.manufacturer_info.name)}
                                    title={nl2br(data.product.manufacturer_info.name)} />
                                </a>
                            </li>
                        : ''}

                        {(typeof data.product.vendor_info != 'undefined' && data.product.vendor_info != null) ?
                            <span>
                            <li>{Joomla.JText._('COM_SMARTSHOP_VENDOR') + ': '}
                                {data.product.vendor_info.shop_name}
                            </li>

                            {(data.config.product_show_vendor_detail == 1) ?
                                <span>
                                <li>
                                    <a href={data.product.vendor_info.urlinfo}>
                                        {Joomla.JText._('COM_SMARTSHOP_VENDOR_INFO')}
                                    </a>
                                </li>

                                <li>
                                    <a href={data.product.vendor_info.urllistproducts}>
                                        {Joomla.JText._('COM_SMARTSHOP_VENDOR_PRODUCTS')}
                                    </a>
                                </li>
                                </span>
                                : ''}
                            </span>
                        : ''}

                        {(data.config.product_show_weight == 1 && data.product.product_weight > 0) ?
                            <li id="product-weight">
                                <span
                                    className="product-weight__text">{Joomla.JText._('COM_SMARTSHOP_WEIGHT')}</span>:<span
                                className="product-weight__weight">{data.weight}</span>
                            </li>
                        : ''}

                        <div id="product-details__extra-fields">
                            <Extra_fields data={data}/>
                        </div>
                    </ul>
                </div>
            </Tab>
        </Tabs>;

    return (element);
}

export default Default_prod_tablist;