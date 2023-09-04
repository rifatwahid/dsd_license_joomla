import React, { useState, useEffect } from '../../../js/react/node_modules/react';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import {
	BrowserRouter as Router,
	Switch,
	Route,
	Link,
	useParams,withRouter
	,useHistory,
	Redirect
} from '../../../js/react/node_modules/react-router-dom';

const Deleteaddress = () => {
	let [data, setData] = useState(dataJson);
	let updateData=(value)=> {
		setData(value);
	}
	useEffect(() => {
			fetch(window.location.href  + '&ajax=1', {
				method: "GET",
			}) .then(res => res.json())
				.then((result) => {
					updateData(result);
				})},
		[]);
	let element = '';
	if(data.redirectLink){
		element = <Redirect
			to={{
				pathname: data.redirectLink,
				state: { referrer: data.message }
			}}
		/>;
	}
	return element;
	}

	export default Deleteaddress;