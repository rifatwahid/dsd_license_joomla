import React, { useState } from '../../../js/react/node_modules/react';
import nl2br from '../../../js/react/node_modules/react-nl2br';
import Moment from '../../../js/react/node_modules/react-moment';
import File_exists from '../elements/file_exists.js';
import Showmarkstar from '../elements/showmarkstar.js';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import Review_rating from '../elements/review_rating.js';
import Review_upload from '../elements/review_upload.js';
import Parser from '../../../js/react/node_modules/html-react-parser';

const Review = (props) => {
	let data = props.data;
	function reactSplit(reviewfile){
		return reviewfile.split('|');
	}
	const element = (typeof data.reviews != 'undefined') ?
		<section className="my-4 product-review">
			<h3 className="product-review__page-title">{Joomla.JText._('COM_SMARTSHOP_REVIEWS')}</h3>

		<ul className="list-group list-group-flush">
			{data.reviews.map((curr, ins) =>

			<li className="list-group-item px-0">
				{(curr.mark > 0) ? <Showmarkstar rating={curr.mark} /> : ''}
				<span className="d-block my-1">
					{curr.user_name}
				</span>

				<p className="my-1">
					{nl2br(curr.review)}
				</p>

				<small className="text-muted">
					<Moment date={curr.time} format='D.MM.Y' />
				</small>

				{(reactSplit(curr.reviewfile).length > 0) ?
				<div className="row">
					{reactSplit(curr.reviewfile).map((reviewfile, ins) =>
						<div className="col-sm-4 col-md-3 col-lg-2 col-6 mb-3 card-group">
							{(reviewfile != '' && <File_exists link={data.file_exists_link} file={data.config.files_product_review_path + '/' + reviewfile} />) ?
								<a href={data.config.files_product_review_path + '/' + reviewfile} target="_blank">
									<Image src={data.config.files_product_review_path + '/' + reviewfile}/>
								</a>
							: ''}
						</div>
					)}
				</div>
				: ''}
			</li>
			)}
		</ul>

		{(data.display_pagination) ?
			<div className="my-4">{data.pagination}</div>
		: ''}

			{(data.allow_review > 0) ?
				<span>
				<h5 className="mb-4 mt-2">{Joomla.JText._('COM_SMARTSHOP_WRITE_REVIEW')}</h5>

				<div id="product-review-alerts">
				</div>

				<Form action={data.reviewsave} id="productReviewForm" name="add_review" method="post"
					  encType="multipart/form-data">
					<input type="hidden" name="product_id" value={data.product.product_id}/>
					<input type="hidden" name="back_link" value={data.request_uri}/>
					<input type="hidden" name={Joomla.getOptions('csrf.token')} value="1"/>


					<Review_rating />

					<div className="form-group row">
					<Form.Label htmlFor="review_user_name" className="col-sm-5 col-md-4 col-lg-3 col-form-label">
						{Joomla.JText._('COM_SMARTSHOP_NAME')}
					</Form.Label>
					<div className="col-sm-7 col-md-8 col-lg-9">
						<Form.Control type="text" name="user_name" id="review_user_name" className="input"
									  placeholder={Joomla.JText._('COM_SMARTSHOP_NAME')}
									  defaultValue={data.user.username}/>
					</div>
				</div>

				<div className="form-group row">
					<Form.Label htmlFor="review_user_email" className="col-sm-5 col-md-4 col-lg-3 col-form-label">
						{Joomla.JText._('COM_SMARTSHOP_EMAIL')}
					</Form.Label>

					<div className="col-sm-7 col-md-8 col-lg-9">
						<input type="text" name="user_email" id="review_user_email" className="input"
							   placeholder={Joomla.JText._('COM_SMARTSHOP_EMAIL')} defaultValue={data.user.email}/>
					</div>
				</div>

				<div className="form-group row">
					<Form.Label htmlFor="review_review" className="col-sm-5 col-md-4 col-lg-3 col-form-label">
						{Joomla.JText._('COM_SMARTSHOP_COMMENT')}
					</Form.Label>

					<div className="col-sm-7 col-md-8 col-lg-9">
						<Form.Control as="textarea" name="review" rows={3} id="review_review"
									  placeholder={Joomla.JText._('COM_SMARTSHOP_COMMENT')}
									  className="form-control w-100"/>
					</div>
				</div>

				<div className="row" id="uploadfileimage">
				</div>

					<Review_upload />

					{/*<?php include templateOverrideBlock('blocks', 'review_upload.php'); ?>*/}


					<div className="form-group row">
					<div className="col-sm-7 col-md-8 col-lg-9 offset-sm-5 offset-md-4 offset-lg-3">
						<Button variant="outline-primary" type="submit" onclick=""
								className="btn-block col-md-6 float-right">{Joomla.JText._('COM_SMARTSHOP_SUBMIT_REVIEW')}</Button>
					</div>
				</div>

				</Form>
				</span>
				: (data.allow_review == -2) ?
					<p className="mt-2">{Joomla.JText._('COM_SMARTSHOP_BUY_FIRST_FOR_WRITE_REVIEW')}</p>
					:
					<p className="mt-2">{Joomla.JText._('COM_SMARTSHOP_LOGIN_TO_WRITE_REVIEW')}</p>
			}
			</section>
			: '';
		return (element);
	}

	export default Review;