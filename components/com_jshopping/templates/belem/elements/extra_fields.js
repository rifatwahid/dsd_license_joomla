import React, { useState } from '../../../js/react/node_modules/react';
import Parser from '../../../js/react/node_modules/html-react-parser';

const Extra_fields = (props) => {
	let data = props.data;
	const result = Object.keys(data.product.extra_field).map((key) => data.product.extra_field[key]);

	const element = (Array.isArray(data.product.extra_field) && data.product.extra_field.length > 0) ?
	<div className="extra_fields">
		{result.map((extra_field, i) =>
		<div key={i}>
			{(extra_field['grshow'] != 0) ?
				Parser("<div className='block_efg'>")
			: ''}
			{(extra_field['grshow'] != 0) ?
				<div className='extra_fields_group'>
					{extra_field['groupname']}
				</div>
			: ''}

			<div className="extra_fields_el">
				{(typeof extra_field['display'] != 'undefined' && extra_field['display'].length > 0) ? Parser(extra_field['display']) : ''}

				{(extra_field['description'] != '') ?
					<span className="extra_fields_description">
						{extra_field['description']}
					</span>
				: ''}
				{/*{': '}<span className="extra_fields_value">{Parser(extra_field['value'])}</span>*/}
			</div>

			{(extra_field['grshowclose']) ?
				Parser("</div>")
			: ''}
			</div>
		)}
	</div>
: '';

	return (element);
	}

	export default Extra_fields;