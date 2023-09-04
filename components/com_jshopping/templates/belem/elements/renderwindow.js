import React, { useState } from '../../../js/react/node_modules/react';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import shopUserAddressesPopup from '../../../js/src/controllers/user/useraddressespopup.js';
import Parser from '../../../js/react/node_modules/html-react-parser';

const Renderwindow = (data) => {

	const element = <div className="modal" id={data.modalId} tabIndex="-1" role="dialog" aria-labelledby=""
						 aria-hidden="true">
		<div className={"modal-dialog modal-dialog-centered "+data.modalId+"__modal-dialog"} role="document">
			<div className="modal-content">
				<div className="modal-header">
					<h5 className={"modal-title " + data.modalId + "__title"}>{data.modalTitle}</h5>

					<button type="button" className="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div className="modal-body">
					{Parser(data.modalBody)}
				</div>
			</div>
		</div>
	</div>;
	return (element);
	}

	export default Renderwindow;