import React, { useState, useEffect  } from '../../../js/react/node_modules/react';
import { Link } from '../../../js/react/node_modules/react-router-dom';
import nl2br from '../../../js/react/node_modules/react-nl2br';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';
import Parser from '../../../js/react/node_modules/html-react-parser';
import Category_products from '../elements/category_products.js';
import Smarteditorlink from '../elements/smarteditorlink.js';
import {connect} from "../../../js/react/node_modules/react-redux";
import { getCategorydefaultData as getCategorydefaultDataAction } from "../../../js/react/src/redux/modules/pageData";

const Category_default = ({categorydefaultData, getCategorydefaultData}) => {
	let data = categorydefaultData;
	const [historyHref, setHref] = useState('');
	const updateHref = (value) => {
		setHref(value);
	}
	if (historyHref != window.location.href) {
		getCategorydefaultData(window.location.href + '?ajax=1&ajax=1');
		updateHref(window.location.href);
	}
	const typeOfStar = data.type;
	let isHalfStar;
	let classReview;
	let iClassReview;
	let i = 0;
	let element = <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;
	const setImage = (category_image) => {
	let urlToCategoryImg = data.image_category_path + '/' + (category_image ? category_image : data.noimage);
		return urlToCategoryImg;
	}
	if (data.component) {
		const result = Object.keys(data.categories).map((key) => data.categories[key]);

		element = <div>
			<div className="shop category-list">
				<h1 className="category-list__page-title">{data.category.name}</h1>

				{(data.category.description.length > 0) ?
					Parser(data.category.description) : ''
				}

				<div className="row">
					{result.map((category, ins) =>

						<div className="col-sm-6 col-md-4 col-lg-3 card-group mb-5" key={ins}>
							<div className="card">

								<a href={category.category_link}>
									<Image className="card-img-top" src={setImage(category.category_image)}
										   alt={nl2br(category.name)}/>
								</a>

								<div className="card-body">
									<a href={category.category_link} className="text-body">
										<h5 className="card-title">{category.name}</h5>
									</a>

									<p className="card-text"
									   dangerouslySetInnerHTML={{__html: category.short_description}}/>
								</div>

							</div>
						</div>
					)}

				</div>

			</div>
			<Smarteditorlink data={data}/>
			<Category_products data={data}/>
		</div>;
	}
	return (element);
}
export default  connect(
	({ categorydefaultData }) => ({ categorydefaultData: categorydefaultData.categorydefaultData }),
	{
		getCategorydefaultData: getCategorydefaultDataAction
	}
)(Category_default);
