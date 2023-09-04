import React, { useState } from '../../../js/react/node_modules/react';

const Showmarkstar = (props) => {
	let data = props.data;
	const typeOfStar = data.config.rating_starparts;
	let isHalfStar;
	let classReview;
	let iClassReview;
	let i = 0;
	let element = '';
	if (data.config.max_mark > 0) {const result = [];
		for(let i = 0; i < data.config.max_mark; i++) {
			result[i] = i;
		}
		element = <div className="rating rating--static">
			{result.map((value, ins) =>
				<div>
					{/*{i = i + 1}*/}
					{isHalfStar = (typeof typeOfStar == 'undefinde' || typeOfStar == 'null' && (ins % 2 == 1)) ? true : false}
					{classReview = (isHalfStar == 1) ? 'rating__label--half' : ''}
					{/*{iClassReview = (isHalfStar == 1) ? 'fa-star-half' : 'fa-star'}*/}
					<label key={ins} className={(ins < props.rating) ? 'rating__label checked'+ classReview : 'rating__label '+ classReview}>
						<i className={(isHalfStar == 1) ? 'rating__icon rating__icon--star fa fa-star-half' : 'rating__icon rating__icon--star fa fa-star'}></i>
					</label>
				</div>
			)}
			<div className="clearfix"></div>
		</div>

	;
}
return (element);
}
export default Showmarkstar;
