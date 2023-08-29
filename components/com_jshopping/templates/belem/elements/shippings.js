import React, { useState } from '../../../js/react/node_modules/react';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import nl2br from '../../../js/react/node_modules/react-nl2br';
import shopQuickCheckout from '../../../js/src/controllers/qcheckout/index.js';
import Formatprice from '../elements/formatprice.js';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';

const Shippings = (props) => {
	let data = props.data;
	const result = Object.keys(data.shipping_methods).map((key) => data.shipping_methods[key]);
	function shippingFormActiveClass(shipping){
		if(shipping.sh_pr_method_id == data.active_shipping) return 'shipping_form_active'; else return '';
	}
	function shippingMethodChecked(shipping){
		if(shipping.sh_pr_method_id == data.active_shipping) return 'checked'; else return '';
	}
	const element = <div className="form-check" id="table_shippings">
		{result.map((shipping, ins) =>

		<div className="shipping" key={ins}>
			<div className="mr-1">
				<input className="form-check-input" type ="radio" name ="sh_pr_method_id" id ={"shipping_method_" + shipping.sh_pr_method_id} defaultChecked={shippingMethodChecked(shipping)} defaultValue={shipping.sh_pr_method_id}  onClick={(e) => shopQuickCheckout.showShipping(shipping.sh_pr_method_id)} />
			</div>

			<div className="mb-3 text-muted">
				<label className="form-check-label d-block text-body" htmlFor={"shipping_method_" + shipping.sh_pr_method_id}>
					{(shipping.image != '') ?
					<span className="shipping_image">
						<Image src={data.jshopConfig.image_shippings_live_path+'/'+shipping.image} alt={nl2br(shipping.name)} />
					</span>
					: ''}

					{shipping.name + ' (' }<Formatprice price={shipping.calculeprice} data={data} /> { ')'}
				</label>

				<div id={"shipping_form_" + shipping.sh_pr_method_id} className={"shipping_form " + shippingFormActiveClass(shipping)}>
					{shipping.form}
				</div>

				{shipping.description}

				{(shipping.delivery != null) ?
				<p className="mb-1">
					{Joomla.JText._('COM_SMARTSHOP_DELIVERY_TIME') + ': ' + shipping.delivery}
				</p>
				: ''}

				{(shipping.delivery_date_f != null) ?
				<p className="mb-1">
					{Joomla.JText._('COM_SMARTSHOP_DELIVERY_DATE') + ': ' + shipping.delivery_date_f}
				</p>
				: ''}
			</div>
		</div>
		)}
	</div>;
	return (element);
	}

	export default Shippings;