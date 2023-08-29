import React, { useState } from '../../../js/react/node_modules/react';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import { Redirect } from '../../../js/react/node_modules/react-router-dom';


const Offer_and_order_prooduct = (props) => {
    let data = props.data;
    let [dataRedirect, setRedirect] = useState('');
    let updateRedirect=(value)=> {
        setRedirect(value);
    }
    function changeForm() {
        let productForm = jQuery('#productForm');
        let projectName = jQuery('#project-name-input');

        if (productForm && projectName) {
            productForm.append(`<input type="hidden" name="projectname" value="${projectName.val()}" />`);
        }

        event.preventDefault();
        const form = jQuery('#productForm');
        var queryString = jQuery('#productForm').serialize();
        let href = 'index.php?option=com_jshopping&controller=offer_and_order&task=createOfferFromProduct&ajax=1';

    fetch(href, {
        method: "POST",
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: queryString
    }).then(res => res.json())
        .then((result) => {
            if(result.status == 1){
                updateRedirect(result.redirectLink);
            }
        });

}
if (dataRedirect){
    return <Redirect to={dataRedirect} />;
}


    const element =
        (data.config.allow_offer_on_product_details_page == 1 && data.product._display_price == 1 && data.usergroup_show_action == 1) ?
            <div className="angebote_erstellen input-group mb-3">
                <div className="input-group-append w-100">
                    <Form.Control type="text" className="form-control" name="projectname" id="project-name-input"
                           placeholder={Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_PROJECTNAME')}
                           defaultValue={data.projectname} />
                    <Form.Control className="btn btn-outline-secondary" type="submit"
                           defaultValue={Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_ANGEBOT_ERSTELLEN')}
                           onClick={() => changeForm()} />
                </div>
            </div>
        : '';

    return (element);
}

export default Offer_and_order_prooduct;