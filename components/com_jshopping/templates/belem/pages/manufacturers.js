import React, {useEffect, useState} from '../../../js/react/node_modules/react';
import { Link, Redirect } from '../../../js/react/node_modules/react-router-dom';
import nl2br from '../../../js/react/node_modules/react-nl2br';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';
import Parser from '../../../js/react/node_modules/html-react-parser';
import {getManufacturersData as getManufacturersDataAction} from '../../../js/react/src/redux/modules/pageData';
import {connect} from "../../../js/react/node_modules/react-redux";

const Manufacturers = ({manufacturersData, getManufacturersData}) => {
	let data = manufacturersData;
	const [historyHref, setHref] = useState('');
	const updateHref = (value) => {
		setHref(value);
	}
	if (historyHref != window.location.href) {
		getManufacturersData(window.location.href + '?ajax=1&ajax=1');
		updateHref(window.location.href);
	}
	const setImage = (image) => {
		let urlToImg = data.image_manufs_live_path + '/' + (image ? image : data.noimage);
		return urlToImg;
	}
	let element = <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;

	if(data.component) {
		const result = Object.keys(data.rows).map((key) => data.rows[key]);

		element = <div className="shop manufacturer-list">
			<h1 className="manufacturer-list__page-title">{Joomla.JText._('COM_SMARTSHOP_MANUFACTURERS')}</h1>
			<div className="row">
				{result.map((row, ins) =>

					<div className="col-sm-6 col-md-4 col-lg-3 card-group mb-5" key={ins}>
						<div className="card">

							<Link to={row.link}>
								<Image className="card-img-top" src={setImage(row.manufacturer_logo)}
									   alt={nl2br(row.shop_name)}/>
							</Link>

							<div className="card-body">
								<Link to={row.link} className="text-body">
									<h5 className="card-title">{row.name}</h5>
								</Link>

								<p className="card-text" dangerouslySetInnerHTML={{__html: row.short_description}}/>
							</div>

						</div>
					</div>
				)}

			</div>
			{(data.display_pagination) ?
				<div className="manufacturer-list__page-title">
					{Parser(data.pagination)}
				</div>
				: ''}
		</div>;

	}

	return (element);
}
export default  connect(
	({ manufacturersData }) => ({ manufacturersData: manufacturersData.manufacturersData }),
	{
		getManufacturersData: getManufacturersDataAction
	}
)(Manufacturers);
