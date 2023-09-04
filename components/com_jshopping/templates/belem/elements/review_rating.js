import React, { useState } from '../../../js/react/node_modules/react';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Parser from '../../../js/react/node_modules/html-react-parser';

const Review_rating = (data) => {
	const typeOfStar = data.config.rating_starparts;
	let isHalfStar;
	let classReview;
	let iClassReview;
	let i = 0;
	let element = '';
	const result = [];
	if (dataJson.stars_count > 0) {
		for (let i = 0; i < dataJson.stars_count; i++) {
			result[i] = i;
		}
	}
	const [checked, setChecked] = React.useState(true);
	function reviewBloc(value, ins){
		isHalfStar = (typeof typeOfStar == 'undefinde' || typeOfStar == 'null' && ((ins + 1) % 2 == 1)) ? true : false;
		classReview = (isHalfStar == 1) ? 'rating__label--half' : '';
		iClassReview = (isHalfStar == 1) ? 'rating__icon rating__icon--star fa fa-star-half' : 'rating__icon rating__icon--star fa fa-star';
var val = ins + 1;
		return "<label key={+ins+} className='rating__label "+ classReview + "' for='rating-"+parseInt(ins + 1)+"'>" +
			"<i className='"+iClassReview+"'></i>" +
			"</label>" +
			"<input className='rating__input' name='mark' id='rating-"+parseInt(ins + 1)+"' defaultChecked={checked} value='"+ val +"' type='radio' />";
	}

	element = <div className="form-group row">
		<div className="col-sm-5 col-md-4 col-lg-3 col-form-label">
			{Joomla.JText._('COM_SMARTSHOP_RATING')}
		</div>

		<div className="col-sm-7 col-md-8 col-lg-9 py-2">
			<div className="rating rating--hover">
				<input  className="rating__input rating__input--none"
				                                custom
				                                type='radio'
				                                name="mark" id="rating-0"
				                                defaultValue="0"
							 defaultChecked={checked} onChange={() => setChecked(!checked)}
							 label={Joomla.JText._('COM_SMARTSHOP_SEARCH_ANY')} />
					{/*<input className="rating__input rating__input--none" checked name="mark" id="rating-0" value="0"*/}
					{/*	   type="radio" />*/}
					<Form.Label className="rating__label" htmlFor="rating-0">&nbsp;</Form.Label>
					{result.map((value, ins) =>
						 Parser(reviewBloc(value, ins))


					)}

				</div>
			</div>

			<div className="clearfix"></div>
		</div>;

	return (element);
}
export default Review_rating;
