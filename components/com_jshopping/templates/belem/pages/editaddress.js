import React, {useEffect, useState} from '../../../js/react/node_modules/react';
import { Redirect } from '../../../js/react/node_modules/react-router-dom';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import shopUser from '../../../js/src/controllers/user/index.js';
import Parser from '../../../js/react/node_modules/html-react-parser';
import Col from '../../../js/react/node_modules/react-bootstrap/Col';
import ShopHelper from '../../../js/src/common/helper/index.js';
import ValidateForm from '../../../js/src/common/validate_form/index.js';
import { getEditAddressData as getEditAddressDataAction } from '../../../js/react/src/redux/modules/pageData';
import {connect} from "../../../js/react/node_modules/react-redux";
import DatePicker from '../../../js/react/node_modules/react-datepicker';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';
import '../../../js/react/node_modules/react-datepicker/dist/react-datepicker.css';

const Editaddress = ({editAddressData, getEditAddressData}) => {
    var data = editAddressData;
    let editDate = null;
    let [startDate, setStartDate] = useState(editDate);
    if(startDate){
        editDate = startDate;
    }else if (typeof data.address != 'undefined' && typeof data.address.birthday != 'undefined' && data.address.birthday != null){
        editDate = new Date(data.address.birthday.replace( /(\d{2}).(\d{2}).(\d{4})/, "$2/$1/$3"));
    }


    useEffect(() => {
        getEditAddressData(window.location.href + '&ajax=1');
        shopUser.setFields(data.jsConfigFields);
    }, []);


    const handleChange = (event) => {
        const val = jQuery("#client_type").val();
        if(val != 2){
            jQuery('#tr_field_firma_code').addClass('display--none');
            jQuery('#tr_field_tax_number').addClass('display--none');
        }else{
            jQuery('#tr_field_firma_code').removeClass('display--none');
            jQuery('#tr_field_tax_number').removeClass('display--none');
        }
    }
    function getDefaultValue(field) {
        if (data.flashSavedData && data.flashSavedData[{field}]) {
            return data.flashSavedData[{field}];
        }
        return data.address.[field];
    }


    let [dataSave, setStatus] = useState('');
    let updateStatus=(value)=> {
        setStatus(value);
    }

    useEffect(() => {
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
        jQuery('#birthday_btn').tooltip({"html": true,"container": "body"});

    });
    const handleSubmit = (event) => {
        const form = event.currentTarget;
        if(!shopUser.validateAddress(form.name)){
            event.preventDefault();
        }else {
            event.preventDefault();
            var queryString = jQuery('#editUserAddressForm').serialize();
            let ar = queryString.split('&');
            fetch('index.php?option=com_jshopping&controller=user&task=editAddressSave&ajax=1', {
                method: "POST",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: queryString
            }).then(res => res.json())
                .then((result) => {
                    if(result.close == 1){
                        window.close();
                    }else {
                        updateStatus(result);
                        if (result.status == 1) {
                            Joomla.renderMessages({"success": [result.message]});
                        } else {
                            Joomla.renderMessages({"error": [result.message]});
                        }
                        setTimeout(function () {
                            document.getElementById("system-message-container").innerHTML = '';
                        }, 3000);
                    }
                })
        }
        return shopUser.validateAddress(form.name);
    };

    function setDate() {
        this.setState({
            day : Moment().date(),
            month : Moment().format('MMM'),
            year : Moment().year(),
            weekday : Moment().format('dddd')
        });
    }
    if (dataSave.status) return <Redirect to={dataSave.redirectLink} />;
    // window.addEvent('domready', function() {Calendar.setup({
    //     inputField     :    "birthday",     // id of the input field
    //     ifFormat       :    "d.m.Y",      // format of the input field
    //     button         :    "birthday_btn",  // trigger for the calendar (button ID)
    //     align          :    "Tl",           // alignment (defaults to "Bl")
    //     singleClick    :    true
    // });});
    let element = <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;
    if(data.component) {
        shopUser.setFields(data.jsConfigFields);
        let firma_code_style = 'display';
        let tax_number_style = 'display';
        if(typeof data.config_fields['tax_number']['display'] == 1 && data.clientTypeId != 2) {
            tax_number_style="display--none";
        }

        if(data.config_fields['client_type']['display']) {
            firma_code_style="display--none";
        }
        const keys = Object.keys(data.config_fields);
        const result = Object.keys(data.config_fields).map((key) => data.config_fields[key]);

         element = <div>
            <div id="qc_error" className="display--none"></div>
            <div className="editUserAddress">
                <h1 className="editUserAddress__page-title">
                    {Joomla.JText._('COM_SMARTSHOP_EDIT_ADDRESS')}
                </h1>

                <Form action={data.action} id="editUserAddressForm" method="post" name="loginForm"
                      className="form-validate form-horizontal" onSubmit={handleSubmit} encType="multipart/form-data">


                    {(data.config_fields) ?
                        result.map((val, i) =>
                            (keys[i] == 'title') ?
                                <Form.Group className="row">
                                    <Form.Label htmlFor="title" column sm={5} md={4}
                                                lg={3}>{Joomla.JText._('COM_SMARTSHOP_TITLE')}
                                        {(val.require) ? <span>*</span> : ''}
                                    </Form.Label>
                                    <Col sm={7} md={8} lg={9}>
                                        <Form.Control as="select" id="title" name="title"
                                                      defaultValue={getDefaultValue('title')} className="inputbox">
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
                                            <Form.Control as="select" id="client_type" defaultValue={getDefaultValue('client_type')}
                                                          name="client_type" className="inputbox" onChange={handleChange}>
                                                {Parser(data.clientTypesOptions.replace('selected', 'defaultValue'))}
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
                                                <DatePicker showYearDropdown selected={editDate} name="birthday" id="birthday" onChange={(date) => {if(event.target.value == null || event.target.value.length == 10){setStartDate(date)}}} onChangeRaw={(event) => {if(event.target.value == null || event.target.value.length == 10){setStartDate(event.target.value)}}} dateFormat='dd.MM.yyy'/>
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
                                            {/*<div className="country_error text-danger"></div>*/}
                                        </Col>
                                        <div className="country_error text-danger"></div>
                                    </Form.Group>
                                : (keys[i] == 'privacy_statement') ?
                                    <Form.Group className="row">
                                        <Form.Label htmlFor={keys[i]} column sm={5} md={4} lg={3}>
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
                                        <Col sm={8} md={8} lg={9}>
                                            <Form.Control id={keys[i]} name={keys[i]} className="input"
                                                          defaultValue={getDefaultValue(keys[i])}
                                                          placeholder={Joomla.JText._('COM_SMARTSHOP_'+keys[i])}/>
                                            <div className={keys[i]+"_error text-danger"}></div>
                                        </Col>
                                    </Form.Group>
                        )
                        : ''}

                    {data._tmpl_editaccount_html}
                    <div className="form-group row">
                        <div className="col-sm-5 col-md-4 col-lg-3 text-danger">
                            <span>*</span>{Joomla.JText._('COM_SMARTSHOP_REQUIRED_FIELD')}
                        </div>

                        <div className="col-sm-7 col-md-8 col-lg-9">
                            <Button type="submit" name="next" variant="outline-primary"
                                    className="btn-block col-md-6 float-right">{Joomla.JText._('COM_SMARTSHOP_SAVE')}
                            </Button>
                        </div>
                    </div>

                    <input type="hidden" name="editId" value={data.address.address_id}/>
                    <input type="hidden" name="isCloseTabAfterSave" value={data.isCloseTabAfterSave}/>
                    <input type="hidden" name={Joomla.getOptions('csrf.token')} value="1"/>
                </Form>
            </div>
        </div>;
    }
    return (element);
}
export default  connect(
    ({ editAddressData }) => ({ editAddressData: editAddressData.editAddressData }),
    {
        getEditAddressData: getEditAddressDataAction
    }
)(Editaddress);
