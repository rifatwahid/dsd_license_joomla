import React, { useState } from '../../../js/react/node_modules/react';
import Parser from '../../../js/react/node_modules/html-react-parser';
import ListGroup from '../../../js/react/node_modules/react-bootstrap/ListGroup';

const Editor_button_product = (props) => {
	let data = props.data;
	const element = ( data.product._display_price == 1 && data.usergroup_show_action > 0 && typeof data._tmp_product_html_editor_button != undefined) ?
			<ListGroup.Item  as="li" className="list-inline-item btn-block mx-0 mb-4 shop_editor_btn border-0 p-0"  style={{display: data.show_buttons['editor'] > 0 ? " none" : 'block'}}>
				{data._tmp_product_html_editor_button}
			</ListGroup.Item>
		: '';

return (element);
}

export default Editor_button_product;