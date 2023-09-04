import React, { useState } from '../../../js/react/node_modules/react';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import nl2br from '../../../js/react/node_modules/react-nl2br';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';
import Parser from '../../../js/react/node_modules/html-react-parser';

const Payments = (props) => {
	let data = props.data;
	const element = data.payment_methods.map((payment, ins) =>
	<div className="form-check d-flex" key={ins}>

		<div className="mr-1">
			<input className="form-check-input" type ="radio" name ="payment_method" id ={"payment_method_"+payment.payment_id} onClick={(e) => showPaymentForm(payment.payment_class)} defaultValue={payment.payment_class} defaultChecked={(data.active_payment == payment.payment_id) ? 'checked' : ''} />
		</div>

		<div className="mb-3 text-muted">
			<label className="form-check-label d-block text-body" htmlFor ={"payment_method_" + payment.payment_id}>
				{(payment.image) ?
					<span className="payment_image">
						<Image src={payment.image} alt={nl2br(payment.name)} />
					</span>
				: ''}

				{payment.name}{(payment.price_add_text != '') ? '(' + payment.price_add_text + ')' : ''}
			</label>

			{payment.payment_description}
			{Parser(payment.form)}
		</div>

	</div>
	);
	return (element);
	}

	export default Payments;