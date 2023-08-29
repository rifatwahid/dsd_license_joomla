import React, { useState } from '../../../js/react/node_modules/react';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import Address_handling from '../elements/address_handling.js';
import Address_fields from '../elements/address_fields.js';

const Checkout_address = (props) => {
	let data = props.data;
	const element = <fieldset className="form-group">
				{(data.isUserAuthorized) ?
					<Address_handling data={data} />
				:
					<Address_fields data={data} />
				}
		</fieldset>;
	return (element);
	}

	export default Checkout_address;