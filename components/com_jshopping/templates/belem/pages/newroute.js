import React, { useState, useCallback, useEffect,useLayoutEffect  } from '../../../js/react/node_modules/react';
import {
	BrowserRouter as Router,
	Switch,
	Route,
	Link,
	useParams,
	useLocation, browserHistory
} from '../../../js/react/node_modules/react-router-dom';
import Listorder from '../../../templates/belem/pages/listorder';
import Order from '../../../templates/belem/pages/order';
import Login from '../../../templates/belem/pages/login';
import Register from '../../../templates/belem/pages/register';
import Addresses from '../../../templates/belem/pages/addresses';
import Newaddress from '../../../templates/belem/pages/newaddress';
import Editaddress from '../../../templates/belem/pages/editaddress';
import Myaccount from '../../../templates/belem/pages/myaccount';
import Groupsinfo from '../../../templates/belem/pages/groupsinfo';
import Logout from '../../../templates/belem/pages/logout';
import Addressesmodal from '../../../templates/belem/pages/addresses_modal';
import Cart from '../../../templates/belem/pages/cart';
import Wishlist from '../../../templates/belem/pages/wishlist';
import Category_default from '../../../templates/belem/pages/category_default';
import Maincategory from '../../../templates/belem/pages/maincategory';
import Manufacturers from '../../../templates/belem/pages/manufacturers';
import Products_manufacturer from '../../../templates/belem/pages/products_manufacturer';
import Products from '../../../templates/belem/pages/products';
import Product_default from '../../../templates/belem/pages/product_default';
import Created_offer_and_order from '../../../templates/belem/pages/created_offer_and_order';
import Myoffer_and_order from '../../../templates/belem/pages/myoffer_and_order';
import Form_search from '../../../templates/belem/pages/form_search';
import Noresult_search from '../../../templates/belem/pages/noresult_search';
import Products_search from '../../../templates/belem/pages/products_search';
import Default_quick_checkout from '../../../templates/belem/pages/default_quick_checkout';
import Finish from '../../../templates/belem/pages/finish';
import Wishlist_btn from '../../../templates/belem/elements/wishlist_btn';
import Sprint_atribute from '../../../templates/belem/elements/sprint_atribute';
import Default_one_click_checkout from '../../../templates/belem/pages/_default_one_click_checkout';
import queryString from '../../../js/react/node_modules/query-string';

