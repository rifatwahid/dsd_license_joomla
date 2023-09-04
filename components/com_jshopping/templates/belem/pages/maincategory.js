import React, {useEffect, useState} from '../../../js/react/node_modules/react';
import { Link } from '../../../js/react/node_modules/react-router-dom';
import nl2br from '../../../js/react/node_modules/react-nl2br';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';
import Parser from '../../../js/react/node_modules/html-react-parser';
import {connect} from "../../../js/react/node_modules/react-redux";
import { getMaincategoryData as getMaincategoryDataAction } from "../../../js/react/src/redux/modules/pageData";

const Maincategory = ({maincategoryData, getMaincategoryData}) => {
	let data = maincategoryData;
	let element = <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;

	useEffect(() => {
		getMaincategoryData(window.location.href + '?ajax=1&ajax=1');
	}, []);
	if (data.component) {
		const typeOfStar = data.type;
		let isHalfStar;
		let classReview;
		let iClassReview;
		let i = 0;

		const setImage = (category_image) => {
			let urlToCategoryImg = data.image_category_path + '/' + (category_image ? category_image : data.noimage);
			return urlToCategoryImg;
		}
		const result = Object.keys(data.categories).map((key) => data.categories[key]);

		element = <div className="shop category-list">
			{(typeof data.categories != 'undefined' && data.categories.lengh > 0) ?
				<h1 className="category-list__page-title">{Joomla.JText._('COM_SMARTSHOP_CATEGORIES')}</h1>
				: ''}
			<div className="row">
				{result.map((category, ins) =>

					<div className="col-sm-6 col-md-4 col-lg-3 card-group mb-5" key={ins}>
						<div className="card">

							<Link to={category.category_link}>
								<Image className="card-img-top" src={setImage(category.category_image)}
									   alt={nl2br(category.name)}/>
							</Link>

							<div className="card-body">
								<Link to={category.category_link} className="text-body">
									<h5 className="card-title">{category.name}</h5>
								</Link>

								<p className="card-text"
								   dangerouslySetInnerHTML={{__html: category.short_description}}/>
							</div>

						</div>
					</div>
				)}

			</div>
		</div>;
	}
	return (element);
}

export default  connect(
	({ maincategoryData }) => ({ maincategoryData: maincategoryData.maincategoryData }),
	{
		getMaincategoryData: getMaincategoryDataAction
	}
)(Maincategory);
