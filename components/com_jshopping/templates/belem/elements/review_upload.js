import React, { useState } from '../../../js/react/node_modules/react';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Parser from '../../../js/react/node_modules/html-react-parser';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import shopProduct from '../../../js/src/controllers/product/index.js';

const Review_upload = () => {
	jQuery(document).ready(() => {
		jQuery('fileElem').change(function(){
			shopProduct.review_uploadfiles_by_button(this.files)
		});
	});

	const element = (dataJson.allow_reviews_uploads == 1) ?
		<div><div className="dropzone" id="dropzone">
			<p>
					<div id='uploadfiletitle'>&nbsp;</div>
			</p>
			<p>
				<span id="upload-icon" className="icon-upload" aria-hidden="true"></span>
			</p>
		<div className="upload-actions">
			<p className="lead">
				{Joomla.JText._('COM_SMARTSHOP_REVIEW_DRAG_AND_DROP_FILES_HERE')}<br/>

			</p>
			<p>
			{Joomla.JText._('COM_SMARTSHOP_REVIEW_OR')}<br/><br/>
			<Button id="select-file-button" type="button" className="btn btn-success" onclick={(e) => document.getElementById('fileElem').click()}>
				<span className="icon-copy" aria-hidden="true"></span>
				{Joomla.JText._('COM_SMARTSHOP_REVIEW_BROWSE_FILES')}
				</Button>
			</p>
		</div>
	</div>
	<input className="box__file" type="file" name="file[]" id="fileElem" accept="image/*" data-multiple-caption="{count} files selected"  multiple="multiple" />
	<input type='hidden' id='img_path' value={dataJson.config.files_product_review_live_path+"/"} />
	<input type='hidden' id='reviewfile' name='reviewfile' value="" />
	<div id='review_upload_files'>
</div>
<input type='hidden' id='reviewfiles' name='reviewfiles' value="0" />
<input type='hidden' id='review_max_uploads' name='review_max_uploads' value={dataJson.config.review_max_uploads} />
<br/></div>
: '';

	return (element);
}
export default Review_upload;
