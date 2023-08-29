import React, { useState } from '../../../js/react/node_modules/react';
import Parser from '../../../js/react/node_modules/html-react-parser';
import ListGroup from '../../../js/react/node_modules/react-bootstrap/ListGroup';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';

const Demofiles = (props) => {
	let data = props.data;
	const element = (data.demofiles != null) ?
		<div className="col-md ep-left-col" id="ep-mail-sample-order-con">
			<div className="list_product_demo">
				{data.demofiles.map((demo, ins) =>
					<div className="download" key={ins}>
							<a target="_blank" href={data.config.demo_product_live_path + '/' + demo.demo}>
								{(demo.demo_descr != '') ?
									demo.demo_descr
									:
									Joomla.JText._('COM_SMARTSHOP_DOWNLOAD')
								}
							</a>
					</div>

				)}
			</div>
		</div>
	: '';
	return (element);
	}

	export default Demofiles;