import React, { useState, useEffect } from '../../../js/react/node_modules/react';
import { Lines } from '../../../js/react/node_modules/react-preloaders';
import nl2br from '../../../js/react/node_modules/react-nl2br';
import Parser from '../../../js/react/node_modules/html-react-parser';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import ListGroup from '../../../js/react/node_modules/react-bootstrap/ListGroup';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';
import Order_upload from '../elements/order_upload.js';
import queryString from '../../../js/react/node_modules/query-string';
import {
    BrowserRouter as Router,
    Switch,
    Route,
    Link,
    useParams,withRouter
    ,useHistory
} from '../../../js/react/node_modules/react-router-dom';

const Order = (params) => {

   // let ps = queryString.parse(params.location.search);
    let [data, setData] = useState({});
    let updateData=(value)=> {
        setData(value);
    }
    useEffect(() => {
        fetch(window.location.href  + '?ajax=1&ajax=1', {
            method: "GET",
        }) .then(res => res.json())
            .then((result) => {
                updateData(result)  ;
            })},
        []);
   
    let files = {};
    let element = <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;
    if(data.component) {
        element = <div className="shop order-details">
            <h1 className="order-details__page-title">{Joomla.JText._('COM_SMARTSHOP_ORDER')} {data.order.order_number}</h1>
            <div className="row my-4">
                <div className="col-sm">
                    <ListGroup as="ul" variant="flush">
                        <ListGroup.Item as="li" className="border-0 p-0"><span
                            className="font-weight-bold">{Joomla.JText._('COM_SMARTSHOP_ORDER_DATE')}:</span> {data.order_date}
                        </ListGroup.Item>
                        <ListGroup.Item as="li" className="border-0 p-0"><span
                            className="font-weight-bold">{Joomla.JText._('COM_SMARTSHOP_ORDER_STATUS')}:</span> {data.order.status_name}
                        </ListGroup.Item>
                    </ListGroup>
                </div>
                <div className="col-sm">
                    {(data.order.reorder == 1) ?
                        <a href={data.reorderLink}
                           className="btn btn-outline-secondary">{Joomla.JText._('COM_SMARTSHOP_REPEAT_ORDER')}</a>
                        : ''}
                    {(data.order.pdf_file.length > 0) ? <a href={data.urlToInvoice}
                                                           className="btn btn-outline-secondary float-sm-right">{Joomla.JText._('COM_SMARTSHOP_ORDER_DOWNLOAD_BILL')}</a> : ''}

                </div>
            </div>
            <div className="row">
                <div className="col-sm">
                    <h5>{Joomla.JText._('COM_SMARTSHOP_BILL_TO')}:</h5>
                    <ListGroup as="ul" variant="flush">
                        {(data.config_fields['firma_name']['display'] && data.order.firma_name != "") ?
                            <ListGroup.Item as="li" className="border-0 p-0">{data.order.firma_name}</ListGroup.Item>
                            : ''}

                        {(data.config_fields['f_name']['display'] && data.order.f_name != "" || data.config_fields['m_name']['display'] && data.order.m_name != "" || data.config_fields['l_name']['display'] && data.order.l_name != "") ?
                            <ListGroup.Item as="li"
                                            className="border-0 p-0">{data.order.f_name} {data.order.m_name} {data.order.l_name}</ListGroup.Item> : ''
                        }

                        {(data.config_fields['home']['display'] && data.order.home != "") ?
                            <ListGroup.Item as="li" className="border-0 p-0">{data.order.home}</ListGroup.Item> : ''
                        }

                        {(data.config_fields['apartment']['display'] && data.order.apartment != "") ?
                            <ListGroup.Item as="li"
                                            className="border-0 p-0">{data.order.apartment}</ListGroup.Item> : ''
                        }

                        {(data.config_fields['street']['display'] && data.order.street != "") ?
                            <ListGroup.Item as="li"
                                            className="border-0 p-0">{data.order.street + ' ' + data.order.street_nr}</ListGroup.Item> : ''
                        }

                        {(data.config_fields['zip']['display'] && data.order.zip != "" || data.config_fields['city']['display'] && data.order.city != "") ?
                            <ListGroup.Item as="li"
                                            className="border-0 p-0">{data.order.zip} {data.order.city}</ListGroup.Item> : ''
                        }

                        {(data.config_fields['state']['display'] && data.order.state != "") ?
                            <ListGroup.Item as="li" className="border-0 p-0">{data.order.state}</ListGroup.Item> : ''
                        }

                        {(data.config_fields['country']['display'] && data.order.country != "") ?
                            <ListGroup.Item as="li" className="border-0 p-0">{data.order.country}</ListGroup.Item> : ''
                        }
                    </ListGroup>
                </div>

                <div className="col-sm">
                    <h5>{Joomla.JText._('COM_SMARTSHOP_SHIPPING_TO')}:</h5>

                    <ListGroup as="ul" variant="flush">
                        {(data.config_fields['firma_name']['display'] && data.order.d_firma_name != '') ?
                            <ListGroup.Item as="li"
                                            className="border-0 p-0">{data.order.d_firma_name}</ListGroup.Item> : ''
                        }

                        {(data.config_fields['f_name']['display'] && data.order.d_f_name != '' || data.config_fields['m_name']['display'] && data.order.d_m_name != '' || data.config_fields['l_name']['display'] && data.order.d_l_name != '') ?
                            <ListGroup.Item as="li" className="border-0 p-0">{data.order.d_f_name} {data.order.d_m_name}
                                {data.order.d_l_name}</ListGroup.Item> : ''
                        }

                        {(data.config_fields['home']['display'] && data.order.d_home != '') ?
                            <ListGroup.Item as="li" className="border-0 p-0">{data.order.d_home}</ListGroup.Item> : ''
                        }

                        {(data.config_fields['apartment']['display'] && data.order.d_apartment != '') ?
                            <ListGroup.Item as="li"
                                            className="border-0 p-0">{data.order.d_apartment}</ListGroup.Item> : ''
                        }

                        {(data.config_fields['street']['display'] && data.order.d_street != '') ?
                            <ListGroup.Item as="li"
                                            className="border-0 p-0">{data.order.d_street + ' ' + data.order.d_street_nr}</ListGroup.Item> : ''
                        }

                        {(data.config_fields['zip']['display'] && data.order.d_zip != '' || data.config_fields['city']['display'] && data.order.d_city != '') ?
                            <ListGroup.Item as="li"
                                            className="border-0 p-0">{data.order.d_zip} {data.order.d_city}</ListGroup.Item> : ''
                        }

                        {(data.config_fields['state']['display'] && data.order.d_state != '') ?
                            <ListGroup.Item as="li" className="border-0 p-0">{data.order.d_state}</ListGroup.Item> : ''
                        }

                        {(data.config_fields['country']['display'] && data.order.d_country != '') ?
                            <ListGroup.Item as="li"
                                            className="border-0 p-0">{data.order.d_country}</ListGroup.Item> : ''
                        }
                    </ListGroup>
                </div>

            </div>
            {/*end row*/}
            <Form action={data.link_save_order_upload} id="updateCartForm" method="post" name="updateCart">
                <div className="row">
                    <div className="col order-md-2">
                        {(data.isUpoad) ?
                            <li className="list-group-item pl-0 pr-0 border-0">
                                <input type="submit" className="btn btn-outline-primary btn-block"
                                       value={Joomla.JText._('COM_SMARTSHOP_SAVE_UPLOADS')}/>
                            </li>
                            : ''}
                    </div>
                    <div className="col-md-7 col-lg-8 order-md-1"></div>
                </div>
                <ListGroup as="ul" className="my-4">
                    {data.order.items.map((prod, ind) =>
                        <li as="li" key={ind} className="list-group-item py-3">
                            <div className="media">
                                <div className="media-body">
                                    <div className="row">
                                        <div className="col-md-4 col-lg-3">
                                            <Image className="mr-3 order-thumbnail" src={prod.urlToThumbImage}
                                                   alt={prod.product_name}/>
                                        </div>

                                        <div className="col-md-8 col-lg-9">

                                            <h5 className="mt-0 mb-1">{prod.product_name}</h5>

                                            <ul variant="flush" className="list-unstyled">
                                                {(data.config.show_product_code_in_order && prod.product_ean != "") ?
                                                    <ListGroup.Item as="li" className="border-0 p-0 text-muted small">
                                                        {Joomla.JText._('COM_SMARTSHOP_PRODUCT_CODE')}:
                                                        {prod.product_ean}</ListGroup.Item> : ''
                                                }

                                                {(prod.manufacturer != "") ?
                                                    <ListGroup.Item as="li"
                                                                    className="border-0 p-0 text-muted small">{Joomla.JText._('COM_SMARTSHOP_MANUFACTURER')}:
                                                        {prod.manufacturer}</ListGroup.Item> : ''
                                                }

                                                {(prod.product_attributes || prod.product_freeattributes || prod.display_extra_fields) ?
                                                    <ListGroup.Item as="li" className="border-0 p-0 text-muted small">
                                                        {(prod.product_attributes) ?
                                                            <div
                                                                className="list_attribute">{nl2br(prod.product_attributes)}</div> : ''
                                                        }
                                                        {(prod._mirror_editor_data) ?
                                                            <div
                                                                className="list_attribute">{nl2br(prod._mirror_editor_data)}</div> : ''
                                                        }
                                                        {(prod.product_freeattributes) ?
                                                            <div
                                                                className="list_free_attribute">{nl2br(prod.product_freeattributes)}</div> : ''
                                                        }
                                                        {(prod.extra_fields) ?
                                                            <div
                                                                className="list_extra_field">{nl2br(Parser(prod.display_extra_fields))}</div> : ''
                                                        }
                                                    </ListGroup.Item> : ''
                                                }

                                                {(prod.files.length > 0 && data.isOrderHasBeenPaid != 0) ?
                                                    <ListGroup.Item as="li" className="border-0 p-0 filelist">
                                                        {prod.files.map((file, ind1) =>
                                                                (file.file != '') ?
                                                                    <div className="file" key={ind1}>
                                                        <span className="descr">
                                                            {file.file_descr}
                                                        </span>
                                                                        <a className="download"
                                                                           href={data.juri_root + 'index.php?option=com_jshopping&controller=product&task=getfile&oid=' + data.order.order_id + '&id=' + file.id + '&hash=' + data.order.file_hash}>
                                                                            {Joomla.JText._('COM_SMARTSHOP_DOWNLOAD')}
                                                                        </a>
                                                                    </div>
                                                                    : ''
                                                        )}
                                                    </ListGroup.Item> : ''}

                                                <li className="border-0 p-0">{Joomla.JText._('COM_SMARTSHOP_PRICE')}:
                                                    {prod.formatprice} </li>
                                                <li className="border-0 p-0">
                                                    {(!data.isUpoad && prod.uploadDataBlock) ?
                                                        Parser(prod.uploadDataBlock)
                                                        :
                                                        <Order_upload prod={prod} jdata={data} key_id={prod.order_item_id}/>
                                                    }
                                                </li>
                                            </ul>

                                            <ul variant="flush" className="mt-4 list-unstyled">
                                                <li className="border-0 p-0">{Joomla.JText._('COM_SMARTSHOP_COUNT')}: <span
                                                    className="float-md-right">{parseFloat(prod.product_quantity)}</span>
                                                </li>
                                                <li className="border-0 p-0">{Joomla.JText._('COM_SMARTSHOP_PRICE')}: <span
                                                    className="float-md-right">{prod.formatprice_quantity}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name={"quantity[" + ind + "]"} id={"quantity[" + ind + "]"}
                                   value={prod.product_quantity}/>
                        </li>
                    )}
                </ListGroup>
                <input type="hidden" name="order_id" value={data.order.order_id}/>
            </Form>
            <div className="row">
                <div className="col order-md-2">
                    <ListGroup as="ul">
                        {(!data.hide_subtotal || data.hide_subtotal == 0) ?
                            <ListGroup.Item as="li">{Joomla.JText._('COM_SMARTSHOP_SUBTOTAL')}: <span
                                className="float-right">{data.order_subtotal}</span>
                            </ListGroup.Item>
                            : ''
                        }

                        {(data.order.order_discount > 0) ?
                            <ListGroup.Item as="li">{Joomla.JText._('COM_SMARTSHOP_DISCOUNT')}: <span
                                className="float-right">{data.order_discount}</span>
                            </ListGroup.Item>
                            : ''
                        }

                        {(data.config.without_shipping == 0 || data.order.order_shipping > 0) ?
                            <ListGroup.Item as="li">{Joomla.JText._('COM_SMARTSHOP_SHIPPING_COSTS')}: <span
                                className="float-right">{data.order_shipping}</span>
                            </ListGroup.Item> : ''
                        }

                        {(data.config.without_shipping == 0 && (data.order.order_package > 0 || data.config.display_null_package_price > 0)) ?
                            <ListGroup.Item as="li">{Joomla.JText._('COM_SMARTSHOP_PACKAGE_PRICE')}: <span
                                className="float-right">{data.order_package}</span>
                            </ListGroup.Item> : ''
                        }

                        {(data.order.payment_name == 0) ?
                            <ListGroup.Item as="li">{data.order.payment_name}: <span
                                className="float-right">{data.order_payment}</span>
                            </ListGroup.Item> : ''
                        }

                        {(data.config.hide_tax == 0) ?
                            data.order.order_tax_list_format.map((value, ind2) => (
                                    <ListGroup.Item key={ind2} as="li">{data.displayTotalCartTaxName}
                                        {(data.show_percent_tax) ? value['percent'] + '%'
                                            : ''
                                        }:
                                        <span className="float-right">{value['value']}</span>
                                    </ListGroup.Item>
                                )
                            )

                            : ''
                        }
                        {data._tmp_ext_html_user_ordershow_after_total_tax}

                        <ListGroup.Item as="li">{Joomla.JText._('COM_SMARTSHOP_ORDER_TOTAL')}: <span
                            className="float-right">{data.order_total}</span>
                        </ListGroup.Item>
                    </ListGroup>
                </div>

                <div className="col-md-7 col-lg-8 order-md-1">
                    <ListGroup as="ul" variant="flush">
                        {(!data.config.without_payment || data.config.without_payment == 0) ?
                            <ListGroup.Item as="li" className="mb-3 border-0 p-0"><span
                                className="font-weight-bold d-block">{Joomla.JText._('COM_SMARTSHOP_PAYMENT')}:</span>{data.order.payment_name}
                            </ListGroup.Item> : ''
                        }

                        {(!data.config.without_shipping || data.config.without_shipping == 0) ?
                            <ListGroup.Item as="li" className="mb-3 border-0 p-0"><span
                                className="font-weight-bold d-block">{Joomla.JText._('COM_SMARTSHOP_SHIPPING')}:</span>{nl2br(data.order.shipping_info)}
                            </ListGroup.Item> : ''
                        }

                        {(data.config.show_weight_order == 1) ?
                            <ListGroup.Item as="li" className="mb-3 border-0 p-0"><span
                                className="font-weight-bold d-block">{Joomla.JText._('COM_SMARTSHOP_WEIGHT')}:</span>{data.weight}
                            </ListGroup.Item> : ''
                        }

                        {(data.order.order_add_info) ?
                            <ListGroup.Item as="li" className="mb-3 border-0 p-0"><span
                                className="font-weight-bold d-block">{Joomla.JText._('COM_SMARTSHOP_COMMENT')}:</span>{data.order_add_info}
                            </ListGroup.Item> : ''
                        }
                    </ListGroup>

                    <ListGroup as="ul" variant="flush">
                    <span
                        className="font-weight-bold d-block">{Joomla.JText._('COM_SMARTSHOP_ORDER_HISTORY')}:</span>

                        {data.order.history.map((hist, ind) =>
                            (<ListGroup.Item as="li" key={ind}
                                             className="border-0 p-0 m-0">{hist.formatdate} - {hist.status_name}</ListGroup.Item>)
                        )}
                    </ListGroup>

                    {(data.allow_cancel && !data.isDisabledCancelOrder) ?
                        <a href={data.cancelOrderLink}
                           className="text-danger">{Joomla.JText._('COM_SMARTSHOP_ORDER_CANCEL')}</a>
                        : ''
                    }
                </div>
            </div>

        </div>;
    }
return (element);
}

export default Order;