class ShopOrder {

    constructor() {
        this.number = 100;
    }

    /**
     * uploadId - #__jshopping_order_items_native_uploads_files
     */
    deleteUploadedFile(uploadId, callbackSucess) {
        if (uploadId) {
            var url = '/administrator/index.php?option=com_jshopping&controller=orders&task=deleteUploadedFile&id=' + uploadId;
            return shopHelper.sendAjax(url, null, callbackSucess);
        }
    }

    deleteUploadedFileAndDeleteMarkUp(uploadId, markupSelector) {
        if (uploadId) {
            this.deleteUploadedFile(uploadId, function (response) {
                if (response) {
                    var encodedResponse = JSON.parse(response);

                    if (encodedResponse && encodedResponse.success && markupSelector) {
                        shopHelper.deleteMarkUp(markupSelector);
                    }
                }
            });

        }
    }

    generateNumber() {
        this.number++;
        return this.number;
    }

    addNewUploadRow(selectorWhereToPush, orderItemId) {
        if (selectorWhereToPush) {
            var containerForUploadEl = document.querySelector(selectorWhereToPush);

            if (containerForUploadEl) {
                var template = document.querySelector('#templateOfOrderUploadANewFile');
                var templateEl = template.content.cloneNode(true);

                if (templateEl) {
                    let orderItemUploadsItemEl = templateEl.querySelector('.orderItemUploadsItem');

					if (orderItemUploadsItemEl) {
						orderItemUploadsItemEl.dataset.iteration = this.generateNumber();
						orderItemUploadsItemEl.querySelector('.orderItemUploadsItem__delete').setAttribute("onclick", "shopHelper.deleteMarkUp('" + selectorWhereToPush + " .orderItemUploadsItem[data-iteration=\"" + orderItemUploadsItemEl.dataset.iteration + "\"]'); return false;");

						orderItemUploadsItemEl.querySelector('.orderItemUploadsItem__qty').name = 'newUploads[' + orderItemId + '][qty][]';
						orderItemUploadsItemEl.querySelector('.orderItemUploadsItem__files').name = 'newUploads[' + orderItemId + '][file][]';
						orderItemUploadsItemEl.querySelector('.orderItemUploadsItem__description').name = 'newUploads[' + orderItemId + '][description][]';
						containerForUploadEl.appendChild(templateEl);
					}
                }
            }
        }
    }
	
	delete_from_package(t){
		t.style.display='none';
		var target=t.parentNode.parentNode;
		var new_id=target.id.substring(target.id.indexOf("_")+1);
		if (document.getElementById(new_id)){		
			document.getElementById(new_id).children[1].children[1].children[0].value=parseFloat(document.getElementById(new_id).children[1].children[1].children[0].value)+parseFloat(target.children[1].children[1].children[0].value);
			target.remove();
		}else{
			target.id=new_id;
			target.children[1].children[1].children[0].disabled=true;
			document.getElementById('shipping_packages').appendChild(target);
		}
		document.getElementById("shipping_packages_products").value=this.getPackagesProductsJson();
	}
	
	getPackagesProductsJson(){
		const packages=document.getElementById("packages");	
		var products_list = new Array();
		var packs_json="{";
		for (let i = 0; i < packages.children.length; i++) {
			var products=packages.children[i].children[2].children[0];	
		
			var pack_id=packages.children[i].id.substring(4);				
		if (packs_json[packs_json.length-1]=='}') packs_json=packs_json+',';
			packs_json=packs_json+'"'+pack_id+'":{';		
			var products_array = new Array();
			for (let j = 0; j < products.children.length; j++) {					
				var id=products.children[j].id;
				var product_id=id.substring(id.indexOf("_")+2);
				if (product_id.length>0) {
					//products_array[product_id]=parseFloat(products.children[j].children[1].children[1].innerHTML);//alert(packs_json[packs_json.length-1]);
					products_array[product_id]=parseFloat(products.children[j].children[1].children[1].children[0].value);//alert(packs_json[packs_json.length-1]);
					if (packs_json[packs_json.length-1]!='{') packs_json=packs_json+',';				
					//packs_json=packs_json+'"'+product_id+'":'+parseFloat(products.children[j].children[1].children[1].innerHTML);				
					packs_json=packs_json+'"'+product_id+'":'+parseFloat(products.children[j].children[1].children[1].children[0].value);				
				}
				
			}
			packs_json=packs_json+"}";
			products_list[pack_id] = products_array;		
		}
		packs_json=packs_json+"}";
		return packs_json;
	}
	
	
	setSavedPackages(e){
		let el = document.getElementById(e);
		if (el) {
			el.value = this.getPackagesProductsJson();
		}
	}
	
