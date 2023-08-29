import React, { useState } from '../../../js/react/node_modules/react';
import Parser from '../../../js/react/node_modules/html-react-parser';
import ListGroup from '../../../js/react/node_modules/react-bootstrap/ListGroup';


const Smart_link_btn = (data) => {
    let props = data.data;
    const element =
        (props.smartLink != '' && props.productUsergroupPermissions.is_usergroup_show_buy == true) ?
            <ListGroup.Item as="li" className="list-inline-item btn-block mx-0 mb-2">
                <a className="btn btn-outline-secondary shop_editor_btn btn-block" href={props.smartLink} style={{display: props.show_buttons['editor'] > 0 ? " none" : 'block'}}>
                        {Joomla.JText._('COM_SMARTSHOP_EDIT_TEMPLATE')}
                </a>
            </ListGroup.Item>
        : '';

    return (element);
}

export default Smart_link_btn;