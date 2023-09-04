import shopProductAttributes from './attributes.js'
import shopHelper from '../../common/helper/index.js';

class ShopProductFreeAttributes {

    constructor() {
       document.addEventListener('DOMContentLoaded', () => {
            if (shopHelper.isProductPage()) {
                this.setData();
                this.messages();
            }
        });

        shopProductAttributes.events[shopProductAttributes.events.length] = (json) => {
            if (json.jshop_facp_result && json.jshop_facp_label) {
                if(document.querySelector("#jshop_facp_label") != null) document.querySelector("#jshop_facp_label").innerHTML = json.jshop_facp_label + ":";
                if(document.querySelector("#jshop_facp_result") != null) document.querySelector("#jshop_facp_result").innerHTML = json.jshop_facp_result;
                if(document.querySelector("#jshop_facp_suffix") != null) document.querySelector("#jshop_facp_suffix").innerHTML = json.jshop_facp_suffix;
                if(document.querySelector("#jshop_facp_block") != null) shopHelper.show(document.querySelector("#jshop_facp_block"));
            } else {
                if(document.querySelector("#jshop_facp_block") != null) shopHelper.hide(document.querySelector("#jshop_facp_block"));
            }
        };
		
		document.addEventListener('DOMContentLoaded', () => {
				var arr = [];
				document.querySelectorAll("#productForm .inputbox,#productForm input,#productForm select").forEach(function(el){
					if(el.type != 'hidden' && el.name){
						arr.push(el.name);
					}
				});
			document.querySelectorAll("#productForm").forEach(function(el){
				el.addEventListener('keydown', function(event) {
					var active = document.activeElement;
					if(event.which == 9){ 
						if(arr.indexOf(active.name) !== -1 && arr[arr.indexOf(active.name)] && (!active_el || (active_el && arr.indexOf(active.name) >= arr.indexOf(active_el)))){
							if(arr[arr.indexOf(active.name)].indexOf('freeattribut') >= 0 || arr[arr.indexOf(active.name)].indexOf('jshop_attr_id') >= 0){
								active_el = arr[arr.indexOf(active.name) + 1];
							}else{
								active_el = arr[arr.indexOf(active.name)];
							}
						}							
					}else if(event.which >= 37 && event.which <= 40){
						if(arr[arr.indexOf(active.name)].indexOf('jshop_attr_id') >= 0){
							active_el = arr[arr.indexOf(active.name)];
						}
					}else if(event.which == 9 && active_el && arr.indexOf(active.name) < arr.indexOf(active_el)){
						active_el = arr[arr.indexOf(active.name)- 1];
					}
					
					if(event.which == 13 && active.type != 'submit' && active.type != 'button' ){
						event.preventDefault();
					}
				});
			
			});
		});
    }

    recalculate(hide_message) {
        this.setData();
        this.messages(hide_message);
    }

    messages(hide_message) {
        shopProductAttributes.events[shopProductAttributes.events.length] = (json) => {
            if (hide_message === undefined) hide_message = true;

            if (hide_message) {
                let message = document.querySelectorAll('#calcule-price-message1')[0];
                if (message != undefined) message.remove();
                if ((json.jshop_facp_system_message != undefined) && (json.jshop_facp_system_message != '')) {

                    document.querySelector('#system-message-container').innerHTML += `
                        <div id="calcule-price-message1">
                            <dt class="notice">${Joomla.JText._('COM_SMARTSHOP_NOTE')}</dt>
                            <dd class="notice message">
                                ${json.jshop_facp_system_message}
                            </dd>
                        </div>
                    `;

                    //if(document.querySelector('#calcule-price-message1 .text_message') != null) document.querySelector('#calcule-price-message1 .text_message').innerHTML = json.jshop_facp_system_message;
                }
            }

            if (json.jshop_facp_result && json.jshop_facp_label) {
                if(document.querySelector("#jshop_facp_label") != null) document.querySelector("#jshop_facp_label").innerHTML = json.jshop_facp_label + ":";
                if(document.querySelector("#jshop_facp_result") != null) document.querySelector("#jshop_facp_result").innerHTML = json.jshop_facp_result;
                if(document.querySelector("#jshop_facp_suffix") != null) document.querySelector("#jshop_facp_suffix").innerHTML = json.jshop_facp_suffix;
                if(document.querySelector("#jshop_facp_block") != null) shopHelper.show(document.querySelector("#jshop_facp_block"));
            } else {
                if(document.querySelector("#jshop_facp_block") != null) shopHelper.hide(document.querySelector("#jshop_facp_block"));
            }

            if (json.jshop_facp_qty_result > 0) document.querySelector("#quantity").value = parseFloat(json.jshop_facp_qty_result);
        };
    }

    setData() {

        for(var k in shopProductAttributes.free_attr) {
            if (k.match(/freeattr/)) delete shopProductAttributes.free_attr[k];
        }
		
		if(document.querySelector("[name^='freeattribut']") != null){
			var freeattributs = document.querySelectorAll("[name^='freeattribut']");
			for (var i = 0, len = freeattributs.length; i < len; i++) {
			//document.querySelectorAll("[name^='freeattribut']").each((i, val) => {
				let name = freeattributs[i].name;
				let value = freeattributs[i].value;
				let pattern = /\[(.*)\]/;
				let matches = name.match(pattern);
				let freeattrid = matches[1];
				shopProductAttributes.free_attr['freeattr[' + freeattrid + ']'] = value;
			}//);
		}
    
        window.shopProductAttributes.reloadPrice();
    }

    onKeyup (hide_message) { 
        if (window.id_time_out != undefined) clearTimeout(window.id_time_out); 

        window.id_time_out = setTimeout(() => this.recalculate(hide_message), 5000);
    }

}

export default new ShopProductFreeAttributes();