	orderedit_package_add(txt){
		package_index++;
		let packages = document.getElementById('packages');

		if (packages) {
			if(packages.children[0]){
				var new_package = packages.children[0].cloneNode(true);			
				if (new_package) {
					new_package.id = 'pack'+package_index;
					new_package.children[0].children[2].innerHTML=package_index;
					new_package.children[0].children[4].children[0].value="";
					new_package.children[1].children[1].children[0].value="";
					new_package.children[1].children[3].children[0].value="";
					new_package.children[2].children[0].innerHTML="<p>"+txt+"</p>";
					packages.appendChild(new_package);
				}
			}else{	
				var template = document.querySelector('#templateOfOrderPack');
				var templateEl = template.content.cloneNode(true);
				var new_package = templateEl.querySelector('#pack1');
				new_package.id = 'pack'+package_index;
				new_package.children[0].children[2].innerHTML=package_index;
				new_package.children[0].children[4].children[0].value="";
				new_package.children[1].children[1].children[0].value="";
				new_package.children[1].children[3].children[0].value="";
				new_package.children[2].children[0].innerHTML="<p>"+txt+"</p>";
				packages.appendChild(new_package);
			}
		}
	}
	
	productPackage_dragover_handler(ev) {
		ev.preventDefault();
		ev.dataTransfer.dropEffect = "copy";
		ev.dataTransfer.effectAllowed = "copy";
	}  
	
	productPackage_dragstart_handler(ev) {
		ev.dataTransfer.setData("text/html", ev.target.id);
		ev.dataTransfer.dropEffect = "copy";
		ev.dataTransfer.effectAllowed = "copy";
	}
	
