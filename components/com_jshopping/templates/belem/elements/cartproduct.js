import React, { useState } from '../../../js/react/node_modules/react';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';
import nl2br from '../../../js/react/node_modules/react-nl2br';
import ListGroup from '../../../js/react/node_modules/react-bootstrap/ListGroup';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Parser from '../../../js/react/node_modules/html-react-parser';
import Formatprice from '../elements/formatprice.js';
import Producttaxinfo from '../elements/producttaxinfo.js';
import Fast_admin_links from '../elements/fast_admin_links.js';
import Cart_upload from '../elements/cart_upload.js';
import Printselectquantitycart from '../elements/printselectquantitycart.js';
import Displaytotalcarttaxname from '../elements/displaytotalcarttaxname.js';
import Formattax from '../elements/formattax.js';
import Sprintpreviewnativeuploadedfiles from '../elements/sprintpreviewnativeuploadedfiles.js';
import Sprint_atribute from '../elements/sprint_atribute.js';
import uploadImage from '../../../js/src/common/upload_image/index.js';

const Cartproduct = (data) => {
	let prod = data.prod;
	let dataJson = data.data;


	const element = <ListGroup.Item as="li" key={data.key_id} className="list-group-item  cart-products__item">
		<div className="row">
			<div className="col-sm-3">
				<Image className="img-fluid img-cart" src={prod['image']}
						   alt={nl2br(prod['product_name'])}/>
			</div>

			<div className="col">
				<div className="media-body row">
					<div className="col-md-6 col-lg-7">
						<ListGroup as="ul" variant="flush" className="list-unstyled">

							<ListGroup.Item as="li" className="list-group-item font-weight-bold form-control border-0 pl-0">
								{prod['product_name']}
								{(prod['uploadData'] != null && prod.sprintPreviewNativeUploadedFiles != null) ?
									 Parser(prod.sprintPreviewNativeUploadedFiles)
									: ''}
							</ListGroup.Item>

							{(dataJson.config.show_product_code_in_cart == 1 && prod['ean'] != "" ) ?
								<ListGroup.Item as="li" className="list-group-item text-muted small border-0 m-0 p-0">
									{Joomla.JText._('COM_SMARTSHOP_PRODUCT_CODE')}:
									{prod['ean']}
								</ListGroup.Item>
								: ''}

							{ (dataJson.config.show_product_manufacturer_in_cart == 1 && prod['manufacturer_info']['name'] != null) ?
								<ListGroup.Item as="li" className="list-group-item manufacturer_name  border-0 m-0 p-0">
									{Joomla.JText._('COM_SMARTSHOP_MANUFACTURER')}:
									<span>{prod['manufacturer_info']['name']}</span>
								</ListGroup.Item>
								: ''}

							{(prod['manufacturer'] != '') ?
								<ListGroup.Item as="li" className="list-group-item text-muted small  border-0 m-0 p-0">
									{Joomla.JText._('COM_SMARTSHOP_MANUFACTURER')}:
									{prod['manufacturer']}
								</ListGroup.Item>
								: ''}
							{(prod['editor_attr'].length > 0) ?
								<ListGroup.Item as="li" className="list-group-item list_attribute  border-0 m-0 p-0">
									prod['editor_attr'].map((val, ind){
									<p className="jshop_cart_attribute"><span className="name">{val}</span></p>
								} )
								</ListGroup.Item>
								: ''}

							{(prod.attributes_display != null && typeof prod.attributes_display != 'undefined' && prod.attributes_display != '') ?
								//Parser(prod.attributes_display)
								<Sprint_atribute atribute={prod.attributes_display} />
							: ''}
							{(prod._mirror_editor_display != null && typeof prod._mirror_editor_display != 'undefined' && prod._mirror_editor_display != '') ?
								Parser(prod._mirror_editor_display)
							: ''}
							{(typeof prod.free_attributes_display != 'undefined' && prod.free_attributes_display != '' && prod.free_attributes_display != null) ?
								Parser(prod.free_attributes_display)
							: ''}
							{(prod.extra_fields_display != null && typeof prod.extra_fields_display != 'undefined' && prod.extra_fields_display != '') ?
								Parser(prod.extra_fields_display)
							: ''}
							{(dataJson.config.show_delivery_time_step5 == 1 && prod['delivery_times_id'] != 0) ?
								<ListGroup.Item as="li" className="list-group-item text-muted small border-0 m-0 p-0">
									{Joomla.JText._('COM_SMARTSHOP_DELIVERY_TIME')}: {dataJson.deliverytimes[prod['delivery_times_id']]}
								</ListGroup.Item>
								: ''}

							{(dataJson.production_time != null && prod['production_time'] > 0) ?
								<ListGroup.Item as="li" className="list-group-item text-small text-muted border-0 m-0 p-0">
									{Joomla.JText._('COM_SMARTSHOP_PRODUCTION_TIME')}: {prod['production_time']+' '+Joomla.JText._('COM_SMARTSHOP_DAYS') }
								</ListGroup.Item>
								: '' }

						</ListGroup>
					</div>

					<div className="col-md-3 col-lg-2 md-text-center">
						<span
							className="d-md-none">{Joomla.JText._('COM_SMARTSHOP_QUANTITY')}:</span> {prod['quantity']}
					</div>


					<div className="col-md-3 form-control border-0 text-md-right smartshop_cart_price_tax_cell">
						<span className="d-block">
							<Formatprice price={(prod['price1']) ? prod['price1'] : prod['total_price']} data={dataJson} link={dataJson.price_format_link} />
						</span>

						{(dataJson.config.show_tax_product_in_cart == 1) ?
							<div>
							<span>
								<span className="d-block mt-1">
									<Producttaxinfo tax={prod['tax']} link={dataJson.tax_info_link} />
								</span>
								{(typeof dataJson._tmp_ext_html_after_show_product_tax != 'undefined') ?
									dataJson._tmp_ext_html_after_show_product_tax[data.key_id]
								: ''}
							</span>

						<span className={(!dataJson.config.single_item_price || dataJson.config.single_item_price == 0) ? "small text-muted hidden" : "small text-muted" }>
							<Formatprice price={(prod['price']) ? prod['price'] : prod['aprice']} link={dataJson.price_format_link} data={dataJson}/>
						</span>
						{(dataJson.config.show_tax_product_in_cart = 1 && dataJson.config.single_item_price == 1) ?
							<span className="small text-muted d-block mt-1">
                                <Producttaxinfo tax={prod['tax']} link={dataJson.tax_info_link} />
								{dataJson._tmp_ext_html_after_show_product_tax_single_item_price}
                            </span>
							: '' }
						{(dataJson.config.cart_basic_price_show == 1 && prod['basicprice'] > 0 && dataJson.config.config.single_item_price == 1) ?
							<span className="small text-muted d-block mt-1">
                                            <Sprintbasicprice prod={prod} link={dataJson.sprintbasicprice_link} />
										</span>
							: '' }
					</div>
: ''}

				</div>
			</div>
		</div>
		</div>
	</ListGroup.Item>;
	return (element);
	}

	export default Cartproduct;