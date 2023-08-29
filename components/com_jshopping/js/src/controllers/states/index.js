class ShopStates {
	constructor() {
		document.addEventListener("DOMContentLoaded", () => {
			if (!shopHelper.isCheckoutPage() && !shopHelper.isCartPage() && !shopHelper.isOrderPage() && !shopHelper.isRegisterPage()) {
				return;
			}
			var state = (document.querySelector('#state') != null) ? document.querySelector('#state').value : '';
			var d_state = (document.querySelector('#d_state') != null) ? document.querySelector('#d_state').value : '';
			var country = (document.querySelector('#country') != null) ? document.querySelector('#country').value : '';
			var d_country = (document.querySelector('#d_country') != null) ? document.querySelector('#d_country').value : '';
			var country_cart = (document.querySelector('.country_cart') != null) ? document.querySelector('.country_cart').value : '';
			var address_id = (document.querySelector('input[name=editId]') != null) ? document.querySelector('input[name=editId]').value : '';

			if(document.querySelector('#country') != null && document.getElementById('state') != null) 
				if (address_id != ""){
					document.querySelector('#country').setAttribute("onchange", "shopStates.getState(this.id, this.value, '"+state+"', address_id);");
				} else {
					document.querySelector('#country').setAttribute("onchange", "shopStates.getState(this.id, this.value, '"+state+"', '');");
				}
			
			if(document.querySelector('#d_country') != null && document.getElementById('d_state') != null) document.querySelector('#d_country').setAttribute("onchange", "shopStates.getState(this.id, this.value, '"+d_state+"',address_id);");
				if (address_id != ""){
					if(document.querySelector('.country_cart') != null ) document.querySelector('.country_cart').setAttribute("onchange", "shopStates.getState('country_cart', this.value,'"+state+"',address_id);shopCart.getShippingPrice(this.id, this.value, '"+state+"');");
				} else {
					if(document.querySelector('.country_cart') != null ) document.querySelector('.country_cart').setAttribute("onchange", "shopStates.getState('country_cart', this.value,'"+state+"','');shopCart.getShippingPrice(this.id, this.value, '"+state+"');");
				}			if(document.querySelector('.country_cart') != null ) document.querySelector('.country_cart').setAttribute("onchange", "shopStates.getState('country_cart', this.value,'"+state+"',address_id);shopCart.getShippingPrice(this.id, this.value, '"+state+"');");
			
			if(document.querySelector('form[name=shipping_cart]') != null && document.querySelector('form[name=shipping_cart]').length > 0){
				shopStates.getState("country_cart", country, state, address_id);
			}else{
				shopStates.getState("country", country, state, address_id);
				shopStates.getState("d_country", d_country, d_state, address_id);
			}
			if(document.querySelector('.one_click_buy_window__modal-dialog button.close') != null){
				document.querySelector('.one_click_buy_window__modal-dialog button.close').addEventListener('click', function(){
					refreshDataForAllSections();
				});
			}
				
				
			if(document.querySelector("#countries_id") != null){
				let obj_p = document.querySelector("#countries_id").parentNode;				
				obj_p.innerHTML  += "<div id='qwerty'></div>";
			}
			if((document.querySelector("#countries_id")) != null){
				shopStates.getState2(document.querySelector("#countries_id").value);
			}

			if(document.querySelector("#countries_id") != null){
				document.querySelector("#countries_id").addEventListener('change', function() {
					if (this.value.length < 2) {
						shopStates.getState2(this.value);
					} else {
						shopStates.getState2([],[]);
					}
				});
			}
				
		});

	}
	
	getState(id, value, state_val, address_id) {
		state_val = state_val ? state_val : 0;
		let data = {
			country_id : value,
			address_id : address_id,
			id,
			state_val
		};
		var xhr = new XMLHttpRequest();
		xhr.open("GET", Joomla.getOptions('urlStates') + '&' + shopHelper.objToStr(data), true);
		xhr.setRequestHeader('Content-Type', 'application/json');
		xhr.send(data);
		xhr.onload = function() {
			var json = JSON.parse(this.responseText);
			if (json.status=='OK') {
				if (document.querySelector("#state") && (id == 'country' || id == 'country_cart')){
					let state = document.querySelector("#state").parentNode;
					state.innerHTML = json.select_states;
				}
				if (document.querySelector("#d_state") && id=='d_country'){				
					let d_state = document.querySelector("#d_state").parentNode;
					d_state.innerHTML = json.select_states;	
				} 
			}
		};		
	}

	see_states(thhis) {
		let id = thhis.options[thhis.selectedIndex].value;
		let inputbox = document.querySelector(".inputbox_st")
		let state = document.querySelector('#state_c_' + id)
		shopHelper.hide(inputbox);
		shopHelper.show(state); 
	}

	getState2(value) {
		let exttaxes_id = Joomla.getOptions('exttaxes_id');
		let data = { value, exttaxes_id };
		let urlToStates = Joomla.getOptions('urlStates2');
		if (urlToStates) {
			urlToStates += '&' + shopHelper.objToStr(data);
			var xhr = new XMLHttpRequest();
			xhr.open("GET", urlToStates, true);
			xhr.setRequestHeader('Content-Type', 'application/json');
			xhr.send(data);
			xhr.onload = function() {
				var json = JSON.parse(this.responseText);
				if (json.status=='OK') {					
					let obj_p = document.querySelector("#qwerty");				
					obj_p.innerHTML = json.html;
					if(json.test)alert(json.test);
				}
			};
		}		
	}

}

export default new ShopStates();