	productPackage_change_quantity(t=this){
		if (typeof(t.name) != 'undefined' && t != null) { 
		if (parseFloat(t.value)<0){t.value=0;}
		var product_id=t.name.substring(t.name.indexOf('pq_')+3);
		var new_quantity=parseFloat(t.value);
		//PRODUCT EXIST IN NOT PACKAGES
		if (document.getElementById("p"+product_id)){
			var mainproduct=document.getElementById("p"+product_id).children[1].children[1].children[0];//alert(parseFloat(this.oldvalue)+parseFloat(mainproduct.value));
			if (parseFloat(t.value)>=parseFloat(t.oldvalue)+parseFloat(mainproduct.value)){
			//NEW QUANTITY >= TOTAL QUANTITY
				t.value=parseFloat(t.oldvalue)+parseFloat(mainproduct.value);
				mainproduct.value=0;
				document.getElementById("p"+product_id).remove();
			}else{
			//NEW QUANTITY < TOTAL QUANTITY
				mainproduct.value=(parseFloat(mainproduct.value)+parseFloat(t.oldvalue))-parseFloat(t.value);				
			}
		}else if(new_quantity<parseFloat(t.oldvalue)){
			
			var product=t.parentNode.parentNode.parentNode.cloneNode(true);
			product.addEventListener("dragstart", shopOrder.productPackage_dragstart_handler);
			product.id="p"+product_id;
			product.children[1].children[1].children[0].disabled=true;
			product.children[0].children[0].style.display="none";
			product.children[1].children[1].children[0].value=t.oldvalue-t.value;
			document.getElementById('shipping_packages').appendChild(product);
			//if (new_quantity<=0){t.parentNode.parentNode.parentNode.remove();}
			
		}else{
			t.value=t.oldvalue;
		}
		
		if (parseFloat(t.value)<=0){
			t.parentNode.parentNode.parentNode.remove();
		}
		document.getElementById("shipping_packages_products").value=shopOrder.getPackagesProductsJson();
		}else{t.value=t.oldvalue;}
		t.oldvalue=t.value;
	}
	productPackage_start_change_quantity(t=this){	
		t.oldvalue = t.value;
	}
	productPackage_drop_handler(ev) {
		//if (data.innerHTML=='undefined') return 0;
		ev.preventDefault();
		var target=ev.target;
		var data = ev.dataTransfer.getData("text/html");
		if (data.indexOf('">')>0) {
			data=data.substring(data.indexOf('">')+2);
		}		
		//alert(document.getElementById(data).innerHTML);
		var product_quantity=parseFloat(document.getElementById(data).children[1].children[1].children[0].value);
		//var product_quantity=parseFloat(document.getElementById(data).children[1].children[1].innerHTML);
		
		var destination=target.id;
		while (target.id!='packages') {
			if (target.id.indexOf('ack')>0){
				destination=target.id;
			}
			target=target.parentNode;
		}
		
		target=document.getElementById(destination).children[2].children[0];
		
		
		
		if (document.getElementById(data).id.indexOf("_")>0){
			var new_id=target.parentNode.parentNode.id+"_"+document.getElementById(data).id.substring(document.getElementById(data).id.indexOf("_")+1);
		}else{
			var new_id=target.parentNode.parentNode.id+"_"+document.getElementById(data).id;
		}
		if(document.getElementById(data).id==new_id){return;}
		//Set quantity
		var move_quantity=1;
		if(product_quantity>1){
			var move_quantity=parseFloat(prompt("Quantity"));
		}

		if (isNaN(move_quantity)) {move_quantity=product_quantity;}
		if (move_quantity>product_quantity) {move_quantity=product_quantity;}
		if (move_quantity<product_quantity){
			//document.getElementById(data).children[1].children[1].innerHTML=(move_quantity);
			document.getElementById(data).children[1].children[1].children[0].value=(move_quantity);
					
			if (document.getElementById(new_id)){
				//document.getElementById(new_id).children[1].children[1].innerHTML=parseFloat(document.getElementById(new_id).children[1].children[1].innerHTML)+move_quantity;
				document.getElementById(new_id).children[1].children[1].children[0].value=parseFloat(document.getElementById(new_id).children[1].children[1].children[0].value)+move_quantity;
			}else{
				var cloned=document.getElementById(data).cloneNode(true);		
				cloned.id=new_id;
				cloned.addEventListener("dragstart", shopOrder.productPackage_dragstart_handler);
				target.appendChild(cloned);	
			}
			//document.getElementById(data).children[1].children[1].innerHTML=(product_quantity-move_quantity);		
			document.getElementById(data).children[1].children[1].children[0].value=(product_quantity-move_quantity);		
		}else{
			if (document.getElementById(new_id)&&(document.getElementById(data).id!=new_id)){
				//document.getElementById(new_id).children[1].children[1].innerHTML=parseFloat(document.getElementById(new_id).children[1].children[1].innerHTML)+move_quantity;
				document.getElementById(new_id).children[1].children[1].children[0].value=parseFloat(document.getElementById(new_id).children[1].children[1].children[0].value)+move_quantity;				
				document.getElementById(data).remove();
			}else{
				document.getElementById(data).id=new_id;
				target.appendChild(document.getElementById(new_id));		
			}
		}
		var new_input_auantity=document.getElementById(new_id).children[1].children[1].children[0];
		new_input_auantity.disabled=false;
		new_input_auantity.addEventListener("change", shopOrder.productPackage_change_quantity);
		new_input_auantity.addEventListener("focus", shopOrder.productPackage_start_change_quantity);		 
		document.getElementById(new_id).children[0].children[0].style.display="";
		document.getElementById("shipping_packages_products").value=shopOrder.getPackagesProductsJson();
	}
	
