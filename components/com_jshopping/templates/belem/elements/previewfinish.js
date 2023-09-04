import React, { useState } from '../../../js/react/node_modules/react';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import Parser from '../../../js/react/node_modules/html-react-parser';
import { Redirect } from '../../../js/react/node_modules/react-router-dom';

const Previewfinish = (props) => {
	let data = props.data;
	let [dataRedirect, setStatus] = useState('');
	let updateStatus=(value)=> {
		setStatus(value);
	}
	function submitForm() {
		jQuery('#payment_form').submit(function (e) {
			event.preventDefault();
			var errorsBlockEl = document.querySelector('#qc_error');
			var submitCheckoutFormEl = e.target.querySelector('#submitCheckout');
			shopQuickCheckout.isSubmitFormEnabled = true;
			shopQuickCheckout.isRefreshFormData = true;
			shopQuickCheckout.isEnableDisableSubmit = true;

			errorsBlockEl.innerHTML = '';
			submitCheckoutFormEl.setProperty('disabled', true);
			shopQuickCheckout.isSuccessCheckedForm = shopQuickCheckout.checkForm();

			if (!shopQuickCheckout.isSuccessCheckedForm) {
				shopQuickCheckout.showErrors = true;

				if (shopQuickCheckout.isRefreshFormData) {
					shopQuickCheckout._refreshData();
				}

				if (shopQuickCheckout.qcheckoutErrors) {
					jQuery('html, body').animate({
						scrollTop: 0
					}, 250);
				}

				if (shopQuickCheckout.isEnableDisableSubmit) {
					submitCheckoutFormEl.setProperty('disabled', false);
				}

				return shopQuickCheckout.isSuccessCheckedForm;
			}

			if (shopQuickCheckout.beforeSubmitCheckoutTriggers.length >= 1) {
				shopQuickCheckout.beforeSubmitCheckoutTriggers.forEach(function (funcElem) {
					funcElem(e.target);
				});
			}

			if (shopQuickCheckout.isSubmitFormEnabled) {
				var queryString = jQuery('#payment_form').serialize();
				fetch(data.action+'?ajax=1&ajax=1', {
					method: "POST",
					headers: {'Content-Type': 'application/x-www-form-urlencoded'},
					body: queryString
				}).then(res => res.json())
					.then((result) => {
						if(result.status == 1){
							updateStatus(result.redirectLink);
						}else{
							Joomla.renderMessages({"error": [result.message]});
							updateStatus(result.redirectLink);
						}

					})
			}

			return shopQuickCheckout.isSuccessCheckedForm;
		});
	}
	if (dataRedirect) return <Redirect to={dataRedirect} />;
	const element = <div>
		{(data.jshopConfig.show_comment_box == 1) ?
			<div className="form-group mt-4">
				<label htmlFor="order_add_info" className="ml-4">
					{Joomla.JText._('COM_SMARTSHOP_ORDER_COMMENT')}
				</label>

				<textarea className="form-control" id="order_add_info" name="order_add_info" rows="3"></textarea>
			</div>
		: ''}

		{(data.no_return == 1 || data.products_return) ?
			<div className="form-check d-flex">
				<div className="mr-1">
					<input className="form-check-input" type="checkbox" name="no_return" id="no_return" />
				</div>

				<div className="mb-3">
					<label className="form-check-label d-block row_no_return" htmlFor="no_return">
						{Joomla.JText._('COM_SMARTSHOP_NO_RETURN_DESCRIPTION')}
					</label>
				</div>
			</div>
		: ''}

		{(data.jshopConfig.display_agb == 1) ?
			<div className="form-check d-flex row_agb">
				<div className="mr-1">
					<input className="form-check-input" type="checkbox" name="agb" id="agb" />
				</div>

				<div className="mb-3">
					<label className="form-check-label d-block" htmlFor="agb">
						{Parser(Joomla.JText._('COM_SMARTSHOP_AGB_AND_RETURN_POLICY'))}
					</label>
				</div>
			</div>
		: ''}
		{(data._tmpl_address_html) ?
			Parser(data._tmpl_address_html) : ''}

	<input type="submit" name="save" id="submitCheckout" value={Joomla.JText._('COM_SMARTSHOP_SUBMIT_ORDER')} className="btn btn-outline-primary btn-block col-md-6 float-right mt-2" onClick={(e) => submitForm()} />
</div>
	;
	return (element);
	}

	export default Previewfinish;