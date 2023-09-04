import React, { useState } from '../../../js/react/node_modules/react';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import Renderbutton from '../elements/renderbutton.js';
import Renderwindow from '../elements/renderwindow.js';
import Parser from '../../../js/react/node_modules/html-react-parser';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Col from '../../../js/react/node_modules/react-bootstrap/Col';
import DatePicker from '../../../js/react/node_modules/react-datepicker';

import '../../../js/react/node_modules/react-datepicker/dist/react-datepicker.css';

const Address_fields = (props) => {
	let data = props.data;
	let editDate = null;
	let [startDate, setStartDate] = useState(editDate);
	editDate = startDate;

	const handleChange = (event) => {
		const val = jQuery("#client_type").val();
		if(val != 2){
			jQuery('#tr_field_firma_code').addClass('display--none');
			jQuery('#tr_field_tax_number').addClass('display--none');
		}else{
			jQuery('#tr_field_firma_code').removeClass('display--none');
			jQuery('#tr_field_tax_number').removeClass('display--none');
			jQuery('#tr_field_tax_number').removeClass('display--none1');
		}
	}
	const dhandleChange = (event) => {
		const val = jQuery("#d_client_type").val();
		if(val != 2){
			jQuery('#tr_field_d_firma_code').addClass('display--none');
			jQuery('#tr_field_d_tax_number').addClass('display--none');
		}else{
			jQuery('#tr_field_d_firma_code').removeClass('display--none');
			jQuery('#tr_field_d_tax_number').removeClass('display--none');
			jQuery('#tr_field_d_tax_number').removeClass('display--none1');
		}
	}

	let firma_code_style = 'display';
	let d_firma_code_style = 'display';

	let tax_number_style = 'display';
	let d_tax_number_style = 'display';
	if(data.config_fields['tax_number']['display'] == 1 && data.clientTypeId != 2) {
		tax_number_style="display--none";
	}
	if(data.config_dfields['tax_number']['display'] == 1 && data.clientTypeId != 2) {
		d_tax_number_style="display--none";
	}

	if(data.config_fields['client_type']['display'] ) {
		firma_code_style="display--none";
	}
	if(data.config_dfields['client_type']['display']) {
		d_firma_code_style="display--none";
	}

	const keys = Object.keys(data.config_fields);
	const result = Object.keys(data.config_fields).map((key) => data.config_fields[key]);


	const d_keys = Object.keys(data.config_dfields);
	const d_result = Object.keys(data.config_dfields).map((key) => data.config_dfields[key]);

	const element =
		<div id="qc_address">
			<legend>
				{Joomla.JText._('COM_SMARTSHOP_ADDRESS')}
			</legend>
			{(data.config_fields) ?
				result.map((val, i) =>
					(keys[i] == 'title') ?
						<div className="form-group row">
							<label htmlFor="title" className="col-sm-5 col-md-4 col-lg-3 col-form-label">
								{Joomla.JText._('COM_SMARTSHOP_TITLE')}
								{(data.config_fields['title']['require'] == 1) ?
									<span>*</span> : ''}
							</label>

							<div className="col-sm-7 col-md-8 col-lg-9">
								{Parser(data.select_titles)}
								<div className="title_error text-danger"></div>
							</div>
						</div>
					: (keys[i] == 'client_type') ?
						<Form.Group className="row">
							<Form.Label htmlFor="client_type" column sm={5} md={4} lg={3}>{Joomla.JText._('COM_SMARTSHOP_CLIENT_TYPE')}
								{(data.config_fields['client_type']['require']) ? <span>*</span> : ''}
							</Form.Label>
							<Col sm={8} md={8} lg={9}>
								<Form.Control as="select" id="client_type" name="client_type" className="inputbox" onChange={handleChange}>
									{Parser(data.clientTypesOptions)}
								</Form.Control>
								<div className="client_type_error text-danger"></div>
							</Col>
						</Form.Group>
					: (keys[i] == 'birthday') ?
						<Form.Group className="row" id='tr_field_birthday'>
							<Form.Label htmlFor="birthday" column sm={5} md={4} lg={3}>{Joomla.JText._('COM_SMARTSHOP_BIRTHDAY')}
								{(data.config_fields['birthday']['require']) ? <span>*</span> : ''}
							</Form.Label>
							<Col sm={8} md={8} lg={9}>
								<DatePicker selected={editDate} name="birthday" id="birthday" onChange={(date) => {if((jQuery(this).val() == null || jQuery(this).val() == 10) ){setStartDate(date)}}}  dateFormat='dd.MM.yyy'/>
								<div className="birthday_error text-danger"></div>
							</Col>
						</Form.Group>
					:(keys[i] == 'country') ?
						<Form.Group className="row">
							<Form.Label htmlFor="country" column sm={5} md={4} lg={3}>{Joomla.JText._('COM_SMARTSHOP_COUNTRY')}
								{(data.config_fields['country']['require']) ? <span>*</span> : ''}
							</Form.Label>
							<div sm={8} md={8} lg={9} class="col-lg-9 col-md-8 col-sm-8" dangerouslySetInnerHTML={{__html:data.select_countries}}>
								{/*{Parser(data.select_countries)}*/}
								{/*<div className="country_error text-danger"></div>*/}
							</div>
						</Form.Group>
					:(keys[i] == 'privacy_statement') ?
						<Form.Group className="row">
							<Form.Label htmlFor="privacy_statement" column sm={5} md={4} lg={3}>
								<a className="privacy_statement"
								   href="/index.php?option=com_jshopping&controller=content&task=view&page=privacy_statement&tmpl=component"
								   target="_blank">{Joomla.JText._('COM_SMARTSHOP_PRIVACY_POLICY')}
									{(data.config_fields['privacy_statement']['require']) ? <span>*</span> : ''}
								</a>
							</Form.Label>
							<Col sm={7} md={8} lg={9}>
								<Form.Check name="privacy_statement" id="privacy_statement" defaultValue="1" label={Joomla.JText._('COM_SMARTSHOP_PRIVACY_POLICY_NOTICE')} />
								<div className="privacy_statement_error text-danger"></div>
							</Col>
						</Form.Group>
					:
						<Form.Group className="row">
								<Form.Label htmlFor={keys[i]} column sm={5} md={4} lg={3}>{Joomla.JText._('COM_SMARTSHOP_'+keys[i])}
									{(data.config_fields[keys[i]]['require']) ? <span>*</span> : ''}
								</Form.Label>
								<Col sm={8} md={8} lg={9}>
									<Form.Control id={keys[i]} name={keys[i]} defaultValue="" className="input form-control" placeholder={Joomla.JText._('COM_SMARTSHOP_'+keys[i])}/>
									<div className={keys[i] + "_error text-danger"}></div>
								</Col>
							</Form.Group>
					)
				: ''
			}
			<Form.Group className="row" id='tr_field_email'>
				<Form.Label htmlFor="email" column sm={5} md={4} lg={3}>{Joomla.JText._('COM_SMARTSHOP_EMAIL')}
					<span>*</span>
				</Form.Label>
				<Col sm={8} md={8} lg={9}>
					<Form.Control id="email" name="email" className="input"
								  placeholder={Joomla.JText._('COM_SMARTSHOP_EMAIL')}/>
					<div className="email_error text-danger"></div>
				</Col>
			</Form.Group>


			<div className="form-group row pb-3 pt-3">
				<div className="col-sm-5 col-md-4 col-lg-3">
					{Joomla.JText._('COM_SMARTSHOP_DELIVERY_ADDRESS_DIFFERENT')}
				</div>
				<div className="col-sm-7 col-md-8 col-lg-9">
					<div className="form-check d-inline">
						<input className="form-check-input" type="radio" name="delivery_adress" id="delivery_adress_1"
							   defaultValue="0" defaultChecked={(data.user.delivery_adress != 1) ? "checked" : ''}
							   onClick = {(e) => jQuery('#shop-delivery-address').hide()} />
						<label className="form-check-label"
							   htmlFor="delivery_adress_1">{Joomla.JText._('COM_SMARTSHOP_NO')}</label>
					</div>
					<div className="form-check d-inline">
						<input className="form-check-input" type="radio" name="delivery_adress" id="delivery_adress_2"
							   value="1" defaultChecked={(data.user.delivery_adress == 1) ? "checked" : ''}
							onClick = {(e) => jQuery('#shop-delivery-address').show()} />
						<label className="form-check-label"
							   htmlFor="delivery_adress_2">{Joomla.JText._('COM_SMARTSHOP_YES')}</label>
					</div>
				</div>
			</div>
			<div id = "shop-delivery-address" style={{display:(data.delivery_adress != 1) ? "none" : "block"}} >
				{(data.config_dfields) ?
					d_result.map((val, i) =>
						(d_keys[i] == 'title') ?
							<div className="form-group row">
								<label htmlFor="d_title" className="col-sm-5 col-md-4 col-lg-3 col-form-label">
									{Joomla.JText._('COM_SMARTSHOP_TITLE')}
									{(data.config_dfields['title']['require'] == 1) ?
										<span>*</span> : ''}
								</label>

								<div className="col-sm-7 col-md-8 col-lg-9">
									{Parser(data.select_d_titles)}
									<div className="d_title_error text-danger"></div>
								</div>
							</div>
							: (d_keys[i] == 'client_type') ?
								<Form.Group className="row">
									<Form.Label htmlFor="d_client_type" column sm={5} md={4} lg={3}>{Joomla.JText._('COM_SMARTSHOP_CLIENT_TYPE')}
										{(data.config_dfields['client_type']['require']) ? <span>*</span> : ''}
									</Form.Label>
									<Col sm={8} md={8} lg={9}>
										<Form.Control as="select" id="d_client_type" name="d_client_type" className="inputbox" onChange={handleChange}>
											{Parser(data.clientTypesOptions)}
										</Form.Control>
										<div className="d_client_type_error text-danger"></div>
									</Col>
								</Form.Group>
								: (d_keys[i] == 'birthday') ?
									<Form.Group className="row" id='tr_field_birthday'>
										<Form.Label htmlFor="d_birthday" column sm={5} md={4} lg={3}>{Joomla.JText._('COM_SMARTSHOP_BIRTHDAY')}
											{(data.config_dfields['birthday']['require']) ? <span>*</span> : ''}
										</Form.Label>
										<Col sm={8} md={8} lg={9}>
											<DatePicker selected={editDate} name="d_birthday" id="d_birthday" onChange={(date) => {if((jQuery(this).val() == null || jQuery(this).val() == 10) ){setStartDate(date)}}}  dateFormat='dd.MM.yyy'/>
											<div className="d_birthday_error text-danger"></div>
										</Col>
									</Form.Group>
									:(d_keys[i] == 'country') ?
										<Form.Group className="row">
											<Form.Label htmlFor="d_country" column sm={5} md={4} lg={3}>{Joomla.JText._('COM_SMARTSHOP_COUNTRY')}
												{(data.config_dfields['country']['require']) ? <span>*</span> : ''}
											</Form.Label>
											<div sm={8} md={8} lg={9} class="col-lg-9 col-md-8 col-sm-8" dangerouslySetInnerHTML={{__html:data.select_d_countries}}>
												{/*{Parser(data.select_countries)}*/}
											</div>
											<div className="d_country_error text-danger"></div>
										</Form.Group>
											:(d_keys[i] == 'privacy_statement') ?
											''
											:
											<Form.Group className="row">

												<Form.Label htmlFor={'d_'+keys[i]} column sm={5} md={4} lg={3}>{Joomla.JText._('COM_SMARTSHOP_'+keys[i])}
													{(data.config_dfields[keys[i]]['require']) ? <span>*</span> : ''}
												</Form.Label>
												<Col sm={8} md={8} lg={9}>
													<Form.Control id={'d_'+keys[i]} name={'d_'+keys[i]} defaultValue="" className="input form-control" placeholder={Joomla.JText._('COM_SMARTSHOP_'+keys[i])}/>
													<div className={'d_'+keys[i] + "_error text-danger"}></div>
												</Col>
											</Form.Group>
					)
					: ''
				}			</div>

			<Form.Group className="row">
				<Form.Label column sm={5} md={4} lg={3}>
					<span>* </span>{Joomla.JText._('COM_SMARTSHOP_REQUIRED_FIELD')}
				</Form.Label>
			</Form.Group>

			{( data.allowUserRegistration == 1 && data.jshopConfig.show_create_account_block == 1 && data.currentUser.guest > 0 ) ?
				<div><h4 className="mt-4 pb-2 font-weight-normal">{Joomla.JText._('COM_SMARTSHOP_CREATE_ACCOUNT')}</h4>
				<p>{Joomla.JText._('COM_SMARTSHOP_CREATE_ACCOUNT_TEXT')}</p>

				<div id="qcheckout__create-account">
					<Form.Group className="row">
						<Form.Label htmlFor="password" column sm={5} md={4} lg={3}>{Joomla.JText._('COM_SMARTSHOP_PASSWORD')}
						</Form.Label>
						<Col sm={8} md={8} lg={9}>
							<Form.Control type="password" id="password" name="password" className="input" placeholder={Joomla.JText._('COM_SMARTSHOP_PASSWORD')}/>
							<div className="password_error text-danger"></div>
						</Col>
					</Form.Group>
				</div>
				<Form.Group className="row">
					<Form.Label htmlFor="password_2" column sm={5} md={4} lg={3}>{Joomla.JText._('COM_SMARTSHOP_PASSWORD_AGAIN')}
					</Form.Label>
					<Col sm={8} md={8} lg={9}>
						<Form.Control type="password" id="password_2" name="password_2" className="input" placeholder={Joomla.JText._('COM_SMARTSHOP_PASSWORD_AGAIN')}/>
						<div className="password_2_error text-danger"></div>
					</Col>
				</Form.Group>

			{(data.captchaHtml) ?
				<Form.Group className="row">
					<Form.Label htmlFor="captcha" column sm={5} md={4} lg={3}>
						{Joomla.JText._('COM_SMARTSHOP_CAPTCHA_LABEL')}
					</Form.Label>
				<Col sm={8} md={8} lg={9}>
					{Parser(data.captchaHtml)}
				</Col>
				</Form.Group>
				: ''
			}

		<Form.Group className="row">
				<Form.Label htmlFor="qcheckoutReadPrivacy" column sm={5} md={4} lg={3}>
					<a href="/index.php?option=com_jshopping&controller=content&task=view&page=privacy_statement&tmpl=component" target="_blank">{Joomla.JText._('COM_SMARTSHOP_PRIVACY_POLICY')}</a>
				</Form.Label>

				<Col sm={8} md={8} lg={9}>
					<input type="checkbox" name="qcheckoutReadPrivacy" id="qcheckoutReadPrivacy" className="input" />
&nbsp;
			<Form.Label htmlFor="qcheckoutReadPrivacy">
				{ Joomla.JText._('COM_SMARTSHOP_PRIVACY_POLICY_CREATE_ACCOUNT')}
			</Form.Label>
		</Col>
	</Form.Group></div>
	: ''}
		</div>;
		return (element);
	}

	export default Address_fields;