	delete_from_return_package(t){
		t.style.display='none';
		var target=t.parentNode.parentNode;
		
		var new_id=target.id.substring(target.id.lastIndexOf("_")+1);
		if (document.getElementById('return_' + new_id)){		
			document.getElementById('return_' + new_id).querySelector('.product_quantity').value=parseFloat(document.getElementById('return_' + new_id).querySelector('.product_quantity').value)+parseFloat(target.querySelector('.product_quantity').value);
			target.remove();
		}else{			
			target.id='return_' + new_id;
			target.querySelector('.product_quantity').disabled=true;
			target.querySelector('.return_reason').setAttribute('name', '');
			target.querySelector('.return_product_quantity').setAttribute('name', '');
			target.querySelector('.customer_comment').setAttribute('name', '');
			target.querySelector('.admin_notice').setAttribute('name', '');
			target.querySelector('.return_package_product_id').setAttribute('name', '');
			target.setAttribute("name","");
			document.getElementById('returns_packages').appendChild(target);
		}
		//document.getElementById("returns_packages_products").value=this.getReturnPackagesProductsJson();
	}
	
	getReturnPackagesProductsJson(){
		const packages=document.getElementById("return_packages");	
		var products_list = new Array();
		var packs_json="{";
		for (let i = 0; i < packages.children.length; i++) {
			var products=packages.children[i].children[1].children[0];	
			var pack_id=packages.children[i].id.substring(11);		
			if (packs_json[packs_json.length-1]=='}') packs_json=packs_json+',';
			packs_json=packs_json+'"'+pack_id+'":{';		
			var products_array = new Array();
			for (let j = 0; j < products.children.length; j++) {					
				var id=products.children[j].id; 
				var product_id=id.substring(id.lastIndexOf("_")+2);
				if (product_id.length>0) {
					products_array[product_id]=parseFloat(products.querySelector('.product_quantity').value);
					if (packs_json[packs_json.length-1]!='{') packs_json=packs_json+',';				
					packs_json=packs_json+'"'+product_id+'":'+parseFloat(products.querySelector('.product_quantity').value);				
				}
				
			}
			
			
			
			packs_json=packs_json+"}";
			products_list[pack_id] = products_array;		
		}
		
		packs_json=packs_json+"}";
		return packs_json;
	}
	
	setSavedReturnPackages(e){
		let el = document.getElementById(e);
		if (el) {
			el.value = this.getReturnPackagesProductsJson();
		}
	}
	
	orderedit_return_package_add(txt){
		return_package_index++;
		let packages = document.getElementById('return_packages');

		if (packages) {
			if(packages.children[0]){
				var new_package = packages.children[0].cloneNode(true);	
				if (new_package) {
					new_package.id = 'return_pack'+return_package_index;
					new_package.querySelector('.return_package_id').value=return_package_index;
					new_package.querySelector('.return_package_status').setAttribute('name', 'return_package_status['+return_package_index+']');
					new_package.children[0].children[2].innerHTML=return_package_index;
					new_package.children[0].children[4].children[0].value="";
					new_package.children[1].innerHTML="<span>"+txt+"</span>";
					packages.appendChild(new_package);
				}
			}else{	
				var template = document.querySelector('#templateOfOrderReturnPack');
				var templateEl = template.content.cloneNode(true);
				var new_package = templateEl.querySelector('#return_pack1');
				new_package.id = 'return_pack'+return_package_index;
				new_package.querySelector('.return_package_id').value=return_package_index;
				new_package.querySelector('.return_package_status').setAttribute('name', 'return_package_status['+return_package_index+']');
				new_package.children[0].children[2].innerHTML=return_package_index;
				new_package.children[0].children[4].children[0].value="";
				new_package.children[1].innerHTML="<span>"+txt+"</span>";
				packages.appendChild(new_package);
			}
		}
	}
	
