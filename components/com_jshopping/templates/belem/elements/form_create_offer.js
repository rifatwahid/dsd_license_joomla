import React, { useState } from '../../../js/react/node_modules/react';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import { Redirect } from '../../../js/react/node_modules/react-router-dom';

const Form_create_offer = (props) => {
    let data = props.data;
    let formaAction = data.create_offer_link;

    let [dataRedirect, setRedirect] = useState('');
    let updateRedirect=(value)=> {
        setRedirect(value);
    }
    function changeForm() {
        let productForm = jQuery('#angebote_erstellen');
        //let projectName = jQuery('#project-name-input');

        // if (productForm && projectName) {
        //     productForm.append(`<input type="hidden" name="projectname" value="${projectName.val()}" />`);
        // }

        event.preventDefault();
        const form = jQuery('#angebote_erstellen');
        var queryString = jQuery('#angebote_erstellen').serialize();
        let href = formaAction + '?ajax=1&ajax=1';

        fetch(href, {
            method: "POST",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: queryString
        }).then(res => res.json())
            .then((result) => {
                if(result.redirect){
                    updateRedirect(result.redirect);
                }
            });

    }
    if (dataRedirect){
        return <Redirect to={dataRedirect} />;
    }

    let element = (data.config.allow_offer_in_cart == 1) ?
        <Form id="angebote_erstellen" name="angebote_erstellen" className="clearfix" action={formaAction} method="POST">
            <div className="angebote_erstellen input-group mb-3">
                <div className="input-group-append">
                    <Form.Control type="text" className="form-control" name="projectname" placeholder={Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_PROJECTNAME')} defaultValue={props.projectname} />
                    <Form.Control className="btn btn-outline-secondary" type="submit" onClick={() => changeForm()} defaultValue={Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_ANGEBOT_ERSTELLEN')} />
                </div>
            </div>
        </Form>
    : '';

    return (element);
}

export default Form_create_offer;