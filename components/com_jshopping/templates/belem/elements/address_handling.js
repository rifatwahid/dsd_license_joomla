import React, { useState } from '../../../js/react/node_modules/react';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import Renderbutton from '../elements/renderbutton.js';
import Renderwindow from '../elements/renderwindow.js';
import Parser from '../../../js/react/node_modules/html-react-parser';

const Address_handling = (props) => {
	let data = props.data;
	let addresses = {'street' : [
			data.user.street,
			data.user.street_nr
		],
			'city': [
				data.user.zip,
				data.user.city,
			],
			'country' : [
				data.user.country
			]
};
	let link = '/index.php?option=com_jshopping&controller=user&task=addressPopup';

	function isThereAtLeastOneNotEmpty(arr, type) {
		let isThereAtLeastOneNotEmpty = false;

		if (typeof arr != 'undefined' && arr.length > 0 && data.config_fields[type]['display']) {
			arr.forEach(function (item) {
					if(item != 'null') {
						isThereAtLeastOneNotEmpty = true;
					}
				}
			);
		}

		return isThereAtLeastOneNotEmpty;
	}
	const element =
		 (data.countOfUserAddresses != 'null') ?
	 <div id="qc_address">
		 <legend>
			 {Joomla.JText._('COM_SMARTSHOP_ADDRESS')}
		 </legend>
	<fieldset className="form-group billingAddress mb-4">
		<legend className="billingAddress__title">
			{Joomla.JText._('COM_SMARTSHOP_BILL_ADDRESS')}
		</legend>

		{(data.config_fields['f_name']['display'] || data.config_fields['l_name']['display']) ?
			<p className="billingAddress__name">
				{(data.config_fields['f_name']['display']) ? data.user.f_name + ' ' : ''}
				{(data.config_fields['l_name']['display']) ? data.user.l_name: ''}
			</p>
		: ''}


		<p className="billingAddress__addresses">
			{(data.config_fields['street']['display']) ?
				<span className="billingAddress__street">
					{data.user.street}
				</span>
			: ''
			}

			{(data.config_fields['street_nr']['display']) ?
				<span className="billingAddress__street_nr">
					{data.user.street_nr}
				</span>
			: ''}

			{(isThereAtLeastOneNotEmpty(addresses['street'], 'street') && (isThereAtLeastOneNotEmpty(addresses['city'], 'city')  || isThereAtLeastOneNotEmpty(addresses['country'], 'country'))) ?
			<span className="address-comma">,</span>
			: ''}
			{(data.config_fields['zip']['display']) ?
				<span className="billingAddress__zip">
					{data.user.zip}
				</span>
			: ''}
			{(data.config_fields['city']['display']) ?
				<span className="billingAddress__city">
					{data.user.city}
				</span>
			: ''}
			{(isThereAtLeastOneNotEmpty(addresses['country'], 'country') && (isThereAtLeastOneNotEmpty(addresses['city'],'city')  || isThereAtLeastOneNotEmpty(addresses['street'], 'street'))) ?
			<span className="address-comma">,</span>
			: ''}

			{(data.config_fields['country']['display']) ?
				<span className="billingAddress__country">
					{data.user.country}
				</span>
			: ''}
		</p>

		<Renderbutton btnId="billingAddress" dataTarget="userAddressesPopup" addrType='billing' btnText={Joomla.JText._('COM_SMARTSHOP_CHANGE_ADDRESS')}/>

		<input type="hidden" name="billingAddress_id" value={data.user.address_id} />
	</fieldset>

	<fieldset className="form-group shippingAddress mb-4">
		<legend className="shippingAddress__title">
			{Joomla.JText._('COM_SMARTSHOP_SHIPPING_ADDRESS')}
		</legend>

		{(data.config_fields['f_name']['display'] || data.config_fields['l_name']['display']) ?
			<p className="shippingAddress__name">
				{(data.config_fields['f_name']['display']) ? data.user.f_name + ' ' : ''}
				{(data.config_fields['l_name']['display']) ? data.user.l_name: ''}
			</p>
			: ''}

		<p className="shippingAddress__addresses">
			{(data.config_fields['street']['display']) ?
				<span className="shippingAddress__street">
					{data.user.street}
				</span>
				: ''
			}

			{(data.config_fields['street_nr']['display']) ?
				<span className="shippingAddress__street_nr">
					{data.user.street_nr}
				</span>
				: ''
			}
			{(isThereAtLeastOneNotEmpty(addresses['street'], 'street') && (isThereAtLeastOneNotEmpty(addresses['city'], 'city')  || isThereAtLeastOneNotEmpty(addresses['country'], 'country'))) ?
			<span className="address-comma">,</span>
			: ''}

			{(data.config_fields['zip']['display']) ?
				<span className="shippingAddress__zip">
					{data.user.zip}
				</span>
				: ''
			}
			{(data.config_fields['city']['display']) ?
				<span className="shippingAddress__city">
					{data.user.city}
				</span>
				: ''
			}

			{(isThereAtLeastOneNotEmpty(addresses['country'],'country') && (isThereAtLeastOneNotEmpty(addresses['city'],'city')  || isThereAtLeastOneNotEmpty(addresses['street'], 'street'))) ?
			<span className="address-comma">,</span>
			: ''}

			{(data.config_fields['country']['display']) ?
				<span className="shippingAddress__country">
					{data.user.country}
				</span>
				: ''
			}
		</p>

		<Renderbutton btnId="shippingAddress" dataTarget="userAddressesPopup" addrType='shipping' btnText={Joomla.JText._('COM_SMARTSHOP_CHANGE_ADDRESS')}/>

		<input type="hidden" name="shippingAddress_id" value={data.user.address_id} />
	</fieldset>
	<Renderwindow modalId="userAddressesPopup" modalTitle={Joomla.JText._('COM_SMARTSHOP_CHANGE_ADDRESS')} modalBody='<iframe src="/index.php?option=com_jshopping&controller=user&task=addressPopup" id="selectAddressPopup" frameBorder="0"></iframe>'/>
	</div>
	: '';

		return (element);
	}

	export default Address_handling;