	productReturnPackage_dragover_handler(ev) {
		ev.preventDefault();
		ev.dataTransfer.dropEffect = "copy";
		ev.dataTransfer.effectAllowed = "copy";
	}  
	
	productReturnPackage_dragstart_handler(ev) {
		ev.dataTransfer.setData("text/html", ev.target.id);
		ev.dataTransfer.dropEffect = "copy";
		ev.dataTransfer.effectAllowed = "copy";
	}
	
	productReturnPackage_change_quantity(t=this){
		if (typeof(t.name) != 'undefined' && t != null) { 
		if (parseFloat(t.value)<0){t.value=0;}
		var product_id=t.id.substring(t.id.lastIndexOf("_")+1);
		var new_quantity=parseFloat(t.value);
		
		//PRODUCT EXIST IN NOT PACKAGES
		if (document.getElementById("return_p"+product_id)){
			var mainproduct=document.getElementById("return_p"+product_id).querySelector('.return_product_quantity');//alert(parseFloat(this.oldvalue)+parseFloat(mainproduct.value));
			if (parseFloat(t.value)>=parseFloat(t.oldvalue)+parseFloat(mainproduct.value)){ 
			//NEW QUANTITY >= TOTAL QUANTITY
				t.value=parseFloat(t.oldvalue)+parseFloat(mainproduct.value);
				mainproduct.value=0;
				document.getElementById("return_p"+product_id).remove();
			}else{
			//NEW QUANTITY < TOTAL QUANTITY
				mainproduct.value=(parseFloat(mainproduct.value)+parseFloat(t.oldvalue))-parseFloat(t.value);				
			}
		}else if(new_quantity<parseFloat(t.oldvalue)){ 
			var product=t.parentNode.parentNode.parentNode.cloneNode(true);
			product.addEventListener("dragstart", shopOrder.productReturnPackage_dragstart_handler);
			product.id="return_p"+product_id;
			product.children[1].children[1].children[0].disabled=true;
			product.children[0].children[0].style.display="none";
			product.children[1].children[1].children[0].value=t.oldvalue-t.value;
			document.getElementById('returns_packages').appendChild(product);
			if (new_quantity<=0){t.parentNode.parentNode.parentNode.remove();}
			
		}else{
			t.value=t.oldvalue;
		}
		var row = t.parentNode.parentNode.parentNode;
		if (parseFloat(t.value)<=0){
			row.remove();
		}
		}else{t.value=t.oldvalue;}
		t.oldvalue=t.value;
	}
	productReturnPackage_start_change_quantity(t=this){	
		t.oldvalue = t.value;
	}
	productReturnPackage_drop_handler(ev) {		
		ev.preventDefault();
				
		var target=ev.target;
		var data = ev.dataTransfer.getData("text/html");	
		
		if (data.indexOf('">')>0) {
			data=data.substring(data.lastIndexOf('">')+2);
		}		
		
		var product_quantity=parseFloat(document.getElementById(data).querySelector('.product_quantity').value);
		
		if(!target.id){
			var target=ev.target.parentNode;
		}
		var destination=target.id;
		while (target.id!='return_packages') {
			if (target.id.indexOf('ack')>0){
				destination=target.id;
			}
			target=target.parentNode;
		}
		target=document.getElementById(destination).children[1];
		
		if (document.getElementById(data).id.indexOf("_")>1){
			var new_id=target.parentNode.id+"_"+document.getElementById(data).id.substring(document.getElementById(data).id.lastIndexOf("_")+1);
		}else{
			var new_id=target.parentNode.parentNode.id+"_"+document.getElementById(data).id;
		}
		var str_ids = new_id.substring(new_id.indexOf("return_pack")+11);
		var pack_id = str_ids.substring(str_ids.indexOf("_"), 0);
		var pr_id = str_ids.substring(str_ids.indexOf("_")+2);
		
		if(document.getElementById(data).id==new_id){return;}
		//Set quantity
		var move_quantity=1;
		if(product_quantity>1){
			var move_quantity=parseFloat(prompt("Quantity"));
		}

		if (isNaN(move_quantity)) {move_quantity=product_quantity;}
		if (move_quantity>product_quantity) {move_quantity=product_quantity;}
		if (move_quantity<product_quantity){
		
			document.getElementById(data).querySelector('.product_quantity').value=(move_quantity);
			if (document.getElementById(new_id)){
				document.getElementById(new_id).querySelector('.return_product_quantity').value=parseFloat(document.getElementById(new_id).querySelector('.return_product_quantity').value)+move_quantity;
			}else{
				var cloned=document.getElementById(data).cloneNode(true);		
				cloned.id=new_id;
				cloned.addEventListener("dragstart", shopOrder.productReturnPackage_dragstart_handler);
				
				target.children[0].appendChild(cloned);	
			}	
			document.getElementById(data).querySelector('.product_quantity').value=(product_quantity-move_quantity);		
		}else{
			if (document.getElementById(new_id)&&(document.getElementById(data).id!=new_id)){
				document.getElementById(new_id).querySelector('.product_quantity').value=parseFloat(document.getElementById(new_id).querySelector('.product_quantity').value)+move_quantity;				
				document.getElementById(data).remove();
			}else{
				document.getElementById(data).id=new_id;
				target.children[0].appendChild(document.getElementById(new_id));		
			}
		}
		document.getElementById(new_id).querySelector('.return_reason').setAttribute("name","return_reason["+pack_id+"]["+pr_id+"]");
		document.getElementById(new_id).querySelector('.customer_comment').setAttribute("name","customer_comment["+pack_id+"]["+pr_id+"]");
		document.getElementById(new_id).querySelector('.admin_notice').setAttribute("name","admin_notice["+pack_id+"]["+pr_id+"]");
		document.getElementById(new_id).querySelector('.return_package_product_id').setAttribute("name","return_package_product_id["+pack_id+"]["+pr_id+"]");
		document.getElementById(new_id).querySelector('.return_product_quantity').setAttribute("name","return_product_quantity["+pack_id+"]["+pr_id+"]");
		
		document.getElementById(new_id).querySelectorAll('.d-none').forEach(function(el){
			el.classList.remove('d-none');
		});
		var new_input_auantity=document.getElementById(new_id).querySelector('.product_quantity');
		new_input_auantity.disabled=false;
		new_input_auantity.addEventListener("change", shopOrder.productReturnPackage_change_quantity);
		new_input_auantity.addEventListener("focus", shopOrder.productReturnPackage_start_change_quantity);		 
		document.getElementById(new_id).querySelector('.delete_package').style.display="";
	}
	
