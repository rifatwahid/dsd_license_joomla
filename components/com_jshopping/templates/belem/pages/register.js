import React, {useState, useEffect} from '../../../js/react/node_modules/react';
import { Redirect } from '../../../js/react/node_modules/react-router-dom';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import shopUser from '../../../js/src/controllers/user/index.js';
import Parser from '../../../js/react/node_modules/html-react-parser';
import Col from '../../../js/react/node_modules/react-bootstrap/Col';
import ShopHelper from '../../../js/src/common/helper/index.js';
import { getRegisterData as getRegisterDataAction } from '../../../js/react/src/redux/modules/pageData';
import {connect} from "../../../js/react/node_modules/react-redux";
import DatePicker from '../../../js/react/node_modules/react-datepicker';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';

import '../../../js/react/node_modules/react-datepicker/dist/react-datepicker.css';

const Register = ({registerData, getRegisterData}) => {
    var data = {};
    let editDate = null;
    let [startDate, setStartDate] = useState(editDate);
    editDate = startDate;
    useEffect(() => {
        getRegisterData(window.location.href + '?ajax=1&ajax=1');
    }, []);
    data = registerData;
    const handleChange = (event) => {
        const val = jQuery("#client_type").val();
        if (val != 2) {
            jQuery('#tr_field_firma_code').addClass('display--none');
            jQuery('#tr_field_tax_number').addClass('display--none');
        } else {
            jQuery('#tr_field_firma_code').removeClass('display--none');
            jQuery('#tr_field_tax_number').removeClass('display--none');
        }
    }

  //  useEffect(() => {
        jQuery(".inputbox,.input,.form-control, select, .password").focusout('on', function(){
            var form = jQuery(this).closest('form').get(0);
            shopUser.validateAccountField(form.name, jQuery(this).attr('name'));
        });
        let valid = () => {
            if(jQuery(this).val().length == 0 || (jQuery(this).attr('name') == 'password_2' && jQuery('#password)').val() != jQuery('#password_2').val())|| (jQuery(this).attr('name') == 'password2' && jQuery('#password)').val() != jQuery('#password2)').val())){
                jQuery(this).addClass('is-invalid');
                jQuery(this).removeClass('is-valid');
            }else{
                jQuery(this).removeClass('is-invalid');
                jQuery(this).addClass('is-valid');
                jQuery('.'+jQuery(this).attr('name')+'_error').html('');
            }
        }
       // jQuery('#birthday_btn').tooltip({"html": true,"container": "body"});

  //  }, []);
    let [dataSave, setStatus] = useState('');
    let updateStatus=(value)=> {
        setStatus(value);
    }
    const handleSubmit = (event) => {
        const form = event.currentTarget;
        if(!shopUser.validateAddress(form.name)){
            event.preventDefault();
        }else {
            event.preventDefault();
            var queryString = jQuery('#shop-registration-form').serialize();
            let ar = queryString.split('&');
            fetch('index.php?option=com_jshopping&controller=user&task=registersave&ajax=1', {
                method: "POST",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: queryString
            }).then(res => res.json())
                .then((result) => {
                    if (result.status == 1) {
                        updateStatus(result);
                        Joomla.renderMessages({"success": [result.message]});
                    } else if(result.status == 403) {
                        JSONResponse({'redirect': result.redirect}, status=403);
                        Joomla.renderMessages({"error": [result.message]});
                    }else if(result.status == 0){
                        Joomla.renderMessages({"error": [result.message]});
                        jQuery("html, body").animate({ scrollTop: 0 }, "slow");

                    }
                    // setTimeout(function () {
                    //     document.getElementById("system-message-container").innerHTML = '';
                    // }, 3000);
                })
        }
        return shopUser.validateAddress(form.name);
    };

    if (dataSave.status == 1) return <Redirect to={dataSave.redirect} />;
    let element = <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;

    if(data.component) {
    shopUser.setFields(data.jsConfigFields);
    let firma_code_style = 'display';
    let tax_number_style = 'display';
    if (data.config_fields['tax_number']['display'] == 1 && data.clientTypeId != 2) {
        tax_number_style = "display--none";
    }

    if (data.config_fields['client_type']['display']) {
        firma_code_style = "display--none";
    }

    const keys = Object.keys(data.config_fields);
    const result = Object.keys(data.config_fields).map((key) => data.config_fields[key]);

    element = <div>
        <div id="qc_error" className="display--none"></div>
        <div className="shop shop-registration">
            <h1 className="shop-registration__page-title">
                {Joomla.JText._('COM_SMARTSHOP_REGISTER')}
            </h1>

            <Form action={data.registerSaveLink} id="shop-registration-form"
                  className="form-validate form-horizontal"
                  method="post" name="loginForm" onSubmit={handleSubmit}>

                {(data.config_fields) ?
                    result.map((val, i) =>
                        (keys[i] == 'title') ?
                    <Form.Group className="row">
                        <Form.Label htmlFor="title" column sm={5} md={4} lg={3}>{Joomla.JText._('COM_SMARTSHOP_TITLE')}
                            {(data.config_fields['title']['require']) ? <span>*</span> : ''}
                        </Form.Label>
                        <Col sm={8} md={8} lg={9}>
                            <Form.Control as="select" id="title" name="title" className="inputbox">
                                {Parser(data.clientTitlesOptions)}
                            </Form.Control>
                            <div className="title_error text-danger"></div>
                        </Col>
                    </Form.Group>
                : (keys[i] == 'client_type') ?
                    <Form.Group className="row">
                        <Form.Label htmlFor="client_type" column sm={5} md={4}
                                    lg={3}>{Joomla.JText._('COM_SMARTSHOP_CLIENT_TYPE')}
                            {(data.config_fields['client_type']['require']) ? <span>*</span> : ''}
                        </Form.Label>
                        <Col sm={8} md={8} lg={9}>
                            <Form.Control as="select" id="client_type" name="client_type" className="inputbox"
                                          onChange={handleChange}>
                                {Parser(data.clientTypesOptions)}
                            </Form.Control>
                            <div className="client_type_error text-danger"></div>
                        </Col>
                    </Form.Group>
                : (keys[i] == 'birthday') ?
                    <Form.Group className="row" id='tr_field_birthday'>
                        <Form.Label htmlFor="birthday" column sm={5} md={4}
                                    lg={3}>{Joomla.JText._('COM_SMARTSHOP_BIRTHDAY')}
                            {(data.config_fields['birthday']['require']) ? <span>*</span> : ''}
                        </Form.Label>
                        <Col sm={8} md={8} lg={9}>
                            {/*<DatePicker selected={editDate} name="birthday" id="birthday" onChange={(date) => {if(event.target.value == null || event.target.value.length == 10){setStartDate(date)}}}  dateFormat='dd.MM.yyy'/>*/}
                            <DatePicker showYearDropdown selected={editDate} name="birthday" id="birthday" onChange={(date) => {if(event.target.value == null || event.target.value.length == 10){setStartDate(date)}}} dateFormat='dd.MM.yyy'/>
                            <div className="birthday_error text-danger"></div>
                           </Col>
                     </Form.Group>
                :(keys[i] == 'country') ?
                    <Form.Group className="row">
                        <Form.Label htmlFor="country" column sm={5} md={4}
                                    lg={3}>{Joomla.JText._('COM_SMARTSHOP_COUNTRY')}
                            {(data.config_fields['country']['require']) ? <span>*</span> : ''}
                        </Form.Label>
                        <Col sm={8} md={8} lg={9} dangerouslySetInnerHTML={{__html: data.select_countries}}>
                            {/*{Parser(data.select_countries)}*/}

                        </Col>
                        <div className="country_error text-danger"></div>
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
                        <Col sm={8} md={8} lg={9}>
                            <Form.Check name="privacy_statement" id="privacy_statement" defaultValue="1"
                                        label={Joomla.JText._('COM_SMARTSHOP_PRIVACY_POLICY_NOTICE')}/>
                            <div className="privacy_statement_error text-danger"></div>
                        </Col>
                    </Form.Group>
                :
                    <Form.Group className="row">
                        <Form.Label htmlFor={keys[i]} column sm={5} md={4}
                                    lg={3}>{Joomla.JText._('COM_SMARTSHOP_'+keys[i])}
                            {(data.config_fields[keys[i]]['require']) ? <span>*</span> : ''}
                        </Form.Label>
                        <Col sm={8} md={8} lg={9} >
                            <Form.Control id={keys[i]} name={keys[i]} className="input form-control"
                                          defaultValue=""
                                          placeholder={Joomla.JText._('COM_SMARTSHOP_'+keys[i])}/>
                            <div className={keys[i] + '_error text-danger'}></div>
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

                <Form.Group className="row">
                    <Form.Label htmlFor="password" column sm={5} md={4}
                                lg={3}>{Joomla.JText._('COM_SMARTSHOP_PASSWORD')}
                       <span>*</span>
                    </Form.Label>
                    <Col sm={8} md={8} lg={9}>
                        <Form.Control type="password" id="password" name="password" className="input"
                                      placeholder={Joomla.JText._('COM_SMARTSHOP_PASSWORD')}/>
                        <div className="password_error text-danger"></div>
                    </Col>
                </Form.Group>
                <Form.Group className="row">
                    <Form.Label htmlFor="password_2" column sm={5} md={4}
                                lg={3}>{Joomla.JText._('COM_SMARTSHOP_PASSWORD_AGAIN')}
                        <span>*</span>
                    </Form.Label>
                    <Col sm={8} md={8} lg={9}>
                        <Form.Control type="password" id="password_2" name="password_2" className="input"
                                      placeholder={Joomla.JText._('COM_SMARTSHOP_PASSWORD_AGAIN')}/>
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
                            <div className="privacy_statement_error text-danger"></div>
                        </Col>
                    </Form.Group>
                    : ''
                }
                {(data.tmpl_register_html_end) ? Parser(data.tmpl_register_html_end) : ''}

                <Form.Group className="row">
                    <Form.Label htmlFor="captcha" column sm={5} md={4} lg={3}>
                        <span>* </span>{Joomla.JText._('COM_SMARTSHOP_REQUIRED_FIELD')}
                    </Form.Label>
                    <Col sm={8} md={8} lg={9}>
                        <Button variant="outline-primary" type="submit" bsPrefix="btn" className="btn-block">
                            {Joomla.JText._('COM_SMARTSHOP_REGISTER')}
                        </Button>
                    </Col>
                </Form.Group>

                <input type="hidden" name={Joomla.getOptions('csrf.token')} value="1"/>
            </Form>
        </div>
    </div>;
    }
    return (element);
}
export default  connect(
    ({ registerData }) => ({ registerData: registerData.registerData }),
    {
        getRegisterData: getRegisterDataAction
    }
)(Register);
