import React, { useState } from '../../../js/react/node_modules/react';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import shopUserAddressesPopup from '../../../js/src/controllers/user/useraddressespopup.js';

const Renderbutton = (data) => {
	function setData(){
		shopUserAddressesPopup.setAddressTypeToHandler(data.addrType);
		let link='/index.php?option=com_jshopping&controller=user&task=addressPopup&addrType='+data.addrType;
		jQuery('iframe').attr('src',link);
	}
	const element = <a href="" className={data.btnId + '__changeAddress'} data-toggle="modal" data-target={"#" + data.dataTarget} onClick={(e) => {setData()}} >
			{data.btnText}
	</a>;
	return (element);
	}

	export default Renderbutton;