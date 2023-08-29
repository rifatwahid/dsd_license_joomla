import ValidateForm from '../../common/validate_form/index.js';

class ShopSearch {

    validate(name) {
        const fields = {
            ids: ['date_from', 'date_to', 'price_from', 'price_to'],
            type: ['date|em', 'date|em', 'fl|em|0', 'fl|em'],
            params: ['', '', '', '']
        };

        const form = new ValidateForm(name, fields, 2);
        return form.validate(); 
    }

    updateCharacteristic(url, category_id) {
		category_id = shopHelper.dataTransform({category_id});		
		
			this.ajaxRequest = fetch(url, {
			  method: 'POST',
			  headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
			  },
			  body: category_id
			})

			.then(response => response.json())

			.then(data => {
				if(document.querySelector("#list_characteristics") && data) document.querySelector("#list_characteristics").innerHTML = data
			} );

    }

}

export default new ShopSearch();