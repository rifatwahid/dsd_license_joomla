import React, { useState } from '../../../js/react/node_modules/react';
import nl2br from '../../../js/react/node_modules/react-nl2br';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';
import shopProductCommon from '../../../js/src/controllers/product/common.js';

const Media_product_block = (data) => {

	const pathToNoImage = data.data.image_product_path + '/' + data.data.noimage;
	let mediaIteration = 0;
	function getStyle(media){
		if(media.media_abstract_type == 'video') {
			return 'width="' + data.data.config.video_product_width + '" height="' + data.data.config.video_product_height ;
		}else {
			return '';
		}
	}
	if(data.data.images != null) {
		const result = Object.keys(data.data.images).map((key) => data.data.images[key]);
		const element = <div>{(data.product.label_id != 0) ?
			<div className="product_label">
				{(typeof data.product._label_image != 'undefined') ?
					<Image src={data.product._label_image}
						   alt={nl2br(data.product._label_name ?? data.product.name)}/>
					:
					<span className="label_name">
    						{data.product._label_name}
    					</span>
				}
			</div>
			: ''}

			<div id='list_product_image_middle' className="mb-5">
				{(data.data.images == '') ?
					<Image id="main_image" src={pathToNoImage} alt={nl2br(data.product.name)}
						   className="img-fluid w-100 lightbox"/>
					:


					result.map((media, k) =>
						<span key={k}>
    								<a className={(k != 0) ? 'lightbox display--none' : 'lightbox'}
									   id={"main_image_full_" + media.id} href={media.preparedLinkToMedia}
									   rel="sliderElement">
    									<Image id={"main_image_" + media.id} src={media.preparedLinkToPreviewMedia}
											   alt={nl2br(media.media_title ?? data.product.name)}
											   className="img-fluid w-100"/>
    								</a>
    							</span>
					)

				}

			</div>

			{(data.data.images.length > 0) ?
				<div className="row mt-2" id="list_product_image_thumb">
					{data.data.images.map((media, k) =>
						<div className="col-3 col-lg-3 mb-2" key={k}>
							<Image className="img-fluid w-100" src={media.preparedLinkToPreviewMedia}
								   alt={htmlspecialchars(media.media_title ?? data.product.name)} onClick={(e) => {
								shopProductCommon.showImage(media.id);
							}}/>
						</div>
					)}
				</div>
				: ''}
		</div>;

		return (element);
	}
	return ('');
}

export default Media_product_block;
