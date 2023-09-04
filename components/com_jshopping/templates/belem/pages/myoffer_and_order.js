import React, {useEffect, useState} from '../../../js/react/node_modules/react';
import {
	BrowserRouter as Router,
	Switch,
	Route,
	Link,
	Redirect,
} from '../../../js/react/node_modules/react-router-dom';
import nl2br from '../../../js/react/node_modules/react-nl2br';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Moment from '../../../js/react/node_modules/react-moment';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';
import {
	getOfferAndOrder as getOfferAndOrderAction
} from '../../../js/react/src/redux/modules/pageData';
import {connect} from "../../../js/react/node_modules/react-redux";

const Myoffer_and_order = ({offerAndOrder, getOfferAndOrder}) => {

	var data = offerAndOrder;
	const [text_search, setTextsearch] = useState(dataJson.text_search);
	const handleChangeTextsearch = e => {
		setTextsearch(e.target.value)
	}
	let updateData=(value)=> {
		setTextsearch(value);
	}


	const handleSubmit = event => {
		event.preventDefault();
		getOfferAndOrder(window.location.href + '?text_search='+text_search +'&ajax=1');
	}


	useEffect(() => {
		getOfferAndOrder(window.location.href + '&ajax=1?ajax=1');
	}, []);

	let element = <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;

	if(data.component) {

		if (typeof data.text_search == 'undefined' || data.text_search == null) data.text_search = '';
		element = <div className="shop offer-list">
			<div className="row-fluid row pb-2">
				<div className="col-sm-12 col-md-6 col-xl-6 col-12 ">
					<h1>{Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_MY_OFFER')}</h1>
				</div>
				<div className="col-sm-12 col-md-6 col-xl-6 col-12 ">
					{/*<Form name="adminForm" id="adminForm" method="post" onSubmit={handleSubmit} action={data.sefLinkSearchOffers}>*/}
					{/*	<div className="js-stools-container-bar text_right">*/}
					{/*		<div className="filter-search btn-group pull-left">*/}
					{/*			<Form.Control type="text" id="text_search" name="text_search"*/}
					{/*						  placeholder={Joomla.JText._('COM_SMARTSHOP_SEARCH')}*/}
					{/*						  onChange={e => setTextsearch(e.target.value)} value={text_search}/>*/}
					{/*		</div>*/}

					{/*		<div className="btn-group pull-left hidden-phone">*/}
					{/*			<Button variant="secondary" className="btn hasTooltip" type="submit"*/}
					{/*					title={Joomla.JText._('COM_SMARTSHOP_SEARCH')}>*/}
					{/*				<i className="fas fa-search"></i>*/}
					{/*			</Button>*/}
					{/*			<Button variant="secondary" className="btn hasTooltip" onClick={() => setTextsearch('')}*/}
					{/*					type="submit" title={Joomla.JText._('COM_SMARTSHOP_CLEAR_FILTERS')}>*/}
					{/*				<i className="fas fa-window-close"></i>*/}
					{/*			</Button>*/}
					{/*		</div>*/}

					{/*	</div>*/}
					{/*</Form>*/}
				</div>
			</div>

			<div className="list-group">

				<div className="list-group-item d-none d-sm-block">
					<div className="row">

						<div className="col-sm-2">
							{Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_NUMBER')}
						</div>

						<div className="col-sm-2">
							{Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_PROJECTNAME')}
						</div>

						<div className="col-sm-2 text-center">
							{Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_VALID_TO')}
						</div>
						<div className="col-sm-3 text-center"></div>

					</div>
				</div>

				{data.rows.map((v, k) =>
					<li className="list-group-item list-group-item-action" key={k}>
						<div className="row">

							<div className="col-sm-2">
								<span
									className="d-sm-none">{Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_NUMBER')}:</span>
								{v.order_number}
							</div>

							<div className="col-sm-2">
								<span
									className="d-sm-none">{Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_PROJECTNAME')}:</span>
								{v.projectname}
							</div>

							<div className="col-sm-2 text-sm-center">
								<span
									className="d-sm-none">{Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_VALID_TO')}:</span>
								<Moment date={v.valid_to} format='D.MM.Y'/>
							</div>

							<div className="col-sm-3 text-sm-center offer-open-pdf-lightbox-container"
								 data-order-id="<?php echo $v->order_id; ?>" data-user-id="<?php echo $v->user_id; ?>">
								<a className="offer-open-pdf-lightbox"
								   data-med={data.config.pdf_orders_live_path + '/' + v.pdf_file}
								   data-med-size="0x0" data-size="0x0"
								   href={data.config.pdf_orders_live_path + '/' + v.pdf_file} target="_blank">
									<span>{Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_OPEN_OFFER_AND_ORDER')}</span>
								</a>
							</div>

							<div className="col-sm-3 text-sm-right">
								<a target="_blank" href={v.order_link}>{Joomla.JText._('COM_SMARTSHOP_EDIT')}</a>
							</div>

						</div>
					</li>
				)}
			</div>
		</div>;
	}

	return (element);
}
export default  connect(
	({ offerAndOrder }) => ({ offerAndOrder: offerAndOrder.offerAndOrder }),
	{
		getOfferAndOrder: getOfferAndOrderAction
	}
)(Myoffer_and_order);