	delete_package(t, e, msg){
		e.preventDefault();
		if(!confirm(msg)) return false;	
		//t.style.display='none';
		var target=t.parentNode.parentNode.parentNode;
		var new_id=target.id;
		var pr_blocks = document.getElementById(new_id).querySelectorAll('.package_product_line');
		var del_butt = '';
		if(pr_blocks){
			pr_blocks.forEach(function (el) {
               del_butt = el.querySelector('.delete_package');
			   if(del_butt){
				   shopOrder.delete_from_package(del_butt);
			   }			   
            });
		}
		target.remove();
		document.getElementById("shipping_packages_products").value=this.getPackagesProductsJson();
	}

	
	delete_return_package(t, e, msg){
		e.preventDefault();
		if(!confirm(msg)) return false;	
		//t.style.display='none';
		var target=t.parentNode.parentNode.parentNode;
		var new_id=target.id;
		var pr_blocks = document.getElementById(new_id).querySelectorAll('.return_product_line');
		var del_butt = '';
		if(pr_blocks){
			pr_blocks.forEach(function (el) {
               del_butt = el.querySelector('.delete_package');
			   if(del_butt){
				   shopOrder.delete_from_return_package(del_butt);
			   }			   
            });
		}
		target.remove();
		//document.getElementById("returns_packages_products").value=this.getReturnPackagesProductsJson();		
	}
}

export default new ShopOrder();