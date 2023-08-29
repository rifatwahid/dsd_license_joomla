class ShopOneClickCheckout {
    constructor() {
        this.product_id = '';
        this.category_id = '';
        this.request = '';
        this.setUserAddresses = this.setUserAddresses.bind(this);
		if(window.self !== window.top){
			var elements = document.querySelectorAll('#sp-top-bar, header, footer');
			if(elements){
				elements.forEach(function(el){
					if(el){						
						el.style.display = 'none';
					}
				});
			}
			elements = document.querySelectorAll('#sp-main-body');
			if(elements){
				elements.forEach(function(el){
					if(el){						
						el.querySelector('.container').style.maxWidth  = '100%';
						el.style.padding = '0px';
					}
				});
			}
			//document.querySelector('.container').style.maxWidth  = '100%';
		}
	}

    add(product_id, category_id){
        var product_detailed = document.querySelector('form[name=product]');
		const formData = new FormData(product_detailed);
		const data = [];
		for (const [name, value] of formData) {
			data[name] = value ;
		}
		this.product_id = product_id;
        this.category_id = category_id;
        this.request = 'product';
        this.ajax_request(href_add, "json", data, this.view_cart, true);

        return false;
    }

    view_cart(data){
        let iframeEl = document.querySelector('#one_click_buy_window_iframe');
		var myModal = new bootstrap.Modal(document.querySelector("#one_click_buy_window"), {});		

        if (iframeEl && iframeEl.dataset.src) {
            iframeEl.src = iframeEl.dataset.src;
			document.onreadystatechange = function () {
			  myModal.show();
			};
        }
    }

     ajax_request(url, dataType, data, callback, POST){
		data = shopHelper.dataTransform(data);
		
		fetch(url, {
			  method: 'POST',
			  headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
			  },
			  body: JSON.stringify(data)
			})

			.then(response => response.json())

			.then(json => callback(json));	
			
   
	 }
    openNav(id) {
        document.getElementById(id).style.display = "block";
        var h = document.getElementById(id).offsetHeight + 60;
        document.querySelector('.one_click_checkout').style.height = h + 'px';
        document.querySelector('.one_click_checkout').style.overflow = 'hidden';
    }

    closeNav(id) {
        document.getElementById(id).style.display = "none";
        document.querySelector('.one_click_checkout').style.height = 'auto';
        document.querySelector('.one_click_checkout').style.overflow = 'auto';
    }

    reloadAddressData() {
        let data = {
            ajax: 1
        }

		data = shopHelper.dataTransform(data);
		
		fetch(href_address_data, {
			  method: 'POST',
			  headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
			  },
			  body: JSON.stringify(data)
			})

			.then(response => response.json())

			.then(html => {document.querySelector('#checkout_address_step .addressPopup__tbody').innerHTML = html; this.setUserAddresses});

    }

    setUserAddresses (){
        let data = {
            getUserAddresses: 1
        }

		data = shopHelper.dataTransform(data);
		
		fetch(href_address_data, {
			  method: 'POST',
			  headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
			  },
			  body: JSON.stringify(data)
			})

			.then(response => response.json())

			.then(res => shopUserAddressesPopup.setUserAddresses(res));

    }
}

export default new ShopOneClickCheckout();