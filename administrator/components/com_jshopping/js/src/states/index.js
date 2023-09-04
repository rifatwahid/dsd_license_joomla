class ShopStates {
	
	constructor() {
		document.addEventListener('DOMContentLoaded', function() {	
			let countryEl = document.querySelector('#country');
			let dCountryEl = document.querySelector('#d_country');
			let countriesIdEl = document.querySelector('#countries_id');

			if (countryEl) {
				countryEl.setAttribute('onchange', "shopStates.getState(this.id, this.value, document.querySelector('#state').value);");

				let stateEl = document.querySelector('#state');
				if (stateEl) {
					shopStates.getState("country", countryEl.value, stateEl.value);
				}
			}

			if (dCountryEl) {
				dCountryEl.setAttribute('onchange', "shopStates.getState(this.id, this.value, document.querySelector('#d_state').value);");
				
				let dStateEl = document.querySelector('#state');
				if (dStateEl) {
					shopStates.getState("d_country", dCountryEl.value, dStateEl.value);
				}
			}

			let btnCloseEl = document.querySelector('#shop-qcheckout .modal-header button.close');

			if (btnCloseEl) {
				btnCloseEl.addEventListener('click', function () {
					refreshDataForAllSections();
				});
			}

			if (countriesIdEl) {
				let obj_p = countriesIdEl.parentNode;
				obj_p.insertAdjacentHTML('beforeend', "<div id='qwerty'></div>");
				shopStates.getState2(countriesIdEl.value);
			}	
			var values = [];
			if(document.querySelector("#countries_id") != null){
				document.querySelector("#countries_id").addEventListener('change', function() {
					for ( var i = 0; i < this.selectedOptions.length; i++) {
						values[i] = this.selectedOptions[i].value;
					}
					if (values.length < 2) {
						shopStates.getState2(values);
					} else {
						shopStates.getState2([],[]);
					}
				});
			}
		});
	}

	getState(id, value, state_val) {
		let url = Joomla.getOptions('urlStates') + `&country_id=${value}&id=${id}&state_val=${state_val}`;

		fetch(url)
		.then(response => response.json())
		.then(json => {
			if (json.status == 'OK') {                            
				if (id == 'country') {
					let countryEl = document.querySelector('#state');

					if (countryEl) {
						countryEl.parentNode.innerHTML = json.select_states;
					}
				}

				if (id == 'd_country') {
					let dStateEl = document.querySelector('#d_state');

					if (dStateEl) {
						dStateEl.parentNode.innerHTML = json.select_states;
					}
				}
			}
		})
	}

	see_states(thhis) {
		let id = thhis.options[thhis.selectedIndex].value;
		let inputBoxStEl = document.querySelector('.inputbox_st');
		let stateCEl = document.querySelector(`#state_c_${id}`);

		if (inputBoxStEl) {
			inputBoxStEl.style.display = 'none';
		}

		if (stateCEl) {
			stateCEl.style.display = 'block';
		}
	}



	getState2(value) {
		let exttaxes_id = Joomla.getOptions('exttaxes_id');
		let data = { value, exttaxes_id };
		let urlToStates = Joomla.getOptions('urlStates2');
		console.log(urlToStates);
		if (urlToStates) {
			urlToStates += '&' + shopStates.objToStr(data);
			console.log(urlToStates);
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

	getShippState(obj) {
		const selected = document.querySelectorAll('#shipping_countries_id option:checked');
		if(document.querySelector('.states_list')){
			document.querySelector('.states_list').remove();
		}

		if(selected){
			const values = Array.from(selected).map(el => el.value);
			if(values.length == 1){
				let urlToStates = Joomla.getOptions('urlShippStates');
				let data = { country_id: obj.value };
				urlToStates += '&country_id=' + obj.value;

				var xhr = new XMLHttpRequest();
				xhr.open("GET", urlToStates, true);
				xhr.setRequestHeader('Content-Type', 'application/json');
				xhr.send(data);
				xhr.onload = function() {
					var json = JSON.parse(this.responseText);
					console.log(json.html);
					if (json.html !='') {
						var obj_p = document.querySelectorAll(".shippingsprices_countries .form-group:first-child");
						obj_p[0].insertAdjacentHTML('afterend', json.html);

					}
				};
			}else{

			}
		}

		let urlToStates = Joomla.getOptions('urlStates2');
		console.log(urlToStates);
		if (urlToStates) {
			urlToStates += '&' + shopStates.objToStr(data);
			console.log(urlToStates);
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

	objToStr(obj){
		var str = [];
	   for(var p in obj){
		   if (obj.hasOwnProperty(p)) {
			   str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
		   }
	   }
	   return str.join("&");
	}
	
	
}

export default new ShopStates();