const Newroute = (params) => {
	let [data, setData] = useState('');
	let updateData=(value)=> {
		setData(value);
	}
	let uri_params = queryString.parse(params.location.search);
	for(let obj in uri_params){
		let data1 = uri_params[obj];
		if(obj.indexOf('amp;') == 0){
			obj = obj.substring(obj.length, 4);
			uri_params[obj] = data1;
		}else if(obj.indexOf('&') == 0){
			obj = obj.substring(obj.length, 1);
			uri_params[obj] = data1;
		}
	}
	// if(data.component == 'Product_default'){
	// 	return (<Route exact component={() => <Product_default data={data} />}
	// 				   path={window.location.pathname}/>);
	// }else if(data.component == 'Wishlist'){
	// 	return (
	// 		<Route exact component={Wishlist} path={window.location.pathname} />
	// 			);
	// }else if(data.component == 'Myaccount'){
	// 	return (
	// 		<Route exact component={Myaccount} path={window.location.pathname} />
	// 			);
	// }
	// //return (<Product_default/>);
	// if(uri_params.view == 'user' && uri_params.task == 'orders'){
	// 	return (<Listorder/>);
	// }else if(uri_params.view == 'user' && uri_params.task == 'order'){
	// 	return (<Order order_id={uri_params.order_id} />);
	// }else if(uri_params.view == 'user' && uri_params.task == 'myaccount'){
	// 	return (<Myaccount  />);
	// }else if(uri_params.view == 'repeatOrder'){
	// 	return (<Order order_id={uri_params.order_id} />);
	// }
	// let i;
	// uri_params.each((obj, i) => {
	// 	if(obj.indexOf('amp;') == 0){
	// 		obj = obj.substring(obj.length - 4);
	// 	}
	// 	if(obj == 'view'){
	//
	// 	}
	// });
	// if(uri_params['view'] == 'user' && (uri_params['task'] == 'myaccount' || uri_params['task'] == '')){
	// }

		 if(uri_params['task'] == 'orders'){
			 return (<Listorder />);
			 // <Route exact component={Listorder} path={window.location.pathname} />
		 }else if(uri_params['task'] == 'order'){
			 return (<Order />);
		  }else if(uri_params['task'] == 'addresses'){
			 return (<Addresses />);
		  }else if(uri_params['task'] == 'login'){
			 return (<Login />);
		  }else if(uri_params['task'] == 'register'){
			 return (<Register />);
		  }else if(uri_params['task'] == 'addresses'){
			 return (<Addresses />);
		  }else if(uri_params['task'] == 'addNewAddress'){
			 return (<Newaddress />);
		  }else if(uri_params['task'] == 'editaddress'){
			 return (<Editaddress />);
		  }else if(uri_params['task'] == 'addressPopup'){
			 return (<Addressesmodal />);
		  }else if(uri_params['task'] == 'created'){
			 return (<Created_offer_and_order />);
		  }else if(uri_params['task'] == 'myoffer_and_order') {
			 return (<Myoffer_and_order/>);
		 }else if(uri_params['task'] == 'result'){
			 return (<Products_search />);
		  }else if(uri_params['task'] == 'result'){
			 return (<Noresult_search />);
		  }else if((uri_params['view'] == 'search' || uri_params['controller'] == 'search') && (uri_params['task'] == 'display' || typeof uri_params['task'] == 'undefined' || uri_params['task'] == '')){
			 return (<Form_search />);
		  }else if((uri_params['view'] == 'user' || uri_params['controller'] == 'user') && (uri_params['task'] == 'myaccount' || typeof uri_params['task'] == 'undefined' || uri_params['task'] == '')){
			 return (<Myaccount />);
		  }else if((uri_params['view'] == 'products' || uri_params['controller'] == 'products') && (uri_params['task'] == 'display' || typeof uri_params['task'] == 'undefined' || uri_params['task'] == '')){
			 return (<Products />);
		  }else if((uri_params['view'] == 'product' || uri_params['controller'] == 'product') && (uri_params['task'] == 'view' || typeof uri_params['task'] == 'undefined' || uri_params['task'] == '')){
			 return (<Product_default />);
		  }else if((uri_params['view'] == 'category' || uri_params['controller'] == 'category') && (uri_params['task'] == 'view' || typeof uri_params['task'] == 'undefined' || uri_params['task'] == '')){
			 return (<Category_default />);
		  }else if((uri_params['view'] == 'category' || uri_params['controller'] == 'category') && (uri_params['task'] == 'display' || typeof uri_params['task'] == 'undefined' || uri_params['task'] == '')){
			 return (<Maincategory />);
		  }else if((uri_params['view'] == 'manufacturer' || uri_params['controller'] == 'manufacturer') && (uri_params['task'] == 'view' || typeof uri_params['task'] == 'undefined' || uri_params['task'] == '')){
			 return (<Manufacturers />);
		  }else if((uri_params['view'] == 'manufacturer' || uri_params['controller'] == 'manufacturer') && (uri_params['task'] == 'display' || typeof uri_params['task'] == 'undefined' || uri_params['task'] == '')){
			 return (<Products_manufacturer />);
		  }else if((uri_params['view'] == 'cart' || uri_params['controller'] == 'cart') && (uri_params['task'] == 'view' || typeof uri_params['task'] == 'undefined' || uri_params['task'] == '')){
			 return (<Cart />);
		  }else if((uri_params['view'] == 'qcheckout' || uri_params['controller'] == 'qcheckout') && (uri_params['task'] == 'view' || typeof uri_params['task'] == 'undefined' || uri_params['task'] == '')){
			 return (<Default_quick_checkout />);
		  }else if((uri_params['view'] == 'qcheckout' || uri_params['controller'] == 'qcheckout') && (uri_params['task'] == 'finish')){
			 return (<Finish />);
		  }else if((uri_params['view'] == 'wishlist' || uri_params['controller'] == 'wishlist') && (uri_params['task'] == 'view' || typeof uri_params['task'] == 'undefined' || uri_params['task'] == '')){
			 return (<Wishlist />);
		  }
			 // else if(data.component == 'Login'){
			//  return (<Login />);
		 // }else if(data.component == 'Register'){
			//  return (<Register />);
		 // }else if(data.component == 'Addresses'){
			//  return (<Addresses />);
		 // }else if(data.component == 'Newaddress'){
			//  return (<Newaddress />);
		 // }else if(data.component == 'Editaddress'){
			//  return (<Editaddress />);
		 // }else if(data.component == 'Myaccount'){
			//  return (<Myaccount />);
		 // }else if(data.component == 'Groupsinfo'){
			//  return (<Groupsinfo />);
		 // }else if(data.component == 'Logout'){
			//  return (<Logout />);
		 // }else if(data.component == 'Addressesmodal'){
			//  return (<Addressesmodal />);
		 // }else if(data.component == 'Cart'){
			//  return (<Cart />);
		 // }else if(data.component == 'Wishlist'){
			//  return (<Wishlist />);
		 // }else if(data.component == 'Category_default'){
			//  return (<Category_default />);
		 // }else if(data.component == 'Maincategory'){
			//  return (<Maincategory />);
		 // }else if(data.component == 'Manufacturers'){
			//  return (<Manufacturers />);
		 // }else if(data.component == 'Products_manufacturer'){
			//  return (<Products_manufacturer />);
		 // }else if(data.component == 'Products'){
			//  return (<Products/>);
		 // }else if(data.component == 'Product_default'){
			//  if(jQuery('#default_one_click_checkout').length > 0){
			// 	 return (<Default_one_click_checkout />);
			//  }else{
			// 	 return (<Product_default/>);
			//  }
		 //
		 // }else if(dataJson.component == 'Created_offer_and_order'){
			//  return (<Created_offer_and_order />);
		 // }else if(dataJson.component == 'Myoffer_and_order'){
			//  return (<Myoffer_and_order />);
		 // }else if(dataJson.component == 'Form_search'){
			//  return (<Form_search />);
		 // }else if(dataJson.component == 'Products_search'){
			//  return (<Products_search />);
		 // }else if(dataJson.component == 'Noresult_search'){
			//  return (<Noresult_search />);
		 // }else if(dataJson.component == 'Default_quick_checkout'){
			//  return (<Default_quick_checkout />);
		 // }else if(dataJson.component == 'Finish'){
			//  return (<Finish />);
		 // }else if(dataJson.component == 'Wishlist_btn'){
			//  return (<Wishlist_btn />);
		 // }else if(dataJson.component == 'sprint_atribute'){
			//  return (<Sprint_atribute />);
		 // }else if(typeof dataJsonPopup != 'undefined' && dataJsonPopup.component == 'default_one_click_checkout'){
			//  return (<Default_one_click_checkout />);
		 // }
	 else{
	 	return '';
		 }
}
export default Newroute;



